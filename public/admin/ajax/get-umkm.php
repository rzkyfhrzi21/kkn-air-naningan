<?php
/* ======================================================
   ENDPOINT AJAX: AMBIL SATU UMKM (UNTUK MENGISI FORM EDIT)

   File ini ibarat "petugas pengambil arsip" di kantor desa:
   saat admin menekan tombol "Edit" pada sebuah UMKM di halaman Kelola
   UMKM, halaman itu memanggil file ini lewat AJAX. File ini mencari
   data UMKM yang diminta dari file JSON (via Model Umkm), lalu
   mengirimkannya dalam bentuk JSON agar form edit otomatis terisi lengkap.
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
// Umkm.php mengurus semua baca/tulis file JSON data UMKM.
require_once __DIR__ . '/../../../app/Models/Umkm.php';

// (1) Terima ID UMKM yang dikirim lewat formulir halaman admin.
//     Kalau kosong, lapor "ID UMKM wajib diisi" lalu berhenti.
$id = trim((string) ($_POST['id'] ?? ''));
if ($id === '') {
    echo json_encode(['success' => false, 'message' => 'ID UMKM wajib diisi.']);
    exit;
}

// (2) Cari data UMKM dengan ID tersebut di file JSON (via Model Umkm).
//     Kalau tidak ada, lapor "UMKM tidak ditemukan" lalu berhenti.
$item = Umkm::find($id);
if ($item === null) {
    echo json_encode(['success' => false, 'message' => 'UMKM tidak ditemukan.']);
    exit;
}

// (3) Kirim data UMKM lengkap dalam bentuk JSON ke halaman Kelola UMKM,
//     supaya form edit terisi otomatis (format UTF-8 agar huruf Indonesia aman).
echo json_encode([
    'success' => true,
    'message' => 'Data dimuat.',
    'data'    => $item,
], JSON_UNESCAPED_UNICODE);
