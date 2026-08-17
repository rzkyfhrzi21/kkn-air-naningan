<?php

declare(strict_types=1);

/* ======================================================
   MODEL SITEDATA (KURIR DATA SELURUH SITUS)

   File ini bertugas "mengumpulkan" semua file JSON di folder
   public/data/ menjadi satu paket data besar yang siap dipakai
   halaman beranda (home) dan dashboard admin.

   Analoginya: Model ini ibarat kurir yang berkeliling mengambil
   semua buku laporan dari tiap rak (file JSON: umkm, wisata,
   berita, galeri, dsb), lalu menatanya dalam satu tas besar
   berlabel nama rak masing-masing.

   File JSON yang dikelola:
   - Semua file .json di public/data/ (umkm.json, wisata.json,
     berita.json, galeri.json, dsb) — tanpa membaca isi file
     satu per satu secara manual.

   Fungsi yang tersedia:
   - all() : mengambil seluruh isi folder data sebagai satu array
             besar, dengan kunci = nama file (tanpa ekstensi .json)
   ====================================================== */

final class SiteData
{
    private const DATA_DIRECTORY = __DIR__ . '/../../public/data'; // Lokasi folder tempat semua file JSON data desa berada

    public static function all(): array
    {
        // (1) Kalau folder datanya belum ada, tidak ada data sama sekali
        if (!is_dir(self::DATA_DIRECTORY)) {
            return [];
        }

        $datasets = []; // Tas besar kosong, siap diisi semua data

        // (2) Cari SEMUA file berakhiran .json di folder data; glob() = "tunjukkan semua file yang polanya ini"
        foreach (glob(self::DATA_DIRECTORY . '/*.json') ?: [] as $file) {
            // (3) Buka tiap file untuk dibaca
            $handle = fopen($file, 'rb');

            // Kalau file tidak bisa dibuka, lewati file ini dan lanjut ke file berikutnya
            if ($handle === false) {
                continue;
            }

            flock($handle, LOCK_SH); // Kunci "baca": boleh dibaca bersama, asal tidak sedang ada yang menulis
            $contents = stream_get_contents($handle); // Baca seluruh isi file
            flock($handle, LOCK_UN); // Selesai membaca, buka kuncinya
            fclose($handle);

            // (4) Ubah teks JSON menjadi array; kalau rusak, masukkan array kosong
            $data = json_decode($contents ?: '[]', true);
            // (5) Masukkan ke tas besar dengan label = nama file tanpa .json
            //     (misal umkm.json → kunci 'umkm')
            $datasets[pathinfo($file, PATHINFO_FILENAME)] = is_array($data) ? $data : [];
        }

        // (6) Kembalikan tas besar berisi seluruh data situs
        return $datasets;
    }
}
