<?php

declare(strict_types=1);

require_once __DIR__ . '/../../Models/SiteData.php';

final class DashboardController
{
    public function index(): void
    {
        // ── Auth guard ────────────────────────────────────────────────────────
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['admin'])) {
            $base = defined('APP_BASE') ? APP_BASE : '';
            header('Location: ' . $base . '/admin/login');
            exit;
        }

        // ── Data untuk stat cards ─────────────────────────────────────────────
        $datasets  = SiteData::all();
        $summaries = [];
        foreach ($datasets as $key => $records) {
            $summaries[$key] = count($records);
        }

        // ── Aktivitas terbaru (digabung dari data JSON aktual) ────────────────
        $recentActivity = self::buildRecentActivity($datasets);

        require __DIR__ . '/../../Views/admin/dashboard/index.php';
    }

    /**
     * Build recent-activity feed from the real JSON datasets:
     * newest updated_at/created_at first, capped at 6 rows.
     *
     * @param array<string, array<int, array<string, mixed>>> $datasets
     * @return array<int, array{icon: string, title: string, desc: string, time: string}>
     */
    private static function buildRecentActivity(array $datasets): array
    {
        $sources = [
            'berita'  => ['icon' => 'newspaper',           'title' => 'judul', 'entity' => 'Berita'],
            'umkm'    => ['icon' => 'storefront',          'title' => 'nama',  'entity' => 'UMKM'],
            'galeri'  => ['icon' => 'photo_library',       'title' => 'judul', 'entity' => 'Foto galeri'],
            'pesan'   => ['icon' => 'mail',                'title' => 'pesan', 'entity' => 'Pesan'],
        ];

        $items = [];
        foreach ($sources as $key => $cfg) {
            foreach (($datasets[$key] ?? []) as $rec) {
                $created = (string)($rec['created_at'] ?? '');
                $updated = (string)($rec['updated_at'] ?? '');
                $ts = $updated !== '' ? $updated : $created;
                if ($ts === '') {
                    continue;
                }

                $title = (string)($rec[$cfg['title']] ?? '');
                $title = mb_strlen($title) > 60 ? mb_substr($title, 0, 60) . '…' : $title;

                $desc = match ($key) {
                    'berita' => ($rec['status'] ?? '') === 'terbit'
                        ? 'Berita diterbitkan'
                        : 'Berita disimpan sebagai draft',
                    'pesan' => 'Pesan masuk dari ' . (string)($rec['nama'] ?? 'pengunjung'),
                    'umkm', 'galeri' => self::changedLabel($cfg['entity'], $created, $updated),
                    default => 'Data diperbarui',
                };

                $items[] = [
                    'icon'  => $cfg['icon'],
                    'title' => $title,
                    'desc'  => $desc,
                    'time'  => self::relativeTime($ts),
                    '_ts'   => $ts,
                ];
            }
        }

        usort($items, static fn(array $a, array $b): int => strcmp($b['_ts'], $a['_ts']));
        return array_map(
            static fn(array $it): array => [
                'icon'  => $it['icon'],
                'title' => $it['title'],
                'desc'  => $it['desc'],
                'time'  => $it['time'],
            ],
            array_slice($items, 0, 6)
        );
    }

    private static function changedLabel(string $label, string $created, string $updated): string
    {
        return ($updated !== '' && $updated !== $created) ? $label . ' diperbarui' : $label . ' ditambahkan';
    }

    private static function relativeTime(string $iso): string
    {
        $ts = strtotime($iso);
        if ($ts === false) {
            return $iso;
        }
        $diff = time() - $ts;
        if ($diff < 60) return 'Baru saja';
        if ($diff < 3600) return (int) floor($diff / 60) . ' menit lalu';
        if ($diff < 86400) return (int) floor($diff / 3600) . ' jam lalu';
        if ($diff < 604800) return (int) floor($diff / 86400) . ' hari lalu';
        return date('d M Y', $ts);
    }
}
