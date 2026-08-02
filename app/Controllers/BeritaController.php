<?php

declare(strict_types=1);

final class BeritaController
{
    public function index(): void
    {
        require __DIR__ . '/../Views/public/berita/index.php';
    }
}
