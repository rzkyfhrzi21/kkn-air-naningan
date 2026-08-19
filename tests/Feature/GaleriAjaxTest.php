<?php

declare(strict_types=1);

/* ======================================================
   FEATURE — AJAX Galeri (End-to-End lewat HTTP)

   Menguji seluruh endpoint admin galeri sesuai alur bisnis:
   - list-galeri: default sort 'urutan', filter kategori,
     pencarian, pagination (has_prev/has_next)
   - reorder-galeri: swap naik/turun + pesan edge
   - store-galeri: validasi kolom wajib
   - semua tanpa autentikasi = 401 (dites di Security)
====================================================== */

require_once __DIR__ . '/../bootstrap.php';
require_once PROJECT_ROOT . '/app/Models/Galeri.php';

ensureServer();
protectDataFiles();

$jar  = adminSessionCookie();
$csrf = TEST_CSRF;

runTest('list-galeri default: 200 + success + data + urutan ascending', static function () use ($jar): void {
    $resp = httpRequest('POST', '/admin/ajax/list-galeri.php', ['page' => 1], $jar);
    assertStatus(200, $resp);
    $json = json_decode($resp['body'], true);
    assertTrue(is_array($json) && ($json['success'] ?? false) === true, 'Response harus success:true.');
    assertTrue(is_array($json['data'] ?? null), 'Field data harus ada.');
    assertTrue(count($json['data']) >= 2, 'Data default minimal 2 item.');

    $prev = 0;
    foreach ($json['data'] as $item) {
        $u = (int) ($item['urutan'] ?? 0);
        assertTrue($u > $prev, 'Urutan default harus ascending (dapat ' . $u . ' setelah ' . $prev . ').');
        $prev = $u;
    }
});

runTest('list-galeri sort=terbaru mengembalikan urutan created_at menurun', static function () use ($jar): void {
    $resp = httpRequest('POST', '/admin/ajax/list-galeri.php', ['page' => 1, 'sort' => 'terbaru'], $jar);
    assertStatus(200, $resp);
    $json = json_decode($resp['body'], true);
    assertTrue(($json['success'] ?? false) === true, 'Response harus success:true.');
    $prev = PHP_INT_MAX;
    foreach ($json['data'] as $item) {
        $ts = strtotime((string) ($item['created_at'] ?? ''));
        assertTrue($ts <= $prev, 'sort=terbaru harus menurun.');
        $prev = $ts;
    }
});

runTest('list-galeri pencarian memfilter data', static function () use ($jar): void {
    $all   = json_decode(httpRequest('POST', '/admin/ajax/list-galeri.php', ['page' => 1], $jar)['body'], true)['data'] ?? [];
    $first = $all[0]['judul'] ?? '';
    assertTrue($first !== '', 'Butuh minimal 1 item untuk tes pencarian.');

    $needle = mb_substr($first, 0, 3);
    $resp   = httpRequest('POST', '/admin/ajax/list-galeri.php', ['page' => 1, 'search' => $needle], $jar);
    $json   = json_decode($resp['body'], true);
    assertTrue(($json['success'] ?? false) === true, 'Response harus success:true.');
    assertTrue(count($json['data'] ?? []) >= 1, 'Pencarian harus menemukan minimal 1 item.');
    foreach ($json['data'] as $item) {
        assertTrue(stripos((string) ($item['judul'] ?? ''), $needle) !== false, 'Hasil pencarian harus memuat kata kunci.');
    }
});

runTest('list-galeri pagination: halaman 2 punya has_prev', static function () use ($jar): void {
    $resp = httpRequest('POST', '/admin/ajax/list-galeri.php', ['page' => 2], $jar);
    $json = json_decode($resp['body'], true);
    assertTrue(($json['success'] ?? false) === true, 'Response harus success:true.');
    assertTrue(($json['has_prev'] ?? false) === true, 'Halaman 2 harus punya has_prev:true.');
    assertTrue(array_key_exists('has_next', $json), 'Field has_next harus ada.');
});

