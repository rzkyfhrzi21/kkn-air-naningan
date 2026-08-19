<?php

declare(strict_types=1);

/* ======================================================
   BOOTSTRAP PENGUJIAN OTOMATIS (TESTS/)

   File ini ibarat "dapur umum" untuk semua berkas test:
   menyediakan alat bantu (helper) yang sama supaya tiap
   test tidak menulis ulang hal yang sama.

   Yang disediakan:
   1. Konstanta lokasi (root proyek, URL server uji).
   2. Alat menjalankan server uji lokal `php -S` bila belum
      berjalan, dan mematikannya setelah tes selesai.
   3. Klien HTTP ringan berbasis cURL (POST/GET + cookie jar).
   4. Sesi admin palsu untuk menguji endpoint AJAX.
   5. Pelindung data: snapshot file JSON production sebelum
      tes yang menulis, restore otomatis sesudahnya (dijamin
      via register_shutdown_function) — tes TIDAK boleh
      merusak data asli.
   6. Fungsi asersi (PASS/FAIL) dengan format seragam.

   Setiap berkas *Test.php me-require file ini di baris
   pertama, memanggil runTest() untuk tiap kasus uji, lalu
   keluar dengan kode 0 (semua lolos) atau 1 (ada yang gagal).
====================================================== */

error_reporting(E_ALL);
ini_set('display_errors', '1');

const TESTS_ROOT    = __DIR__;
define('PROJECT_ROOT', dirname(__DIR__));
const TEST_BASE_URL = 'http://127.0.0.1:8899';
const TEST_PORT     = 8899;
const TEST_CSRF     = 'test-csrf-token';

/** Gagal dianggap terjadi bila ada exception tipe ini. */
final class TestFailureException extends RuntimeException
{
}

/* ── Penghitung hasil per berkas test ─────────────────────────────── */
$GLOBALS['testPassed'] = 0;
$GLOBALS['testFailed'] = 0;
$GLOBALS['testSkipped'] = 0;

/**
 * Jalankan satu kasus uji: cetak [PASS]/[FAIL] dan lanjutkan.
 */
function runTest(string $name, callable $fn): void
{
    try {
        $fn();
        $GLOBALS['testPassed']++;
        echo "[PASS] {$name}\n";
    } catch (TestFailureException $e) {
        $GLOBALS['testFailed']++;
        echo "[FAIL] {$name}\n      -> {$e->getMessage()}\n";
    } catch (Throwable $e) {
        $GLOBALS['testFailed']++;
        echo "[FAIL] {$name}\n      -> Kesalahan teknis: {$e->getMessage()} @ {$e->getFile()}:{$e->getLine()}\n";
    }
}

/**
 * Tandai tes dilewati (tidak dihitung gagal).
 */
function markSkipped(string $reason): void
{
    $GLOBALS['testSkipped']++;
    echo "[SKIP] {$reason}\n";
}

/**
 * Tutup berkas test: kode keluar 0 bila semua lolos, 1 bila ada gagal.
 */
function finishTests(): void
{
    exit($GLOBALS['testFailed'] > 0 ? 1 : 0);
}

/* ── Asersi ─────────────────────────────────────────────────────────── */

function assertTrue(bool $cond, string $msg = 'Kondisi diharapkan true.'): void
{
    if (!$cond) {
        throw new TestFailureException($msg);
    }
}

function assertFalse(bool $cond, string $msg = 'Kondisi diharapkan false.'): void
{
    if ($cond) {
        throw new TestFailureException($msg);
    }
}

function assertSame(mixed $expected, mixed $actual, string $msg = ''): void
{
    if ($expected !== $actual) {
        throw new TestFailureException(
            ($msg !== '' ? $msg . ' — ' : '') . 'Diharapkan ' . var_export($expected, true) . ', dapat ' . var_export($actual, true)
        );
    }
}

function assertContains(string $needle, string $haystack, string $msg = ''): void
{
    if ($haystack === '' || !str_contains($haystack, $needle)) {
        throw new TestFailureException(
            ($msg !== '' ? $msg . ' — ' : '') . "Teks tidak mengandung: {$needle}"
        );
    }
}

function assertNotContains(string $needle, string $haystack, string $msg = ''): void
{
    if (str_contains($haystack, $needle)) {
        throw new TestFailureException(
            ($msg !== '' ? $msg . ' — ' : '') . "Teks tidak seharusnya mengandung: {$needle}"
        );
    }
}

