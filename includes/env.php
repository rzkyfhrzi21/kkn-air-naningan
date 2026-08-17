<?php

declare(strict_types=1);

/*
 * ======================================================
 * PEMBACA FILE PENGATURAN (.env)
 *
 * File ini ibarat petugas arsip yang bertugas membuka lemari
 * penyimpanan rahasia (.env) tempat disimpannya pengaturan
 * penting situs — misalnya alamat utama situs (APP_URL).
 *
 * Pengaturan ini tidak ditulis langsung di kode, melainkan
 * di file khusus .env yang tidak boleh diakses publik.
 * Tugas file ini:
 * - `env($kunci, $cadangan)`  : "Ambil isi satu pengaturan;
 *                               kalau kosong, pakai cadangan."
 * - `loadEnv($path)`          : "Buka file .env, baca baris
 *                               per baris, lalu ingat semuanya."
 *
 * Fungsi-fungsi di sini hanya dibuat sekali (jika belum ada)
 * supaya tidak bentrok kalau dipanggil dari banyak halaman.
 * Catatan: `declare(strict_types=1)` membuat PHP ketat soal
 * tipe data, seperti petugas arsip yang selalu menulis label
 * dengan teliti.
 * ======================================================
 */

/**
 * includes/env.php — loader .env tanpa dependency.
 * API: loadEnv(string $path) / env(string $key, ?string $default = null)
 *
 * Dipanggil dari index.php (front controller) dan partial header
 * sebagai jaring pengaman untuk akses langsung file shell .php.
 */

// Cek dulu: apakah fungsi env() sudah dibuat di tempat lain? Jika belum, barulah dibuat di sini
if (!function_exists('env')) {
    // Fungsi untuk mengambil satu pengaturan: "tolong ambilkan nilai untuk kunci ini"
    function env(string $key, ?string $default = null): ?string
    {
        $value = getenv($key); // Cari nilainya dari daftar pengaturan yang sudah dimuat
        return ($value === false || $value === '') ? $default : $value; // Jika kosong/tidak ada, kembalikan nilai cadangan
    }
}

// Cek dulu: apakah fungsi loadEnv() sudah ada? Jika belum, barulah dibuat
if (!function_exists('loadEnv')) {
    // Fungsi untuk membaca file .env: "buka file ini, baca semua pengaturannya, lalu simpan di ingatan"
    function loadEnv(string $path): void
    {
        static $loaded = false; // Tanda "sudah pernah dibaca" — agar file tidak dibaca berulang-ulang
        if ($loaded || !is_file($path)) { // Jika sudah dibaca sebelumnya, atau filenya tidak ada, langsung berhenti
            return;
        }
        $loaded = true; // Tandai bahwa file sudah dibaca

        // (1) Baca seluruh baris file, buang baris kosong
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) { // (1) Jika file tidak bisa dibaca, berhenti
            return;
        }

        // (2) Telusuri setiap baris satu per satu
        foreach ($lines as $line) {
            $line = trim($line);                              // (2) Buang spasi kosong di kiri-kanan baris
            if ($line === '' || str_starts_with($line, '#')) { // (2) Jika baris kosong atau diawali tanda # (keterangan), lewati
                continue;
            }

            // (3) Pecah baris menjadi [kunci] = [nilai] (misal: APP_URL = https://...)
            [$key, $value] = array_pad(explode('=', $line, 2), 2, '');
            $key   = trim($key);   // (3) Bersihkan nama kuncinya
            $value = trim($value); // (3) Bersihkan nilai isinya

            if ($key === '') { // (3) Jika nama kunci kosong (baris tidak beraturan), lewati
                continue;
            }

            // (4) Jika nilai diapit tanda kutip ("..." atau '...'), buang tanda kutipnya
            $length = strlen($value);
            if ($length >= 2) {
                $first = $value[0];
                $last  = $value[$length - 1];
                if (($first === '"' && $last === '"') || ($first === "'" && $last === "'")) { // (4) Cek: kutip di awal & akhir?
                    $value = substr($value, 1, -1); // (4) Buang kutip pertama & terakhir
                }
            }

            // (5) Simpan pengaturan ke ingatan PHP (putenv + $_ENV) agar bisa diambil oleh env()
            putenv($key . '=' . $value); // (5) Simpan ke daftar pengaturan sistem
            $_ENV[$key] = $value;        // (5) Simpan juga ke tabel pengaturan PHP
        }
    }
}
