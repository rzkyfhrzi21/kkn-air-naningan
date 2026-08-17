<?php

declare(strict_types=1);

/* ======================================================
   CONTROLLER PESAN MASUK (PANEL ADMIN)

   Tugas utama: membuka halaman "pesan masuk" di panel
   admin — tempat admin membaca pesan yang dikirim
   pengunjung lewat formulir kontak situs. Ibarat petugas
   yang membukakan pintu ruang kotak surat: menyalakan
   lampu (sesi), memeriksa kartu identitas (sesi admin),
   lalu mempersilakan admin masuk melihat isi kotak surat.

   PENTING: isi daftar pesan di halaman ini TIDAK dirender
   penuh dari controller — tabel pesan diisi secara AJAX
   lewat endpoint public/admin/ajax/list-pesan.php. Data
   pesan disimpan Model Pesan (app/Models/Pesan.php) pada
   file JSON public/data/pesan.json.

   Halaman yang dilayani:
   - View : app/Views/admin/pesan-masuk/index.php
====================================================== */

require_once __DIR__ . '/../../Models/Pesan.php';

final class PesanMasukController
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

        // (2) Langsung render View pesan-masuk. Data pesan di tabel nanti
        //     diambil lewat AJAX (public/admin/ajax/list-pesan.php).
        require __DIR__ . '/../../Views/admin/pesan-masuk/index.php';
    }
}