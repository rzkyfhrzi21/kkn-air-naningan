<?php
$pageTitle = 'Kelola Potensi Desa';
$activeNav = 'kelola-potensi';
require __DIR__ . '/../partials/header.php';
?>
<div class="flex flex-col w-full p-8 space-y-12 pb-24">

    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6 border-b border-line pb-8">
        <div class="space-y-2 max-w-2xl">
            <div class="flex items-center gap-3 text-gold-soft font-label-mono uppercase tracking-widest text-[10px]">
                <span class="w-8 h-px bg-gold-soft/50"></span>
                Manajemen Potensi
            </div>
            <h1 class="font-h1 text-h1 text-ink m-0 p-0 leading-tight">Potensi <span class="italic text-primary">Desa</span></h1>
            <p class="font-body-md text-ink-dim text-body-lg max-w-xl">Kelola komoditas unggulan dan potensi ekonomi kreatif Pekon Air Naningan. Data ini akan ditampilkan pada halaman publik portal desa.</p>
        </div>
        <div class="flex-shrink-0">
            <button class="bg-primary hover:bg-primary-fixed text-on-primary font-body-md text-[14px] font-medium px-6 py-3 rounded-full flex items-center gap-2 transition-all shadow-lg shadow-primary/20 hover:shadow-primary/40 hover:-translate-y-0.5" onclick="document.getElementById('add-modal').classList.remove('hidden')">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Tambah Potensi
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-surface-container rounded-2xl p-6 border border-line-strong relative overflow-hidden group">
                <div class="absolute -right-12 -top-12 w-48 h-48 bg-primary/5 rounded-full blur-3xl group-hover:bg-primary/10 transition-colors duration-700"></div>
                <div class="relative z-10 flex flex-col gap-4">
                    <div class="w-12 h-12 rounded-xl bg-surface-2 flex items-center justify-center border border-line text-primary">
                        <span class="material-symbols-outlined text-[24px]">coffee</span>
                    </div>
                    <div>
                        <h3 class="font-h3 text-[32px] text-ink leading-none mb-1">12</h3>
                        <p class="font-label-mono text-ink-dim uppercase tracking-wider text-[10px]">Total Komoditas</p>
                    </div>
                </div>
            </div>
            <div class="bg-surface-container rounded-2xl p-6 border border-line-strong relative overflow-hidden group">
                <div class="absolute -right-12 -top-12 w-48 h-48 bg-tertiary-container/5 rounded-full blur-3xl group-hover:bg-tertiary-container/10 transition-colors duration-700"></div>
                <div class="relative z-10 flex flex-col gap-4">
                    <div class="w-12 h-12 rounded-xl bg-surface-2 flex items-center justify-center border border-line text-tertiary-container">
                        <span class="material-symbols-outlined text-[24px]">agriculture</span>
                    </div>
                    <div>
                        <h3 class="font-h3 text-[32px] text-ink leading-none mb-1">4</h3>
                        <p class="font-label-mono text-ink-dim uppercase tracking-wider text-[10px]">Kategori Aktif</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-3">
            <div class="bg-surface-container rounded-2xl border border-line-strong overflow-hidden flex flex-col">
                <div class="p-6 border-b border-line flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-surface-container-low/50">
                    <h2 class="font-h3 text-h3 text-ink text-[20px]">Daftar Komoditas</h2>
                    <div class="relative w-full sm:w-64">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-ink-dim text-[18px]">search</span>
                        <input class="w-full bg-surface border border-line-strong rounded-lg py-2 pl-10 pr-4 text-[14px] text-ink placeholder:text-ink-dim/50 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors font-body-md" placeholder="Cari potensi..." type="text"/>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-line text-ink-dim font-label-mono text-[10px] uppercase tracking-wider bg-surface-container/50">
                                <th class="py-4 px-6 font-medium">Komoditas</th>
                                <th class="py-4 px-6 font-medium">Kategori</th>
                                <th class="py-4 px-6 font-medium">Status</th>
                                <th class="py-4 px-6 font-medium text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="font-body-md text-[14px] text-ink divide-y divide-line/50">
                            <?php
                            $items = [
                                ['Kopi Robusta',  'Produksi tahunan mencapai 50 ton dengan kualitas ekspor.', 'Perkebunan'],
                                ['Kakao',         'Biji kakao fermentasi standar industri pengolahan cokelat.', 'Perkebunan'],
                                ['Gula Aren',     'Gula aren organik cetak tradisional tanpa pengawet.', 'Industri Rumahan'],
                            ];
                            foreach ($items as [$nama, $desc, $kat]): ?>
                            <tr class="hover:bg-surface-2/30 transition-colors group">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-lg overflow-hidden flex-shrink-0 border border-line bg-surface flex items-center justify-center">
                                            <span class="material-symbols-outlined text-gold-soft text-[20px]">energy_savings_leaf</span>
                                        </div>
                                        <div>
                                            <div class="font-medium text-ink group-hover:text-primary transition-colors"><?= htmlspecialchars($nama) ?></div>
                                            <div class="text-ink-dim text-[12px] line-clamp-1 max-w-[200px]"><?= htmlspecialchars($desc) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6"><span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-medium bg-surface text-ink-dim border border-line"><?= htmlspecialchars($kat) ?></span></td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-1.5 text-primary text-[12px]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>Aktif
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                        <button class="p-1.5 rounded-md hover:bg-surface-2 text-ink-dim hover:text-ink transition-colors" title="Edit"><span class="material-symbols-outlined text-[18px]">edit</span></button>
                                        <button class="p-1.5 rounded-md hover:bg-error-container hover:text-on-error-container text-ink-dim transition-colors" title="Hapus"><span class="material-symbols-outlined text-[18px]">delete</span></button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-line flex items-center justify-between bg-surface-container-low text-[13px] text-ink-dim">
                    <span>Menampilkan 1-3 dari 12 komoditas</span>
                    <div class="flex gap-1">
                        <button class="px-3 py-1 rounded-md bg-surface border border-line text-ink">1</button>
                        <button class="px-3 py-1 rounded-md hover:bg-surface border border-transparent hover:border-line transition-all">2</button>
                        <button class="px-3 py-1 rounded-md hover:bg-surface border border-transparent hover:border-line transition-all">3</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Potensi -->
