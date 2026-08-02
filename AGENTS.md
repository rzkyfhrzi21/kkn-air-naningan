---
name: ai-master-rules
description: >
  File induk aturan AI untuk proyek.
  Seluruh skill telah dipecah ke file masing-masing di folder @agents/AI-SKILLS/
  agar lebih modular dan mudah di-maintain.
  Bagian PROYEK di bawah berisi aturan spesifik situs Pekon Air Naningan.
---

# DAFTAR ATURAN & SKILL BACA AI

Berikut adalah aturan prioritas pembacaan file yang wajib dipatuhi oleh AI saat memulai sesi atau melakukan pengerjaan.

## 1. Aturan Pembacaan Folder Skill (`@agents/AI-SKILLS/`)

AI **wajib** membaca semua file skill di folder `@agents/AI-SKILLS/` dan subfoldernya. Berikut adalah fungsi garis besar dari tiap folder _skill_ yang tersedia:

- **📁 PERSONA_SKILLS**: Kumpulan persona dan gaya komunikasi AI.
  - `CAVEMAN_SKILL.md`: Mode komunikasi sangat singkat dan padat (hemat token).
  - `PONYTAIL_SKILL.md`: Mode "Senior Dev Malas", memprioritaskan solusi paling simpel, bersih, dan menolak _over-engineering_ (YAGNI).
  - `KOMENTAR_ORANG_TUA.md`: (Pengecualian) Jangan dibaca/diterapkan kecuali diminta secara eksplisit oleh pengguna.

- **📁 TOOL_SKILLS**: Panduan integrasi dan penggunaan alat (tools/MCP) pendukung.
  - `CODEBASE_MEMORY.md`: Wajib digunakan di awal untuk memahami struktur _repository_ secara menyeluruh.
  - `CONTEXT7.md`: Digunakan untuk mencari dokumentasi resmi teknologi utama proyek.
  - `FIRECRAWL.md`: Digunakan untuk _web scraping_ atau mencari dokumentasi dari sumber luar.
  - `RTK_SKILL.md`: Panduan inisialisasi Repository Toolkit (RTK) demi optimasi token terminal.
  - `BROWSER_USE_SKILL.md`: Panduan penggunaan library `browser-use` untuk otomasi browser berbasis AI — form filling, scraping, QA testing, dan web agent dengan berbagai LLM provider.
  - `GRAPHIFY_SKILL.md`: Panduan penggunaan `graphify` untuk membuat Knowledge Graph multimodal dari kode, PDF, diagram, dan catatan proyek (beserta dokumentasi teknis lengkap di folder `TOOL_SKILLS/graphify/`).

- **📁 WORKFLOW_SKILLS**: Aturan standar operasional, alur kerja, dan lingkungan kerja AI.
  - `AGENTS-GENERAL.md` / `WORKFLOW_MODE.md` / `AI_SETUP_MACHINE.md`: Aturan terkait alur kerja AI, penyiapan lingkungan (_environment_), dan persona.
  - `CICD_SSH_SKILL.md`: Prosedur wajib untuk setup otomatis CI/CD menggunakan SSH Rsync dan Github Actions.

- **📁 SUPERPOWERS**:
  - `SUPERPOWERS_SKILLS.md`: Kumpulan _skill_ modular untuk alur kerja seperti _brainstorming_, penulisan _plan_, dan _debugging_.

- **📁 AGENT_SKILLS**: (Dari repo `agent-skills`)
  - Kumpulan _skill_ engineering (TDD, CI/CD, Code Review, SDD, Debugging, Refactoring, dsb). Diambil dari koleksi Addy Osmani untuk menstandarkan siklus hidup rekayasa perangkat lunak (_software engineering lifecycle_) AI.

- **📁 TASTE_SKILLS**: (Dari repo `taste-skill`)
  - Kumpulan _skill_ (seperti `brandkit`, `minimalist-skill`, dll) untuk merancang antarmuka (UI/UX) yang premium, dinamis, dan terhindar dari gaya bawaan yang kaku (_anti-slop_). **Untuk proyek ini, section anti-slop di bagian PROYEK (§P.8) berlaku sebagai lapisan tambahan yang lebih spesifik — bukan pengganti.**

