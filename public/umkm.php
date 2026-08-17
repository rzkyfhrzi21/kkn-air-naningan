<?php

/*
 * ======================================================
 * HALAMAN UMKM (PUBLIK)
 *
 * File ini ibarat pintu masuk pasar desa: pengunjung yang
 * membuka alamat .../umkm akan melihat daftar usaha kecil
 * milik warga Pekon Air Naningan.
 *
 * Alur kerjanya:
 * (1) Memanggil Controller UmkmController — petugas yang
 *     mengumpulkan semua data UMKM dari file JSON.
 * (2) Menjalankan fungsi index() miliknya, yang membaca data
 *     lewat Model `Umkm` lalu mengirimkannya ke View halaman
 *     UMKM agar setiap usaha tampil sebagai satu kartu.
 *
 * Catatan: `declare(strict_types=1)` membuat PHP ketat soal
 * tipe data, seperti petugas pasar yang memeriksa kelengkapan
 * kios sebelum dibuka.
 * ======================================================
 */

declare(strict_types=1);

// (1) Panggil Controller UmkmController agar fungsi-fungsinya siap dipakai
require_once __DIR__ . '/../app/Controllers/UmkmController.php';

// (2) Jalankan fungsi index() — menyiapkan data UMKM & menampilkan halamannya
(new UmkmController())->index();
