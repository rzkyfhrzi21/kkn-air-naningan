<?php

declare(strict_types=1);

/* ======================================================
   FEATURE — Validasi Upload Media Galeri

   Menguji batas & format upload sesuai aturan bisnis:
   - Foto > 2MB  → ditolak dengan pesan 'Ukuran foto maksimal 2MB.'
   - Video > 15MB → ditolak dengan pesan 'Ukuran video maksimal 15MB.'
   - Spoofing ekstensi (file .jpg berisi PHP) → ditolak via finfo
   - Video dengan MIME tak dikenal (MKV) → ditolak
   - Upload JPEG asli (lewat GD) → sukses + file tersimpan,
     lalu dibersihkan (file dihapus, galeri.json dipulihkan)
====================================================== */

require_once __DIR__ . '/../bootstrap.php';

ensureServer();
protectDataFiles();

$jar  = adminSessionCookie();
$csrf = TEST_CSRF;

$baseFields = ['csrf_token' => $csrf, 'judul' => 'Media Uji Otomatis', 'kategori' => 'kegiatan', 'tipe' => 'foto'];

runTest('Foto > 2MB ditolak dengan pesan batas ukuran', static function () use ($jar, $baseFields): void {
    $tmp = tempnam(sys_get_temp_dir(), 'foto') ?: '';
    assertTrue($tmp !== '', 'Gagal membuat file temp.');
    $f = fopen($tmp, 'wb');
    fwrite($f, str_repeat("\0", (2 * 1024 * 1024) + 10));
    fclose($f);

    $resp = httpRequest('POST', '/admin/ajax/store-galeri.php', $baseFields, $jar, [], [
        'media_file' => new CURLFile($tmp, 'image/jpeg', 'besar.jpg'),
    ]);
    @unlink($tmp);

    $json = json_decode($resp['body'], true);
    assertTrue(($json['success'] ?? true) === false, 'Foto > 2MB harus ditolak.');
    assertContains('maksimal 2MB', (string) ($json['message'] ?? ''), 'Pesan harus menyebut batas 2MB.');
});

runTest('Video > 15MB ditolak dengan pesan batas ukuran', static function () use ($jar, $baseFields): void {
    $tmp = tempnam(sys_get_temp_dir(), 'vid') ?: '';
    $f = fopen($tmp, 'wb');
    fwrite($f, str_repeat("\0", (15 * 1024 * 1024) + 10));
    fclose($f);

    $fields = $baseFields;
    $fields['tipe'] = 'video';
    $resp = httpRequest('POST', '/admin/ajax/store-galeri.php', $fields, $jar, [], [
        'media_file' => new CURLFile($tmp, 'video/mp4', 'besar.mp4'),
    ]);
    @unlink($tmp);

    $json = json_decode($resp['body'], true);
    assertTrue(($json['success'] ?? true) === false, 'Video > 15MB harus ditolak.');
    assertContains('maksimal 15MB', (string) ($json['message'] ?? ''), 'Pesan harus menyebut batas 15MB.');
});

runTest('Spoofing MIME: file .jpg berisi PHP ditolak (finfo, bukan ekstensi)', static function () use ($jar, $baseFields): void {
    $tmp = tempnam(sys_get_temp_dir(), 'spoof') ?: '';
    file_put_contents($tmp, "<?php echo 'pwned'; ?>");
    $resp = httpRequest('POST', '/admin/ajax/store-galeri.php', $baseFields, $jar, [], [
        'media_file' => new CURLFile($tmp, 'image/jpeg', 'gambar.jpg'),
    ]);
    @unlink($tmp);

    $json = json_decode($resp['body'], true);
    assertTrue(($json['success'] ?? true) === false, 'Spoof MIME harus ditolak.');
    assertContains('Format foto tidak valid', (string) ($json['message'] ?? ''), 'Pesan harus menyebut format tidak valid.');
});

runTest('MKV (video tak dikenal) ditolak', static function () use ($jar, $baseFields): void {
    $tmp = tempnam(sys_get_temp_dir(), 'mkv') ?: '';
    file_put_contents($tmp, str_repeat('matroska', 200));
    $fields = $baseFields;
    $fields['tipe'] = 'video';
    $resp = httpRequest('POST', '/admin/ajax/store-galeri.php', $fields, $jar, [], [
        'media_file' => new CURLFile($tmp, 'video/x-matroska', 'film.mkv'),
    ]);
    @unlink($tmp);

    $json = json_decode($resp['body'], true);
    assertTrue(($json['success'] ?? true) === false, 'MKV harus ditolak.');
    assertContains('Format video tidak valid', (string) ($json['message'] ?? ''), 'Pesan harus menyebut format tidak valid.');
});

runTest('Upload JPEG asli berhasil + file tersimpan + pesan toast', static function () use ($jar, $baseFields): void {
    if (!function_exists('imagecreatetruecolor')) {
        markSkipped('Ekstensi GD tidak tersedia — kasus sukses-upload dilewati.');
        return;
    }
    $img = imagecreatetruecolor(64, 64);
    imagefill($img, 0, 0, imagecolorallocate($img, 200, 120, 40));
    $tmp = tempnam(sys_get_temp_dir(), 'jpeg') ?: '';
    imagejpeg($img, $tmp, 85);
    imagedestroy($img);

    $resp = httpRequest('POST', '/admin/ajax/store-galeri.php', $baseFields, $jar, [], [
        'media_file' => new CURLFile($tmp, 'image/jpeg', 'asli.jpg'),
    ]);
    @unlink($tmp);

    $json = json_decode($resp['body'], true);
    assertTrue(($json['success'] ?? false) === true, 'Upload JPEG asli harus sukses. Pesan: ' . ($json['message'] ?? ''));
    assertContains('berhasil ditambahkan', (string) ($json['message'] ?? ''), 'Pesan toast sukses harus ada.');

    $file = (string) ($json['data']['file'] ?? '');
    assertTrue($file !== '' && str_contains($file, '/uploads/galeri/'), 'Response harus memuat path file upload.');

    // Bersihkan file yang baru terupload agar tidak meninggalkan artefak
    $full = PROJECT_ROOT . '/public' . $file;
    if (is_file($full)) {
        @unlink($full);
    }
    assertFalse(is_file($full), 'File upload uji harus dibersihkan.');
});

finishTests();