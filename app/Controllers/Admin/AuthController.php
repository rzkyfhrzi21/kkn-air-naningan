<?php

declare(strict_types=1);

/* ======================================================
   CONTROLLER LOGIN & LOGOUT ADMIN (AUTH CONTROLLER)

   Tugas utama: menjaga pintu masuk panel admin.
   Ibarat satpam yang menjaga lobi kantor desa:

   (1) Kalau pengunjung datang ke alamat /admin/login,
       satpam menyodorkan formulir nama & kata sandi.
   (2) Kalau pengunjung sudah mengisi dan menekan tombol
       "Masuk", isian itu dikirim lewat POST ke sini.
       Ketikan itu hanya DATA DARI FORM — belum tentu benar.
   (3) Satpam mencocokkan ketikan tadi dengan buku daftar
       karyawan asli milik server (file secure/admin_credentials.json).
       Data buku ini DATA DARI FILE — diambil lewat fungsi
       loadCredentials() di bawah.
   (4) Kalau cocok, satpam memberi "tanda pengenal" berupa
       sesi admin, lalu mengantar masuk ke ruang dashboard (/admin).
   (5) Kalau tidak cocok, satpam menolak dengan pesan yang
       sama untuk semua kesalahan (tidak membedakan "username
       salah" atau "password salah") supaya orang iseng tidak
       bisa menebak nama akun satu per satu.

   Halaman yang dilayani:
   - View form login : app/Views/admin/login/index.php
   - Halaman tujuan  : dashboard admin (public/admin/index.php)

   Keamanan ekstra yang dijaga di sini:
   - Token CSRF   : "kartu undangan" sekali pakai, supaya form
                    tidak bisa dipalsukan oleh situs lain.
   - Rate limit   : kalau gagal login lebih dari 10 kali dalam
                    15 menit dari IP yang sama, percobaan berikutnya
                    ditolak sementara (seperti mencatat wajah orang
                    yang berkali-kali salah masuk).
   - HTTPS wajib  : di domain production, koneksi dipaksa lewat
                    HTTPS agar kata sandi tidak bisa dibaca di jalan.
   - Logging      : setiap keberhasilan/kegagalan login dicatat
                    di buku catatan (includes/logger.php).
====================================================== */

/**
 * AuthController — Menangani login dan logout admin.
 *
 * GET  /admin/login  → tampilkan form login
 * POST /admin/login  → proses kredensial
 * GET  /admin/logout → hapus sesi, redirect ke login
 *
 * Kontrol keamanan yang diterapkan (OWASP Top 10 yang relevan):
 *  - A07 (AuthN/AuthZ failure): rate limit berbasis IP di server-side,
 *    pesan error seragam (anti user-enumeration), session regeneration,
 *    session timeout inaktivitas, cookie session secure+httpOnly+SameSite.
 *  - A09 (Logging): pencatatan login_success / login_failed / rate_limited.
 *  - Login-CSRF: POST login dilindungi token CSRF.
 *  - Proteksi abuse input: batasan panjang username & password di server.
 */
final class AuthController
{
    private static string $credentialsFile = __DIR__ . '/../../../secure/admin_credentials.json';   // Buku data akun admin (di luar folder publik → tidak bisa dibuka browser)
    private static string $rateLimitFile   = __DIR__ . '/../../../secure/login_attempts.json';     // Buku catatan percobaan login per alamat IP (anti serangan tebak-tebakan)

