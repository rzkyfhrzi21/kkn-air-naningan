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
if (empty($id)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID potensi tidak valid.']);
    exit;
}

$item = Potensi::find($id);
if (!$item) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Potensi tidak ditemukan.']);
    exit;
}

$deleted = Potensi::delete($id);
if ($deleted) {
    echo json_encode([
        'success' => true,
        'message' => "Potensi '{$item['nama']}' berhasil dihapus."
    ]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Gagal menghapus potensi.']);
}
