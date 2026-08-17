<?php

declare(strict_types=1);

/* ======================================================
   MODEL UMKM (DATA USAHA WARGA DESA)

   File ini bertugas mengelola data Usaha Mikro Kecil Menengah
   (UMKM) milik warga desa, seperti warung kopi, kuliner olahan,
   kerajinan, dan jasa.

   Analoginya: Model ini ibarat petugas gudang yang mengurus satu
   rak khusus data UMKM. Semua kartu usaha warga disimpan dalam
   satu kotak arsip (file JSON). Petugas ini yang tahu cara
   menyimpan kartu baru, mencari kartu lama, mengubah isinya,
   dan membuang kartu yang tidak dipakai.

   File JSON yang dikelola:
   - public/data/umkm.json  → seluruh data UMKM desa
   - secure/backup/         → folder cadangan (salinan lama
                              sebelum file ditimpa)

   Fungsi yang tersedia:
   - all()             : mengambil seluruh UMKM
   - find()            : mencari 1 UMKM berdasarkan id
   - create()          : menambah UMKM baru
   - update()          : mengubah UMKM yang sudah ada
   - delete()          : menghapus UMKM
   - save()            : menulis seluruh data ke file JSON
   - backup()          : menyalin file lama sebagai cadangan
   - normalizeKategori(): merapikan kategori agar selalu sah
   - normalizeWa()     : merapikan nomor WhatsApp agar format 62
   - slugify()         : mengubah nama menjadi link URL yang aman
   ====================================================== */

class Umkm
{
    private static string $file      = __DIR__ . '/../../public/data/umkm.json'; // Lokasi file JSON tempat seluruh data UMKM disimpan
    private static string $backupDir = __DIR__ . '/../../secure/backup/'; // Folder tempat salinan cadangan file lama disimpan sebelum ditimpa

    // Daftar kategori UMKM yang sah: kode pendek di kiri (disimpan di JSON),
    // dan label panjang di kanan (ditampilkan ke pengunjung website)
    public const KATEGORI = [
        'kopi'    => 'Kopi & Agrikultur',
        'kuliner' => 'Kuliner Olahan',
        'kriya'   => 'Kriya & Kerajinan',
        'jasa'    => 'Jasa',
    ];

    public static function all(): array
    {
        // (1) Kalau file JSON belum ada, tidak ada UMKM sama sekali
        if (!file_exists(self::$file)) {
            return [];
        }
        // (2) Buka file untuk dibaca; kalau gagal, anggap kosong
        $fp = fopen(self::$file, 'r');
        if ($fp === false) {
            return [];
        }
        flock($fp, LOCK_SH); // Kunci "baca": boleh dibaca bersama, asal tidak sedang ada yang menulis
        $data = json_decode(stream_get_contents($fp) ?: '[]', true) ?? []; // Ubah teks JSON menjadi array UMKM
        flock($fp, LOCK_UN); // Selesai membaca, buka kuncinya
        fclose($fp);
        return is_array($data) ? $data : []; // Pastikan hasilnya array; kalau rusak, anggap kosong
    }

    public static function find(string $id): ?array
    {
        // Periksa satu per satu; begitu id cocok, kembalikan UMKM itu
        foreach (self::all() as $item) {
            if (($item['id'] ?? '') === $id) {
                return $item;
            }
        }
        return null; // Tidak ketemu → null
    }

