<?php

declare(strict_types=1);

/* ======================================================
   FEATURE — AJAX Pesan Masuk (End-to-End lewat HTTP)

   Menguji alur bisnis modul pesan-masuk:
   - list-pesan: tab kotak masuk / arsip / semua + stats
   - update-pesan: tandai dibaca, arsip, kembalikan, belum dibaca
   - get-pesan: BACA SAJA — TIDAK boleh mengubah is_read
     (regresi penting: bug lama auto-tandai-dibaca saat buka)
   - delete-pesan: hapus pesan
   Data production dipulihkan otomatis oleh protectDataFiles().
====================================================== */

require_once __DIR__ . '/../bootstrap.php';

ensureServer();
protectDataFiles();

$jar  = adminSessionCookie();
$csrf = TEST_CSRF;

$latest = static function (): array {
    require_once PROJECT_ROOT . '/app/Models/Pesan.php';
    $all = Pesan::all();
    assertTrue($all !== [], 'Data pesan.json kosong — suite butuh minimal 1 pesan dummy.');
    return $all[0];
};

runTest('list-pesan kotak masuk: 200 + stats + item berstatus tidak diarsipkan', static function () use ($jar): void {
    $resp = httpRequest('POST', '/admin/ajax/list-pesan.php', ['read_filter' => 'all'], $jar);
    assertStatus(200, $resp);
    $json = json_decode($resp['body'], true);
    assertTrue(($json['success'] ?? false) === true, 'Response harus success:true.');
    assertTrue(is_array($json['data'] ?? null) && count($json['data']) >= 1, 'Kotak masuk minimal 1 item.');
    assertTrue(array_key_exists('stat_total', $json), 'Response harus memuat stat_total.');
    assertTrue(array_key_exists('stat_baru', $json), 'Response harus memuat stat_baru.');
    assertTrue((int) ($json['stat_total'] ?? 0) >= 1, 'stat_total minimal 1.');
    foreach ($json['data'] as $item) {
        assertTrue(($item['is_archived'] ?? true) === false, 'Item kotak masuk tidak boleh berstatus arsip.');
    }
});

runTest('update-pesan: tandai dibaca menghasilkan toast sukses', static function () use ($jar, $csrf, $latest): void {
    $pesan = $latest();
    $resp  = httpRequest('POST', '/admin/ajax/update-pesan.php', [
        'csrf_token' => $csrf,
        'id'         => $pesan['id'],
        'is_read'    => '1',
    ], $jar);
    $json = json_decode($resp['body'], true);
    assertTrue(($json['success'] ?? false) === true, 'Tandai dibaca harus sukses.');
    assertTrue(is_string($json['message'] ?? null) && $json['message'] !== '', 'Harus ada pesan untuk toast.');
    assertTrue($latest()['is_read'] === true, 'is_read harus menjadi true di file JSON.');
});

runTest('update-pesan: arsip → toast "Pesan diarsipkan." + pindah tab arsip', static function () use ($jar, $csrf, $latest): void {
    $pesan = $latest();
    $resp  = httpRequest('POST', '/admin/ajax/update-pesan.php', [
        'csrf_token' => $csrf,
        'id'         => $pesan['id'],
        'is_archived' => '1',
    ], $jar);
    $json = json_decode($resp['body'], true);
    assertTrue(($json['success'] ?? false) === true, 'Arsip harus sukses.');
    assertContains('diarsipkan', (string) ($json['message'] ?? ''), 'Pesan toast harus menyebut "diarsipkan".');

    $list = json_decode(httpRequest('POST', '/admin/ajax/list-pesan.php', ['read_filter' => 'archived'], $jar)['body'], true);
    assertTrue(count($list['data'] ?? []) >= 1, 'Tab arsip harus memuat pesan yang diarsipkan.');
    assertTrue($list['data'][0]['is_archived'] === true, 'Item di tab arsip harus berstatus arsip.');
});

runTest('update-pesan: kembalikan dari arsip ke kotak masuk', static function () use ($jar, $csrf, $latest): void {
    $pesan = $latest();
    $resp  = httpRequest('POST', '/admin/ajax/update-pesan.php', [
        'csrf_token' => $csrf,
        'id'         => $pesan['id'],
        'is_archived' => '0',
        'is_read'    => '0',
    ], $jar);
    $json = json_decode($resp['body'], true);
    assertTrue(($json['success'] ?? false) === true, 'Kembalikan dari arsip harus sukses.');
    assertContains('kembali', (string) ($json['message'] ?? ''), 'Pesan toast harus menyebut "kembali".');
    assertTrue($latest()['is_archived'] === false, 'Item harus kembali ke kotak masuk.');
});

runTest('get-pesan: BACA SAJA — tidak mengubah is_read', static function () use ($jar, $csrf, $latest): void {
    $pesan = $latest();
    $readBefore = $pesan['is_read'];

    $resp = httpRequest('POST', '/admin/ajax/get-pesan.php', [
        'csrf_token' => $csrf,
        'id'         => $pesan['id'],
    ], $jar);
    assertStatus(200, $resp);
    $json = json_decode($resp['body'], true);
    assertTrue(($json['success'] ?? false) === true, 'get-pesan harus sukses.');
    assertSame($pesan['id'], $json['data']['id'] ?? null, 'Data pesan harus sesuai id.');

    $after = $latest();
    assertSame($readBefore, $after['is_read'], 'get-pesan TIDAK boleh mengubah is_read (regresi lama).');
});

runTest('delete-pesan: hapus pesan + pesan sukses', static function () use ($jar, $csrf, $latest): void {
    $pesan = $latest();
    $resp  = httpRequest('POST', '/admin/ajax/delete-pesan.php', [
        'csrf_token' => $csrf,
        'id'         => $pesan['id'],
    ], $jar);
    $json = json_decode($resp['body'], true);
    assertTrue(($json['success'] ?? false) === true, 'Hapus harus sukses.');
    assertContains('dihapus', (string) ($json['message'] ?? ''), 'Pesan toast harus menyebut "dihapus".');

    require_once PROJECT_ROOT . '/app/Models/Pesan.php';
    assertTrue(Pesan::find($pesan['id']) === null, 'Pesan tidak boleh ditemukan setelah dihapus.');
});

finishTests();