<?php
/* ======================================================
   ENDPOINT AJAX: CHART DATA BERITA (GRAFIK DI DASHBOARD ADMIN)

   File ini ibarat "petugas rekap data" di kantor desa.
   Saat halaman Dashboard Admin membuka grafik berita, halaman itu
   memanggil file ini lewat AJAX (transaksi di belakang layar).
   File ini lalu menghitung ringkasan data berita dari file JSON
   (lewat Model Berita) dan mengirimkannya kembali dalam bentuk JSON,
   supaya JavaScript di halaman bisa menggambar grafik batang/lingkaran.

   Yang dihitung:
   (1) Jumlah berita untuk setiap kategori (misal: "Berita Desa": 5, "Pengumuman": 2)
   (2) Jumlah berita berstatus "terbit" vs "draft"
====================================================== */

// ── MEMULAI SESI LOGIN ADMIN ──
// Aktifkan dulu "kartu identitas" browser (sesi PHP) supaya sistem
// bisa mengenali siapa yang sedang membuka halaman ini.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ── PENJAGA PINTU MASUK ──
// Pintu dijaga satpam: kalau tidak ada data login admin di sesi,
// kirim kode 401 (= "kamu tidak diizinkan") beserta pesan minta login ulang,
// lalu berhenti di sini (exit) — tidak ada data yang dibocorkan ke orang asing.
if (!isset($_SESSION['admin'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Sesi habis, silakan login ulang.']);
    exit;
}

// ── CEK CARA DATANGNYA PERMINTAAN ──
// Permintaan WAJIB lewat method POST (seperti pengiriman formulir tersembunyi).
// Kalau bukan POST, kirim kode 405 = "metode tidak diizinkan", lalu berhenti.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

// ── PANGGIL MODEL (PETUGAS GUDANG DATA) ──
// Berita.php adalah petugas gudang yang membaca & menulis file JSON berita.
// Semua urusan baca data diurus di sana, file ini tidak membuka file JSON sendiri.
require_once __DIR__ . '/../../../app/Models/Berita.php';

// (1) Siapkan dua keranjang kosong untuk menampung hasil hitungan:
//     - $kategori : keranjang "jumlah berita per kategori"
//     - $status   : keranjang "jumlah berita per status" (semua mulai dari angka 0)
$kategori = [];
$status = ['terbit' => 0, 'draft' => 0];

// (2) Buka semua berita dari file JSON satu per satu (via Model Berita):
//     - Ambil nama kategorinya; kalau kosong, dianggap "Tanpa Kategori".
//       Lalu tambahkan +1 ke keranjang kategori yang cocok.
//     - Cek statusnya: kalau "terbit" masuk keranjang terbit,
//       kalau tidak (misal "draft") masuk keranjang draft.
foreach (Berita::all() as $berita) {
    $kat = trim((string)($berita['kategori'] ?? ''));
    if ($kat === '') {
        $kat = 'Tanpa Kategori';
    }
    $kategori[$kat] = ($kategori[$kat] ?? 0) + 1;

    $st = ($berita['status'] ?? '') === 'terbit' ? 'terbit' : 'draft';
    $status[$st]++;
}

// (3) Susun ulang keranjang kategori dari angka terbesar ke terkecil,
//     seperti memeringkat juara lomba: nilai tertinggi duduk di kursi pertama.
arsort($kategori);

// (4) Bungkus hasil hitungan ke dalam format JSON (bahasa yang dimengerti
//     JavaScript) lalu kirimkan kembali ke halaman Dashboard Admin.
//     Kategori diubah menjadi daftar pasangan "label + jumlah",
//     status diubah menjadi dua baris: "Terbit" dan "Draft".
echo json_encode([
    'success' => true,
    'kategori' => array_map(
        fn(string $label, int $count): array => ['label' => $label, 'count' => $count],
        array_keys($kategori),
        array_values($kategori)
    ),
    'status' => [
        ['label' => 'Terbit', 'count' => $status['terbit']],
        ['label' => 'Draft', 'count' => $status['draft']],
    ],
]);
