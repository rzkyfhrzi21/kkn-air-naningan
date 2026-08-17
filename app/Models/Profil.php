<?php

declare(strict_types=1);

/* ======================================================
   MODEL PROFIL (PROFIL DESA)

   File ini bertugas mengelola data profil lengkap desa, seperti
   logo, tahun berdiri, visi & misi, struktur pemerintahan,
   demografi penduduk, mata pencaharian, APBDes, sejarah, dan peta.

   Analoginya: Model ini ibarat petugas "buku induk desa".
   Hanya ada SATU buku induk (satu file JSON), dan isinya sangat
   banyak bab. Petugas ini yang bertanggung jawab membaca buku
   tersebut dan menulis ulang bab-bab yang diminta admin.

   File JSON yang dikelola:
   - public/data/profil.json  → data profil desa (satu objek besar)
   - secure/backup/           → folder cadangan (salinan lama
                                sebelum file ditimpa)

   Fungsi yang tersedia:
   - get()     : mengambil data profil; kalau belum ada, pakai nilai bawaan
   - save()    : memperbarui profil sesuai ketikan form admin
   - write()   : menulis seluruh data profil ke file JSON
   - backup()  : menyalin file lama sebagai cadangan
   - defaults(): nilai awal bawaan jika file profil belum pernah dibuat
   ====================================================== */

/**
 * Model Profil — objek tunggal public/data/profil.json
 * API: get() / save(array)
 */
class Profil
{
    private static string $file      = __DIR__ . '/../../public/data/profil.json'; // Lokasi file JSON tempat seluruh data profil desa disimpan
    private static string $backupDir = __DIR__ . '/../../secure/backup/'; // Folder tempat salinan cadangan file lama disimpan sebelum ditimpa

    public static function get(): array
    {
        // (1) Kalau file profil belum pernah dibuat, kembalikan nilai bawaan (defaults)
        if (!file_exists(self::$file)) {
            return self::defaults();
        }

        // (2) Buka file untuk dibaca; kalau gagal, kembalikan nilai bawaan
        $fp = fopen(self::$file, 'r');
        if ($fp === false) {
            return self::defaults();
        }

        flock($fp, LOCK_SH); // Kunci "baca": boleh dibaca bersama, asal tidak sedang ada yang menulis
        $raw = stream_get_contents($fp); // Baca seluruh isi file
        flock($fp, LOCK_UN); // Selesai membaca, buka kuncinya
        fclose($fp);

        // (3) Ubah teks JSON menjadi array; kalau file rusak/kosong, pakai nilai bawaan
        $data = json_decode($raw ?: '{}', true);
        if (!is_array($data) || $data === []) {
            return self::defaults();
        }

        // (4) Gabungkan nilai bawaan dengan data yang tersimpan — kolom yang belum
        //     ada di file tetap terisi nilai bawaan, jadi tampilan tidak pernah kosong
        return array_replace_recursive(self::defaults(), $data);
    }

