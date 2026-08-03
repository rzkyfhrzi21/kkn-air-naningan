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
if (empty($id)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID berita tidak valid.']);
    exit;
}

$item = Berita::find($id);
if (!$item) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Berita tidak ditemukan.']);
    exit;
}

echo json_encode(['success' => true, 'data' => $item]);
