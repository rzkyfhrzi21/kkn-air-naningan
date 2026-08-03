<?php

declare(strict_types=1);

class Galeri
{
    private static string $file      = __DIR__ . '/../../public/data/galeri.json';
    private static string $backupDir = __DIR__ . '/../../secure/backup/';

    public const KATEGORI = [
        'alam'         => 'Alam & Wisata',
        'kegiatan'     => 'Kegiatan Desa',
        'budaya'       => 'Budaya',
        'pembangunan'  => 'Pembangunan',
    ];

    public static function all(): array
    {
        if (!file_exists(self::$file)) {
            return [];
        }
        $fp = fopen(self::$file, 'r');
        if ($fp === false) {
            return [];
        }
        flock($fp, LOCK_SH);
        $data = json_decode(stream_get_contents($fp) ?: '[]', true) ?? [];
        flock($fp, LOCK_UN);
        fclose($fp);
        if (!is_array($data)) {
            return [];
        }
        usort($data, static fn($a, $b) => ((int) ($a['urutan'] ?? 0)) <=> ((int) ($b['urutan'] ?? 0)));
        return $data;
    }

    public static function find(string $id): ?array
    {
        foreach (self::all() as $item) {
            if (($item['id'] ?? '') === $id) {
                return $item;
            }
        }
        return null;
    }

    public static function create(array $payload): array
    {
        $items = self::all();
        $ts    = time();
        $kat   = self::normalizeKat($payload['kategori'] ?? '');
        $item  = [
            'id'             => 'galeri-' . $ts,
            'judul'          => trim((string) ($payload['judul'] ?? '')),
            'deskripsi'      => trim((string) ($payload['deskripsi'] ?? '')),
            'kategori'       => $kat,
            'kategori_label' => self::KATEGORI[$kat] ?? $kat,
            'tipe'           => in_array($payload['tipe'] ?? '', ['foto', 'video'], true) ? $payload['tipe'] : 'foto',
            'file'           => trim((string) ($payload['file'] ?? '')),
            'rasio'          => trim((string) ($payload['rasio'] ?? '100%')),
            'urutan'         => (int) ($payload['urutan'] ?? (count($items) + 1)),
            'created_at'     => date('c'),
        ];
        $items[] = $item;
        self::save($items);
        return $item;
    }

    public static function update(string $id, array $payload): ?array
    {
        $items = self::all();
        foreach ($items as &$item) {
            if (($item['id'] ?? '') !== $id) {
                continue;
            }
            if (isset($payload['judul'])) {
                $item['judul'] = trim((string) $payload['judul']);
            }
            if (isset($payload['deskripsi'])) {
                $item['deskripsi'] = trim((string) $payload['deskripsi']);
            }
            if (isset($payload['kategori'])) {
                $kat = self::normalizeKat($payload['kategori']);
                $item['kategori'] = $kat;
                $item['kategori_label'] = self::KATEGORI[$kat] ?? $kat;
            }
            if (isset($payload['tipe'])) {
                $item['tipe'] = in_array($payload['tipe'], ['foto', 'video'], true) ? $payload['tipe'] : $item['tipe'];
            }
            if (isset($payload['file'])) {
                $item['file'] = trim((string) $payload['file']);
            }
            if (isset($payload['rasio'])) {
                $item['rasio'] = trim((string) $payload['rasio']);
            }
            if (isset($payload['urutan'])) {
                $item['urutan'] = (int) $payload['urutan'];
            }
            self::save($items);
            return $item;
        }
        unset($item);
        return null;
    }

    public static function delete(string $id): bool
    {
        $items = self::all();
        $new = array_values(array_filter($items, static fn($i) => ($i['id'] ?? '') !== $id));
        if (count($new) === count($items)) {
            return false;
        }
        self::save($new);
        return true;
    }

    private static function normalizeKat(string $k): string
    {
        $k = strtolower(trim($k));
        return array_key_exists($k, self::KATEGORI) ? $k : 'kegiatan';
    }

    private static function save(array $data): void
    {
        self::backup();
        $dir = dirname(self::$file);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $fp = fopen(self::$file, 'c');
        flock($fp, LOCK_EX);
        ftruncate($fp, 0);
        fwrite($fp, json_encode(array_values($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        flock($fp, LOCK_UN);
        fclose($fp);
    }

    private static function backup(): void
    {
        if (!file_exists(self::$file)) {
            return;
        }
        if (!is_dir(self::$backupDir)) {
            mkdir(self::$backupDir, 0755, true);
        }
        copy(self::$file, self::$backupDir . 'galeri_' . date('Y-m-d_His') . '.json');
    }
}
