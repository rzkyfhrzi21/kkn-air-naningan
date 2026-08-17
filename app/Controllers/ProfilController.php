<?php

declare(strict_types=1);

/* ======================================================
   CONTROLLER PROFIL (SISI PUBLIK / PENGUNJUNG)

   Tugas utama: Menyiapkan data informasi profil pekon (sejarah,
   visi-misi, demografi, aparatur desa, APBDes, dll) untuk publik.

   Analoginya: Controller ini ibarat "Petugas Museum / Humas Pekon".
   Saat masyarakat membaca halaman profil pekon, controller ini mengambil
   buku profil dari Model Profil, lalu menyajikannya di lemari pajangan (View Profil).
====================================================== */

require_once __DIR__ . '/../Models/Profil.php';

final class ProfilController
{
    public function index(): void
    {
        // (1) Ambil seluruh data profil desa dari file JSON via Model Profil
        $profil = Profil::get();
        
        // (2) Render tampilan halaman profil pekon untuk publik
        require __DIR__ . '/../Views/public/profil/index.php';
    }
}

