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

require_once __DIR__ . '/../../../app/Models/Potensi.php';

$id = trim($_POST['id'] ?? '');
$nama = trim($_POST['nama'] ?? '');
$kategori = trim($_POST['kategori'] ?? '');
$deskripsi = trim($_POST['deskripsi'] ?? '');
$foto = trim($_POST['foto'] ?? '');
$kapasitas = trim($_POST['kapasitas'] ?? '');
$status = trim($_POST['status'] ?? 'aktif');

if (empty($nama)) {
    echo json_encode(['success' => false, 'message' => 'Nama potensi wajib diisi.']);
    exit;
}

if (empty($kategori)) {
    echo json_encode(['success' => false, 'message' => 'Kategori potensi wajib dipilih.']);
    exit;
}

if (empty($deskripsi)) {
    echo json_encode(['success' => false, 'message' => 'Deskripsi potensi wajib diisi.']);
    exit;
}

$payload = [
    'nama' => $nama,
    'kategori' => $kategori,
    'deskripsi' => $deskripsi,
    'foto' => $foto,
    'kapasitas' => $kapasitas,
    'status' => $status,
];

if (empty($id)) {
    $item = Potensi::create($payload);
    echo json_encode([
        'success' => true,
        'message' => "Potensi '{$item['nama']}' berhasil ditambahkan.",
        'data' => $item
    ]);
} else {
    $item = Potensi::update($id, $payload);
    if ($item) {
        echo json_encode([
            'success' => true,
            'message' => "Potensi '{$item['nama']}' berhasil diperbarui.",
            'data' => $item
        ]);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Potensi tidak ditemukan.']);
    }
}