runTest('reorder-galeri: swap naik (up) mengubah urutan di JSON', static function () use ($jar, $csrf): void {
    $before = Galeri::all();
    $target = $before[1]; // urutan 2 → naik ke posisi 1
    assertSame('2', (string) $target['urutan'], 'Asumsi tes: item kedua berurutan 2.');

    $resp = httpRequest('POST', '/admin/ajax/reorder-galeri.php', [
        'csrf_token' => $csrf,
        'id'         => $target['id'],
        'direction'  => 'up',
    ], $jar);
    assertStatus(200, $resp);
    $json = json_decode($resp['body'], true);
    assertTrue(($json['success'] ?? false) === true, 'Swap naik harus success:true — pesan: ' . ($json['message'] ?? ''));
    assertContains('dinaikkan', (string) ($json['message'] ?? ''), 'Pesan sukses harus menyebut "dinaikkan".');

    $after = Galeri::all();
    assertSame('1', (string) $after[0]['urutan'], 'Item sasaran harus naik ke urutan 1.');
    assertSame($target['id'], $after[0]['id'], 'Item sasaran harus ada di posisi pertama.');
    $u1 = (int) $after[0]['urutan'];
    $u2 = (int) $after[1]['urutan'];
    assertTrue($u1 < $u2, 'Urutan harus tetap ascending setelah swap.');
});

runTest('reorder-galeri: swap naik pada item teratas memberi pesan edge', static function () use ($jar, $csrf): void {
    $first = Galeri::all()[0];
    $resp  = httpRequest('POST', '/admin/ajax/reorder-galeri.php', [
        'csrf_token' => $csrf,
        'id'         => $first['id'],
        'direction'  => 'up',
    ], $jar);
    $json = json_decode($resp['body'], true);
    assertTrue(($json['success'] ?? false) === false, 'Edge harus success:false.');
    assertContains('paling atas', (string) ($json['message'] ?? ''), 'Pesan edge harus menyebut "paling atas".');
});

runTest('reorder-galeri: swap turun (down) pada item terakhir memberi pesan edge', static function () use ($jar, $csrf): void {
    $all   = Galeri::all();
    $last  = end($all);
    $resp  = httpRequest('POST', '/admin/ajax/reorder-galeri.php', [
        'csrf_token' => $csrf,
        'id'         => $last['id'],
        'direction'  => 'down',
    ], $jar);
    $json = json_decode($resp['body'], true);
    assertTrue(($json['success'] ?? false) === false, 'Edge harus success:false.');
    assertContains('paling bawah', (string) ($json['message'] ?? ''), 'Pesan edge harus menyebut "paling bawah".');
});

runTest('reorder-galeri: direction tak dikenal ditolak', static function () use ($jar, $csrf): void {
    $first = Galeri::all()[0];
    $resp  = httpRequest('POST', '/admin/ajax/reorder-galeri.php', [
        'csrf_token' => $csrf,
        'id'         => $first['id'],
        'direction'  => 'sideways',
    ], $jar);
    $json = json_decode($resp['body'], true);
    assertTrue(($json['success'] ?? false) === false, 'Direction tak dikenal harus ditolak.');
});

runTest('store-galeri: judul kosong ditolak dengan pesan detail', static function () use ($jar, $csrf): void {
    $resp = httpRequest('POST', '/admin/ajax/store-galeri.php', [
        'csrf_token' => $csrf,
        'judul'      => '',
    ], $jar);
    assertStatus(200, $resp);
    $json = json_decode($resp['body'], true);
    assertTrue(($json['success'] ?? false) === false, 'Judul kosong harus gagal.');
    assertContains('wajib diisi', (string) ($json['message'] ?? ''), 'Pesan harus menyebut wajib diisi.');
});

finishTests();