function assertMatches(string $pattern, string $subject, string $msg = ''): void
{
    if (preg_match($pattern, $subject) !== 1) {
        throw new TestFailureException(
            ($msg !== '' ? $msg . ' — ' : '') . "Pola tidak cocok: {$pattern}"
        );
    }
}

/**
 * Cek status HTTP dari hasil httpRequest().
 */
function assertStatus(int $expected, array $resp, string $msg = ''): void
{
    $actual = $resp['status'] ?? -1;
    if ($actual !== $expected) {
        throw new TestFailureException(
            ($msg !== '' ? $msg . ' — ' : '') . "Status HTTP diharapkan {$expected}, dapat {$actual}. Body: "
            . mb_substr((string) ($resp['body'] ?? ''), 0, 200)
        );
    }
}

/* ── Server uji lokal (php -S) ───────────────────────────────────────── */

/**
 * Pastikan server uji berjalan di TEST_PORT dengan docroot yang BENAR
 * (folder public/, supaya URL identik dengan production).
 *
 * Bila belum berjalan: nyalakan `php -S` dengan docroot public/,
 * router khusus tes, dan batas upload dinaikkan (server uji hanya —
 * php.ini production yang dipakai web server asli tidak disentuh).
 * Bila sudah ada server lama di port itu tapi docroot-nya salah
 * (mis. dinyalakan manual dari root proyek), server lama dimatikan
 * dan diganti.
 */
function ensureServer(): void
{
    if (@fsockopen('127.0.0.1', TEST_PORT, $errno, $errstr, 0.5) !== false) {
        // Port terbuka: pastikan docroot benar (endpoint AJAX harus 401,
        // bukan 404). Kalau salah, matikan dan nyalakan ulang.
        $probe = httpRequest('GET', '/admin/ajax/list-galeri.php');
        if (($probe['status'] ?? 0) === 401) {
            return; // Server dengan docroot benar sudah jalan
        }
        stopTestServer();
        usleep(500_000);
    }
    $router = __DIR__ . '/support/router.php';
    $cmd = 'start /b php -S 127.0.0.1:' . TEST_PORT
        . ' -t ' . escapeshellarg(PROJECT_ROOT . '/public')
        . ' -d upload_max_filesize=25M -d post_max_size=25M'
        . ' ' . escapeshellarg($router)
        . ' > NUL 2>&1';
    $h = popen($cmd, 'r');
    if ($h !== false) {
        pclose($h);
    }
    for ($i = 0; $i < 50; $i++) {
        usleep(100_000);
        if (@fsockopen('127.0.0.1', TEST_PORT, $errno, $errstr, 0.5) !== false) {
            register_shutdown_function('stopTestServer');
            return;
        }
    }
    throw new TestFailureException('Server uji tidak bisa dijalankan di port ' . TEST_PORT . '. Pastikan PHP tersedia di PATH.');
}

/**
 * Matikan server uji yang tadi dinyalakan oleh tes ini
 * (hanya bila memang tes yang menyalakannya).
 */
function stopTestServer(): void
{
    $out = (string) shell_exec('netstat -ano | findstr :' . TEST_PORT . ' | findstr LISTENING');
    if ($out === '') {
        return;
    }
    preg_match_all('/\s(\d+)\s*$/', $out, $m);
    foreach (array_unique($m[1] ?? []) as $pid) {
        @shell_exec('taskkill /F /PID ' . (int) $pid . ' 2>NUL');
    }
}

/* ── Klien HTTP ───────────────────────────────────────────────────────── */

/**
 * Kirim permintaan HTTP ke server uji.
 *
 * @param array<string,mixed> $fields   data POST (form-urlencoded atau multipart bila $files diisi)
 * @param array<string,mixed> $files    peta nama => CURLFile untuk upload
 * @return array{status:int,headers:string,body:string}
 */
