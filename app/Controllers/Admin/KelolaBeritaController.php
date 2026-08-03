<?php

declare(strict_types=1);

require_once __DIR__ . '/../../Models/Berita.php';

final class KelolaBeritaController
{
    public function index(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['admin'])) {
            $base = defined('APP_BASE') ? APP_BASE : '';
            header('Location: ' . $base . '/admin/login');
            exit;
        }

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $kategoriList = array_values(array_unique(array_filter(array_map(
            static fn(array $item): string => trim((string) ($item['kategori'] ?? '')),
            Berita::all()
        ))));
        sort($kategoriList, SORT_NATURAL | SORT_FLAG_CASE);

        require __DIR__ . '/../../Views/admin/kelola-berita/index.php';
    }
}
