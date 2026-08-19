<?php

/* ======================================================
   ENDPOINT AJAX: UPDATE STATUS PESAN (TANDAI DIBACA / BELUM DIBACA)

   File ini ibarat "petugas stempel status surat":
   saat admin secara manual mengubah tanda "Sudah Dibaca" / "Belum Dibaca"
   pada daftar pesan di Kotak Masuk, file ini dipanggil lewat AJAX.

   Alur kerjanya:
   (1) Cek Sesi Admin & metode request POST.
   (2) Tangkap ID pesan & nilai status `is_read` ('0' atau '1').
   (3) Panggil `Pesan::update()` untuk mengubah status pesan pada file JSON.
   (4) Mengirimkan balasan berformat JSON.
====================================================== */

header('Content-Type: application/json; charset=utf-8');

// (1) Cek Sesi Admin & Metode POST
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
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

require_once __DIR__ . '/../../../app/Models/Pesan.php';

// (2a) Jaring pengaman anti-CSRF: token wajib cocok dengan sesi admin.
$csrf = (string) ($_POST['csrf_token'] ?? '');
if ($csrf === '' || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrf)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Token keamanan tidak valid. Muat ulang halaman.']);
    exit;
}

// (2) Terima ID pesan dan status is_read
$id = trim($_POST['id'] ?? '');

if (empty($id)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID pesan tidak valid.']);
    exit;
}

// (2b) Kumpulkan perubahan yang sah: is_read (0/1) dan/atau is_archived (0/1)
$payload = [];
if (isset($_POST['is_read']) && in_array((string) $_POST['is_read'], ['0', '1'], true)) {
    $payload['is_read'] = $_POST['is_read'] === '1';
}
if (isset($_POST['is_archived']) && in_array((string) $_POST['is_archived'], ['0', '1'], true)) {
    $payload['is_archived'] = $_POST['is_archived'] === '1';
}

if ($payload === []) {
    echo json_encode(['success' => false, 'message' => 'Tidak ada data yang diperbarui.']);
    exit;
}

// (3) Update status pesan via Model Pesan
$item = Pesan::update($id, $payload);

// (4) Kirim balasan JSON
if ($item) {
    $msg = 'Status pesan berhasil diperbarui.';
    if (array_key_exists('is_archived', $payload)) {
        $msg = $payload['is_archived'] ? 'Pesan diarsipkan.' : 'Pesan dikembalikan ke kotak masuk.';
    } elseif (array_key_exists('is_read', $payload)) {
        $msg = $payload['is_read'] ? 'Pesan ditandai sudah dibaca.' : 'Pesan ditandai belum dibaca.';
    }
    echo json_encode([
        'success' => true,
        'message' => $msg,
        'data'    => $item
    ]);
} else {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Pesan tidak ditemukan.']);
}

