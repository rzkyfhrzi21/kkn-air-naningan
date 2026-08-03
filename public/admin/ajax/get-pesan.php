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

require_once __DIR__ . '/../../../app/Models/Pesan.php';

$id = trim($_POST['id'] ?? '');
if (empty($id)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID pesan tidak valid.']);
    exit;
}

$item = Pesan::find($id);
if (!$item) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Pesan tidak ditemukan.']);
    exit;
}

echo json_encode(['success' => true, 'data' => $item]);
