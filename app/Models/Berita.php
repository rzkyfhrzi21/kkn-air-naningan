<?php

declare(strict_types=1);

/* ======================================================
   MODEL BERITA (ARTIKEL DESA)

   File ini bertugas mengelola seluruh berita/artikel desa, mulai
   dari menyimpan berita baru, mencari berita, sampai menghapusnya.

   Analoginya: Model ini ibarat petugas gudang arsip koran desa.
   Semua koran (berita) ditumpuk rapi di satu lemari (file JSON).
   Petugas ini yang tahu cara menyusun, mencari, dan menata ulang
   tumpukan koran tersebut sesuai aturan yang diminta.

   File JSON yang dikelola:
   - public/data/berita.json  → seluruh data berita desa
   - secure/backup/           → folder cadangan (salinan lama sebelum
                                file ditimpa)

   Fungsi yang tersedia:
   - all()        : mengambil SEMUA berita, sudah diurutkan
   - published()  : mengambil hanya berita berstatus "terbit"
   - isPublished(): mengecek apakah sebuah berita layak tampil
   - isScheduled(): mengecek apakah berita dijadwalkan terbit nanti
   - find()       : mencari 1 berita berdasarkan id
   - findBySlug() : mencari 1 berita berdasarkan slug (link URL)
   - latest()     : mengambil beberapa berita terbit terbaru
   - create()     : menambah berita baru
   - update()     : mengubah isi berita yang sudah ada
   - delete()     : menghapus berita
   ====================================================== */

/**
 * Model Berita — baca/tulis public/data/berita.json
 * API: all() / find() / findBySlug() / create() / update() / delete()
 */
class Berita
{
    private static string $file      = __DIR__ . '/../../public/data/berita.json'; // Lokasi file JSON tempat seluruh data berita desa disimpan
    private static string $backupDir = __DIR__ . '/../../secure/backup/'; // Folder tempat salinan cadangan file lama disimpan sebelum ditimpa

    // ── Read ─────────────────────────────────────────────────────────────────

    public static function all(): array
    {
        // Kalau file JSON belum ada, tidak ada berita sama sekali
        if (!file_exists(self::$file)) return [];
        $fp   = fopen(self::$file, 'r'); // Buka file untuk dibaca
        flock($fp, LOCK_SH); // Kunci "baca": boleh dibaca bersama, asal tidak sedang ada yang menulis
        $data = json_decode(file_get_contents(self::$file), true) ?? []; // Ubah teks JSON menjadi array berita
        flock($fp, LOCK_UN); // Selesai membaca, buka kuncinya
        fclose($fp);
        // Urutkan: terbit dulu, lalu by tanggal_terbit DESC
        usort($data, static function (array $a, array $b): int {
            $statusCmp = strcmp($a['status'] ?? '', $b['status'] ?? ''); // Bandingkan status kedua berita (misal 'terbit' vs 'draft')
            if ($statusCmp !== 0) {
                return ($a['status'] ?? '') === 'terbit' ? -1 : 1; // Berita berstatus "terbit" diletakkan lebih dulu di atas
            }
            return strcmp($b['tanggal_terbit'] ?? '', $a['tanggal_terbit'] ?? ''); // Status sama → urutkan dari tanggal terbit terbaru
        });
        return $data;
    }

    /** Ambil hanya berita yang sudah berstatus terbit dan tanggal publikasinya tiba. */
    public static function published(): array
    {
        // Saring semua berita: hanya yang isPublished() bernilai benar yang dipertahankan;
        // array_values() merapikan ulang nomor urut array setelah penyaringan
        return array_values(
            array_filter(self::all(), static fn(array $b): bool => self::isPublished($b))
        );
    }

    public static function isPublished(array $item, ?DateTimeImmutable $today = null): bool
    {
        // (1) Berita yang statusnya bukan "terbit" (misal masih "draft") tidak boleh tampil
        if (($item['status'] ?? '') !== 'terbit') {
            return false;
        }

        // (2) Ubah tanggal terbit (teks, misal "2026-08-09") menjadi objek tanggal; kalau formatnya salah, anggap belum layak tampil
        $publishedAt = DateTimeImmutable::createFromFormat('!Y-m-d', (string) ($item['tanggal_terbit'] ?? ''));
        if ($publishedAt === false) {
            return false;
        }

        // (3) Berita dianggap tampil kalau tanggal terbitnya sudah lewat atau hari ini
        return $publishedAt <= ($today ?? new DateTimeImmutable('today'));
    }

    public static function isScheduled(array $item, ?DateTimeImmutable $today = null): bool
    {
        // Sama seperti isPublished, tetapi kebalikannya:
        // berita ini sudah "terbit" statusnya tapi tanggalnya MASIH nanti → berarti terjadwal
        if (($item['status'] ?? '') !== 'terbit') {
            return false;
        }

        $publishedAt = DateTimeImmutable::createFromFormat('!Y-m-d', (string) ($item['tanggal_terbit'] ?? ''));
        return $publishedAt !== false && $publishedAt > ($today ?? new DateTimeImmutable('today'));
    }

