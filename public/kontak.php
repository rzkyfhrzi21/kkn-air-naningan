<?php

/*
 * ======================================================
 * HALAMAN KONTAK (PUBLIK)
 *
 * File ini ibarat meja resepsionis: pengunjung yang membuka
 * alamat .../kontak akan melihat formulir untuk mengirim
 * pesan kepada pekon beserta informasi kontak lainnya.
 *
 * Alur kerjanya:
 * (1) Memanggil Controller KontakController — petugas yang
 *     menyiapkan data kontak (alamat, nomor WhatsApp, dsb).
 * (2) Menjalankan fungsi index() miliknya, yang mengambil
 *     data kontak dari Model lalu menyerahkannya ke View
 *     halaman kontak agar tampil rapi.
 *
 * Catatan: `declare(strict_types=1)` memaksa PHP menjaga
 * ketepatan tipe data, seperti resepsionis yang mencatat
 * pesan dengan teliti.
 * ======================================================
 */

declare(strict_types=1);

// (1) Panggil Controller KontakController agar fungsi-fungsinya siap dipakai
require_once __DIR__ . '/../app/Controllers/KontakController.php';

// (2) Jalankan fungsi index() — menyiapkan data kontak & menampilkan halamannya
(new KontakController())->index();
