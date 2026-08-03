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

$csrf = (string) ($_POST['csrf_token'] ?? '');
if ($csrf === '' || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrf)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Token keamanan tidak valid. Muat ulang halaman.']);
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

$nama = (string) ($item['nama'] ?? '');
if (!Umkm::delete($id)) {
    echo json_encode(['success' => false, 'message' => 'Gagal menghapus UMKM.']);
    exit;
}

echo json_encode([
    'success' => true,
    'message' => "UMKM '{$nama}' berhasil dihapus.",
], JSON_UNESCAPED_UNICODE);
