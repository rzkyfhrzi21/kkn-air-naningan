<?php

declare(strict_types=1);

/* ======================================================
   MODEL AKUN (DATA ADMIN)

   File ini bertugas mengelola data akun admin desa, seperti
   nama pengguna (username), password (dalam bentuk kode rahasia
   yang disebut hash), nama lengkap, WhatsApp, email, dan foto.

   Analoginya: Model ini ibarat petugas arsip yang memegang kunci
   lemari khusus. Hanya dia yang boleh membuka lemari, melihat isinya,
   dan menimpa isinya dengan versi baru. Orang lain (halaman web)
   hanya bisa minta dibukakan atau diperbarui lewat dia.

   File JSON yang dikelola:
   - secure/admin_credentials.json  → data akun admin
   - secure/backup/                 → folder cadangan (salinan lama
                                       sebelum file ditimpa, agar tidak
                                       hilang selamanya)

   Fungsi yang tersedia:
   - get()    : mengambil (membaca) seluruh data admin dari file JSON
   - update() : memperbarui data admin sesuai ketikan form
   - backup() : menyalin file lama ke folder cadangan sebelum ditimpa
   ====================================================== */

/**
 * Model Akun — baca/tulis secure/admin_credentials.json
 * API: get() / update()
 */
class Akun
{
    private static string $file      = __DIR__ . '/../../secure/admin_credentials.json'; // Lokasi file JSON tempat data akun admin disimpan (di folder secure agar tidak bisa diakses publik)
    private static string $backupDir = __DIR__ . '/../../secure/backup/'; // Folder tempat salinan cadangan file lama disimpan sebelum ditimpa

    public static function get(): array
    {
        // (1) Cek dulu apakah file JSON-nya ada; kalau belum pernah dibuat, anggap saja datanya kosong
        if (!file_exists(self::$file)) {
            return [];
        }
        // (2) Buka file untuk dibaca ("r" = read); kalau gagal dibuka, kembalikan data kosong
        $fp = fopen(self::$file, 'r');
        if ($fp === false) {
            return [];
        }
        flock($fp, LOCK_SH); // Kunci "baca" (SH = shared): boleh dibaca oleh banyak orang bersamaan, asal tidak ada yang sedang menulis
        $data = json_decode(stream_get_contents($fp) ?: '{}', true) ?? []; // Ubah isi file (teks JSON) menjadi array agar mudah dipakai PHP
        flock($fp, LOCK_UN); // Buka kuncinya, proses membaca selesai
        fclose($fp);
        return is_array($data) ? $data : []; // Pastikan hasilnya array; kalau rusak, anggap kosong
    }

    public static function update(array $payload): array
    {
        // $payload = data yang dikirim dari form di halaman admin (hasil ketikan pengguna di layar)
        $current = self::get(); // (1) Ambil dulu data akun yang sudah tersimpan di file JSON

        // (2) Timpa satu per satu hanya kolom yang memang dikirim dari form —
        //     'username', 'nama_lengkap', dll adalah nama kolom yang disimpan di JSON
        if (array_key_exists('username', $payload)) {
            $current['username'] = trim((string) $payload['username']); // trim() membuang spasi kosong di kiri-kanan hasil ketikan
        }
        if (array_key_exists('nama_lengkap', $payload)) {
            $current['nama_lengkap'] = trim((string) $payload['nama_lengkap']);
        }
        if (array_key_exists('whatsapp', $payload)) {
            $current['whatsapp'] = trim((string) $payload['whatsapp']);
        }
        if (array_key_exists('email', $payload)) {
            $current['email'] = trim((string) $payload['email']);
        }
        if (array_key_exists('foto', $payload)) {
            $current['foto'] = trim((string) $payload['foto']);
        }
        // Password khusus: hanya diganti kalau form mengirim kode hash yang tidak kosong
        // (kalau kolom password dikosongkan, berarti admin tidak ingin mengganti passwordnya)
        if (isset($payload['password_hash']) && $payload['password_hash'] !== '') {
            $current['password_hash'] = (string) $payload['password_hash'];
        }
        // (3) Catat waktu sekarang sebagai tanggal terakhir data diperbarui (format tanggal internasional)
        $current['updated_at'] = date('c');

        // (4) Salin dulu versi lama ke folder cadangan, siapa tahu suatu saat perlu dikembalikan
        self::backup();
        // (5) Buka file untuk ditulis ("c" = buat/ciptakan bila belum ada)
        $fp = fopen(self::$file, 'c');
        if ($fp === false) {
            throw new RuntimeException('Gagal membuka file kredensial untuk ditulis.');
        }
        flock($fp, LOCK_EX); // Kunci "tulis" (EX = exclusive): mengunci pintu gudang agar tidak ada dua admin menulis bersamaan
        ftruncate($fp, 0); // Kosongkan isi file dulu sebelum menulis versi baru
        fwrite($fp, json_encode($current, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); // Tulis data sebagai JSON rapi; huruf seperti "é" atau emoji tidak dirusak
        flock($fp, LOCK_UN); // Buka kunci, proses menulis selesai
        fclose($fp);

        return $current; // Kembalikan data yang sudah diperbarui agar bisa dipakai halaman web
    }

    private static function backup(): void
    {
        // Kalau file aslinya belum ada, tidak ada yang perlu dicadangkan
        if (!file_exists(self::$file)) {
            return;
        }
        // Kalau folder cadangan belum ada, buat dulu
        if (!is_dir(self::$backupDir)) {
            mkdir(self::$backupDir, 0755, true);
        }
        // Salin file lama dengan nama berisi tanggal & jam,
        // contoh: admin_credentials_2026-08-09_143000.json (unik, tidak menimpa backup sebelumnya)
        copy(self::$file, self::$backupDir . 'admin_credentials_' . date('Y-m-d_His') . '.json');
    }
}
