<?php

declare(strict_types=1);

require_once __DIR__ . '/../Models/Umkm.php';

final class UmkmController
{
    public function index(): void
    {
        $items = array_values(array_filter(
            Umkm::all(),
            static fn(array $i): bool => ($i['status'] ?? 'aktif') === 'aktif'
        ));
        $kategori = Umkm::KATEGORI;
        require __DIR__ . '/../Views/public/umkm/index.php';
    }
}