    public static function save(array $payload): array
    {
        // $payload = seluruh ketikan admin di form pengaturan profil
        $current = self::get(); // (1) Ambil data profil yang tersimpan sekarang

        // (2) Timpa satu per satu hanya bagian yang dikirim dari form —
        //     nama di kiri (misal 'logo') adalah nama kolom yang disimpan di JSON
        if (isset($payload['logo'])) {
            $current['logo'] = trim((string) $payload['logo']); // Path file logo yang di-upload
        }
        if (isset($payload['tahun_berdiri'])) {
            $current['tahun_berdiri'] = (int) $payload['tahun_berdiri']; // Diubah jadi angka tahun
        }
        if (isset($payload['tagline'])) {
            $current['tagline'] = trim((string) $payload['tagline']);
        }
        if (isset($payload['visi'])) {
            $current['visi'] = trim((string) $payload['visi']);
        }
        // Misi: dari daftar isian, buang baris kosong, rapikan spasi tiap baris
        if (isset($payload['misi']) && is_array($payload['misi'])) {
            $current['misi'] = array_values(array_filter(
                array_map(static fn($m) => trim((string) $m), $payload['misi']), // Bersihkan tiap baris misi
                static fn($m) => $m !== '' // Buang baris yang kosong setelah dibersihkan
            ));
        }
        if (isset($payload['masa_bakti'])) {
            $current['masa_bakti'] = trim((string) $payload['masa_bakti']);
        }
        // Struktur pemerintahan: daftar orang dengan nama, jabatan, foto, dan level
        if (isset($payload['struktur']) && is_array($payload['struktur'])) {
            $struktur = [];
            foreach ($payload['struktur'] as $row) { // Telusuri satu per satu baris isian form
                if (!is_array($row)) {
                    continue; // Baris yang bukan array (rusak) dilewati saja
                }
                $nama = trim((string) ($row['nama'] ?? '')); // Nama orang (dari ketikan form)
                $jabatan = trim((string) ($row['jabatan'] ?? '')); // Jabatan orang tersebut
                if ($nama === '' && $jabatan === '') {
                    continue; // Baris yang nama & jabatannya kosong tidak disimpan
                }
                $struktur[] = [
                    'nama'    => $nama, // Kolom 'nama' di JSON
                    'jabatan' => $jabatan, // Kolom 'jabatan' di JSON
                    'foto'    => trim((string) ($row['foto'] ?? '')), // Kolom 'foto' = nama file foto di folder upload
                    'level'   => (int) ($row['level'] ?? 0), // Kolom 'level' = urutan prioritas tampil
                ];
            }
            $current['struktur'] = $struktur;
        }
        // Demografi: data jumlah penduduk desa
        if (isset($payload['demografi']) && is_array($payload['demografi'])) {
            $d = $payload['demografi'];
            $current['demografi']['total_jiwa']       = (int) ($d['total_jiwa'] ?? $current['demografi']['total_jiwa']); // Total penduduk (jiwa)
            $current['demografi']['kepala_keluarga']  = (int) ($d['kepala_keluarga'] ?? $current['demografi']['kepala_keluarga']); // Jumlah kepala keluarga
            $current['demografi']['luas_wilayah']     = (float) ($d['luas_wilayah'] ?? $current['demografi']['luas_wilayah']); // Luas wilayah (angka desimal)
            $current['demografi']['luas_satuan']      = trim((string) ($d['luas_satuan'] ?? $current['demografi']['luas_satuan'])); // Satuan luas (misal km²)
            $current['demografi']['ketinggian']       = (float) ($d['ketinggian'] ?? $current['demografi']['ketinggian']); // Ketinggian tempat
            $current['demografi']['ketinggian_satuan'] = trim((string) ($d['ketinggian_satuan'] ?? $current['demografi']['ketinggian_satuan'])); // Satuan ketinggian (misal mdpl)
            // Rincian per dusun: nama dusun + jumlah penduduknya
            if (isset($d['per_dusun']) && is_array($d['per_dusun'])) {
                $dusuns = [];
                foreach ($d['per_dusun'] as $i => $row) {
                    if (!is_array($row)) {
                        continue;
                    }
                    $dusuns[] = [
                        'nama'   => trim((string) ($row['nama'] ?? ('Dusun ' . ($i + 1)))), // Kalau nama kosong, otomatis "Dusun 1", "Dusun 2", dst
                        'jumlah' => (int) ($row['jumlah'] ?? 0), // Jumlah penduduk dusun tersebut
                    ];
                }
                if ($dusuns !== []) {
                    $current['demografi']['per_dusun'] = $dusuns; // Hanya ditimpa kalau hasilnya tidak kosong
                }
            }
        }
        // Mata pencaharian: daftar jenis pekerjaan + persentasenya (total wajib 100%)
        if (isset($payload['mata_pencaharian']) && is_array($payload['mata_pencaharian'])) {
            $jobs = [];
            $jobTotal = 0;
            foreach ($payload['mata_pencaharian'] as $row) { // Telusuri tiap baris isian form
                if (!is_array($row)) {
                    continue;
                }
                $jenis = trim((string) ($row['jenis'] ?? '')); // Nama pekerjaan
                if ($jenis === '') {
                    continue; // Baris tanpa nama pekerjaan dilewati
                }
                $persen = max(0, min(100, (int) ($row['persen'] ?? 0))); // Persen dipaksa antara 0–100 (tidak boleh negatif/lebih)
                $jobTotal += $persen; // Kumpulkan total persen
                $jobs[] = ['jenis' => $jenis, 'persen' => $persen];
            }
            // Kalau tidak ada satu pun pekerjaan terisi, tolak simpan dengan pesan jelas
            if ($jobs === []) {
                throw new InvalidArgumentException('Minimal satu jenis mata pencaharian harus diisi.');
            }
            // Kalau total persen tidak tepat 100, tolak simpan agar data tidak janggal
            if ($jobTotal !== 100) {
                throw new InvalidArgumentException("Total persentase mata pencaharian harus tepat 100%. Saat ini {$jobTotal}%.");
            }
            $current['mata_pencaharian'] = $jobs;
        }
        // APBDes: anggaran desa — tahun, link laporan, dan rincian pemasukan/pengeluaran
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
                        continue; // Baris tanpa nama tidak disimpan
                    }
                    $items[] = [
                        'nama'   => $nama, // Nama pos anggaran
                        'jumlah' => trim((string) ($row['jumlah'] ?? '')), // Nominal (disimpan sebagai teks agar format rupiah rapi)
                        'persen' => max(0, min(100, (int) ($row['persen'] ?? 0))), // Persen dipaksa 0–100
                        'icon'   => trim((string) ($row['icon'] ?? 'account_balance')), // Ikon tampilan; kalau kosong, pakai ikon default
                    ];
                }
                $current['apbdes']['items'] = $items;
            }
        }
        // Sejarah desa: paragraf cerita dan kutipan (quote)
        if (isset($payload['sejarah']) && is_array($payload['sejarah'])) {
            $s = $payload['sejarah'];
            if (isset($s['paragraf'])) {
                if (is_array($s['paragraf'])) {
                    // Kalau isiannya berupa daftar paragraf → bersihkan dan buang yang kosong
                    $current['sejarah']['paragraf'] = array_values(array_filter(
                        array_map(static fn($p) => trim((string) $p), $s['paragraf']),
                        static fn($p) => $p !== ''
                    ));
                } else {
                    $raw = trim((string) $s['paragraf']); // Isian satu teks panjang dari textarea
                    if (strpos($raw, '<') !== false) {
                        // Rich text dari RTE: simpan HTML utuh sebagai string
                        $current['sejarah']['paragraf'] = $raw; // Kalau mengandung tag HTML, simpan apa adanya (dari editor kaya)
                    } else {
                        // textarea plain: pecah per baris kosong
                        $parts = preg_split('/\n\s*\n/', $raw) ?: []; // Pisahkan tiap paragraf: baris kosong menandai pergantian paragraf
                        $current['sejarah']['paragraf'] = array_values(array_filter(
                            array_map('trim', $parts),
                            static fn($p) => $p !== ''
                        ));
                    }
                }
            }
            if (isset($s['quote'])) {
                $current['sejarah']['quote'] = trim((string) $s['quote']); // Kutipan terkenal/penutup sejarah
            }
        }
        // Peta: lokasi teks dan link peta (embed Google Maps)
        if (isset($payload['peta']) && is_array($payload['peta'])) {
            $p = $payload['peta'];
            $current['peta']['lokasi'] = trim((string) ($p['lokasi'] ?? $current['peta']['lokasi'])); // Nama lokasi yang ditampilkan
            $current['peta']['embed_url'] = trim((string) ($p['embed_url'] ?? $current['peta']['embed_url'])); // Link embed peta
        }

        // (3) Catat waktu terakhir profil diperbarui
        $current['updated_at'] = date('c');
        self::write($current); // (4) Tulis seluruh data ke file JSON
        return $current; // Kembalikan profil yang sudah diperbarui
    }

    private static function write(array $data): void
    {
        // (1) Salin dulu file lama sebagai cadangan sebelum ditimpa
        self::backup();
        // (2) Pastikan folder tempat file JSON berada sudah ada
        $dir = dirname(self::$file);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        // (3) Buka file untuk ditulis; kalau gagal, berhenti dengan pesan kesalahan
        $fp = fopen(self::$file, 'c');
        if ($fp === false) {
            throw new RuntimeException('Gagal membuka profil.json untuk ditulis.');
        }
        flock($fp, LOCK_EX); // Kunci "tulis": mengunci pintu gudang agar tidak ada dua admin menulis bersamaan
        ftruncate($fp, 0); // Kosongkan file dulu
        fwrite($fp, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); // Tulis data sebagai JSON rapi
        flock($fp, LOCK_UN); // Buka kunci
        fclose($fp);
    }

    private static function backup(): void
    {
        // Kalau file asli belum ada, tidak perlu dicadangkan
        if (!file_exists(self::$file)) {
            return;
        }
        // Buat folder cadangan kalau belum ada
        if (!is_dir(self::$backupDir)) {
            mkdir(self::$backupDir, 0755, true);
        }
        // Salin file lama dengan nama berisi tanggal & jam, misal profil_2026-08-09_143000.json
        copy(self::$file, self::$backupDir . 'profil_' . date('Y-m-d_His') . '.json');
    }

    private static function defaults(): array
    {
        // Nilai awal bawaan profil desa — dipakai saat file JSON belum pernah dibuat
        return [
            'logo'          => '/assets/images/logo.jpg', // Path foto logo bawaan
            'tahun_berdiri' => 0, // Tahun berdiri (0 = belum diisi)
            'tagline'       => '',
            'visi'          => '',
            'misi'          => [],
            'masa_bakti'    => '',
            'struktur'      => [], // Daftar perangkat desa (masih kosong)
            'demografi'     => [
                'total_jiwa'        => 0, // Total penduduk
                'kepala_keluarga'   => 0, // Kepala keluarga
                'luas_wilayah'      => 0, // Luas wilayah
                'luas_satuan'       => 'km²', // Satuan luas default
                'ketinggian'        => 0, // Ketinggian tempat
                'ketinggian_satuan' => 'mdpl', // Satuan ketinggian default
                'per_dusun'         => [], // Rincian per dusun (masih kosong)
            ],
            'mata_pencaharian' => [], // Jenis pekerjaan penduduk (masih kosong)
            'apbdes'           => [
                'tahun'       => (int) date('Y'), // Tahun anggaran default = tahun berjalan
                'items'       => [], // Rincian anggaran (masih kosong)
                'laporan_url' => '', // Link laporan APBDes
            ],
            'sejarah' => [
                'paragraf' => [], // Paragraf sejarah desa
                'quote'    => '', // Kutipan sejarah
            ],
            'peta' => [
                'lokasi'   => 'Air Naningan, Tanggamus, Lampung, Indonesia', // Lokasi bawaan desa
                'embed_url' => '', // Link embed peta (belum diisi)
            ],
            'updated_at' => null, // Waktu pembaruan terakhir (belum pernah diperbarui)
        ];
    }
}
