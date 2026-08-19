<?php

declare(strict_types=1);

/* ======================================================
   QUALITY — Audit SEO Halaman Publik & Admin

   Server-rendered pages wajib punya meta lengkap:
   - title, meta description, canonical, og:title/og:image/og:url
   - halaman admin wajib noindex, nofollow (anti SEO admin)
   - robots.txt ada & menolak /admin
   - render awal publik TIDAK boleh bergantung AJAX
====================================================== */

require_once __DIR__ . '/../bootstrap.php';

ensureServer();

$publicPages = [
    '/'        => 'Beranda',
    '/galeri'  => 'Galeri',
    '/kontak'  => 'Kontak',
    '/umkm'    => 'UMKM',
    '/berita'  => 'Berita',
    '/profil'  => 'Profil',
];

runTest('Halaman publik merender HTML lengkap (tidak bergantung AJAX)', static function () use ($publicPages): void {
    foreach ($publicPages as $path => $name) {
        $resp = httpRequest('GET', $path);
        assertStatus(200, $resp, 'Halaman ' . $path . ' gagal.');
        assertMatches('/<!doctype html>/i', $resp['body'], 'Halaman ' . $path . ' harus HTML lengkap.');
        assertContains('<body', $resp['body'], 'Halaman ' . $path . ' harus punya body.');
        assertNotContains('id="app-boot"', $resp['body'], 'Halaman publik ' . $path . ' tidak boleh memakai skeleton boot admin.');
    }
});

runTest('Meta SEO lengkap di halaman publik (title, description, canonical, og)', static function () use ($publicPages): void {
    foreach ($publicPages as $path => $name) {
        $resp = httpRequest('GET', $path);
        assertStatus(200, $resp, 'Halaman ' . $path . ' gagal.');
        $body = $resp['body'];
        assertMatches('/<title>.*<\/title>/i', $body, 'Halaman ' . $path . ' tanpa <title>.');
        assertMatches('/<meta name="description" content="[^"]+"/i', $body, 'Halaman ' . $path . ' tanpa meta description.');
        assertMatches('/<link rel="canonical" href="[^"]+"/i', $body, 'Halaman ' . $path . ' tanpa canonical.');
        assertContains('<meta property="og:title"', $body, 'Halaman ' . $path . ' tanpa og:title.');
        assertContains('<meta property="og:description"', $body, 'Halaman ' . $path . ' tanpa og:description.');
        assertContains('<meta property="og:image"', $body, 'Halaman ' . $path . ' tanpa og:image.');
        assertContains('<meta property="og:url"', $body, 'Halaman ' . $path . ' tanpa og:url.');
    }
});

runTest('Halaman login admin noindex + form login ada', static function (): void {
    $resp = httpRequest('GET', '/admin/login');
    assertStatus(200, $resp, 'Halaman login gagal.');
    assertContains('noindex', $resp['body'], 'Login admin harus noindex.');
    assertContains('name="csrf_token"', $resp['body'], 'Form login harus punya CSRF.');
    assertContains('name="username"', $resp['body'], 'Form login harus punya input username.');
    assertContains('name="password"', $resp['body'], 'Form login harus punya input password.');
});

runTest('Dashboard admin (dengan sesi) noindex', static function (): void {
    $jar = adminSessionCookie();
    $resp = httpRequest('GET', '/admin', [], $jar);
    assertStatus(200, $resp, 'Dashboard gagal.');
    assertContains('noindex', $resp['body'], 'Halaman admin harus noindex.');
});

runTest('robots.txt ada dan menolak area admin', static function (): void {
    $resp = httpRequest('GET', '/robots.txt');
    assertStatus(200, $resp, 'robots.txt tidak tersedia.');
    assertContains('Disallow: /admin', $resp['body'], 'robots.txt harus menolak /admin.');
});

finishTests();