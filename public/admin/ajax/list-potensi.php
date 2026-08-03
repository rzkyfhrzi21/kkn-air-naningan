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

$page = (int)($_POST['page'] ?? 1);
$search = trim($_POST['search'] ?? '');
$kategori = trim($_POST['kategori'] ?? '');
$perPage = 10;

$items = Potensi::all();

if ($search !== '') {
    $items = array_filter($items, fn($i) => 
        stripos($i['nama'] ?? '', $search) !== false || 
        stripos($i['deskripsi'] ?? '', $search) !== false
    );
}

if ($kategori !== '') {
    $items = array_filter($items, fn($i) => ($i['kategori'] ?? '') === $kategori);
}

$items = array_values($items);
$total = count($items);
$paged = array_slice($items, ($page - 1) * $perPage, $perPage);

echo json_encode([
    'success' => true,
    'data' => $paged,
    'page' => $page,
    'total' => $total,
    'has_next' => ($page * $perPage) < $total,
    'has_prev' => $page > 1,
]);
