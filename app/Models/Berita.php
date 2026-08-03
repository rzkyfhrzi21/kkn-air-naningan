<?php

declare(strict_types=1);

/**
 * Model Berita — baca/tulis public/data/berita.json
 * API: all() / find() / findBySlug() / create() / update() / delete()
 */
class Berita
{
    private static string $file      = __DIR__ . '/../../public/data/berita.json';
    private static string $backupDir = __DIR__ . '/../../secure/backup/';

    // ── Read ─────────────────────────────────────────────────────────────────

    public static function all(): array
    {
        if (!file_exists(self::$file)) return [];
        $fp   = fopen(self::$file, 'r');
        flock($fp, LOCK_SH);
        $data = json_decode(file_get_contents(self::$file), true) ?? [];
        flock($fp, LOCK_UN);
        fclose($fp);
        // Urutkan: terbit dulu, lalu by tanggal_terbit DESC
        usort($data, static function (array $a, array $b): int {
            $statusCmp = strcmp($a['status'] ?? '', $b['status'] ?? '');
            if ($statusCmp !== 0) {
                return ($a['status'] ?? '') === 'terbit' ? -1 : 1;
            }
            return strcmp($b['tanggal_terbit'] ?? '', $a['tanggal_terbit'] ?? '');
        });
        return $data;
    }

    /** Ambil hanya yang status=terbit */
    public static function published(): array
    {
        return array_values(
            array_filter(self::all(), static fn(array $b): bool => ($b['status'] ?? '') === 'terbit')
        );
    }

    public static function find(string $id): ?array
    {
        foreach (self::all() as $item) {
            if (($item['id'] ?? '') === $id) return $item;
        }
        return null;
    }

    public static function findBySlug(string $slug): ?array
    {
        foreach (self::all() as $item) {
            if (($item['slug'] ?? '') === $slug) return $item;
        }
        return null;
    }

    /** Berita terbit terbaru, limit n item */
    public static function latest(int $limit = 5): array
    {
        return array_slice(self::published(), 0, $limit);
    }

    // ── Write ─────────────────────────────────────────────────────────────────

    public static function create(array $payload): array
    {
        $items  = self::all();
        $ts     = time();
        $suffix = bin2hex(random_bytes(4));
        $slugBase = self::slugify($payload['judul'] ?? '');
        $slug   = ($slugBase !== '' ? $slugBase : 'berita') . '-' . $ts . '-' . $suffix;
        $item   = [
            'id'             => 'berita-' . $ts . '-' . $suffix,
            'judul'          => trim($payload['judul'] ?? ''),
            'slug'           => $slug,
            'kategori'       => trim($payload['kategori'] ?? ''),
            'ringkasan'      => trim($payload['ringkasan'] ?? ''),
            'konten'         => self::sanitizeHtml($payload['konten'] ?? ''),
            'foto_sampul'    => trim($payload['foto_sampul'] ?? ''),
            'penulis'        => trim($payload['penulis'] ?? 'Admin Desa'),
            'status'         => in_array($payload['status'] ?? '', ['draft', 'terbit'], true)
                                    ? $payload['status'] : 'draft',
            'tanggal_terbit' => trim($payload['tanggal_terbit'] ?? date('Y-m-d')),
            'tags'           => array_filter(array_map('trim', (array)($payload['tags'] ?? []))),
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
            if (($item['id'] ?? '') !== $id) continue;
            if (isset($payload['judul'])) {
                $newTitle = trim($payload['judul']);
                if ($newTitle !== ($item['judul'] ?? '')) {
                    $item['slug'] = self::slugify($newTitle) . '-' . substr(hash('sha256', $id), 0, 8);
                }
                $item['judul'] = $newTitle;
            }
            if (isset($payload['kategori']))       $item['kategori']       = trim($payload['kategori']);
            if (isset($payload['ringkasan']))      $item['ringkasan']      = trim($payload['ringkasan']);
            if (isset($payload['konten']))         $item['konten']         = self::sanitizeHtml($payload['konten']);
            if (isset($payload['foto_sampul']))    $item['foto_sampul']    = trim($payload['foto_sampul']);
            if (isset($payload['penulis']))        $item['penulis']        = trim($payload['penulis']);
            if (isset($payload['status']))         $item['status']         = in_array($payload['status'], ['draft','terbit'], true) ? $payload['status'] : $item['status'];
            if (isset($payload['tanggal_terbit'])) $item['tanggal_terbit'] = trim($payload['tanggal_terbit']);
            if (isset($payload['tags']))           $item['tags']           = array_filter(array_map('trim', (array)$payload['tags']));
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
        $new   = array_values(array_filter($items, static fn($i): bool => ($i['id'] ?? '') !== $id));
        if (count($new) === count($items)) return false;
        self::save($new);
        return true;
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private static function save(array $data): void
    {
        self::backup();
        if (!is_dir(dirname(self::$file))) {
            mkdir(dirname(self::$file), 0755, true);
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
        if (!file_exists(self::$file)) return;
        if (!is_dir(self::$backupDir)) mkdir(self::$backupDir, 0755, true);
        copy(self::$file, self::$backupDir . 'berita_' . date('Y-m-d_His') . '.json');
    }

    private static function slugify(string $text): string
    {
        $text = mb_strtolower($text, 'UTF-8');
        $map  = ['à'=>'a','á'=>'a','â'=>'a','ã'=>'a','ä'=>'a','å'=>'a',
                  'è'=>'e','é'=>'e','ê'=>'e','ë'=>'e','ì'=>'i','í'=>'i',
                  'î'=>'i','ï'=>'i','ò'=>'o','ó'=>'o','ô'=>'o','õ'=>'o',
                  'ö'=>'o','ù'=>'u','ú'=>'u','û'=>'u','ü'=>'u','ý'=>'y',
                  'ñ'=>'n','ç'=>'c'];
        $text = strtr($text, $map);
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
        $text = preg_replace('/[\s-]+/', '-', trim($text));
        return trim($text, '-');
    }

    /** Strip tags; izinkan subset HTML aman untuk konten artikel */
    private static function sanitizeHtml(string $html): string
    {
        $allowed = '<p><br><b><strong><i><em><u><h2><h3><h4><ul><ol><li><a><blockquote><img><figure><figcaption>';
        return strip_tags($html, $allowed);
    }
}
