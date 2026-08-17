<?php

declare(strict_types=1);

/* ======================================================
   ENDPOINT AJAX: SIMPAN / PERBARUI MEDIA GALERI (FORM KELOLA GALERI)

   File ini ibarat "petugas pengunggah album foto/video desa":
   saat admin menekan tombol "Simpan Media" (tambah foto/video baru atau edit media lama),
   halaman Kelola Galeri memanggil file ini lewat AJAX.

   Alur kerjanya:
   (1) Memeriksa login admin & token keamanan CSRF.
   (2) Mengambil input form (judul, deskripsi, kategori, tipe foto/video, rasio, urutan).
   (3) Validasi bidang wajib diisi & validasi kategori resmi.
   (4) Pengolahan upload file media ke `uploads/galeri/`:
       - Tipe Foto: maks 2MB, format JPG/PNG/GIF/WebP.
       - Tipe Video: maks 15MB, format MP4/WebM/MOV (MKV ditolak).
       - Hapus file lama jika diganti.
   (5) Jika ID kosong: panggil `Galeri::create()` untuk menambah media baru.
       Jika ID ada: panggil `Galeri::update()` untuk memperbarui media lama.
   (6) Mengirimkan balasan JSON berpesan rinci untuk notifikasi toast.
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

require_once __DIR__ . '/../../../app/Models/Galeri.php';

// (2) Ambil input dari form HTML admin
$id        = trim((string) ($_POST['id'] ?? ''));
$judul     = trim((string) ($_POST['judul'] ?? ''));
$deskripsi = trim((string) ($_POST['deskripsi'] ?? ''));
$kategori  = trim((string) ($_POST['kategori'] ?? ''));
$tipe      = trim((string) ($_POST['tipe'] ?? 'foto'));
$rasio     = trim((string) ($_POST['rasio'] ?? '100%'));
$urutan    = (int) ($_POST['urutan'] ?? 0);

// (3) Validasi bidang wajib & kategori
if ($judul === '') {
    echo json_encode(['success' => false, 'message' => 'Judul galeri wajib diisi.']);
    exit;
}
if ($kategori === '' || !array_key_exists($kategori, Galeri::KATEGORI)) {
    echo json_encode(['success' => false, 'message' => 'Kategori galeri wajib dipilih.']);
    exit;
}
if (!in_array($tipe, ['foto', 'video'], true)) {
    echo json_encode(['success' => false, 'message' => 'Tipe media harus foto atau video.']);
    exit;
}
if ($rasio === '' || !preg_match('/^\d{1,3}%$/', $rasio)) {
    $rasio = '100%';
}

$existing = $id !== '' ? Galeri::find($id) : null;
if ($id !== '' && $existing === null) {
    echo json_encode(['success' => false, 'message' => 'Galeri tidak ditemukan.']);
    exit;
}

// (4) Pengolahan Upload File Media (Foto / Video)
$filePath = '';
$fileErr  = $_FILES['media_file']['error'] ?? UPLOAD_ERR_NO_FILE;

if ($fileErr === UPLOAD_ERR_OK) {
    $tmpFile  = (string) $_FILES['media_file']['tmp_name'];
    $fileSize = (int) $_FILES['media_file']['size'];

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime  = (string) $finfo->file($tmpFile);

    $imageMimes = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/gif'  => 'gif',
        'image/webp' => 'webp',
    ];
    $videoMimes = [
        'video/mp4'        => 'mp4',
        'video/webm'       => 'webm',
        'video/quicktime'  => 'mov',
    ];

    if ($tipe === 'foto') {
        if ($fileSize > 2 * 1024 * 1024) {
            echo json_encode(['success' => false, 'message' => 'Ukuran foto maksimal 2MB.']);
            exit;
        }
        if (!array_key_exists($mime, $imageMimes)) {
            echo json_encode(['success' => false, 'message' => 'Format foto tidak valid. Gunakan JPG, PNG, GIF, atau WebP. HEIC/HEIF ditolak.']);
            exit;
        }
        $ext = $imageMimes[$mime];
    } else {
        if ($fileSize > 15 * 1024 * 1024) {
            echo json_encode(['success' => false, 'message' => 'Ukuran video maksimal 15MB.']);
            exit;
        }
        if (!array_key_exists($mime, $videoMimes)) {
            echo json_encode(['success' => false, 'message' => 'Format video tidak valid. Gunakan MP4, MOV, atau WebM. MKV ditolak.']);
            exit;
        }
        $ext = $videoMimes[$mime];
    }

    $filename  = bin2hex(random_bytes(16)) . '.' . $ext;
    $uploadDir = dirname(__DIR__, 2) . '/uploads/galeri/';
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
        echo json_encode(['success' => false, 'message' => 'Folder upload galeri tidak bisa dibuat.']);
        exit;
    }
    if (!move_uploaded_file($tmpFile, $uploadDir . $filename)) {
        echo json_encode(['success' => false, 'message' => 'Gagal menyimpan file media ke server.']);
        exit;
    }

    $scriptBase = defined('APP_BASE')
        ? rtrim((string) APP_BASE, '/')
        : rtrim(dirname(dirname(dirname($_SERVER['SCRIPT_NAME'] ?? ''))), '/');
    $scriptBase = $scriptBase === '/' ? '' : $scriptBase;
    $filePath   = $scriptBase . '/uploads/galeri/' . $filename;

    // Hapus file lama jika diganti
    $oldFile = (string) ($existing['file'] ?? '');
    if ($oldFile !== '' && !preg_match('#^https?://#i', $oldFile) && str_contains($oldFile, '/uploads/galeri/')) {
        $oldBase = basename(parse_url($oldFile, PHP_URL_PATH) ?: $oldFile);
        $oldFull = $uploadDir . $oldBase;
        if (is_file($oldFull)) {
            @unlink($oldFull);
        }
    }
} elseif ($fileErr !== UPLOAD_ERR_NO_FILE) {
    $messages = [
        UPLOAD_ERR_INI_SIZE   => 'File melebihi batas upload server (upload_max_filesize).',
        UPLOAD_ERR_FORM_SIZE  => 'File melebihi batas form.',
        UPLOAD_ERR_PARTIAL    => 'File hanya terunggah sebagian.',
        UPLOAD_ERR_NO_TMP_DIR => 'Folder temporary upload tidak tersedia.',
        UPLOAD_ERR_CANT_WRITE => 'Gagal menulis file ke disk.',
        UPLOAD_ERR_EXTENSION  => 'Upload dihentikan oleh ekstensi PHP.',
    ];
    echo json_encode(['success' => false, 'message' => $messages[$fileErr] ?? ('Upload gagal (kode: ' . $fileErr . ').')]);
    exit;
} else {
    $filePath = (string) ($existing['file'] ?? '');
    if ($id === '' && $filePath === '') {
        echo json_encode(['success' => false, 'message' => 'File media wajib diunggah untuk item baru.']);
        exit;
    }
    if ($id !== '' && $filePath === '') {
        echo json_encode(['success' => false, 'message' => 'Media galeri belum memiliki file. Unggah file baru.']);
        exit;
    }
}

// (5) Rangkai paket data baru untuk disimpan
$payload = [
    'judul'     => $judul,
    'deskripsi' => $deskripsi,
    'kategori'  => $kategori,
    'tipe'      => $tipe,
    'file'      => $filePath,
    'rasio'     => $rasio,
    'urutan'    => $urutan > 0 ? $urutan : null,
];

// (6) Eksekusi simpan baru (create) atau perbarui lama (update)
try {
    if ($id !== '') {
        if ($payload['urutan'] === null) {
            unset($payload['urutan']);
        }
        $item = Galeri::update($id, $payload);
        if ($item === null) {
            echo json_encode(['success' => false, 'message' => 'Galeri tidak ditemukan.']);
            exit;
        }
        echo json_encode([
            'success' => true,
            'message' => "Galeri '{$item['judul']}' berhasil diperbarui.",
            'data'    => $item,
        ], JSON_UNESCAPED_UNICODE);
    } else {
        if ($payload['urutan'] === null) {
            unset($payload['urutan']);
        }
        $item = Galeri::create($payload);
        echo json_encode([
            'success' => true,
            'message' => "Galeri '{$item['judul']}' berhasil ditambahkan.",
            'data'    => $item,
        ], JSON_UNESCAPED_UNICODE);
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Gagal menyimpan: ' . $e->getMessage()]);
}

