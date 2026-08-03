<?php

declare(strict_types=1);

require_once __DIR__ . '/../../Models/Pesan.php';

final class PesanMasukController
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

        require __DIR__ . '/../../Views/admin/pesan-masuk/index.php';
    }
}