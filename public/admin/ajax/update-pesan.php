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
$status = trim($_POST['status'] ?? '');
$balasan = trim($_POST['balasan'] ?? '');

if (empty($id)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID pesan tidak valid.']);
    exit;
}

$payload = [];
if (!empty($status)) {
    $payload['status'] = $status;
}
if (!empty($balasan)) {
    $payload['balasan'] = $balasan;
}

if (empty($payload)) {
    echo json_encode(['success' => false, 'message' => 'Tidak ada data yang diperbarui.']);
    exit;
}

$item = Pesan::update($id, $payload);
if ($item) {
    echo json_encode([
        'success' => true,
        'message' => 'Status pesan berhasil diperbarui.',
        'data' => $item
    ]);
} else {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Pesan tidak ditemukan.']);
}
