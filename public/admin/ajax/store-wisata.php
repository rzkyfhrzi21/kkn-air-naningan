<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

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

$csrf = (string) ($_POST['csrf_token'] ?? '');
if ($csrf === '' || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrf)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Token keamanan tidak valid. Muat ulang halaman.']);
    exit;
}

require_once __DIR__ . '/../../../app/Models/Wisata.php';

$id        = trim((string) ($_POST['id'] ?? ''));
$nama      = trim((string) ($_POST['nama'] ?? ''));
$kategori  = trim((string) ($_POST['kategori'] ?? ''));
$deskripsi = trim((string) ($_POST['deskripsi'] ?? ''));
$jarak     = trim((string) ($_POST['jarak'] ?? ''));
$fasilitas = $_POST['fasilitas'] ?? [];   // array [{icon,label}]
$maps_url  = trim((string) ($_POST['maps_url'] ?? ''));
$status    = trim((string) ($_POST['status'] ?? 'buka'));
$offset    = !empty($_POST['offset']);

if ($nama === '') {
    echo json_encode(['success' => false, 'message' => 'Nama wisata wajib diisi.']);
    exit;
}
if ($kategori === '') {
    echo json_encode(['success' => false, 'message' => 'Kategori wisata wajib dipilih.']);
    exit;
}
if ($deskripsi === '') {
    echo json_encode(['success' => false, 'message' => 'Deskripsi wisata wajib diisi.']);
    exit;
}

// ── Handle upload foto ──────────────────────────────────────────────────────
$fotoPath = '';
$fileErr  = $_FILES['foto_file']['error'] ?? UPLOAD_ERR_NO_FILE;

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
    $uploadDir = dirname(dirname(__DIR__)) . '/uploads/wisata/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if (!move_uploaded_file($tmpFile, $uploadDir . $filename)) {
        echo json_encode(['success' => false, 'message' => 'Gagal menyimpan foto ke server.']);
        exit;
    }

    // Use the front controller base path when the app runs in a subdirectory.
    $scriptBase = defined('APP_BASE')
        ? rtrim((string) APP_BASE, '/')
        : rtrim(dirname(dirname(dirname($_SERVER['SCRIPT_NAME'] ?? ''))), '/');
    $scriptBase = $scriptBase === '/' ? '' : $scriptBase;
    $fotoPath   = $scriptBase . '/uploads/wisata/' . $filename;

} elseif ($fileErr !== UPLOAD_ERR_NO_FILE) {
    echo json_encode(['success' => false, 'message' => 'Upload gagal (kode: ' . $fileErr . ').']);
    exit;
} else {
    // Tidak ada file baru → preserve foto lama saat edit
    if ($id !== '') {
        $existing = Wisata::find($id);
        $fotoPath = (string) ($existing['foto'] ?? '');
    }
}
// ────────────────────────────────────────────────────────────────────────────

$payload = [
    'nama'      => $nama,
    'kategori'  => $kategori,
    'deskripsi' => $deskripsi,
    'jarak'     => $jarak,
    'foto'      => $fotoPath,
    'fasilitas' => $fasilitas,
    'maps_url'  => $maps_url !== '' ? $maps_url : 'https://maps.google.com',
    'status'    => $status,
    'offset'    => $offset,
];


try {
    if ($id !== '') {
        $item = Wisata::update($id, $payload);
        if ($item === null) {
            echo json_encode(['success' => false, 'message' => 'Wisata tidak ditemukan.']);
            exit;
        }
        echo json_encode([
            'success' => true,
            'message' => "Wisata '{$item['nama']}' berhasil diperbarui.",
            'data'    => $item,
        ], JSON_UNESCAPED_UNICODE);
    } else {
        $item = Wisata::create($payload);
        echo json_encode([
            'success' => true,
            'message' => "Wisata '{$item['nama']}' berhasil ditambahkan.",
            'data'    => $item,
        ], JSON_UNESCAPED_UNICODE);
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Gagal menyimpan: ' . $e->getMessage()]);
}
