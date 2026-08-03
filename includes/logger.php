<?php

declare(strict_types=1);

/**
 * logger.php — Security & activity logging (OWASP A09).
 *
 * Menulis satu baris JSON per kejadian ke /secure/logs/admin.log
 * (di luar folder publik, tidak bisa diakses lewat HTTP).
 * Diterapkan dari docs/BRIEF_STANDAR_INTERAKSI_DAN_KEAMANAN.md §11.A09.
 */

if (!function_exists('writeLog')) {
    function writeLog(string $event, string $detail = ''): void
    {
        $logDir = __DIR__ . '/../secure/logs';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0755, true);
        }
        $file = $logDir . '/admin.log';

        $entry = [
            'time'   => date('Y-m-d H:i:s'),
            'ip'     => $_SERVER['REMOTE_ADDR'] ?? '',
            'user'   => (string) ($_SESSION['admin_username'] ?? ($_POST['username'] ?? 'guest')),
            'event'  => $event,
            'detail' => $detail,
        ];

        $fp = @fopen($file, 'a');
        if ($fp === false) {
            return;
        }
        flock($fp, LOCK_EX);
        fwrite($fp, json_encode($entry, JSON_UNESCAPED_UNICODE) . PHP_EOL);
        flock($fp, LOCK_UN);
        fclose($fp);
    }
}
