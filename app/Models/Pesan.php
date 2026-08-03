<?php

declare(strict_types=1);

class Pesan
{
    private static string $file      = __DIR__ . '/../../public/data/pesan.json';
    private static string $backupDir = __DIR__ . '/../../secure/backup/';

    public const KATEGORI = [
        'info'      => 'Permintaan Informasi',
        'layanan'   => 'Layanan Administrasi',
        'pengaduan' => 'Pengaduan Masyarakat',
        'saran'     => 'Kritik & Saran',
        'lainnya'   => 'Lainnya',
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
        usort($data, static fn($a, $b) => strcmp((string) ($b['created_at'] ?? ''), (string) ($a['created_at'] ?? '')));
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
        $kat   = trim((string) ($payload['kategori'] ?? 'lainnya'));
        if (!array_key_exists($kat, self::KATEGORI)) {
            $kat = 'lainnya';
        }
        $item = [
            'id'         => 'pesan-' . $ts,
            'nama'       => trim((string) ($payload['nama'] ?? '')),
            'kontak'     => trim((string) ($payload['kontak'] ?? '')),
            'kategori'   => $kat,
            'kategori_label' => self::KATEGORI[$kat],
            'pesan'      => trim((string) ($payload['pesan'] ?? '')),
            'is_read'    => false,
            'created_at' => date('c'),
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
            if (array_key_exists('is_read', $payload)) {
                $item['is_read'] = (bool) $payload['is_read'];
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
        copy(self::$file, self::$backupDir . 'pesan_' . date('Y-m-d_His') . '.json');
    }
}