    /** Tampilkan halaman login (GET) atau proses login (POST). */
    public static function login(): void
    {
        // (1) Nyalakan sesi PHP — ibarat membuka buku tamu sebelum melayani pengunjung.
        //     Tanpa ini, tanda pengenal (sesi) belum siap dipakai.
        // Pastikan sesi aktif (aman bila dipanggil tanpa router).
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // (2) Kalau pengunjung SUDAH punya tanda pengenal admin di sesi,
        //     berarti dia sudah login sebelumnya — langsung antar ke dashboard.
        // Sudah login → langsung ke dashboard.
        if (!empty($_SESSION['admin'])) {
            self::redirectDashboard();
        }

        // (3) Siapkan "kartu undangan" anti-CSRF: token acak 64 karakter.
        //     Form login akan membawa kartu ini; kalau kartunya tidak cocok,
        //     berarti form tersebut palsu (buatan orang lain / situs iseng).
        // Jaring pengaman anti-CSRF.
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        // (4) Di domain production, koneksi dipaksa HTTPS dulu:
        //     kata sandi tidak boleh dikirim lewat HTTP biasa (ibarat
        //     mengirim surat berisi rahasia tanpa amplop). Kalau belum
        //     HTTPS, langsung pindahkan ke HTTPS dan berhenti di sini.
        $redirectForHttps = self::redirectToHttps();
        if ($redirectForHttps) {
            return; // redirect() sudah mengakhiri proses (exit).
        }

        // Tempat menampung pesan kesalahan untuk ditampilkan di form login.
        $error = '';

        // (5) Ada kiriman dari form login (method POST)? Kalau ya, berarti
        //     pengunjung menekan tombol "Masuk". Isiannya adalah KETIKAN
        //     PENGGUNA DARI FORM HTML, diambil dari variabel $_POST.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // (5a) Cek kartu undangan CSRF yang ikut dikirim form vs yang
            //      tersimpan di sesi. hash_equals membandingkan dua teks
            //      dengan aman (anti tipuan perbedaan waktu eksekusi).
            // ── Anti-CSRF pada form login ──────────────────────────────────
            $token = (string) ($_POST['csrf_token'] ?? '');
            if ($token === '' || !hash_equals($_SESSION['csrf_token'], $token)) {
                $error = 'Token keamanan tidak valid. Muat ulang halaman dan coba lagi.';
            } else {
                // (5b) Cek buku catatan percobaan gagal (rate limit) untuk
                //      alamat IP ini. Kalau IP sudah gagal 10 kali dalam
                //      15 menit, percobaan berikutnya ditolak sementara.
                // ── Rate limit berbasis IP (server-side store) ─────────────
                $ip    = self::clientIp();
                $limit = self::consumeRateLimit($ip);
                if ($limit !== '') {
                    require_once __DIR__ . '/../../../includes/logger.php';
                    writeLog('login_rate_limited', 'Rate limit tercapai untuk IP: ' . $ip);
                    $error = $limit;
                } else {
                    // (5c) Ambil KETIKAN PENGGUNA dari form ($_POST):
                    //      - username : rapikan spasi kiri/kanan (trim).
                    //      - password : dibiarkan apa adanya.
                    //      Keduanya nanti dibatasi panjangnya supaya file
                    //      data tidak dibanjiri tulisan raksasa dari iseng.
                    $username = trim((string) ($_POST['username'] ?? ''));
                    $password = (string) ($_POST['password'] ?? '');

                    // (5d) Batasi panjang input: kalau melebihi batas (64
                    //      karakter username / 256 password), anggap kosong.
                    //      Batas ini harus sinkron dengan atribut maxlength
                    //      yang dipasang di kolom form HTML.
                    // Batasi panjang (harus sinkron dengan atribut maxlength pada form).
                    if (strlen($username) > 64 || strlen($password) > 256) {
                        $username = '';
                        $password = '';
                    } elseif ($username === '' || $password === '') {
                        // Kosong: biarkan lewat ke verifikasi agar data user tidak bocor
                        // (response tetap seragam, tanpa membedakan field mana yang kosong).
                        $username = '';
                        $password = '';
                    }

                    // (5e) Ambil DATA AKUN ADMIN dari FILE JSON (bukan dari
                    //      form): file secure/admin_credentials.json — buku
                    //      data asli milik server, diambil via Model sendiri.
                    //      - username : dibandingkan dengan hash_equals.
                    //      - password : dicocokkan dengan password_verify,
                    //        karena kata sandi tersimpan sebagai hash (tulisan
                    //        acak), bukan teks asli — seperti kunci yang sudah
                    //        diparut, hanya bisa dicocokkan bukan dibaca.
                    // ── Cek kredensial ────────────────────────────────────
                    $creds    = self::loadCredentials();
                    $okUser   = $creds !== null && hash_equals((string) ($creds['username'] ?? ''), $username);
                    $okPass   = $creds !== null && password_verify($password, (string) ($creds['password_hash'] ?? ''));
                    $authed   = $okUser && $okPass;

                    require_once __DIR__ . '/../../../includes/logger.php';
                    if ($authed) {
                        // (6) Kredensial COCOK — buka pintunya:
                        //     (6a) Ganti nomor kartu sesi (session_regenerate_id)
                        //          supaya kartu lama yang mungkin bocor mati.
                        //     (6b) Buat token CSRF baru untuk sesi baru ini.
                        //     (6c) Beri tanda pengenal admin di sesi: nama,
                        //          waktu login, waktu aktivitas terakhir.
                        //          Inilah yang dicek halaman admin lain sebagai
                        //          bukti "orang ini sudah login".
                        // Regenerasi sesi + token CSRF (session fixation / token reuse).
                        $safeUser  = substr($username, 0, 32);
                        session_regenerate_id(true);
                        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                        $_SESSION['admin'] = true;
                        $_SESSION['admin_username'] = $safeUser;
                        $_SESSION['admin_nama_lengkap'] = (string) ($creds['nama_lengkap'] ?? '');
                        $_SESSION['login_at'] = time();
                        $_SESSION['admin_last_activity'] = time();
                        self::clearRateLimit($ip);

                        // (6d) Catat keberhasilan di buku log, hapus catatan
                        //      percobaan gagal IP ini, lalu antar ke dashboard.
                        writeLog('login_success', 'Login berhasil untuk username: ' . $safeUser);
                        self::redirectDashboard();
                    } else {
                        // (7) Kredensial TIDAK cocok: beri pesan yang sama
                        //     untuk semua kemungkinan kesalahan (anti tebak
                        //     akun), lalu catat kegagalannya di buku log.
                        writeLog('login_failed', 'Percobaan login gagal untuk username: ' . ($username !== '' ? $username : '(kosong)'));
                        $error = 'Username atau kata sandi salah.';
                    }
                }
            }
        }

