<?php
/* ======================================================
   ENDPOINT AJAX: HAPUS PESAN DARI PENGUNJUNG (KOTAK MASUK ADMIN)

   File ini ibarat "petugas pembuang surat" di kantor desa:
   saat admin menekan tombol hapus pada sebuah pesan di halaman
   Kotak Masuk, halaman itu memanggil file ini lewat AJAX.
   File ini mengecek keamanan (login + token), lalu meminta Model Pesan
   menghapus pesan dari file JSON, dan mengirim kabar berhasil/gagal
   dalam bentuk JSON untuk ditampilkan sebagai toast di layar admin.
====================================================== */

// ── BERI TAHU BROWSER: JAWABAN INI JSON ──
// Jawaban berformat JSON UTF-8 supaya huruf Indonesia tampil dengan benar.
header('Content-Type: application/json; charset=utf-8');

// ── MEMULAI SESI LOGIN ADMIN ──
// Aktifkan "kartu identitas" browser (sesi PHP) supaya sistem tahu siapa pengunjungnya.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ── PENJAGA PINTU MASUK ──
// Kalau admin belum login, kirim kode 401 = "tidak diizinkan" + pesan login ulang.
if (!isset($_SESSION['admin'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Sesi habis, silakan login ulang.']);
    exit;
}

// ── CEK CARA DATANGNYA PERMINTAAN ──
// Permintaan WAJIB lewat method POST. Kalau bukan POST,
// kirim kode 405 = "metode tidak diizinkan", lalu berhenti.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Metode tidak diizinkan.']);
    exit;
}

// ── CEK TOKEN KEAMANAN (SEMACAM CAPTCHA RAHASIA) ──
// Token rahasia memastikan permintaan hapus ini benar-benar datang dari
// halaman admin kita, bukan dari situs jahat. Kalau tidak cocok,
// kirim kode 403 = "dilarang" lalu berhenti.
$csrf = (string) ($_POST['csrf_token'] ?? '');
if ($csrf === '' || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrf)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Token keamanan tidak valid. Muat ulang halaman.']);
    exit;
}

// ── PANGGIL MODEL (PETUGAS GUDANG DATA) ──
// Pesan.php mengurus semua baca/tulis file JSON pesan dari pengunjung.
require_once __DIR__ . '/../../../app/Models/Pesan.php';

// (1) Terima ID pesan yang dikirim lewat formulir halaman admin.
//     Kalau kosong, berarti data kiriman rusak → kode 400 = "permintaan salah".
$id = trim($_POST['id'] ?? '');
if (empty($id)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID pesan tidak valid.']);
    exit;
}

// (2) Cek dulu apakah pesan dengan ID itu benar-benar ada di file JSON (via Model).
//     Kalau tidak ada → kode 404 = "barang yang dicari tidak ditemukan".
$item = Pesan::find($id);
if (!$item) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Pesan tidak ditemukan.']);
    exit;
}

// (3) Minta Model Pesan menghapus data dari file JSON (Model juga membuat
//     cadangan/backup otomatis file lama sebelum menimpa).
//     Berhasil → kabar sukses yang menyebut nama pengirim pesan.
//     Gagal   → kode 500 = "ada masalah di dalam server".
$deleted = Pesan::delete($id);
if ($deleted) {
    echo json_encode([
        'success' => true,
        'message' => "Pesan dari '{$item['nama']}' berhasil dihapus."
    ]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Gagal menghapus pesan.']);
}