<div class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6" id="add-modal">
    <div class="absolute inset-0 bg-background/80 backdrop-blur-sm" onclick="document.getElementById('add-modal').classList.add('hidden')"></div>
    <div class="relative bg-surface-container rounded-2xl border border-line-strong shadow-[0_20px_50px_-20px_rgba(0,0,0,0.55)] w-full max-w-3xl max-h-[90vh] overflow-hidden flex flex-col">
        <div class="p-6 border-b border-line flex items-center justify-between bg-surface-container-low sticky top-0 z-10">
            <div>
                <h2 class="font-h3 text-h3 text-ink text-[22px]">Tambah Potensi Baru</h2>
                <p class="text-ink-dim text-[13px] mt-1 font-body-md">Lengkapi detail informasi komoditas atau potensi desa.</p>
            </div>
            <button class="p-2 text-ink-dim hover:text-ink hover:bg-surface-2 rounded-full transition-colors" onclick="document.getElementById('add-modal').classList.add('hidden')">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-6 overflow-y-auto space-y-8 font-body-md">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-[13px] text-ink-dim font-medium">Nama Komoditas</label>
                    <input class="w-full bg-surface border border-line-strong rounded-lg px-4 py-2.5 text-[14px] text-ink placeholder:text-ink-dim/50 focus:outline-none focus:border-primary transition-colors" placeholder="Misal: Kopi Liberika" type="text"/>
                </div>
                <div class="space-y-2">
                    <label class="block text-[13px] text-ink-dim font-medium">Kategori</label>
                    <select class="w-full bg-surface border border-line-strong rounded-lg px-4 py-2.5 text-[14px] text-ink focus:outline-none focus:border-primary transition-colors appearance-none">
                        <option value="">Pilih Kategori...</option>
                        <option value="perkebunan">Perkebunan</option>
                        <option value="pertanian">Pertanian</option>
                        <option value="industri">Industri Rumahan</option>
                        <option value="kerajinan">Kerajinan</option>
                    </select>
                </div>
            </div>
            <div class="space-y-2">
                <label class="block text-[13px] text-ink-dim font-medium">Deskripsi Produksi &amp; Proses</label>
                <textarea class="w-full bg-surface border border-line-strong rounded-lg px-4 py-3 text-[14px] text-ink placeholder:text-ink-dim/50 focus:outline-none focus:border-primary transition-colors resize-none" placeholder="Ceritakan detail proses produksi, keunikan, dan nilai jual..." rows="5"></textarea>
            </div>
        </div>
        <div class="p-6 border-t border-line bg-surface-container-low flex justify-end gap-3 sticky bottom-0 z-10">
            <button class="px-6 py-2.5 rounded-full border border-line text-ink hover:bg-surface transition-colors font-body-md text-[14px]" onclick="document.getElementById('add-modal').classList.add('hidden')">Batal</button>
            <button class="px-6 py-2.5 rounded-full bg-primary hover:bg-primary-fixed text-on-primary font-medium transition-colors font-body-md text-[14px] shadow-sm shadow-primary/20">Simpan Potensi</button>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
