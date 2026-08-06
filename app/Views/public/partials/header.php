<?php
// header.php — Public site header partial
// Variables expected: $pageTitle (string), $currentPage (string), $metaDescription (string)

// Jaring pengaman: pastikan .env termuat walau akses langsung via file shell
require_once dirname(__DIR__, 4) . '/includes/env.php';
loadEnv(dirname(__DIR__, 4) . '/.env');

$pageTitle       = $pageTitle       ?? 'Pekon Air Naningan';
$currentPage     = $currentPage     ?? 'beranda';
$metaDescription = $metaDescription ?? 'Situs resmi Pekon Air Naningan — profil desa, produk UMKM warga, dan potensi wisata alam, dalam satu tempat.';
$metaKeywords    = $metaKeywords    ?? 'Pekon Air Naningan, profil desa, UMKM, wisata, galeri, berita desa, Tanggamus';
$metaImage       = $metaImage       ?? '';

// Deteksi base path
$base = defined('APP_BASE') ? APP_BASE : '';

// URL absolut untuk og:url & canonical
$scheme      = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host        = $_SERVER['HTTP_HOST'] ?? '';
$reqUri      = $_SERVER['REQUEST_URI'] ?? '/';
$canonicalUri = strtok($reqUri, '?'); // tanpa query string untuk canonical
$metaImageUrl = $metaImage !== '' ? $metaImage : $scheme . '://' . $host . $base . '/assets/images/logo.jpg';

if (!function_exists('mediaUrl')) {
    function mediaUrl(string $path, string $basePath): string {
        $val = trim($path);
        if ($val === '' || preg_match('/^(https?:)?\/\//i', $val) || str_starts_with($val, 'data:')) return $val;
        if ($basePath !== '' && ($val === $basePath || str_starts_with($val, $basePath . '/'))) return $val;
        return $basePath . '/' . ltrim($val, '/');
    }
}

