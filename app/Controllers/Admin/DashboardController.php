<?php

declare(strict_types=1);

/* ======================================================
   CONTROLLER DASHBOARD ADMIN (BERANDA PANEL ADMIN)

   Tugas utama: menyiapkan "papan ringkasan" yang dilihat
   admin begitu masuk panel. Ibarat meja informasi di kantor
   desa: sebelum pengunjung bertanya, petugas sudah menyusun
   ringkasan singkat — berapa berita, berapa UMKM, berapa
   foto galeri, berapa pesan masuk — plus daftar kegiatan
   terbaru di situs.

   Penting (aturan MVC): controller TIDAK membaca file JSON
   secara langsung. Semua data diambil lewat Model SiteData
   (app/Models/SiteData.php) yang membaca seluruh file JSON
   di folder public/data/. Controller hanya menghitung,
   merapikan, lalu mengantar hasilnya ke View.

   Halaman yang dilayani:
   - View : app/Views/admin/dashboard/index.php
====================================================== */

require_once __DIR__ . '/../../Models/SiteData.php';

final class DashboardController
{
    public function index(): void
    {
        // (1) JAGA PINTU (Auth guard): kalau belum ada tanda pengenal
        //     admin di sesi, arahkan ke halaman login. Ibarat satpam
        //     menahan orang tanpa kartu identitas di depan ruang dashboard.
        // ── Auth guard ────────────────────────────────────────────────────────
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['admin'])) {
            $base = defined('APP_BASE') ? APP_BASE : '';
            header('Location: ' . $base . '/admin/login');
            exit;
        }

        // (2) Ambil SEMUA data dari file JSON lewat Model SiteData::all().
        //     Hasilnya berupa keranjang besar berisi semua entitas:
        //     berita, umkm, galeri, pesan, dan lainnya.
        // ── Data untuk stat cards ─────────────────────────────────────────────
        $datasets  = SiteData::all();
        $summaries = [];
        // (3) Hitung jumlah baris tiap entitas (fungsi count) — hasilnya
        //     dipakai untuk kartu angka di dashboard ("Berita: 5", "UMKM: 12").
        foreach ($datasets as $key => $records) {
            $summaries[$key] = count($records);
        }

        // (4) Rangkai daftar "aktivitas terbaru" dari data JSON yang nyata
        //     (bukan contoh karangan) — detailnya di fungsi buildRecentActivity().
        // ── Aktivitas terbaru (digabung dari data JSON aktual) ────────────────
        $recentActivity = self::buildRecentActivity($datasets);

        // (5) Antar data yang sudah siap ($summaries & $recentActivity)
        //     ke View dashboard untuk ditampilkan ke admin.
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
        // Membangun daftar "kegiatan terbaru" dari data JSON yang ada:
        // (1) Tentukan sumber datanya: berita, umkm, galeri, dan pesan.
        //     Tiap sumber diberi: ikon (nama ikon Material), kolom judul,
        //     dan sebutan entitas untuk kalimat di tampilan.
        $sources = [
            'berita'  => ['icon' => 'newspaper',           'title' => 'judul', 'entity' => 'Berita'],
            'umkm'    => ['icon' => 'storefront',          'title' => 'nama',  'entity' => 'UMKM'],
            'galeri'  => ['icon' => 'photo_library',       'title' => 'judul', 'entity' => 'Foto galeri'],
            'pesan'   => ['icon' => 'mail',                'title' => 'pesan', 'entity' => 'Pesan'],
        ];

        // (2) Kumpulkan semua entitas ke satu keranjang $items:
        //     untuk tiap data, ambil waktu terakhir diubah (updated_at) atau
        //     dibuat (created_at) — mana yang terisi. Kalau keduanya kosong,
        //     lewati data itu (tidak mungkin jadi "aktivitas terbaru").
        $items = [];
        foreach ($sources as $key => $cfg) {
            foreach (($datasets[$key] ?? []) as $rec) {
                $created = (string)($rec['created_at'] ?? '');
                $updated = (string)($rec['updated_at'] ?? '');
                $ts = $updated !== '' ? $updated : $created;
                if ($ts === '') {
                    continue;
                }

                // (3) Potong judul yang kepanjangan (lebih dari 60 huruf)
                //     supaya baris aktivitas tidak jadi bertele-tele.
                $title = (string)($rec[$cfg['title']] ?? '');
                $title = mb_strlen($title) > 60 ? mb_substr($title, 0, 60) . '…' : $title;

                // (4) Susun kalimat keterangan singkat sesuai jenis data:
                //     berita → "diterbitkan"/"draft", pesan → "dari siapa",
                //     umkm/galeri → "ditambahkan"/"diperbarui".
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

        // (5) Urutkan keranjang dari yang paling baru (membandingkan teks
        //     tanggalnya), lalu ambil 6 aktivitas teratas untuk feed dashboard.
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
        // Membuat kalimat "ditambahkan" vs "diperbarui":
        // kalau waktu pembuatan = waktu perubahan, berarti datanya baru
        // dibuat (belum pernah diubah) → "ditambahkan"; kalau beda, → "diperbarui".
        return ($updated !== '' && $updated !== $created) ? $label . ' diperbarui' : $label . ' ditambahkan';
    }

    private static function relativeTime(string $iso): string
    {
        // Mengubah waktu (format tanggal ISO) menjadi kalimat ramah manusia:
        // "Baru saja", "5 menit lalu", "3 jam lalu", dst. Kalau sudah lebih
        // dari seminggu, tampilkan tanggalnya langsung (misal "02 Agu 2026").
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
