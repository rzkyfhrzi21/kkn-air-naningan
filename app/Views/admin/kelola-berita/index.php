<?php
$pageTitle = 'Kelola Berita';
$activeNav = 'kelola-berita';
require __DIR__ . '/../partials/header.php';
?>
<div class="flex flex-col w-full min-h-full">

    <!-- Header Section -->
    <div class="px-container-pad-desktop py-8 md:py-12 flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-line">
        <div class="max-w-2xl">
            <h1 class="font-h1 text-h1-mobile md:text-h1 text-ink mb-3">Kelola Berita</h1>
            <p class="font-body-lg text-body-lg text-ink-dim">Publikasikan informasi terkini, pengumuman desa, dan liputan kegiatan Pekon Air Naningan.</p>
        </div>
        <button id="btn-create-news" class="bg-primary text-on-primary hover:bg-primary-fixed transition-colors rounded-full px-6 py-3 font-label-mono uppercase tracking-widest flex items-center justify-center gap-2 flex-shrink-0 shadow-xl shadow-primary/10">
            <span class="material-symbols-outlined text-[20px]">add</span>
            Tulis Berita Baru
        </button>
    </div>

    <!-- Main Content: Split View -->
    <div class="flex-1 flex flex-col xl:flex-row relative">

        <!-- News List (Left Panel) -->
        <div id="news-list-panel" class="w-full xl:w-1/2 2xl:w-7/12 border-r-0 xl:border-r border-line flex flex-col transition-all duration-500">

            <!-- Filter & Search -->
            <div class="p-6 border-b border-line bg-surface-container-low flex flex-col sm:flex-row gap-4 items-center justify-between sticky top-16 z-20">
                <div class="relative w-full sm:w-64">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-ink-dim text-[20px]">search</span>
                    <input id="search-berita" class="w-full bg-surface border border-line rounded-lg py-2.5 pl-10 pr-4 text-ink font-body-md focus:outline-none focus:border-primary transition-colors placeholder:text-surface-variant" placeholder="Cari judul berita..." type="text"/>
                </div>
                <div class="flex items-center gap-2 w-full sm:w-auto overflow-x-auto pb-1 sm:pb-0">
                    <button class="filter-btn active px-4 py-1.5 rounded-full bg-surface-2 text-ink border border-line-strong font-label-mono text-[11px] whitespace-nowrap" data-filter="">Semua</button>
                    <button class="filter-btn px-4 py-1.5 rounded-full bg-transparent text-ink-dim hover:text-ink border border-transparent hover:border-line font-label-mono text-[11px] whitespace-nowrap transition-colors" data-filter="pengumuman">Pengumuman</button>
                    <button class="filter-btn px-4 py-1.5 rounded-full bg-transparent text-ink-dim hover:text-ink border border-transparent hover:border-line font-label-mono text-[11px] whitespace-nowrap transition-colors" data-filter="kegiatan">Kegiatan</button>
                    <button class="filter-btn px-4 py-1.5 rounded-full bg-transparent text-ink-dim hover:text-ink border border-transparent hover:border-line font-label-mono text-[11px] whitespace-nowrap transition-colors" data-filter="draft">Draft</button>
                </div>
            </div>

            <!-- Table Header -->
            <div class="hidden sm:grid grid-cols-12 gap-4 px-8 py-4 border-b border-line bg-surface-container-lowest font-label-mono text-[10px] text-ink-dim uppercase tracking-widest sticky top-[137px] z-10">
                <div class="col-span-6">Judul Berita</div>
                <div class="col-span-2">Kategori</div>
                <div class="col-span-2">Status</div>
                <div class="col-span-2 text-right">Tanggal</div>
            </div>

            <!-- News Items -->
            <div id="news-list" class="flex-1 overflow-y-auto divide-y divide-line">

                <!-- Item 1 -->
                <div class="group cursor-pointer hover:bg-surface-2 transition-colors relative" data-id="1">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary scale-y-0 group-hover:scale-y-100 transition-transform origin-center"></div>
                    <div class="p-6 sm:px-8 sm:py-5 grid grid-cols-1 sm:grid-cols-12 gap-4 items-center">
                        <div class="sm:col-span-6 flex gap-4 items-start">
                            <div class="w-16 h-16 rounded-lg bg-surface-container overflow-hidden flex-shrink-0 relative">
                                <img class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAFOZjbtjGMOQr3w205EZuufKfIO7CMxUUSAFXTZBKKjpVPeIHGWMqPkUqKieHOTFc8iMo_Oq6exfKYJqtYvtsvvS-2qoiYG9T8XF4QgOMpXt0ONBq5ORp94JxEKFATnk0vZENsqR3fox9_DePSKVYSCHk6adwarY3QZLHVXsQ91XrRQrXHu7ncHoDXnfgGxBroy-yVXCcZuTeAl6WgxF22BhY09xbczyuRp91J-mXv6gAITgmCetzb7g" alt="BLT"/>
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-h3 text-[18px] text-ink leading-snug truncate mb-1 group-hover:text-primary transition-colors">Penyaluran BLT Dana Desa Tahap III Tahun 2024</h3>
                                <p class="text-ink-dim font-body-md text-[13px] line-clamp-1">Kegiatan penyaluran Bantuan Langsung Tunai (BLT) Dana Desa berjalan lancar di balai pekon...</p>
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
                        <div class="sm:col-span-2 sm:text-right flex items-center sm:justify-end gap-4 sm:gap-0 text-ink-dim font-label-mono text-[11px]">
                            <span>12 Okt 2024</span>
                        </div>
                    </div>
                </div>

                <!-- Item 2 -->
                <div class="group cursor-pointer hover:bg-surface-2 transition-colors relative" data-id="2">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary scale-y-0 group-hover:scale-y-100 transition-transform origin-center"></div>
                    <div class="p-6 sm:px-8 sm:py-5 grid grid-cols-1 sm:grid-cols-12 gap-4 items-center">
                        <div class="sm:col-span-6 flex gap-4 items-start">
                            <div class="w-16 h-16 rounded-lg bg-surface-container overflow-hidden flex-shrink-0 relative">
                                <div class="absolute inset-0 flex items-center justify-center bg-surface-variant text-ink-dim">
                                    <span class="material-symbols-outlined">image</span>
                                </div>
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-h3 text-[18px] text-ink leading-snug truncate mb-1 group-hover:text-primary transition-colors">Jadwal Posyandu Balita dan Lansia Bulan November</h3>
                                <p class="text-ink-dim font-body-md text-[13px] line-clamp-1">Diberitahukan kepada seluruh warga Pekon Air Naningan mengenai jadwal rutin Posyandu...</p>
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
                        <div class="sm:col-span-2 sm:text-right flex items-center sm:justify-end gap-4 sm:gap-0 text-ink-dim font-label-mono text-[11px]">
                            <span>15 Okt 2024</span>
                        </div>
                    </div>
                </div>

                <!-- Item 3 (Active) -->
                <div class="group cursor-pointer hover:bg-surface-2 transition-colors relative bg-surface-2" data-id="3">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary scale-y-100 transition-transform origin-center"></div>
                    <div class="p-6 sm:px-8 sm:py-5 grid grid-cols-1 sm:grid-cols-12 gap-4 items-center">
                        <div class="sm:col-span-6 flex gap-4 items-start">
                            <div class="w-16 h-16 rounded-lg bg-surface-container overflow-hidden flex-shrink-0 relative">
                                <img class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAMAB8i2tQJpH5Mq06ARBG1YRYaN7rbZidraZXe2JtavSUnL3kK8sW1mbP-2sIAahx1cpTxytuLj4p8vu17UWGQTuXg2neB_p0ZBud-RrburyYrL8kZGndxHRNVmzbyc87PA6eyCy3T-bCCnHQMJIWwu_Uy1ML0egXI4btEjgBuKYXHCWWT-ANW3Pdm2iR3hYlJ7Ur4lTKJ5sEjgLwmJqxUiWvbjHbSKU8Fmg2hMTan4y4xf8syVnEbEA" alt="Kopi"/>
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-h3 text-[18px] text-primary leading-snug truncate mb-1">Panen Raya Kopi Robusta Air Naningan Sukses Digelar</h3>
                                <p class="text-ink-dim font-body-md text-[13px] line-clamp-1">Petani kopi di Pekon Air Naningan merayakan panen raya dengan hasil yang melimpah tahun ini...</p>
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
                        <div class="sm:col-span-2 sm:text-right flex items-center sm:justify-end gap-4 sm:gap-0 text-ink-dim font-label-mono text-[11px]">
                            <span>08 Okt 2024</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Pagination -->
            <div class="p-6 border-t border-line flex items-center justify-between text-ink-dim font-label-mono text-[11px]">
                <span>Menampilkan 1-3 dari 24 berita</span>
                <div class="flex items-center gap-2">
                    <button class="w-8 h-8 rounded border border-line flex items-center justify-center hover:bg-surface-2 hover:text-ink transition-colors disabled:opacity-50">
                        <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                    </button>
                    <button class="w-8 h-8 rounded border border-line flex items-center justify-center hover:bg-surface-2 hover:text-ink transition-colors">
                        <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Editor Panel (Right Panel) -->
        <div id="editor-panel" class="w-full xl:w-1/2 2xl:w-5/12 bg-surface-container-lowest flex flex-col xl:sticky xl:top-16 xl:h-[calc(100vh-4rem)] border-t xl:border-t-0 border-line">

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
                        <img class="absolute inset-0 w-full h-full object-cover opacity-60 group-hover:opacity-40 transition-opacity" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA4pcA51X-n3xYFrswSGn_8K5dpzEbLCCkbz9x1XRhJGZikmKzJEsBIIRKCkrSbswZh4PHqTJ_W6bfKivZpz651UiBAqAdJOY3JfRWphTu46WkBbSAxFUAgNLj-S5qUSpRAmb7rMKHvb1MAFykOqmsDJ8vUeSehbcHMbvY7gqODhXoyfu1v97Mnz0vZq9KpwjDCFLejIc7nu_Zt0eLaIWmKH6KsHFCE-Pv_70e2IAYPmWX377jYXSsbig" alt="Cover"/>
                        <div class="relative z-10 flex flex-col items-center gap-2 bg-background/80 p-4 rounded-lg backdrop-blur-sm border border-line">
                            <span class="material-symbols-outlined text-primary text-[28px]">photo_camera</span>
                            <span class="font-label-mono text-[11px] text-ink">Ganti Gambar</span>
                        </div>
                    </div>
                </div>

                <!-- Meta Info -->
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

                <!-- Title -->
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
                    <div class="prose prose-invert max-w-none font-body-md text-ink-dim outline-none leading-relaxed space-y-4" contenteditable="true">
                        <p>Petani kopi di Pekon Air Naningan merayakan panen raya dengan hasil yang melimpah tahun ini. Cuaca yang mendukung serta pendampingan penyuluh pertanian desa membuahkan hasil panen kopi robusta yang berkualitas premium.</p>
                        <p>Kepala Pekon menyampaikan apresiasinya kepada seluruh kelompok tani yang telah bekerja keras mempertahankan kualitas indikasi geografis kopi Air Naningan.</p>
                        <p class="text-surface-variant italic">Ketik di sini untuk melanjutkan berita...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
