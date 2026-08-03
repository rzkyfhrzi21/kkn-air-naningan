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

require_once __DIR__ . '/../../../app/Models/Berita.php';

$id = trim($_POST['id'] ?? '');
$judul = trim($_POST['judul'] ?? '');
$kategori = trim($_POST['kategori'] ?? '');
$ringkasan = trim($_POST['ringkasan'] ?? '');
$konten = trim($_POST['konten'] ?? '');
$foto_sampul = trim($_POST['foto_sampul'] ?? '');
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

$payload = [
    'judul' => $judul,
    'kategori' => $kategori,
    'ringkasan' => $ringkasan,
    'konten' => $konten,
    'foto_sampul' => $foto_sampul,
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