- **📁 ECC_SKILLS**: (Dari repo `ecc`)
  - Kumpulan masif ratusan _skill_ modular (mulai dari _frontend_, _backend_, _security_, hingga DevOps) yang bertindak sebagai _Agent Harness Operating System_. File dipisah per-topik agar AI dapat membaca yang paling relevan dengan tumpukan teknologi proyek yang sedang dikerjakan.

- **📁 IMPECCABLE_SKILLS**: (Dari repo `impeccable`)
  - _Skill_ sistem desain (_design tokens_, tipografi, spasi, dsb) untuk meningkatkan kualitas dan konsistensi antarmuka (UI) dari agen AI serta mencegah keluaran desain _slop_.

- **📁 UIUX_SKILLS**: (Dari repo `ui-ux-pro-max-skill`)
  - Koleksi skill UI/UX lengkap dengan database 84 gaya visual, 192 palet warna, 74 font pairing, 98 panduan UX, dan 16 GSAP motion preset. Mencakup: `ui-ux-pro-max` (skill utama), `design` (identitas brand, logo, banner, ikon, social media), `design-system` (token arsitektur tiga-layer), `ui-styling` (shadcn/ui + Tailwind), `brand` (brand voice & visual identity), `banner-design` (22 gaya banner multi-platform), `slides` (presentasi HTML + Chart.js).

## 2. Aturan Pembacaan Dokumen Panduan (`docs/`)

AI **wajib** membaca panduan dari file dan subfolder di dalam `docs/` berikut:

- `docs/BRIEF.txt`
- `docs/CANVAS.md`
- `docs/PRD.md`
- `docs/DESIGN.md`
- Folder `docs/BRIEF KOMPONENT/` (Semua file di dalamnya)
- Folder `docs/BRIEF MODUL/` (Semua file di dalamnya)

**DILARANG KERAS** membaca subfolder berikut di dalam `docs/`:

1. `docs/BRIEF SAAT DEPLOY/` (Folder brief saat deploy - JANGAN DIBACA)
2. `docs/PROJECT LAIN (CONTOH)/` (Folder project lain - JANGAN DIBACA)
3. `docs/DATA KLIEN/` (Folder data klien - JANGAN DIBACA)

## 3. Aturan Pembacaan README Utama

- **DILARANG KERAS** membaca file `README.md` yang berada di root project karena itu hanya panduan untuk instalasi & deployment saja (JANGAN DIBACA). Gunakan dokumen panduan `docs/` yang diperbolehkan di atas untuk referensi teknis.

---

# INISIALISASI PROYEK BARU (AUTO-SETUP)

Jika AI mendeteksi bahwa ini adalah proyek atau _workspace_ baru, AI **wajib** secara proaktif menjalankan (atau mengingatkan pengguna untuk menjalankan) langkah-langkah inisialisasi _tools_ berikut:

1. **RTK (Repository Toolkit):** Jalankan perintah `./@agents/RTK/rtk.exe init` (atau sesuaikan path rtk) di terminal untuk menghasilkan folder `.rtk` (berisi konfigurasi `filters.toml`) dan folder `rules` (berisi aturan `antigravity-rtk-rules.md`).
2. **Context7:** Pastikan integrasi sudah terpasang dengan menjalankan perintah `npx ctx7 setup` untuk autentikasi dan pembuatan _rules_ otomatis di dalam agen.
3. **Codebase Memory MCP:** Lakukan pemetaan struktur proyek secara otomatis. Jika server MCP sudah tersambung, AI harus segera memindai (melakukan aksi _"Index this project"_) agar grafik pengetahuan kode terbentuk di memori.
4. **Firecrawl MCP:** Ingatkan pengguna untuk memastikan variabel lingkungan `FIRECRAWL_API_KEY` sudah terpasang jika proyek membutuhkan fitur pencarian web/_scraping_ lanjutan.
5. **Graphify:** Jika proyek membutuhkan pemetaan pengetahuan multimodal (dokumen, diagram, riset, & kode), jalankan perintah `graphify .` di terminal root proyek untuk menghasilkan folder `graphify-out/`.

---

# MANAJEMEN FILE/SCRIPT SEMENTARA

