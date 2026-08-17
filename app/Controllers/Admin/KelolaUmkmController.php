<?php

declare(strict_types=1);

/* ======================================================
   CONTROLLER KELOLA UMKM (PANEL ADMIN)

   Tugas utama: membuka halaman pengelolaan daftar usaha
   UMKM warga Pekon Air Naningan. Ibarat petugas yang
   membukakan pintu ruang arsip usaha warga: menyalakan
   lampu (sesi), memeriksa kartu identitas (sesi admin),
   lalu menyiapkan buku daftar UMKM dan pilihan kategori
   untuk halaman tersebut.

   PENTING: seluruh baca/tulis data UMKM ditangani Model
   Umkm (app/Models/Umkm.php) yang membaca/menulis file
   JSON public/data/umkm.json. Controller hanya memanggil
   Model dan mengantar data ke View.

   Halaman yang dilayani:
   - View : app/Views/admin/kelola-umkm/index.php
     (tabel & aksi di halaman itu berjalan via AJAX, lihat
      endpoint public/admin/ajax/*)
====================================================== */

require_once __DIR__ . '/../../Models/Umkm.php';

final class KelolaUmkmController
{
    public function index(): void
    {
        // (1) JAGA PINTU: nyalakan sesi (ibarat membuka buku tamu),
        //     lalu cek apakah pengunjung sudah login sebagai admin.
        //     Kalau belum, arahkan ke halaman login.
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['admin'])) {
            $base = defined('APP_BASE') ? APP_BASE : '';
            header('Location: ' . $base . '/admin/login');
            exit;
        }
        // (2) Siapkan kartu undangan anti-CSRF (token keamanan acak)
        //     untuk form tambah/edit UMKM di View.
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        // (3) Ambil data UMKM dari FILE JSON via Model Umkm::all()
        //     (public/data/umkm.json) dan daftar kategori dari konstanta
        //     Umkm::KATEGORI (dropdown filter & form).
        $items    = Umkm::all();
        $kategori = Umkm::KATEGORI;
        // (4) Antar data UMKM & kategori ke View kelola-umkm untuk dirender.
        require __DIR__ . '/../../Views/admin/kelola-umkm/index.php';
    }
}
