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
<a class="hover:text-gold-soft transition-colors" href="#">Beranda</a>
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
<div class="lg:col-span-12 flex flex-col gap-12">
<div class="flex flex-col gap-2 text-center">
<h2 class="font-h3 text-h3 text-ink">Struktur Pemerintahan</h2>
<p class="font-body-md text-body-md text-ink-dim">Aparatur pekon masa bakti 2022 - 2028.</p>
</div>
<div class="flex flex-col items-center gap-8">
<!-- Level 1: Kepala Pekon -->
<div class="bg-surface-2 rounded-sm border-2 border-primary px-6 py-4 text-center shadow-xl">
<p class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest mb-1">Kepala Pekon</p>
<p class="font-body-md font-semibold text-ink">Tri Sugiyanto</p>
</div>
<!-- Level 2: Branches -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-12 w-full">
<!-- Cabang Kiri: Kasi -->
<div class="flex flex-col items-center gap-6 border-t border-line pt-6">
<p class="font-label-mono text-label-mono text-ink-dim uppercase tracking-widest mb-2">Kepala Seksi (Kasi)</p>
<div class="flex flex-wrap justify-center gap-4">
<!-- Kasi Pemerintahan + Staf -->
<div class="flex flex-col gap-3">
<div class="bg-surface-2 rounded-sm border border-line px-4 py-3 text-center">
<p class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest mb-1">Kasi Pemerintahan</p>
<p class="font-body-md font-semibold text-ink">Widia Wati</p>
</div>
<div class="ml-4 pl-4 border-l-2 border-line-strong">
<div class="bg-surface-2 rounded-sm border border-line px-3 py-2 text-center">
<p class="font-label-mono text-[10px] text-ink-dim uppercase tracking-widest mb-1">Staf Pemerintahan</p>
<p class="text-sm font-medium text-ink">Agustina Heni K.</p>
</div>
</div>
</div>
<!-- Kasi Pelayanan -->
<div class="bg-surface-2 rounded-sm border border-line px-4 py-3 text-center h-fit">
<p class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest mb-1">Kasi Pelayanan</p>
<p class="font-body-md font-semibold text-ink">Agus Heriansyah</p>
</div>
<!-- Kasi Kesejahteraan -->
<div class="bg-surface-2 rounded-sm border border-line px-4 py-3 text-center h-fit">
<p class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest mb-1">Kasi Kesejahteraan</p>
<p class="font-body-md font-semibold text-ink">Nelli Fitriani</p>
</div>
</div>
</div>
<!-- Cabang Kanan: Sekretaris + Kaur -->
<div class="flex flex-col items-center gap-6 border-t border-line pt-6">
<p class="font-label-mono text-label-mono text-ink-dim uppercase tracking-widest mb-2">Sekretariat</p>
<div class="flex flex-col items-center gap-6">
<div class="bg-surface-2 rounded-sm border border-gold-soft px-4 py-3 text-center">
<p class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest mb-1">Sekretaris Pekon</p>
<p class="font-body-md font-semibold text-ink">Arfan M.Noor, SH</p>
</div>
<div class="flex flex-wrap justify-center gap-4">
<div class="bg-surface-2 rounded-sm border border-line px-4 py-3 text-center">
<p class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest mb-1">Kaur TU/Umum</p>
<p class="font-body-md font-semibold text-ink">Surahman</p>
</div>
<div class="bg-surface-2 rounded-sm border border-line px-4 py-3 text-center">
<p class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest mb-1">Kaur Keuangan</p>
<p class="font-body-md font-semibold text-ink">Inda Agustiana</p>
</div>
<div class="bg-surface-2 rounded-sm border border-line px-4 py-3 text-center">
<p class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest mb-1">Kaur Perencanaan</p>
<p class="font-body-md font-semibold text-ink">Hariyono</p>
</div>
</div>
</div>
</div>
</div>
<!-- Level 3: Kadus -->
<div class="w-full pt-8 border-t border-line-strong">
<p class="font-label-mono uppercase tracking-widest text-label-mono text-gold-soft text-center mb-6">Kepala Dusun (Kadus)</p>
<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3">
<div class="bg-surface-2 rounded-sm border border-line px-3 py-3 text-center">
<p class="font-label-mono text-[10px] text-gold-soft uppercase mb-1">Air Naningan</p>
<p class="text-sm font-medium text-ink">Agus</p>
</div>
<div class="bg-surface-2 rounded-sm border border-line px-3 py-3 text-center">
<p class="font-label-mono text-[10px] text-gold-soft uppercase mb-1">Neglasari</p>
<p class="text-sm font-medium text-ink">Mudasir</p>
</div>
<div class="bg-surface-2 rounded-sm border border-line px-3 py-3 text-center">
<p class="font-label-mono text-[10px] text-gold-soft uppercase mb-1">Mataram Selatan</p>
<p class="text-sm font-medium text-ink">Yadi Ruhyadi</p>
</div>
<div class="bg-surface-2 rounded-sm border border-line px-3 py-3 text-center">
<p class="font-label-mono text-[10px] text-gold-soft uppercase mb-1">Mataram Utara</p>
<p class="text-sm font-medium text-ink">Sugiyono</p>
</div>
<div class="bg-surface-2 rounded-sm border border-line px-3 py-3 text-center">
<p class="font-label-mono text-[10px] text-gold-soft uppercase mb-1">Padasuka</p>
<p class="text-sm font-medium text-ink">Soni Ari Anggara</p>
</div>
<div class="bg-surface-2 rounded-sm border border-line px-3 py-3 text-center">
<p class="font-label-mono text-[10px] text-gold-soft uppercase mb-1">Dusun 6</p>
<p class="text-sm font-medium text-ink">Joni Hendrawan</p>
</div>
<div class="bg-surface-2 rounded-sm border border-line px-3 py-3 text-center">
<p class="font-label-mono text-[10px] text-gold-soft uppercase mb-1">Pancasila</p>
<p class="text-sm font-medium text-ink">Suhedi</p>
</div>
</div>
</div>
</div>
</div>
<!-- Demografi & Ekonomi -->
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
<!-- Card 1 -->
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
<!-- Card 2 -->
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
<!-- Card 3 -->
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
<!-- Card 4 -->
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
<!-- Peta Wilayah -->
<section class="w-full py-section-v-mobile lg:py-section-v-desktop bg-surface-container-lowest">
<div class="max-w-container-max mx-auto px-container-pad-mobile lg:px-container-pad-desktop">
<div class="flex flex-col gap-6">
<h2 class="font-h2 text-h2 text-ink">Peta Administrasi</h2>
<div class="w-full h-[400px] lg:h-[500px] rounded-2xl overflow-hidden border border-line grayscale hover:grayscale-0 transition-all duration-700 bg-surface-2 flex items-center justify-center relative shadow-xl" data-location="Air Naningan, Tanggamus, Lampung, Indonesia" style="">
<div class="absolute inset-0 bg-gradient-to-t from-bg via-transparent to-transparent opacity-60"></div>
<span class="font-label-mono text-label-mono text-ink-dim relative z-10 bg-surface-container/80 px-4 py-2 rounded backdrop-blur-sm">MAP INTERACTIVE PLACEHOLDER (Google Maps API)</span>
</div>
</div>
</div>
</section>
<!-- Sejarah Desa -->
<section class="w-full py-section-v-mobile lg:py-section-v-desktop bg-surface border-t border-line">
<div class="max-w-container-max mx-auto px-container-pad-mobile lg:px-container-pad-desktop">
<div class="max-w-3xl mx-auto flex flex-col gap-12">
<div class="text-center flex flex-col gap-4">
<span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest">Napak Tilas</span>
<h2 class="font-h2 text-h2 text-ink">Sejarah Air Naningan</h2>
</div>
<div class="prose prose-invert prose-lg max-w-none text-ink-dim font-body-lg text-body-lg">
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
