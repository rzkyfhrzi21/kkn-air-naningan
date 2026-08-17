<?php

declare(strict_types=1);

/* ======================================================
   MODEL GALERI (FOTO & VIDEO DESA)

   File ini bertugas mengelola koleksi foto dan video desa yang
   ditampilkan di halaman galeri website.

   Analoginya: Model ini ibarat petugas album foto desa. Semua
   foto dan video ditata dalam satu kotak album (file JSON).
   Petugas ini yang tahu cara memasukkan, mengeluarkan, dan
   menata urutan album tersebut.

   File JSON yang dikelola:
   - public/data/galeri.json  → seluruh data foto/video galeri
   - secure/backup/           → folder cadangan (salinan lama
                                sebelum file ditimpa)

   Fungsi yang tersedia:
   - all()          : mengambil seluruh galeri (sudah diurutkan)
   - find()         : mencari 1 item berdasarkan id
   - create()       : menambah foto/video baru
   - update()       : mengubah item yang sudah ada
   - delete()       : menghapus item
   - normalizeKat() : merapikan kategori agar selalu sah
   - save()         : menulis seluruh data ke file JSON
   - backup()       : menyalin file lama sebagai cadangan
   ====================================================== */

class Galeri
{
    private static string $file      = __DIR__ . '/../../public/data/galeri.json'; // Lokasi file JSON tempat seluruh data galeri disimpan
    private static string $backupDir = __DIR__ . '/../../secure/backup/'; // Folder tempat salinan cadangan file lama disimpan sebelum ditimpa

    // Daftar kategori galeri yang sah: kode pendek di kiri (disimpan di JSON),
    // dan label panjang di kanan (ditampilkan ke pengunjung website)
    public const KATEGORI = [
        'alam'         => 'Alam & Wisata',
        'kegiatan'     => 'Kegiatan Desa',
        'budaya'       => 'Budaya',
        'pembangunan'  => 'Pembangunan',
    ];

    public static function all(): array
    {
        // (1) Kalau file JSON belum ada, tidak ada galeri sama sekali
        if (!file_exists(self::$file)) {
            return [];
        }
        // (2) Buka file untuk dibaca; kalau gagal, anggap kosong
        $fp = fopen(self::$file, 'r');
        if ($fp === false) {
            return [];
        }
        flock($fp, LOCK_SH); // Kunci "baca": boleh dibaca bersama, asal tidak sedang ada yang menulis
        $data = json_decode(stream_get_contents($fp) ?: '[]', true) ?? []; // Ubah teks JSON menjadi array galeri
        flock($fp, LOCK_UN); // Selesai membaca, buka kuncinya
        fclose($fp);
        // Kalau hasilnya bukan array (file rusak), anggap kosong
        if (!is_array($data)) {
            return [];
        }
        // Urutkan galeri berdasarkan kolom 'urutan' dari angka kecil ke besar
        // (misal urutan 1 tampil lebih dulu, lalu 2, 3, dst)
        usort($data, static fn($a, $b) => ((int) ($a['urutan'] ?? 0)) <=> ((int) ($b['urutan'] ?? 0)));
        return $data;
    }

    public static function find(string $id): ?array
    {
        // Periksa satu per satu; begitu id cocok, kembalikan item itu
        foreach (self::all() as $item) {
            if (($item['id'] ?? '') === $id) {
                return $item;
            }
        }
        return null; // Tidak ketemu → null (tidak ada data)
    }