$navLinks = [
    ['beranda',    'Beranda',    $base . '/'],
    ['profil-desa','Profil Desa',$base . '/profil'],
    ['umkm',       'UMKM',      $base . '/umkm'],
    ['wisata',     'Wisata',    $base . '/wisata'],
    ['berita',     'Berita',    $base . '/berita'],
    ['galeri',     'Galeri',    $base . '/galeri'],
    ['kontak',     'Kontak',    $base . '/kontak'],
];
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="icon" type="image/jpeg" href="<?= htmlspecialchars($base . '/assets/images/logo.jpg', ENT_QUOTES, 'UTF-8') ?>">
    <meta name="description" content="<?= htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8') ?>">
    <meta name="keywords" content="<?= htmlspecialchars($metaKeywords, ENT_QUOTES, 'UTF-8') ?>">
    <!-- Open Graph -->
    <meta property="og:title" content="<?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:description" content="<?= htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:image" content="<?= htmlspecialchars($metaImageUrl, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="<?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:url" content="<?= htmlspecialchars($scheme . '://' . $host . $reqUri, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="id_ID">
    <meta property="og:site_name" content="Pekon Air Naningan">
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8') ?>">
    <meta name="twitter:image" content="<?= htmlspecialchars($metaImageUrl, ENT_QUOTES, 'UTF-8') ?>">
    <!-- Canonical (tanpa query string) -->
    <link rel="canonical" href="<?= htmlspecialchars($scheme . '://' . $host . $canonicalUri, ENT_QUOTES, 'UTF-8') ?>">
    <!-- Inline critical base styles -->
    <style>
        @layer base {
            html, body { margin: 0; padding: 0; }
            body { overscroll-behavior: none; }
        }
        ::-webkit-scrollbar { display: none; }
    </style>
    <!-- Tailwind CSS Browser CDN (Play CDN untuk dev; sesuai keputusan proyek) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "line-strong":                 "rgba(243,236,218,0.22)",
                        "secondary-fixed":             "#ffdbcd",
                        "on-tertiary-fixed-variant":   "#174780",
                        "error-container":             "#93000a",
                        "on-secondary":                "#581e00",
                        "secondary-fixed-dim":         "#ffb596",
                        "surface-container-lowest":    "#110e08",
                        "bg":                          "#12201A",
                        "on-primary-fixed-variant":    "#5e4200",
                        "on-secondary-container":      "#ffa177",
                        "surface-container-low":       "#1f1b14",
                        "on-surface-variant":          "#d2c5b1",
                        "surface-variant":             "#39342c",
                        "inverse-primary":             "#7c5800",
                        "surface-container-highest":   "#39342c",
                        "on-secondary-fixed-variant":  "#7a3007",
                        "tertiary":                    "#a7c8ff",
                        "surface-container-high":      "#2e2922",
                        "surface":                     "#1B2B22",
                        "on-tertiary":                 "#003061",
                        "on-tertiary-container":       "#00386e",
                        "secondary-container":         "#7d3209",
                        "outline-variant":             "#4f4537",
                        "error":                       "#ffb4ab",
                        "on-primary":                  "#412d00",
                        "tertiary-container":          "#7ba3e2",
                        "inverse-surface":             "#ebe1d6",
                        "on-secondary-fixed":          "#360f00",
                        "primary":                     "#f2bf5d",
                        "on-primary-fixed":            "#271900",
                        "outline":                     "#9b8f7d",
                        "primary-fixed-dim":           "#f2bf5d",
                        "inverse-on-surface":          "#353028",
                        "surface-container":           "#231f18",
                        "on-surface":                  "#ebe1d6",
                        "tertiary-fixed":              "#d5e3ff",
                        "on-error-container":          "#ffdad6",
                        "surface-bright":              "#3e3831",
                        "surface-dim":                 "#17130c",
                        "danger":                      "#C1653A",
                        "surface-tint":                "#f2bf5d",
                        "on-primary-container":        "#4b3400",
                        "surface-2":                   "#24382C",
                        "line":                        "rgba(243,236,218,0.12)",
                        "on-background":               "#ebe1d6",
                        "gold-soft":                   "#E4C374",
                        "on-tertiary-fixed":           "#001b3c",
                        "tertiary-fixed-dim":          "#a7c8ff",
                        "primary-container":           "#c99a3c",
                        "ink-dim":                     "#B9C4B4",
                        "secondary":                   "#ffb596",
                        "ink":                         "#F3ECDA",
                        "background":                  "#17130c",
                        "primary-fixed":               "#ffdea7",
                        "on-error":                    "#690005"
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg":      "0.5rem",
                        "xl":      "0.75rem",
                        "full":    "9999px"
                    },
                    spacing: {
                        "container-pad-desktop": "32px",
                        "section-v-desktop":     "96px",
                        "gutter":                "24px",
                        "container-max":         "1180px",
                        "section-v-mobile":      "56px",
                        "container-pad-mobile":  "18px"
                    },
                    fontFamily: {
                        "h1":        ["Newsreader"],
                        "label-mono":["JetBrains Mono"],
                        "h1-mobile": ["Newsreader"],
                        "body-md":   ["Public Sans"],
                        "h2":        ["Newsreader"],
                        "h3":        ["Newsreader"],
                        "body-lg":   ["Public Sans"]
                    },
                    fontSize: {
                        "h1":        ["48px", {"lineHeight":"1.1","letterSpacing":"-0.01em","fontWeight":"600"}],
                        "label-mono":["11px", {"lineHeight":"1.0","letterSpacing":"0.14em","fontWeight":"500"}],
                        "h1-mobile": ["32px", {"lineHeight":"1.2","fontWeight":"600"}],
                        "body-md":   ["16px", {"lineHeight":"1.5","fontWeight":"400"}],
                        "h2":        ["36px", {"lineHeight":"1.2","letterSpacing":"-0.01em","fontWeight":"600"}],
                        "h3":        ["24px", {"lineHeight":"1.3","fontWeight":"600"}],
                        "body-lg":   ["18px", {"lineHeight":"1.6","fontWeight":"400"}]
                    }
                }
            }
        }
    </script>
    <!-- Fonts: preconnect untuk performa, lalu load stylesheet -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:ital,opsz,wght@0,6..72,400..800;1,6..72,400..800&family=Public+Sans:ital,wght@0,100..900;1,100..900&family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <!-- JSON-LD: Organization / GovernmentOrganization -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "GovernmentOrganization",
      "name": "Pekon Air Naningan",
      "alternateName": "Desa Air Naningan",
      "url": "<?= htmlspecialchars($scheme . '://' . $host . $base . '/', ENT_QUOTES, 'UTF-8') ?>",
      "logo": "<?= htmlspecialchars($scheme . '://' . $host . $base . '/assets/images/logo.jpg', ENT_QUOTES, 'UTF-8') ?>",
      "description": "Situs resmi Pekon Air Naningan, Kecamatan Air Naningan, Kabupaten Tanggamus, Lampung.",
      "address": {
        "@type": "PostalAddress",
        "addressLocality": "Air Naningan",
        "addressRegion": "Tanggamus, Lampung",
        "addressCountry": "ID"
      },
      "areaServed": "Air Naningan, Kecamatan Air Naningan, Tanggamus"
    }
    </script>
