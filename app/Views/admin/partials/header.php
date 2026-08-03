<?php

/**
 * Admin layout header — sidebar kiri + topbar atas.
 * Variabel yang harus diset oleh view:
 *   $pageTitle      string  — judul tab browser
 *   $activeNav      string  — slug item nav aktif (e.g. 'overview', 'kelola-umkm')
 */
$pageTitle = $pageTitle ?? 'Admin | Pekon Air Naningan';
$activeNav = $activeNav ?? 'overview';
$base      = defined('APP_BASE') ? APP_BASE : '';

$navItems = [
    ['overview',      'dashboard',          'Overview'],
    ['kelola-profil', 'info',               'Kelola Profil'],
    ['kelola-umkm',   'storefront',         'Kelola UMKM'],
    ['kelola-wisata', 'landscape',          'Kelola Wisata'],
    ['kelola-berita', 'newspaper',          'Kelola Berita'],
    ['kelola-galeri', 'photo_library',      'Kelola Galeri'],
    ['pesan-masuk',   'mail',               'Pesan Masuk'],
    ['pengaturan',    'settings',           'Pengaturan'],
];
?>
<!doctype html>
<html class="dark" lang="id">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:ital,opsz,wght@0,6..72,400..800;1,6..72,400..800&family=Public+Sans:ital,wght@0,100..900;1,100..900&family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style type="text/tailwindcss">
        @theme {
            --color-bg: #12201A;
            --color-surface: #1B2B22;
            --color-surface-2: #24382C;
            --color-surface-container: #231f18;
            --color-surface-container-high: #2e2922;
            --color-surface-container-highest: #39342c;
            --color-surface-container-lowest: #110e08;
            --color-surface-container-low: #1f1b14;
            --color-ink: #F3ECDA;
            --color-ink-dim: #B9C4B4;
            --color-primary: #f2bf5d;
            --color-primary-fixed: #ffdea7;
            --color-primary-fixed-dim: #f2bf5d;
            --color-on-primary: #412d00;
            --color-gold-soft: #E4C374;
            --color-line: rgba(243,236,218,0.12);
            --color-line-strong: rgba(243,236,218,0.22);
            --color-danger: #C1653A;
            --color-error: #ffb4ab;
            --color-secondary: #ffb596;
            --color-secondary-fixed-dim: #ffb596;
            --color-tertiary-fixed-dim: #a7c8ff;
            --color-on-surface: #ebe1d6;
            --color-on-surface-variant: #d2c5b1;
            --font-family-h1: Newsreader, serif;
            --font-family-h2: Newsreader, serif;
            --font-family-h3: Newsreader, serif;
            --font-family-body-md: "Public Sans", sans-serif;
            --font-family-body-lg: "Public Sans", sans-serif;
            --font-family-label-mono: "JetBrains Mono", monospace;

            /* ── Spacing tokens (dari stitch tailwind.config) ── */
            --spacing-container-pad-desktop: 32px;
            --spacing-container-pad-mobile: 18px;
            --spacing-section-v-desktop: 96px;
            --spacing-section-v-mobile: 56px;
            --spacing-gutter: 24px;
            --spacing-container-max: 1180px;

            /* ── Font-size tokens ── */
            --font-size-h1: 48px;
            --font-size-h2: 36px;
            --font-size-h3: 24px;
            --font-size-body-md: 16px;
            --font-size-body-lg: 18px;
            --font-size-label-mono: 11px;
            --font-size-h1-mobile: 32px;

            /* ── Extra color tokens ── */
            --color-background: #17130c;
            --color-surface-bright: #3e3831;
            --color-surface-dim: #17130c;
            --color-surface-tint: #f2bf5d;
            --color-on-primary-container: #4b3400;
            --color-primary-container: #c99a3c;
            --color-secondary-container: #7d3209;
            --color-on-secondary-container: #ffa177;
            --color-tertiary: #a7c8ff;
            --color-tertiary-container: #7ba3e2;
            --color-tertiary-fixed: #d5e3ff;
            --color-on-tertiary: #003061;
            --color-on-tertiary-container: #00386e;
            --color-on-tertiary-fixed: #001b3c;
            --color-tertiary-fixed-dim: #a7c8ff;
            --color-error-container: #93000a;
            --color-on-error-container: #ffdad6;
            --color-on-error: #690005;
            --color-outline: #9b8f7d;
            --color-outline-variant: #4f4537;
            --color-inverse-surface: #ebe1d6;
            --color-inverse-on-surface: #353028;
            --color-inverse-primary: #7c5800;
            --color-surface-variant: #39342c;
            --color-on-primary-fixed: #271900;
            --color-on-primary-fixed-variant: #5e4200;
            --color-on-secondary: #581e00;
            --color-on-secondary-fixed: #360f00;
            --color-on-secondary-fixed-variant: #7a3007;
            --color-secondary-fixed: #ffdbcd;
            --color-on-tertiary-fixed-variant: #174780;
        }
        body { background-color: var(--color-bg); color: var(--color-ink); font-family: var(--font-family-body-md); }
    </style>