- Simpan semua file eksekusi, script sementara (temporary scripts), atau file uji coba/scratch di dalam folder `Zzz/` yang ada di root proyek.
- DILARANG mengotori root directory atau folder lain dengan script sekali pakai.
- Berlaku juga untuk script test endpoint PHP (`save.php`/`upload.php`/AJAX endpoint) dan file percobaan styling — taruh di `Zzz/`, bukan di `/app` atau `/public`.

---

# ENVIRONMENT TERMINAL USER

- User menggunakan **CMD (Command Prompt) mode Administrator**, BUKAN PowerShell.
- AI **wajib** memberikan perintah dalam sintaks CMD, bukan PowerShell.
- Contoh perbedaan:
  - PowerShell: `New-Item`, `Remove-Item`, `Move-Item`
  - CMD: `mkdir`, `del`, `move`, `rmdir /s /q`
- Berlaku juga untuk semua perintah proyek ini (PHP, git, dsb) — pastikan sintaks kompatibel CMD saat memberi instruksi ke user.

---

# ATURAN REFACTORING & MODIFIKASI KODE LAMA

- **DILARANG KERAS** mengubah, merombak (refactor), atau menulis ulang seluruh kode pada file yang sudah ada secara tiba-tiba hanya karena membaca aturan di `AGENTS.md` atau file skill lainnya.
- Aturan penulisan kode **hanya berlaku** untuk kode baru yang sedang ditulis atau fitur baru yang sedang ditambahkan.
- Pengecualian: AI hanya diizinkan melakukan refactor pada kode lama **JIKA DAN HANYA JIKA** pengguna secara eksplisit menginstruksikan atau meminta refactor tersebut.

**Peringatan (setelah baca standar ini):**

- Standar di atas **bukan** undangan untuk rename/refactor file yang sudah ada.
- Kalau file lama belum ikut standar (nama beda, logic di page, modal legacy, dll.) — **biarkan**. Hanya file/fitur **baru** yang wajib ikut.
- Jangan sentuh kode existing kecuali user **minta eksplisit**.

---

---

# BAGIAN PROYEK: SITUS PEKON AIR NANINGAN

## P.0 Konteks Proyek

- **Pola:** MVC PHP native (ditulis manual, tanpa framework).
- **Backend:** PHP 8.3.19.
- **Data:** file JSON flat, bukan database.
- **CSS:** Tailwind CSS Browser CDN v4 (`https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4`) dipakai langsung, termasuk di production; tidak ada proses build Tailwind lokal.
- **Keputusan production:** Penggunaan Browser CDN adalah keputusan eksplisit pemilik proyek meskipun dokumentasi resmi Tailwind merekomendasikannya untuk development. Halaman wajib memiliki koneksi internet dan kebijakan CSP harus mengizinkan domain CDN tersebut.
- **JS interaktif:** Bootstrap 5 `bootstrap.bundle.min.js` disimpan lokal, CSS Bootstrap TIDAK PERNAH dipakai.
- **Admin:** 1 akun tunggal, tanpa role bertingkat.
- **Data production:** Semua data yang tersedia wajib digunakan dan ikut dipasang ke production, termasuk data dummy. Jangan menghapus, menyembunyikan, atau menggantinya dengan empty state hanya karena belum terverifikasi sebagai data final.
- **Referensi wajib:** `docs/PRD.md` dan `docs/DESIGN.md`. Jangan menyimpang dari keduanya tanpa konfirmasi eksplisit pemilik repo.

## P.1 Struktur Folder (MVC)

