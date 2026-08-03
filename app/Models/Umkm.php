<?php

declare(strict_types=1);

class Umkm
{
    private static string $file      = __DIR__ . '/../../public/data/umkm.json';
    private static string $backupDir = __DIR__ . '/../../secure/backup/';

    public const KATEGORI = [
        'kopi'    => 'Kopi & Agrikultur',
        'kuliner' => 'Kuliner Olahan',
        'kriya'   => 'Kriya & Kerajinan',
        'jasa'    => 'Jasa',
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
        $kat   = self::normalizeKategori($payload['kategori'] ?? '');
        $nama  = trim((string) ($payload['nama'] ?? ''));
        $item  = [
            'id'             => 'umkm-' . $ts,
            'nama'           => $nama,
            'slug'           => self::slugify($nama) . '-' . $ts,
            'usaha'          => trim((string) ($payload['usaha'] ?? '')),
            'kategori'       => $kat,
            'kategori_label' => self::KATEGORI[$kat] ?? $kat,
            'deskripsi'      => trim((string) ($payload['deskripsi'] ?? '')),
            'pemilik'        => trim((string) ($payload['pemilik'] ?? '')),
            'dusun'          => trim((string) ($payload['dusun'] ?? '')),
            'no_wa'          => self::normalizeWa((string) ($payload['no_wa'] ?? '')),
            'foto'           => trim((string) ($payload['foto'] ?? '')),
            'status'         => in_array($payload['status'] ?? '', ['aktif', 'nonaktif'], true)
                ? $payload['status'] : 'aktif',
            'is_featured'    => !empty($payload['is_featured']),
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
            if (isset($payload['usaha'])) {
                $item['usaha'] = trim((string) $payload['usaha']);
            }
            if (isset($payload['kategori'])) {
                $kat = self::normalizeKategori($payload['kategori']);
                $item['kategori'] = $kat;
                $item['kategori_label'] = self::KATEGORI[$kat] ?? $kat;
            }
            if (isset($payload['deskripsi'])) {
                $item['deskripsi'] = trim((string) $payload['deskripsi']);
            }
            if (isset($payload['pemilik'])) {
                $item['pemilik'] = trim((string) $payload['pemilik']);
            }
            if (isset($payload['dusun'])) {
                $item['dusun'] = trim((string) $payload['dusun']);
            }
            if (isset($payload['no_wa'])) {
                $item['no_wa'] = self::normalizeWa((string) $payload['no_wa']);
            }
            if (isset($payload['foto'])) {
                $item['foto'] = trim((string) $payload['foto']);
            }
            if (isset($payload['status'])) {
                $item['status'] = in_array($payload['status'], ['aktif', 'nonaktif'], true)
                    ? $payload['status'] : $item['status'];
            }
            if (array_key_exists('is_featured', $payload)) {
                $item['is_featured'] = !empty($payload['is_featured']);
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
        $new   = array_values(array_filter($items, static fn($i) => ($i['id'] ?? '') !== $id));
        if (count($new) === count($items)) {
            return false;
        }
        self::save($new);
        return true;
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
        copy(self::$file, self::$backupDir . 'umkm_' . date('Y-m-d_His') . '.json');
    }

    private static function normalizeKategori(string $k): string
    {
        $k = strtolower(trim($k));
        return array_key_exists($k, self::KATEGORI) ? $k : 'jasa';
    }

    private static function normalizeWa(string $wa): string
    {
        $wa = preg_replace('/\D+/', '', $wa) ?? '';
        if ($wa !== '' && str_starts_with($wa, '0')) {
            $wa = '62' . substr($wa, 1);
        }
        return $wa;
    }

    private static function slugify(string $text): string
    {
        $text = mb_strtolower($text, 'UTF-8');
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text) ?? '';
        $text = preg_replace('/[\s-]+/', '-', trim($text)) ?? '';
        return trim($text, '-');
    }
}
