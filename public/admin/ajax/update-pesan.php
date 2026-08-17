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

// (2) Terima ID pesan dan status is_read
$id = trim($_POST['id'] ?? '');

if (empty($id)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID pesan tidak valid.']);
    exit;
}

if (!isset($_POST['is_read']) || !in_array((string) $_POST['is_read'], ['0', '1'], true)) {
    echo json_encode(['success' => false, 'message' => 'Tidak ada data yang diperbarui.']);
    exit;
}

// (3) Update status pesan via Model Pesan
$item = Pesan::update($id, ['is_read' => $_POST['is_read'] === '1']);

// (4) Kirim balasan JSON
if ($item) {
    echo json_encode([
        'success' => true,
        'message' => 'Status pesan berhasil diperbarui.',
        'data'    => $item
    ]);
} else {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Pesan tidak ditemukan.']);
}

