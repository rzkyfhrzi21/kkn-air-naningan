<?php

declare(strict_types=1);

/* ======================================================
   SUPPORT — Sesi Admin Palsu untuk Pengujian

   Endpoint khusus lingkungan test (TIDAK untuk production).
   Saat diakses lewat server uji `php -S` (lihat bootstrap.php),
   endpoint ini mengisi sesi admin + token CSRF uji sehingga
   tes AJAX dapat menguji endpoint yang butuh autentikasi
   tanpa harus login sungguhan.

   URL: http://127.0.0.1:8899/tests/support/set-session.php
   Dipanggil oleh adminSessionCookie() di bootstrap.php.
====================================================== */

session_start();

$_SESSION['admin']      = true;
$_SESSION['csrf_token'] = 'test-csrf-token';
$_SESSION['login_time'] = time();

http_response_code(200);
exit;