<?php

declare(strict_types=1);

/* ======================================================
   QUALITY — Audit Kebocoran Debug & Marker Pengembangan

   Kode production TIDAK boleh meninggalkan:
   - var_dump() / print_r() / die() debugging
   - console.log() di JavaScript aplikasi (di luar vendor)
   - tag HTML kotor (<div>, </div> kosong berlebih / typo)
   - file sementara .tmp / .bak di area production
====================================================== */

require_once __DIR__ . '/../bootstrap.php';

$scanDirs = [
    'app',
    'public',
    'config',
    'includes',
];

runTest('Tidak ada var_dump / print_r / die di kode production', static function () use ($scanDirs): void {
    $patterns = ['var_dump', 'print_r(', 'die('];
    foreach ($scanDirs as $dir) {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(PROJECT_ROOT . '/' . $dir, FilesystemIterator::SKIP_DOTS)
        );
        foreach ($iterator as $file) {
            if (!($file instanceof SplFileInfo) || !$file->isFile()) {
                continue;
            }
            $ext = strtolower($file->getExtension());
            if ($ext !== 'php') {
                continue;
            }
            $content = (string) file_get_contents($file->getPathname());
            foreach ($patterns as $p) {
                if (str_contains($content, $p)) {
                    throw new TestFailureException(
                        "Ditemukan '{$p}' di " . str_replace(PROJECT_ROOT . '\\', '', $file->getPathname())
                    );
                }
            }
        }
    }
});

runTest('Tidak ada console.log di JavaScript aplikasi (di luar vendor)', static function (): void {
    $jsDir = PROJECT_ROOT . '/public/assets/js';
    if (!is_dir($jsDir)) {
        markSkipped('Folder public/assets/js tidak ada.');
        return;
    }
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($jsDir, FilesystemIterator::SKIP_DOTS)
    );
    foreach ($iterator as $file) {
        if (!($file instanceof SplFileInfo) || !$file->isFile()) {
            continue;
        }
        $rel = str_replace('\\', '/', $file->getPathname());
        if (str_contains($rel, '/vendor/')) {
            continue; // library pihak ketiga dikecualikan
        }
        $content = (string) file_get_contents($file->getPathname());
        if (str_contains($content, 'console.log')) {
            throw new TestFailureException('console.log di ' . str_replace(PROJECT_ROOT . '\\', '', $file->getPathname()));
        }
    }
});

runTest('Tidak ada file artefak (.tmp/.bak/.orig) di area production', static function () use ($scanDirs): void {
    $badExt = ['tmp', 'bak', 'orig', 'swp'];
    foreach ($scanDirs as $dir) {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(PROJECT_ROOT . '/' . $dir, FilesystemIterator::SKIP_DOTS)
        );
        foreach ($iterator as $file) {
            if (!($file instanceof SplFileInfo) || !$file->isFile()) {
                continue;
            }
            $ext = strtolower($file->getExtension());
            if (in_array($ext, $badExt, true)) {
                throw new TestFailureException(
                    'File artefak ditemukan: ' . str_replace(PROJECT_ROOT . '\\', '', $file->getPathname())
                );
            }
        }
    }
});

runTest('Endpoint publik tidak mengembalikan output debug (render bersih)', static function (): void {
    ensureServer();
    foreach (['/', '/galeri', '/umkm', '/berita', '/kontak', '/profil'] as $path) {
        $resp = httpRequest('GET', $path);
        assertStatus(200, $resp, 'Halaman ' . $path . ' gagal.');
        assertNotContains('Warning:', $resp['body'], 'Ada PHP Warning di ' . $path);
        assertNotContains('Notice:', $resp['body'], 'Ada PHP Notice di ' . $path);
        assertNotContains('Fatal error', $resp['body'], 'Ada Fatal error di ' . $path);
    }
});

finishTests();