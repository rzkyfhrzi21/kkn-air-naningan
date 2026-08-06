<?php

declare(strict_types=1);

/**
 * AuthController — Menangani login dan logout admin.
 *
 * GET  /admin/login  → tampilkan form login
 * POST /admin/login  → proses kredensial
 * GET  /admin/logout → hapus sesi, redirect ke login
 *
 * Kontrol keamanan yang diterapkan (OWASP Top 10 yang relevan):
 *  - A07 (AuthN/AuthZ failure): rate limit berbasis IP di server-side,
 *    pesan error seragam (anti user-enumeration), session regeneration,
 *    session timeout inaktivitas, cookie session secure+httpOnly+SameSite.
 *  - A09 (Logging): pencatatan login_success / login_failed / rate_limited.
 *  - Login-CSRF: POST login dilindungi token CSRF.
 *  - Proteksi abuse input: batasan panjang username & password di server.
 */
final class AuthController
{
    private static string $credentialsFile = __DIR__ . '/../../../secure/admin_credentials.json';
    private static string $rateLimitFile   = __DIR__ . '/../../../secure/login_attempts.json';

    /** Tampilkan halaman login (GET) atau proses login (POST). */
    public static function login(): void
    {
        // Pastikan sesi aktif (aman bila dipanggil tanpa router).
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Sudah login → langsung ke dashboard.
        if (!empty($_SESSION['admin'])) {
            self::redirectDashboard();
        }

        // Jaring pengaman anti-CSRF.
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $redirectForHttps = self::redirectToHttps();
        if ($redirectForHttps) {
            return; // redirect() sudah mengakhiri proses (exit).
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // ── Anti-CSRF pada form login ──────────────────────────────────
            $token = (string) ($_POST['csrf_token'] ?? '');
            if ($token === '' || !hash_equals($_SESSION['csrf_token'], $token)) {
                $error = 'Token keamanan tidak valid. Muat ulang halaman dan coba lagi.';
            } else {
                // ── Rate limit berbasis IP (server-side store) ─────────────
                $ip    = self::clientIp();
                $limit = self::consumeRateLimit($ip);
                if ($limit !== '') {
                    require_once __DIR__ . '/../../../includes/logger.php';
                    writeLog('login_rate_limited', 'Rate limit tercapai untuk IP: ' . $ip);
                    $error = $limit;
                } else {
                    $username = trim((string) ($_POST['username'] ?? ''));
                    $password = (string) ($_POST['password'] ?? '');

                    // Batasi panjang (harus sinkron dengan atribut maxlength pada form).
                    if (strlen($username) > 64 || strlen($password) > 256) {
                        $username = '';
                        $password = '';
                    } elseif ($username === '' || $password === '') {
                        // Kosong: biarkan lewat ke verifikasi agar data user tidak bocor
                        // (response tetap seragam, tanpa membedakan field mana yang kosong).
                        $username = '';
                        $password = '';
                    }

                    // ── Cek kredensial ────────────────────────────────────
                    $creds    = self::loadCredentials();
                    $okUser   = $creds !== null && hash_equals((string) ($creds['username'] ?? ''), $username);
                    $okPass   = $creds !== null && password_verify($password, (string) ($creds['password_hash'] ?? ''));
                    $authed   = $okUser && $okPass;

                    require_once __DIR__ . '/../../../includes/logger.php';
                    if ($authed) {
                        // Regenerasi sesi + token CSRF (session fixation / token reuse).
                        $safeUser  = substr($username, 0, 32);
                        session_regenerate_id(true);
                        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                        $_SESSION['admin'] = true;
                        $_SESSION['admin_username'] = $safeUser;
                        $_SESSION['admin_nama_lengkap'] = (string) ($creds['nama_lengkap'] ?? '');
                        $_SESSION['login_at'] = time();
                        $_SESSION['admin_last_activity'] = time();
                        self::clearRateLimit($ip);

                        writeLog('login_success', 'Login berhasil untuk username: ' . $safeUser);
                        self::redirectDashboard();
                    } else {
                        writeLog('login_failed', 'Percobaan login gagal untuk username: ' . ($username !== '' ? $username : '(kosong)'));
                        $error = 'Username atau kata sandi salah.';
                    }
                }
            }
        }

        $loginLogo   = self::loginLogo();
        $csrfToken   = (string) ($_SESSION['csrf_token'] ?? '');
        self::noStoreHeaders();
        require __DIR__ . '/../../Views/admin/login/index.php';
    }

