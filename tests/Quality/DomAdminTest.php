<?php

declare(strict_types=1);

/* ======================================================
   QUALITY — Audit DOM & Kerangka Halaman Admin

   Memeriksa kesepakatan kerangka (framework) yang sudah
   menjadi standar proyek, semuanya berbasis pembacaan file:
   1. Sidebar admin: key localStorage 'admin-sidebar-collapsed'
      + fallback non-JS (skeleton #app-boot menghilang).
   2. Toast: tombol tutup × wajib ada; toast sukses
      auto-dismiss (2 detik); tak ada toast tersangkut.
   3. Tidak ada CSS Bootstrap (bootstrap.min.css) di admin.
   4. Tailwind Browser CDN v4 di seluruh head publik & admin.
   5. Skeleton #app-boot di admin punya fallback timeout.
====================================================== */

require_once __DIR__ . '/../bootstrap.php';

$adminHeader = (string) readFileSafe(PROJECT_ROOT . '/app/Views/admin/partials/header.php');
$adminFooter = (string) readFileSafe(PROJECT_ROOT . '/app/Views/admin/partials/footer.php');
$pubHeader   = (string) readFileSafe(PROJECT_ROOT . '/app/Views/public/partials/header.php');
$pubFooter   = (string) readFileSafe(PROJECT_ROOT . '/app/Views/public/partials/footer.php');

runTest('Sidebar admin: key localStorage admin-sidebar-collapsed dipakai', static function () use ($adminHeader, $adminFooter): void {
    $all = $adminHeader . $adminFooter;
    assertContains('admin-sidebar-collapsed', $all, 'Key localStorage sidebar harus dipakai.');
    assertContains("localStorage.getItem", $all, 'Sidebar harus membaca state dari localStorage.');
});

runTest('Skeleton #app-boot di admin punya fallback timeout (anti halaman kosong)', static function () use ($adminHeader, $adminFooter): void {
    $all = $adminHeader . $adminFooter;
    assertContains('app-boot', $all, 'Skeleton boot harus ada.');
    assertContains('setTimeout', $all, 'Harus ada fallback setTimeout untuk menghilangkan skeleton.');
});

runTest('Toast: tombol tutup × dan auto-dismiss sukses 2 detik', static function () use ($adminFooter): void {
    assertContains('at-close', $adminFooter, 'Toast harus punya tombol tutup (at-close).');
    assertContains('addEventListener', $adminFooter, 'Tombol tutup harus punya event listener.');
    assertMatches('/setTimeout\s*\([^)]*2000/', $adminFooter, 'Toast sukses harus auto-dismiss 2000ms.');
});

runTest('Tidak ada CSS Bootstrap di halaman admin', static function (): void {
    $files = glob(PROJECT_ROOT . '/app/Views/admin/**/*.php') ?: [];
    foreach ($files as $file) {
        $content = (string) file_get_contents($file);
        assertNotContains('bootstrap.min.css', $content, 'Ditemukan bootstrap.min.css di ' . basename($file));
    }
});

runTest('Tailwind Browser CDN terpasang di header publik & admin', static function () use ($pubHeader, $adminHeader): void {
    // Admin: wajib v4 (keputusan proyek, lihat AGENTS.md P.0/P.1).
    assertContains('@tailwindcss/browser@4', $adminHeader, 'Header admin wajib memuat Tailwind Browser CDN v4.');
    // Publik: CDN Tailwind boleh v3 (cdn.tailwindcss.com) atau v4.
    assertTrue(
        str_contains($pubHeader, '@tailwindcss/browser@4') || str_contains($pubHeader, 'cdn.tailwindcss.com'),
        'Header publik wajib memuat CDN Tailwind (v3 atau v4).'
    );
});

runTest('Footer admin memuat bootstrap.bundle.min.js (JS interaktif)', static function () use ($adminFooter): void {
    assertContains('bootstrap.bundle.min.js', $adminFooter, 'bootstrap.bundle.min.js harus dimuat.');
});

runTest('Modal admin pakai mekanisme classList (bukan data-bs) + tombol tutup', static function (): void {
    $files = glob(PROJECT_ROOT . '/app/Views/admin/**/*.php') ?: [];
    $found = 0;
    foreach ($files as $file) {
        $content = (string) file_get_contents($file);
        if (str_contains($content, 'classList.add(\'flex\')') || str_contains($content, 'classList.remove(\'hidden\')')) {
            $found++;
        }
    }
    assertTrue($found >= 3, 'Modal admin harus di-toggle via classList (ditemukan di ' . $found . ' file).');
    // Modal preview media wajib punya tombol tutup (aria-label Tutup + handler)
    $galeri = (string) readFileSafe(PROJECT_ROOT . '/app/Views/admin/kelola-galeri/index.php');
    assertContains('modal-preview-galeri', $galeri, 'Modal preview galeri harus ada.');
    assertContains('aria-label="Tutup"', $galeri, 'Modal preview wajib punya tombol tutup (aria-label Tutup).');
});

finishTests();