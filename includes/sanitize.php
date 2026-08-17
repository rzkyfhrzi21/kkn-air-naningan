<?php

declare(strict_types=1);

/*
 * ======================================================
 * PEMBERSIH KONTEN TEKS / FILTER KEAMANAN HTML (SANITIZE)
 *
 * File ini ibarat saringan air di dapur. Saat admin menginput teks bercetak tebal,
 * miring, atau daftar berpoin melalui editor teks, fungsi `sanitize_rich_html()`
 * akan menyaring teks tersebut agar hanya tag HTML yang aman saja yang boleh masuk.
 *
 * Tag berbahaya seperti `<script>` (skrip peretas) atau link beracun `javascript:`
 * akan dibuang otomatis demi keamanan seluruh pengunjung situs.
 * ======================================================
 */

/**
 * Sanitize rich HTML produced by admin content editors (narasi profil,
 * konten berita) to a safe whitelist of tags. Strip script, event handlers,
 * and javascript: URLs.
 */
function sanitize_rich_html(string $html): string
{
    // (1) Daftar tag HTML yang diizinkan (teks tebal, miring, paragraf, list, dll)
    $allowed = '<p><br><strong><b><em><i><u><h2><h3><h4><blockquote><ul><ol><li><a><span>';
    
    // (2) Buang semua tag HTML di luar daftar putih di atas menggunakan strip_tags()
    $html    = strip_tags($html, $allowed);
    
    // (3) Hapus event javascript berbahaya seperti onclick=..., onload=...
    $html    = preg_replace('/\son\w+\s*=\s*("(?:[^"]*)"|\'(?:[^\']*)\'|[^\s>]+)/i', '', $html) ?? $html;
    
    // (4) Netralkan link `javascript:...` menjadi link aman `href="#"`
    $html    = preg_replace('/href\s*=\s*("javascript:[^"]*"|\'javascript:[^\']*\')/i', 'href="#"', $html) ?? $html;
    
    // (5) Kembalikan teks bersih yang sudah aman setelah dibuang spasi kosong di ujungnya
    return trim($html);
}

