<?php

declare(strict_types=1);

final class KelolaProfilController
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

        require __DIR__ . '/../../Views/admin/kelola-profil/index.php';
    }
}