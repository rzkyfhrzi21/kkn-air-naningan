<?php

declare(strict_types=1);

require_once __DIR__ . '/../Models/Profil.php';

final class ProfilController
{
    public function index(): void
    {
        $profil = Profil::get();
        require __DIR__ . '/../Views/public/profil/index.php';
    }
}
