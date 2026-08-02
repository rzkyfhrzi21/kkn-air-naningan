<?php

declare(strict_types=1);

final class SiteData
{
    private const DATA_DIRECTORY = __DIR__ . '/../../public/data';

    public static function all(): array
    {
        if (!is_dir(self::DATA_DIRECTORY)) {
            return [];
        }

        $datasets = [];

        foreach (glob(self::DATA_DIRECTORY . '/*.json') ?: [] as $file) {
            $handle = fopen($file, 'rb');

            if ($handle === false) {
                continue;
            }

            flock($handle, LOCK_SH);
            $contents = stream_get_contents($handle);
            flock($handle, LOCK_UN);
            fclose($handle);

            $data = json_decode($contents ?: '[]', true);
            $datasets[pathinfo($file, PATHINFO_FILENAME)] = is_array($data) ? $data : [];
        }

        return $datasets;
    }
}
