<?php

/*
 * ======================================================
 * HALAMAN BERITA (PUBLIK)
 *
 * File ini ibarat gerbang khusus menuju ruang "Berita":
 * saat pengunjung membuka alamat .../berita, file inilah
 * yang pertama kali bekerja.
 *
 * Alur kerjanya:
 * (1) Memanggil Controller BeritaController — juru masak
 *     yang menyiapkan bahan (data berita dari file JSON).
 * (2) Menjalankan fungsi index() miliknya, yang membaca
 *     semua berita dari Model `Berita` lalu menyerahkannya
 *     ke View halaman berita untuk ditampilkan.
 *
 * Catatan: `declare(strict_types=1)` memaksa PHP menjaga
 * ketepatan tipe data (misal teks tetap teks, angka tetap
 * angka) — seperti petugas yang mengecek tiket sebelum masuk.
 * ======================================================
 */

declare(strict_types=1);

// (1) Panggil Controller BeritaController agar fungsi-fungsinya siap dipakai
require_once __DIR__ . '/../app/Controllers/BeritaController.php';

// (2) Jalankan fungsi index() — menyiapkan daftar berita & menampilkan halaman berita
(new BeritaController())->index();
