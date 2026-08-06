<?php

declare(strict_types=1);

/**
 * sitemap.php — Sitemap XML dinamis untuk Pekon Air Naningan
 * Generate otomatis dari data JSON yang tersedia.
 */

require_once __DIR__ . '/../includes/env.php';
loadEnv(__DIR__ . '/../.env');

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host   = $_SERVER['HTTP_HOST'] ?? 'localhost:8090';

// Deteksi base path dari SCRIPT_NAME
$scriptDir = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/');
// /kkn-air-naningan/sitemap.php → base = /kkn-air-naningan
$base = $scriptDir;

$baseUrl = $scheme . '://' . $host . $base;

// Helper: tulis satu <url> entry
function sitemapUrl(string $loc, string $changefreq = 'weekly', string $priority = '0.8', string $lastmod = ''): string
{
    $entry  = '  <url>' . PHP_EOL;
    $entry .= '    <loc>' . htmlspecialchars($loc, ENT_XML1, 'UTF-8') . '</loc>' . PHP_EOL;
    if ($lastmod !== '') {
        $entry .= '    <lastmod>' . htmlspecialchars($lastmod, ENT_XML1) . '</lastmod>' . PHP_EOL;
    }
    $entry .= '    <changefreq>' . $changefreq . '</changefreq>' . PHP_EOL;
    $entry .= '    <priority>' . $priority . '</priority>' . PHP_EOL;
    $entry .= '  </url>' . PHP_EOL;
    return $entry;
}

// Kumpulkan semua URL
$urls = '';

// ── Halaman statis ──────────────────────────────────────────────────────────
$staticPages = [
    ['/',        'daily',   '1.0'],
    ['/profil',  'monthly', '0.9'],
    ['/umkm',    'weekly',  '0.8'],
    ['/wisata',  'weekly',  '0.8'],
    ['/berita',  'daily',   '0.8'],
    ['/galeri',  'weekly',  '0.7'],
    ['/kontak',  'monthly', '0.6'],
];

$profilJson = __DIR__ . '/data/profil.json';
$updatedAt  = file_exists($profilJson) ? date('Y-m-d', filemtime($profilJson)) : date('Y-m-d');

foreach ($staticPages as [$path, $freq, $prio]) {
    $urls .= sitemapUrl($baseUrl . $path, $freq, $prio, $updatedAt);
}

// ── Berita dinamis ───────────────────────────────────────────────────────────
require_once dirname(__DIR__) . '/app/Models/Berita.php';
foreach (Berita::published() as $item) {
        $slug = trim((string) ($item['slug'] ?? $item['id'] ?? ''));
        if ($slug === '') {
            continue;
        }
        $lastmod = '';
        if (!empty($item['tanggal_terbit'])) {
            $ts = strtotime((string) $item['tanggal_terbit']);
            if ($ts !== false) {
                $lastmod = date('Y-m-d', $ts);
            }
        }
        $urls .= sitemapUrl($baseUrl . '/berita/' . rawurlencode($slug), 'monthly', '0.6', $lastmod);
}

// ── UMKM dinamis ─────────────────────────────────────────────────────────────
$umkmFile = __DIR__ . '/data/umkm.json';
if (file_exists($umkmFile)) {
    $umkmData = json_decode(file_get_contents($umkmFile), true) ?? [];
    foreach ($umkmData as $item) {
        $slug = trim((string) ($item['slug'] ?? $item['id'] ?? ''));
        if ($slug === '') {
            continue;
        }
        $urls .= sitemapUrl($baseUrl . '/umkm?id=' . urlencode($slug), 'monthly', '0.5');
    }
}

// ── Wisata dinamis ────────────────────────────────────────────────────────────
$wisataFile = __DIR__ . '/data/wisata.json';
if (file_exists($wisataFile)) {
    $wisataData = json_decode(file_get_contents($wisataFile), true) ?? [];
    foreach ($wisataData as $item) {
        $slug = trim((string) ($item['slug'] ?? $item['id'] ?? ''));
        if ($slug === '') {
            continue;
        }
        $urls .= sitemapUrl($baseUrl . '/wisata?id=' . urlencode($slug), 'monthly', '0.6');
    }
}

// ── Output XML ───────────────────────────────────────────────────────────────
header('Content-Type: application/xml; charset=utf-8');
header('X-Robots-Tag: noindex'); // Sitemap sendiri tidak perlu diindex

echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;
echo $urls;
echo '</urlset>' . PHP_EOL;
