<?php
$pageTitle = 'Kelola Wisata';
$activeNav = 'kelola-wisata';
require __DIR__ . '/../partials/header.php';
?>
<div class="flex flex-col w-full px-container-pad-mobile md:px-container-pad-desktop py-section-v-mobile md:py-section-v-desktop gap-8 max-w-container-max mx-auto">

    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="flex flex-col gap-2">
            <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest">Manajemen Destinasi</span>
            <h1 class="font-h2 text-h2 text-ink">Kelola Wisata</h1>
            <p class="font-body-md text-body-md text-ink-dim max-w-2xl">Pusat kontrol untuk memperbarui informasi, menambahkan destinasi baru, dan memantau status operasional lokasi wisata di Air Naningan.</p>
        </div>
        <button class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-primary hover:bg-primary-fixed-dim text-on-primary rounded-full transition-all duration-300 font-label-mono text-label-mono uppercase shadow-lg shadow-primary/20 shrink-0">
            <span class="material-symbols-outlined text-[18px]">add</span>
            Tambah Wisata
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <div class="lg:col-span-1 flex flex-col gap-6">
            <div class="bg-surface-2 rounded-2xl p-6 border border-line flex flex-col gap-4">
                <div class="flex items-center justify-between">
                    <span class="font-label-mono text-label-mono text-ink-dim uppercase">Total Destinasi</span>
                    <div class="w-8 h-8 rounded-full bg-surface-container flex items-center justify-center border border-line">
                        <span class="material-symbols-outlined text-gold-soft text-[16px]">landscape</span>
                    </div>
                </div>
                <div class="font-h1 text-h1 text-ink">12</div>
                <div class="flex items-center gap-2 mt-2">
                    <span class="inline-flex items-center px-2 py-1 rounded bg-surface text-ink-dim font-label-mono text-[10px]">Aktif Semua</span>
                </div>
            </div>
            <div class="bg-surface-container rounded-2xl p-6 border border-line flex flex-col gap-4 relative overflow-hidden group hover:border-line-strong transition-colors">
                <div class="flex items-center gap-3 mb-2 relative z-10">
                    <span class="material-symbols-outlined text-gold-soft">insights</span>
                    <span class="font-label-mono text-label-mono text-ink-dim uppercase">Statistik Pengunjung</span>
                </div>
                <div class="relative h-24 w-full flex items-end gap-1 mt-2 z-10">
                    <div class="w-full bg-primary/20 rounded-t-sm h-[30%] hover:bg-primary transition-colors"></div>
                    <div class="w-full bg-primary/40 rounded-t-sm h-[45%] hover:bg-primary transition-colors"></div>
                    <div class="w-full bg-primary/30 rounded-t-sm h-[35%] hover:bg-primary transition-colors"></div>
                    <div class="w-full bg-primary/60 rounded-t-sm h-[70%] hover:bg-primary transition-colors"></div>
                    <div class="w-full bg-primary/50 rounded-t-sm h-[55%] hover:bg-primary transition-colors"></div>
                    <div class="w-full bg-primary/80 rounded-t-sm h-[90%] hover:bg-primary transition-colors"></div>
                    <div class="w-full bg-primary rounded-t-sm h-[100%] hover:bg-primary transition-colors"></div>
                </div>
                <div class="flex justify-between font-label-mono text-[9px] text-ink-dim relative z-10">
                    <span>Sen</span><span>Min</span>
                </div>
            </div>
        </div>

        <div class="lg:col-span-3">
            <div class="bg-surface rounded-2xl border border-line overflow-hidden flex flex-col h-full shadow-md">
                <div class="p-4 border-b border-line flex items-center justify-between bg-surface-container/50">
                    <div class="relative w-64">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-ink-dim text-[18px]">search</span>
                        <input class="w-full bg-surface-container-high text-ink font-body-md text-sm rounded-lg pl-10 pr-4 py-2 border border-line focus:outline-none focus:border-gold-soft transition-colors placeholder:text-ink-dim/50" placeholder="Cari wisata..." type="text"/>
                    </div>
                    <button class="p-2 rounded-lg bg-surface-container-high border border-line text-ink-dim hover:text-ink hover:border-line-strong transition-colors">
                        <span class="material-symbols-outlined text-[18px]">filter_list</span>
                    </button>
                </div>
                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-line-strong bg-surface-container/30">
                                <th class="py-4 px-6 font-label-mono text-label-mono text-ink-dim uppercase tracking-wider font-normal">Destinasi</th>
                                <th class="py-4 px-6 font-label-mono text-label-mono text-ink-dim uppercase tracking-wider font-normal">Kategori</th>
                                <th class="py-4 px-6 font-label-mono text-label-mono text-ink-dim uppercase tracking-wider font-normal">Lokasi</th>
                                <th class="py-4 px-6 font-label-mono text-label-mono text-ink-dim uppercase tracking-wider font-normal">Status</th>
                                <th class="py-4 px-6 font-label-mono text-label-mono text-ink-dim uppercase tracking-wider font-normal text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-line">
                            <tr class="hover:bg-surface-2 transition-colors group">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-lg bg-surface-container overflow-hidden shrink-0 border border-line">
                                            <div class="w-full h-full bg-surface-container-high flex items-center justify-center"><span class="material-symbols-outlined text-gold-soft">landscape</span></div>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-body-md text-body-md text-ink font-medium group-hover:text-gold-soft transition-colors">Air Terjun Way Lalaan</span>
                                            <span class="font-body-md text-[13px] text-ink-dim line-clamp-1">Air terjun bertingkat dengan kolam alami...</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6"><span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-surface-container border border-line text-ink-dim font-label-mono text-[10px] uppercase"><span class="w-1.5 h-1.5 rounded-full bg-primary"></span>Air Terjun</span></td>
                                <td class="py-4 px-6"><div class="flex flex-col"><span class="font-body-md text-[13px] text-ink">Dusun Talang Baru</span><span class="font-label-mono text-[10px] text-ink-dim mt-1">-5.2341, 104.4562</span></div></td>
                                <td class="py-4 px-6"><span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-2 border border-line text-ink font-label-mono text-[10px] uppercase">Buka</span></td>
                                <td class="py-4 px-6 text-right"><div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity"><button class="p-1.5 rounded-md hover:bg-surface-container-high text-ink-dim hover:text-primary transition-colors" title="Edit"><span class="material-symbols-outlined text-[18px]">edit</span></button><button class="p-1.5 rounded-md hover:bg-surface-container-high text-ink-dim hover:text-danger transition-colors" title="Hapus"><span class="material-symbols-outlined text-[18px]">delete</span></button></div></td>
                            </tr>
                            <tr class="hover:bg-surface-2 transition-colors group">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-lg bg-surface-container overflow-hidden shrink-0 border border-line"><div class="w-full h-full bg-surface-container-high flex items-center justify-center"><span class="material-symbols-outlined text-gold-soft">landscape</span></div></div>
                                        <div class="flex flex-col">
                                            <span class="font-body-md text-body-md text-ink font-medium group-hover:text-gold-soft transition-colors">Puncak Bukit Kabut</span>
                                            <span class="font-body-md text-[13px] text-ink-dim line-clamp-1">Titik pandang terbaik untuk melihat matahari...</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6"><span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-surface-container border border-line text-ink-dim font-label-mono text-[10px] uppercase"><span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>Titik Pandang</span></td>
                                <td class="py-4 px-6"><div class="flex flex-col"><span class="font-body-md text-[13px] text-ink">Dusun Sinar Agung</span><span class="font-label-mono text-[10px] text-ink-dim mt-1">-5.2411, 104.4601</span></div></td>
                                <td class="py-4 px-6"><span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-2 border border-line text-ink font-label-mono text-[10px] uppercase">Buka</span></td>
                                <td class="py-4 px-6 text-right"><div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity"><button class="p-1.5 rounded-md hover:bg-surface-container-high text-ink-dim hover:text-primary transition-colors" title="Edit"><span class="material-symbols-outlined text-[18px]">edit</span></button><button class="p-1.5 rounded-md hover:bg-surface-container-high text-ink-dim hover:text-danger transition-colors" title="Hapus"><span class="material-symbols-outlined text-[18px]">delete</span></button></div></td>
                            </tr>
                            <tr class="hover:bg-surface-2 transition-colors group opacity-75">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-lg bg-surface-container overflow-hidden shrink-0 border border-line"><div class="w-full h-full bg-surface-container-high flex items-center justify-center"><span class="material-symbols-outlined text-line-strong">image</span></div></div>
                                        <div class="flex flex-col">
                                            <span class="font-body-md text-body-md text-ink font-medium group-hover:text-gold-soft transition-colors">Goa Kelelawar Hitam</span>
                                            <span class="font-body-md text-[13px] text-ink-dim line-clamp-1">Formasi batu kapur alami dengan stalaktit...</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6"><span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-surface-container border border-line text-ink-dim font-label-mono text-[10px] uppercase"><span class="w-1.5 h-1.5 rounded-full bg-outline"></span>Wisata Alam</span></td>
                                <td class="py-4 px-6"><div class="flex flex-col"><span class="font-body-md text-[13px] text-ink">Dusun Mekar Jaya</span><span class="font-label-mono text-[10px] text-ink-dim mt-1">-5.2505, 104.4712</span></div></td>
                                <td class="py-4 px-6"><span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-lowest border border-line text-ink-dim font-label-mono text-[10px] uppercase">Tutup</span></td>
                                <td class="py-4 px-6 text-right"><div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity"><button class="p-1.5 rounded-md hover:bg-surface-container-high text-ink-dim hover:text-primary transition-colors" title="Edit"><span class="material-symbols-outlined text-[18px]">edit</span></button><button class="p-1.5 rounded-md hover:bg-surface-container-high text-ink-dim hover:text-danger transition-colors" title="Hapus"><span class="material-symbols-outlined text-[18px]">delete</span></button></div></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-line flex items-center justify-between bg-surface-container/30">
                    <span class="font-body-md text-[13px] text-ink-dim">Menampilkan 1-3 dari 12 destinasi</span>
                    <div class="flex items-center gap-1">
                        <button class="w-8 h-8 flex items-center justify-center rounded bg-surface-container border border-line text-ink-dim opacity-50 cursor-not-allowed"><span class="material-symbols-outlined text-[16px]">chevron_left</span></button>
                        <button class="w-8 h-8 flex items-center justify-center rounded bg-surface border border-line text-ink hover:border-gold-soft transition-colors">1</button>
                        <button class="w-8 h-8 flex items-center justify-center rounded bg-surface-container border border-line text-ink-dim hover:text-ink transition-colors">2</button>
                        <button class="w-8 h-8 flex items-center justify-center rounded bg-surface-container border border-line text-ink-dim hover:text-ink transition-colors"><span class="material-symbols-outlined text-[16px]">chevron_right</span></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
