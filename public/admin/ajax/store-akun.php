<?php

declare(strict_types=1);

/* ======================================================
   ENDPOINT AJAX: SIMPAN AKUN ADMIN (FORM EDIT PROFIL AKUN ADMIN)

   File ini ibarat "petugas pendaftaran ulang pegawai":
   saat admin mengubah nama lengkap, username, nomor WhatsApp, email,
   foto profil, atau password pada halaman Profil Admin, file ini dipanggil via AJAX.

   Alur kerjanya:
   (1) Memeriksa sesi login admin & token keamanan CSRF.
   (2) Mengambil data kredensial akun admin dari Model Akun.
   (3) Validasi input: Nama lengkap, Username (format & panjang), WhatsApp (format 62), Email.
   (4) Validasi perubahan password (jika diisi: password lama, password baru >= 8 karakter, konfirmasi).
   (5) Proses upload foto profil ke folder `uploads/admin/` (validasi MIME & hapus foto lama).
   (6) Memperbarui file JSON `secure/admin_credentials.json` via Model Akun.
   (7) Mengirimkan balasan JSON sukses/gagal.
====================================================== */

header('Content-Type: application/json; charset=utf-8');

// (1) Cek Sesi Admin & Metode POST
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['admin'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Sesi habis, silakan login ulang.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Metode tidak diizinkan.']);
    exit;
}

// Cek Token Keamanan CSRF
$csrf = (string) ($_POST['csrf_token'] ?? '');
if ($csrf === '' || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrf)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Token keamanan tidak valid. Muat ulang halaman.']);
    exit;
}

require_once __DIR__ . '/../../../app/Models/Akun.php';

// (2) Ambil data kredensial saat ini dari Model Akun
$creds = Akun::get();
if ($creds === []) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'File kredensial admin tidak ditemukan.']);
    exit;
}

// (3) Validasi Nama Lengkap & Username
$namaLengkap = trim((string) ($_POST['nama_lengkap'] ?? ''));
if ($namaLengkap === '') {
    echo json_encode(['success' => false, 'message' => 'Nama lengkap wajib diisi.']);
    exit;
}
if (mb_strlen($namaLengkap) > 80) {
    echo json_encode(['success' => false, 'message' => 'Nama lengkap maksimal 80 karakter.']);
    exit;
}

$username = trim((string) ($_POST['username'] ?? ''));
if ($username === '') {
    echo json_encode(['success' => false, 'message' => 'Username wajib diisi.']);
    exit;
}
if (!preg_match('/^[a-zA-Z0-9_.-]{3,32}$/', $username)) {
    echo json_encode(['success' => false, 'message' => 'Username hanya boleh huruf, angka, titik, garis bawah, strip — 3–32 karakter.']);
    exit;
}

// Validasi & Format WhatsApp (dirapikan ke 62)
$whatsappRaw = preg_replace('/\D/', '', (string) ($_POST['whatsapp'] ?? ''));
if ($whatsappRaw === '') {
    $whatsapp = '';
} elseif (preg_match('/^0(\d{8,13})$/', $whatsappRaw, $m)) {
    $whatsapp = '62' . $m[1];
} elseif (preg_match('/^62(\d{8,13})$/', $whatsappRaw, $m)) {
    $whatsapp = '62' . $m[1];
} elseif (preg_match('/^8\d{8,13}$/', $whatsappRaw)) {
    $whatsapp = '62' . $whatsappRaw;
} else {
    echo json_encode(['success' => false, 'message' => 'Nomor WhatsApp tidak valid. Gunakan 8–14 digit (tanpa awalan 0 atau +62).']);
    exit;
}

// Validasi Email
$email = trim((string) ($_POST['email'] ?? ''));
if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Alamat email tidak valid.']);
    exit;
}

// (4) Validasi Penggantian Password (opsional)
$currentPass = (string) ($_POST['password_saat_ini'] ?? '');
$newPass     = (string) ($_POST['password_baru'] ?? '');
$confirmPass = (string) ($_POST['konfirmasi_password'] ?? '');
$passwordHash = null;

