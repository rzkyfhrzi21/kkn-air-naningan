<?php

declare(strict_types=1);

/* ======================================================
   CONTROLLER BERANDA / HOME (HUTAN UTAMA BERANDA)

   Tugas utama: Menyiapkan seluruh data yang dibutuhkan oleh
   halaman utama (Beranda/Home) website Pekon Air Naningan.

   Analoginya: Controller ini ibarat "Koki Utama Restoran".
   Saat pengunjung datang ke beranda, koki ini akan mengambil
   semua bahan makanan dari gudang (Model SiteData), memotong dan
   merapikannya (menghitung jumlah dusun, merapikan paragraf sejarah),
   lalu menyajikan hidangan lengkap tersebut ke meja makan (View Beranda).

   Data yang disiapkan:
   - Data Profil Desa (tagline, visi, tahun berdiri)
   - Data Demografi (luas wilayah, ketinggian, total jiwa, daftar dusun)
   - Data Sejarah Desa (paragraf narasi yang sudah dibersihkan)
   - Data Mata Pencaharian & Statistik UMKM
====================================================== */

require_once __DIR__ . '/../Models/SiteData.php';

final class HomeController
{
    public function index(): void
    {
        // (1) Ambil seluruh data desa dari gudang penyimpanan (Model SiteData)
        $datasets = SiteData::all();
        
        // (2) Ambil sub-bagian profil, demografi, dusun, dan mata pencaharian
        $profile = is_array($datasets['profil'] ?? null) ? $datasets['profil'] : [];
        $demographics = is_array($profile['demografi'] ?? null) ? $profile['demografi'] : [];
        $hamlets = is_array($demographics['per_dusun'] ?? null) ? $demographics['per_dusun'] : [];
        $livelihoods = is_array($profile['mata_pencaharian'] ?? null) ? $profile['mata_pencaharian'] : [];
        $historyData = is_array($profile['sejarah'] ?? null) ? $profile['sejarah'] : [];
        $historySource = $historyData['paragraf'] ?? [];

        // (3) Olah paragraf sejarah: jika berbentuk teks HTML editor kaya, bersihkan tag HTML dan pecah jadi paragraf
        if (is_string($historySource)) {
            $historyText = preg_replace('/<\/(?:p|div|h[1-6]|li|blockquote)>/i', "\n\n", $historySource) ?? $historySource;
            $historyText = html_entity_decode(strip_tags($historyText), ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $history = preg_split('/\R\s*\R/', trim($historyText)) ?: [];
        } else {
            $history = is_array($historySource) ? $historySource : [];
        }

        // (4) Buang paragraf yang kosong dan rapikan spasi kiri-kanan tiap baris sejarah
        $history = array_values(array_filter(
            array_map(static fn(mixed $paragraph): string => trim((string) $paragraph), $history),
            static fn(string $paragraph): bool => $paragraph !== ''
        ));

        // (5) Rangkai paket data lengkap beranda dalam satu nampan ($homepage)
        $homepage = [
            'tagline'          => (string) ($profile['tagline'] ?? ''),
            'vision'           => (string) ($profile['visi'] ?? ''),
            'established_year' => (int) ($profile['tahun_berdiri'] ?? 0),
            'elevation'        => (int) ($demographics['ketinggian'] ?? 0),
            'elevation_unit'   => (string) ($demographics['ketinggian_satuan'] ?? 'Mdpl'),
            'area'             => (float) ($demographics['luas_wilayah'] ?? 0),
            'area_unit'        => (string) ($demographics['luas_satuan'] ?? ''),
            'household_count'  => (int) ($demographics['kepala_keluarga'] ?? 0),
            'hamlet_count'     => count($hamlets),
            'hamlets'          => $hamlets,
            'population'       => (int) ($demographics['total_jiwa'] ?? 0),
            'history'          => $history,
            'livelihoods'      => $livelihoods,
            'umkm_count'       => is_array($datasets['umkm'] ?? null) ? count($datasets['umkm']) : 0,
        ];

        // (6) Panggil dan tampilkan antarmuka View Beranda dengan membawa nampan data $homepage
        require __DIR__ . '/../Views/public/home/index.php';
    }
}

