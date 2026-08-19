<?php

declare(strict_types=1);

/* ======================================================
   ENDPOINT AJAX: DAFTAR UMKM (TABEL & PAGINASI DI KELOLA UMKM)

   File ini ibarat "petugas pemilah daftar usaha warga":
   saat admin membuka tabel UMKM, melakukan pencarian, menyaring kategori,
   atau berpindah halaman di panel Kelola UMKM, file ini dipanggil via AJAX.

   Alur kerjanya:
   (1) Cek Sesi Admin & metode request POST.
   (2) Tangkap parameter pencarian, kategori, dan nomor halaman.
   (3) Ambil semua UMKM via Model Umkm & hitung statistik (aktif, non-aktif, unggulan, per kategori).
   (4) Filter array berdasarkan teks pencarian & kategori.
   (5) Paginasi (10 UMKM per halaman).
   (6) Kirim balasan berformat JSON.
====================================================== */

header('Content-Type: application/json; charset=utf-8');

// (1) Cek Sesi Admin & Metode Request POST
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

// (2) Tangkap parameter pencarian, penyaringan, dan halaman
$page     = max(1, (int) ($_POST['page'] ?? 1));
$search   = trim((string) ($_POST['search'] ?? ''));
$kategori = trim((string) ($_POST['kategori'] ?? ''));
$perPage  = 10;

// (3) Ambil semua UMKM dari file JSON via Model Umkm & filter
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

// (4) Hitung total & statistik data UMKM
$items = array_values($items);
$total = count($items);
$statAktif = count(array_filter($items, static fn(array $i): bool => ($i['status'] ?? 'aktif') === 'aktif'));
$statKategori = array_fill_keys(array_values(Umkm::KATEGORI), 0);
foreach ($items as $item) {
    $label = (string) ($item['kategori_label'] ?? Umkm::KATEGORI[$item['kategori'] ?? ''] ?? 'Lainnya');
    $statKategori[$label] = ($statKategori[$label] ?? 0) + 1;
}

// (5) Paginasi (potong array 10 item per halaman)
$paged = array_slice($items, ($page - 1) * $perPage, $perPage);

// (6) Kirim hasil balasan berformat JSON UTF-8
echo json_encode([
    'success'  => true,
    'data'     => $paged,
    'page'     => $page,
    'total'    => $total,
    'stat_aktif' => $statAktif,
    'stat_nonaktif' => $total - $statAktif,
    'stat_kategori' => $statKategori,
    'has_next' => ($page * $perPage) < $total,
    'has_prev' => $page > 1,
], JSON_UNESCAPED_UNICODE);

