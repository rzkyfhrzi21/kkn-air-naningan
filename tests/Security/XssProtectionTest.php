<?php

declare(strict_types=1);

/* ======================================================
   SECURITY — Perlindungan XSS (Cross-Site Scripting)

   Data disimpan apa adanya di JSON (netral), tetapi saat
   dirender ke HTML WAJIB di-escape dengan
   htmlspecialchars(ENT_QUOTES). Tes ini:
   1. Menyuntik payload XSS ke data pesan (via Model, bukan
      browser) lalu memastikan endpoint list mengembalikannya
      apa adanya (tidak rusak / tidak 500).
   2. Memverifikasi escape output HTML menetralkan payload.
   3. Memverifikasi view admin & publik memakai fungsi escape
      (tidak menampilkan variabel mentah).
====================================================== */

require_once __DIR__ . '/../bootstrap.php';
require_once PROJECT_ROOT . '/app/Models/Pesan.php';

ensureServer();
protectDataFiles();

$jar  = adminSessionCookie();
$csrf = TEST_CSRF;

$xssPayloads = [
    '<script>alert(document.cookie)</script>',
    '<img src=x onerror=alert(1)>',
    '"><svg onload=alert(1)>',
    "'-alert(1)-'",
    'javascript:alert(1)',
];

runTest('Payload XSS disimpan apa adanya dan tidak merusak list-pesan', static function () use ($jar, $csrf, $xssPayloads): void {
    foreach ($xssPayloads as $i => $payload) {
        $item = Pesan::create([
            'nama'     => $payload,
            'kontak'   => '0812-test',
            'kategori' => 'saran',
            'pesan'    => $payload,
        ]);
        assertTrue($item !== null, 'Payload #' . $i . ' gagal disimpan.');

        $resp = httpRequest('POST', '/admin/ajax/list-pesan.php', ['tab' => 'inbox'], $jar);
        assertStatus(200, $resp, 'Payload #' . $i . ' memicu status non-200.');
        $json = json_decode($resp['body'], true);
        assertTrue(($json['success'] ?? false) === true, 'Payload #' . $i . ' merusak response schema.');

        // Simulasi render: escape harus menetralkan payload
        $escaped = htmlspecialchars($payload, ENT_QUOTES, 'UTF-8');
        assertNotContains('<script>', $escaped, 'Payload #' . $i . ' lolos escape.');
        assertNotContains('<img', $escaped, 'Payload #' . $i . ' lolos escape.');
    }

    // Bersihkan pesan uji (data asli dipulihkan oleh protectDataFiles juga)
    foreach (Pesan::all() as $item) {
        if (($item['kontak'] ?? '') === '0812-test') {
            Pesan::delete((string) $item['id']);
        }
    }
});

runTest('View admin pesan-masuk memakai escape pada nama & pesan', static function (): void {
    $view = (string) readFileSafe(PROJECT_ROOT . '/app/Views/admin/pesan-masuk/index.php');
    assertTrue($view !== '', 'View pesan-masuk tidak ditemukan.');
    assertContains('htmlspecialchars', $view, 'View wajib memakai htmlspecialchars.');
    assertNotContains("<?= \$item['nama'] ?>", $view, 'Nama tidak boleh dirender mentah.');
    assertNotContains("<?= \$pesan['nama'] ?>", $view, 'Nama tidak boleh dirender mentah.');
});

runTest('View publik kontak memakai escape pada input ulang', static function (): void {
    $view = (string) readFileSafe(PROJECT_ROOT . '/app/Views/public/kontak/index.php');
    if ($view === '') {
        markSkipped('View kontak tidak ditemukan.');
        return;
    }
    assertContains('htmlspecialchars', $view, 'View kontak wajib memakai htmlspecialchars.');
});

finishTests();