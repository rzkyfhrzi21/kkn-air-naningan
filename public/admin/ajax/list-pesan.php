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

$page = (int)($_POST['page'] ?? 1);
$search = trim($_POST['search'] ?? '');
$readFilter = trim($_POST['read_filter'] ?? 'all');
$perPage = 10;

$items = Pesan::all();

if ($search !== '') {
    $items = array_filter($items, fn($i) => 
        stripos($i['nama'] ?? '', $search) !== false || 
        stripos($i['pesan'] ?? '', $search) !== false
    );
}

if ($readFilter === 'unread') {
    $items = array_filter($items, static fn(array $i): bool => !($i['is_read'] ?? false));
} elseif ($readFilter === 'read') {
    $items = array_filter($items, static fn(array $i): bool => (bool) ($i['is_read'] ?? false));
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
