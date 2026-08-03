<?php

declare(strict_types=1);

class Wisata
{
    private static string $file      = __DIR__ . '/../../public/data/wisata.json';
    private static string $backupDir = __DIR__ . '/../../secure/backup/';

    public const KATEGORI = [
        'air-terjun'    => ['label' => 'Air Terjun', 'icon' => 'water_drop'],
        'titik-pandang' => ['label' => 'Titik Pandang', 'icon' => 'landscape'],
        'wisata-alam'   => ['label' => 'Wisata Alam', 'icon' => 'park'],
        'agrowisata'    => ['label' => 'Agrowisata', 'icon' => 'agriculture'],
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
        $meta  = self::KATEGORI[$kat] ?? ['label' => $kat, 'icon' => 'landscape'];
        $nama  = trim((string) ($payload['nama'] ?? ''));
        $fasilitas = self::parseFasilitas($payload['fasilitas'] ?? []);
        $item = [
            'id'             => 'wisata-' . $ts,
            'nama'           => $nama,
            'slug'           => self::slugify($nama) . '-' . $ts,
            'kategori'       => $kat,
            'kategori_label' => $meta['label'],
            'kategori_icon'  => $meta['icon'],
            'deskripsi'      => trim((string) ($payload['deskripsi'] ?? '')),
            'jarak'          => trim((string) ($payload['jarak'] ?? '')),
            'foto'           => trim((string) ($payload['foto'] ?? '')),
            'fasilitas'      => $fasilitas,
            'maps_url'       => trim((string) ($payload['maps_url'] ?? 'https://maps.google.com')),
            'offset'         => !empty($payload['offset']),
            'status'         => in_array($payload['status'] ?? '', ['buka', 'tutup'], true) ? $payload['status'] : 'buka',
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
                $meta = self::KATEGORI[$kat] ?? ['label' => $kat, 'icon' => 'landscape'];
                $item['kategori'] = $kat;
                $item['kategori_label'] = $meta['label'];
                $item['kategori_icon'] = $meta['icon'];
            }
            if (isset($payload['deskripsi'])) {
                $item['deskripsi'] = trim((string) $payload['deskripsi']);
            }
            if (isset($payload['jarak'])) {
                $item['jarak'] = trim((string) $payload['jarak']);
            }
            if (isset($payload['foto'])) {
                $item['foto'] = trim((string) $payload['foto']);
            }
            if (isset($payload['fasilitas'])) {
                $item['fasilitas'] = self::parseFasilitas($payload['fasilitas']);
            }
            if (isset($payload['maps_url'])) {
                $item['maps_url'] = trim((string) $payload['maps_url']);
            }
            if (array_key_exists('offset', $payload)) {
                $item['offset'] = !empty($payload['offset']);
            }
            if (isset($payload['status'])) {
                $item['status'] = in_array($payload['status'], ['buka', 'tutup'], true) ? $payload['status'] : $item['status'];
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

    private static function parseFasilitas($raw): array
    {
        if (is_string($raw)) {
            $parts = array_filter(array_map('trim', explode(',', $raw)));
            return array_map(static fn($l) => ['icon' => 'check', 'label' => $l], $parts);
        }
        if (!is_array($raw)) {
            return [];
        }
        $out = [];
        foreach ($raw as $row) {
            if (is_string($row)) {
                $out[] = ['icon' => 'check', 'label' => trim($row)];
            } elseif (is_array($row) && trim((string) ($row['label'] ?? '')) !== '') {
                $out[] = [
                    'icon'  => trim((string) ($row['icon'] ?? 'check')),
                    'label' => trim((string) $row['label']),
                ];
            }
        }
        return $out;
    }

    private static function normalizeKat(string $k): string
    {
        $k = strtolower(trim($k));
        return array_key_exists($k, self::KATEGORI) ? $k : 'wisata-alam';
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
        copy(self::$file, self::$backupDir . 'wisata_' . date('Y-m-d_His') . '.json');
    }

    private static function slugify(string $text): string
    {
        $text = mb_strtolower($text, 'UTF-8');
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text) ?? '';
        $text = preg_replace('/[\s-]+/', '-', trim($text)) ?? '';
        return trim($text, '-');
    }
}
