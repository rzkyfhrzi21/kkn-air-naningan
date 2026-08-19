<?php

declare(strict_types=1);

/* ======================================================
   FEATURE — AJAX Kelola UMKM (End-to-End lewat HTTP)

   Menguji validasi No. WhatsApp di store-umkm sesuai aturan:
   - nomor dipaksa jadi angka (karakter non-digit dibuang)
   - harus diawali 62 dan panjang total 11-15 digit
   - nomor kosong tetap diperbolehkan (opsional)
   Data production dipulihkan otomatis oleh protectDataFiles().
====================================================== */

require_once __DIR__ . '/../bootstrap.php';

ensureServer();
protectDataFiles();

$jar  = adminSessionCookie();
$csrf = TEST_CSRF;

function postStoreUmkm(array $fields, string $jar): array
{
    $resp = httpRequest('POST', '/admin/ajax/store-umkm.php', $fields, $jar);
    assertStatus(200, $resp);
    $json = json_decode($resp['body'], true);
    assertTrue(is_array($json), 'Response harus JSON valid.');
    return $json;
}

runTest('store-umkm: no_wa diawali 0 ditolak dengan pesan format tidak valid', static function () use ($jar, $csrf): void {
    $json = postStoreUmkm([
        'csrf_token' => $csrf,
        'nama'       => 'Tes WA Nol',
        'no_wa'      => '081234567890',
    ], $jar);
    assertTrue(($json['success'] ?? false) === false, 'No WA diawali 0 harus gagal.');
    assertContains('format', mb_strtolower((string) ($json['message'] ?? '')), 'Pesan harus menyebut "format".');
});

runTest('store-umkm: no_wa terlalu pendek (<11 digit) ditolak', static function () use ($jar, $csrf): void {
    $json = postStoreUmkm([
        'csrf_token' => $csrf,
        'nama'       => 'Tes WA Pendek',
        'no_wa'      => '62812',
    ], $jar);
    assertTrue(($json['success'] ?? false) === false, 'No WA pendek harus gagal.');
});

runTest('store-umkm: no_wa terlalu panjang (>15 digit) ditolak', static function () use ($jar, $csrf): void {
    $json = postStoreUmkm([
        'csrf_token' => $csrf,
        'nama'       => 'Tes WA Panjang',
        'no_wa'      => '628123456789012345',
    ], $jar);
    assertTrue(($json['success'] ?? false) === false, 'No WA panjang harus gagal.');
});

runTest('store-umkm: no_wa dengan karakter non-digit dipaksa jadi angka (62... valid)', static function () use ($jar, $csrf): void {
    $json = postStoreUmkm([
        'csrf_token' => $csrf,
        'nama'       => 'Tes WA Campur',
        'kategori'   => 'kopi',
        'no_wa'      => '62 812-3456-7890',
    ], $jar);
    assertTrue(($json['success'] ?? false) === true, 'Non-digit harus dibuang, 6281234567890 valid — pesan: ' . ($json['message'] ?? ''));
    $saved = $json['data']['no_wa'] ?? '';
    assertSame('6281234567890', $saved, 'Nomor tersimpan harus berupa digit murni format 62.');
});

runTest('store-umkm: no_wa dikosongkan tetap diperbolehkan (opsional)', static function () use ($jar, $csrf): void {
    $json = postStoreUmkm([
        'csrf_token' => $csrf,
        'nama'       => 'Tes WA Kosong',
        'kategori'   => 'kopi',
        'no_wa'      => '',
    ], $jar);
    assertTrue(($json['success'] ?? false) === true, 'No WA kosong harus tetap boleh — pesan: ' . ($json['message'] ?? ''));
});

finishTests();