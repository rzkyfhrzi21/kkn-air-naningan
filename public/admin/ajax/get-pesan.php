<?php
/* ======================================================
   ENDPOINT AJAX: AMBIL SATU PESAN + TANDAI SUDAH DIBACA

   File ini ibarat "petugas pembuka surat" di kantor desa:
   saat admin mengklik sebuah pesan di halaman Kotak Masuk, halaman itu
   memanggil file ini lewat AJAX. File ini mengambil isi pesan dari file
   JSON (via Model Pesan), lalu — seperti membuka amplop — menandai
   pesan tersebut sebagai "sudah dibaca" (is_read = true) agar hitungan
   pesan baru di menu berkurang. Isi pesan dikirim balik dalam bentuk JSON.
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

// (2) Cari data pesan dengan ID tersebut di file JSON (via Model Pesan).
//     Kalau tidak ada → kode 404 = "barang yang dicari tidak ditemukan".
$item = Pesan::find($id);
if (!$item) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Pesan tidak ditemukan.']);
    exit;
}

// (3) Kirim isi pesan lengkap dalam bentuk JSON ke halaman Kotak Masuk,
//     supaya admin bisa membaca dan menindaklanjuti pesan tersebut.
//     Catatan: endpoint ini read-only — membuka pesan TIDAK mengubah
//     status is_read. Status baca hanya berubah lewat aksi tombol admin.
echo json_encode(['success' => true, 'data' => $item]);