```
/app
  /Models
    SiteData.php               → agregasi data JSON untuk beranda dan dashboard
    Umkm.php, Wisata.php, ...  → 1 class per entitas, baca/tulis JSON, TIDAK boleh output HTML
  /Controllers
    HomeController.php         → menyiapkan data view beranda publik
    Admin/DashboardController.php → menyiapkan ringkasan dashboard admin
  /Views
    /components                → komponen view reusable (modal, toast, card, dan sejenisnya)
    /partials                  → partial reusable lintas area bila diperlukan
    /public
      /partials/header.php, footer.php
      /home/index.php          → view beranda publik
    /admin
      /partials/header.php, footer.php
      /dashboard/index.php     → view dashboard admin
/public                     → WEB ROOT
  index.php                  → entry point beranda, memanggil HomeController
  umkm.php, wisata.php, berita.php, potensi.php, galeri.php, kontak.php, profil.php
  /admin/index.php           → entry point dashboard, memanggil DashboardController
  /admin/*.php               → shell halaman admin lain (render struktur + tabel kosong, isi via AJAX)
  /admin/ajax/*.php          → endpoint AJAX, SEMUA menerima POST, balas JSON (list-*, store-*, delete-*, get-*)
  /assets
    /css                     → CSS tambahan bila benar-benar diperlukan; Tailwind tetap dari Browser CDN
    /js                      → JavaScript aplikasi
      /vendor                → library JavaScript lokal, termasuk bootstrap.bundle.min.js
    /images                  → gambar statis milik antarmuka
    /fonts                   → font lokal bila digunakan
  /uploads
  /data                      → seluruh data JSON aplikasi, termasuk data dummy yang dipakai di production
/config                      → konfigurasi internal PHP; tidak boleh berisi output HTML
/includes                    → bootstrap/helper PHP reusable; bukan komponen tampilan
/secure
  admin_credentials.json     → HANYA di sini, TIDAK PERNAH di /public
Zzz/
```

Setiap partial `<head>` wajib memuat Browser CDN berikut sebelum class Tailwind dirender:

```html
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
```

**Prinsip MVC keras:** Controller tidak boleh langsung `json_decode(file_get_contents(...))` — itu tanggung jawab Model. View tidak boleh berisi logic bisnis (validasi, hitung, dsb) — hanya render data yang sudah disiapkan Controller.

## P.2 Model Pattern (JSON hari ini, DB besok tanpa rombak Controller/View)

```php
// app/Models/Umkm.php
class Umkm
{
    private static string $file = __DIR__ . '/../../public/data/umkm.json';
    private static string $backupDir = __DIR__ . '/../../secure/backup/';

    public static function all(): array
    {
        if (!file_exists(self::$file)) return [];
        $fp = fopen(self::$file, 'r');
        flock($fp, LOCK_SH);
        $data = json_decode(file_get_contents(self::$file), true) ?? [];
        flock($fp, LOCK_UN);
        fclose($fp);
        return $data;
    }

    public static function find(string $id): ?array
    {
        foreach (self::all() as $item) if ($item['id'] === $id) return $item;
        return null;
    }

    public static function create(array $payload): array { /* validasi, generate id (slug/timestamp), append, save() */ }
    public static function update(string $id, array $payload): array { /* validasi, replace, save() */ }
    public static function delete(string $id): bool { /* filter out, save() */ }

    private static function save(array $data): void
    {
        self::backup();
        $fp = fopen(self::$file, 'c');
        flock($fp, LOCK_EX);
        ftruncate($fp, 0);
        fwrite($fp, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        flock($fp, LOCK_UN);
        fclose($fp);
    }

    private static function backup(): void
    {
        if (!file_exists(self::$file)) return;
        copy(self::$file, self::$backupDir . 'umkm_' . date('Y-m-d_His') . '.json');
    }
}
```

Buat 1 Model per entitas dengan bentuk method sama: `all() / find() / create() / update() / delete()`. Semua write pakai `flock` + backup otomatis sebelum overwrite (lihat §P.4 poin 6).

## P.3 Controller & AJAX Pattern

**Halaman publik** (`public/umkm.php`) — Controller tipis, render langsung:

```php
require_once __DIR__ . '/../app/Models/Umkm.php';
$items = Umkm::all(); // render awal WAJIB berisi data lengkap — lihat docs/PRD.md §0.2 poin 5
require __DIR__ . '/../app/Views/public/umkm.php';
```

**Endpoint AJAX admin** (`public/admin/ajax/list-umkm.php`) — WAJIB terima POST, balas JSON, dukung pagination/filter/search dari body POST:

