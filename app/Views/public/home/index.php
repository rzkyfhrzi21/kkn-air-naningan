<?php
/* ======================================================
   VIEW BERANDA PUBLIK (HOME PAGE VIEW)

   File ini ibarat "ruang tamu utama website Pekon Air Naningan":
   halaman pertama yang dilihat pengunjung saat membuka website pekon.

   Data / Variabel yang dipanggil di file ini:
   - $homepage['stats']        : Statistik desa (luas wilayah, total jiwa, kk, dusun, ketinggian mdpl)
   - $homepage['tagline']      : Tagline / deskripsi singkat pekon
   - $homepage['history']      : Sejarah singkat pekon (paragraf & quote)
   - $homepage['hamlets']      : Daftar dusun beserta jumlah warganya
- $homepage['livelihoods']  : Data mata pencaharian warga (persentase kopi/aren/dll)
   - $homepage['latest_news']  : Berita desa terbaru yang baru saja diterbitkan
   - $homepage['featured_media']: Galeri foto & video unggulan desa
====================================================== */

$currentPage     = 'beranda';
$pageTitle       = 'Pekon Air Naningan Tanggamus | Website Resmi Desa';
$homepage        = $homepage ?? [];
$metaDescription = 'Website resmi Pekon Air Naningan, Tanggamus. Temukan profil desa, data pemerintahan, UMKM lokal, berita terbaru, galeri, dan layanan warga.';
$metaKeywords    = 'Pekon Air Naningan, Desa Air Naningan, Air Naningan Tanggamus, website desa Tanggamus, UMKM Air Naningan';
$history         = $homepage['history'] ?? [];
$hamlets         = is_array($homepage['hamlets'] ?? null) ? $homepage['hamlets'] : [];
$livelihoods     = is_array($homepage['livelihoods'] ?? null) ? $homepage['livelihoods'] : [];
require __DIR__ . '/../partials/header.php';
?>
    <section class="relative pt-24 lg:pt-32 pb-section-v-mobile lg:pb-section-v-desktop px-container-pad-mobile lg:px-container-pad-desktop overflow-hidden">
        <!-- Abstract wavy background lines (SVG) -->
        <div class="absolute inset-0 pointer-events-none opacity-20 text-gold-soft">
            <svg class="w-full h-full" fill="none" preserveAspectRatio="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 1440 600">
                <path d="M0,150 C320,300 420,0 720,150 C1020,300 1120,0 1440,150"></path>
                <path d="M0,250 C320,400 420,100 720,250 C1020,400 1120,100 1440,250"></path>
                <path d="M0,350 C320,500 420,200 720,350 C1020,500 1120,200 1440,350"></path>
            </svg>
        </div>
        <div class="relative max-w-container-max mx-auto z-10 flex flex-col items-start">
            <div class="font-label-mono text-label-mono text-gold-soft tracking-[0.2em] uppercase mb-8 flex items-center gap-4">
                <span>Lampung</span>
                <span class="w-1 h-1 rounded-full bg-gold-soft"></span>
                <span>Kaki Gunung Tanggamus</span>
            </div>
            <h1 class="font-h1 text-[48px] lg:text-[72px] leading-[1.05] tracking-tight text-ink max-w-4xl mb-8">
                Pekon Air Naningan, <br class="hidden md:block">
                rumah bagi kebun kopi <br class="hidden md:block">
                dan <span class="text-gold-soft italic font-serif">aren di lereng gunung.</span>
            </h1>
            <p class="font-body-lg text-body-lg text-ink-dim max-w-xl mb-12 leading-relaxed">
                <?= htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8') ?>
            </p>
            <div class="flex flex-wrap items-center gap-4">
                <a class="inline-flex items-center justify-center bg-primary text-on-primary font-body-md font-medium px-8 py-3.5 rounded-full hover:bg-primary-fixed transition-colors" href="<?= $base ?>/profil">
                    Jelajahi Desa &rarr;
                </a>
                <a class="inline-flex items-center justify-center bg-surface-container-lowest text-ink font-body-md font-medium px-8 py-3.5 rounded-full border border-line hover:bg-surface-container transition-colors" href="<?= $base ?>/umkm">
                    UMKM
                </a>
            </div>
        </div>
    </section>

    <!-- Statistics Grid -->
    <section class="px-container-pad-mobile lg:px-container-pad-desktop -mt-8 relative z-20">
        <div class="max-w-container-max mx-auto bg-surface-container border border-line rounded-xl overflow-hidden shadow-xl shadow-black/20">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 divide-x divide-y lg:divide-y-0 divide-line">
                <div class="p-8 flex flex-col">
                    <span class="material-symbols-outlined text-gold-soft mb-5" aria-hidden="true">elevation</span>
                    <span class="font-h2 text-h2 text-primary mb-2 flex items-baseline">
                        <?= number_format((int) ($homepage['elevation'] ?? 0), 0, ',', '.') ?>
                    </span>
                    <span class="font-label-mono text-label-mono text-ink-dim tracking-widest uppercase"><?= htmlspecialchars((string) ($homepage['elevation_unit'] ?? 'Mdpl'), ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <div class="p-8 flex flex-col">
                    <span class="material-symbols-outlined text-gold-soft mb-5" aria-hidden="true">holiday_village</span>
                    <span class="font-h2 text-h2 text-primary mb-2"><?= number_format((int) ($homepage['hamlet_count'] ?? 0), 0, ',', '.') ?></span>
                    <span class="font-label-mono text-label-mono text-ink-dim tracking-widest uppercase">Dusun</span>
                </div>
                <div class="p-8 flex flex-col">
                    <span class="material-symbols-outlined text-gold-soft mb-5" aria-hidden="true">groups</span>
                    <span class="font-h2 text-h2 text-primary mb-2"><?= number_format((int) ($homepage['population'] ?? 0), 0, ',', '.') ?></span>
                    <span class="font-label-mono text-label-mono text-ink-dim tracking-widest uppercase">Jiwa</span>
                </div>
                <div class="p-8 flex flex-col">
                    <span class="material-symbols-outlined text-gold-soft mb-5" aria-hidden="true">family_restroom</span>
                    <span class="font-h2 text-h2 text-primary mb-2"><?= number_format((int) ($homepage['household_count'] ?? 0), 0, ',', '.') ?></span>
                    <span class="font-label-mono text-label-mono text-ink-dim tracking-widest uppercase">Kepala Keluarga</span>
                </div>
                <div class="p-8 flex flex-col">
                    <span class="material-symbols-outlined text-gold-soft mb-5" aria-hidden="true">map</span>
                    <span class="font-h2 text-h2 text-primary mb-2"><?= number_format((float) ($homepage['area'] ?? 0), 0, ',', '.') ?></span>
                    <span class="font-label-mono text-label-mono text-ink-dim tracking-widest uppercase">Luas Wilayah (<?= htmlspecialchars((string) ($homepage['area_unit'] ?? ''), ENT_QUOTES, 'UTF-8') ?>)</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Sekilas Sejarah Section -->
    <section class="py-section-v-mobile lg:py-section-v-desktop px-container-pad-mobile lg:px-container-pad-desktop">
        <div class="max-w-container-max mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-24 items-start">
            <div class="lg:col-span-7 flex flex-col">
                <h2 class="font-h2 text-h2 text-ink mb-8">Sekilas sejarah</h2>
                <div class="prose prose-invert prose-lg text-ink-dim font-body-lg text-body-lg mb-8">
                    <?php foreach (array_slice($history, 0, 2) as $paragraph): ?>
                    <p class="mb-6"><?= htmlspecialchars($paragraph, ENT_QUOTES, 'UTF-8') ?></p>
                    <?php endforeach; ?>
                </div>
                <a class="font-label-mono text-[12px] text-gold-soft uppercase tracking-wider inline-flex items-center gap-2" href="<?= $base ?>/profil">
                    Baca sejarah lengkap <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </a>
            </div>
            <div class="lg:col-span-5 w-full">
                <div class="bg-surface-2 p-8 rounded-xl border border-line shadow-lg">
                    <div class="font-label-mono text-label-mono text-gold-soft tracking-widest uppercase mb-4">
                        Karakter Wilayah
                    </div>
                    <h3 class="font-h3 text-h3 text-ink mb-4">Agraris &amp; pegunungan</h3>
                    <p class="text-body-md font-body-md text-ink-dim leading-relaxed">
                        <?= htmlspecialchars((string) ($homepage['vision'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <?php if ($hamlets !== [] || $livelihoods !== []): ?>
    <section class="pb-section-v-mobile lg:pb-section-v-desktop px-container-pad-mobile lg:px-container-pad-desktop">
        <div class="max-w-container-max mx-auto border-y border-line py-10 lg:py-14">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6 mb-8">
                <div class="max-w-2xl">
                    <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest">Kehidupan Warga</span>
                    <h2 class="font-h2 text-h2 text-ink mt-3 mb-3">Demografi pekon</h2>
                    <p class="font-body-md text-body-md text-ink-dim">Lihat persebaran penduduk dan komposisi bidang pekerjaan masyarakat Air Naningan.</p>
                </div>
                <div class="flex gap-2 overflow-x-auto" role="tablist" aria-label="Data demografi pekon">
                    <?php if ($hamlets !== []): ?>
                        <button type="button" id="home-tab-hamlets" data-home-tab="hamlets" class="home-demography-tab whitespace-nowrap rounded-full border border-primary bg-primary px-5 py-2.5 font-body-md text-sm text-on-primary" role="tab" aria-selected="true" aria-controls="home-panel-hamlets">Penduduk per Dusun</button>
                    <?php endif; ?>
                    <?php if ($livelihoods !== []): ?>
                        <button type="button" id="home-tab-jobs" data-home-tab="jobs" class="home-demography-tab whitespace-nowrap rounded-full border border-line bg-surface-container px-5 py-2.5 font-body-md text-sm text-ink-dim" role="tab" aria-selected="<?= $hamlets === [] ? 'true' : 'false' ?>" aria-controls="home-panel-jobs">Bidang Pekerjaan</button>
                    <?php endif; ?>
                </div>
            </div>
            <?php if ($hamlets !== []): ?>
                <div id="home-panel-hamlets" data-home-panel="hamlets" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4" role="tabpanel" aria-labelledby="home-tab-hamlets">
                    <?php foreach ($hamlets as $hamlet): ?>
                        <article class="bg-surface-container rounded-xl border border-line p-6">
                            <span class="material-symbols-outlined text-gold-soft mb-5" aria-hidden="true">home_work</span>
                            <h3 class="font-h3 text-xl text-ink mb-2"><?= htmlspecialchars((string) ($hamlet['nama'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h3>
                            <p class="font-h3 text-h3 text-primary"><?= number_format((int) ($hamlet['jumlah'] ?? 0), 0, ',', '.') ?> <span class="font-body-md text-body-md text-ink-dim">jiwa</span></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php if ($livelihoods !== []): ?>
                <div id="home-panel-jobs" data-home-panel="jobs" class="<?= $hamlets !== [] ? 'hidden ' : '' ?>grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4" role="tabpanel" aria-labelledby="home-tab-jobs">
                    <?php foreach ($livelihoods as $item): ?>
                        <?php $percent = max(0, min(100, (int) ($item['persen'] ?? 0))); ?>
                        <?php $widthClass = 'w-[' . $percent . '%]'; ?>
                        <article class="bg-surface-container rounded-xl border border-line p-6 flex flex-col">
                            <span class="material-symbols-outlined text-gold-soft mb-5" aria-hidden="true">work</span>
                            <h3 class="font-body-lg text-body-lg font-medium text-ink mb-5 flex-grow"><?= htmlspecialchars((string) ($item['jenis'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h3>
                            <div class="flex items-end justify-between gap-4 mb-3">
                                <span class="font-h3 text-h3 text-primary"><?= $percent ?>%</span>
                                <span class="font-label-mono text-label-mono text-ink-dim uppercase">Warga</span>
                            </div>
                            <div class="h-2 rounded-full bg-surface-2 overflow-hidden"><div class="h-full rounded-full bg-primary <?= htmlspecialchars($widthClass, ENT_QUOTES, 'UTF-8') ?>"></div></div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Quick Links Grid -->
    <section class="pb-section-v-mobile lg:pb-section-v-desktop px-container-pad-mobile lg:px-container-pad-desktop">
        <div class="max-w-container-max mx-auto grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Profil Desa -->
            <a class="group bg-surface-container rounded-xl p-6 border border-line hover:border-gold-soft/50 hover:bg-surface-container-high transition-all duration-300 flex flex-col h-full" href="<?= $base ?>/profil">
                <div class="w-10 h-10 rounded border border-line flex items-center justify-center text-gold-soft mb-6 group-hover:bg-gold-soft/10 transition-colors">
                    <span class="material-symbols-outlined text-[20px]">apartment</span>
                </div>
                <h4 class="font-h3 text-xl text-ink mb-3">Profil Desa</h4>
                <p class="text-body-md font-body-md text-ink-dim mb-8 flex-grow">
                    Sejarah lengkap, visi misi, aparatur pekon, dan peta administrasi.
                </p>
                <div class="flex items-center gap-2 text-gold-soft font-label-mono text-[12px] uppercase tracking-wider group-hover:translate-x-1 transition-transform">
                    Lihat profil <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </div>
            </a>

            <!-- UMKM -->
            <a class="group bg-surface-container rounded-xl p-6 border border-line hover:border-gold-soft/50 hover:bg-surface-container-high transition-all duration-300 flex flex-col h-full" href="<?= $base ?>/umkm">
                <div class="w-10 h-10 rounded border border-line flex items-center justify-center text-gold-soft mb-6 group-hover:bg-gold-soft/10 transition-colors">
                    <span class="material-symbols-outlined text-[20px]">storefront</span>
                </div>
                <h4 class="font-h3 text-xl text-ink mb-3">UMKM</h4>
                <p class="text-body-md font-body-md text-ink-dim mb-8 flex-grow">
                    <?= number_format((int) ($homepage['umkm_count'] ?? 0), 0, ',', '.') ?> usaha warga dalam katalog, lengkap dengan kontak WhatsApp.
                </p>
                <div class="flex items-center gap-2 text-gold-soft font-label-mono text-[12px] uppercase tracking-wider group-hover:translate-x-1 transition-transform">
                    Lihat katalog <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </div>
            </a>

        </div>
    </section>

</div>

<script>
(() => {
    const tabs = document.querySelectorAll('[data-home-tab]');
    const panels = document.querySelectorAll('[data-home-panel]');

    tabs.forEach((tab) => {
        tab.addEventListener('click', () => {
            const activeName = tab.dataset.homeTab;
            tabs.forEach((item) => {
                const active = item === tab;
                item.setAttribute('aria-selected', active ? 'true' : 'false');
                item.classList.toggle('bg-primary', active);
                item.classList.toggle('border-primary', active);
                item.classList.toggle('text-on-primary', active);
                item.classList.toggle('bg-surface-container', !active);
                item.classList.toggle('border-line', !active);
                item.classList.toggle('text-ink-dim', !active);
            });
            panels.forEach((panel) => panel.classList.toggle('hidden', panel.dataset.homePanel !== activeName));
        });
    });
})();
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
