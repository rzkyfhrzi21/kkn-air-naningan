<?php

declare(strict_types=1);

require_once __DIR__ . '/../Models/Wisata.php';

final class WisataController
{
    public function index(): void
    {
        $items = array_values(array_filter(
            Wisata::all(),
            static fn(array $i): bool => ($i['status'] ?? 'buka') === 'buka'
        ));
        $base = defined('APP_BASE') ? APP_BASE : '';
        require __DIR__ . '/../Views/public/wisata/index.php';
    }
}
