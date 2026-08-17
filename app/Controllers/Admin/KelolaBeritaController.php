<?php

declare(strict_types=1);

/* ======================================================
   CONTROLLER KELOLA BERITA (PANEL ADMIN)

   Tugas utama: membuka pintu halaman pengelolaan berita
   di panel admin dan menyiapkan data pendukung yang
   dibutuhkan tampilannya. Ibarat petugas yang membukakan
   pintu gudang arsip berita: sebelum admin masuk, petugas
   sudah menyalakan lampu (membangunkan sesi), memeriksa
   kartu identitas (sesi admin), lalu menyiapkan daftar
   kategori berita yang ada supaya mudah difilter.

   PENTING: Controller ini TIDAK mengelola berita secara
   langsung — semua baca/tulis data berita dilakukan Model
   Berita (app/Models/Berita.php). Controller hanya memanggil
   Model dan menyiapkan data untuk View.

   Halaman yang dilayani:
   - View : app/Views/admin/kelola-berita/index.php
     (tabel berita di halaman itu diisi via AJAX, bukan dari
      controller ini — lihat endpoint public/admin/ajax/*)
====================================================== */

require_once __DIR__ . '/../../Models/Berita.php';

final class KelolaBeritaController
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

        // (2) Siapkan kartu undangan anti-CSRF (token keamanan acak).
        //     Form tambah/edit berita di View akan membawa token ini,
        //     supaya kiriman data palsu dari luar bisa ditolak nanti.
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        // (3) Ambil SEMUA data berita dari file JSON via Model Berita::all(),
        //     lalu kumpulkan daftar kategori yang unik (tidak ada duplikat).
        //     Daftar ini dipakai untuk dropdown filter kategori di halaman.
        //     Data ini berasal dari FILE (public/data/berita.json), bukan
        //     dari ketikan form.
        $kategoriList = array_values(array_unique(array_filter(array_map(
            static fn(array $item): string => trim((string) ($item['kategori'] ?? '')),
            Berita::all()
        ))));
        sort($kategoriList, SORT_NATURAL | SORT_FLAG_CASE);

        // (4) Antar daftar kategori ke View kelola-berita untuk dirender.
        require __DIR__ . '/../../Views/admin/kelola-berita/index.php';
    }
}
