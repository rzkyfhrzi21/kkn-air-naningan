<?php

declare(strict_types=1);

final class ProfilController
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

        require_once __DIR__ . '/../../Models/Akun.php';

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $creds = Akun::get();
        $akun  = [
            'nama_lengkap' => (string) ($creds['nama_lengkap'] ?? ''),
            'username'     => (string) ($creds['username'] ?? ''),
            'whatsapp'     => (string) ($creds['whatsapp'] ?? ''),
            'email'        => (string) ($creds['email'] ?? ''),
            'foto'         => (string) ($creds['foto'] ?? ''),
        ];

        require __DIR__ . '/../../Views/admin/profil/index.php';
    }
}
