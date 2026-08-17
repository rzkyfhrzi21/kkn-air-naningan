<?php

/* ======================================================
   ENDPOINT AJAX: SIMPAN / PERBARUI BERITA (FORM KELOLA BERITA)

   File ini ibarat "petugas editor berita":
   saat admin menekan tombol "Simpan Berita" (tambah berita baru atau edit berita lama),
   halaman Kelola Berita memanggil file ini lewat AJAX.

   Alur kerjanya:
   (1) Memeriksa login admin & token CSRF.
   (2) Mengambil input form (judul, kategori, ringkasan, konten, penulis, status, tanggal terbit, tags).
   (3) Validasi kelengkapan bidang (judul, kategori, ringkasan, konten) & tanggal.
   (4) Sanitasi konten HTML agar bebas dari tag script peretas (menggunakan sanitize_rich_html).
   (5) Mengolah upload gambar sampul ke `uploads/berita/` (maks 2MB, format JPG/PNG/GIF/WebP).
   (6) Jika ID kosong: panggil `Berita::create()` untuk membuat berita baru.
       Jika ID ada: panggil `Berita::update()` untuk memperbarui berita lama.
   (7) Mengirimkan balasan JSON berpesan rinci untuk notifikasi toast.
====================================================== */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// (1) Cek Sesi Admin
if (!isset($_SESSION['admin'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Sesi habis, silakan login ulang.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

// Cek Token Keamanan CSRF
$csrf = (string) ($_POST['csrf_token'] ?? '');
if ($csrf === '' || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrf)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Token keamanan tidak valid. Muat ulang halaman.']);
    exit;
}

require_once __DIR__ . '/../../../app/Models/Berita.php';

// (2) Ambil input dari form HTML admin
$id             = trim($_POST['id'] ?? '');
$judul          = trim($_POST['judul'] ?? '');
$kategori       = trim($_POST['kategori'] ?? '');
$ringkasan      = trim($_POST['ringkasan'] ?? '');
$konten         = trim($_POST['konten'] ?? '');
$penulis        = trim($_POST['penulis'] ?? 'Admin Desa');
$status         = trim($_POST['status'] ?? 'draft');
$tanggal_terbit = trim($_POST['tanggal_terbit'] ?? date('Y-m-d'));
$tags           = isset($_POST['tags']) ? array_filter(array_map('trim', explode(',', $_POST['tags']))) : [];

// (3) Validasi bidang wajib diisi
if (empty($judul)) {
    echo json_encode(['success' => false, 'message' => 'Judul berita wajib diisi.']);
    exit;
}

if (empty($kategori)) {
    echo json_encode(['success' => false, 'message' => 'Kategori berita wajib dipilih.']);
    exit;
}

if (empty($ringkasan)) {
    echo json_encode(['success' => false, 'message' => 'Ringkasan berita wajib diisi.']);
    exit;
}

if (empty($konten)) {
    echo json_encode(['success' => false, 'message' => 'Konten berita wajib diisi.']);
    exit;
}

// (4) Sterilkan HTML konten berita demi keamanan
require_once __DIR__ . '/../../../includes/sanitize.php';
$konten = sanitize_rich_html($konten);

if (!in_array($status, ['draft', 'terbit'], true)) {
    echo json_encode(['success' => false, 'message' => 'Status berita tidak valid.']);
    exit;
}

$publishedAt = DateTime::createFromFormat('Y-m-d', $tanggal_terbit);
if (!$publishedAt || $publishedAt->format('Y-m-d') !== $tanggal_terbit) {
    echo json_encode(['success' => false, 'message' => 'Tanggal publikasi tidak valid.']);
    exit;
}

// (5) Pengolahan Upload File Foto Sampul (Maks 2MB, simpan ke /uploads/berita/)
$fotoPath  = '';
$fileErr   = $_FILES['foto_file']['error'] ?? UPLOAD_ERR_NO_FILE;

if ($fileErr === UPLOAD_ERR_OK) {
    $tmpFile  = $_FILES['foto_file']['tmp_name'];
    $fileSize = $_FILES['foto_file']['size'];

    if ($fileSize > 2 * 1024 * 1024) {
        echo json_encode(['success' => false, 'message' => 'Ukuran foto maksimal 2MB.']);
        exit;
    }

    $finfo   = new finfo(FILEINFO_MIME_TYPE);
    $mime    = $finfo->file($tmpFile);
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];

    if (!array_key_exists($mime, $allowed)) {
        echo json_encode(['success' => false, 'message' => 'Format foto tidak valid. Gunakan JPG, PNG, GIF, atau WebP.']);
        exit;
    }

    $ext       = $allowed[$mime];
    $filename  = bin2hex(random_bytes(16)) . '.' . $ext;
    $uploadDir = dirname(dirname(__DIR__)) . '/uploads/berita/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if (!move_uploaded_file($tmpFile, $uploadDir . $filename)) {
        echo json_encode(['success' => false, 'message' => 'Gagal menyimpan foto ke server.']);
        exit;
    }

    $scriptBase = rtrim(dirname(dirname(dirname($_SERVER['SCRIPT_NAME'] ?? ''))), '/');
    $scriptBase = ($scriptBase === '/') ? '' : $scriptBase;
    $fotoPath   = $scriptBase . '/uploads/berita/' . $filename;

} elseif ($fileErr !== UPLOAD_ERR_NO_FILE) {
    echo json_encode(['success' => false, 'message' => 'Upload gagal (kode: ' . $fileErr . ').']);
    exit;
} else {
    if ($id !== '') {
        $existing = Berita::find($id);
        $fotoPath = (string) ($existing['foto_sampul'] ?? '');
    }
}

// (6) Rangkai array payload untuk disimpan
$payload = [
    'judul'          => $judul,
    'kategori'       => $kategori,
    'ringkasan'      => $ringkasan,
    'konten'         => $konten,
    'foto_sampul'    => $fotoPath,
    'penulis'        => $penulis,
    'status'         => $status,
    'tanggal_terbit' => $tanggal_terbit,
    'tags'           => $tags,
];

// (7) Eksekusi simpan baru (create) atau perbarui lama (update)
if (empty($id)) {
    $item = Berita::create($payload);
    echo json_encode([
        'success' => true,
        'message' => "Berita '{$item['judul']}' berhasil ditambahkan.",
        'data'    => $item
    ]);
} else {
    $item = Berita::update($id, $payload);
    if ($item) {
        echo json_encode([
            'success' => true,
            'message' => "Berita '{$item['judul']}' berhasil diperbarui.",
            'data'    => $item
        ]);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Berita tidak ditemukan.']);
    }
}

