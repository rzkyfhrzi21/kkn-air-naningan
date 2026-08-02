<?php
$currentPage     = 'berita';
$pageTitle       = 'Berita | Pekon Air Naningan';
$metaDescription = 'Informasi terkini, pengumuman resmi, dan dokumentasi kegiatan masyarakat di lingkungan Pekon Air Naningan.';
require __DIR__ . '/../partials/header.php';
?>

<div class="flex flex-col w-full min-h-screen bg-bg">

    <!-- Hero Header -->
    <div class="relative w-full h-[409px] min-h-[300px] flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 bg-surface-container-lowest z-0">
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-surface-2/40 via-bg to-bg opacity-70"></div>
            <!-- Grid pattern -->
            <svg class="absolute w-full h-full opacity-5 pointer-events-none" xmlns="http://www.w3.org/2000/svg">
                <pattern id="grid-pattern" patternUnits="userSpaceOnUse" width="40" height="40">
                    <path class="text-gold-soft" d="M 40 0 L 0 0 0 40" fill="none" stroke="currentColor" stroke-width="1"></path>
                </pattern>
                <rect width="100%" height="100%" fill="url(#grid-pattern)"></rect>
            </svg>
        </div>
        <div class="relative z-10 max-w-container-max w-full px-container-pad-mobile lg:px-container-pad-desktop text-center flex flex-col items-center gap-6">
            <div class="inline-flex items-center gap-2 text-label-mono text-gold-soft uppercase tracking-widest px-4 py-2 rounded-full border border-line-strong bg-surface/30 backdrop-blur-sm">
                <a class="hover:text-primary transition-colors" href="/">Beranda</a>
                <span class="text-ink-dim/50">/</span>
                <span class="text-ink">Berita &amp; Informasi</span>
            </div>
            <div class="flex flex-col gap-4">
                <h1 class="font-h1 text-h1-mobile lg:text-h1 text-ink">Kabar Pekon Air Naningan</h1>
                <p class="font-body-lg text-body-lg text-ink-dim max-w-2xl mx-auto">
                    Informasi terkini, pengumuman resmi, dan dokumentasi kegiatan masyarakat di lingkungan Pekon Air Naningan.
                </p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-container-max w-full mx-auto px-container-pad-mobile lg:px-container-pad-desktop py-section-v-mobile lg:py-section-v-desktop flex flex-col gap-12">

        <!-- Filter Section -->
        <div class="flex flex-col md:flex-row items-center justify-between gap-6 border-b border-line pb-6">
            <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto pb-2 md:pb-0">
                <button class="px-6 py-2.5 rounded-full bg-primary text-on-primary font-body-md text-body-md whitespace-nowrap shadow-md shadow-primary/20 transition-transform hover:scale-105">Semua Berita</button>
                <button class="px-6 py-2.5 rounded-full bg-surface-2 text-ink hover:text-primary border border-line font-body-md text-body-md whitespace-nowrap transition-colors">Pengumuman</button>
                <button class="px-6 py-2.5 rounded-full bg-surface-2 text-ink hover:text-primary border border-line font-body-md text-body-md whitespace-nowrap transition-colors">Kegiatan</button>
                <button class="px-6 py-2.5 rounded-full bg-surface-2 text-ink hover:text-primary border border-line font-body-md text-body-md whitespace-nowrap transition-colors">Bantuan Sosial</button>
            </div>
            <div class="relative w-full md:w-64">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-ink-dim">search</span>
                <input class="w-full bg-surface border border-line rounded-full py-2.5 pl-10 pr-4 text-body-md text-ink placeholder-ink-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all"
                       placeholder="Cari berita..." type="text">
            </div>
        </div>

        <!-- News Grid -->
        <div class="flex flex-col gap-6">

            <!-- Article 1 -->
            <article class="group flex flex-col md:flex-row bg-surface-2 rounded-[14px] overflow-hidden border border-line hover:border-line-strong transition-all duration-300 shadow-sm hover:shadow-md">
                <div class="md:w-1/3 h-56 md:h-auto relative overflow-hidden">
                    <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                         alt="Gotong royong perbaikan saluran irigasi"
                         src="https://lh3.googleusercontent.com/aida-public/AB6AXuD9kE9jWIMsc6-HOx33QjvqNAdY-X1TCLDmii6rwAs5yBIj6YAbfROhp5uNMn3O3v48eRTi1sDQqnsJJ9YItVgUPaCP9YEY6UPVLlapvr9LHX-HYg2YISOFjsiHWjEFTduWwMVsUk5wZjSeuulwtxk8LoJu9d_xkEkdqsLb_E6dCkOMb7lOLQCD8FsRRpUhqpeVgOSaJlbm-9JvlwjSDUFUuQHwGYV3JEetPeCXd3iWswMbHoQIntGMTg">
                    <div class="absolute inset-0 bg-gradient-to-t from-bg/60 to-transparent md:hidden"></div>
                    <div class="absolute top-4 left-4">
                        <span class="px-3 py-1 bg-surface/80 backdrop-blur-md rounded-full text-label-mono text-gold-soft border border-line-strong uppercase">Kegiatan</span>
                    </div>
                </div>
                <div class="flex-1 p-6 flex flex-col justify-between gap-4">
                    <div class="flex flex-col gap-3">
                        <div class="flex items-center gap-2 text-label-mono text-ink-dim">
                            <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                            <time datetime="2023-10-24">24 Oktober 2023</time>
                            <span class="w-1 h-1 rounded-full bg-line-strong mx-2"></span>
                            <span class="material-symbols-outlined text-[16px]">person</span>
                            <span>Admin Pekon</span>
                        </div>
                        <h2 class="font-h3 text-h3 text-ink group-hover:text-primary transition-colors line-clamp-2">Gotong Royong Perbaikan Saluran Irigasi Sambut Musim Tanam</h2>
                        <p class="font-body-md text-body-md text-ink-dim line-clamp-3">Warga Pekon Air Naningan antusias mengikuti kegiatan gotong royong massal untuk membersihkan dan memperbaiki saluran irigasi utama menjelang musim tanam padi tahun ini.</p>
                    </div>
                    <a class="inline-flex items-center gap-2 text-gold-soft hover:text-primary font-body-md transition-colors w-fit" href="#">
                        Baca Selengkapnya
                        <span class="material-symbols-outlined text-[18px] transition-transform group-hover:translate-x-1">arrow_forward</span>
                    </a>
                </div>
            </article>

            <!-- Article 2 -->
            <article class="group flex flex-col md:flex-row bg-surface-2 rounded-[14px] overflow-hidden border border-line hover:border-line-strong transition-all duration-300 shadow-sm hover:shadow-md">
                <div class="md:w-1/3 h-56 md:h-auto relative overflow-hidden">
                    <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                         alt="Pendaftaran BLT Dana Desa"
                         src="https://lh3.googleusercontent.com/aida-public/AB6AXuCsB7MEgtG2hLcI0zAk3K_UIP8C66Il2m_qGEOuUKJv5rFFdq1fnMRgs_xtkPSnSicW34DNWZZF8swOHKCCO36yemc3o1X9hPYfXX0XMAzSJRV8GPqfq2CVh7JlAgzB06n9DvJcE_OyldgltHJPbBKVRTtiBrCbbQrqJd-tvUkJ_LjbYjwnu29-NKYCpEly8XnMOoHgyO-crF5OHfYa0Kc8Bk4KMvzr4mV8HcVdhLnRQHFq_QBxod18qg">
                    <div class="absolute inset-0 bg-gradient-to-t from-bg/60 to-transparent md:hidden"></div>
                    <div class="absolute top-4 left-4">
                        <span class="px-3 py-1 bg-surface/80 backdrop-blur-md rounded-full text-label-mono text-error border border-error-container uppercase">Pengumuman</span>
                    </div>
                </div>
                <div class="flex-1 p-6 flex flex-col justify-between gap-4">
                    <div class="flex flex-col gap-3">
                        <div class="flex items-center gap-2 text-label-mono text-ink-dim">
                            <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                            <time datetime="2023-10-20">20 Oktober 2023</time>
                            <span class="w-1 h-1 rounded-full bg-line-strong mx-2"></span>
                            <span class="material-symbols-outlined text-[16px]">person</span>
                            <span>Sekretaris Desa</span>
                        </div>
                        <h2 class="font-h3 text-h3 text-ink group-hover:text-primary transition-colors line-clamp-2">Pendaftaran Bantuan Langsung Tunai (BLT) Dana Desa Tahap IV Dibuka</h2>
                        <p class="font-body-md text-body-md text-ink-dim line-clamp-3">Pemerintah Pekon Air Naningan secara resmi membuka pendaftaran verifikasi penerima BLT Dana Desa untuk tahap keempat. Warga yang memenuhi kriteria diimbau segera melapor ke aparatur pekon.</p>
                    </div>
                    <a class="inline-flex items-center gap-2 text-gold-soft hover:text-primary font-body-md transition-colors w-fit" href="#">
                        Baca Selengkapnya
                        <span class="material-symbols-outlined text-[18px] transition-transform group-hover:translate-x-1">arrow_forward</span>
                    </a>
                </div>
            </article>

            <!-- Article 3 -->
            <article class="group flex flex-col md:flex-row bg-surface-2 rounded-[14px] overflow-hidden border border-line hover:border-line-strong transition-all duration-300 shadow-sm hover:shadow-md">
                <div class="md:w-1/3 h-56 md:h-auto relative overflow-hidden">
                    <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                         alt="Penyaluran bantuan sembako"
                         src="https://lh3.googleusercontent.com/aida-public/AB6AXuDZkPEhv1Qyx8La-o4CZXahp1KjR3X0bIcdySq3LEZkNwfxMdWsMSXirx0U6llACc5z4kJ1NGSFW2D-zJteyZUxXPhI8ltcGa-xLSRSVk8qIW8x-5IGYcT2bcfXrI7_E4pH6n5rF58zEUO8p6cOy1nAu0QdxnbYFXSpidl066BM-Whpz-rYZB8B7Zi6ODUGzBGmCrO6UsR3J-j7oOXFuz3aCCfJAB9YT4uvJ4hEuQe1C8wLNP5D5GUC4g">
                    <div class="absolute inset-0 bg-gradient-to-t from-bg/60 to-transparent md:hidden"></div>
                    <div class="absolute top-4 left-4">
                        <span class="px-3 py-1 bg-surface/80 backdrop-blur-md rounded-full text-label-mono text-secondary border border-secondary-container uppercase">Bantuan Sosial</span>
                    </div>
                </div>
                <div class="flex-1 p-6 flex flex-col justify-between gap-4">
                    <div class="flex flex-col gap-3">
                        <div class="flex items-center gap-2 text-label-mono text-ink-dim">
                            <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                            <time datetime="2023-10-15">15 Oktober 2023</time>
                            <span class="w-1 h-1 rounded-full bg-line-strong mx-2"></span>
                            <span class="material-symbols-outlined text-[16px]">person</span>
                            <span>Kasi Kesejahteraan</span>
                        </div>
                        <h2 class="font-h3 text-h3 text-ink group-hover:text-primary transition-colors line-clamp-2">Penyaluran Bantuan Sembako untuk Keluarga Prasejahtera Berjalan Lancar</h2>
                        <p class="font-body-md text-body-md text-ink-dim line-clamp-3">Penyaluran program bantuan sembako bulanan untuk keluarga prasejahtera di Pekon Air Naningan telah selesai dilaksanakan. Proses distribusi dipusatkan di Balai Pekon dan berjalan tertib.</p>
                    </div>
                    <a class="inline-flex items-center gap-2 text-gold-soft hover:text-primary font-body-md transition-colors w-fit" href="#">
                        Baca Selengkapnya
                        <span class="material-symbols-outlined text-[18px] transition-transform group-hover:translate-x-1">arrow_forward</span>
                    </a>
                </div>
            </article>

            <!-- Article 4 -->
            <article class="group flex flex-col md:flex-row bg-surface-2 rounded-[14px] overflow-hidden border border-line hover:border-line-strong transition-all duration-300 shadow-sm hover:shadow-md">
                <div class="md:w-1/3 h-56 md:h-auto relative overflow-hidden">
                    <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                         alt="Pelatihan pasca panen kopi robusta"
                         src="https://lh3.googleusercontent.com/aida-public/AB6AXuDYMuWShzW6WkHvt9br1_iiI9EMJjU0jTArEvDRV2xZPfZUoihhn9f9HkHk_pjk4J8pA1V7eOG1TP9YVQ26BLGb58bWB_1GRvGvbsSuWPxoLU03tq6w-0H_tvHiYNxp_K9VFRCpp-EP12jmkOy-ODuUNkUqvZaYnGpKmTMZJLwk1aczD_enZk_wjunuFoJncnrMcoPYbLBJ41rbqt2CaOTMxEpsxE_Zr8auKJ0EZK-54Xb37wCs2Bj1Og">
                    <div class="absolute inset-0 bg-gradient-to-t from-bg/60 to-transparent md:hidden"></div>
                    <div class="absolute top-4 left-4">
                        <span class="px-3 py-1 bg-surface/80 backdrop-blur-md rounded-full text-label-mono text-gold-soft border border-line-strong uppercase">Kegiatan</span>
                    </div>
                </div>
                <div class="flex-1 p-6 flex flex-col justify-between gap-4">
                    <div class="flex flex-col gap-3">
                        <div class="flex items-center gap-2 text-label-mono text-ink-dim">
                            <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                            <time datetime="2023-10-10">10 Oktober 2023</time>
                            <span class="w-1 h-1 rounded-full bg-line-strong mx-2"></span>
                            <span class="material-symbols-outlined text-[16px]">person</span>
                            <span>Admin Pekon</span>
                        </div>
                        <h2 class="font-h3 text-h3 text-ink group-hover:text-primary transition-colors line-clamp-2">Pelatihan Peningkatan Kualitas Pasca Panen Kopi Robusta</h2>
                        <p class="font-body-md text-body-md text-ink-dim line-clamp-3">Bekerja sama dengan Dinas Pertanian Kabupaten Tanggamus, kelompok tani Pekon Air Naningan mengadakan pelatihan intensif mengenai teknik pasca panen kopi robusta untuk meningkatkan nilai jual.</p>
                    </div>
                    <a class="inline-flex items-center gap-2 text-gold-soft hover:text-primary font-body-md transition-colors w-fit" href="#">
                        Baca Selengkapnya
                        <span class="material-symbols-outlined text-[18px] transition-transform group-hover:translate-x-1">arrow_forward</span>
                    </a>
                </div>
            </article>

        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-center pt-8 border-t border-line mt-4">
            <nav class="flex items-center gap-2">
                <button class="w-10 h-10 flex items-center justify-center rounded-full bg-surface border border-line text-ink-dim hover:text-ink hover:border-line-strong transition-colors disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>
                <button class="w-10 h-10 flex items-center justify-center rounded-full bg-primary text-on-primary font-label-mono text-label-mono shadow-sm shadow-primary/20">1</button>
                <button class="w-10 h-10 flex items-center justify-center rounded-full bg-surface border border-line text-ink hover:bg-surface-2 transition-colors font-label-mono text-label-mono">2</button>
                <button class="w-10 h-10 flex items-center justify-center rounded-full bg-surface border border-line text-ink hover:bg-surface-2 transition-colors font-label-mono text-label-mono">3</button>
                <span class="text-ink-dim px-2">...</span>
                <button class="w-10 h-10 flex items-center justify-center rounded-full bg-surface border border-line text-ink hover:bg-surface-2 transition-colors font-label-mono text-label-mono">12</button>
                <button class="w-10 h-10 flex items-center justify-center rounded-full bg-surface border border-line text-ink hover:bg-surface-2 hover:border-line-strong transition-colors">
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>
            </nav>
        </div>

    </div>

</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
