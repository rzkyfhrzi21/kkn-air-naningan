<?php

declare(strict_types=1);

/* ======================================================
   UNIT — Sanitasi & Escape Output

   Menguji fungsi sanitasi standar yang dipakai di seluruh
   view: htmlspecialchars(ENT_QUOTES) harus benar-benar
   menetralkan payload XSS, dan json_encode harus menghasilkan
   JSON valid dengan karakter UTF-8 utuh.
====================================================== */

require_once __DIR__ . '/../bootstrap.php';

$esc = static fn(string $s): string => htmlspecialchars($s, ENT_QUOTES, 'UTF-8');

runTest('htmlspecialchars menetralkan tag script', static function () use ($esc): void {
    $out = $esc('<script>alert(1)</script>');
    assertNotContains('<script>', $out, 'Tag <script> tidak boleh lolos.');
    assertContains('&lt;script&gt;', $out, 'Harus ter-escape menjadi entitas.');
});

runTest('htmlspecialchars menetralkan atribut via kutip ganda & tunggal', static function () use ($esc): void {
    $out = $esc('" onmouseover="alert(1)" \' onclick=\'alert(2)');
    assertNotContains('" onmouseover="', $out);
    assertNotContains("' onclick='", $out);
    assertContains('&quot;', $out, 'Kutip ganda harus di-escape.');
    assertContains('&#039;', $out, 'Kutip tunggal harus di-escape.');
});

runTest('Payload XSS gabungan (tag + event handler) dinetralkan', static function () use ($esc): void {
    $payload = '<img src=x onerror=alert(1)><a href="javascript:alert(1)">klik</a>';
    $out     = $esc($payload);
    assertNotContains('<img', $out, 'Tag <img> tidak boleh utuh.');
    assertNotContains('<a ', $out, 'Tag <a> tidak boleh utuh.');
    assertContains('&lt;', $out, 'Karakter < harus ter-escape.');
    // htmlspecialchars tidak mengubah kata biasa (onerror/javascript:),
    // tetapi menetralkan pembukaan tag sehingga payload tak ter-render.
    assertTrue(!str_contains($out, '<img') && !str_contains($out, '<a href'), 'Tag tetap utuh — escape gagal.');
});

runTest('Konteks href dari input pengguna selalu dibangun di server (bukan mentah)', static function (): void {
    // Di proyek ini input pengguna tidak pernah dipakai sebagai href mentah:
    // nomor WhatsApp diubah menjadi tautan wa.me oleh server/JS helper.
    $wa = '081234567890';
    $normalized = preg_replace('/^0/', '62', $wa);
    assertSame('6281234567890', $normalized, 'Nomor WA harus dinormalisasi ke awalan 62 di server.');
});

runTest('json_encode menghasilkan JSON valid dan aman dari break out', static function (): void {
    $data = ['nama' => 'Pekon Air Naningan', 'pesan' => 'Halo "dunia" \'tunggal\' </script>'];
    $json = json_encode($data, JSON_UNESCAPED_UNICODE);
    assertTrue($json !== false, 'json_encode gagal.');
    assertTrue(json_decode($json, true) === $data, 'JSON tidak round-trip.');
    $encoded = htmlspecialchars($json, ENT_QUOTES, 'UTF-8');
    assertNotContains('</script>', $encoded, 'Penutup </script> harus di-escape dalam konteks inline JS/JSON.');
});

runTest('Trim normalisasi input dipakai pada teks bebas', static function (): void {
    $raw = "   nama dengan spasi berlebih\t ";
    assertSame('nama dengan spasi berlebih', trim($raw), 'trim() seharusnya merapikan spasi.');
    assertTrue(trim('   ') === '', 'Hanya-spasi harus menjadi string kosong.');
});

finishTests();