    public static function find(string $id): ?array
    {
        // Periksa semua berita satu per satu; begitu ketemu id yang sama, langsung kembalikan
        foreach (self::all() as $item) {
            if (($item['id'] ?? '') === $id) return $item;
        }
        return null; // Tidak ketemu → kembalikan "tidak ada data" (null)
    }

    public static function findBySlug(string $slug): ?array
    {
        // Sama seperti find(), tetapi mencocokkan kolom 'slug'
        // (slug = versi judul yang aman dipakai di link URL, misal "panen-kopi-2026")
        foreach (self::all() as $item) {
            if (($item['slug'] ?? '') === $slug) return $item;
        }
        return null;
    }

    /** Berita terbit terbaru, limit n item */
    public static function latest(int $limit = 5): array
    {
        // Ambil daftar berita yang sudah layak tampil, lalu potong dari depan sebanyak $limit buah
        return array_slice(self::published(), 0, $limit);
    }

    // ── Write ─────────────────────────────────────────────────────────────────

    public static function create(array $payload): array
    {
        // $payload = data berita yang diketik pengguna di form admin
        $items  = self::all(); // (1) Ambil semua berita yang sudah ada
        $ts     = time(); // (2) Catat detik saat ini (dipakai sebagai bahan pembuat id unik)
        $suffix = bin2hex(random_bytes(4)); // (3) Buat angka acak rahasia agar id & slug tidak pernah kembar
        $slugBase = self::slugify($payload['judul'] ?? ''); // Ubah judul menjadi slug ramah-URL
        $slug   = ($slugBase !== '' ? $slugBase : 'berita') . '-' . $ts . '-' . $suffix; // Gabung slug + waktu + angka acak, pasti unik
        $item   = [
            'id'             => 'berita-' . $ts . '-' . $suffix, // Kolom id: id unik berita ini
            'judul'          => trim($payload['judul'] ?? ''), // 'judul' = kolom JSON; isinya dari ketikan form, spasi kiri-kanan dibuang
            'slug'           => $slug,
            'kategori'       => trim($payload['kategori'] ?? ''),
            'ringkasan'      => trim($payload['ringkasan'] ?? ''),
            'konten'         => self::sanitizeHtml($payload['konten'] ?? ''), // Isi artikel — dipbersihkan dulu dari tag HTML berbahaya
            'foto_sampul'    => trim($payload['foto_sampul'] ?? ''),
            'penulis'        => trim($payload['penulis'] ?? 'Admin Desa'), // Kalau tidak diisi, otomatis tertulis "Admin Desa"
            'status'         => in_array($payload['status'] ?? '', ['draft', 'terbit'], true) // Status hanya boleh 'draft' atau 'terbit'
                                    ? $payload['status'] : 'draft', // Kalau isinya aneh, amankan jadi 'draft'
            'tanggal_terbit' => trim($payload['tanggal_terbit'] ?? date('Y-m-d')), // Kalau tidak diisi, pakai tanggal hari ini
            'tags'           => array_filter(array_map('trim', (array)($payload['tags'] ?? []))), // Kata kunci berita; spasi tiap tag dibuang, yang kosong dibuang
            'created_at'     => date('c'), // Catatan waktu berita dibuat
            'updated_at'     => date('c'), // Catatan waktu berita diperbarui (sama saat baru dibuat)
        ];
        $items[] = $item; // (4) Tumpuk berita baru ke belakang daftar berita lama
        self::save($items); // (5) Simpan seluruh daftar ke file JSON
        return $item; // Kembalikan berita yang barusan dibuat
    }

    public static function update(string $id, array $payload): ?array
    {
        $items = self::all(); // (1) Ambil seluruh berita
        foreach ($items as &$item) { // (2) Telusuri satu per satu; &$item = "ambil alih" agar perubahan langsung masuk ke daftar
            if (($item['id'] ?? '') !== $id) continue; // Bukan berita yang dicari, lewati
            // (3) Ganti kolom hanya yang dikirim dari form ($payload = ketikan pengguna)
            if (isset($payload['judul'])) {
                $newTitle = trim($payload['judul']);
                // Kalau judul berubah, slug ikut dibuat ulang (diambil 8 karakter pertama dari kode unik id
                // agar slug tetap stabil walaupun ada berita lain berjudul sama)
                if ($newTitle !== ($item['judul'] ?? '')) {
                    $item['slug'] = self::slugify($newTitle) . '-' . substr(hash('sha256', $id), 0, 8);
                }
                $item['judul'] = $newTitle;
            }
            if (isset($payload['kategori']))       $item['kategori']       = trim($payload['kategori']);
            if (isset($payload['ringkasan']))      $item['ringkasan']      = trim($payload['ringkasan']);
            if (isset($payload['konten']))         $item['konten']         = self::sanitizeHtml($payload['konten']); // Isi artikel juga disterilkan
            if (isset($payload['foto_sampul']))    $item['foto_sampul']    = trim($payload['foto_sampul']);
            if (isset($payload['penulis']))        $item['penulis']        = trim($payload['penulis']);
            if (isset($payload['status']))         $item['status']         = in_array($payload['status'], ['draft','terbit'], true) ? $payload['status'] : $item['status']; // Status hanya diubah kalau nilainya sah
            if (isset($payload['tanggal_terbit'])) $item['tanggal_terbit'] = trim($payload['tanggal_terbit']);
            if (isset($payload['tags']))           $item['tags']           = array_filter(array_map('trim', (array)$payload['tags']));
            $item['updated_at'] = date('c'); // (4) Perbarui catatan waktu perubahan
            self::save($items); // (5) Simpan seluruh daftar ke file JSON
            return $item; // Berita sudah diubah, langsung selesai
        }
        unset($item); // Lepaskan "ambil alih" agar tidak membahayakan daftar asli
        return null; // Id tidak ketemu → berita tidak ada, kembalikan null
    }

