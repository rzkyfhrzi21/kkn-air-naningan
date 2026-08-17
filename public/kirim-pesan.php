<?php

/*
 * ======================================================
 * PROSES PENGIRIMAN PESAN (PUBLIK)
 *
 * File ini ibarat petugas surat masuk: dia tidak menampilkan
 * halaman apa pun, melainkan menerima formulir yang dikirim
 * pengunjung dari halaman Kontak, lalu menyimpannya ke data.
 *
 * Alur kerjanya:
 * (1) Memastikan data datang lewat metode POST (pengiriman
 *     tersembunyi dari form), bukan lewat alamat browser.
 * (2) Membersihkan ketikan pengguna (trim) dan mengambil
 *     keempat isi form: nama, kontak, kategori, dan pesan.
 * (3) Memeriksa kelengkapan isian — kalau ada yang kosong,
 *     langsung ditolak dengan pesan yang jelas.
 * (4) Memeriksa panjang isian agar tidak membebani server.
 * (5) Memastikan kategori hanya yang sudah terdaftar
 *     (whitelist), supaya tidak ada nilai sembarangan.
 * (6) Menyimpan pesan lewat Model `Pesan` ke file JSON.
 * (7) Memberi kabar sukses dalam bentuk JSON agar situs
 *     bisa menampilkan notifikasi kepada pengunjung.
 *
 * Catatan: `declare(strict_types=1)` membuat PHP ketat soal
 * tipe data, seperti petugas yang teliti mencatat surat.
 * ======================================================
 */

declare(strict_types=1);

// Beri tahu browser bahwa jawaban file ini berupa teks JSON (bukan halaman HTML)
header('Content-Type: application/json; charset=utf-8');

// (1) Cek apakah pengiriman dilakukan lewat metode POST; jika bukan, tolak dengan kode 405
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Metode tidak diizinkan.']);
    exit;
}

// (2a) Panggil Model Pesan — dia yang bertugas menulis data ke file JSON
require_once __DIR__ . '/../app/Models/Pesan.php';

// (2b) Ambil ketikan pengguna dari form lalu buang spasi kosong di kiri-kanan teksnya (trim)
$nama     = trim((string) ($_POST['nama'] ?? ''));
$kontak   = trim((string) ($_POST['kontak'] ?? ''));
$kategori = trim((string) ($_POST['kategori'] ?? ''));
$pesan    = trim((string) ($_POST['pesan'] ?? ''));

// (3) Jika ada salah satu isian yang masih kosong, tolak dengan pesan yang ramah
if ($nama === '' || $kontak === '' || $kategori === '' || $pesan === '') {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Nama, kontak, kategori, dan isi pesan wajib diisi.']);
    exit;
}

// (4) Batasi panjang isian agar tidak melebihi kapasitas (nama ≤100, kontak ≤150, pesan ≤5000 huruf)
if (mb_strlen($nama) > 100 || mb_strlen($kontak) > 150 || mb_strlen($pesan) > 5000) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Data pesan melebihi batas panjang yang diizinkan.']);
    exit;
}

// Whitelist kategori — mencegah nilai sembarang tersimpan di JSON
$kategoriValid = ['info', 'layanan', 'pengaduan', 'saran', 'lainnya']; // (5) Daftar kategori yang diizinkan: info, layanan, pengaduan, saran, lainnya
if (!in_array($kategori, $kategoriValid, true)) {                      // (5) Cek: apakah kategori yang dikirim ada di daftar resmi?
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Kategori pesan tidak valid.']);
    exit;
}

// (6) Serahkan ke Model Pesan untuk disimpan ke file JSON (otomatis diberi nomor id)
$item = Pesan::create([
    'nama' => $nama,         // Isi kolom "nama" dari ketikan pengguna
    'kontak' => $kontak,     // Isi kolom "kontak" dari ketikan pengguna
    'kategori' => $kategori, // Isi kolom "kategori" dari ketikan pengguna
    'pesan' => $pesan,       // Isi kolom "pesan" dari ketikan pengguna
]);

// (7) Kirim kabar sukses + nomor id pesan dalam format JSON agar tampil sebagai notifikasi
echo json_encode([
    'success' => true,
    'message' => 'Pesan Anda berhasil dikirim dan akan diproses pada jam kerja.',
    'data' => ['id' => $item['id']],
], JSON_UNESCAPED_UNICODE);
