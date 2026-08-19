<?php
/* ======================================================
   ENDPOINT AJAX: SUSUN ULANG URUTAN GALERI (FOTO/VIDEO)

   File ini ibarat "petugas penata album foto" di kantor desa:
   saat admin menekan tombol naik/turun pada sebuah media di
   halaman Kelola Galeri, halaman itu memanggil file ini lewat
   AJAX dengan ID media + arah perpindahan.

   Tugasnya:
   (1) Mengecek keamanan: login admin + token rahasia (CSRF).
   (2) Menerima ID media dan arah ("up" naik / "down" turun).
   (3) Meminta Model Galeri menukar posisi media itu dengan
       tetangganya, merapikan nomor urut, lalu menyimpan sekali
       dengan backup otomatis.
   (4) Memberi kabar berhasil/gagal dalam bentuk JSON untuk
       ditampilkan sebagai toast di layar admin.
====================================================== */

declare(strict_types=1);

// Beri tahu browser bahwa jawaban file ini JSON UTF-8
header('Content-Type: application/json; charset=utf-8');

// Mulai sesi login admin
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Penjaga pintu masuk: admin belum login -> 401
if (empty($_SESSION['admin'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Sesi habis, silakan login ulang.']);
    exit;
}

// Permintaan wajib lewat method POST -> selain itu 405
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Metode tidak diizinkan.']);
    exit;
}

// Cek token keamanan CSRF: tidak cocok -> 403
$csrf = (string) ($_POST['csrf_token'] ?? '');
if ($csrf === '' || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrf)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Token keamanan tidak valid. Muat ulang halaman.']);
    exit;
}

// Panggil Model Galeri (petugas gudang data)
require_once __DIR__ . '/../../../app/Models/Galeri.php';

// (1) Terima ID media dan arah perpindahan
$id        = trim((string) ($_POST['id'] ?? ''));
$direction = trim((string) ($_POST['direction'] ?? ''));
if ($id === '' || !in_array($direction, ['up', 'down'], true)) {
    echo json_encode(['success' => false, 'message' => 'Data urutan tidak valid.']);
    exit;
}

// (2) Ambil judul media untuk pesan toast yang detail
$item = Galeri::find($id);
if ($item === null) {
    echo json_encode(['success' => false, 'message' => 'Media galeri tidak ditemukan.']);
    exit;
}

// (3) Minta Model menukar posisi; false = sudah di ujung / gagal
if (!Galeri::swapOrder($id, $direction)) {
    echo json_encode([
        'success' => false,
        'message' => $direction === 'up'
            ? "Galeri '{$item['judul']}' sudah berada di urutan paling atas."
            : "Galeri '{$item['judul']}' sudah berada di urutan paling bawah.",
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// (4) Kabar sukses untuk toast admin
echo json_encode([
    'success' => true,
    'message' => $direction === 'up'
        ? "Urutan '{$item['judul']}' dinaikkan."
        : "Urutan '{$item['judul']}' diturunkan.",
], JSON_UNESCAPED_UNICODE);