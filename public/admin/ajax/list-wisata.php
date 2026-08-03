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

require_once __DIR__ . '/../../../app/Models/Wisata.php';

$page     = max(1, (int) ($_POST['page'] ?? 1));
$search   = trim((string) ($_POST['search'] ?? ''));
$kategori = trim((string) ($_POST['kategori'] ?? ''));
$perPage  = 10;

$items = Wisata::all();

if ($search !== '') {
    $items = array_filter($items, static fn($i) =>
        stripos((string) ($i['nama'] ?? ''), $search) !== false ||
        stripos((string) ($i['deskripsi'] ?? ''), $search) !== false
    );
}

if ($kategori !== '' && $kategori !== 'all') {
    $items = array_filter($items, static fn($i) => ($i['kategori'] ?? '') === $kategori);
}

$items = array_values($items);
$total = count($items);

// Statistik status (hitung dari seluruh data setelah filter, sebelum paginasi)
$statBuka  = count(array_filter($items, static fn($i) => ($i['status'] ?? 'buka') === 'buka'));
$statTutup = $total - $statBuka;

$paged = array_slice($items, ($page - 1) * $perPage, $perPage);

echo json_encode([
    'success'    => true,
    'data'       => $paged,
    'page'       => $page,
    'total'      => $total,
    'stat_buka'  => $statBuka,
    'stat_tutup' => $statTutup,
    'has_next'   => ($page * $perPage) < $total,
    'has_prev'   => $page > 1,
], JSON_UNESCAPED_UNICODE);
