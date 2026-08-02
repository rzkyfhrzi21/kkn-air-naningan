<?php

declare(strict_types=1);

/**
 * AuthController — Menangani login dan logout admin.
 *
 * GET  /admin/login  → tampilkan form login
 * POST /admin/login  → proses kredensial
 * GET  /admin/logout → hapus sesi, redirect ke login
 */
final class AuthController
{
    private static string $credentialsFile = __DIR__ . '/../../../secure/admin_credentials.json';

    /** Tampilkan halaman login (GET) atau proses login (POST). */
    public static function login(): void
    {
        // Sudah login → langsung ke dashboard
        if (!empty($_SESSION['admin'])) {
            self::redirectDashboard();
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // ── Rate limit: max 5 percobaan per 15 menit ──────────────────
            $key     = 'login_attempts';
            $window  = 900; // 15 menit
            $maxTry  = 5;
            $now     = time();

            $_SESSION[$key]        = $_SESSION[$key]        ?? [];
            $_SESSION[$key . '_ts'] = $_SESSION[$key . '_ts'] ?? $now;

            // Reset window jika sudah lewat
            if ($now - ($_SESSION[$key . '_ts']) > $window) {
                $_SESSION[$key]        = [];
                $_SESSION[$key . '_ts'] = $now;
            }

            $_SESSION[$key][] = $now;

            if (count($_SESSION[$key]) > $maxTry) {
                $error = 'Terlalu banyak percobaan. Coba lagi dalam beberapa menit.';
            } else {
                $username = trim($_POST['username'] ?? '');
                $password = $_POST['password'] ?? '';

                $creds = self::loadCredentials();

                if (
                    $creds !== null
                    && hash_equals($creds['username'], $username)
                    && password_verify($password, $creds['password_hash'])
                ) {
                    // ── Login sukses ──
                    session_regenerate_id(true);
                    $_SESSION['admin']         = true;
                    $_SESSION['admin_username'] = $username;
                    $_SESSION['login_at']       = $now;
                    unset($_SESSION[$key], $_SESSION[$key . '_ts']);

                    self::redirectDashboard();
                } else {
                    $error = 'Username atau kata sandi salah.';
                }
            }
        }

        // Render view login
        require __DIR__ . '/../../Views/admin/login/index.php';
    }

    /** Hapus sesi dan redirect ke halaman login. */
    public static function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $p['path'], $p['domain'], $p['secure'], $p['httponly']
            );
        }
        session_destroy();

        $base = defined('APP_BASE') ? APP_BASE : '';
        header('Location: ' . $base . '/admin/login');
        exit;
    }

    /** Load dan decode kredensial dari file JSON. */
    private static function loadCredentials(): ?array
    {
        $file = self::$credentialsFile;
        if (!file_exists($file)) {
            return null;
        }
        $fp   = fopen($file, 'r');
        flock($fp, LOCK_SH);
        $json = file_get_contents($file);
        flock($fp, LOCK_UN);
        fclose($fp);

        $data = json_decode($json, true);
        return is_array($data) ? $data : null;
    }

    /** Redirect ke dashboard admin. */
    private static function redirectDashboard(): void
    {
        $base = defined('APP_BASE') ? APP_BASE : '';
        header('Location: ' . $base . '/admin');
        exit;
    }
}
