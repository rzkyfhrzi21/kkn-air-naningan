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

$page     = max(1, (int) ($_POST['page'] ?? 1));
$search   = trim((string) ($_POST['search'] ?? ''));
$kategori = trim((string) ($_POST['kategori'] ?? ''));
$perPage  = 10;

$items = Umkm::all();
if ($search !== '') {
    $items = array_filter($items, static function (array $i) use ($search): bool {
        return stripos((string) ($i['nama'] ?? ''), $search) !== false
            || stripos((string) ($i['pemilik'] ?? ''), $search) !== false
            || stripos((string) ($i['usaha'] ?? ''), $search) !== false;
    });
}
if ($kategori !== '' && $kategori !== 'all') {
    $items = array_filter($items, static fn(array $i): bool => ($i['kategori'] ?? '') === $kategori);
}
$items = array_values($items);
$total = count($items);
$statAktif = count(array_filter($items, static fn(array $i): bool => ($i['status'] ?? 'aktif') === 'aktif'));
$statFeatured = count(array_filter($items, static fn(array $i): bool => !empty($i['is_featured'])));
$statKategori = array_fill_keys(array_values(Umkm::KATEGORI), 0);
foreach ($items as $item) {
    $label = (string) ($item['kategori_label'] ?? Umkm::KATEGORI[$item['kategori'] ?? ''] ?? 'Lainnya');
    $statKategori[$label] = ($statKategori[$label] ?? 0) + 1;
}
$paged = array_slice($items, ($page - 1) * $perPage, $perPage);

echo json_encode([
    'success'  => true,
    'data'     => $paged,
    'page'     => $page,
    'total'    => $total,
    'stat_aktif' => $statAktif,
    'stat_nonaktif' => $total - $statAktif,
    'stat_featured' => $statFeatured,
    'stat_kategori' => $statKategori,
    'has_next' => ($page * $perPage) < $total,
    'has_prev' => $page > 1,
], JSON_UNESCAPED_UNICODE);
