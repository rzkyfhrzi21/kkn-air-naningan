<?php
/* ======================================================
   ENDPOINT AJAX: AMBIL DATA PROFIL DESA (UNTUK MENGISI FORM EDIT)

   File ini ibarat "petugas fotokopi arsip desa": saat admin membuka
   halaman Kelola Profil, halaman itu memanggil file ini lewat AJAX.
   File ini mengambil seluruh data profil desa dari file JSON (via Model
   Profil) — seperti visi misi, sejarah, peta, dusun, dan data lainnya —
   lalu mengirimkannya dalam bentuk JSON agar semua kolom formulir
   terisi otomatis dengan data yang sudah tersimpan.
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
// Profil.php mengurus semua baca/tulis file JSON profil desa.
require_once __DIR__ . '/../../../app/Models/Profil.php';

// (1) Ambil seluruh data profil desa dari file JSON via Model Profil,
//     lalu kirimkan dalam bentuk JSON ke halaman Kelola Profil.
//     Di halaman itu, data ini dipakai untuk mengisi otomatis semua
//     kolom formulir: visi misi, sejarah, peta, dusun, dan sebagainya.
echo json_encode([
    'success' => true,
    'message' => 'Data profil dimuat.',
    'data'    => Profil::get(),
], JSON_UNESCAPED_UNICODE);
