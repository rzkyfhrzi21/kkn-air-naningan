<?php

declare(strict_types=1);

/* ======================================================
   SUPPORT — Router Server Uji (php -S)

   Dipakai oleh tests/bootstrap.php saat menjalankan
   `php -S ... tests/support/router.php` (argumen positional).

   Aturan routing:
   1. URL khusus tes /tests/support/set-session.php
      → jalankan langsung (docroot = public/, file tes di
      luar docroot tidak bisa di-serve biasa).
   2. URL yang cocok file fisik di docroot public/
      → biarkan server built-in PHP menyajikannya
      (return false), sehingga path identik dengan production.
   3. URL lain (route bersih seperti /admin/login, /galeri,
      /xyz) → serahkan ke front controller ROOT (index.php)
      yang menangani routing aplikasi. INI PENTING: tanpa
      langkah ini, php -S "fallback" ke index.php di direktori
      terdekat (mis. public/admin/index.php untuk /admin/login)
      sehingga halaman login malah menampilkan dashboard.
====================================================== */

$path = parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?: '/';

if ($path === '/tests/support/set-session.php') {
    require __DIR__ . '/set-session.php';
    return true;
}

$file = dirname(__DIR__, 2) . '/public' . $path;
if ($path !== '/' && is_file($file)) {
    return false; // file fisik di docroot: serahkan ke server
}

// Route aplikasi: serahkan ke front controller root.
// PENTING: php -S tidak mengubah SCRIPT_NAME saat router me-require
// file lain — SCRIPT_NAME tetap berisi path request (mis. /admin/login),
// padahal index.php menghitung APP_BASE dari SCRIPT_NAME. Timpa dulu
// dengan /index.php supaya APP_BASE menjadi '' (sama seperti production).
$_SERVER['SCRIPT_NAME'] = '/index.php';
require dirname(__DIR__, 2) . '/index.php';
return true;