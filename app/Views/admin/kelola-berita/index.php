<?php
$pageTitle = 'Kelola Berita';
$activeNav = 'kelola-berita';
require __DIR__ . '/../partials/header.php';
?>
<div class="flex flex-col w-full min-h-full">

    <!-- Header -->
    <div class="px-container-pad-desktop py-8 md:py-12 flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-line">
        <div class="max-w-2xl">
            <h1 class="font-h1 text-h1-mobile md:text-h1 text-ink mb-3">Kelola Berita</h1>
            <p class="font-body-lg text-body-lg text-ink-dim">Publikasikan informasi terkini, pengumuman desa, dan liputan kegiatan Pekon Air Naningan.</p>
        </div>
        <button class="bg-primary text-on-primary hover:bg-primary-fixed transition-colors rounded-full px-6 py-3 font-label-mono uppercase tracking-widest flex items-center justify-center gap-2 flex-shrink-0 shadow-xl shadow-primary/10" id="btn-create-news">
            <span class="material-symbols-outlined text-[20px]">add</span>
            Tulis Berita Baru
        </button>
    </div>

    <!-- Split View -->
    <div class="flex-1 flex flex-col xl:flex-row relative">

        <!-- Daftar Berita (Kiri) -->
        <div class="w-full xl:w-1/2 2xl:w-7/12 border-r-0 xl:border-r border-line flex flex-col" id="news-list-panel">
            <!-- Filter -->
            <div class="p-6 border-b border-line bg-surface-container-low flex flex-col sm:flex-row gap-4 items-center justify-between sticky top-16 z-20">
                <div class="relative w-full sm:w-64">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-ink-dim text-[20px]">search</span>
                    <input class="w-full bg-surface border border-line rounded-lg py-2.5 pl-10 pr-4 text-ink font-body-md focus:outline-none focus:border-primary transition-colors placeholder:text-surface-variant" placeholder="Cari judul berita..." type="text"/>
                </div>
                <div class="flex items-center gap-2 w-full sm:w-auto overflow-x-auto pb-1 sm:pb-0">
                    <button class="px-4 py-1.5 rounded-full bg-surface-2 text-ink border border-line-strong font-label-mono text-[11px] whitespace-nowrap">Semua</button>
                    <button class="px-4 py-1.5 rounded-full bg-transparent text-ink-dim hover:text-ink border border-transparent hover:border-line font-label-mono text-[11px] whitespace-nowrap transition-colors">Pengumuman</button>
                    <button class="px-4 py-1.5 rounded-full bg-transparent text-ink-dim hover:text-ink border border-transparent hover:border-line font-label-mono text-[11px] whitespace-nowrap transition-colors">Kegiatan</button>
                    <button class="px-4 py-1.5 rounded-full bg-transparent text-ink-dim hover:text-ink border border-transparent hover:border-line font-label-mono text-[11px] whitespace-nowrap transition-colors">Draft</button>
                </div>
            </div>

            <!-- Table Header -->
            <div class="hidden sm:grid grid-cols-12 gap-4 px-8 py-4 border-b border-line bg-surface-container-lowest font-label-mono text-[10px] text-ink-dim uppercase tracking-widest">
                <div class="col-span-6">Judul Berita</div>
                <div class="col-span-2">Kategori</div>
                <div class="col-span-2">Status</div>
                <div class="col-span-2 text-right">Tanggal</div>
            </div>

            <!-- Berita Items -->
            <div class="flex-1 divide-y divide-line">

                <!-- Item 1 -->
                <div class="group cursor-pointer hover:bg-surface-2 transition-colors relative">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary scale-y-0 group-hover:scale-y-100 transition-transform origin-center"></div>
                    <div class="p-6 sm:px-8 sm:py-5 grid grid-cols-1 sm:grid-cols-12 gap-4 items-center">
                        <div class="sm:col-span-6 flex gap-4 items-start">
                            <div class="w-16 h-16 rounded-lg bg-surface-container overflow-hidden flex-shrink-0 flex items-center justify-center">
                                <span class="material-symbols-outlined text-ink-dim/40 text-[28px]">newspaper</span>
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-h3 text-[18px] text-ink leading-snug truncate mb-1 group-hover:text-primary transition-colors">Penyaluran BLT Dana Desa Tahap III Tahun 2024</h3>
                                <p class="text-ink-dim font-body-md text-[13px] line-clamp-1">Kegiatan penyaluran BLT Dana Desa berjalan lancar di balai pekon...</p>
                            </div>
                        </div>
                        <div class="sm:col-span-2 flex items-center">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-secondary-container/20 text-on-secondary-container font-label-mono text-[10px]">
                                <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>Bansos
                            </span>
                        </div>
                        <div class="sm:col-span-2 flex items-center">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-surface-container-highest text-ink font-label-mono text-[10px]">
                                <span class="material-symbols-outlined text-[14px]">public</span>Terbit
                            </span>
                        </div>
                        <div class="sm:col-span-2 sm:text-right text-ink-dim font-label-mono text-[11px]">12 Okt 2024</div>
                    </div>
                </div>

                <!-- Item 2 -->
                <div class="group cursor-pointer hover:bg-surface-2 transition-colors relative">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary scale-y-0 group-hover:scale-y-100 transition-transform origin-center"></div>
                    <div class="p-6 sm:px-8 sm:py-5 grid grid-cols-1 sm:grid-cols-12 gap-4 items-center">
                        <div class="sm:col-span-6 flex gap-4 items-start">
                            <div class="w-16 h-16 rounded-lg bg-surface-container overflow-hidden flex-shrink-0 flex items-center justify-center">
                                <span class="material-symbols-outlined text-ink-dim/40 text-[28px]">newspaper</span>
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-h3 text-[18px] text-ink leading-snug truncate mb-1 group-hover:text-primary transition-colors">Jadwal Posyandu Balita dan Lansia Bulan November</h3>
                                <p class="text-ink-dim font-body-md text-[13px] line-clamp-1">Diberitahukan kepada warga mengenai jadwal rutin Posyandu...</p>
                            </div>
                        </div>
                        <div class="sm:col-span-2 flex items-center">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-tertiary-container/20 text-tertiary-fixed-dim font-label-mono text-[10px]">
                                <span class="w-1.5 h-1.5 rounded-full bg-tertiary"></span>Pengumuman
                            </span>
                        </div>
                        <div class="sm:col-span-2 flex items-center">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-surface-container text-ink-dim font-label-mono text-[10px]">
                                <span class="material-symbols-outlined text-[14px]">edit_document</span>Draft
                            </span>
                        </div>
                        <div class="sm:col-span-2 sm:text-right text-ink-dim font-label-mono text-[11px]">15 Okt 2024</div>
                    </div>
                </div>

                <!-- Item 3 — active -->
                <div class="group cursor-pointer hover:bg-surface-2 transition-colors relative bg-surface-2">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary"></div>
                    <div class="p-6 sm:px-8 sm:py-5 grid grid-cols-1 sm:grid-cols-12 gap-4 items-center">
                        <div class="sm:col-span-6 flex gap-4 items-start">
                            <div class="w-16 h-16 rounded-lg bg-surface-container overflow-hidden flex-shrink-0 flex items-center justify-center">
                                <span class="material-symbols-outlined text-primary text-[28px]">coffee</span>
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-h3 text-[18px] text-primary leading-snug truncate mb-1">Panen Raya Kopi Robusta Air Naningan Sukses Digelar</h3>
                                <p class="text-ink-dim font-body-md text-[13px] line-clamp-1">Petani kopi merayakan panen raya dengan hasil yang melimpah tahun ini...</p>
                            </div>
                        </div>
                        <div class="sm:col-span-2 flex items-center">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-primary-container/20 text-primary-fixed-dim font-label-mono text-[10px]">
                                <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>Kegiatan
                            </span>
                        </div>
                        <div class="sm:col-span-2 flex items-center">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-surface-container-highest text-ink font-label-mono text-[10px]">
                                <span class="material-symbols-outlined text-[14px]">public</span>Terbit
                            </span>
                        </div>
                        <div class="sm:col-span-2 sm:text-right text-ink-dim font-label-mono text-[11px]">08 Okt 2024</div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="p-6 border-t border-line flex items-center justify-between text-ink-dim font-label-mono text-[11px]">
                <span>Menampilkan 1-3 dari 24 berita</span>
                <div class="flex items-center gap-2">
                    <button class="w-8 h-8 rounded border border-line flex items-center justify-center hover:bg-surface-2 hover:text-ink transition-colors opacity-50" disabled><span class="material-symbols-outlined text-[18px]">chevron_left</span></button>
                    <button class="w-8 h-8 rounded border border-line flex items-center justify-center hover:bg-surface-2 hover:text-ink transition-colors"><span class="material-symbols-outlined text-[18px]">chevron_right</span></button>
                </div>
            </div>
        </div>

        <!-- Editor Panel (Kanan) -->
        <div class="w-full xl:w-1/2 2xl:w-5/12 bg-surface-container-lowest flex flex-col xl:sticky xl:top-16 xl:h-[calc(100vh-4rem)] border-t xl:border-t-0 border-line" id="editor-panel">
            <!-- Editor Header -->
            <div class="px-8 py-5 border-b border-line flex items-center justify-between bg-surface/50 backdrop-blur-md">
                <div class="flex items-center gap-3">
                    <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                    <span class="font-label-mono text-[11px] text-ink uppercase tracking-widest">Sedang Mengedit</span>
                </div>
                <div class="flex items-center gap-3">
                    <button class="text-ink-dim hover:text-ink font-label-mono text-[11px] px-3 py-2 rounded-md hover:bg-surface-2 transition-colors">Simpan Draft</button>
                    <button class="bg-primary text-on-primary px-5 py-2 rounded-full font-label-mono text-[11px] hover:bg-primary-fixed transition-colors shadow-lg shadow-primary/10">Publikasikan</button>
                </div>
            </div>

            <!-- Editor Body -->
            <div class="flex-1 overflow-y-auto p-8 space-y-8">
                <!-- Cover Image -->
                <div class="space-y-3">
                    <label class="font-body-md text-[13px] text-ink-dim block">Gambar Sampul</label>
                    <div class="w-full h-48 rounded-xl border border-dashed border-outline-variant bg-surface-container hover:bg-surface-2 transition-colors flex flex-col items-center justify-center cursor-pointer group relative overflow-hidden">
                        <div class="relative z-10 flex flex-col items-center gap-2 bg-background/80 p-4 rounded-lg backdrop-blur-sm border border-line">
                            <span class="material-symbols-outlined text-primary text-[28px]">add_photo_alternate</span>
                            <span class="font-label-mono text-[11px] text-ink">Unggah Gambar Sampul</span>
                        </div>
                    </div>
                </div>

                <!-- Meta: Kategori & Tanggal -->
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="font-body-md text-[13px] text-ink-dim block">Kategori</label>
                        <select class="w-full bg-surface border border-line rounded-lg px-4 py-3 text-ink font-body-md focus:outline-none focus:border-primary appearance-none cursor-pointer">
                            <option>Pengumuman</option>
                            <option selected>Kegiatan</option>
                            <option>Bansos</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="font-body-md text-[13px] text-ink-dim block">Tanggal Publikasi</label>
                        <input class="w-full bg-surface border border-line rounded-lg px-4 py-3 text-ink font-body-md focus:outline-none focus:border-primary" type="date" value="2024-10-08"/>
                    </div>
                </div>

                <!-- Judul -->
                <div class="space-y-2">
                    <label class="font-body-md text-[13px] text-ink-dim block">Judul Berita</label>
                    <textarea class="w-full bg-transparent border-none p-0 text-h2 font-h2 text-ink focus:ring-0 resize-none placeholder:text-surface-variant leading-tight" placeholder="Masukkan judul berita..." rows="2">Panen Raya Kopi Robusta Air Naningan Sukses Digelar</textarea>
                </div>

                <!-- Toolbar -->
                <div class="sticky top-0 z-10 bg-surface-container-lowest py-2 border-y border-line flex items-center gap-1 overflow-x-auto">
                    <button class="p-2 text-ink hover:bg-surface-2 rounded"><span class="material-symbols-outlined text-[18px]">format_bold</span></button>
                    <button class="p-2 text-ink hover:bg-surface-2 rounded"><span class="material-symbols-outlined text-[18px]">format_italic</span></button>
                    <button class="p-2 text-ink hover:bg-surface-2 rounded"><span class="material-symbols-outlined text-[18px]">format_underlined</span></button>
                    <div class="w-px h-5 bg-line mx-2"></div>
                    <button class="p-2 text-ink hover:bg-surface-2 rounded"><span class="material-symbols-outlined text-[18px]">format_list_bulleted</span></button>
                    <button class="p-2 text-ink hover:bg-surface-2 rounded"><span class="material-symbols-outlined text-[18px]">format_list_numbered</span></button>
                    <div class="w-px h-5 bg-line mx-2"></div>
                    <button class="p-2 text-ink hover:bg-surface-2 rounded"><span class="material-symbols-outlined text-[18px]">add_photo_alternate</span></button>
                    <button class="p-2 text-ink hover:bg-surface-2 rounded"><span class="material-symbols-outlined text-[18px]">link</span></button>
                </div>

                <!-- Content Area -->
                <div class="min-h-[400px]">
                    <div class="font-body-md text-ink-dim outline-none leading-relaxed space-y-4" contenteditable="true">
                        <p>Petani kopi di Pekon Air Naningan merayakan panen raya dengan hasil yang melimpah tahun ini. Cuaca yang mendukung serta pendampingan penyuluh pertanian desa membuahkan hasil panen kopi robusta yang berkualitas premium.</p>
                        <p>Kepala Pekon menyampaikan apresiasinya kepada seluruh kelompok tani yang telah bekerja keras mempertahankan kualitas indikasi geografis kopi Air Naningan.</p>
                        <p class="text-surface-variant italic">Ketik di sini untuk melanjutkan berita...</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const createBtn = document.getElementById('btn-create-news');
    const titleArea = document.querySelector('textarea');
    const editorBody = document.querySelector('[contenteditable="true"]');
    if (createBtn) {
        createBtn.addEventListener('click', () => {
            titleArea.value = '';
            editorBody.innerHTML = '<p class="text-surface-variant italic">Mulai menulis berita baru...</p>';
            titleArea.focus();
        });
    }
});
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
