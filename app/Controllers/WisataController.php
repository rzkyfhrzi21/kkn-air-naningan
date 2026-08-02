<?php

declare(strict_types=1);

final class WisataController
{
    public function index(): void
    {
        require __DIR__ . '/../Views/public/wisata/index.php';
    }
}
