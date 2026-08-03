<?php

declare(strict_types=1);

/**
 * Model Akun — baca/tulis secure/admin_credentials.json
 * API: get() / update()
 */
class Akun
{
    private static string $file      = __DIR__ . '/../../secure/admin_credentials.json';
    private static string $backupDir = __DIR__ . '/../../secure/backup/';

    public static function get(): array
    {
        if (!file_exists(self::$file)) {
            return [];
        }
        $fp = fopen(self::$file, 'r');
        if ($fp === false) {
            return [];
        }
        flock($fp, LOCK_SH);
        $data = json_decode(stream_get_contents($fp) ?: '{}', true) ?? [];
        flock($fp, LOCK_UN);
        fclose($fp);
        return is_array($data) ? $data : [];
    }

    public static function update(array $payload): array
    {
        $current = self::get();

        if (array_key_exists('username', $payload)) {
            $current['username'] = trim((string) $payload['username']);
        }
        if (array_key_exists('nama_lengkap', $payload)) {
            $current['nama_lengkap'] = trim((string) $payload['nama_lengkap']);
        }
        if (array_key_exists('whatsapp', $payload)) {
            $current['whatsapp'] = trim((string) $payload['whatsapp']);
        }
        if (array_key_exists('email', $payload)) {
            $current['email'] = trim((string) $payload['email']);
        }
        if (array_key_exists('foto', $payload)) {
            $current['foto'] = trim((string) $payload['foto']);
        }
        if (isset($payload['password_hash']) && $payload['password_hash'] !== '') {
            $current['password_hash'] = (string) $payload['password_hash'];
        }
        $current['updated_at'] = date('c');

        self::backup();
        $fp = fopen(self::$file, 'c');
        if ($fp === false) {
            throw new RuntimeException('Gagal membuka file kredensial untuk ditulis.');
        }
        flock($fp, LOCK_EX);
        ftruncate($fp, 0);
        fwrite($fp, json_encode($current, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        flock($fp, LOCK_UN);
        fclose($fp);

        return $current;
    }

    private static function backup(): void
    {
        if (!file_exists(self::$file)) {
            return;
        }
        if (!is_dir(self::$backupDir)) {
            mkdir(self::$backupDir, 0755, true);
        }
        copy(self::$file, self::$backupDir . 'admin_credentials_' . date('Y-m-d_His') . '.json');
    }
}
