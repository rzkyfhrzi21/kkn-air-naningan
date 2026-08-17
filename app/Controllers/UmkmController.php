<?php

declare(strict_types=1);

/* ======================================================
   CONTROLLER UMKM (SISI PUBLIK / PENGUNJUNG)

   Tugas utama: Menyiapkan data Usaha Mikro Kecil Menengah (UMKM)
   warga pekon yang berstatus 'aktif' untuk ditampilkan di publik.

   Analoginya: Controller ini ibarat "Petugas Pasar Kreatif / Direktori Usaha".
   Saat pengunjung mencari daftar produk & usaha warga desa, controller ini
   mengambil data dari Model Umkm, menyaring usaha yang aktif saja,
   dan menampilkan daftarnya di etalase direktori (View UMKM).
====================================================== */

require_once __DIR__ . '/../Models/Umkm.php';

final class UmkmController
{
    public function index(): void
    {
        // (1) Ambil seluruh UMKM dari file JSON via Model Umkm, lalu saring (filter) hanya yang berstatus 'aktif'
        $items = array_values(array_filter(
            Umkm::all(),
            static fn(array $i): bool => ($i['status'] ?? 'aktif') === 'aktif'
        ));
        
        // (2) Ambil daftar kategori usaha resmi (Kopi, Kuliner, Kriya, Jasa)
        $kategori = Umkm::KATEGORI;
        
        // (3) Tampilkan antarmuka View UMKM publik
        require __DIR__ . '/../Views/public/umkm/index.php';
    }
}

