<?php

declare(strict_types=1);

/* ======================================================
   MODEL PESAN (KOTAK MASUK DESA)

   File ini bertugas mengelola pesan yang dikirim pengunjung
   website (lewat halaman kontak) kepada admin desa.

   Analoginya: Model ini ibarat petugas kotak surat desa.
   Setiap surat dari warga masuk ke satu kotak (file JSON),
   lalu petugas ini yang menata, menandai sudah dibaca/belum,
   dan membuang surat yang tidak diperlukan.

   File JSON yang dikelola:
   - public/data/pesan.json  → seluruh pesan masuk
   - secure/backup/          → folder cadangan (salinan lama
                               sebelum file ditimpa)

   Fungsi yang tersedia:
   - all()    : mengambil seluruh pesan (terbaru di atas)
   - find()   : mencari 1 pesan berdasarkan id
   - create() : menyimpan pesan baru dari pengunjung
   - update() : memperbarui pesan (menandai sudah dibaca)
   - delete() : menghapus pesan
   - save()   : menulis seluruh data ke file JSON
   - backup() : menyalin file lama sebagai cadangan
   ====================================================== */

class Pesan
{
    private static string $file      = __DIR__ . '/../../public/data/pesan.json'; // Lokasi file JSON tempat seluruh pesan masuk disimpan
    private static string $backupDir = __DIR__ . '/../../secure/backup/'; // Folder tempat salinan cadangan file lama disimpan sebelum ditimpa

    // Daftar kategori pesan yang sah: kode pendek di kiri (disimpan di JSON),
    // dan label panjang di kanan (ditampilkan di layar admin)
    public const KATEGORI = [
        'info'      => 'Permintaan Informasi',
        'layanan'   => 'Layanan Administrasi',
        'pengaduan' => 'Pengaduan Masyarakat',
        'saran'     => 'Kritik & Saran',
        'lainnya'   => 'Lainnya',
    ];

    public static function all(): array
    {
        // (1) Kalau file JSON belum ada, tidak ada pesan masuk
        if (!file_exists(self::$file)) {
            return [];
        }
        // (2) Buka file untuk dibaca; kalau gagal, anggap kosong
        $fp = fopen(self::$file, 'r');
        if ($fp === false) {
            return [];
        }
        flock($fp, LOCK_SH); // Kunci "baca": boleh dibaca bersama, asal tidak sedang ada yang menulis
        $data = json_decode(stream_get_contents($fp) ?: '[]', true) ?? []; // Ubah teks JSON menjadi array pesan
        flock($fp, LOCK_UN); // Selesai membaca, buka kuncinya
        fclose($fp);
        // Kalau hasilnya bukan array (file rusak), anggap kosong
        if (!is_array($data)) {
            return [];
        }
        // Urutkan dari pesan terbaru ke terlama berdasarkan waktu dibuat (created_at)
        usort($data, static fn($a, $b) => strcmp((string) ($b['created_at'] ?? ''), (string) ($a['created_at'] ?? '')));
        return $data;
    }

    public static function find(string $id): ?array
    {
        // Periksa satu per satu; begitu id cocok, kembalikan pesan itu
        foreach (self::all() as $item) {
            if (($item['id'] ?? '') === $id) {
                return $item;
            }
        }
        return null; // Tidak ketemu → null
    }

    public static function create(array $payload): array
    {
        // $payload = ketikan pengunjung di form kontak website
        $items = self::all(); // (1) Ambil semua pesan yang sudah ada
        $ts    = time(); // (2) Catat detik saat ini untuk membuat id unik
        $kat   = trim((string) ($payload['kategori'] ?? 'lainnya')); // (3) Ambil kategori pilihan pengunjung
        // Kalau kategori yang dikirim tidak ada di daftar sah, amankan jadi 'lainnya'
        if (!array_key_exists($kat, self::KATEGORI)) {
            $kat = 'lainnya';
        }
        $item = [
            'id'         => 'pesan-' . $ts, // Kolom id: 'pesan-' + waktu detik → unik
            'nama'       => trim((string) ($payload['nama'] ?? '')), // 'nama' = kolom JSON, isinya dari ketikan form
            'kontak'     => trim((string) ($payload['kontak'] ?? '')), // Nomor HP/email pengirim
            'kategori'   => $kat, // Simpan kode pendek kategori (misal 'saran')
            'kategori_label' => self::KATEGORI[$kat], // Simpan juga label tampilnya (misal 'Kritik & Saran')
            'pesan'      => trim((string) ($payload['pesan'] ?? '')), // Isi surat dari pengunjung
            'is_read'    => false, // Pesan baru selalu berstatus "belum dibaca" (false)
            'is_archived' => false, // Pesan baru selalu di kotak masuk, belum diarsipkan
            'created_at' => date('c'), // Waktu pesan masuk
        ];
        $items[] = $item; // (4) Tumpuk pesan baru ke belakang daftar
        self::save($items); // (5) Simpan seluruh daftar ke file JSON
        return $item;
    }

    public static function update(string $id, array $payload): ?array
    {
        $items = self::all(); // (1) Ambil seluruh pesan
        foreach ($items as &$item) { // (2) Telusuri satu per satu; &$item = perubahan langsung masuk ke daftar
            if (($item['id'] ?? '') !== $id) {
                continue; // Bukan pesan yang dicari, lewati
            }
            // (3) Hal yang bisa diubah dari pesan: tanda "sudah dibaca" dan status arsip
            if (array_key_exists('is_read', $payload)) {
                $item['is_read'] = (bool) $payload['is_read']; // true = sudah dibaca admin, false = belum
            }
            if (array_key_exists('is_archived', $payload)) {
                $item['is_archived'] = (bool) $payload['is_archived']; // true = diarsipkan, false = di kotak masuk
            }
            self::save($items); // (4) Simpan seluruh daftar ke file JSON
            return $item; // Selesai
        }
        unset($item); // Lepaskan "ambil alih" daftar
        return null; // Id tidak ketemu → null
    }

    public static function delete(string $id): bool
    {
        $items = self::all(); // (1) Ambil seluruh pesan
        $new = array_values(array_filter($items, static fn($i) => ($i['id'] ?? '') !== $id)); // (2) Buang pesan yang id-nya sama
        if (count($new) === count($items)) {
            return false; // (3) Jumlah tidak berubah → id tidak ketemu, gagal hapus
        }
        self::save($new); // (4) Simpan daftar tanpa pesan tersebut
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
        // Salin file lama dengan nama berisi tanggal & jam, misal pesan_2026-08-09_143000.json
        copy(self::$file, self::$backupDir . 'pesan_' . date('Y-m-d_His') . '.json');
    }
}