    public static function delete(string $id): bool
    {
        $items = self::all(); // (1) Ambil seluruh berita
        // (2) Saring: buang semua berita yang id-nya sama dengan yang diminta hapus
        $new   = array_values(array_filter($items, static fn($i): bool => ($i['id'] ?? '') !== $id));
        if (count($new) === count($items)) return false; // (3) Kalau jumlahnya tidak berubah, berarti id tidak ketemu → gagal hapus
        self::save($new); // (4) Simpan daftar tanpa berita yang dihapus
        return true; // Berhasil dihapus
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private static function save(array $data): void
    {
        // (1) Salin dulu file lama ke folder cadangan, sebelum ditimpa
        self::backup();
        // (2) Pastikan folder tempat file JSON berada sudah ada; kalau belum, buat
        if (!is_dir(dirname(self::$file))) {
            mkdir(dirname(self::$file), 0755, true);
        }
        // (3) Buka file untuk ditulis, kunci khusus menulis agar tidak ada dua admin menulis bersamaan
        $fp = fopen(self::$file, 'c');
        flock($fp, LOCK_EX); // Mengunci pintu gudang: satu orang menulis, yang lain menunggu
        ftruncate($fp, 0); // Kosongkan file dulu
        fwrite($fp, json_encode(array_values($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); // Tulis daftar sebagai JSON rapi
        flock($fp, LOCK_UN); // Buka kunci
        fclose($fp);
    }

    private static function backup(): void
    {
        // Kalau file asli belum ada, tidak perlu dicadangkan
        if (!file_exists(self::$file)) return;
        // Buat folder cadangan kalau belum ada
        if (!is_dir(self::$backupDir)) mkdir(self::$backupDir, 0755, true);
        // Salin file lama dengan nama berisi tanggal & jam, misal berita_2026-08-09_143000.json
        copy(self::$file, self::$backupDir . 'berita_' . date('Y-m-d_His') . '.json');
    }

    private static function slugify(string $text): string
    {
        // Ubah judul menjadi slug: teks link yang aman (misal "Panen Kopi!" → "panen-kopi")
        $text = mb_strtolower($text, 'UTF-8'); // (1) Ubah semua huruf jadi kecil (huruf besar/kecil ikut dihilangkan agar konsisten)
        // (2) Ganti huruf beraksen (à, é, ñ, dst) dengan huruf biasa agar aman di URL
        $map  = ['à'=>'a','á'=>'a','â'=>'a','ã'=>'a','ä'=>'a','å'=>'a',
                  'è'=>'e','é'=>'e','ê'=>'e','ë'=>'e','ì'=>'i','í'=>'i',
                  'î'=>'i','ï'=>'i','ò'=>'o','ó'=>'o','ô'=>'o','õ'=>'o',
                  'ö'=>'o','ù'=>'u','ú'=>'u','û'=>'u','ü'=>'u','ý'=>'y',
                  'ñ'=>'n','ç'=>'c'];
        $text = strtr($text, $map);
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text); // (3) Buang semua karakter aneh selain huruf, angka, spasi, dan tanda strip (-)
        $text = preg_replace('/[\s-]+/', '-', trim($text)); // (4) Ganti spasi atau strip beruntun dengan satu strip (-) saja
        return trim($text, '-'); // (5) Buang strip di paling awal/akhir teks
    }

    /** Strip tags; izinkan subset HTML aman untuk konten artikel */
    private static function sanitizeHtml(string $html): string
    {
        // Hanya tag HTML "ramah" yang diizinkan (paragraf, tebal, miring, link, gambar, dsb);
        // semua tag lain (termasuk <script> berbahaya) dibuang agar konten aman
        $allowed = '<p><br><b><strong><i><em><u><h2><h3><h4><ul><ol><li><a><blockquote><img><figure><figcaption>';
        return strip_tags($html, $allowed);
    }
}
