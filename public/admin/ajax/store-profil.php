<?php

declare(strict_types=1);

/* ======================================================
   ENDPOINT AJAX: SIMPAN PROFIL PEKON (FORM KELOLA PROFIL)

   File ini ibarat "petugas pembaru buku induk desa":
   saat admin mengubah visi-misi, sejarah, peta Google Maps, demografi dusun,
   mata pencaharian, atau aparatur pekon pada halaman Kelola Profil, file ini dipanggil via AJAX.

   Alur kerjanya:
   (1) Memeriksa login admin & token keamanan CSRF.
   (2) Validasi input bidang wajib: Visi, Misi (minimal 1).
   (3) Sanitasi paragraf sejarah (sanitize_rich_html) & validasi URL embed Google Maps.
   (4) Menghitung persentase mata pencaharian warga (wajib tepat 100%).
   (5) Menangani upload foto aparatur desa ke `uploads/struktur/` (maks 2MB, format JPG/PNG/WebP/GIF).
   (6) Memperbarui file JSON `public/data/profil.json` via Model Profil.
   (7) Mengirimkan balasan JSON berpesan rinci untuk notifikasi toast.
====================================================== */

header('Content-Type: application/json; charset=utf-8');

// (1) Cek Sesi Admin & Metode POST
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['admin'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Sesi habis, silakan login ulang.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Metode tidak diizinkan.']);
    exit;
}

// Cek Token Keamanan CSRF
$csrf = (string) ($_POST['csrf_token'] ?? '');
if ($csrf === '' || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrf)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Token keamanan tidak valid. Muat ulang halaman.']);
    exit;
}

require_once __DIR__ . '/../../../app/Models/Profil.php';
require_once __DIR__ . '/../../../includes/sanitize.php';

// (2) Validasi Visi & Misi Desa
$visi = trim((string) ($_POST['visi'] ?? ''));
if ($visi === '') {
    echo json_encode(['success' => false, 'message' => 'Visi desa wajib diisi.']);
    exit;
}

$misi = $_POST['misi'] ?? [];
if (!is_array($misi)) {
    $misi = [];
}
$misi = array_values(array_filter(array_map('trim', $misi), static fn($m) => $m !== ''));
if ($misi === []) {
    echo json_encode(['success' => false, 'message' => 'Minimal satu misi harus diisi.']);
    exit;
}

// (3) Sanitasi Teks Sejarah & Validasi URL Peta Google Maps
$sejarahText  = trim(sanitize_rich_html((string) ($_POST['sejarah_teks'] ?? '')));
$sejarahQuote = trim((string) ($_POST['sejarah_quote'] ?? ''));
$petaEmbedUrl = trim((string) ($_POST['peta_embed_url'] ?? ''));
if ($petaEmbedUrl !== '') {
    $petaHost = strtolower((string) parse_url($petaEmbedUrl, PHP_URL_HOST));
    $petaPath = (string) parse_url($petaEmbedUrl, PHP_URL_PATH);
    if (!in_array($petaHost, ['www.google.com', 'google.com', 'maps.google.com'], true) || !str_starts_with($petaPath, '/maps/embed')) {
        echo json_encode(['success' => false, 'message' => 'URL peta harus berupa URL embed Google Maps yang valid.']);
        exit;
    }
}

// Olah data dusun
$dusunNama   = $_POST['dusun_nama'] ?? [];
$dusunJumlah = $_POST['dusun_jumlah'] ?? [];
$perDusun    = [];
if (is_array($dusunNama) && is_array($dusunJumlah)) {
    foreach ($dusunNama as $i => $nama) {
        $nama = trim((string) $nama);
        if ($nama === '') {
            continue;
        }
        $perDusun[] = [
            'nama'   => $nama,
            'jumlah' => max(0, (int) ($dusunJumlah[$i] ?? 0)),
        ];
    }
}

// (4) Validasi & Hitung Persentase Mata Pencaharian (Wajib 100%)
$jobJenis  = $_POST['pekerjaan_jenis'] ?? [];
$jobPersen = $_POST['pekerjaan_persen'] ?? [];
$jobs      = [];
$jobTotal  = 0;
if (is_array($jobJenis) && is_array($jobPersen)) {
    foreach ($jobJenis as $i => $jenis) {
        $jenis = trim((string) $jenis);
        if ($jenis === '') {
            continue;
        }
        $rawPersen = $jobPersen[$i] ?? null;
        $persen = filter_var($rawPersen, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 0, 'max_range' => 100],
        ]);
        if ($persen === false) {
            echo json_encode(['success' => false, 'message' => "Persentase pekerjaan '{$jenis}' harus berupa bilangan bulat antara 0 sampai 100%."]);
            exit;
        }
        $jobTotal += $persen;
        $jobs[] = [
            'jenis'  => $jenis,
            'persen' => $persen,
        ];
    }
}
if ($jobs === []) {
    echo json_encode(['success' => false, 'message' => 'Minimal satu jenis mata pencaharian harus diisi.']);
    exit;
}
if ($jobTotal !== 100) {
    echo json_encode(['success' => false, 'message' => "Total persentase mata pencaharian harus tepat 100%. Saat ini {$jobTotal}%."]);
    exit;
}

