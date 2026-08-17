<?php

declare(strict_types=1);

/* ======================================================
   ENDPOINT AJAX: SIMPAN / PERBARUI DATA UMKM (FORM KELOLA UMKM)

   File ini ibarat "petugas pendaftaran usaha warga":
   saat admin menekan tombol "Simpan UMKM" (tambah usaha baru atau edit data usaha lama),
   halaman Kelola UMKM memanggil file ini lewat AJAX.

   Alur kerjanya:
   (1) Memeriksa login admin & token keamanan CSRF.
   (2) Validasi nama usaha (wajib) & format nomor WhatsApp (diawali 0 atau 62).
   (3) Pengolahan upload foto produk/usaha ke `uploads/umkm/` (maks 2MB, format JPG/PNG/GIF/WebP).
   (4) Menyiapkan array payload (nama, jenis usaha, kategori, deskripsi, pemilik, dusun, no_wa, status, is_featured).
   (5) Jika ID kosong: panggil `Umkm::create()` untuk menambah data usaha baru.
       Jika ID ada: panggil `Umkm::update()` untuk memperbarui data usaha lama.
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

require_once __DIR__ . '/../../../app/Models/Umkm.php';

// (2) Validasi Nama Usaha & Nomor WhatsApp
$nama = trim((string) ($_POST['nama'] ?? ''));
if ($nama === '') {
    echo json_encode(['success' => false, 'message' => 'Nama produk/usaha wajib diisi.']);
    exit;
}

$wa = preg_replace('/\D+/', '', (string) ($_POST['no_wa'] ?? '')) ?? '';
if ($wa !== '' && !str_starts_with($wa, '62') && !str_starts_with($wa, '0')) {
    echo json_encode(['success' => false, 'message' => 'Nomor WhatsApp harus diawali 62 atau 0.']);
    exit;
}

// (3) Pengolahan Upload File Foto Produk/Usaha (Maks 2MB)
$fotoPath  = '';
$fileErr   = $_FILES['foto_file']['error'] ?? UPLOAD_ERR_NO_FILE;
$id        = trim((string) ($_POST['id'] ?? ''));

if ($fileErr === UPLOAD_ERR_OK) {
    $tmpFile  = $_FILES['foto_file']['tmp_name'];
    $fileSize = $_FILES['foto_file']['size'];

    if ($fileSize > 2 * 1024 * 1024) {
        echo json_encode(['success' => false, 'message' => 'Ukuran foto maksimal 2MB.']);
        exit;
    }

    $finfo   = new finfo(FILEINFO_MIME_TYPE);
    $mime    = $finfo->file($tmpFile);
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];

    if (!array_key_exists($mime, $allowed)) {
        echo json_encode(['success' => false, 'message' => 'Format foto tidak valid. Gunakan JPG, PNG, GIF, atau WebP.']);
        exit;
    }

    $ext       = $allowed[$mime];
    $filename  = bin2hex(random_bytes(16)) . '.' . $ext;
    $uploadDir = dirname(dirname(__DIR__)) . '/uploads/umkm/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if (!move_uploaded_file($tmpFile, $uploadDir . $filename)) {
        echo json_encode(['success' => false, 'message' => 'Gagal menyimpan foto ke server.']);
        exit;
    }

    $scriptBase = rtrim(dirname(dirname(dirname($_SERVER['SCRIPT_NAME'] ?? ''))), '/');
    $scriptBase = ($scriptBase === '/') ? '' : $scriptBase;
    $fotoPath   = $scriptBase . '/uploads/umkm/' . $filename;

} elseif ($fileErr !== UPLOAD_ERR_NO_FILE) {
    echo json_encode(['success' => false, 'message' => 'Upload gagal (kode: ' . $fileErr . ').']);
    exit;
} else {
    if ($id !== '') {
        $existing = Umkm::find($id);
        $fotoPath = (string) ($existing['foto'] ?? '');
    }
}

// (4) Rangkai array payload untuk disimpan
$payload = [
    'nama'        => $nama,
    'usaha'       => trim((string) ($_POST['usaha'] ?? '')),
    'kategori'    => trim((string) ($_POST['kategori'] ?? '')),
    'deskripsi'   => trim((string) ($_POST['deskripsi'] ?? '')),
    'pemilik'     => trim((string) ($_POST['pemilik'] ?? '')),
    'dusun'       => trim((string) ($_POST['dusun'] ?? '')),
    'no_wa'       => $wa,
    'foto'        => $fotoPath,
    'status'      => trim((string) ($_POST['status'] ?? 'aktif')),
    'is_featured' => !empty($_POST['is_featured']),
];

// (5) Eksekusi simpan baru (create) atau perbarui lama (update)
try {
    if ($id !== '') {
        $item = Umkm::update($id, $payload);
        if ($item === null) {
            echo json_encode(['success' => false, 'message' => 'UMKM tidak ditemukan.']);
            exit;
        }
        echo json_encode([
            'success' => true,
            'message' => "UMKM '{$item['nama']}' berhasil diperbarui.",
            'data'    => $item,
        ], JSON_UNESCAPED_UNICODE);
    } else {
        $item = Umkm::create($payload);
        echo json_encode([
            'success' => true,
            'message' => "UMKM '{$item['nama']}' berhasil ditambahkan.",
            'data'    => $item,
        ], JSON_UNESCAPED_UNICODE);
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Gagal menyimpan: ' . $e->getMessage()]);
}

