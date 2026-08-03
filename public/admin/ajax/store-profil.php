<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

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

$csrf = (string) ($_POST['csrf_token'] ?? '');
if ($csrf === '' || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrf)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Token keamanan tidak valid. Muat ulang halaman.']);
    exit;
}

require_once __DIR__ . '/../../../app/Models/Profil.php';

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

$sejarahText = trim((string) ($_POST['sejarah_teks'] ?? ''));
$sejarahQuote = trim((string) ($_POST['sejarah_quote'] ?? ''));

$dusunNama   = $_POST['dusun_nama'] ?? [];
$dusunJumlah = $_POST['dusun_jumlah'] ?? [];
$perDusun    = [];
if (is_array($dusunNama)) {
    foreach ($dusunNama as $i => $nama) {
        $perDusun[] = [
            'nama'   => trim((string) $nama),
            'jumlah' => (int) ($dusunJumlah[$i] ?? 0),
        ];
    }
}

$jobJenis  = $_POST['pekerjaan_jenis'] ?? [];
$jobPersen = $_POST['pekerjaan_persen'] ?? [];
$jobs      = [];
if (is_array($jobJenis)) {
    foreach ($jobJenis as $i => $jenis) {
        $jenis = trim((string) $jenis);
        if ($jenis === '') {
            continue;
        }
        $jobs[] = [
            'jenis'  => $jenis,
            'persen' => (int) ($jobPersen[$i] ?? 0),
        ];
    }
}

$strNama    = $_POST['struktur_nama'] ?? [];
$strJabatan = $_POST['struktur_jabatan'] ?? [];
$strFoto    = $_POST['struktur_foto'] ?? [];
$strLevel   = $_POST['struktur_level'] ?? [];
$struktur   = [];
if (is_array($strNama)) {
    foreach ($strNama as $i => $nama) {
        $nama = trim((string) $nama);
        $jab  = trim((string) ($strJabatan[$i] ?? ''));
        if ($nama === '' && $jab === '') {
            continue;
        }
        $struktur[] = [
            'nama'    => $nama,
            'jabatan' => $jab,
            'foto'    => trim((string) ($strFoto[$i] ?? '')),
            'level'   => (int) ($strLevel[$i] ?? 0),
        ];
    }
}

$apbNama   = $_POST['apbdes_nama'] ?? [];
$apbJumlah = $_POST['apbdes_jumlah'] ?? [];
$apbPersen = $_POST['apbdes_persen'] ?? [];
$apbIcon   = $_POST['apbdes_icon'] ?? [];
$apbItems  = [];
if (is_array($apbNama)) {
    foreach ($apbNama as $i => $nama) {
        $nama = trim((string) $nama);
        if ($nama === '') {
            continue;
        }
        $apbItems[] = [
            'nama'   => $nama,
            'jumlah' => trim((string) ($apbJumlah[$i] ?? '')),
            'persen' => (int) ($apbPersen[$i] ?? 0),
            'icon'   => trim((string) ($apbIcon[$i] ?? 'account_balance')),
        ];
    }
}

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
        'apbdes'           => [
            'tahun'       => (int) ($_POST['apbdes_tahun'] ?? date('Y')),
            'laporan_url' => trim((string) ($_POST['apbdes_laporan_url'] ?? '')),
            'items'       => $apbItems,
        ],
        'sejarah' => [
            'paragraf' => $sejarahText,
            'quote'    => $sejarahQuote,
        ],
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Gagal menyimpan profil: ' . $e->getMessage()]);
    exit;
}

echo json_encode([
    'success' => true,
    'message' => 'Profil desa berhasil disimpan.',
    'data'    => $saved,
], JSON_UNESCAPED_UNICODE);
