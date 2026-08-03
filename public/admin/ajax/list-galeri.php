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

require_once __DIR__ . '/../../../app/Models/Galeri.php';

$page     = max(1, (int) ($_POST['page'] ?? 1));
$search   = trim((string) ($_POST['search'] ?? ''));
$kategori = trim((string) ($_POST['kategori'] ?? ''));
$sort     = trim((string) ($_POST['sort'] ?? 'newest'));
$perPage  = 12;

$items = Galeri::all();
$statTotal = count($items);
$statFoto  = count(array_filter($items, static fn(array $i): bool => ($i['tipe'] ?? 'foto') === 'foto'));
$statVideo = $statTotal - $statFoto;

if ($search !== '') {
    $items = array_values(array_filter($items, static function (array $i) use ($search): bool {
        return stripos((string) ($i['judul'] ?? ''), $search) !== false
            || stripos((string) ($i['deskripsi'] ?? ''), $search) !== false;
    }));
}

if ($kategori !== '' && $kategori !== 'all') {
    $items = array_values(array_filter($items, static fn(array $i): bool => ($i['kategori'] ?? '') === $kategori));
}

usort($items, static function (array $a, array $b) use ($sort): int {
    $ta = strtotime((string) ($a['created_at'] ?? '')) ?: 0;
    $tb = strtotime((string) ($b['created_at'] ?? '')) ?: 0;
    if ($ta === $tb) {
        return ((int) ($a['urutan'] ?? 0)) <=> ((int) ($b['urutan'] ?? 0));
    }
    return $sort === 'oldest' ? ($ta <=> $tb) : ($tb <=> $ta);
});

$total = count($items);
$paged = array_slice($items, ($page - 1) * $perPage, $perPage);

echo json_encode([
    'success'    => true,
    'data'       => $paged,
    'page'       => $page,
    'total'      => $total,
    'stat_total' => $statTotal,
    'stat_foto'  => $statFoto,
    'stat_video' => $statVideo,
    'has_next'   => ($page * $perPage) < $total,
    'has_prev'   => $page > 1,
], JSON_UNESCAPED_UNICODE);
