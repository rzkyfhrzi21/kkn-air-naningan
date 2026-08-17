<?php
/* ======================================================
   ENDPOINT AJAX: DAFTAR PESAN MASUK (TABEL & PAGINASI DI KOTAK MASUK)

   File ini ibarat "petugas pemilah surat masuk":
   saat admin melihat pesan masuk dari pengunjung, melakukan pencarian nama/isi,
   menyaring status dibaca/belum, atau berpindah halaman di Kotak Masuk, file ini dipanggil via AJAX.

   Alur kerjanya:
   (1) Cek Sesi Admin & metode POST.
   (2) Ambil semua pesan via Model Pesan.
   (3) Hitung statistik total, pesan belum dibaca, dan sudah dibaca.
   (4) Filter berdasarkan teks pencarian & filter dibaca.
   (5) Paginasi (10 pesan per halaman).
   (6) Balas JSON.
====================================================== */

header('Content-Type: application/json; charset=utf-8');

// (1) Cek Sesi Admin & Metode Request POST
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

// (2) Tangkap parameter pencarian, penyaringan, dan halaman
$page = (int)($_POST['page'] ?? 1);
$search = trim($_POST['search'] ?? '');
$readFilter = trim($_POST['read_filter'] ?? 'all');
$perPage = 10;

// (3) Ambil semua pesan dari file JSON via Model Pesan & hitung statistik
$allItems = Pesan::all();
$statTotal  = count($allItems);
$statBaru   = count(array_filter($allItems, static fn(array $i): bool => !($i['is_read'] ?? false)));
$statDibaca = $statTotal - $statBaru;

$items = $allItems;

// (4) Filter berdasarkan kata kunci pencarian (nama / pesan)
if ($search !== '') {
    $items = array_filter($items, fn($i) => 
        stripos($i['nama'] ?? '', $search) !== false || 
        stripos($i['pesan'] ?? '', $search) !== false
    );
}

// Filter berdasarkan status dibaca (unread / read / all)
if ($readFilter === 'unread') {
    $items = array_filter($items, static fn(array $i): bool => !($i['is_read'] ?? false));
} elseif ($readFilter === 'read') {
    $items = array_filter($items, static fn(array $i): bool => (bool) ($i['is_read'] ?? false));
}

// (5) Paginasi data (10 item per halaman)
$items = array_values($items);
$total = count($items);
$paged = array_slice($items, ($page - 1) * $perPage, $perPage);

// (6) Kirim hasil balasan berformat JSON
echo json_encode([
    'success' => true,
    'data' => $paged,
    'page' => $page,
    'total' => $total,
    'stat_total' => $statTotal,
    'stat_baru' => $statBaru,
    'stat_dibaca' => $statDibaca,
    'has_next' => ($page * $perPage) < $total,
    'has_prev' => $page > 1,
]);

