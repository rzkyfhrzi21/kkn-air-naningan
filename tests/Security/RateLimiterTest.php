<?php

declare(strict_types=1);

/* ======================================================
   SECURITY — Rate Limiter Login (anti brute force)

   Aturan bisnis aktual (lihat AuthController::consumeRateLimit):
   - Jendela 15 menit ($window = 900 detik) per alamat IP
   - Maksimal 10 percobaan gagal ($maxTry = 10)
   - Percobaan ke-11 ditolak dengan pesan
     'Terlalu banyak percobaan login. Silakan tunggu beberapa menit lalu coba lagi.'

   Tes memakai IP palsu dari rentang dokumentasi TEST-NET-3
   (203.0.113.x) lewat header X-Forwarded-For + X-Forwarded-Proto,
   sehingga TIDAK menyentuh bucket IP lokal pengembang.
   File login_attempts.json dipulihkan otomatis di akhir tes.
====================================================== */

require_once __DIR__ . '/../bootstrap.php';

ensureServer();
protectDataFiles();

$fakeIp    = '203.0.113.' . random_int(1, 254);
$proxyHdrs = ['X-Forwarded-For: ' . $fakeIp, 'X-Forwarded-Proto: https'];
$jar       = tempnam(sys_get_temp_dir(), 'rlim') ?: (sys_get_temp_dir() . '/rlim-cookie.txt');

runTest('Login tanpa CSRF ditolak', static function () use ($jar, $proxyHdrs): void {
    $resp = httpRequest('POST', '/admin/login', ['username' => 'x', 'password' => 'y'], $jar, $proxyHdrs);
    assertStatus(200, $resp, 'Harus re-render form (200) dengan pesan error.');
    assertContains('Token keamanan tidak valid', $resp['body'], 'Pesan CSRF harus muncul.');
});

runTest('10 percobaan kredensial salah → pesan "Username atau kata sandi salah."', static function () use ($jar, $proxyHdrs): void {
    $csrf = fetchLoginCsrf($jar);
    for ($i = 1; $i <= 10; $i++) {
        $resp = httpRequest('POST', '/admin/login', [
            'csrf_token' => $csrf,
            'username'   => 'admin-salah',
            'password'   => 'kata-sandi-salah-' . $i,
        ], $jar, $proxyHdrs);
        assertStatus(200, $resp, 'Percobaan #' . $i . ' harus re-render (200).');
        assertContains('Username atau kata sandi salah', $resp['body'], 'Percobaan #' . $i . ' harus pesan kredensial salah.');
    }
});

runTest('Percobaan ke-11 diblokir rate limiter', static function () use ($jar, $proxyHdrs): void {
    $csrf = fetchLoginCsrf($jar);
    $resp = httpRequest('POST', '/admin/login', [
        'csrf_token' => $csrf,
        'username'   => 'admin-salah',
        'password'   => 'masih-coba-lagi',
    ], $jar, $proxyHdrs);
    assertStatus(200, $resp);
    assertContains('Terlalu banyak percobaan login', $resp['body'], 'Percobaan ke-11 harus diblokir rate limiter.');
});

runTest('Blokir rate limiter tercatat di login_attempts.json (bucket IP palsu)', static function () use ($fakeIp): void {
    $raw = file_get_contents(PROJECT_ROOT . '/secure/login_attempts.json');
    assertTrue($raw !== false, 'File login_attempts.json harus ada.');
    $data = json_decode((string) $raw, true);
    assertTrue(is_array($data) && isset($data[$fakeIp]), 'Bucket IP palsu harus tercatat.');
    // Struktur bucket: array timestamp percobaan (kunci = IP, nilai = daftar waktu)
    assertTrue(is_array($data[$fakeIp]) && count($data[$fakeIp]) >= 10, 'Jumlah percobaan tercatat minimal 10.');
});

runTest('Login kredensial salah dengan CSRF salah tetap ditolak (tanpa bocor)', static function (): void {
    $jar2 = tempnam(sys_get_temp_dir(), 'rlim2') ?: '';
    $resp = httpRequest('POST', '/admin/login', [
        'csrf_token' => 'token-asal-asalan',
        'username'   => 'x',
        'password'   => 'y',
    ], $jar2, ['X-Forwarded-For: 203.0.113.200', 'X-Forwarded-Proto: https']);
    assertContains('Token keamanan tidak valid', $resp['body'], 'CSRF salah harus ditolak.');
});

// Bersihkan bucket IP palsu yang dibuat tes ini dari login_attempts.json
// (jangan sampai meninggalkan sampah di file production).
$rateFile = PROJECT_ROOT . '/secure/login_attempts.json';
$rateRaw  = is_file($rateFile) ? file_get_contents($rateFile) : false;
if ($rateRaw !== false) {
    $rateData = json_decode($rateRaw, true);
    if (is_array($rateData)) {
        unset($rateData[$fakeIp]);
        file_put_contents($rateFile, json_encode($rateData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}

finishTests();