    public static function create(array $payload): array
    {
        // $payload = data yang diketik/dipilih pengguna di form admin
        $items = self::all(); // (1) Ambil galeri yang sudah ada
        $ts    = time(); // (2) Catat detik saat ini untuk membuat id unik
        $kat   = self::normalizeKat($payload['kategori'] ?? ''); // (3) Rapikan kategori agar pasti salah satu kode yang sah
        $item  = [
            'id'             => 'galeri-' . $ts, // Kolom id: 'galeri-' + waktu detik → unik
            'judul'          => trim((string) ($payload['judul'] ?? '')), // 'judul' = kolom JSON, isinya dari ketikan form
            'deskripsi'      => trim((string) ($payload['deskripsi'] ?? '')),
            'kategori'       => $kat, // Simpan kode pendek (misal 'alam')
            'kategori_label' => self::KATEGORI[$kat] ?? $kat, // Simpan juga label tampilnya (misal 'Alam & Wisata')
            'tipe'           => in_array($payload['tipe'] ?? '', ['foto', 'video'], true) ? $payload['tipe'] : 'foto', // Hanya boleh 'foto' atau 'video'; selain itu dipaksa 'foto'
            'file'           => trim((string) ($payload['file'] ?? '')), // Nama file gambar/video yang sudah di-upload
            'rasio'          => trim((string) ($payload['rasio'] ?? '100%')), // Perbandingan lebar/tinggi tampilan (default 100% = kotak)
            'urutan'         => (int) ($payload['urutan'] ?? (count($items) + 1)), // Posisi tampil; kalau tidak diisi, taruh di urutan paling akhir
            'created_at'     => date('c'), // Waktu item dibuat
            'updated_at'     => date('c'), // Waktu item diperbarui
        ];
        $items[] = $item; // (4) Tambahkan item baru ke belakang daftar
        self::save($items); // (5) Simpan seluruh daftar ke file JSON
        return $item;
    }

    public static function update(string $id, array $payload): ?array
    {
        $items = self::all(); // (1) Ambil seluruh galeri
        foreach ($items as &$item) { // (2) Telusuri satu per satu; &$item = perubahan langsung masuk ke daftar
            if (($item['id'] ?? '') !== $id) {
                continue; // Bukan item yang dicari, lewati
            }
            // (3) Ganti kolom yang dikirim dari form saja ($payload = ketikan pengguna)
            if (isset($payload['judul'])) {
                $item['judul'] = trim((string) $payload['judul']);
            }
            if (isset($payload['deskripsi'])) {
                $item['deskripsi'] = trim((string) $payload['deskripsi']);
            }
            if (isset($payload['kategori'])) {
                // Kategori dirapikan, lalu label tampilnya ikut diperbarui agar tidak bertentangan
                $kat = self::normalizeKat($payload['kategori']);
                $item['kategori'] = $kat;
                $item['kategori_label'] = self::KATEGORI[$kat] ?? $kat;
            }
            if (isset($payload['tipe'])) {
                $item['tipe'] = in_array($payload['tipe'], ['foto', 'video'], true) ? $payload['tipe'] : $item['tipe']; // Nilai tidak sah → pertahankan tipe lama
            }
            if (isset($payload['file'])) {
                $item['file'] = trim((string) $payload['file']);
            }
            if (isset($payload['rasio'])) {
                $item['rasio'] = trim((string) $payload['rasio']);
            }
            if (isset($payload['urutan'])) {
                $item['urutan'] = (int) $payload['urutan'];
            }
            $item['updated_at'] = date('c'); // (4) Catat waktu perubahan
            self::save($items); // (5) Simpan seluruh daftar ke file JSON
            return $item; // Selesai, kembalikan item yang sudah diubah
        }
        unset($item); // Lepaskan "ambil alih" daftar
        return null; // Id tidak ketemu → null
    }

    public static function delete(string $id): bool
    {
        $items = self::all(); // (1) Ambil seluruh galeri
        $new = array_values(array_filter($items, static fn($i) => ($i['id'] ?? '') !== $id)); // (2) Buang item yang id-nya sama
        if (count($new) === count($items)) {
            return false; // (3) Jumlah tidak berubah → id tidak ketemu, gagal hapus
        }
        self::save($new); // (4) Simpan daftar tanpa item tersebut
        return true; // Berhasil
    }

    private static function normalizeKat(string $k): string
    {
        // (1) Buang spasi dan ubah jadi huruf kecil (misal "ALAM" → "alam")
        $k = strtolower(trim($k));
        // (2) Kalau kode ini ada di daftar KATEGORI, pakai; kalau tidak, amankan jadi 'kegiatan'
        return array_key_exists($k, self::KATEGORI) ? $k : 'kegiatan';
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
        // Salin file lama dengan nama berisi tanggal & jam, misal galeri_2026-08-09_143000.json
        copy(self::$file, self::$backupDir . 'galeri_' . date('Y-m-d_His') . '.json');
    }
}
