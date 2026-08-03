<?php

declare(strict_types=1);

require_once __DIR__ . '/../Models/Berita.php';

final class BeritaController
{
    /** GET /berita — daftar semua berita terbit */
    public function index(): void
    {
        $beritaList = Berita::published();
        require __DIR__ . '/../Views/public/berita/index.php';
    }

    /**
     * GET /berita/{slug} — detail satu artikel
     * $slug diambil dari $GLOBALS['_beritaSlug'] yang di-set router di index.php
     */
    public function detail(): void
    {
        $slug   = $GLOBALS['_beritaSlug'] ?? '';
        $berita = Berita::findBySlug($slug);

        if ($berita === null || ($berita['status'] ?? '') !== 'terbit') {
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

        // Berita terpopuler (4 berita terbit terbaru, kecuali yang sedang dibaca)
        $terkait = array_values(
            array_filter(
                Berita::latest(5),
                static fn(array $b): bool => ($b['id'] ?? '') !== ($berita['id'] ?? '')
            )
        );
        $terkait = array_slice($terkait, 0, 4);

        require __DIR__ . '/../Views/public/berita/detail.php';
    }
}