function httpRequest(
    string $method,
    string $path,
    array $fields = [],
    ?string $cookieFile = null,
    array $headers = [],
    array $files = []
): array {
    if (!function_exists('curl_init')) {
        throw new TestFailureException('Ekstensi cURL tidak tersedia di PHP ini.');
    }
    $ch = curl_init(TEST_BASE_URL . $path);
    $opts = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER         => true,
        CURLOPT_TIMEOUT        => 20,
        CURLOPT_FOLLOWLOCATION => false,
    ];
    if ($cookieFile !== null) {
        $opts[CURLOPT_COOKIEJAR]  = $cookieFile;
        $opts[CURLOPT_COOKIEFILE] = $cookieFile;
    }
    if (strtoupper($method) === 'POST') {
        $opts[CURLOPT_POST] = true;
        if ($files !== []) {
            // cURL otomatis multipart bila POSTFIELDS berisi CURLFile
            $opts[CURLOPT_POSTFIELDS] = array_merge($fields, $files);
        } elseif ($fields !== []) {
            $opts[CURLOPT_POSTFIELDS] = http_build_query($fields);
        }
    } elseif (strtoupper($method) !== 'GET') {
        $opts[CURLOPT_CUSTOMREQUEST] = strtoupper($method);
    }
    if ($headers !== []) {
        $opts[CURLOPT_HTTPHEADER] = $headers;
    }
    curl_setopt_array($ch, $opts);

    $raw    = (string) curl_exec($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    $err    = curl_error($ch);
    curl_close($ch);

    if ($raw === '' && $err !== '') {
        throw new TestFailureException('Permintaan HTTP gagal: ' . $err);
    }
    $headerSize = strpos($raw, "\r\n\r\n");
    $head       = $headerSize !== false ? substr($raw, 0, $headerSize) : '';
    $body       = $headerSize !== false ? substr($raw, $headerSize + 4) : $raw;
    return ['status' => $status, 'headers' => $head, 'body' => $body];
}

/* ── Sesi admin untuk tes ─────────────────────────────────────────────── */

/**
 * Ambil cookie jar sesi admin palsu lewat tests/support/set-session.php.
 * Semua request AJAX selanjutnya tinggal menyertakan cookie ini.
 */
function adminSessionCookie(): string
{
    $jar = tempnam(sys_get_temp_dir(), 'adm') ?: (sys_get_temp_dir() . '/adm-cookie.txt');
    $resp = httpRequest('GET', '/tests/support/set-session.php', [], $jar);
    if (($resp['status'] ?? 0) !== 200) {
        throw new TestFailureException('Gagal membuat sesi admin uji (status ' . ($resp['status'] ?? '?') . ').');
    }
    return $jar;
}

/* ── Pelindung data production ─────────────────────────────────────────── */

/**
 * Snapshot seluruh file data yang bisa disentuh tes (JSON + rate limit).
 * Data asli dikembalikan otomatis saat tes selesai (dijamin oleh
 * register_shutdown_function meski tes fatal error di tengah jalan).
 *
 * @return array<string,string|null> peta path => path backup
 */
function protectDataFiles(): array
{
    $paths = [
        PROJECT_ROOT . '/public/data/galeri.json',
        PROJECT_ROOT . '/public/data/pesan.json',
        PROJECT_ROOT . '/secure/login_attempts.json',
    ];
    $backups = [];
    foreach ($paths as $path) {
        $backups[$path] = null;
        if (is_file($path)) {
            $tmp = tempnam(sys_get_temp_dir(), 'bak') ?: '';
            if ($tmp !== '' && copy($path, $tmp)) {
                $backups[$path] = $tmp;
            }
        }
    }
    register_shutdown_function(static function () use ($backups): void {
        restoreDataFiles($backups);
    });
    return $backups;
}

/**
 * Kembalikan file data dari snapshot.
 */
function restoreDataFiles(array $backups): void
{
    foreach ($backups as $path => $tmp) {
        if ($tmp !== null && is_file($tmp)) {
            @copy($tmp, $path);
            @unlink($tmp);
        }
    }
}

/* ── Pembantu kecil lain ───────────────────────────────────────────────── */

/**
 * Baca seluruh isi file dengan aman (false bila tidak ada).
 */
function readFileSafe(string $path): string|false
{
    return is_file($path) ? file_get_contents($path) : false;
}

/**
 * Ambil token CSRF dari halaman login admin (untuk tes rate limit login).
 */
function fetchLoginCsrf(string $cookieFile): string
{
    $resp = httpRequest('GET', '/admin/login', [], $cookieFile);
    if (($resp['status'] ?? 0) !== 200) {
        throw new TestFailureException('Halaman login tidak terbuka (status ' . ($resp['status'] ?? '?') . ').');
    }
    if (!preg_match('/name="csrf_token"\s+value="([^"]+)"/', $resp['body'], $m)) {
        throw new TestFailureException('Token CSRF tidak ditemukan di form login.');
    }
    return $m[1];
}