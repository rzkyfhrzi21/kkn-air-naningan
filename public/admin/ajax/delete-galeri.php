<?php
/* ======================================================
   ENDPOINT AJAX: HAPUS MEDIA GALERI (FOTO/VIDEO)

   File ini ibarat "petugas pemusnah barang bukti" di kantor desa:
   saat admin menekan tombol hapus (satu atau beberapa media sekaligus)
   di halaman Kelola Galeri, halaman itu memanggil file ini lewat AJAX.
   Tugasnya:
   (1) Mengecek keamanan: login admin + token rahasia.
   (2) Menerima daftar ID media yang mau dihapus (bisa satu, bisa banyak).
   (3) Untuk setiap ID: hapus FILE FISIKNYA dari folder uploads/galeri/
       (agar hardisk server tidak penuh oleh file sisa yang tak terpakai),
       lalu hapus catatannya dari file JSON lewat Model Galeri.
   (4) Memberi kabar berhasil/gagal dalam bentuk JSON untuk ditampilkan
       sebagai toast di layar admin.
====================================================== */

declare(strict_types=1);

// ── BERI TAHU BROWSER: JAWABAN INI JSON ──
// Semua jawaban file ini berformat JSON berbahasa Indonesia (UTF-8),
// supaya huruf seperti 'è', '—', atau tanda kutip tidak berubah jadi simbol aneh.
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
// Token rahasia ini memastikan permintaan hapus benar-benar datang dari
// halaman admin kita, bukan dari situs jahat. Kalau tidak cocok,
// kirim kode 403 = "dilarang" lalu berhenti.
$csrf = (string) ($_POST['csrf_token'] ?? '');
if ($csrf === '' || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrf)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Token keamanan tidak valid. Muat ulang halaman.']);
    exit;
}

// ── PANGGIL MODEL (PETUGAS GUDANG DATA) ──
// Galeri.php mengurus semua baca/tulis file JSON galeri.
require_once __DIR__ . '/../../../app/Models/Galeri.php';

// (1) Kumpulkan ID media yang mau dihapus.
//     Bisa dikirim sebagai array "ids" (hapus banyak sekaligus / centang massal),
//     atau satu ID lewat "id" (hapus satu per satu). Semua dibersihkan dari spasi,
//     lalu ID yang dobel dibuang supaya tidak ada media yang dihapus dua kali.
$ids = [];
if (isset($_POST['ids']) && is_array($_POST['ids'])) {
    foreach ($_POST['ids'] as $raw) {
        $id = trim((string) $raw);
        if ($id !== '') {
            $ids[] = $id;
        }
    }
} else {
    $id = trim((string) ($_POST['id'] ?? ''));
    if ($id !== '') {
        $ids[] = $id;
    }
}
$ids = array_values(array_unique($ids));

// (2) Kalau ternyata tidak ada satu pun ID yang valid, langsung lapor gagal
//     tanpa lanjut bekerja.
if ($ids === []) {
    echo json_encode(['success' => false, 'message' => 'ID galeri wajib diisi.']);
    exit;
}

// $uploadRoot : alamat folder asli (fisik) tempat media galeri disimpan.
//               Dipakai sebagai patokan agar file yang dihapus benar-benar
//               berada di dalam folder galeri, bukan di folder lain.
$uploadRoot = realpath(dirname(__DIR__, 2) . '/uploads/galeri') ?: '';
$deletedTitles = [];
$deletedCount  = 0;

// (3) Proses penghapusan satu per satu ID:
//     - Cari datanya di file JSON (via Model Galeri); kalau tidak ketemu,
//       lewati saja (continue) karena tidak ada yang perlu dihapus.
//     - Kalau media itu file lokal (bukan link dari internet) dan memang
//       berada di folder uploads/galeri/, hapus file fisiknya dengan unlink()
//       supaya hardisk server tidak menumpuk file sisa.
//       Catatan: nama file diambil dari URL, lalu dicek ulang posisinya
//       benar-benar di dalam folder galeri (anti-hack ke folder lain).
//     - Setelah file fisik hilang, baru minta Model menghapus catatannya
//       dari file JSON, sambil mencatat judul media yang berhasil dihapus.
foreach ($ids as $id) {
    $item = Galeri::find($id);
    if ($item === null) {
        continue;
    }

    $file = (string) ($item['file'] ?? '');
    if ($file !== '' && !preg_match('#^https?://#i', $file) && str_contains($file, '/uploads/galeri/')) {
        $basename = basename(parse_url($file, PHP_URL_PATH) ?: $file);
        $fullPath = dirname(__DIR__, 2) . '/uploads/galeri/' . $basename;
        if (is_file($fullPath)) {
            $real = realpath($fullPath);
            if ($real !== false && ($uploadRoot === '' || str_starts_with($real, $uploadRoot))) {
                @unlink($real);
            }
        }
    }

    if (Galeri::delete($id)) {
        $deletedCount++;
        $deletedTitles[] = (string) ($item['judul'] ?? $id);
    }
}

// (4) Kirim kabar hasil:
//     - Tidak ada yang terhapus → lapor "Tidak ada media galeri yang dihapus."
//     - Hanya 1 media           → sebutkan judulnya
//     - Lebih dari 1 media      → sebutkan jumlah totalnya
//     Semua jawaban dibungkus JSON berformat UTF-8 agar huruf Indonesia
//     (seperti 'è' atau tanda kutip) tampil dengan benar.
if ($deletedCount === 0) {
    echo json_encode(['success' => false, 'message' => 'Tidak ada media galeri yang dihapus.']);
    exit;
}

if ($deletedCount === 1) {
    $title = $deletedTitles[0] ?? 'Media';
    echo json_encode([
        'success' => true,
        'message' => "Galeri '{$title}' berhasil dihapus.",
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

echo json_encode([
    'success' => true,
    'message' => "{$deletedCount} media galeri berhasil dihapus.",
], JSON_UNESCAPED_UNICODE);
