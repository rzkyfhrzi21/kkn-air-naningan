<?php

declare(strict_types=1);

/* ======================================================
   CONTROLLER BERITA (SISI PUBLIK / PENGUNJUNG)

   Tugas utama: melayani halaman berita yang dilihat
   pengunjung situs. Ibarat petugas papan pengumuman di
   depan balai desa: kalau ada yang minta lihat daftar
   pengumuman, petugas menempelkan daftar berita terbit;
   kalau ada yang menanyakan satu pengumuman tertentu
   (lewat link berisi slug), petugas mengambil isi lengkap
   artikel itu beserta beberapa berita terkait.

   PENTING (aturan MVC + SEO): semua data diambil dari
   file JSON via Model Berita (app/Models/Berita.php).
   Halaman publik WAJIB dirender penuh oleh PHP pada
   kunjungan pertama (bukan menunggu AJAX) supaya mesin
   pencari (Google dll) bisa membaca isinya.

   Halaman yang dilayani:
   - View daftar : app/Views/public/berita/index.php
   - View detail : app/Views/public/berita/detail.php
====================================================== */

require_once __DIR__ . '/../Models/Berita.php';

final class BeritaController
{
    /** GET /berita — daftar semua berita terbit */
    public function index(): void
    {
        // (1) Minta Model Berita mengeluarkan semua berita yang statusnya
        //     "terbit" (draft tidak boleh muncul di situs publik).
        //     Data ini DATA DARI FILE (public/data/berita.json) via Model.
        $beritaList = Berita::published();
        // (2) Render halaman daftar berita; daftar sudah lengkap di
        //     kunjungan pertama (server-rendered, ramah SEO).
        require __DIR__ . '/../Views/public/berita/index.php';
    }

    /**
     * GET /berita/{slug} — detail satu artikel
     * $slug diambil dari $GLOBALS['_beritaSlug'] yang di-set router di index.php
     */
    public function detail(): void
    {
        // (1) Ambil slug (nama singkat artikel) dari router. Slug ini
        //     berasal dari LINK/URL yang diklik pengunjung, bukan dari form.
        $slug   = $GLOBALS['_beritaSlug'] ?? '';
        // (2) Cari artikel di file JSON via Model (Berita::findBySlug).
        $berita = Berita::findBySlug($slug);

        // (3) Kalau artikel tidak ketemu ATAU statusnya belum terbit,
        //     tampilkan halaman "404 — Berita tidak ditemukan" sederhana
        //     (dengan kode HTTP 404 yang benar agar mesin pencari tahu
        //     halaman ini tidak ada), lalu berhenti di sini.
        if ($berita === null || !Berita::isPublished($berita)) {
            http_response_code(404);
            $base = defined('APP_BASE') ? APP_BASE : '';
            echo '<!doctype html><html lang="id"><head><meta charset="utf-8"><title>404 — Pekon Air Naningan</title>'
               . '<style>body{font-family:sans-serif;display:flex;align-items:center;justify-content:center;'
               . 'min-height:100vh;background:#12201A;color:#F3ECDA;margin:0}a{color:#f2bf5d}</style>'
               . '</head><body><div style="text-align:center">'
               . '<h1 style="font-size:5rem;margin:0;opacity:.4">404</h1>'
               . '<p>Berita tidak ditemukan.</p>'
               . '<a href="' . htmlspecialchars($base . '/berita') . '">← Kembali ke Berita</a>'
               . '</div></body></html>';
            return;
        }

        // (4) Susun daftar "Berita Terkait": ambil 1 berita terbaru dari
        //     TIAP kategori (kecuali artikel yang sedang dibaca ini),
        //     maksimal 4 berita — supaya pengunjung punya rekomendasi
        //     bacaan lain setelah selesai membaca.
        // Berita terbaru: 1 dari masing-masing kategori (kecuali yang sedang dibaca)
        $terkait = [];
        $kategoriSeen = [];
        foreach (Berita::published() as $b) {
            if (($b['id'] ?? '') === ($berita['id'] ?? '')) continue;
            $kat = $b['kategori'] ?? '';
            if (!isset($kategoriSeen[$kat])) {
                $kategoriSeen[$kat] = true;
                $terkait[] = $b;
            }
            if (count($terkait) >= 4) break;
        }

        // (5) Render halaman detail artikel beserta daftar berita terkait.
        require __DIR__ . '/../Views/public/berita/detail.php';
    }
}