if ($newPass !== '' || $confirmPass !== '' || $currentPass !== '') {
    if ($currentPass === '' || $newPass === '' || $confirmPass === '') {
        echo json_encode(['success' => false, 'message' => 'Isi password saat ini, password baru, dan konfirmasi untuk mengganti password.']);
        exit;
    }
    if (!password_verify($currentPass, (string) ($creds['password_hash'] ?? ''))) {
        echo json_encode(['success' => false, 'message' => 'Password saat ini salah.']);
        exit;
    }
    if (strlen($newPass) < 8) {
        echo json_encode(['success' => false, 'message' => 'Password baru minimal 8 karakter.']);
        exit;
    }
    if (!hash_equals($newPass, $confirmPass)) {
        echo json_encode(['success' => false, 'message' => 'Konfirmasi password baru tidak cocok.']);
        exit;
    }
    $passwordHash = password_hash($newPass, PASSWORD_DEFAULT);
}

// (5) Pengolahan File Upload Foto Profil (Maks 2MB, simpan di uploads/admin/)
$foto = (string) ($creds['foto'] ?? '');
$fileErr = $_FILES['foto_profil']['error'] ?? UPLOAD_ERR_NO_FILE;

if ($fileErr === UPLOAD_ERR_OK) {
    $tmpFile  = (string) $_FILES['foto_profil']['tmp_name'];
    $fileSize = (int) $_FILES['foto_profil']['size'];

    if ($fileSize > 2 * 1024 * 1024) {
        echo json_encode(['success' => false, 'message' => 'Ukuran foto profil maksimal 2MB.']);
        exit;
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime  = (string) $finfo->file($tmpFile);
    $ext   = match ($mime) {
        'image/jpeg'             => 'jpg',
        'image/png'              => 'png',
        'image/gif'              => 'gif',
        'image/webp'             => 'webp',
        default                  => null,
    };
    if ($ext === null) {
        echo json_encode(['success' => false, 'message' => 'Format foto tidak didukung. Gunakan JPG, PNG, GIF, atau WebP.']);
        exit;
    }

    $uploadDir = dirname(__DIR__, 2) . '/uploads/admin/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    $filename = bin2hex(random_bytes(16)) . '.' . $ext;
    if (!move_uploaded_file($tmpFile, $uploadDir . $filename)) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Gagal menyimpan foto profil.']);
        exit;
    }

    // Hapus foto lama jika diganti
    if ($foto !== '' && str_contains($foto, '/uploads/admin/')) {
        $oldPath = dirname(__DIR__, 2) . '/' . str_replace('\\', '/', $foto);
        if (is_file($oldPath)) {
            @unlink($oldPath);
        }
    }
    $foto = 'uploads/admin/' . $filename;

} elseif ($fileErr !== UPLOAD_ERR_NO_FILE) {
    $errMap = [
        UPLOAD_ERR_INI_SIZE   => 'File melebihi batas upload server (upload_max_filesize).',
        UPLOAD_ERR_PARTIAL    => 'Upload foto gagal di tengah proses.',
        UPLOAD_ERR_NO_TMP_DIR => 'Folder upload sementara server tidak tersedia.',
        UPLOAD_ERR_CANT_WRITE => 'Server gagal menulis file upload.',
    ];
    echo json_encode(['success' => false, 'message' => $errMap[$fileErr] ?? 'Gagal mengunggah foto profil.']);
    exit;
}

// Hapus foto profil jika opsi hapus dipilih
if ($fileErr === UPLOAD_ERR_NO_FILE && ($_POST['hapus_foto'] ?? '') === '1') {
    if ($foto !== '' && str_contains($foto, '/uploads/admin/')) {
        $oldPath = dirname(__DIR__, 2) . '/' . str_replace('\\', '/', $foto);
        if (is_file($oldPath)) {
            @unlink($oldPath);
        }
    }
    $foto = '';
}

// (6) Rangkai paket data baru & update via Model Akun
$payload = [
    'nama_lengkap' => $namaLengkap,
    'username'     => $username,
    'whatsapp'     => $whatsapp,
    'email'        => $email,
    'foto'         => $foto,
];
if ($passwordHash !== null) {
    $payload['password_hash'] = $passwordHash;
}

try {
    $saved = Akun::update($payload);
    $_SESSION['admin_username']    = $username;
    $_SESSION['admin_nama_lengkap'] = $namaLengkap;
    
    // (7) Kirim respon JSON sukses
    echo json_encode([
        'success' => true,
        'message' => 'Akun admin berhasil diperbarui.',
        'data'    => [
            'nama_lengkap' => $saved['nama_lengkap'] ?? '',
            'username'     => $saved['username'] ?? '',
            'whatsapp'     => $saved['whatsapp'] ?? '',
            'email'        => $saved['email'] ?? '',
            'foto'         => $saved['foto'] ?? '',
        ],
    ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Gagal menyimpan akun: ' . $e->getMessage()]);
}

