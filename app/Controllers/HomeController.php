<?php

declare(strict_types=1);

require_once __DIR__ . '/../Models/SiteData.php';

final class HomeController
{
    public function index(): void
    {
        $datasets = SiteData::all();
        $profile = $datasets['profil'] ?? [];
        $demographics = $profile['demografi'] ?? [];
        $history = $profile['sejarah']['paragraf'] ?? [];
        $livelihoods = $profile['mata_pencaharian'] ?? [];

        $homepage = [
            'tagline' => (string) ($profile['tagline'] ?? ''),
            'vision' => (string) ($profile['visi'] ?? ''),
            'established_year' => (int) ($profile['tahun_berdiri'] ?? 0),
            'elevation' => (int) ($demographics['ketinggian'] ?? 0),
            'elevation_unit' => (string) ($demographics['ketinggian_satuan'] ?? 'Mdpl'),
            'hamlet_count' => count($demographics['per_dusun'] ?? []),
            'population' => (int) ($demographics['total_jiwa'] ?? 0),
            'history' => array_values(array_filter($history, 'is_string')),
            'main_livelihood' => (string) ($livelihoods[0]['jenis'] ?? 'Pertanian lokal'),
            'umkm_count' => count($datasets['umkm'] ?? []),
            'tourism_count' => count($datasets['wisata'] ?? []),
        ];

        require __DIR__ . '/../Views/public/home/index.php';
    }
}
