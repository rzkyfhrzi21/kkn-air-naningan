<?php

declare(strict_types=1);

class Potensi
{
    private static string $file      = __DIR__ . '/../../public/data/potensi.json';
    private static string $backupDir = __DIR__ . '/../../secure/backup/';

    public const KATEGORI = [
        'pertanian'   => ['label' => 'Pertanian', 'icon' => 'coffee'],
        'wisata'      => ['label' => 'Wisata', 'icon' => 'landscape'],
        'umkm'        => ['label' => 'UMKM', 'icon' => 'shopping_bag'],
        'peternakan'  => ['label' => 'Peternakan', 'icon' => 'pets'],
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
        return is_array($data) ? $data : [];
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
        $meta  = self::KATEGORI[$kat] ?? ['label' => $kat, 'icon' => 'category'];
        $nama  = trim((string) ($payload['nama'] ?? ''));
        
        $item = [
            'id'             => 'potensi-' . $ts,
            'nama'           => $nama,
            'kategori'       => $kat,
            'kategori_label' => $meta['label'],
            'kategori_icon'  => $meta['icon'],
            'deskripsi'      => trim((string) ($payload['deskripsi'] ?? '')),
            'foto'           => trim((string) ($payload['foto'] ?? '')),
            'kapasitas'      => trim((string) ($payload['kapasitas'] ?? '')),
            'status'         => in_array($payload['status'] ?? '', ['aktif', 'berkembang', 'potensial'], true) ? $payload['status'] : 'aktif',
            'created_at'     => date('c'),
            'updated_at'     => date('c'),
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
            if (isset($payload['nama'])) {
                $item['nama'] = trim((string) $payload['nama']);
            }
            if (isset($payload['kategori'])) {
                $kat = self::normalizeKat($payload['kategori']);
                $meta = self::KATEGORI[$kat] ?? ['label' => $kat, 'icon' => 'category'];
                $item['kategori'] = $kat;
                $item['kategori_label'] = $meta['label'];
                $item['kategori_icon'] = $meta['icon'];
            }
            if (isset($payload['deskripsi'])) {
                $item['deskripsi'] = trim((string) $payload['deskripsi']);
            }
            if (isset($payload['foto'])) {
                $item['foto'] = trim((string) $payload['foto']);
            }
            if (isset($payload['kapasitas'])) {
                $item['kapasitas'] = trim((string) $payload['kapasitas']);
            }
            if (isset($payload['status'])) {
                $item['status'] = in_array($payload['status'], ['aktif', 'berkembang', 'potensial'], true) ? $payload['status'] : $item['status'];
            }
            $item['updated_at'] = date('c');
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
        return array_key_exists($k, self::KATEGORI) ? $k : 'pertanian';
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
        copy(self::$file, self::$backupDir . 'potensi_' . date('Y-m-d_His') . '.json');
    }
}