</head>

<body class="bg-bg text-ink font-body-md">

    <!-- ── Sidebar ──────────────────────────────────────────────────────── -->
    <aside class="fixed left-0 top-0 h-full w-[280px] bg-surface-container border-r border-line z-50 flex flex-col">
        <!-- Logo -->
        <div class="p-8 flex items-center gap-3 border-b border-line">
            <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-on-primary text-[18px]">park</span>
            </div>
            <div class="flex flex-col">
                <span class="font-h3 text-lg text-ink">Air Naningan</span>
                <span class="font-label-mono text-[9px] text-gold-soft tracking-widest uppercase">Admin Panel</span>
            </div>
        </div>

        <!-- Nav -->
        <nav class="flex-1 py-6 px-4 space-y-1 overflow-y-auto">
            <?php foreach ($navItems as [$slug, $icon, $label]): ?>
                <?php $isActive = ($activeNav === $slug); ?>
                <a class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?= $isActive
                                                                                            ? 'bg-primary text-on-primary shadow-lg shadow-primary/20'
                                                                                            : 'text-ink-dim hover:bg-surface-container-high hover:text-ink' ?>"
                    <?= $isActive ? 'aria-current="page"' : '' ?>
                    data-path="<?= $slug ?>"
                    href="<?= $base ?>/admin/<?= $slug === 'overview' ? '' : $slug ?>">
                    <span class="material-symbols-outlined text-[20px]"><?= $icon ?></span>
                    <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <!-- Logout -->
        <div class="p-6 border-t border-line">
            <a class="flex items-center gap-3 w-full px-4 py-3 text-ink-dim hover:text-danger transition-colors rounded-xl hover:bg-surface-container-high"
                href="<?= $base ?>/admin/logout">
                <span class="material-symbols-outlined">logout</span>
                Keluar
            </a>
        </div>
    </aside>

    <!-- ── Content Wrapper ────────────────────────────────────────────── -->
    <div class="pl-[280px]">

        <!-- Topbar -->
        <header class="fixed top-0 left-[280px] right-0 h-16 bg-bg/80 backdrop-blur-xl border-b border-line z-40 flex items-center justify-between px-8">
            <div class="flex items-center gap-2 text-ink-dim font-label-mono text-label-mono">
                <a class="hover:text-primary transition-colors" href="<?= $base ?>/admin">Admin</a>
                <span class="material-symbols-outlined text-sm">chevron_right</span>
                <span class="text-ink"><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></span>
            </div>
            <div class="flex items-center gap-4">
                <a class="p-2 text-ink-dim hover:text-ink transition-colors" href="<?= $base ?>/" title="Lihat situs publik" target="_blank">
                    <span class="material-symbols-outlined">open_in_new</span>
                </a>
                <div class="h-8 w-px bg-line"></div>
                <div class="flex items-center gap-3">
                    <div class="flex flex-col items-end">
                        <span class="text-[13px] font-bold text-ink">
                            <?= htmlspecialchars($_SESSION['admin_username'] ?? 'Administrator', ENT_QUOTES, 'UTF-8') ?>
                        </span>
                        <span class="text-[11px] text-ink-dim">Super Admin</span>
                    </div>
                    <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center">
                        <span class="material-symbols-outlined text-on-primary text-[18px]">person</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main content -->
        <main class="pt-16 min-h-screen">