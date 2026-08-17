<?php

/*
 * ======================================================
 * HALAMAN PROFIL PEKON (PUBLIK)
 *
 * File ini ibarat pintu masuk ruang profil desa: pengunjung
 * yang membuka alamat .../profil akan melihat sejarah,
 * visi-misi, dan informasi umum tentang Pekon Air Naningan.
 *
 * Alur kerjanya:
 * (1) Memanggil Controller ProfilController — petugas yang
 *     mengambil data profil dari file JSON.
 * (2) Menjalankan fungsi index() miliknya, yang membaca data
 *     lewat Model lalu mengirimkannya ke View halaman profil
 *     agar ditampilkan sebagai halaman yang rapi.
 *
 * Catatan: `declare(strict_types=1)` membuat PHP ketat soal
 * tipe data, seperti petugas yang memastikan setiap berkas
 * profil dicatat dengan benar.
 * ======================================================
 */

declare(strict_types=1);

// (1) Panggil Controller ProfilController agar fungsi-fungsinya siap dipakai
require_once __DIR__ . '/../app/Controllers/ProfilController.php';

// (2) Jalankan fungsi index() — menyiapkan data profil & menampilkan halamannya
(new ProfilController())->index();
