<?php

header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Sesi habis, silakan login ulang.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Metode tidak diizinkan.']);
    exit;
}

require_once __DIR__ . '/../../../app/Models/Pesan.php';

$id = trim($_POST['id'] ?? '');

if (empty($id)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID pesan tidak valid.']);
    exit;
}

if (!isset($_POST['is_read']) || !in_array((string) $_POST['is_read'], ['0', '1'], true)) {
    echo json_encode(['success' => false, 'message' => 'Tidak ada data yang diperbarui.']);
    exit;
}

$item = Pesan::update($id, ['is_read' => $_POST['is_read'] === '1']);
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
