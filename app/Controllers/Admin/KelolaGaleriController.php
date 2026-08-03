<?php

declare(strict_types=1);

require_once __DIR__ . '/../../Models/Galeri.php';

final class KelolaGaleriController
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

        require __DIR__ . '/../../Views/admin/kelola-galeri/index.php';
    }
}