<?php

declare(strict_types=1);

require_once __DIR__ . '/../Models/Galeri.php';

final class GaleriController
{
    public function index(): void
    {
        $items = Galeri::all();
        $kategori = Galeri::KATEGORI;
        require __DIR__ . '/../Views/public/galeri/index.php';
    }
}
