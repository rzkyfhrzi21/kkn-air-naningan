<?php
$pageTitle = 'Kelola Galeri';
$activeNav = 'kelola-galeri';
require __DIR__ . '/../partials/header.php';
?>
<div class="flex flex-col w-full px-container-pad-mobile lg:px-container-pad-desktop pb-section-v-desktop gap-10">

    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6 pt-10">
        <div class="flex flex-col gap-2 max-w-2xl">
            <h1 class="font-h1-mobile lg:font-h1 text-h1-mobile lg:text-h1 text-ink">Kelola Galeri</h1>
            <p class="font-body-lg text-body-lg text-ink-dim">Unggah dan kelola aset visual situs Pekon Air Naningan. Pertahankan standar visual agrarian premium.</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="px-6 py-3 rounded-full bg-surface-2 text-ink border border-line flex items-center gap-2 hover:bg-surface-container-high transition-colors">
                <span class="material-symbols-outlined text-[20px]">delete</span>
                <span class="font-label-mono text-label-mono uppercase tracking-widest">Hapus Pilihan (0)</span>
            </button>
            <button class="px-6 py-3 rounded-full bg-primary text-on-primary font-label-mono text-label-mono uppercase tracking-widest hover:bg-primary-fixed transition-colors shadow-lg shadow-primary/20">Simpan Perubahan</button>
        </div>
    </div>

    <div class="flex flex-col xl:flex-row gap-8">
        <!-- Sidebar Upload & Filter -->
        <div class="w-full xl:w-[380px] shrink-0 flex flex-col gap-6">
            <div class="bg-surface-container rounded-2xl p-6 border border-line flex flex-col gap-4">
                <div class="flex items-center gap-3 text-gold-soft">
                    <span class="material-symbols-outlined">cloud_upload</span>
                    <h3 class="font-h3 text-h3">Upload Foto</h3>
                </div>
                <div class="border-2 border-dashed border-line-strong rounded-xl p-8 flex flex-col items-center justify-center text-center gap-4 hover:border-primary/50 transition-colors cursor-pointer bg-surface/50 group">
                    <div class="w-16 h-16 rounded-full bg-surface-2 flex items-center justify-center text-ink-dim group-hover:text-primary transition-colors group-hover:scale-110 duration-300">
                        <span class="material-symbols-outlined text-3xl">add_photo_alternate</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="font-body-md text-body-md text-ink">Drag &amp; drop foto di sini</span>
                        <span class="font-label-mono text-label-mono text-ink-dim">JPG, PNG maksimal 2MB</span>
                    </div>
                    <button class="px-4 py-2 mt-2 rounded-full bg-surface-2 text-ink border border-line hover:border-primary transition-colors font-label-mono text-[10px] uppercase tracking-widest">Pilih File</button>
                </div>
            </div>
            <div class="bg-surface-container rounded-2xl p-6 border border-line flex flex-col gap-6">
                <div class="flex items-center gap-3 text-gold-soft">
                    <span class="material-symbols-outlined">filter_list</span>
                    <h3 class="font-h3 text-h3">Filter</h3>
                </div>
                <div class="flex flex-col gap-4">
                    <div class="flex flex-col gap-2">
                        <label class="font-body-md text-body-md text-ink-dim">Kategori</label>
                        <select class="w-full bg-surface border border-line rounded-lg px-4 py-3 text-ink font-body-md focus:outline-none focus:border-primary appearance-none">
                            <option>Semua Kategori</option>
                            <option>Pertanian</option>
                            <option>Alam</option>
                            <option>Kegiatan</option>
                            <option>Infrastruktur</option>
                        </select>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="font-body-md text-body-md text-ink-dim">Urut Berdasarkan</label>
                        <div class="flex gap-2">
                            <button class="flex-1 py-2 rounded-lg bg-surface-2 text-ink border border-primary text-sm font-label-mono">Terbaru</button>
                            <button class="flex-1 py-2 rounded-lg bg-surface text-ink-dim border border-line hover:bg-surface-2 text-sm font-label-mono transition-colors">Terlama</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grid Foto -->
        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 auto-rows-max">
            <?php
            $photos = [
                ['Pemandangan Pagi',  'Landscape',  '2 hari lalu • 2.4 MB'],
                ['Panen Kopi 2024',   'Pertanian',  '1 minggu lalu • 1.8 MB'],
                ['Kerajinan Anyaman', 'Komunitas',  '2 minggu lalu • 3.1 MB'],
                ['Jalan Desa Baru',   'Infrastruktur', '1 bulan lalu • 1.2 MB'],
            ];
            foreach ($photos as $i => [$nama, $kat, $info]):
                $colors = ['bg-primary/20', 'bg-secondary/20', 'bg-tertiary/20', 'bg-outline/20'];
            ?>
            <div class="group relative bg-surface-container rounded-2xl overflow-hidden border border-line hover:border-primary/50 transition-all shadow-sm hover:shadow-xl hover:-translate-y-1 duration-300">
                <div class="absolute top-3 left-3 z-10">
                    <input class="w-5 h-5 rounded border-line-strong bg-surface/80 checked:bg-primary cursor-pointer accent-primary backdrop-blur-sm" type="checkbox"/>
                </div>
                <div class="absolute top-3 right-3 z-10 bg-surface/80 backdrop-blur-md px-3 py-1 rounded-full border border-line">
                    <span class="font-label-mono text-[10px] text-gold-soft uppercase tracking-wider"><?= htmlspecialchars($kat) ?></span>
                </div>
                <div class="w-full aspect-[4/3] <?= $colors[$i % count($colors)] ?> relative overflow-hidden flex items-center justify-center">
                    <span class="material-symbols-outlined text-ink-dim/30 text-[48px]">image</span>
                    <div class="absolute inset-0 bg-gradient-to-t from-background/90 via-background/20 to-transparent opacity-60"></div>
                </div>
                <div class="p-5 flex flex-col gap-3">
                    <div class="flex flex-col gap-1">
                        <input class="w-full bg-transparent border-b border-transparent hover:border-line focus:border-primary focus:outline-none text-ink font-body-lg transition-colors px-1 -ml-1" type="text" value="<?= htmlspecialchars($nama) ?>"/>
                        <span class="font-label-mono text-[10px] text-ink-dim"><?= htmlspecialchars($info) ?></span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