</head>
<body class="bg-bg font-body-md text-on-surface">

<!-- Mobile Menu Overlay -->
<div class="fixed inset-0 z-40 bg-bg/97 backdrop-blur-2xl hidden flex-col gap-3 px-container-pad-mobile pt-24 pb-8 overflow-y-auto" id="mobile-menu">
    <?php foreach ($navLinks as [$path, $label, $href]): ?>
        <?php if ($currentPage === $path): ?>
            <a href="<?= htmlspecialchars($href, ENT_QUOTES) ?>"
               class="font-body-md bg-primary text-on-primary rounded-full px-6 py-3.5 text-center font-medium">
                <?= htmlspecialchars($label) ?>
            </a>
        <?php else: ?>
            <a href="<?= htmlspecialchars($href, ENT_QUOTES) ?>"
               class="font-body-md text-ink-dim hover:text-ink transition-colors px-6 py-3.5 border border-line rounded-full text-center">
                <?= htmlspecialchars($label) ?>
            </a>
        <?php endif; ?>
    <?php endforeach; ?>
</div>

<header class="fixed top-0 w-full z-50 bg-bg/80 backdrop-blur-xl border-b border-line">
    <div class="h-20 max-w-container-max mx-auto px-container-pad-mobile lg:px-container-pad-desktop flex items-center justify-between">
        <a href="<?= htmlspecialchars($base ?: '/', ENT_QUOTES) ?>" class="flex items-center gap-4">
            <img alt="Logo Pekon Air Naningan"
                 class="h-10 w-auto object-contain"
                 src="<?= htmlspecialchars($base . '/assets/images/logo.jpg', ENT_QUOTES) ?>">
            <div class="flex flex-col">
                <span class="font-h3 text-h3 text-ink leading-none">Air Naningan</span>
                <span class="font-label-mono text-label-mono text-gold-soft tracking-widest uppercase">Pekon Mandiri</span>
            </div>
        </a>
        <nav class="hidden lg:flex items-center gap-6" aria-label="Navigasi utama">
            <?php foreach ($navLinks as [$path, $label, $href]): ?>
                <?php if ($currentPage === $path): ?>
                    <a aria-current="page"
                       class="font-body-md transition-colors bg-primary text-on-primary rounded-full px-4 py-1.5"
                       href="<?= htmlspecialchars($href, ENT_QUOTES) ?>">
                        <?= htmlspecialchars($label) ?>
                    </a>
                <?php else: ?>
                    <a class="text-body-md font-body-md text-ink-dim hover:text-ink transition-colors px-2"
                       href="<?= htmlspecialchars($href, ENT_QUOTES) ?>">
                        <?= htmlspecialchars($label) ?>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </nav>
        <div class="flex items-center">
            <button class="lg:hidden text-ink" id="mobile-menu-btn" aria-label="Buka menu navigasi" aria-expanded="false" aria-controls="mobile-menu">
                <span class="material-symbols-outlined" id="menu-icon">menu</span>
            </button>
        </div>
    </div>
</header>

<main class="pt-20">
