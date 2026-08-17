<?php

declare(strict_types=1);

/*
 * ======================================================
 * PENCATAT BUKU TAMU / BUKU CATATAN SATPAM (LOGGER)
 *
 * File ini ibarat buku tamu otomatis di pos satpam. Setiap kali ada
 * kejadian penting atau peristiwa keamanan (misalnya admin login,
 * gagal login, atau mengubah data), fungsi `writeLog()` akan
 * mencatat waktu, alamat IP pengunjung, nama akun, dan detil aktivitas.
 *
 * Catatan disimpan rapi dalam format JSON satu baris per kejadian
 * di dalam folder rahasia `/secure/logs/admin.log` (di luar folder web publik).
 * ======================================================
 */

/**
 * includes/logger.php — Security & activity logging (OWASP A09).
 *
 * Menulis satu baris JSON per kejadian ke /secure/logs/admin.log
 * (di luar folder publik, tidak bisa diakses lewat HTTP).
 */

if (!function_exists('writeLog')) {
    // Fungsi utama pencatat log aktivitas/keamanan
    function writeLog(string $event, string $detail = ''): void
    {
        // (1) Tentukan lokasi folder penyimpanan catatan log rahasia
        $logDir = __DIR__ . '/../secure/logs';

        // (2) Buat foldernya jika belum ada
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0755, true);
        }
        $file = $logDir . '/admin.log'; // Path file catatan log

        // (3) Susun data catatan dalam bentuk daftar (array)
        $entry = [
            'time'   => date('Y-m-d H:i:s'),                                       // Jam dan tanggal saat kejadian
            'ip'     => $_SERVER['REMOTE_ADDR'] ?? '',                             // Alamat IP komputer/HP pengakses
            'user'   => (string) ($_SESSION['admin_username'] ?? ($_POST['username'] ?? 'guest')), // Nama pengguna/admin
            'event'  => $event,                                                    // Nama kejadian (misal: LOGIN_SUCCESS)
            'detail' => $detail,                                                   // Keterangan rincian kejadian
        ];

        // (4) Buka file log untuk menambahkan baris baru di paling bawah ('a' = append)
        $fp = @fopen($file, 'a');
        if ($fp === false) {
            return; // Jika file tidak bisa dibuka, batal mencatat
        }

        // (5) Kunci file sementara (flock EX) agar tidak bentrok jika ada 2 aksi bersamaan
        flock($fp, LOCK_EX);
        fwrite($fp, json_encode($entry, JSON_UNESCAPED_UNICODE) . PHP_EOL); // Tulis data teks JSON
        flock($fp, LOCK_UN); // Buka kunci file
        fclose($fp);         // Tutup file kembali
    }
}

