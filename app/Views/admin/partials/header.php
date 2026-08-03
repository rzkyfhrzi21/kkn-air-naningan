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

// Jaring pengaman: pastikan .env termuat walau akses langsung via file shell
require_once dirname(__DIR__, 4) . '/includes/env.php';
loadEnv(dirname(__DIR__, 4) . '/.env');

$navItems = [
    ['overview',      'dashboard',          'Overview'],
    ['kelola-profil', 'info',               'Kelola Profil'],
    ['kelola-umkm',   'storefront',         'Kelola UMKM'],
    ['kelola-wisata', 'landscape',          'Kelola Wisata'],
    ['kelola-berita', 'newspaper',          'Kelola Berita'],
    ['kelola-galeri', 'photo_library',      'Kelola Galeri'],
    ['pesan-masuk',   'mail',               'Pesan Masuk'],
    ['profil',        'settings',           'Profil'],
];
?>
<!doctype html>
<html class="dark" lang="id">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="icon" type="image/jpeg" href="<?= htmlspecialchars($base . '/assets/images/logo.jpg', ENT_QUOTES, 'UTF-8') ?>">
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

        #sidebar, #admin-wrapper, #topbar { transition: transform .25s ease, padding-left .25s ease, left .25s ease, width .25s ease; }

        @media (max-width: 1023.98px) {
            #sidebar { transform: translateX(-100%); }
            body.sidebar-open #sidebar { transform: translateX(0); box-shadow: 0 0 60px rgba(0, 0, 0, .55); }
            body.sidebar-open #sidebar-overlay { display: block; }
            #admin-wrapper { padding-left: 0; }
            #topbar { left: 0; }
        }

        /* Mode collapsed (desktop): sidebar jadi rail ikon, teks disembunyikan */
        body.sidebar-collapsed #sidebar { width: 80px; }
        body.sidebar-collapsed #sidebar .sb-label,
        body.sidebar-collapsed #sidebar .sb-logo-text { display: none; }
        body.sidebar-collapsed #sidebar .sb-logo { justify-content: center; padding: 24px 0; }
        body.sidebar-collapsed #sidebar .sb-logout { padding: 20px 10px; }
        body.sidebar-collapsed #sidebar nav { padding-left: 10px; padding-right: 10px; }
        body.sidebar-collapsed #sidebar nav a { justify-content: center; padding-left: 0; padding-right: 0; }
        body.sidebar-collapsed #admin-wrapper { padding-left: 80px; }
        body.sidebar-collapsed #topbar { left: 80px; }
    </style>
</head>