// (5) Fungsi Pembantu Upload Foto Aparatur Desa (Maks 2MB)
const FOTO_ALLOWED_MIME = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
const FOTO_MAX_BYTES    = 2 * 1024 * 1024; // 2 MB
const UPLOAD_DIR        = __DIR__ . '/../../../public/uploads/struktur/';
const UPLOAD_URL_PREFIX = '/uploads/struktur/'; // path publik relatif dari web root

function uploadFotoStruktur(array $file): string
{
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Upload gagal (kode error PHP: ' . $file['error'] . ').');
    }
    if ($file['size'] > FOTO_MAX_BYTES) {
        throw new RuntimeException("Foto \"{$file['name']}\" melebihi batas 2 MB.");
    }
    $finfo    = new finfo(FILEINFO_MIME_TYPE);
    $mimeReal = $finfo->file($file['tmp_name']);
    if (!in_array($mimeReal, FOTO_ALLOWED_MIME, true)) {
        throw new RuntimeException("Format foto \"{$file['name']}\" tidak didukung ($mimeReal). Gunakan JPG, PNG, WEBP, atau GIF.");
    }
    $ext      = match ($mimeReal) {
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif',
        default      => 'jpg',
    };
    $filename = 'struktur_' . bin2hex(random_bytes(8)) . '_' . time() . '.' . $ext;
    $destPath = UPLOAD_DIR . $filename;

    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0755, true);
    }
    if (!move_uploaded_file($file['tmp_name'], $destPath)) {
        throw new RuntimeException("Gagal menyimpan foto \"{$file['name']}\" ke server.");
    }
    return UPLOAD_URL_PREFIX . $filename;
}

// Pengolahan array upload file aparatur desa
$uploadedFiles = $_FILES['struktur_foto_file'] ?? [];
$fotoFiles = [];
if (!empty($uploadedFiles['name']) && is_array($uploadedFiles['name'])) {
    foreach ($uploadedFiles['name'] as $i => $name) {
        $fotoFiles[$i] = [
            'name'     => $name,
            'type'     => $uploadedFiles['type'][$i],
            'tmp_name' => $uploadedFiles['tmp_name'][$i],
            'error'    => (int) $uploadedFiles['error'][$i],
            'size'     => (int) $uploadedFiles['size'][$i],
        ];
    }
}

// Susun array struktur aparatur desa
$strNama    = $_POST['struktur_nama']    ?? [];
$strJabatan = $_POST['struktur_jabatan'] ?? [];
$strFoto    = $_POST['struktur_foto']    ?? [];
$strLevel   = $_POST['struktur_level']   ?? [];
$struktur   = [];

if (is_array($strNama)) {
    foreach ($strNama as $i => $nama) {
        $nama = trim((string) $nama);
        $jab  = trim((string) ($strJabatan[$i] ?? ''));
        if ($nama === '' && $jab === '') {
            continue;
        }

        $fotoPath = trim((string) ($strFoto[$i] ?? ''));
        $fileEntry = $fotoFiles[$i] ?? null;
        if ($fileEntry && $fileEntry['error'] === UPLOAD_ERR_OK && $fileEntry['size'] > 0) {
            try {
                $fotoPath = uploadFotoStruktur($fileEntry);
            } catch (RuntimeException $ex) {
                echo json_encode(['success' => false, 'message' => $ex->getMessage()]);
                exit;
            }
        }

        $struktur[] = [
            'nama'    => $nama,
            'jabatan' => $jab,
            'foto'    => $fotoPath,
            'level'   => (int) ($strLevel[$i] ?? 0),
        ];
    }
}

// (6) Eksekusi simpan ke file JSON via Model Profil
try {
    $saved = Profil::save([
        'tahun_berdiri' => (int) ($_POST['tahun_berdiri'] ?? 0),
        'tagline'       => trim((string) ($_POST['tagline'] ?? '')),
        'visi'          => $visi,
        'misi'          => $misi,
        'masa_bakti'    => trim((string) ($_POST['masa_bakti'] ?? '')),
        'struktur'      => $struktur,
        'demografi'     => [
            'total_jiwa'        => (int) ($_POST['total_jiwa'] ?? 0),
            'kepala_keluarga'   => (int) ($_POST['kepala_keluarga'] ?? 0),
            'luas_wilayah'      => (float) ($_POST['luas_wilayah'] ?? 0),
            'luas_satuan'       => trim((string) ($_POST['luas_satuan'] ?? 'km²')),
            'ketinggian'        => (float) ($_POST['ketinggian'] ?? 0),
            'ketinggian_satuan' => trim((string) ($_POST['ketinggian_satuan'] ?? 'mdpl')),
            'per_dusun'         => $perDusun,
        ],
        'mata_pencaharian' => $jobs,
        'sejarah' => [
            'paragraf' => $sejarahText,
            'quote'    => $sejarahQuote,
        ],
        'peta' => [
            'lokasi'    => trim((string) ($_POST['peta_lokasi'] ?? '')),
            'embed_url' => $petaEmbedUrl,
        ],
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Gagal menyimpan profil: ' . $e->getMessage()]);
    exit;
}

// (7) Kirim respon balasan JSON sukses
echo json_encode([
    'success' => true,
    'message' => 'Profil desa berhasil disimpan.',
    'data'    => $saved,
], JSON_UNESCAPED_UNICODE);

