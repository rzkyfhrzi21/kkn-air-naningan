<?php

declare(strict_types=1);

require_once __DIR__ . '/../../Models/Umkm.php';

final class KelolaUmkmController
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

        $items    = Umkm::all();
        $kategori = Umkm::KATEGORI;
        require __DIR__ . '/../../Views/admin/kelola-umkm/index.php';
    }
}