```php
session_start();
if (!isset($_SESSION['admin'])) { http_response_code(401); echo json_encode(['success'=>false,'message'=>'Sesi habis, silakan login ulang.']); exit; }
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit; }

$page = (int)($_POST['page'] ?? 1);
$search = trim($_POST['search'] ?? '');
$kategori = trim($_POST['kategori'] ?? '');
$perPage = 10;

$items = Umkm::all();
if ($search !== '') $items = array_filter($items, fn($i) => stripos($i['nama'], $search) !== false);
if ($kategori !== '') $items = array_filter($items, fn($i) => $i['kategori'] === $kategori);
$items = array_values($items);

$total = count($items);
$paged = array_slice($items, ($page - 1) * $perPage, $perPage);

echo json_encode([
    'success' => true,
    'data' => $paged,
    'page' => $page,
    'has_next' => ($page * $perPage) < $total,
    'has_prev' => $page > 1,
]);
```

**Endpoint write** (`store-umkm.php`, `delete-umkm.php`) — validasi server wajib, response WAJIB berisi `message` yang detail untuk ditampilkan di toast (lihat `docs/DESIGN.md` §2 Toast):

```php
if (empty($_POST['nama'])) {
    echo json_encode(['success' => false, 'message' => 'Nama usaha wajib diisi.']);
    exit;
}
// ... validasi lain (no_wa harus diawali 62, dll)
$item = Umkm::create($_POST);
echo json_encode(['success' => true, 'message' => "UMKM '{$item['nama']}' berhasil ditambahkan."]);
```

## P.4 Aturan Interaksi UI (WAJIB, ringkas — detail lengkap di `docs/PRD.md` §0.2 dan `docs/DESIGN.md` §2)

1. Tambah/Edit/Hapus = Modal (Bootstrap JS + Tailwind style). Hasil = Toast dengan pesan detail dari server, bukan teks generik hardcode di JS.
2. Upload: foto maks 2MB (`jpg,jpeg,png,gif,webp,ico,heic,heif`), video maks 15MB (`mp4,mkv,mov,webm`). Validasi MIME asli via `finfo_file`, bukan ekstensi/`accept` attribute saja. **Catatan risiko wajib ditangani, bukan diabaikan:** HEIC/HEIF tidak native-render di banyak browser desktop, MKV tidak konsisten di tag `<video>` — putuskan strategi (convert via GD/Imagick saat upload, atau tolak format itu di validasi) sebelum form upload selesai dibangun, jangan biarkan file ke-upload lalu ternyata tidak tampil tanpa peringatan ke admin.
3. Semua foto/video (publik & admin) klik → Modal preview, tombol × wajib ada.
4. Carousel (kalau dipakai): `data-bs-interval="1500"`, tombol prev/next selalu visible, pause-on-hover TIDAK boleh dimatikan (mitigasi kecepatan slide yang di atas rekomendasi WCAG 2.2.2).
5. Tabel: **admin = full AJAX POST** (list/filter/search/pagination Prev-Next, lazy spinner, aksi sebagai icon button di kolom terakhir). **Publik = render PHP penuh di request pertama** (SEO), AJAX hanya enhancement filter/search di atasnya — TIDAK BOLEH publik bergantung AJAX untuk render awal.
6. Loading state: data teks → spinner. Foto/video → skeleton (dimensi sama dengan media asli, cegah layout shift).
7. Setiap endpoint write (`store-*`, `delete-*`) WAJIB `flock` + backup versi lama sebelum overwrite (lihat §P.2), whitelist entity, CSRF token, autentikasi session.
8. HTTPS wajib untuk form login dan semua endpoint AJAX yang kirim data admin.
9. `upload_max_filesize`/`post_max_size` PHP harus disetel ≥20MB — cek dulu sebelum form upload video dianggap selesai, jangan asumsikan default hosting sudah cukup.

## P.5 SEO

Server-rendered by default menyelesaikan masalah crawler/Open Graph untuk **halaman publik** — TAPI ini hanya berlaku selama aturan P.4 poin 5 dipatuhi (render awal publik tidak boleh kosong menunggu AJAX). Tambahan wajib: meta tag per halaman (title/description/OG image spesifik per konten), `sitemap.xml` (boleh digenerate dinamis oleh PHP saat diakses, baca dari JSON — tidak perlu build-time seperti rencana React sebelumnya), `robots.txt` (disallow `/admin` dan `/public/admin/ajax`), structured data JSON-LD dasar, alt text wajib di semua foto, URL bersih via `.htaccess` rewrite kalau mod_rewrite tersedia (fallback: query param slug, tetap valid untuk SEO).

