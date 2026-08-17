<?php

declare(strict_types=1);

/* ======================================================
   ENDPOINT AJAX: DAFTAR GALERI (KOTAK MEDIA & PAGINASI DI KELOLA GALERI)

   File ini ibarat "petugas album foto desa":
   saat admin melihat galeri foto/video, melakukan pencarian, menyaring kategori,
   atau berpindah halaman di panel admin Kelola Galeri, file ini dipanggil via AJAX.

   Alur kerjanya:
   (1) Memeriksa login admin & metode POST.
   (2) Mengambil semua data galeri via Model Galeri.
   (3) Menghitung statistik media (foto vs video).
   (4) Menyaring berdasarkan kata kunci & kategori.
   (5) Mengurutkan media (terbaru/terlama).
   (6) Memotong data per halaman (12 media per halaman).
   (7) Mengirimkan balasan berformat JSON.
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

require_once __DIR__ . '/../../../app/Models/Galeri.php';

// (2) Ambil parameter dari form AJAX
$page     = max(1, (int) ($_POST['page'] ?? 1));
$search   = trim((string) ($_POST['search'] ?? ''));
$kategori = trim((string) ($_POST['kategori'] ?? ''));
$sort     = trim((string) ($_POST['sort'] ?? 'newest'));
$perPage  = 12;

// (3) Ambil semua media dari Model & hitung statistik foto vs video
$items = Galeri::all();
$statTotal = count($items);
$statFoto  = count(array_filter($items, static fn(array $i): bool => ($i['tipe'] ?? 'foto') === 'foto'));
$statVideo = $statTotal - $statFoto;

// (4) Filter berdasarkan teks pencarian (judul / deskripsi)
if ($search !== '') {
    $items = array_values(array_filter($items, static function (array $i) use ($search): bool {
        return stripos((string) ($i['judul'] ?? ''), $search) !== false
            || stripos((string) ($i['deskripsi'] ?? ''), $search) !== false;
    }));
}

// Filter berdasarkan kategori
if ($kategori !== '' && $kategori !== 'all') {
    $items = array_values(array_filter($items, static fn(array $i): bool => ($i['kategori'] ?? '') === $kategori));
}

// (5) Urutkan array berdasarkan tanggal/urutan
usort($items, static function (array $a, array $b) use ($sort): int {
    $ta = strtotime((string) ($a['created_at'] ?? '')) ?: 0;
    $tb = strtotime((string) ($b['created_at'] ?? '')) ?: 0;
    if ($ta === $tb) {
        return ((int) ($a['urutan'] ?? 0)) <=> ((int) ($b['urutan'] ?? 0));
    }
    return $sort === 'oldest' ? ($ta <=> $tb) : ($tb <=> $ta);
});

// (6) Paginasi (potong array 12 media per halaman)
$total = count($items);
$paged = array_slice($items, ($page - 1) * $perPage, $perPage);

// (7) Balas JSON UTF-8
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

