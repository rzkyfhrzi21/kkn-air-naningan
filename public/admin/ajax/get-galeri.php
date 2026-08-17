<?php
/* ======================================================
   ENDPOINT AJAX: AMBIL SATU MEDIA GALERI (UNTUK MENGISI FORM EDIT)

   File ini ibarat "petugas pengambil arsip" di kantor desa:
   saat admin menekan tombol "Edit" pada sebuah foto/video di halaman
   Kelola Galeri, halaman itu memanggil file ini lewat AJAX.
   File ini mencari data media yang diminta dari file JSON (via Model
   Galeri), lalu mengirimkannya dalam bentuk JSON agar form edit
   otomatis terisi lengkap.
====================================================== */

declare(strict_types=1);

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
if (empty($_SESSION['admin'])) {
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
// Galeri.php mengurus semua baca/tulis file JSON galeri.
require_once __DIR__ . '/../../../app/Models/Galeri.php';

// (1) Terima ID media yang dikirim lewat formulir halaman admin.
//     Kalau kosong, berarti data kiriman rusak → kode 400 = "permintaan salah".
$id = trim((string) ($_POST['id'] ?? ''));
if ($id === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID galeri tidak valid.']);
    exit;
}

// (2) Cari data media dengan ID tersebut di file JSON (via Model Galeri).
//     Kalau tidak ada → kode 404 = "barang yang dicari tidak ditemukan".
$item = Galeri::find($id);
if ($item === null) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Galeri tidak ditemukan.']);
    exit;
}

// (3) Kirim data media lengkap dalam bentuk JSON ke halaman Kelola Galeri,
//     supaya form edit terisi otomatis (format UTF-8 agar huruf Indonesia aman).
echo json_encode(['success' => true, 'data' => $item], JSON_UNESCAPED_UNICODE);
