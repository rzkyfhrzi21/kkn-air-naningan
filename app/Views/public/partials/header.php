<?php
// header.php — Public site header partial
// Variables expected: $pageTitle (string), $currentPage (string), $metaDescription (string)
$pageTitle       = $pageTitle       ?? 'Pekon Air Naningan';
$currentPage     = $currentPage     ?? 'beranda';
$metaDescription = $metaDescription ?? 'Situs resmi Pekon Air Naningan — profil desa, produk UMKM warga, dan potensi wisata alam, dalam satu tempat.';

// Deteksi base path (kosong jika di root, '/kkn-air-naningan2' jika subdirektori)
$base = defined('APP_BASE') ? APP_BASE : '';

$navLinks = [
    ['beranda',    'Beranda',    $base . '/'],
    ['profil-desa','Profil Desa',$base . '/profil'],
    ['umkm',       'UMKM',      $base . '/umkm'],
    ['wisata',     'Wisata',    $base . '/wisata'],
    ['potensi',    'Potensi',   $base . '/potensi'],
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
    <meta name="description" content="<?= htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:title" content="<?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:description" content="<?= htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:type" content="website">
    <style>
        @layer base {
            html, body { margin: 0; padding: 0; }
            body { overscroll-behavior: none; }
        }
        ::-webkit-scrollbar { display: none; }
    </style>
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
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:ital,opsz,wght@0,6..72,400..800;1,6..72,400..800&family=Public+Sans:ital,wght@0,100..900;1,100..900&family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
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
    <div class="border-t border-line my-4"></div>
    <a href="<?= htmlspecialchars($base . '/admin', ENT_QUOTES) ?>" class="font-label-mono text-label-mono text-ink-dim hover:text-gold-soft transition-colors px-6 py-3 border border-gold-soft/30 rounded-full text-center uppercase tracking-widest flex items-center justify-center gap-2">
        <span class="material-symbols-outlined text-[16px]">person</span> Admin
    </a>
</div>

<header class="fixed top-0 w-full z-50 bg-bg/80 backdrop-blur-xl border-b border-line">
    <div class="h-20 max-w-container-max mx-auto px-container-pad-mobile lg:px-container-pad-desktop flex items-center justify-between">
        <a href="<?= htmlspecialchars($base ?: '/', ENT_QUOTES) ?>" class="flex items-center gap-4">
            <img alt="Logo Pekon Air Naningan"
                 class="h-10 w-auto object-contain"
                 src="https://lh3.googleusercontent.com/aida-public/AB6AXuDK8H8Se06RoiwH3wJUlg_WHE-OgxxczJXVk5QpS3z5Nrh9E-j4DWfOQGQdYjrZ_4qD0b0knh3Lh9z4-Br0W2p7XHqFOeTE-coRDtrzlAeBSeX1RxZH9ViZAjV2cFI4G7ELLcfStWHI4FnE7oINSXOgQPfezfdHZoTBjjUgsqfBaXdugJDSTO1KXsNlpryA9s7n8dKAynQE5letH5Wym17CkRm5ou_ywSF0k0_ETyMzzyqNSuTW_EDbWw">
            <div class="flex flex-col">
                <span class="font-h3 text-h3 text-ink leading-none">Air Naningan</span>
                <span class="font-label-mono text-label-mono text-gold-soft tracking-widest uppercase">Pekon Mandiri</span>
            </div>
        </a>
        <nav class="hidden lg:flex items-center gap-6">
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
        <div class="flex items-center gap-4">
            <a href="<?= htmlspecialchars($base . '/admin', ENT_QUOTES) ?>" class="w-8 h-8 rounded-full bg-primary flex items-center justify-center" title="Masuk Admin">
                <span class="material-symbols-outlined text-on-primary text-[18px]">person</span>
            </a>
            <button class="lg:hidden text-ink" id="mobile-menu-btn" aria-label="Buka menu navigasi">
                <span class="material-symbols-outlined" id="menu-icon">menu</span>
            </button>
        </div>
    </div>
</header>

<main class="pt-20">
