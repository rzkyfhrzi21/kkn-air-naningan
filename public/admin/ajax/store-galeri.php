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

require_once __DIR__ . '/../../../app/Models/Galeri.php';

$id = trim($_POST['id'] ?? '');
$judul = trim($_POST['judul'] ?? '');
$deskripsi = trim($_POST['deskripsi'] ?? '');
$kategori = trim($_POST['kategori'] ?? '');
$tipe = trim($_POST['tipe'] ?? 'foto');
$file = trim($_POST['file'] ?? '');
$rasio = trim($_POST['rasio'] ?? '100%');
$urutan = (int)($_POST['urutan'] ?? 0);

if (empty($judul)) {
    echo json_encode(['success' => false, 'message' => 'Judul galeri wajib diisi.']);
    exit;
}

if (empty($kategori)) {
    echo json_encode(['success' => false, 'message' => 'Kategori galeri wajib dipilih.']);
    exit;
}

if (empty($file)) {
    echo json_encode(['success' => false, 'message' => 'URL file (foto/video) wajib diisi.']);
    exit;
}

$payload = [
    'judul' => $judul,
    'deskripsi' => $deskripsi,
    'kategori' => $kategori,
    'tipe' => $tipe,
    'file' => $file,
    'rasio' => $rasio,
    'urutan' => $urutan,
];

if (empty($id)) {
    $item = Galeri::create($payload);
    echo json_encode([
        'success' => true,
        'message' => "Galeri '{$item['judul']}' berhasil ditambahkan.",
        'data' => $item
    ]);
} else {
    $item = Galeri::update($id, $payload);
    if ($item) {
        echo json_encode([
            'success' => true,
            'message' => "Galeri '{$item['judul']}' berhasil diperbarui.",
            'data' => $item
        ]);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Galeri tidak ditemukan.']);
    }
}
