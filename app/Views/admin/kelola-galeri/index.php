<?php
$pageTitle = 'Kelola Galeri';
$activeNav = 'kelola-galeri';
require __DIR__ . '/../partials/header.php';
?>
<div class="flex flex-col w-full px-container-pad-mobile lg:px-container-pad-desktop pb-section-v-desktop gap-10">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6 pt-10">
        <div class="flex flex-col gap-2 max-w-2xl">
            <h1 class="font-h1-mobile lg:font-h1 text-h1-mobile lg:text-h1 text-ink">Kelola Galeri</h1>
            <p class="font-body-lg text-body-lg text-ink-dim">Kelola koleksi foto dan video yang menampilkan keindahan, budaya, dan aktivitas Pekon Air Naningan.</p>
        </div>
        <div class="flex items-center gap-3">
            <button id="btn-delete-selected" class="px-6 py-3 rounded-full bg-surface-2 text-ink border border-line flex items-center gap-2 hover:bg-surface-container-high transition-colors">
                <span class="material-symbols-outlined text-[20px]">delete</span>
                <span class="font-label-mono text-label-mono uppercase tracking-widest">Hapus Dipilih (0)</span>
            </button>
            <button id="btn-create" class="px-6 py-3 rounded-full bg-primary text-on-primary font-label-mono text-label-mono uppercase tracking-widest hover:bg-primary-fixed transition-colors shadow-lg shadow-primary/20">
                Tambah Media
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex flex-col xl:flex-row gap-8">

        <!-- Sidebar: Upload + Filters -->
        <div class="w-full xl:w-[380px] shrink-0 flex flex-col gap-6">

            <!-- Upload Card -->
            <div class="bg-surface-container rounded-2xl p-6 border border-line flex flex-col gap-4">
                <div class="flex items-center gap-3 text-gold-soft">
                    <span class="material-symbols-outlined">cloud_upload</span>
                    <h3 class="font-h3 text-h3">Upload Aset</h3>
                </div>
                <div id="drop-zone" class="border-2 border-dashed border-line-strong rounded-xl p-8 flex flex-col items-center justify-center text-center gap-4 hover:border-primary/50 transition-colors cursor-pointer bg-surface/50 group">
                    <div class="w-16 h-16 rounded-full bg-surface-2 flex items-center justify-center text-ink-dim group-hover:text-primary transition-colors group-hover:scale-110 duration-300">
                        <span class="material-symbols-outlined text-3xl">add_photo_alternate</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="font-body-md text-body-md text-ink">Drag &amp; drop gambar di sini</span>
                        <span class="font-label-mono text-label-mono text-ink-dim">JPG, PNG hingga 10MB</span>
                    </div>
                    <button class="px-4 py-2 mt-2 rounded-full bg-surface-2 text-ink border border-line hover:border-primary transition-colors font-label-mono text-[10px] uppercase tracking-widest">
                        Browse Files
                    </button>
                </div>
            </div>

            <!-- Filters Card -->
            <div class="bg-surface-container rounded-2xl p-6 border border-line flex flex-col gap-6">
                <div class="flex items-center gap-3 text-gold-soft">
                    <span class="material-symbols-outlined">filter_list</span>
                    <h3 class="font-h3 text-h3">Filter</h3>
                </div>
                <div class="flex flex-col gap-4">
                    <div class="flex flex-col gap-2">
                        <label class="font-body-md text-body-md text-ink-dim">Kategori</label>
                        <select id="filter-kategori" class="w-full bg-surface border border-line rounded-lg px-4 py-3 text-ink font-body-md focus:outline-none focus:border-primary appearance-none">
                            <option value="">Semua Kategori</option>
                            <option value="alam">Alam &amp; Wisata</option>
                            <option value="kegiatan">Kegiatan Desa</option>
                            <option value="budaya">Budaya</option>
                            <option value="infrastruktur">Infrastruktur</option>
                        </select>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="font-body-md text-body-md text-ink-dim">Urutkan</label>
                        <div class="flex gap-2">
                            <button class="sort-btn flex-1 py-2 rounded-lg bg-surface-2 text-ink border border-primary text-sm font-label-mono" data-sort="newest">Terbaru</button>
                            <button class="sort-btn flex-1 py-2 rounded-lg bg-surface text-ink-dim border border-line hover:bg-surface-2 text-sm font-label-mono transition-colors" data-sort="oldest">Terlama</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gallery Grid -->
        <div id="gallery-grid" class="flex-1 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 auto-rows-max">

            <!-- Card 1 -->
            <div class="group relative bg-surface-container rounded-2xl overflow-hidden border border-line hover:border-primary/50 transition-all shadow-sm hover:shadow-xl hover:-translate-y-1 duration-300">
                <div class="absolute top-3 left-3 z-10">
                    <input class="w-5 h-5 rounded border-line-strong bg-surface/80 cursor-pointer accent-primary backdrop-blur-sm" type="checkbox"/>
                </div>
                <div class="absolute top-3 right-3 z-10 bg-surface/80 backdrop-blur-md px-3 py-1 rounded-full border border-line">
                    <span class="font-label-mono text-[10px] text-gold-soft uppercase tracking-wider">Alam</span>
                </div>
                <div class="w-full aspect-[4/3] bg-surface-2 relative overflow-hidden">
                    <div class="absolute inset-0 bg-cover bg-center group-hover:scale-105 transition-transform duration-700" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBbQgKkhvWd0ZWLAiTbBQuCKHz-2LKyiK-FvaNn-wNb9zkpDI1U4e_KjmQ68emx-Ql1TEJoQxIoTmv1A29z9RqkSmoQ9IauBmrV_1t4OeTHohfub1JH5Af4z9jjulYQqf1ObeYui2P02ygm422AI7sXYj62UpIsJLENBNU6dCb5cntkiMuiUBTkNUYR886opZmzEn_jNVSSp8Ojsvka10NBR0qWTtmFgjF7sxMePo622WXjZ_0z0qOBpw')"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-background/90 via-background/20 to-transparent opacity-60"></div>
                </div>
                <div class="p-5 flex flex-col gap-3">
                    <div class="flex flex-col gap-1">
                        <span class="text-ink font-body-lg">Lembah Kabut Pagi</span>
                        <span class="font-label-mono text-[10px] text-ink-dim">Ditambah 2 hari lalu</span>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="group relative bg-surface-container rounded-2xl overflow-hidden border border-line hover:border-primary/50 transition-all shadow-sm hover:shadow-xl hover:-translate-y-1 duration-300">
                <div class="absolute top-3 left-3 z-10">
                    <input class="w-5 h-5 rounded border-line-strong bg-surface/80 cursor-pointer accent-primary backdrop-blur-sm" type="checkbox"/>
                </div>
                <div class="absolute top-3 right-3 z-10 bg-surface/80 backdrop-blur-md px-3 py-1 rounded-full border border-line">
                    <span class="font-label-mono text-[10px] text-gold-soft uppercase tracking-wider">Budaya</span>
                </div>
                <div class="w-full aspect-[4/3] bg-surface-2 relative overflow-hidden">
                    <div class="absolute inset-0 bg-cover bg-center group-hover:scale-105 transition-transform duration-700" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAg8r-XCJPOhndkDSilLVMrsccGHJJjJPBvu_EY8yDCSjkK-P7XzdezxhLnSWfQmYqt0_E4DoHSVPeBHfyCL8jWkJqRC5CvMtIU9k9qmRTczRV9C9vRyT0A_wsKvIdhwuy9tswWYapXQUu6EaKyd4SkEf4qW4EIzpczXyh4f2IM5kt7M15XpJiXxFND9daWShlxGzAwpI4q602buXlAagmMoNyFJf93_jXOAyo3bTPmiPBelAs1B3j-oQ')"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-background/90 via-background/20 to-transparent opacity-60"></div>
                </div>
                <div class="p-5 flex flex-col gap-3">
                    <div class="flex flex-col gap-1">
                        <span class="text-ink font-body-lg">Panen Kopi Robusta 2024</span>
                        <span class="font-label-mono text-[10px] text-ink-dim">Ditambah 1 minggu lalu</span>
                    </div>
                </div>
            </div>

            <!-- Card 3 (Selected) -->
            <div class="group relative bg-surface-container rounded-2xl overflow-hidden border border-primary ring-1 ring-primary/20 shadow-xl shadow-primary/5 transition-all -translate-y-1 duration-300">
                <div class="absolute top-3 left-3 z-10">
                    <input checked class="w-5 h-5 rounded border-line-strong bg-surface/80 cursor-pointer accent-primary backdrop-blur-sm" type="checkbox"/>
                </div>
                <div class="absolute top-3 right-3 z-10 bg-surface/80 backdrop-blur-md px-3 py-1 rounded-full border border-line">
                    <span class="font-label-mono text-[10px] text-gold-soft uppercase tracking-wider">Kegiatan</span>
                </div>
                <div class="w-full aspect-[4/3] bg-surface-2 relative overflow-hidden">
                    <div class="absolute inset-0 bg-cover bg-center group-hover:scale-105 transition-transform duration-700" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBPJzDxjkj-YbBJLn68BXrkKDOOUPPE1WMfQfVF0sngF0pSXeDM2wxnlsE8MLI-PqxhqvklLq3iuKV3xp9SzK0bnn96UsuYECe7fA0nYM8OmfGOojSr_-JQamWfG8okgnQvXuTdA0yUkxtSy1l5RdmSPnCtg5H5Qmsih1bWtwCJ3-dVSbo5sZZ5HwU1YaEg6hMsDReUQs71w_C4dxSTlE-3VvBrTXvsX8i9-SUa98chmIgPjV3mRJP4Ew')"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-background/90 via-background/20 to-transparent opacity-60"></div>
                </div>
                <div class="p-5 flex flex-col gap-3">
                    <div class="flex flex-col gap-1">
                        <span class="text-ink font-body-lg">Gotong Royong Desa</span>
                        <span class="font-label-mono text-[10px] text-ink-dim">Ditambah 2 minggu lalu</span>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="group relative bg-surface-container rounded-2xl overflow-hidden border border-line hover:border-primary/50 transition-all shadow-sm hover:shadow-xl hover:-translate-y-1 duration-300 opacity-70">
                <div class="absolute inset-0 bg-background/40 z-20 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity backdrop-blur-sm">
                    <button class="w-12 h-12 rounded-full bg-surface border border-line flex items-center justify-center text-ink hover:text-danger hover:border-danger transition-colors">
                        <span class="material-symbols-outlined">delete</span>
                    </button>
                </div>
                <div class="absolute top-3 left-3 z-10">
                    <input class="w-5 h-5 rounded border-line-strong bg-surface/80 cursor-pointer accent-primary backdrop-blur-sm" type="checkbox"/>
                </div>
                <div class="absolute top-3 right-3 z-10 bg-surface/80 backdrop-blur-md px-3 py-1 rounded-full border border-line">
                    <span class="font-label-mono text-[10px] text-gold-soft uppercase tracking-wider">Infrastruktur</span>
                </div>
                <div class="w-full aspect-[4/3] bg-surface-2 relative overflow-hidden">
                    <div class="absolute inset-0 bg-cover bg-center group-hover:scale-105 transition-transform duration-700 grayscale-[30%]" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAVlTwaVKMgl3wQPqpD0VvscjKzgIOYfHxsMS3DIEIS1_03C9nZpwUMUzGIje61l3vH7AdG_5MwLu3qHCkADazkgqkJRoI6Ftch5sExyxvF2FsQye39uA60zukDG-THNKgzWz0Q7fheZoYQqnLh4Pm-f5Fpa41kYS0VWsuREmPkdOUgpnaEmEOEgsqnFb--HE4fATVYP5mNqx-4RRLXW0WlIjoRavVM3ZwRZis99YJj1xGagdejV5_5Nw')"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-background/90 via-background/20 to-transparent opacity-60"></div>
                </div>
                <div class="p-5 flex flex-col gap-3">
                    <div class="flex flex-col gap-1">
                        <span class="text-ink font-body-lg">Jalan Akses Desa</span>
                        <span class="font-label-mono text-[10px] text-ink-dim">Ditambah 1 bulan lalu</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
