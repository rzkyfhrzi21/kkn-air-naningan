<?php

declare(strict_types=1);

/* ======================================================
   SECURITY — Ketahanan terhadap Injeksi (SQLi)

   Proyek memakai penyimpanan JSON flat (bukan SQL), namun
   tetap wajib diuji: payload injeksi klasik tidak boleh
   mengubah perilaku endpoint (tidak 500, tidak bocor data,
   tidak mengeksekusi query). Payload dianggap "mentah"
   dan aman disimpan/di-filter sebagai teks biasa.
====================================================== */

require_once __DIR__ . '/../bootstrap.php';

ensureServer();
protectDataFiles();

$jar  = adminSessionCookie();
$csrf = TEST_CSRF;

$payloads = [
    "' OR 1=1 --",
    "\" OR 1=1 --",
    "' OR '1'='1",
    "'; DROP TABLE pesan; --",
    "1 UNION SELECT username,password FROM admin --",
    "' UNION ALL SELECT NULL,NULL,NULL --",
    "'; DELETE FROM umkm WHERE '1'='1",
    "1 OR 1=1 /*",
];

runTest('Payload SQLi di pencarian list-galeri tidak merusak response', static function () use ($jar, $payloads): void {
    foreach ($payloads as $i => $p) {
        $resp = httpRequest('POST', '/admin/ajax/list-galeri.php', ['page' => 1, 'search' => $p], $jar);
        assertStatus(200, $resp, 'Payload #' . $i . ' memicu status non-200.');
        $json = json_decode($resp['body'], true);
        assertTrue(is_array($json) && ($json['success'] ?? false) === true, 'Payload #' . $i . ' merusak response schema.');
        assertTrue(is_array($json['data'] ?? null), 'Payload #' . $i . ' menghilangkan field data.');
    }
});

runTest('Payload SQLi di filter list-pesan tidak merusak response', static function () use ($jar, $payloads): void {
    foreach ($payloads as $i => $p) {
        $resp = httpRequest('POST', '/admin/ajax/list-pesan.php', ['tab' => $p, 'search' => $p], $jar);
        assertStatus(200, $resp, 'Payload #' . $i . ' memicu status non-200.');
        $json = json_decode($resp['body'], true);
        assertTrue(($json['success'] ?? false) === true, 'Payload #' . $i . ' merusak response schema.');
        assertTrue(array_key_exists('stat_total', $json), 'Payload #' . $i . ' menghilangkan field stat_total.');
    }
});

runTest('Payload SQLi di parameter sort tidak mengubah perilaku', static function () use ($jar, $payloads): void {
    foreach ($payloads as $i => $p) {
        $resp = httpRequest('POST', '/admin/ajax/list-galeri.php', ['page' => 1, 'sort' => $p], $jar);
        assertStatus(200, $resp, 'Payload #' . $i . ' memicu status non-200.');
        $json = json_decode($resp['body'], true);
        assertTrue(($json['success'] ?? false) === true, 'Payload #' . $i . ' merusak response schema.');
    }
});

runTest('Payload SQLi di id store/delete ditolak aman (tidak mengeksekusi apa pun)', static function () use ($jar, $csrf, $payloads): void {
    $resp = httpRequest('POST', '/admin/ajax/delete-galeri.php', [
        'csrf_token' => $csrf,
        'id'         => $payloads[4],
    ], $jar);
    assertStatus(200, $resp);
    $json = json_decode($resp['body'], true);
    assertTrue(($json['success'] ?? true) === false, 'Id injeksi harus ditolak (tidak sukses).');
});

finishTests();