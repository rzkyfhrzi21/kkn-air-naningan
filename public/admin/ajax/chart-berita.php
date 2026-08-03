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

$kategori = [];
$status = ['terbit' => 0, 'draft' => 0];

foreach (Berita::all() as $berita) {
    $kat = trim((string)($berita['kategori'] ?? ''));
    if ($kat === '') {
        $kat = 'Tanpa Kategori';
    }
    $kategori[$kat] = ($kategori[$kat] ?? 0) + 1;

    $st = ($berita['status'] ?? '') === 'terbit' ? 'terbit' : 'draft';
    $status[$st]++;
}

arsort($kategori);

echo json_encode([
    'success' => true,
    'kategori' => array_map(
        fn(string $label, int $count): array => ['label' => $label, 'count' => $count],
        array_keys($kategori),
        array_values($kategori)
    ),
    'status' => [
        ['label' => 'Terbit', 'count' => $status['terbit']],
        ['label' => 'Draft', 'count' => $status['draft']],
    ],
]);