    public static function create(array $payload): array
    {
        // $payload = seluruh ketikan admin di form tambah UMKM
        $items = self::all(); // (1) Ambil semua UMKM yang sudah ada
        $ts    = time(); // (2) Catat detik saat ini untuk membuat id unik
        $kat   = self::normalizeKategori($payload['kategori'] ?? ''); // (3) Rapikan kategori agar pasti salah satu kode yang sah
        $nama  = trim((string) ($payload['nama'] ?? '')); // Nama usaha dari ketikan form, spasi kiri-kanan dibuang
        $item  = [
            'id'             => 'umkm-' . $ts, // Kolom id: 'umkm-' + waktu detik → unik
            'nama'           => $nama, // 'nama' = kolom JSON (nama usaha)
            'slug'           => self::slugify($nama) . '-' . $ts, // Slug link URL dari nama + waktu → pasti unik
            'usaha'          => trim((string) ($payload['usaha'] ?? '')), // Jenis produk yang dijual
            'kategori'       => $kat, // Simpan kode pendek kategori (misal 'kopi')
            'kategori_label' => self::KATEGORI[$kat] ?? $kat, // Simpan juga label tampilnya (misal 'Kopi & Agrikultur')
            'deskripsi'      => trim((string) ($payload['deskripsi'] ?? '')), // Cerita singkat usaha
            'pemilik'        => trim((string) ($payload['pemilik'] ?? '')), // Nama pemilik usaha
            'dusun'          => trim((string) ($payload['dusun'] ?? '')), // Dusun tempat usaha berada
            'no_wa'          => self::normalizeWa((string) ($payload['no_wa'] ?? '')), // Nomor WhatsApp, dirapikan ke format 62
            'foto'           => trim((string) ($payload['foto'] ?? '')), // Nama file foto usaha yang di-upload
            'status'         => in_array($payload['status'] ?? '', ['aktif', 'nonaktif'], true) // Status hanya boleh 'aktif'/'nonaktif'
                ? $payload['status'] : 'aktif', // Kalau aneh, amankan jadi 'aktif'
            'is_featured'    => !empty($payload['is_featured']), // true = tampil di bagian unggulan (kotak centang di form)
            'created_at'     => date('c'), // Waktu UMKM dibuat
            'updated_at'     => date('c'), // Waktu UMKM diperbarui
        ];
        $items[] = $item; // (4) Tumpuk UMKM baru ke belakang daftar
        self::save($items); // (5) Simpan seluruh daftar ke file JSON
        return $item;
    }

    public static function update(string $id, array $payload): ?array
    {
        $items = self::all(); // (1) Ambil seluruh UMKM
        foreach ($items as &$item) { // (2) Telusuri satu per satu; &$item = perubahan langsung masuk ke daftar
            if (($item['id'] ?? '') !== $id) {
                continue; // Bukan UMKM yang dicari, lewati
            }
            // (3) Ganti kolom yang dikirim dari form saja ($payload = ketikan pengguna)
            if (isset($payload['nama'])) {
                $item['nama'] = trim((string) $payload['nama']);
            }
            if (isset($payload['usaha'])) {
                $item['usaha'] = trim((string) $payload['usaha']);
            }
            if (isset($payload['kategori'])) {
                // Kategori dirapikan, lalu label tampilnya ikut diperbarui agar tidak bertentangan
                $kat = self::normalizeKategori($payload['kategori']);
                $item['kategori'] = $kat;
                $item['kategori_label'] = self::KATEGORI[$kat] ?? $kat;
            }
            if (isset($payload['deskripsi'])) {
                $item['deskripsi'] = trim((string) $payload['deskripsi']);
            }
            if (isset($payload['pemilik'])) {
                $item['pemilik'] = trim((string) $payload['pemilik']);
            }
            if (isset($payload['dusun'])) {
                $item['dusun'] = trim((string) $payload['dusun']);
            }
            if (isset($payload['no_wa'])) {
                $item['no_wa'] = self::normalizeWa((string) $payload['no_wa']); // Nomor WA dirapikan lagi ke format 62
            }
            if (isset($payload['foto'])) {
                $item['foto'] = trim((string) $payload['foto']);
            }
            if (isset($payload['status'])) {
                $item['status'] = in_array($payload['status'], ['aktif', 'nonaktif'], true) // Nilai tidak sah → pertahankan status lama
                    ? $payload['status'] : $item['status'];
            }
            if (array_key_exists('is_featured', $payload)) {
                $item['is_featured'] = !empty($payload['is_featured']); // Kotak centang diubah jadi true/false
            }
            $item['updated_at'] = date('c'); // (4) Catat waktu perubahan
            self::save($items); // (5) Simpan seluruh daftar ke file JSON
            return $item; // Selesai
        }
        unset($item); // Lepaskan "ambil alih" daftar
        return null; // Id tidak ketemu → null
    }

