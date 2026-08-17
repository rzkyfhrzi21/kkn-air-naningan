<?php
/* ======================================================
   ENDPOINT AJAX: AMBIL SATU BERITA (UNTUK MENGISI FORM EDIT)

   File ini ibarat "petugas pengambil arsip" di kantor desa:
   saat admin menekan tombol "Edit" pada sebuah berita, halaman Kelola
   Berita memanggil file ini lewat AJAX. File ini mencari data berita
   yang diminta dari file JSON (via Model Berita), lalu mengirimkannya
   dalam bentuk JSON agar form edit otomatis terisi lengkap.
   Bonus: file ini juga menghitung dua penanda khusus:
   - is_published : apakah berita ini sudah "terbit" sesuai waktunya
   - is_scheduled : apakah berita ini dijadwalkan terbit di masa depan
====================================================== */

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
    exit;
}

// ── PANGGIL MODEL (PETUGAS GUDANG DATA) ──
// Berita.php mengurus semua baca/tulis file JSON berita.
require_once __DIR__ . '/../../../app/Models/Berita.php';

// (1) Terima ID berita yang dikirim lewat formulir halaman admin.
//     Kalau kosong, berarti data kiriman rusak → kode 400 = "permintaan salah".
$id = trim($_POST['id'] ?? '');
if (empty($id)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID berita tidak valid.']);
    exit;
}

// (2) Cari data berita dengan ID tersebut di file JSON (via Model Berita).
//     Kalau tidak ada → kode 404 = "barang yang dicari tidak ditemukan".
$item = Berita::find($id);
if (!$item) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Berita tidak ditemukan.']);
    exit;
}

// (3) Hitung dua penanda tambahan supaya halaman tahu kondisi berita ini:
//     - is_published : apakah berita sudah terbit sesuai waktunya
//     - is_scheduled : apakah berita dijadwalkan terbit di masa depan
$item['is_published'] = Berita::isPublished($item);
$item['is_scheduled'] = Berita::isScheduled($item);

// (4) Kirim data berita lengkap dalam bentuk JSON ke halaman Kelola Berita,
//     supaya form edit terisi otomatis.
echo json_encode(['success' => true, 'data' => $item]);
