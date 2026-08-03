<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['admin'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Sesi habis, silakan login ulang.']);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Metode tidak diizinkan.']);
    exit;
}

require_once __DIR__ . '/../../../app/Models/Umkm.php';

$id = trim((string) ($_POST['id'] ?? ''));
if ($id === '') {
    echo json_encode(['success' => false, 'message' => 'ID UMKM wajib diisi.']);
    exit;
}

$item = Umkm::find($id);
if ($item === null) {
    echo json_encode(['success' => false, 'message' => 'UMKM tidak ditemukan.']);
    exit;
}

echo json_encode([
    'success' => true,
    'message' => 'Data dimuat.',
    'data'    => $item,
], JSON_UNESCAPED_UNICODE);
