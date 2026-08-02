<?php
$currentPage     = 'profil-desa';
$pageTitle       = 'Profil Desa | Pekon Air Naningan';
$metaDescription = 'Profil Pekon Air Naningan — sejarah, visi misi, struktur pemerintahan, data demografi, transparansi anggaran, dan peta administrasi.';
require __DIR__ . '/../partials/header.php';
?>

<div class="flex flex-col w-full text-on-surface">

    <!-- Page Header & Breadcrumb -->
    <section class="w-full pt-12 pb-8 bg-surface-container-lowest">
        <div class="max-w-container-max mx-auto px-container-pad-mobile lg:px-container-pad-desktop">
            <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-label-mono text-ink-dim uppercase mb-6">
                <a class="hover:text-gold-soft transition-colors" href="/">Beranda</a>
                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                <span class="text-primary">Profil Desa</span>
            </nav>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-end">
                <div>
                    <h1 class="font-h1-mobile lg:font-h1 text-h1-mobile lg:text-h1 text-ink mb-4">Profil Pekon Air Naningan</h1>
                    <p class="font-body-lg text-body-lg text-ink-dim max-w-xl">
                        Mengenal lebih dekat identitas, arah juang, dan tata kelola masyarakat agraris di jantung Tanggamus.
                    </p>
                </div>
                <div class="hidden lg:flex justify-end pb-2">
                    <div class="flex items-center gap-4 bg-surface px-6 py-3 rounded-full border border-line">
                        <span class="font-label-mono text-label-mono text-gold-soft">BERDIRI SEJAK</span>
                        <span class="w-px h-4 bg-line-strong"></span>
                        <span class="font-h3 text-h3 text-ink">1982</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Visi & Misi -->
    <section class="w-full py-section-v-mobile lg:py-section-v-desktop relative overflow-hidden">
        <div class="absolute inset-0 bg-surface -z-10"></div>
        <div class="absolute -top-64 -right-64 w-[800px] h-[800px] bg-gradient-to-br from-primary/5 to-transparent rounded-full blur-3xl -z-10 pointer-events-none"></div>
        <div class="max-w-container-max mx-auto px-container-pad-mobile lg:px-container-pad-desktop">
            <div class="flex flex-col lg:flex-row gap-16 lg:gap-24">
                <!-- Visi -->
                <div class="flex-1 flex flex-col gap-6">
                    <div class="flex items-center gap-4">
                        <span class="w-12 h-px bg-primary"></span>
                        <h2 class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest">Arah Juang</h2>
                    </div>
                    <h3 class="font-h2 text-h2 text-ink">"Mewujudkan Pekon Air Naningan yang Mandiri, Sejahtera, dan Berbudaya melalui Optimalisasi Potensi Pertanian Kopi."</h3>
                </div>
                <!-- Misi -->
                <div class="flex-1 flex flex-col gap-8">
                    <h4 class="font-label-mono text-label-mono text-ink-dim uppercase tracking-widest border-b border-line pb-4">Misi Pekon</h4>
                    <ul class="flex flex-col gap-6">
                        <li class="flex gap-4">
                            <span class="font-h3 text-h3 text-primary/40">01</span>
                            <p class="font-body-lg text-body-lg text-ink">Meningkatkan kualitas tata kelola pemerintahan pekon yang transparan dan akuntabel.</p>
                        </li>
                        <li class="flex gap-4">
                            <span class="font-h3 text-h3 text-primary/40">02</span>
                            <p class="font-body-lg text-body-lg text-ink">Mendorong hilirisasi produk unggulan komoditas kopi robusta lokal.</p>
                        </li>
                        <li class="flex gap-4">
                            <span class="font-h3 text-h3 text-primary/40">03</span>
                            <p class="font-body-lg text-body-lg text-ink">Meningkatkan kesadaran masyarakat akan kelestarian lingkungan dan budaya gotong royong.</p>
                        </li>
                        <li class="flex gap-4">
                            <span class="font-h3 text-h3 text-primary/40">04</span>
                            <p class="font-body-lg text-body-lg text-ink">Membangun infrastruktur dasar yang mendukung akses ekonomi dan sosial kemasyarakatan.</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Struktur Organisasi & Data -->
    <section class="w-full py-section-v-mobile lg:py-section-v-desktop bg-surface-container-lowest">
        <div class="max-w-container-max mx-auto px-container-pad-mobile lg:px-container-pad-desktop">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter">

                <!-- Org Chart -->
                <div class="lg:col-span-4 flex flex-col gap-8">
                    <div class="flex flex-col gap-2">
                        <h2 class="font-h3 text-h3 text-ink">Struktur Pemerintahan</h2>
                        <p class="font-body-md text-body-md text-ink-dim">Aparatur pekon masa bakti 2022 - 2028.</p>
                    </div>
                    <div class="flex flex-col gap-4 relative before:absolute before:inset-y-0 before:left-[19px] before:w-px before:bg-line-strong before:-z-10">
                        <div class="flex items-center gap-4 group">
                            <div class="w-10 h-10 rounded-full bg-surface-2 overflow-hidden flex-shrink-0 border-2 border-surface shadow-sm group-hover:border-primary transition-colors">
                                <img class="w-full h-full object-cover"
                                     alt="Kepala Pekon Budi Santoso"
                                     src="https://lh3.googleusercontent.com/aida-public/AB6AXuClOPTvSysQaxfaVtjru7aFRFRvPIihT_jSx0qHiRl-ICk63YCzmSzISkUxoM0oVYuIu59WxgzGwnsjY1sQoGUOv5khXTgnvwKsTB3xSarrUJWy8-CunOT1SQdCNY6DjZSJfPF5tSbvcVoXjXVuEP3vS6XG8ZYbqJjSkpLWXEMEfXvP4U7gnWPQPybSjBZIKTKycoaymdP1imoWJ9te-00J7L2IiiQMNfYRsPoe28psu4qe5X9mcvMG9A">
                            </div>
                            <div class="flex flex-col">
                                <span class="font-body-md text-body-md text-ink font-semibold">Budi Santoso</span>
                                <span class="font-label-mono text-label-mono text-gold-soft">Kepala Pekon</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 group ml-4">
                            <div class="w-10 h-10 rounded-full bg-surface-2 overflow-hidden flex-shrink-0 border-2 border-surface shadow-sm group-hover:border-primary transition-colors">
                                <img class="w-full h-full object-cover"
                                     alt="Sekretaris Pekon Ahmad Fauzan"
                                     src="https://lh3.googleusercontent.com/aida-public/AB6AXuCoa8AKBIj44m8tTq_MCk6Co_50_UxHKRUmpa8yTF-NUvqcWEL-J8iKJpHrY2_3lGHHQ5iOrLevVKt_4TCuaMbsNgRDVgnKGiFmFyoIu70_KXjNvAhobUlD_EGtp3SdnAYOl20F1ucqKt13XegeZZPJRexSUdtv5OQowI2dN4wbk5SWRolBN2r4_phCg9PCSLtv4BB_k2thbK8-ZsNbVGr93CpU1EFttZbiUYisHEVmPcEJPvbcWX9R-Q">
                            </div>
                            <div class="flex flex-col">
                                <span class="font-body-md text-body-md text-ink font-semibold">Ahmad Fauzan</span>
                                <span class="font-label-mono text-label-mono text-ink-dim">Sekretaris Pekon</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 group ml-8">
                            <div class="w-10 h-10 rounded-full bg-surface-container flex-shrink-0 border-2 border-surface shadow-sm group-hover:border-primary transition-colors flex items-center justify-center">
                                <span class="material-symbols-outlined text-ink-dim">person</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-body-md text-body-md text-ink font-semibold">Siti Aminah</span>
                                <span class="font-label-mono text-label-mono text-ink-dim">Kaur Keuangan</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 group ml-8">
                            <div class="w-10 h-10 rounded-full bg-surface-container flex-shrink-0 border-2 border-surface shadow-sm group-hover:border-primary transition-colors flex items-center justify-center">
                                <span class="material-symbols-outlined text-ink-dim">person</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-body-md text-body-md text-ink font-semibold">Rahmat Hidayat</span>
                                <span class="font-label-mono text-label-mono text-ink-dim">Kasi Pemerintahan</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Demografi & Ekonomi -->
                <div class="lg:col-span-8 flex flex-col gap-8">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-surface-2 p-6 rounded-xl flex flex-col gap-2 border border-line">
                            <span class="font-label-mono text-label-mono text-ink-dim uppercase">Total Jiwa</span>
                            <span class="font-h2 text-h2 text-primary">3.245</span>
                        </div>
                        <div class="bg-surface-2 p-6 rounded-xl flex flex-col gap-2 border border-line">
                            <span class="font-label-mono text-label-mono text-ink-dim uppercase">Kepala Keluarga</span>
                            <span class="font-h2 text-h2 text-primary">842</span>
                        </div>
                        <div class="bg-surface-2 p-6 rounded-xl flex flex-col gap-2 border border-line">
                            <span class="font-label-mono text-label-mono text-ink-dim uppercase">Luas Wilayah</span>
                            <span class="font-h2 text-h2 text-primary">45<span class="text-h3 font-h3 text-ink-dim">km²</span></span>
                        </div>
                        <div class="bg-surface-2 p-6 rounded-xl flex flex-col gap-2 border border-line">
                            <span class="font-label-mono text-label-mono text-ink-dim uppercase">Ketinggian</span>
                            <span class="font-h2 text-h2 text-primary">850<span class="text-h3 font-h3 text-ink-dim">mdpl</span></span>
                        </div>
                    </div>
                    <!-- Mata Pencaharian Chart -->
                    <div class="bg-surface rounded-xl p-8 border border-line flex flex-col gap-6">
                        <h3 class="font-h3 text-h3 text-ink">Distribusi Mata Pencaharian</h3>
                        <div class="flex flex-col gap-4 w-full">
                            <div class="flex flex-col gap-1 w-full">
                                <div class="flex justify-between items-end">
                                    <span class="font-body-md text-body-md text-ink">Petani Kopi &amp; Kebun</span>
                                    <span class="font-label-mono text-label-mono text-gold-soft">75%</span>
                                </div>
                                <div class="w-full bg-surface-container-high h-2 rounded-full overflow-hidden">
                                    <div class="bg-primary h-full rounded-full" style="width:75%"></div>
                                </div>
                            </div>
                            <div class="flex flex-col gap-1 w-full">
                                <div class="flex justify-between items-end">
                                    <span class="font-body-md text-body-md text-ink">Pedagang / Wiraswasta</span>
                                    <span class="font-label-mono text-label-mono text-gold-soft">12%</span>
                                </div>
                                <div class="w-full bg-surface-container-high h-2 rounded-full overflow-hidden">
                                    <div class="bg-primary-container h-full rounded-full" style="width:12%"></div>
                                </div>
                            </div>
                            <div class="flex flex-col gap-1 w-full">
                                <div class="flex justify-between items-end">
                                    <span class="font-body-md text-body-md text-ink">Buruh Harian Lepas</span>
                                    <span class="font-label-mono text-label-mono text-gold-soft">8%</span>
                                </div>
                                <div class="w-full bg-surface-container-high h-2 rounded-full overflow-hidden">
                                    <div class="bg-primary-container h-full rounded-full" style="width:8%"></div>
                                </div>
                            </div>
                            <div class="flex flex-col gap-1 w-full">
                                <div class="flex justify-between items-end">
                                    <span class="font-body-md text-body-md text-ink">PNS / TNI / Polri</span>
                                    <span class="font-label-mono text-label-mono text-gold-soft">5%</span>
                                </div>
                                <div class="w-full bg-surface-container-high h-2 rounded-full overflow-hidden">
                                    <div class="bg-primary-container h-full rounded-full" style="width:5%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Transparansi APBDes -->
    <section class="w-full py-section-v-mobile lg:py-section-v-desktop bg-surface">
        <div class="max-w-container-max mx-auto px-container-pad-mobile lg:px-container-pad-desktop">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-10 gap-4">
                <div>
                    <h2 class="font-h2 text-h2 text-ink mb-2">Transparansi Anggaran (2024)</h2>
                    <p class="font-body-md text-body-md text-ink-dim">Ringkasan realisasi Anggaran Pendapatan dan Belanja Desa.</p>
                </div>
                <button class="px-6 py-2 rounded-full border border-line text-ink font-label-mono text-label-mono hover:bg-surface-2 transition-colors">
                    UNDUH LAPORAN LENGKAP
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-surface-2 p-6 rounded-xl flex flex-col gap-6 border border-line relative overflow-hidden group">
                    <div class="flex items-start justify-between relative z-10">
                        <div class="w-10 h-10 rounded bg-surface border border-line flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary">account_balance</span>
                        </div>
                        <span class="font-label-mono text-label-mono text-gold-soft bg-surface-container px-2 py-1 rounded">35%</span>
                    </div>
                    <div class="flex flex-col relative z-10">
                        <span class="font-body-md text-body-md text-ink-dim mb-1">Penyelenggaraan Pemerintahan</span>
                        <span class="font-h3 text-h3 text-ink">Rp 420.5M</span>
                    </div>
                    <div class="absolute inset-x-0 bottom-0 h-1 bg-primary/20 group-hover:bg-primary transition-colors"></div>
                </div>
                <div class="bg-surface-2 p-6 rounded-xl flex flex-col gap-6 border border-line relative overflow-hidden group">
                    <div class="flex items-start justify-between relative z-10">
                        <div class="w-10 h-10 rounded bg-surface border border-line flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary">construction</span>
                        </div>
                        <span class="font-label-mono text-label-mono text-gold-soft bg-surface-container px-2 py-1 rounded">42%</span>
                    </div>
                    <div class="flex flex-col relative z-10">
                        <span class="font-body-md text-body-md text-ink-dim mb-1">Pelaksanaan Pembangunan</span>
                        <span class="font-h3 text-h3 text-ink">Rp 510.2M</span>
                    </div>
                    <div class="absolute inset-x-0 bottom-0 h-1 bg-primary/20 group-hover:bg-primary transition-colors"></div>
                </div>
                <div class="bg-surface-2 p-6 rounded-xl flex flex-col gap-6 border border-line relative overflow-hidden group">
                    <div class="flex items-start justify-between relative z-10">
                        <div class="w-10 h-10 rounded bg-surface border border-line flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary">group</span>
                        </div>
                        <span class="font-label-mono text-label-mono text-gold-soft bg-surface-container px-2 py-1 rounded">15%</span>
                    </div>
                    <div class="flex flex-col relative z-10">
                        <span class="font-body-md text-body-md text-ink-dim mb-1">Pembinaan Kemasyarakatan</span>
                        <span class="font-h3 text-h3 text-ink">Rp 180.0M</span>
                    </div>
                    <div class="absolute inset-x-0 bottom-0 h-1 bg-primary/20 group-hover:bg-primary transition-colors"></div>
                </div>
                <div class="bg-surface-2 p-6 rounded-xl flex flex-col gap-6 border border-line relative overflow-hidden group">
                    <div class="flex items-start justify-between relative z-10">
                        <div class="w-10 h-10 rounded bg-surface border border-line flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary">trending_up</span>
                        </div>
                        <span class="font-label-mono text-label-mono text-gold-soft bg-surface-container px-2 py-1 rounded">8%</span>
                    </div>
                    <div class="flex flex-col relative z-10">
                        <span class="font-body-md text-body-md text-ink-dim mb-1">Pemberdayaan Masyarakat</span>
                        <span class="font-h3 text-h3 text-ink">Rp 95.8M</span>
                    </div>
                    <div class="absolute inset-x-0 bottom-0 h-1 bg-primary/20 group-hover:bg-primary transition-colors"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sejarah Desa -->
    <section class="w-full py-section-v-mobile lg:py-section-v-desktop bg-surface-container-lowest border-t border-line">
        <div class="max-w-container-max mx-auto px-container-pad-mobile lg:px-container-pad-desktop">
            <div class="max-w-3xl mx-auto flex flex-col gap-12">
                <div class="text-center flex flex-col gap-4">
                    <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest">Napak Tilas</span>
                    <h2 class="font-h2 text-h2 text-ink">Sejarah Air Naningan</h2>
                </div>
                <div class="font-body-lg text-body-lg text-ink-dim">
                    <p class="mb-6">
                        Pekon Air Naningan terbentuk pada tahun 1982 melalui program transmigrasi lokal yang diprakarsai oleh pemerintah daerah. Para pendahulu membuka hutan kawasan ini dengan semangat gotong royong yang tinggi, mengubah lahan rimbun menjadi lumbung pertanian yang subur, khususnya untuk komoditas kopi robusta.
                    </p>
                    <blockquote class="my-10 pl-6 border-l-4 border-primary italic font-h3 text-h3 text-ink bg-surface-2/50 py-4 pr-6 rounded-r-xl">
                        "Tanah ini bukan warisan nenek moyang semata, melainkan titipan untuk dijaga kelestariannya bagi generasi mendatang."
                    </blockquote>
                    <p class="mb-6">
                        Seiring berjalannya waktu, Air Naningan berkembang tidak hanya sebagai pusat produksi kopi berkualitas, tetapi juga mulai merintis potensi ekowisata berbasis komunitas. Karakter masyarakatnya yang terbuka namun tetap memegang teguh adat istiadat menjadi pondasi kuat dalam menyongsong era modernisasi pedesaan.
                    </p>
                    <p>
                        Kini, di bawah kepemimpinan yang progresif, Air Naningan berupaya memadukan tradisi bertani dengan teknologi tepat guna guna mewujudkan kesejahteraan yang merata bagi seluruh lapisan warganya.
                    </p>
                </div>
            </div>
        </div>
    </section>

</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
