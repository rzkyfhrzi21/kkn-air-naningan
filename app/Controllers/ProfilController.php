<?php

declare(strict_types=1);

final class ProfilController
{
    public function index(): void
    {
        require __DIR__ . '/../Views/public/profil/index.php';
    }
}