    /** Hapus sesi dan redirect ke halaman login. */
    public static function logout(): void
    {
        require_once __DIR__ . '/../../../includes/logger.php';
        writeLog('logout', 'Admin keluar dari panel.');

        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $p['path'], $p['domain'], $p['secure'], $p['httponly']
            );
        }
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }

        self::noStoreHeaders();
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
        $fp = fopen($file, 'r');
        if ($fp === false) {
            return null;
        }
        flock($fp, LOCK_SH);
        $json = stream_get_contents($fp);
        flock($fp, LOCK_UN);
        fclose($fp);

        $data = json_decode($json ?: '{}', true);
        return is_array($data) ? $data : null;
    }

    /**
     * Ambil referensi logo asli dari data profil (public/data/profil.json),
     * dengan fallback ke aset default /assets/images/logo.jpg.
     */
    private static function loginLogo(): string
    {
        $file = __DIR__ . '/../../../public/data/profil.json';
        if (file_exists($file)) {
            $data = json_decode((string) file_get_contents($file), true);
            if (is_array($data) && isset($data['logo']) && is_string($data['logo']) && trim($data['logo']) !== '') {
                return trim($data['logo']);
            }
        }
        return '/assets/images/logo.jpg';
    }

    /**
     * IP klien, mempertimbangkan proxy terpercaya (X-Forwarded-For)
     * hanya bila indikator proxy ada; default REMOTE_ADDR.
     */
    private static function clientIp(): string
    {
        $xff = trim((string) ($_SERVER['HTTP_X_FORWARDED_FOR'] ?? ''));
        if ($xff !== '' && strtolower((string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')) !== '') {
            $parts = explode(',', $xff);
            $first = trim((string) ($parts[0] ?? ''));
            if ($first !== '' && filter_var($first, FILTER_VALIDATE_IP)) {
                return $first;
            }
        }
        return (string) ($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
    }

    /**
     * Rate limit IP: maksimum $max per $window detik.  Catat percobaan
     * pada file JSON di /secure (server-side), bersihkan entri lama.
     */
    private static function consumeRateLimit(string $ip): string
    {
        $window = 900;    // 15 menit
        $maxTry = 10;     // maks 10 percobaan per window per IP

        $now  = time();
        $data = [];
        if (file_exists(self::$rateLimitFile)) {
            $raw  = (string) file_get_contents(self::$rateLimitFile);
            $data = json_decode($raw, true);
            if (!is_array($data)) {
                $data = [];
            }
        }

        $bucket = $data[$ip] ?? [];
        $bucket = array_values(array_filter(
            (array) $bucket,
            static fn($ts): bool => $now - (int) $ts < $window
        ));
        $bucket[] = $now;
        $data[$ip] = $bucket;

        self::saveRateLimit($data);

        if (count($bucket) > $maxTry) {
            return 'Terlalu banyak percobaan login. Silakan tunggu beberapa menit lalu coba lagi.';
        }
        return '';
    }

    private static function clearRateLimit(string $ip): void
    {
        $data = [];
        if (file_exists(self::$rateLimitFile)) {
            $data = json_decode((string) file_get_contents(self::$rateLimitFile), true);
            if (!is_array($data)) {
                $data = [];
            }
        }
        unset($data[$ip]);
        self::saveRateLimit($data);
    }

    private static function saveRateLimit(array $data): void
    {
        $dir = dirname(self::$rateLimitFile);
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        $fp = @fopen(self::$rateLimitFile, 'c');
        if ($fp === false) {
            return;
        }
        flock($fp, LOCK_EX);
        ftruncate($fp, 0);
        fwrite($fp, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        flock($fp, LOCK_UN);
        fclose($fp);
    }

    /** Redirect HTTP → HTTPS hanya untuk domain production (kanonis SEO). */
    private static function redirectToHttps(): bool
    {
        $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
        if ($isSecure) {
            return false;
        }
        $host = strtolower((string) ($_SERVER['HTTP_HOST'] ?? ''));
        $isProd = ($host === 'pekon-air-naningan.web.id' || str_ends_with($host, '.pekon-air-naningan.web.id'));
        if (!$isProd) {
            return false; // biarkan HTTP untuk pengembangan lokal / staging.
        }
        header('Location: https://' . $host . (string) ($_SERVER['REQUEST_URI'] ?? '/'), true, 301);
        exit;
    }

    /** Cache-control agar halaman login tidak disimpan oleh proxy/browser. */
    private static function noStoreHeaders(): void
    {
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
    }

    /** Redirect ke dashboard admin. */
    private static function redirectDashboard(): void
    {
        $base = defined('APP_BASE') ? APP_BASE : '';
        header('Location: ' . $base . '/admin');
        exit;
    }
}