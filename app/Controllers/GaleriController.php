<?php

declare(strict_types=1);

final class GaleriController
{
    public function index(): void
    {
        require __DIR__ . '/../Views/public/galeri/index.php';
    }
}
