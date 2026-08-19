<?php

declare(strict_types=1);

/* ======================================================
   ENDPOINT AJAX: HAPUS FOTO PENGURUS (STRUKTUR)

   Menghapus foto satu baris struktur pada profil desa secara
   langsung (tanpa menunggu form "Simpan Perubahan"): path foto
   dikosongkan di JSON dan file fisik di /uploads/struktur/
   ikut dihapus bila aman.

   Wajib: POST + sesi admin + token CSRF.
   ====================================================== */

session_start();

if (!isset($_SESSION['admin'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Sesi habis, silakan login ulang.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Metode tidak diizinkan.']);
    exit;
}

require_once __DIR__ . '/../../../app/Models/Profil.php';

$csrf = (string) ($_POST['csrf_token'] ?? '');
if ($csrf === '' || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrf)) {
    echo json_encode(['success' => false, 'message' => 'Token keamanan tidak valid. Muat ulang halaman lalu coba lagi.']);
    exit;
}

$index = (int) ($_POST['index'] ?? -1);
if ($index < 0) {
    echo json_encode(['success' => false, 'message' => 'Indeks data pengurus tidak valid.']);
    exit;
}

try {
    $profil = Profil::get();

    if (!isset($profil['struktur'][$index])) {
        echo json_encode(['success' => false, 'message' => 'Data pengurus tidak ditemukan.']);
        exit;
    }

    $oldFoto = trim((string) ($profil['struktur'][$index]['foto'] ?? ''));
    $profil['struktur'][$index]['foto'] = '';

    // Hapus file fisik hanya bila path-nya aman (berada di dalam /uploads/)
    if ($oldFoto !== '') {
        $uploadRoot = realpath(__DIR__ . '/../../uploads') ?: (__DIR__ . '/../../uploads');
        $rel        = ltrim($oldFoto, '/'); // contoh: uploads/struktur/x.jpg
        $rel        = preg_replace('#^uploads/?#', '', $rel) ?? $rel; // menjadi: struktur/x.jpg
        $rel        = str_replace(['../', '..\\'], '', $rel); // cegah path traversal
        $candidate  = realpath($uploadRoot . DIRECTORY_SEPARATOR . $rel);
        if (
            $candidate !== false
            && str_starts_with($candidate, $uploadRoot . DIRECTORY_SEPARATOR)
            && basename($candidate) !== ''
        ) {
            @unlink($candidate);
        }
    }

    Profil::save(['struktur' => $profil['struktur']]);

    echo json_encode(['success' => true, 'message' => 'Foto pengurus berhasil dihapus.']);
} catch (Throwable $ex) {
    echo json_encode(['success' => false, 'message' => 'Gagal menghapus foto: ' . $ex->getMessage()]);
}