<body class="bg-bg text-ink font-body-md">

    <!-- ── Sidebar ──────────────────────────────────────────────────────── -->
    <aside id="sidebar" class="fixed left-0 top-0 h-full w-[280px] bg-surface-container border-r border-line z-50 flex flex-col">
        <!-- Logo -->
        <div class="sb-logo p-8 flex items-center gap-3 border-b border-line">
            <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center shrink-0 overflow-hidden">
                <img src="<?= htmlspecialchars($base . '/assets/images/logo.jpg', ENT_QUOTES, 'UTF-8') ?>" alt="Logo Pekon Air Naningan" class="w-full h-full object-cover">
            </div>
            <div class="sb-logo-text flex flex-col">
                <span class="font-h3 text-lg text-ink">Pekon Air Naningan</span>
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
                    title="<?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>"
                    href="<?= $base ?>/admin/<?= $slug === 'overview' ? '' : $slug ?>">
                    <span class="material-symbols-outlined text-[20px] shrink-0"><?= $icon ?></span>
                    <span class="sb-label"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></span>
                </a>
            <?php endforeach; ?>
        </nav>

        <!-- Logout -->
        <div class="sb-logout p-6 border-t border-line">
            <a class="flex items-center gap-3 w-full px-4 py-3 text-ink-dim hover:text-danger transition-colors rounded-xl hover:bg-surface-container-high"
                title="Keluar"
                href="<?= $base ?>/admin/logout">
                <span class="material-symbols-outlined shrink-0">logout</span>
                <span class="sb-label">Keluar</span>
            </a>
        </div>
    </aside>

    <!-- Sidebar overlay (mobile) -->
    <div id="sidebar-overlay" class="hidden fixed inset-0 z-[45] bg-black/60"></div>

    <!-- ── Content Wrapper ────────────────────────────────────────────── -->
    <div id="admin-wrapper" class="pl-[280px]">

        <!-- Topbar -->
        <header id="topbar" class="fixed top-0 left-[280px] right-0 h-16 bg-bg/80 backdrop-blur-xl border-b border-line z-40 flex items-center justify-between px-4 md:px-8">
            <div class="flex items-center gap-3 min-w-0">
                <button type="button" id="sidebar-toggle" class="flex items-center justify-center w-10 h-10 -ml-2 md:-ml-3 shrink-0 rounded-lg text-ink-dim hover:text-ink hover:bg-surface-container-high transition-colors" aria-label="Buka atau tutup menu sidebar">
                    <span class="material-symbols-outlined">menu</span>
                </button>
            <div class="flex items-center gap-2 text-ink-dim font-label-mono text-label-mono truncate">
                <a class="hover:text-primary transition-colors shrink-0" href="<?= $base ?>/admin">Admin</a>
                <span class="material-symbols-outlined text-sm shrink-0">chevron_right</span>
                <span class="text-ink truncate"><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></span>
            </div>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <a class="flex items-center gap-1.5 text-ink-dim hover:text-primary transition-colors font-label-mono text-[11px] uppercase tracking-wider" href="<?= $base ?>/" target="_blank" rel="noopener" title="Lihat situs publik">
                <span class="material-symbols-outlined text-[17px]">open_in_new</span>
                Beranda
            </a>
            <span class="h-8 w-px bg-line shrink-0"></span>
            <span class="flex flex-col items-end font-label-mono whitespace-nowrap leading-tight" id="topbar-clock">
                <span id="topbar-clock-date" class="text-[12px] text-ink-dim">—</span>
                <span id="topbar-clock-time" class="text-[16px] text-ink font-bold">—</span>
            </span>
            <span class="h-8 w-px bg-line shrink-0"></span>
            <div class="relative" id="profile-menu">
                <button type="button" id="profile-menu-btn" class="flex items-center gap-3 rounded-xl p-1.5 pr-2 hover:bg-surface-container-high transition-colors" aria-haspopup="true" aria-expanded="false" aria-label="Menu akun admin">
                    <div class="flex flex-col items-end hidden sm:flex">
                        <span class="text-[14px] font-bold text-ink leading-tight">
                            <?= htmlspecialchars(
                                $_SESSION['admin_nama_lengkap'] ?? $_SESSION['admin_username'] ?? 'Administrator',
                                ENT_QUOTES, 'UTF-8'
                            ) ?>
                        </span>
                        <span class="text-[11px] text-ink-dim"><?= htmlspecialchars($_SESSION['admin_username'] ?? 'Super Admin', ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                    <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center overflow-hidden shrink-0">
                        <?php
                        require_once dirname(__DIR__, 4) . '/app/Models/Akun.php';
                        $akunFoto = (string) (Akun::get()['foto'] ?? '');
                        ?>
                        <?php if ($akunFoto !== ''): ?>
                            <img src="<?= htmlspecialchars($base . '/' . $akunFoto, ENT_QUOTES, 'UTF-8') ?>" alt="Foto admin" class="w-full h-full object-cover">
                        <?php else: ?>
                            <span class="material-symbols-outlined text-on-primary text-[18px]">person</span>
                        <?php endif; ?>
                    </div>
                    <span class="material-symbols-outlined text-[16px] text-ink-dim">arrow_drop_down</span>
                </button>
                <div id="profile-dropdown" class="hidden absolute right-0 top-full mt-2 w-52 bg-surface rounded-xl border border-line shadow-2xl shadow-black/40 py-2 z-50">
                    <a class="flex items-center gap-2.5 px-4 py-2.5 text-ink-dim hover:text-ink hover:bg-surface-2 transition-colors"
                        href="<?= $base ?>/admin/profil">
                        <span class="material-symbols-outlined text-[18px]">settings</span>
                        Profil
                    </a>
                    <a class="flex items-center gap-2.5 px-4 py-2.5 text-ink-dim hover:text-danger hover:bg-surface-2 transition-colors"
                        href="<?= $base ?>/admin/logout">
                        <span class="material-symbols-outlined text-[18px]">logout</span>
                        Keluar
                    </a>
                </div>
            </div>
        </div>
    </header>

        <!-- Main content -->
        <main class="pt-16 min-h-screen">