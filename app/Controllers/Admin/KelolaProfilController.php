<?php

declare(strict_types=1);

/* ======================================================
   CONTROLLER KELOLA PROFIL PEKON (PANEL ADMIN)

   Tugas utama: membuka halaman pengelolaan profil desa
   (sejarah, visi, misi, sambutan, dan informasi dasar
   Pekon Air Naningan). Ibarat petugas yang membukakan
   pintu ruang arsip profil desa: menyalakan lampu (sesi),
   memeriksa kartu identitas (sesi admin), lalu mengambil
   buku profil yang ada untuk ditampilkan di formulir edit.

   PENTING: data profil dibaca dari file JSON via Model
   Profil (app/Models/Profil.php), bukan dibaca langsung
   oleh controller.

   Halaman yang dilayani:
   - View : app/Views/admin/kelola-profil/index.php
====================================================== */

require_once __DIR__ . '/../../Models/Profil.php';

final class KelolaProfilController
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
        //     untuk form edit profil di View.
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        // (3) Ambil data profil desa saat ini dari FILE JSON via Model
        //     Profil::get() (data dari file public/data/profil.json).
        //     Data ini mengisi kolom-kolom formulir edit.
        $profil = Profil::get();
        // (4) Antar data profil ke View kelola-profil untuk dirender.
        require __DIR__ . '/../../Views/admin/kelola-profil/index.php';
    }
}
