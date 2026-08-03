<?php

/**
 * index.php — Front Controller Publik
 *
 * Bekerja di root (http://localhost/) maupun subdirektori
 * (http://localhost:8090/kkn-air-naningan2/) tanpa konfigurasi tambahan.
 */

declare(strict_types=1);

define('BASE_PATH', __DIR__);

// ── Deteksi APP_BASE secara otomatis ─────────────────────────────────────────
// Misal SCRIPT_NAME = /kkn-air-naningan2/index.php → APP_BASE = /kkn-air-naningan2
// Misal SCRIPT_NAME = /index.php                   → APP_BASE = ''
$_scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php');
define('APP_BASE', $_scriptDir === '/' ? '' : rtrim($_scriptDir, '/'));
unset($_scriptDir);

// ── Session security ──────────────────────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => APP_BASE !== '' ? APP_BASE . '/' : '/',
        'secure'   => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

// ── Parse & bersihkan URI ────────────────────────────────────────────────────
$_rawUri  = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$_rawUri  = preg_replace('/\.php$/', '', $_rawUri);   // toleransi URL lama ber-.php
$_rawUri  = substr($_rawUri, strlen(APP_BASE));         // strip base path
$uri      = trim($_rawUri, '/');
$segments = $uri !== '' ? explode('/', $uri) : [];
$seg0     = $segments[0] ?? '';  // 'admin', 'profil', '', ...
$seg1     = $segments[1] ?? '';  // 'login', 'logout', 'kelola-umkm', ...
unset($_rawUri);

// ── Route table publik ───────────────────────────────────────────────────────
$publicRoutes = [
    ''            => ['HomeController',    'app/Controllers/HomeController.php'],
    'profil'      => ['ProfilController',  'app/Controllers/ProfilController.php'],
    'profil-desa' => ['ProfilController',  'app/Controllers/ProfilController.php'],
    'wisata'      => ['WisataController',  'app/Controllers/WisataController.php'],
    'umkm'        => ['UmkmController',    'app/Controllers/UmkmController.php'],
    'berita'      => ['BeritaController',  'app/Controllers/BeritaController.php'],
    'galeri'      => ['GaleriController',  'app/Controllers/GaleriController.php'],
    'kontak'      => ['KontakController',  'app/Controllers/KontakController.php'],
    'potensi'     => ['PotensiController', 'app/Controllers/PotensiController.php'],
];

// ── Dispatch ─────────────────────────────────────────────────────────────────
if ($seg0 === 'admin') {

    // ── Admin area ────────────────────────────────────────────────────────────
    require_once BASE_PATH . '/app/Controllers/Admin/AuthController.php';
    require_once BASE_PATH . '/app/Controllers/Admin/DashboardController.php';

    // ── Admin route map ───────────────────────────────────────────────────────
    $adminRoutes = [
        'kelola-umkm'    => 'KelolaUmkmController',
        'kelola-wisata'  => 'KelolaWisataController',
        'kelola-potensi' => 'KelolaPotensiController',
        'kelola-galeri'  => 'KelolaGaleriController',
        'kelola-profil'  => 'KelolaProfilController',
        'kelola-berita'  => 'KelolaBeritaController',
        'pesan-masuk'    => 'PesanMasukController',
        'pengaturan'     => 'PengaturanController',
    ];

    $seg2 = $segments[2] ?? '';

    if ($seg1 === 'login') {
        AuthController::login();

    } elseif ($seg1 === 'logout') {
        AuthController::logout();

    } elseif ($seg1 === 'ajax' && $seg2 !== '') {
        // /admin/ajax/{endpoint} → public/admin/ajax/{endpoint}.php
        $ajaxFile = BASE_PATH . '/public/admin/ajax/' . basename($seg2) . '.php';
        if (!is_file($ajaxFile)) {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Endpoint tidak ditemukan.']);
            exit;
        }
        require $ajaxFile;

    } elseif (isset($adminRoutes[$seg1])) {
        $ctrlClass = $adminRoutes[$seg1];
        require_once BASE_PATH . '/app/Controllers/Admin/' . $ctrlClass . '.php';
        (new $ctrlClass())->index();

    } else {
        // Dashboard (seg1 === '' atau 'dashboard')
        (new DashboardController())->index();
    }

} elseif (isset($publicRoutes[$seg0])) {

    // ── Halaman publik ────────────────────────────────────────────────────────
    [$class, $file] = $publicRoutes[$seg0];
    require_once BASE_PATH . '/' . $file;

    // /berita/{slug} → detail artikel
    if ($seg0 === 'berita' && $seg1 !== '') {
        $GLOBALS['_beritaSlug'] = $seg1;
        (new $class())->detail();
    } else {
        (new $class())->index();
    }

} else {

    // ── 404 ───────────────────────────────────────────────────────────────────
    http_response_code(404);
    $base = APP_BASE;
    echo '<!doctype html><html lang="id"><head><meta charset="utf-8"><title>404 — Pekon Air Naningan</title>'
       . '<style>body{font-family:sans-serif;display:flex;align-items:center;justify-content:center;'
       . 'min-height:100vh;background:#12201A;color:#F3ECDA;margin:0}a{color:#f2bf5d}</style>'
       . '</head><body><div style="text-align:center">'
       . '<h1 style="font-size:5rem;margin:0;opacity:.4">404</h1>'
       . '<p style="font-size:1.1rem">Halaman tidak ditemukan.</p>'
       . '<a href="' . htmlspecialchars($base ?: '/') . '">← Kembali ke Beranda</a>'
       . '</div></body></html>';
}
