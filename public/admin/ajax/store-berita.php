<?php
session_start();
if (!isset($_SESSION['admin'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Sesi habis, silakan login ulang.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

$csrf = (string) ($_POST['csrf_token'] ?? '');
if ($csrf === '' || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrf)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Token keamanan tidak valid. Muat ulang halaman.']);
    exit;
}

require_once __DIR__ . '/../../../app/Models/Berita.php';

$id = trim($_POST['id'] ?? '');
$judul = trim($_POST['judul'] ?? '');
$kategori = trim($_POST['kategori'] ?? '');
$ringkasan = trim($_POST['ringkasan'] ?? '');
$konten = trim($_POST['konten'] ?? '');
$penulis = trim($_POST['penulis'] ?? 'Admin Desa');
$status = trim($_POST['status'] ?? 'draft');
$tanggal_terbit = trim($_POST['tanggal_terbit'] ?? date('Y-m-d'));
$tags = isset($_POST['tags']) ? array_filter(array_map('trim', explode(',', $_POST['tags']))) : [];

if (empty($judul)) {
    echo json_encode(['success' => false, 'message' => 'Judul berita wajib diisi.']);
    exit;
}

if (empty($kategori)) {
    echo json_encode(['success' => false, 'message' => 'Kategori berita wajib dipilih.']);
    exit;
}

if (empty($ringkasan)) {
    echo json_encode(['success' => false, 'message' => 'Ringkasan berita wajib diisi.']);
    exit;
}

if (empty($konten)) {
    echo json_encode(['success' => false, 'message' => 'Konten berita wajib diisi.']);
    exit;
}

$fotoPath  = '';
$fileErr   = $_FILES['foto_file']['error'] ?? UPLOAD_ERR_NO_FILE;

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
    $uploadDir = dirname(dirname(__DIR__)) . '/uploads/berita/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if (!move_uploaded_file($tmpFile, $uploadDir . $filename)) {
        echo json_encode(['success' => false, 'message' => 'Gagal menyimpan foto ke server.']);
        exit;
    }

    $scriptBase = rtrim(dirname(dirname(dirname($_SERVER['SCRIPT_NAME'] ?? ''))), '/');
    $scriptBase = ($scriptBase === '/') ? '' : $scriptBase;
    $fotoPath   = $scriptBase . '/uploads/berita/' . $filename;

} elseif ($fileErr !== UPLOAD_ERR_NO_FILE) {
    echo json_encode(['success' => false, 'message' => 'Upload gagal (kode: ' . $fileErr . ').']);
    exit;
} else {
    if ($id !== '') {
        $existing = Berita::find($id);
        $fotoPath = (string) ($existing['foto_sampul'] ?? '');
    }
}

$payload = [
    'judul' => $judul,
    'kategori' => $kategori,
    'ringkasan' => $ringkasan,
    'konten' => $konten,
    'foto_sampul' => $fotoPath,
    'penulis' => $penulis,
    'status' => $status,
    'tanggal_terbit' => $tanggal_terbit,
    'tags' => $tags,
];

if (empty($id)) {
    $item = Berita::create($payload);
    echo json_encode([
        'success' => true,
        'message' => "Berita '{$item['judul']}' berhasil ditambahkan.",
        'data' => $item
    ]);
} else {
    $item = Berita::update($id, $payload);
    if ($item) {
        echo json_encode([
            'success' => true,
            'message' => "Berita '{$item['judul']}' berhasil diperbarui.",
            'data' => $item
        ]);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Berita tidak ditemukan.']);
    }
}
