<?php
/* ======================================================
   ENDPOINT AJAX: HAPUS UMKM (TOMBOL SAMPAH DI HALAMAN KELOLA UMKM)

   File ini ibarat "petugas penghapus katalog usaha" di kantor desa:
   saat admin menekan tombol hapus pada sebuah UMKM di halaman Kelola
   UMKM, halaman itu memanggil file ini lewat AJAX. File ini mengecek
   keamanan (login + token), memastikan UMKM-nya ada, lalu meminta
   Model Umkm menghapusnya dari file JSON dan mengirim kabar hasil
   dalam bentuk JSON untuk ditampilkan sebagai toast di layar admin.
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
// Umkm.php mengurus semua baca/tulis file JSON data UMKM.
require_once __DIR__ . '/../../../app/Models/Umkm.php';

// (1) Terima ID UMKM yang dikirim lewat formulir halaman admin (ketikan dari browser).
//     Kalau kosong, lapor "ID UMKM wajib diisi" lalu berhenti.
$id = trim((string) ($_POST['id'] ?? ''));
if ($id === '') {
    echo json_encode(['success' => false, 'message' => 'ID UMKM wajib diisi.']);
    exit;
}

// (2) Cek dulu apakah UMKM dengan ID itu benar-benar ada di file JSON (via Model).
//     Kalau tidak ada, lapor "UMKM tidak ditemukan" lalu berhenti.
$item = Umkm::find($id);
if ($item === null) {
    echo json_encode(['success' => false, 'message' => 'UMKM tidak ditemukan.']);
    exit;
}

// (3) Simpan dulu nama usahanya (untuk dipakai di pesan sukses), lalu minta
//     Model Umkm menghapus data dari file JSON (Model juga membuat backup
//     otomatis file lama sebelum menimpa). Kalau gagal dihapus, lapor gagal.
$nama = (string) ($item['nama'] ?? '');
if (!Umkm::delete($id)) {
    echo json_encode(['success' => false, 'message' => 'Gagal menghapus UMKM.']);
    exit;
}

// (4) Kirim kabar sukses dalam bentuk JSON: sebutkan nama UMKM yang dihapus
//     (format UTF-8 supaya huruf Indonesia tampil benar).
echo json_encode([
    'success' => true,
    'message' => "UMKM '{$nama}' berhasil dihapus.",
], JSON_UNESCAPED_UNICODE);
