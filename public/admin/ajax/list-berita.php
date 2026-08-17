<?php
/* ======================================================
   ENDPOINT AJAX: DAFTAR BERITA (TABEL & PAGINASI DI KELOLA BERITA)

   File ini ibarat "petugas pemilah arsip berita":
   saat admin membuka tabel berita, mencari kata kunci, menyaring kategori,
   atau menekan halaman 2, 3, dst., file ini dipanggil lewat AJAX.
   
   Alur kerjanya:
   (1) Cek login & metode POST.
   (2) Ambil semua berita dari Model Berita.
   (3) Filter berita berdasarkan kata pencarian, kategori, atau status.
   (4) Hitung statistik berita terbit vs draft.
   (5) Potong data sesuai halaman (paginasi: 10 item per halaman).
   (6) Balas dalam bentuk JSON.
====================================================== */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// (1) Cek Sesi Admin
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

// (2) Tangkap parameter pencarian, penyaringan, dan nomor halaman
$page = (int)($_POST['page'] ?? 1);
$search = trim($_POST['search'] ?? '');
$kategori = trim($_POST['kategori'] ?? '');
$status = trim($_POST['status'] ?? '');
$perPage = 10;

// (3) Ambil seluruh berita dari file JSON via Model Berita
$items = Berita::all();

// Filter berdasarkan teks pencarian
if ($search !== '') {
    $items = array_filter($items, fn($i) => 
        stripos($i['judul'] ?? '', $search) !== false || 
        stripos($i['ringkasan'] ?? '', $search) !== false
    );
}

// Filter berdasarkan kategori
if ($kategori !== '') {
    $items = array_filter($items, fn($i) => ($i['kategori'] ?? '') === $kategori);
}

// Filter berdasarkan status terbit/draft
if ($status !== '') {
    $items = array_filter($items, fn($i) => ($i['status'] ?? '') === $status);
}

// (4) Hitung total data & statistik terbit vs draft
$items = array_values($items);
$total = count($items);
$statTerbit = count(array_filter($items, fn($i) => ($i['status'] ?? '') === 'terbit'));
$statDraft  = $total - $statTerbit;

// (5) Potong array sesuai halaman aktif (Paginasi)
$paged = array_slice($items, ($page - 1) * $perPage, $perPage);
$paged = array_map(static function (array $item): array {
    $item['is_published'] = Berita::isPublished($item);
    $item['is_scheduled'] = Berita::isScheduled($item);
    return $item;
}, $paged);

// (6) Kirim hasil balasan JSON
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