        // (8) Siapkan bahan untuk form login:
        //     - $loginLogo : alamat gambar logo (folder /assets/images/logo.jpg).
        //     - $csrfToken : kartu undangan CSRF yang tadi dibuat — form HTML
        //       akan menyelipkannya agar lolos pemeriksaan langkah (5a).
        //     - noStoreHeaders() : larang browser/proxy menyimpan halaman ini.
        //     Lalu render View login supaya pengunjung melihat formulirnya.
        $loginLogo   = self::loginLogo();
        $csrfToken   = (string) ($_SESSION['csrf_token'] ?? '');
        self::noStoreHeaders();
        require __DIR__ . '/../../Views/admin/login/index.php';
    }

    /** Hapus sesi dan redirect ke halaman login. */
    public static function logout(): void
    {
        // (1) Catat dulu di buku log bahwa admin keluar (jejak audit).
        require_once __DIR__ . '/../../../includes/logger.php';
        writeLog('logout', 'Admin keluar dari panel.');

        // (2) Kosongkan semua isi sesi — tanda pengenal admin dihapus,
        //     ibarat merobek kartu identitas sebelum meninggalkan kantor.
        $_SESSION = [];
        // (3) Kalau sesi memakai cookie, bunuh juga cookie-nya di browser
        //     (beri tanggal kedaluwarsa di masa lalu) supaya kartu lama
        //     tidak tersisa di perangkat.
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $p['path'], $p['domain'], $p['secure'], $p['httponly']
            );
        }
        // (4) Hancurkan sesi yang masih aktif di sisi server.
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }

        // (5) Larang halaman disimpan di cache, lalu arahkan pengunjung
        //     kembali ke form login.
        self::noStoreHeaders();
        $base = defined('APP_BASE') ? APP_BASE : '';
        header('Location: ' . $base . '/admin/login');
        exit;
    }

    /** Load dan decode kredensial dari file JSON. */
    private static function loadCredentials(): ?array
    {
        // Membaca buku data akun admin (secure/admin_credentials.json):
        // (1) Cek dulu apakah file-nya ada; kalau tidak ada, lapor kosong (null).
        $file = self::$credentialsFile;
        if (!file_exists($file)) {
            return null;
        }
        // (2) Buka file untuk dibaca bersama (LOCK_SH = kunci baca ringan,
        //     mencegah file terbaca saat sedang ditulis pihak lain).
        $fp = fopen($file, 'r');
        if ($fp === false) {
            return null;
        }
        flock($fp, LOCK_SH);
        $json = stream_get_contents($fp);
        flock($fp, LOCK_UN);
        fclose($fp);

        // (3) Terjemahkan isi file JSON menjadi array PHP.
        //     INGAT: ini DATA DARI FILE (buku asli), bukan ketikan form.
        $data = json_decode($json ?: '{}', true);
        return is_array($data) ? $data : null;
    }

    /**
     * Logo halaman login — wajib sama dengan logo yang dipakai di situs
     * (header publik & sidebar admin): /assets/images/logo.jpg.
     * Sumber tunggal agar tidak terjadi perbedaan gambar antara login dan situs.
     */
    private static function loginLogo(): string
    {
        return '/assets/images/logo.jpg';   // Alamat logo — wajib sama dengan logo di header situs & sidebar admin
    }

    /**
     * IP klien, mempertimbangkan proxy terpercaya (X-Forwarded-For)
     * hanya bila indikator proxy ada; default REMOTE_ADDR.
     */
    private static function clientIp(): string
    {
        // Mencari tahu alamat IP pengunjung untuk dicatat di buku rate limit:
        // - Biasanya diambil dari REMOTE_ADDR (alamat langsung dari browser).
        // - Kalau situs berada di belakang proxy/penengah, alamat asli ada di
        //   X-Forwarded-For (ambil bagian paling kiri, setelah dirapikan).
        $xff = trim((string) ($_SERVER['HTTP_X_FORWARDED_FOR'] ?? ''));
        if ($xff !== '' && strtolower((string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')) !== '') {
            $parts = explode(',', $xff);
            $first = trim((string) ($parts[0] ?? ''));
            if ($first !== '' && filter_var($first, FILTER_VALIDATE_IP)) {
                return $first;
            }
        }
        return (string) ($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
    }

    /**
     * Rate limit IP: maksimum $max per $window detik.  Catat percobaan
     * pada file JSON di /secure (server-side), bersihkan entri lama.
     */
    private static function consumeRateLimit(string $ip): string
    {
        // Mencatat satu percobaan login untuk IP ini pada file
        // secure/login_attempts.json: kalau dalam jendela 15 menit sudah
        // lebih dari 10 percobaan, permintaan berikutnya ditolak.
        $window = 900;    // 15 menit
        $maxTry = 10;     // maks 10 percobaan per window per IP

        // (1) Ambil catatan lama dari file (kalau belum ada, mulai kosong).
        $now  = time();
        $data = [];
        if (file_exists(self::$rateLimitFile)) {
            $raw  = (string) file_get_contents(self::$rateLimitFile);
            $data = json_decode($raw, true);
            if (!is_array($data)) {
                $data = [];
            }
        }

        // (2) Ambil daftar waktu percobaan milik IP ini, buang yang sudah
        //     lewat 15 menit (tidak dihitung lagi), lalu tambahkan percobaan ini.
        $bucket = $data[$ip] ?? [];
        $bucket = array_values(array_filter(
            (array) $bucket,
            static fn($ts): bool => $now - (int) $ts < $window
        ));
        $bucket[] = $now;
        $data[$ip] = $bucket;

        // (3) Simpan kembali catatan ke file, lalu tentukan hasilnya.
        self::saveRateLimit($data);

        // (4) Kalau jumlah percobaan melewati batas, kembalikan pesan
        //     larangan; kalau masih aman, kembalikan teks kosong (boleh lanjut).
        if (count($bucket) > $maxTry) {
            return 'Terlalu banyak percobaan login. Silakan tunggu beberapa menit lalu coba lagi.';
        }
        return '';
    }

    private static function clearRateLimit(string $ip): void
    {
        // Setelah login BERHASIL, hapus catatan percobaan IP ini dari file,
        // agar hitungan gagal "diskon" dari awal lagi untuk kunjungan berikutnya.
        $data = [];
        if (file_exists(self::$rateLimitFile)) {
            $data = json_decode((string) file_get_contents(self::$rateLimitFile), true);
            if (!is_array($data)) {
                $data = [];
            }
        }
        unset($data[$ip]);
        self::saveRateLimit($data);
    }

    private static function saveRateLimit(array $data): void
    {
        // Menulis catatan rate limit kembali ke file:
        // (1) Pastikan folder secure/ ada (buat kalau belum).
        $dir = dirname(self::$rateLimitFile);
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        // (2) Buka file dengan kunci tulis (LOCK_EX) supaya tidak ada dua
        //     proses menulis bersamaan, kosongkan, lalu tulis JSON rapi.
        $fp = @fopen(self::$rateLimitFile, 'c');
        if ($fp === false) {
            return;
        }
        flock($fp, LOCK_EX);
        ftruncate($fp, 0);
        fwrite($fp, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        flock($fp, LOCK_UN);
        fclose($fp);
    }

    /** Redirect HTTP → HTTPS hanya untuk domain production (kanonis SEO). */
    private static function redirectToHttps(): bool
    {
        // (1) Kalau koneksi sudah HTTPS (langsung atau lewat proxy),
        //     tidak perlu pindah-pindah — langsung saja lanjut (false).
        $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
        if ($isSecure) {
            return false;
        }
        // (2) Kalau belum HTTPS, cek dulu apakah domainnya production
        //     (pekon-air-naningan.web.id). Di komputer pengembangan lokal
        //     HTTP tetap dibolehkan — tidak ada data penting di sana.
        $host = strtolower((string) ($_SERVER['HTTP_HOST'] ?? ''));
        $isProd = ($host === 'pekon-air-naningan.web.id' || str_ends_with($host, '.pekon-air-naningan.web.id'));
        if (!$isProd) {
            return false; // biarkan HTTP untuk pengembangan lokal / staging.
        }
        // (3) Domain production & belum HTTPS: pindahkan ke HTTPS dengan
        //     kode 301 (pindah permanen — juga bagus untuk SEO).
        header('Location: https://' . $host . (string) ($_SERVER['REQUEST_URI'] ?? '/'), true, 301);
        exit;
    }

    /** Cache-control agar halaman login tidak disimpan oleh proxy/browser. */
    private static function noStoreHeaders(): void
    {
        // Melarang browser & server penengah menyimpan halaman ini di cache:
        // halaman login berisi data pribadi, tidak boleh tertinggal di perangkat.
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
    }

    /** Redirect ke dashboard admin. */
    private static function redirectDashboard(): void
    {
        // Mengarahkan admin ke halaman dashboard (/admin) — seperti satpam
        // menunjuk arah ke ruang kerja setelah tanda pengenal terverifikasi.
        $base = defined('APP_BASE') ? APP_BASE : '';
        header('Location: ' . $base . '/admin');
        exit;
    }
}