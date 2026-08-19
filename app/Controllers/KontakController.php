<?php

declare(strict_types=1);

/* ======================================================
   CONTROLLER KONTAK (SISI PUBLIK / PENGUNJUNG)

   Tugas utama: Menampilkan formulir kontak dan alamat kantor desa
   untuk pengunjung situs.

   Analoginya: Controller ini ibarat "Petugas Meja Informasi / Loket Kontak".
   Saat warga atau pengunjung ingin menghubungi pihak kantor pekon,
   controller ini akan membuka pintu halaman formulir kontak (View Kontak).
====================================================== */

final class KontakController
{
    public function index(): void
    {
        // (1) Ambil data peta dari Model Profil (public/data/profil.json)
        require_once __DIR__ . '/../Models/Profil.php';
        $profil = Profil::get();

        // (2) Serahkan data peta ke view: embed Google Maps + label lokasi
        $mapsEmbed    = (string) ($profil['peta']['embed_url'] ?? '');
        $mapsLocation = (string) ($profil['peta']['lokasi'] ?? '');

        // (3) Tampilkan antarmuka View Kontak publik (formulir pesan + peta + info kontak)
        require __DIR__ . '/../Views/public/kontak/index.php';
    }
}

