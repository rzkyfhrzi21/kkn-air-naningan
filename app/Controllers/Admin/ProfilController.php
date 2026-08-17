<?php

declare(strict_types=1);

/* ======================================================
   CONTROLLER PROFIL ADMIN (DATA AKUN YANG LOGIN)

   Tugas utama: membuka halaman "Profil" di panel admin —
   tempat admin melihat/mengubah data dirinya sendiri
   (nama lengkap, username, WhatsApp, email, foto).
   Ibarat ruang ganti kartu pegawai: sebelum masuk, kartu
   identitas (sesi admin) diperiksa dulu, lalu data diri
   yang tersimpan diambil untuk ditampilkan di formulir.

   PENTING: data akun dibaca dari file JSON secure lewat
   Model Akun (app/Models/Akun.php) — controller TIDAK
   membaca file langsung.

   Halaman yang dilayani:
   - View : app/Views/admin/profil/index.php
====================================================== */

final class ProfilController
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

        // (2) Panggil Model Akun (tempat baca/tulis data akun admin).
        //     Dibutuhkan untuk mengambil data diri admin dari file.
        require_once __DIR__ . '/../../Models/Akun.php';

        // (3) Siapkan kartu undangan anti-CSRF (token keamanan acak)
        //     untuk form edit profil di View.
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        // (4) Ambil data akun admin dari FILE via Model Akun::get(),
        //     lalu susun ulang menjadi array $akun berisi hanya kolom
        //     yang dibutuhkan View:
        //     - nama_lengkap : nama pegawai
        //     - username     : nama akun untuk login
        //     - whatsapp     : nomor WhatsApp yang ditampilkan di situs
        //     - email        : alamat surel admin
        //     - foto         : nama berkas foto (di folder uploads/profil/)
        $creds = Akun::get();
        $akun  = [
            'nama_lengkap' => (string) ($creds['nama_lengkap'] ?? ''),
            'username'     => (string) ($creds['username'] ?? ''),
            'whatsapp'     => (string) ($creds['whatsapp'] ?? ''),
            'email'        => (string) ($creds['email'] ?? ''),
            'foto'         => (string) ($creds['foto'] ?? ''),
        ];

        // (5) Antar data akun ke View profil untuk dirender.
        require __DIR__ . '/../../Views/admin/profil/index.php';
    }
}
