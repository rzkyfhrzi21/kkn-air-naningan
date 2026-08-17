<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/env.php';
require_once __DIR__ . '/../app/Models/Berita.php';

loadEnv(__DIR__ . '/../.env');

$baseUrl = rtrim((string) env('APP_URL', 'https://pekon-air-naningan.web.id'), '/');

/** @return string ISO 8601 date accepted by sitemap parsers. */
function sitemapDate(string $value, string $fallbackFile = ''): string
{
    $timestamp = $value !== '' ? strtotime($value) : false;
    if ($timestamp === false && $fallbackFile !== '' && is_file($fallbackFile)) {
        $timestamp = filemtime($fallbackFile);
    }

    return $timestamp === false ? '' : date('c', $timestamp);
}

/** @return string Waktu modifikasi terbaru di antara daftar file. */
function sitemapNewest(string $directory): string
{
    $newest = 0;
    foreach (glob($directory . '/*.json') ?: [] as $file) {
        $mtime = filemtime($file);
        if ($mtime !== false && $mtime > $newest) {
            $newest = $mtime;
        }
    }

    return $newest > 0 ? date('c', $newest) : '';
}

function sitemapEntry(
    string $location,
    string $lastModified = '',
    string $changeFrequency = 'weekly',
    string $priority = '0.5',
    string $image = ''
): string {
    $xml  = "  <url>\n    <loc>" . htmlspecialchars($location, ENT_XML1, 'UTF-8') . "</loc>\n";
    if ($lastModified !== '') {
        $xml .= '    <lastmod>' . htmlspecialchars($lastModified, ENT_XML1, 'UTF-8') . "</lastmod>\n";
    }
    $xml .= '    <changefreq>' . $changeFrequency . "</changefreq>\n";
    $xml .= '    <priority>' . $priority . "</priority>\n";
    if ($image !== '') {
        $xml .= "    <image:image>\n";
        $xml .= '      <image:loc>' . htmlspecialchars($image, ENT_XML1, 'UTF-8') . "</image:loc>\n";
        $xml .= "    </image:image>\n";
    }

    return $xml . "  </url>\n";
}

$dataDir = __DIR__ . '/data';
$assetsDir = $baseUrl . '/assets/images';
$staticPages = [
    ['/', sitemapNewest($dataDir), 'daily', '1.0', $assetsDir . '/logo.jpg'],
    ['/profil', sitemapDate('', $dataDir . '/profil.json'), 'monthly', '0.8', ''],
    ['/umkm', sitemapDate('', $dataDir . '/umkm.json'), 'weekly', '0.8', ''],
    ['/berita', sitemapDate('', $dataDir . '/berita.json'), 'daily', '0.8', ''],
    ['/galeri', sitemapDate('', $dataDir . '/galeri.json'), 'weekly', '0.7', ''],
    ['/kontak', '', 'monthly', '0.7', ''],
];

$urls = '';
foreach ($staticPages as [$path, $lastmod, $frequency, $priority, $image]) {
    $urls .= sitemapEntry(
        $baseUrl . ($path === '/' ? '/' : $path),
        $lastmod,
        $frequency,
        $priority,
        $image
    );
}

foreach (Berita::published() as $article) {
    $slug = trim((string) ($article['slug'] ?? ''));
    if ($slug === '') {
        continue;
    }

    $lastModified = sitemapDate((string) ($article['updated_at'] ?? $article['tanggal_terbit'] ?? ''));
    $urls .= sitemapEntry($baseUrl . '/berita/' . rawurlencode($slug), $lastModified, 'monthly', '0.6', '');
}

header('Content-Type: application/xml; charset=utf-8');
header('X-Content-Type-Options: nosniff');

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"'
   . ' xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";
echo $urls;
echo '</urlset>' . "\n";
