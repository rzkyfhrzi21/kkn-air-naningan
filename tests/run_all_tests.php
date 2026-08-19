<?php

declare(strict_types=1);

/* ======================================================
   MASTER TEST RUNNER (CLI) — tes/run_all_tests.php

   Menjalankan SELURUH suite pengujian otomatis:
   - Mencari semua berkas *Test.php di subfolder tests/
   - Menjalankan tiap berkas sebagai sub-proses php
   - Mencetak hasil berwarna: HIJAU = PASS, MERAH = FAIL
   - Ringkasan akhir + kode keluar 1 bila ada yang gagal

   Cara pakai (CMD / PowerShell):
       php tests/run_all_tests.php

   Menjalankan satu berkas tertentu:
       php tests/Feature/GaleriAjaxTest.php
====================================================== */

$startTime = microtime(true);

echo "\n";
echo "==================================================\n";
echo " MEMULAI EKSEKUSI PENGUJIAN OTOMATIS (PHP NATIVE)\n";
echo "==================================================\n\n";

$testFiles = glob(__DIR__ . '/*/*Test.php') ?: [];
if ($testFiles === []) {
    echo "Tidak ada berkas *Test.php ditemukan.\n";
    exit(1);
}

$passed = 0;
$failed = 0;
$skipped = 0;

foreach ($testFiles as $file) {
    $testName = basename($file);
    echo "Testing [{$testName}] ...\n";

    $output     = [];
    $returnCode = 0;
    exec('php ' . escapeshellarg($file) . ' 2>&1', $output, $returnCode);

    // Cetak baris [PASS]/[FAIL]/[SKIP] dari sub-proses dengan indentasi
    foreach ($output as $line) {
        echo '  ' . $line . "\n";
    }

    if ($returnCode === 0) {
        echo "\033[32m[PASS]\033[0m {$testName}\n\n";
        $passed++;
    } else {
        echo "\033[31m[FAIL]\033[0m {$testName}\n\n";
        $failed++;
    }
}

$elapsed = round(microtime(true) - $startTime, 3);

echo "--------------------------------------------------\n";
echo " HASIL AKHIR: {$passed} Passed, {$failed} Failed ({$elapsed}s)\n";
echo "--------------------------------------------------\n";

exit($failed > 0 ? 1 : 0);