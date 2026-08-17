<?php

/*
 * ======================================================
 * HALAMAN KELOLA GALERI (ADMIN)
 *
 * File ini ibarat ruang pameran foto kantor desa: admin
 * datang ke sini untuk menambah, mengubah, atau menghapus
 * foto dan video galeri yang tampil di situs.
 *
 * Alur kerjanya:
 * (1) Memanggil Controller KelolaGaleriController — petugas
 *     yang mengatur semua data galeri di file JSON.
 * (2) Menjalankan fungsi index() miliknya, yang memastikan
 *     admin sudah login, lalu menyiapkan daftar galeri dan
 *     mengirimkannya ke View halaman kelola galeri.
 *
 * Catatan: `declare(strict_types=1)` membuat PHP ketat soal
 * tipe data, seperti kurator pameran yang teliti memeriksa
 * setiap foto sebelum dipajang.
 * ======================================================
 */

declare(strict_types=1);

// (1) Panggil Controller KelolaGaleriController agar fungsi-fungsinya siap dipakai
require_once __DIR__ . '/../../app/Controllers/Admin/KelolaGaleriController.php';

// (2) Jalankan fungsi index() — cek login, siapkan data galeri & tampilkan halaman kelola galeri
(new KelolaGaleriController())->index();