    public static function delete(string $id): bool
    {
        $items = self::all(); // (1) Ambil seluruh UMKM
        $new   = array_values(array_filter($items, static fn($i) => ($i['id'] ?? '') !== $id)); // (2) Buang UMKM yang id-nya sama
        if (count($new) === count($items)) {
            return false; // (3) Jumlah tidak berubah → id tidak ketemu, gagal hapus
        }
        self::save($new); // (4) Simpan daftar tanpa UMKM tersebut
        return true; // Berhasil
    }

    private static function save(array $data): void
    {
        // (1) Salin dulu file lama sebagai cadangan sebelum ditimpa
        self::backup();
        // (2) Pastikan folder tempat file JSON berada sudah ada
        $dir = dirname(self::$file);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        // (3) Buka file untuk ditulis dan kunci khusus menulis
        $fp = fopen(self::$file, 'c');
        flock($fp, LOCK_EX); // Mengunci pintu gudang: satu admin menulis, yang lain menunggu
        ftruncate($fp, 0); // Kosongkan file dulu
        fwrite($fp, json_encode(array_values($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); // Tulis daftar sebagai JSON rapi
        flock($fp, LOCK_UN); // Buka kunci
        fclose($fp);
    }

    private static function backup(): void
    {
        // Kalau file asli belum ada, tidak perlu dicadangkan
        if (!file_exists(self::$file)) {
            return;
        }
        // Buat folder cadangan kalau belum ada
        if (!is_dir(self::$backupDir)) {
            mkdir(self::$backupDir, 0755, true);
        }
        // Salin file lama dengan nama berisi tanggal & jam, misal umkm_2026-08-09_143000.json
        copy(self::$file, self::$backupDir . 'umkm_' . date('Y-m-d_His') . '.json');
    }

    private static function normalizeKategori(string $k): string
    {
        // (1) Buang spasi dan ubah jadi huruf kecil (misal "KULINER" → "kuliner")
        $k = strtolower(trim($k));
        // (2) Kalau kode ini ada di daftar KATEGORI, pakai; kalau tidak, amankan jadi 'jasa'
        return array_key_exists($k, self::KATEGORI) ? $k : 'jasa';
    }

    private static function normalizeWa(string $wa): string
    {
        // (1) Buang semua karakter selain angka (hapus tanda +, -, spasi, dsb)
        $wa = preg_replace('/\D+/', '', $wa) ?? '';
        // (2) Kalau nomor diawali angka 0 (gaya penulisan Indonesia, misal 0812...),
        //     ganti dengan 62 (kode negara Indonesia) agar bisa dipakai aplikasi WhatsApp
        if ($wa !== '' && str_starts_with($wa, '0')) {
            $wa = '62' . substr($wa, 1);
        }
        return $wa; // Contoh: "0812-3456-7890" → "6281234567890"
    }

    private static function slugify(string $text): string
    {
        // Ubah nama usaha menjadi slug: teks link yang aman (misal "Kopi Gunung!" → "kopi-gunung")
        $text = mb_strtolower($text, 'UTF-8'); // (1) Ubah semua huruf jadi kecil agar konsisten
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text) ?? ''; // (2) Buang karakter aneh selain huruf, angka, spasi, strip
        $text = preg_replace('/[\s-]+/', '-', trim($text)) ?? ''; // (3) Ganti spasi beruntun dengan satu strip (-)
        return trim($text, '-'); // (4) Buang strip di paling awal/akhir teks
    }
}
