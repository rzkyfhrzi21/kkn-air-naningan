<?php

declare(strict_types=1);

/**
 * Model Profil — objek tunggal public/data/profil.json
 * API: get() / save(array)
 */
class Profil
{
    private static string $file      = __DIR__ . '/../../public/data/profil.json';
    private static string $backupDir = __DIR__ . '/../../secure/backup/';

    public static function get(): array
    {
        if (!file_exists(self::$file)) {
            return self::defaults();
        }

        $fp = fopen(self::$file, 'r');
        if ($fp === false) {
            return self::defaults();
        }

        flock($fp, LOCK_SH);
        $raw = stream_get_contents($fp);
        flock($fp, LOCK_UN);
        fclose($fp);

        $data = json_decode($raw ?: '{}', true);
        if (!is_array($data) || $data === []) {
            return self::defaults();
        }

        return array_replace_recursive(self::defaults(), $data);
    }

    public static function save(array $payload): array
    {
        $current = self::get();

        if (isset($payload['logo'])) {
            $current['logo'] = trim((string) $payload['logo']);
        }
        if (isset($payload['tahun_berdiri'])) {
            $current['tahun_berdiri'] = (int) $payload['tahun_berdiri'];
        }
        if (isset($payload['tagline'])) {
            $current['tagline'] = trim((string) $payload['tagline']);
        }
        if (isset($payload['visi'])) {
            $current['visi'] = trim((string) $payload['visi']);
        }
        if (isset($payload['misi']) && is_array($payload['misi'])) {
            $current['misi'] = array_values(array_filter(
                array_map(static fn($m) => trim((string) $m), $payload['misi']),
                static fn($m) => $m !== ''
            ));
        }
        if (isset($payload['masa_bakti'])) {
            $current['masa_bakti'] = trim((string) $payload['masa_bakti']);
        }
        if (isset($payload['struktur']) && is_array($payload['struktur'])) {
            $struktur = [];
            foreach ($payload['struktur'] as $row) {
                if (!is_array($row)) {
                    continue;
                }
                $nama = trim((string) ($row['nama'] ?? ''));
                $jabatan = trim((string) ($row['jabatan'] ?? ''));
                if ($nama === '' && $jabatan === '') {
                    continue;
                }
                $struktur[] = [
                    'nama'    => $nama,
                    'jabatan' => $jabatan,
                    'foto'    => trim((string) ($row['foto'] ?? '')),
                    'level'   => (int) ($row['level'] ?? 0),
                ];
            }
            $current['struktur'] = $struktur;
        }
        if (isset($payload['demografi']) && is_array($payload['demografi'])) {
            $d = $payload['demografi'];
            $current['demografi']['total_jiwa']       = (int) ($d['total_jiwa'] ?? $current['demografi']['total_jiwa']);
            $current['demografi']['kepala_keluarga']  = (int) ($d['kepala_keluarga'] ?? $current['demografi']['kepala_keluarga']);
            $current['demografi']['luas_wilayah']     = (float) ($d['luas_wilayah'] ?? $current['demografi']['luas_wilayah']);
            $current['demografi']['luas_satuan']      = trim((string) ($d['luas_satuan'] ?? $current['demografi']['luas_satuan']));
            $current['demografi']['ketinggian']       = (float) ($d['ketinggian'] ?? $current['demografi']['ketinggian']);
            $current['demografi']['ketinggian_satuan'] = trim((string) ($d['ketinggian_satuan'] ?? $current['demografi']['ketinggian_satuan']));
            if (isset($d['per_dusun']) && is_array($d['per_dusun'])) {
                $dusuns = [];
                foreach ($d['per_dusun'] as $i => $row) {
                    if (!is_array($row)) {
                        continue;
                    }
                    $dusuns[] = [
                        'nama'   => trim((string) ($row['nama'] ?? ('Dusun ' . ($i + 1)))),
                        'jumlah' => (int) ($row['jumlah'] ?? 0),
                    ];
                }
                if ($dusuns !== []) {
                    $current['demografi']['per_dusun'] = $dusuns;
                }
            }
        }
        if (isset($payload['mata_pencaharian']) && is_array($payload['mata_pencaharian'])) {
            $jobs = [];
            $jobTotal = 0;
            foreach ($payload['mata_pencaharian'] as $row) {
                if (!is_array($row)) {
                    continue;
                }
                $jenis = trim((string) ($row['jenis'] ?? ''));
                if ($jenis === '') {
                    continue;
                }
                $persen = max(0, min(100, (int) ($row['persen'] ?? 0)));
                $jobTotal += $persen;
                $jobs[] = ['jenis' => $jenis, 'persen' => $persen];
            }
            if ($jobs === []) {
                throw new InvalidArgumentException('Minimal satu jenis mata pencaharian harus diisi.');
            }
            if ($jobTotal !== 100) {
                throw new InvalidArgumentException("Total persentase mata pencaharian harus tepat 100%. Saat ini {$jobTotal}%.");
            }
            $current['mata_pencaharian'] = $jobs;
        }
        if (isset($payload['apbdes']) && is_array($payload['apbdes'])) {
            $a = $payload['apbdes'];
            $current['apbdes']['tahun'] = (int) ($a['tahun'] ?? $current['apbdes']['tahun']);
            $current['apbdes']['laporan_url'] = trim((string) ($a['laporan_url'] ?? $current['apbdes']['laporan_url']));
            if (isset($a['items']) && is_array($a['items'])) {
                $items = [];
                foreach ($a['items'] as $row) {
                    if (!is_array($row)) {
                        continue;
                    }
                    $nama = trim((string) ($row['nama'] ?? ''));
                    if ($nama === '') {
                        continue;
                    }
                    $items[] = [
                        'nama'   => $nama,
                        'jumlah' => trim((string) ($row['jumlah'] ?? '')),
                        'persen' => max(0, min(100, (int) ($row['persen'] ?? 0))),
                        'icon'   => trim((string) ($row['icon'] ?? 'account_balance')),
                    ];
                }
                $current['apbdes']['items'] = $items;
            }
        }
        if (isset($payload['sejarah']) && is_array($payload['sejarah'])) {
            $s = $payload['sejarah'];
            if (isset($s['paragraf'])) {
                if (is_array($s['paragraf'])) {
                    $current['sejarah']['paragraf'] = array_values(array_filter(
                        array_map(static fn($p) => trim((string) $p), $s['paragraf']),
                        static fn($p) => $p !== ''
                    ));
                } else {
                    $raw = trim((string) $s['paragraf']);
                    if (strpos($raw, '<') !== false) {
                        // Rich text dari RTE: simpan HTML utuh sebagai string
                        $current['sejarah']['paragraf'] = $raw;
                    } else {
                        // textarea plain: pecah per baris kosong
                        $parts = preg_split('/\n\s*\n/', $raw) ?: [];
                        $current['sejarah']['paragraf'] = array_values(array_filter(
                            array_map('trim', $parts),
                            static fn($p) => $p !== ''
                        ));
                    }
                }
            }
            if (isset($s['quote'])) {
                $current['sejarah']['quote'] = trim((string) $s['quote']);
            }
        }
        if (isset($payload['peta']) && is_array($payload['peta'])) {
            $p = $payload['peta'];
            $current['peta']['lokasi'] = trim((string) ($p['lokasi'] ?? $current['peta']['lokasi']));
            $current['peta']['embed_url'] = trim((string) ($p['embed_url'] ?? $current['peta']['embed_url']));
        }

        $current['updated_at'] = date('c');
        self::write($current);
        return $current;
    }

