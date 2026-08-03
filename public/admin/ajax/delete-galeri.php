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

require_once __DIR__ . '/../../../app/Models/Galeri.php';

$ids = [];
if (isset($_POST['ids']) && is_array($_POST['ids'])) {
    foreach ($_POST['ids'] as $raw) {
        $id = trim((string) $raw);
        if ($id !== '') {
            $ids[] = $id;
        }
    }
} else {
    $id = trim((string) ($_POST['id'] ?? ''));
    if ($id !== '') {
        $ids[] = $id;
    }
}
$ids = array_values(array_unique($ids));

if ($ids === []) {
    echo json_encode(['success' => false, 'message' => 'ID galeri wajib diisi.']);
    exit;
}

$uploadRoot = realpath(dirname(__DIR__, 2) . '/uploads/galeri') ?: '';
$deletedTitles = [];
$deletedCount  = 0;

foreach ($ids as $id) {
    $item = Galeri::find($id);
    if ($item === null) {
        continue;
    }

    $file = (string) ($item['file'] ?? '');
    if ($file !== '' && !preg_match('#^https?://#i', $file) && str_contains($file, '/uploads/galeri/')) {
        $basename = basename(parse_url($file, PHP_URL_PATH) ?: $file);
        $fullPath = dirname(__DIR__, 2) . '/uploads/galeri/' . $basename;
        if (is_file($fullPath)) {
            $real = realpath($fullPath);
            if ($real !== false && ($uploadRoot === '' || str_starts_with($real, $uploadRoot))) {
                @unlink($real);
            }
        }
    }

    if (Galeri::delete($id)) {
        $deletedCount++;
        $deletedTitles[] = (string) ($item['judul'] ?? $id);
    }
}

if ($deletedCount === 0) {
    echo json_encode(['success' => false, 'message' => 'Tidak ada media galeri yang dihapus.']);
    exit;
}

if ($deletedCount === 1) {
    $title = $deletedTitles[0] ?? 'Media';
    echo json_encode([
        'success' => true,
        'message' => "Galeri '{$title}' berhasil dihapus.",
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

echo json_encode([
    'success' => true,
    'message' => "{$deletedCount} media galeri berhasil dihapus.",
], JSON_UNESCAPED_UNICODE);
