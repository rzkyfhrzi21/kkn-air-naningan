<?php

/*
 * ======================================================
 * HALAMAN UTAMA SITUS (BERANDA)
 *
 * File ini ibarat pintu masuk utama gedung: setiap pengunjung
 * yang membuka alamat situs ini akan masuk lewat sini, lalu
 * diarahkan ke Controller dan View yang sesuai.
 *
 * Alur kerjanya sangat pendek, hanya 2 langkah:
 * (1) Memanggil (require) file Controller beranda.
 * (2) Membuat Controller-nya lalu menjalankan fungsi index(),
 *     yang bertugas menyiapkan semua data halaman depan
 *     (berita terbaru, profil pekon, dsb) dari Model JSON
 *     dan mengirimkannya ke View beranda.
 *
 * Catatan: `declare(strict_types=1)` membuat PHP bersikap
 * ketat terhadap tipe data — seperti satpam yang memastikan
 * setiap tamu masuk dengan "identitas" yang benar.
 * ======================================================
 */

declare(strict_types=1);

// (1) Panggil file Controller HomeController agar fungsi-fungsinya siap dipakai
require_once __DIR__ . '/../app/Controllers/HomeController.php';

// (2) Jalankan fungsi index() milik HomeController — dia yang menyiapkan data & menampilkan halaman
(new HomeController())->index();
