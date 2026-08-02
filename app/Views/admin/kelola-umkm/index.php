<?php
$pageTitle = 'Kelola UMKM';
$activeNav = 'kelola-umkm';
require __DIR__ . '/../partials/header.php';
?>
<div class="flex flex-col w-full h-full min-h-[calc(100vh-64px)] bg-background">

    <!-- Header Section -->
    <div class="px-container-pad-desktop py-8 md:py-12 flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-line-strong bg-surface-container-lowest">
        <div class="flex flex-col max-w-2xl">
            <h1 class="font-h2 text-h2 text-ink mb-2">Kelola UMKM</h1>
            <p class="font-body-md text-body-md text-ink-dim">Direktori Usaha Mikro Kecil dan Menengah Pekon Air Naningan. Kelola data pelaku usaha untuk mendukung pengembangan ekonomi lokal.</p>
        </div>
        <div class="flex shrink-0">
            <button class="flex items-center justify-center gap-2 px-6 py-3 rounded-full bg-primary text-on-primary font-body-md text-body-md font-medium hover:bg-primary-fixed transition-colors shadow-lg shadow-primary/10">
                <span class="material-symbols-outlined text-[20px]">add</span>
                Tambah UMKM
            </button>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="flex-1 p-container-pad-desktop">
        <div class="max-w-container-max mx-auto flex flex-col gap-6">

            <!-- Filters and Search -->
            <div class="flex flex-col md:flex-row items-center justify-between gap-4 bg-surface p-4 rounded-xl border border-line">
                <div class="relative w-full md:w-96">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="material-symbols-outlined text-ink-dim text-[20px]">search</span>
                    </div>
                    <input class="w-full bg-surface-container-high border-none text-ink text-body-md font-body-md rounded-lg pl-10 pr-4 py-2.5 focus:ring-1 focus:ring-primary focus:outline-none placeholder:text-ink-dim/50 transition-shadow" placeholder="Cari nama usaha atau pemilik..." type="text"/>
                </div>
                <div class="flex items-center gap-2 w-full md:w-auto overflow-x-auto pb-1 md:pb-0">
                    <button class="px-4 py-2 rounded-full bg-surface-2 text-ink border border-line-strong font-label-mono text-label-mono whitespace-nowrap hover:border-primary/50 transition-colors">Semua Kategori</button>
                    <button class="px-4 py-2 rounded-full bg-transparent text-ink-dim border border-transparent font-label-mono text-label-mono whitespace-nowrap hover:bg-surface-2 transition-colors">Kuliner</button>
                    <button class="px-4 py-2 rounded-full bg-transparent text-ink-dim border border-transparent font-label-mono text-label-mono whitespace-nowrap hover:bg-surface-2 transition-colors">Kopi</button>
                    <button class="px-4 py-2 rounded-full bg-transparent text-ink-dim border border-transparent font-label-mono text-label-mono whitespace-nowrap hover:bg-surface-2 transition-colors">Kriya</button>
                    <button class="px-4 py-2 rounded-full bg-transparent text-ink-dim border border-transparent font-label-mono text-label-mono whitespace-nowrap hover:bg-surface-2 transition-colors">Jasa</button>
                </div>
            </div>

            <!-- Data Table -->
            <div class="bg-surface rounded-2xl border border-line overflow-hidden flex flex-col shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[800px]">
                        <thead>
                            <tr class="border-b border-line-strong bg-surface-container/50">
                                <th class="py-4 px-6 font-label-mono text-label-mono text-ink-dim uppercase bg-surface-container/90 backdrop-blur z-10 w-16">No</th>
                                <th class="py-4 px-6 font-label-mono text-label-mono text-ink-dim uppercase bg-surface-container/90 backdrop-blur z-10">Nama Usaha</th>
                                <th class="py-4 px-6 font-label-mono text-label-mono text-ink-dim uppercase bg-surface-container/90 backdrop-blur z-10 w-32">Kategori</th>
                                <th class="py-4 px-6 font-label-mono text-label-mono text-ink-dim uppercase bg-surface-container/90 backdrop-blur z-10 w-48">Dusun</th>
                                <th class="py-4 px-6 font-label-mono text-label-mono text-ink-dim uppercase bg-surface-container/90 backdrop-blur z-10 w-32">Status</th>
                                <th class="py-4 px-6 font-label-mono text-label-mono text-ink-dim uppercase bg-surface-container/90 backdrop-blur z-10 w-24 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="font-body-md text-body-md text-ink">
                            <tr class="border-b border-line/50 hover:bg-surface-2 transition-colors group cursor-pointer">
                                <td class="py-4 px-6 text-ink-dim font-label-mono text-label-mono">01</td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-surface-container-high flex items-center justify-center shrink-0 border border-line">
                                            <span class="material-symbols-outlined text-gold-soft text-[20px]">local_cafe</span>
                                        </div>
                                        <div>
                                            <div class="font-medium text-ink">Kopi Robusta Sinar Mas</div>
                                            <div class="text-[13px] text-ink-dim mt-0.5">Bpk. Haryanto</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6"><span class="inline-flex px-2.5 py-1 rounded-md bg-surface-container-high text-gold-soft text-[12px] font-medium border border-gold-soft/20">Kopi</span></td>
                                <td class="py-4 px-6 text-ink-dim">Sinar Naningan</td>
                                <td class="py-4 px-6">
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-primary/10 text-primary border border-primary/20 text-[12px] font-medium">
                                        <div class="w-1.5 h-1.5 rounded-full bg-primary"></div>Aktif
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button class="w-8 h-8 rounded-full hover:bg-surface-container-highest flex items-center justify-center text-ink-dim hover:text-ink transition-colors" title="Edit"><span class="material-symbols-outlined text-[18px]">edit</span></button>
                                        <button class="w-8 h-8 rounded-full hover:bg-error-container/30 flex items-center justify-center text-ink-dim hover:text-error transition-colors" title="Hapus"><span class="material-symbols-outlined text-[18px]">delete</span></button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="border-b border-line/50 hover:bg-surface-2 transition-colors group cursor-pointer">
                                <td class="py-4 px-6 text-ink-dim font-label-mono text-label-mono">02</td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-surface-container-high flex items-center justify-center shrink-0 border border-line">
                                            <span class="material-symbols-outlined text-gold-soft text-[20px]">restaurant</span>
                                        </div>
                                        <div>
                                            <div class="font-medium text-ink">Keripik Pisang Mpok Nur</div>
                                            <div class="text-[13px] text-ink-dim mt-0.5">Ibu Nurhayati</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6"><span class="inline-flex px-2.5 py-1 rounded-md bg-surface-container-high text-gold-soft text-[12px] font-medium border border-gold-soft/20">Kuliner</span></td>
                                <td class="py-4 px-6 text-ink-dim">Batu Tegi</td>
                                <td class="py-4 px-6">
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-primary/10 text-primary border border-primary/20 text-[12px] font-medium">
                                        <div class="w-1.5 h-1.5 rounded-full bg-primary"></div>Aktif
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button class="w-8 h-8 rounded-full hover:bg-surface-container-highest flex items-center justify-center text-ink-dim hover:text-ink transition-colors" title="Edit"><span class="material-symbols-outlined text-[18px]">edit</span></button>
                                        <button class="w-8 h-8 rounded-full hover:bg-error-container/30 flex items-center justify-center text-ink-dim hover:text-error transition-colors" title="Hapus"><span class="material-symbols-outlined text-[18px]">delete</span></button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="border-b border-line/50 hover:bg-surface-2 transition-colors group cursor-pointer">
                                <td class="py-4 px-6 text-ink-dim font-label-mono text-label-mono">03</td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-surface-container-high flex items-center justify-center shrink-0 border border-line">
                                            <span class="material-symbols-outlined text-gold-soft text-[20px]">handyman</span>
                                        </div>
                                        <div>
                                            <div class="font-medium text-ink">Anyaman Bambu Lestari</div>
                                            <div class="text-[13px] text-ink-dim mt-0.5">Bpk. Sudirman</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6"><span class="inline-flex px-2.5 py-1 rounded-md bg-surface-container-high text-gold-soft text-[12px] font-medium border border-gold-soft/20">Kriya</span></td>
                                <td class="py-4 px-6 text-ink-dim">Sinar Petir</td>
                                <td class="py-4 px-6">
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-surface-container-highest text-ink-dim border border-line-strong text-[12px] font-medium">
                                        <div class="w-1.5 h-1.5 rounded-full bg-ink-dim/50"></div>Non-aktif
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button class="w-8 h-8 rounded-full hover:bg-surface-container-highest flex items-center justify-center text-ink-dim hover:text-ink transition-colors" title="Edit"><span class="material-symbols-outlined text-[18px]">edit</span></button>
                                        <button class="w-8 h-8 rounded-full hover:bg-error-container/30 flex items-center justify-center text-ink-dim hover:text-error transition-colors" title="Hapus"><span class="material-symbols-outlined text-[18px]">delete</span></button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="border-b border-line/50 hover:bg-surface-2 transition-colors group cursor-pointer">
                                <td class="py-4 px-6 text-ink-dim font-label-mono text-label-mono">04</td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-surface-container-high flex items-center justify-center shrink-0 border border-line">
                                            <span class="material-symbols-outlined text-gold-soft text-[20px]">agriculture</span>
                                        </div>
                                        <div>
                                            <div class="font-medium text-ink">Pembibitan Pala Jaya</div>
                                            <div class="text-[13px] text-ink-dim mt-0.5">Kelompok Tani Mekar</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6"><span class="inline-flex px-2.5 py-1 rounded-md bg-surface-container-high text-gold-soft text-[12px] font-medium border border-gold-soft/20">Agrikultur</span></td>
                                <td class="py-4 px-6 text-ink-dim">Air Naningan Induk</td>
                                <td class="py-4 px-6">
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-primary/10 text-primary border border-primary/20 text-[12px] font-medium">
                                        <div class="w-1.5 h-1.5 rounded-full bg-primary"></div>Aktif
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button class="w-8 h-8 rounded-full hover:bg-surface-container-highest flex items-center justify-center text-ink-dim hover:text-ink transition-colors" title="Edit"><span class="material-symbols-outlined text-[18px]">edit</span></button>
                                        <button class="w-8 h-8 rounded-full hover:bg-error-container/30 flex items-center justify-center text-ink-dim hover:text-error transition-colors" title="Hapus"><span class="material-symbols-outlined text-[18px]">delete</span></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination Footer -->
                <div class="px-6 py-4 border-t border-line bg-surface-container/30 flex items-center justify-between">
                    <span class="text-[13px] text-ink-dim">Menampilkan 1-4 dari 42 UMKM</span>
                    <div class="flex items-center gap-1">
                        <button class="w-8 h-8 rounded-md flex items-center justify-center text-ink-dim hover:bg-surface-2 transition-colors disabled:opacity-30" disabled><span class="material-symbols-outlined text-[20px]">chevron_left</span></button>
                        <button class="w-8 h-8 rounded-md flex items-center justify-center bg-primary/20 text-primary font-medium text-[13px] border border-primary/30">1</button>
                        <button class="w-8 h-8 rounded-md flex items-center justify-center text-ink hover:bg-surface-2 transition-colors font-medium text-[13px]">2</button>
                        <button class="w-8 h-8 rounded-md flex items-center justify-center text-ink hover:bg-surface-2 transition-colors font-medium text-[13px]">3</button>
                        <button class="w-8 h-8 rounded-md flex items-center justify-center text-ink-dim hover:bg-surface-2 transition-colors"><span class="material-symbols-outlined text-[20px]">chevron_right</span></button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
