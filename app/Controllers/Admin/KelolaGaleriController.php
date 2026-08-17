<?php

declare(strict_types=1);

/* ======================================================
   CONTROLLER KELOLA GALERI (PANEL ADMIN)

   Tugas utama: membuka pintu halaman pengelolaan galeri
   foto di panel admin. Ibarat petugas yang membukakan
   pintu ruang pamer foto: sebelum admin masuk, petugas
   menyalakan lampu (membangunkan sesi), memeriksa kartu
   identitas (sesi admin), lalu menyiapkan daftar kategori
   foto yang tersedia untuk diisi di form.

   PENTING: controller ini TIDAK menyimpan/menghapus foto
   secara langsung — semua data galeri ditangani Model Galeri
   (app/Models/Galeri.php). Controller hanya menyiapkan
   data untuk View.

   Halaman yang dilayani:
   - View : app/Views/admin/kelola-galeri/index.php
     (tabel & aksi di halaman itu berjalan via AJAX, lihat
      endpoint public/admin/ajax/*)
====================================================== */

require_once __DIR__ . '/../../Models/Galeri.php';

final class KelolaGaleriController
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
        //     untuk form tambah/edit galeri di View.
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        // (3) Ambil daftar kategori galeri dari Model Galeri (konstanta
        //     KATEGORI di app/Models/Galeri.php) — dipakai untuk dropdown
        //     kategori pada form tambah foto.
        $kategori = Galeri::KATEGORI;
        // (4) Antar daftar kategori ke View kelola-galeri untuk dirender.
        require __DIR__ . '/../../Views/admin/kelola-galeri/index.php';
    }
}