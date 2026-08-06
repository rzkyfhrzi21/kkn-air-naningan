<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Metode tidak diizinkan.']);
    exit;
}

require_once __DIR__ . '/../app/Models/Pesan.php';

$nama     = trim((string) ($_POST['nama'] ?? ''));
$kontak   = trim((string) ($_POST['kontak'] ?? ''));
$kategori = trim((string) ($_POST['kategori'] ?? ''));
$pesan    = trim((string) ($_POST['pesan'] ?? ''));

if ($nama === '' || $kontak === '' || $kategori === '' || $pesan === '') {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Nama, kontak, kategori, dan isi pesan wajib diisi.']);
    exit;
}

if (mb_strlen($nama) > 100 || mb_strlen($kontak) > 150 || mb_strlen($pesan) > 5000) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Data pesan melebihi batas panjang yang diizinkan.']);
    exit;
}

// Whitelist kategori — mencegah nilai sembarang tersimpan di JSON
$kategoriValid = ['info', 'layanan', 'pengaduan', 'saran', 'lainnya'];
if (!in_array($kategori, $kategoriValid, true)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Kategori pesan tidak valid.']);
    exit;
}

$item = Pesan::create([
    'nama' => $nama,
    'kontak' => $kontak,
    'kategori' => $kategori,
    'pesan' => $pesan,
]);

echo json_encode([
    'success' => true,
    'message' => 'Pesan Anda berhasil dikirim dan akan diproses pada jam kerja.',
    'data' => ['id' => $item['id']],
], JSON_UNESCAPED_UNICODE);
