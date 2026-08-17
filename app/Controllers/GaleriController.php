<?php

declare(strict_types=1);

/* ======================================================
   CONTROLLER GALERI (SISI PUBLIK / PENGUNJUNG)

   Tugas utama: Menyiapkan data album foto dan video desa untuk
   ditampilkan di halaman galeri publik.

   Analoginya: Controller ini ibarat "Pemandu Pameran Foto".
   Saat pengunjung membuka halaman galeri, pemandu ini mengambil
   seluruh album foto (Model Galeri) dan daftar kategori resmi,
   lalu menata foto-foto tersebut di dinding pameran (View Galeri).
====================================================== */

require_once __DIR__ . '/../Models/Galeri.php';

final class GaleriController
{
    public function index(): void
    {
        // (1) Minta Model Galeri mengambil seluruh item foto & video yang tersimpan di galeri.json
        $items = Galeri::all();
        
        // (2) Ambil daftar kategori resmi (Alam, Kegiatan, Budaya, Pembangunan)
        $kategori = Galeri::KATEGORI;
        
        // (3) Tampilkan antarmuka View Galeri publik dengan menyerahkan data foto dan kategori
        require __DIR__ . '/../Views/public/galeri/index.php';
    }
}

