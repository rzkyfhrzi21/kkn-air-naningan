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
if (empty($id)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID galeri tidak valid.']);
    exit;
}

$item = Galeri::find($id);
if (!$item) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Galeri tidak ditemukan.']);
    exit;
}

$deleted = Galeri::delete($id);
if ($deleted) {
    echo json_encode([
        'success' => true,
        'message' => "Galeri '{$item['judul']}' berhasil dihapus."
    ]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Gagal menghapus galeri.']);
}
