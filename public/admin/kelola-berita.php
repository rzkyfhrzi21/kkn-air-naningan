<?php

/*
 * ======================================================
 * HALAMAN KELOLA BERITA (ADMIN)
 *
 * File ini ibarat ruang redaksi kantor desa: admin datang ke
 * sini untuk menambah, mengubah, atau menghapus berita yang
 * akan tampil di situs.
 *
 * Alur kerjanya:
 * (1) Memanggil Controller KelolaBeritaController — petugas
 *     yang mengatur semua data berita di file JSON.
 * (2) Menjalankan fungsi index() miliknya, yang memastikan
 *     admin sudah login, lalu menyiapkan daftar berita dan
 *     mengirimkannya ke View halaman kelola berita.
 *
 * Catatan: `declare(strict_types=1)` membuat PHP ketat soal
 * tipe data, seperti penulis berita yang selalu menulis
 * dengan ejaan yang benar.
 * ======================================================
 */

declare(strict_types=1);

// (1) Panggil Controller KelolaBeritaController agar fungsi-fungsinya siap dipakai
require_once __DIR__ . '/../../app/Controllers/Admin/KelolaBeritaController.php';

// (2) Jalankan fungsi index() — cek login, siapkan data berita & tampilkan halaman kelola berita
(new KelolaBeritaController())->index();
