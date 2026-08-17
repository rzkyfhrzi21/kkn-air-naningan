<?php

/*
 * ======================================================
 * HALAMAN GALERI (PUBLIK)
 *
 * File ini ibarat pintu masuk ruang pameran foto: saat
 * pengunjung membuka alamat .../galeri, file inilah yang
 * membukakan pintunya.
 *
 * Alur kerjanya:
 * (1) Memanggil Controller GaleriController — petugas yang
 *     mengumpulkan semua foto & video dari file JSON galeri.
 * (2) Menjalankan fungsi index() miliknya, yang membaca data
 *     lewat Model `Galeri` lalu mengirimkannya ke View galeri
 *     agar ditampilkan sebagai kumpulan foto.
 *
 * Catatan: `declare(strict_types=1)` membuat PHP ketat soal
 * tipe data, seperti petugas loket yang selalu memeriksa
 * identitas pengunjung.
 * ======================================================
 */

declare(strict_types=1);

// (1) Panggil Controller GaleriController agar fungsi-fungsinya siap dipakai
require_once __DIR__ . '/../app/Controllers/GaleriController.php';

// (2) Jalankan fungsi index() — menyiapkan data galeri & menampilkan halamannya
(new GaleriController())->index();
