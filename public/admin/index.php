<?php

/*
 * ======================================================
 * HALAMAN UTAMA ADMIN (DASHBOARD)
 *
 * File ini ibarat pintu masuk ruang kerja Kepala Desa: hanya
 * petugas (admin) yang sudah masuk (login) yang boleh lewat
 * sini untuk melihat ringkasan kegiatan desa.
 *
 * Alur kerjanya:
 * (1) Memanggil Controller DashboardController — petugas
 *     belakang layar yang menghitung ringkasan data.
 * (2) Menjalankan fungsi index() miliknya, yang memastikan
 *     pengunjung sudah login, lalu menyiapkan angka ringkasan
 *     (jumlah UMKM, berita, pesan masuk, dsb.) dari Model dan
 *     mengirimkannya ke View dashboard.
 *
 * Catatan: `declare(strict_types=1)` membuat PHP ketat soal
 * tipe data, seperti satpam yang selalu mengecek tanda pengenal.
 * ======================================================
 */

declare(strict_types=1);

// (1) Panggil Controller DashboardController agar fungsi-fungsinya siap dipakai
require_once __DIR__ . '/../../app/Controllers/Admin/DashboardController.php';

// (2) Jalankan fungsi index() — cek login, siapkan ringkasan data & tampilkan dashboard
(new DashboardController())->index();
