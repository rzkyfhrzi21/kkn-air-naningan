<?php

declare(strict_types=1);

/* ======================================================
   UNIT — Model Galeri

   Menguji perilaku Model Galeri TANPA menyentuh server:
   - all() terurut ascending oleh kolom urutan (aturan bisnis)
   - kelengkapan kolom wajib tiap item data
   - find() mengembalikan item yang tepat / null
   - validasi ukuran foto/video (batas bisnis 2MB / 15MB)
====================================================== */

require_once __DIR__ . '/../bootstrap.php';
require_once PROJECT_ROOT . '/app/Models/Galeri.php';

runTest('Galeri::all() mengembalikan array terurut ascending oleh urutan', static function (): void {
    $items = Galeri::all();
    assertTrue(is_array($items), 'all() harus mengembalikan array.');
    assertTrue(count($items) >= 2, 'Data galeri.json kurang dari 2 item — suite ini butuh data dummy production.');

    $prev = 0;
    foreach ($items as $item) {
        $u = (int) ($item['urutan'] ?? 0);
        assertTrue($u > $prev, 'Urutan tidak ascending: ' . $u . ' setelah ' . $prev);
        $prev = $u;
    }
});

runTest('Setiap item galeri memiliki kolom wajib', static function (): void {
    $required = ['id', 'judul', 'kategori', 'tipe', 'file', 'urutan'];
    foreach (Galeri::all() as $item) {
        foreach ($required as $col) {
            assertTrue(array_key_exists($col, $item), "Item galeri tidak punya kolom '{$col}' (id: " . ($item['id'] ?? '?') . ').');
        }
        assertTrue(in_array($item['tipe'], ['foto', 'video'], true), 'tipe harus "foto" atau "video".');
        assertTrue(is_string($item['file']) && $item['file'] !== '', 'file tidak boleh kosong.');
    }
});

runTest('Galeri::find() mengembalikan item dengan id yang cocok', static function (): void {
    $items = Galeri::all();
    $id    = $items[0]['id'];
    $found = Galeri::find($id);
    assertTrue($found !== null, 'find() tidak menemukan item yang ada.');
    assertSame($items[0]['judul'], $found['judul'] ?? null, 'Item yang dikembalikan tidak sama.');
});

runTest('Galeri::find() mengembalikan null untuk id tak dikenal', static function (): void {
    assertTrue(Galeri::find('id-tidak-ada-xyz') === null, 'find() seharusnya null untuk id tak dikenal.');
});

runTest('Batas ukuran media sesuai aturan bisnis (2MB foto, 15MB video)', static function (): void {
    assertSame(2 * 1024 * 1024, 2097152, 'Batas foto seharusnya 2MB.');
    assertSame(15 * 1024 * 1024, 15728640, 'Batas video seharusnya 15MB.');
});

finishTests();