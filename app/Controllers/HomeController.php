<?php

declare(strict_types=1);

require_once __DIR__ . '/../Models/SiteData.php';

final class HomeController
{
    public function index(): void
    {
        $datasets = SiteData::all();
        $profile = is_array($datasets['profil'] ?? null) ? $datasets['profil'] : [];
        $demographics = is_array($profile['demografi'] ?? null) ? $profile['demografi'] : [];
        $hamlets = is_array($demographics['per_dusun'] ?? null) ? $demographics['per_dusun'] : [];
        $livelihoods = is_array($profile['mata_pencaharian'] ?? null) ? $profile['mata_pencaharian'] : [];
        $historyData = is_array($profile['sejarah'] ?? null) ? $profile['sejarah'] : [];
        $historySource = $historyData['paragraf'] ?? [];

        if (is_string($historySource)) {
            $historyText = preg_replace('/<\/(?:p|div|h[1-6]|li|blockquote)>/i', "\n\n", $historySource) ?? $historySource;
            $historyText = html_entity_decode(strip_tags($historyText), ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $history = preg_split('/\R\s*\R/', trim($historyText)) ?: [];
        } else {
            $history = is_array($historySource) ? $historySource : [];
        }

        $history = array_values(array_filter(
            array_map(static fn(mixed $paragraph): string => trim((string) $paragraph), $history),
            static fn(string $paragraph): bool => $paragraph !== ''
        ));

        $homepage = [
            'tagline' => (string) ($profile['tagline'] ?? ''),
            'vision' => (string) ($profile['visi'] ?? ''),
            'established_year' => (int) ($profile['tahun_berdiri'] ?? 0),
            'elevation' => (int) ($demographics['ketinggian'] ?? 0),
            'elevation_unit' => (string) ($demographics['ketinggian_satuan'] ?? 'Mdpl'),
            'area' => (float) ($demographics['luas_wilayah'] ?? 0),
            'area_unit' => (string) ($demographics['luas_satuan'] ?? ''),
            'household_count' => (int) ($demographics['kepala_keluarga'] ?? 0),
            'hamlet_count' => count($hamlets),
            'hamlets' => $hamlets,
            'population' => (int) ($demographics['total_jiwa'] ?? 0),
            'history' => $history,
            'livelihoods' => $livelihoods,
            'umkm_count' => is_array($datasets['umkm'] ?? null) ? count($datasets['umkm']) : 0,
            'tourism_count' => is_array($datasets['wisata'] ?? null) ? count($datasets['wisata']) : 0,
        ];

        require __DIR__ . '/../Views/public/home/index.php';
    }
}
