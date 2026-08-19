<?php

declare(strict_types=1);

/* ======================================================
   SECURITY — Autentikasi & Otorisasi Endpoint Admin

   Semua endpoint AJAX admin wajib:
   - 401 tanpa sesi admin
   - 405 untuk metode selain POST
   - 403 bila CSRF tidak valid / tidak dikirim
   Halaman /admin wajib redirect ke login bila belum login.
====================================================== */

require_once __DIR__ . '/../bootstrap.php';

ensureServer();
protectDataFiles();

$endpoints = [
    '/admin/ajax/list-galeri.php',
    '/admin/ajax/store-galeri.php',
    '/admin/ajax/delete-galeri.php',
    '/admin/ajax/reorder-galeri.php',
    '/admin/ajax/list-pesan.php',
    '/admin/ajax/get-pesan.php',
    '/admin/ajax/update-pesan.php',
    '/admin/ajax/delete-pesan.php',
];

runTest('Semua endpoint AJAX admin: 401 tanpa sesi', static function () use ($endpoints): void {
    foreach ($endpoints as $ep) {
        $resp = httpRequest('POST', $ep, ['page' => 1]);
        assertStatus(401, $resp, 'Endpoint ' . $ep . ' harus 401 tanpa sesi.');
        $json = json_decode($resp['body'], true);
        assertTrue(($json['success'] ?? true) === false, 'Response 401 harus success:false.');
        assertTrue(is_string($json['message'] ?? null), 'Response 401 harus punya pesan.');
    }
});

runTest('Endpoint write admin: 405 untuk metode GET (dengan sesi)', static function () use ($endpoints): void {
    $jar = adminSessionCookie();
    $writeOnly = ['store-galeri', 'delete-galeri', 'reorder-galeri', 'get-pesan', 'update-pesan', 'delete-pesan'];
    foreach ($writeOnly as $name) {
        $resp = httpRequest('GET', '/admin/ajax/' . $name . '.php', [], $jar);
        assertStatus(405, $resp, 'Endpoint ' . $name . '.php harus 405 untuk GET.');
    }
});

runTest('Endpoint write: 403 tanpa CSRF (dengan sesi)', static function (): void {
    $jar = adminSessionCookie();
    $resp = httpRequest('POST', '/admin/ajax/delete-galeri.php', ['id' => 'x'], $jar);
    assertStatus(403, $resp, 'Hapus tanpa CSRF harus 403.');
    $json = json_decode($resp['body'], true);
    assertContains('Token', (string) ($json['message'] ?? ''), 'Pesan harus menyebut token keamanan.');
});

runTest('Endpoint write: 403 dengan CSRF salah (dengan sesi)', static function (): void {
    $jar = adminSessionCookie();
    $resp = httpRequest('POST', '/admin/ajax/update-pesan.php', ['id' => 'x', 'csrf_token' => 'salah-token'], $jar);
    assertStatus(403, $resp, 'CSRF salah harus 403.');
});

runTest('Halaman /admin belum login → redirect ke /admin/login', static function (): void {
    $resp = httpRequest('GET', '/admin');
    assertStatus(302, $resp, 'Harus redirect (302).');
    assertContains('Location: /admin/login', $resp['headers'], 'Redirect harus mengarah ke halaman login.');
});

runTest('Halaman /admin dengan sesi admin → 200', static function (): void {
    $jar = adminSessionCookie();
    $resp = httpRequest('GET', '/admin', [], $jar);
    assertStatus(200, $resp, 'Dashboard admin dengan sesi harus 200.');
    assertContains('Dashboard', $resp['body'], 'Halaman dashboard harus ter-render.');
});

finishTests();