<?php
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
    exit;
}

require_once __DIR__ . '/../../../app/Models/Berita.php';

$page = (int)($_POST['page'] ?? 1);
$search = trim($_POST['search'] ?? '');
$kategori = trim($_POST['kategori'] ?? '');
$status = trim($_POST['status'] ?? '');
$perPage = 10;

$items = Berita::all();

if ($search !== '') {
    $items = array_filter($items, fn($i) => 
        stripos($i['judul'] ?? '', $search) !== false || 
        stripos($i['ringkasan'] ?? '', $search) !== false
    );
}

if ($kategori !== '') {
    $items = array_filter($items, fn($i) => ($i['kategori'] ?? '') === $kategori);
}

if ($status !== '') {
    $items = array_filter($items, fn($i) => ($i['status'] ?? '') === $status);
}

$items = array_values($items);
$total = count($items);
$statTerbit = count(array_filter($items, fn($i) => ($i['status'] ?? '') === 'terbit'));
$statDraft  = $total - $statTerbit;
$paged = array_slice($items, ($page - 1) * $perPage, $perPage);
$paged = array_map(static function (array $item): array {
    $item['is_published'] = Berita::isPublished($item);
    $item['is_scheduled'] = Berita::isScheduled($item);
    return $item;
}, $paged);

echo json_encode([
    'success' => true,
    'data' => $paged,
    'page' => $page,
    'total' => $total,
    'stat_terbit' => $statTerbit,
    'stat_draft' => $statDraft,
    'has_next' => ($page * $perPage) < $total,
    'has_prev' => $page > 1,
]);