## P.6 Keamanan

Semua endpoint AJAX write: autentikasi session PHP, CSRF token, whitelist entity/action, validasi & sanitasi server-side, `flock` + backup (lihat P.2, P.4). Upload: validasi MIME asli, rename file ke nama acak/hash, matikan eksekusi PHP di folder `/uploads` via `.htaccess` (`php_flag engine off`). Password admin: `password_hash()`, rate limit percobaan login, session timeout. Kredensial admin (`admin_credentials.json`) di `/secure`, di luar folder yang bisa di-fetch/akses publik. Matikan `display_errors` di production.

## P.7 Konvensi Kode

- Model/Controller: PascalCase (`Umkm.php`, `UmkmController.php`).
- View partial & file publik: kebab-case atau snake_case sesuai konvensi PHP umum (`umkm.php`, `modal-preview.php`).
- String ke user: Bahasa Indonesia. Kode/komentar/nama variabel: Inggris.
- Styling: 100% Tailwind class + token `docs/DESIGN.md`, tidak ada inline style, tidak ada hex hardcode di luar token.
- Commit kecil per fitur.

## P.8 Anti AI-Slop pada Tampilan (fitur tetap lengkap, tampilan tidak generik)

Lapisan tambahan di atas `TASTE_SKILLS`/`IMPECCABLE_SKILLS` (§1) — skill generik tidak tahu token spesifik proyek ini.

1. Dilarang gradient di luar token — depth pakai layering `--surface`/`--surface-2`, bukan gradient baru.
2. Icon hanya kalau nambah makna, bukan dekorasi kosong per item list otomatis.
3. Dilarang copy filler generik ("Kami berkomitmen...", "Selamat datang di website resmi..."). Gunakan semua data yang tersedia, termasuk data dummy, sebagai konten production. Hanya tampilkan empty state jika sumber data benar-benar kosong.
4. Satu pola card per jenis konten — konsisten sesama UMKM, konsisten sesama Wisata, boleh beda struktur antar jenis kalau memang kebutuhan info beda.
5. Tidak ada checkmark-bullet-list generik ala template SaaS.
6. Animasi dibatasi ke yang didefinisikan (`reveal-on-scroll`, bar-chart fill, skeleton pulse, spinner) — jangan tambah hover-scale/glow/parallax dekoratif tanpa fungsi.
7. Jangan default semua section center-aligned simetris — pertahankan asimetri hero asli.
8. Panjang kalimat/paragraf jangan seragam-rata, tulis natural.
9. Empty state jujur + CTA jelas, bukan ilustrasi generik stok.
10. Komponen baru wajib dicek ke `docs/DESIGN.md` dulu — kalau tidak ada, update `docs/DESIGN.md` dulu (dengan alasan eksplisit) baru dipakai.
11. **Tambahan khusus stack ini:** modal/toast/carousel Bootstrap JANGAN dibiarkan tampil dengan gaya default Bootstrap walau CSS-nya sengaja tidak di-load — pastikan benar-benar di-restyle Tailwind (lihat `docs/DESIGN.md` §2), bukan unstyled/rusak.

## P.9 Larangan untuk Agent

- Jangan taruh kredensial di folder publik.
- Jangan bangun sistem role/multi-user.
- Jangan skip validasi server-side dengan alasan "sudah divalidasi di HTML5/JS".
- Jangan menambahkan proses build Tailwind lokal; gunakan Tailwind CSS Browser CDN v4 yang ditetapkan di P.0 dan P.1, termasuk untuk production.
- Jangan load `bootstrap.min.css` dalam bentuk apa pun.
- Jangan biarkan Controller baca/tulis JSON langsung — wajib lewat Model.
- Jangan biarkan halaman publik bergantung AJAX untuk render awal (lihat P.4 poin 5, P.5).
- Jangan terima upload HEIC/MKV tanpa strategi jelas (convert atau tolak) — lihat P.4 poin 2.
