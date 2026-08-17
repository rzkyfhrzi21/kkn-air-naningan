<?php

/*
 * ======================================================
 * HALAMAN PESAN MASUK (ADMIN)
 *
 * File ini ibarat kotak surat kantor desa: semua pesan yang
 * dikirim pengunjung lewat halaman Kontak akan terkumpul di
 * sini agar bisa dibaca dan dijawab oleh admin.
 *
 * Alur kerjanya:
 * (1) Memanggil Controller PesanMasukController — petugas
 *     yang membaca semua pesan dari file JSON.
 * (2) Menjalankan fungsi index() miliknya, yang memastikan
 *     admin sudah login, lalu menyiapkan daftar pesan masuk
 *     dan mengirimkannya ke View halaman pesan masuk.
 *
 * Catatan: `declare(strict_types=1)` membuat PHP ketat soal
 * tipe data, seperti petugas pos yang teliti mengurutkan
 * setiap surat yang masuk.
 * ======================================================
 */

declare(strict_types=1);

// (1) Panggil Controller PesanMasukController agar fungsi-fungsinya siap dipakai
require_once __DIR__ . '/../../app/Controllers/Admin/PesanMasukController.php';

// (2) Jalankan fungsi index() — cek login, siapkan daftar pesan masuk & tampilkan halamannya
(new PesanMasukController())->index();
