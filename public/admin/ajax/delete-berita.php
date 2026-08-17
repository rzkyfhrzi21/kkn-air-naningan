<?php
/* ======================================================
   ENDPOINT AJAX: HAPUS BERITA (TOMBOL SAMPAH DI HALAMAN KELOLA BERITA)

   File ini ibarat "petugas penghapus arsip" di kantor desa.
   Saat admin menekan tombol hapus pada sebuah berita, halaman admin
   memanggil file ini lewat AJAX. File ini mengecek keamanan dulu
   (login + token rahasia), lalu meminta Model Berita menghapus baris
   berita dari file JSON, dan mengirim kabar berhasil/gagal dalam bentuk
   JSON yang nantinya ditampilkan sebagai toast (pemberitahuan kecil) di layar.
====================================================== */

// ── MEMULAI SESI LOGIN ADMIN ──
// Aktifkan "kartu identitas" browser (sesi PHP) supaya sistem tahu siapa pengunjungnya.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ── PENJAGA PINTU MASUK ──
// Kalau admin belum login (tidak ada data "admin" di sesi),
// kirim kode 401 = "tidak diizinkan" + pesan minta login ulang, lalu berhenti.
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
    exit;
}

// ── CEK TOKEN KEAMANAN (SEMACAM CAPTCHA RAHASIA) ──
// Setiap formulir admin membawa "token rahasia" yang hanya diketahui oleh
// halaman admin sendiri (CSRF token). Tujuannya: memastikan permintaan hapus
// ini benar-benar datang dari halaman kita, bukan dari situs jahat yang
// mencoba menghapus berita diam-diam. Kalau token tidak cocok,
// kirim kode 403 = "dilarang" lalu berhenti.
$csrf = (string) ($_POST['csrf_token'] ?? '');
if ($csrf === '' || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrf)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Token keamanan tidak valid. Muat ulang halaman.']);
    exit;
}

// ── PANGGIL MODEL (PETUGAS GUDANG DATA) ──
// Berita.php mengurus semua baca/tulis file JSON berita.
require_once __DIR__ . '/../../../app/Models/Berita.php';

// (1) Terima ID berita yang dikirim lewat formulir halaman admin (ketikan/sinyal
//     dari browser). Kalau kosong, berarti halaman mengirim data rusak
//     → kirim kode 400 = "permintaan salah", lalu berhenti.
$id = trim($_POST['id'] ?? '');
if (empty($id)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID berita tidak valid.']);
    exit;
}

// (2) Cek dulu apakah berita dengan ID itu benar-benar ada di file JSON (via Model).
//     Kalau tidak ada → kirim kode 404 = "barang yang dicari tidak ditemukan".
$item = Berita::find($id);
if (!$item) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Berita tidak ditemukan.']);
    exit;
}

// (3) Minta Model Berita menghapus data dari file JSON (Model juga membuat
//     cadangan/backup otomatis file lama sebelum menimpa).
//     Berhasil → kabar sukses yang menyebut judul berita yang dihapus.
//     Gagal   → kode 500 = "ada masalah di dalam server".
$deleted = Berita::delete($id);
if ($deleted) {
    echo json_encode([
        'success' => true,
        'message' => "Berita '{$item['judul']}' berhasil dihapus."
    ]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Gagal menghapus berita.']);
}