    private static function write(array $data): void
    {
        self::backup();
        $dir = dirname(self::$file);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $fp = fopen(self::$file, 'c');
        if ($fp === false) {
            throw new RuntimeException('Gagal membuka profil.json untuk ditulis.');
        }
        flock($fp, LOCK_EX);
        ftruncate($fp, 0);
        fwrite($fp, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
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
        copy(self::$file, self::$backupDir . 'profil_' . date('Y-m-d_His') . '.json');
    }

    private static function defaults(): array
    {
        return [
            'logo'          => '/assets/images/logo.jpg',
            'tahun_berdiri' => 0,
            'tagline'       => '',
            'visi'          => '',
            'misi'          => [],
            'masa_bakti'    => '',
            'struktur'      => [],
            'demografi'     => [
                'total_jiwa'        => 0,
                'kepala_keluarga'   => 0,
                'luas_wilayah'      => 0,
                'luas_satuan'       => 'km²',
                'ketinggian'        => 0,
                'ketinggian_satuan' => 'mdpl',
                'per_dusun'         => [],
            ],
            'mata_pencaharian' => [],
            'apbdes'           => [
                'tahun'       => (int) date('Y'),
                'items'       => [],
                'laporan_url' => '',
            ],
            'sejarah' => [
                'paragraf' => [],
                'quote'    => '',
            ],
            'peta' => [
                'lokasi'   => 'Air Naningan, Tanggamus, Lampung, Indonesia',
                'embed_url' => '',
            ],
            'updated_at' => null,
        ];
    }
}
