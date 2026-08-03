<?php
$currentPage     = 'wisata';
$pageTitle       = 'Wisata | Pekon Air Naningan';
$metaDescription = 'Temukan destinasi wisata alam di Pekon Air Naningan — air terjun, titik pandang pegunungan, dan pesona alam lereng Tanggamus.';
$base            = $base ?? '';
require __DIR__ . '/../partials/header.php';
?>

<div class="flex flex-col w-full overflow-hidden pb-section-v-desktop">

    <!-- Hero Section -->
    <section class="relative w-full h-[614px] min-h-[500px] flex items-center justify-center -mt-20 pt-20">
        <div class="absolute inset-0 bg-cover bg-center z-0"
             style="background-image: url('<?= htmlspecialchars($base . '/uploads/konten/wisata-hero.jpg', ENT_QUOTES, 'UTF-8') ?>')">
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-bg via-bg/60 to-transparent z-10"></div>
        <div class="relative z-20 w-full max-w-container-max mx-auto px-container-pad-mobile lg:px-container-pad-desktop text-center flex flex-col items-center">
            <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-[0.2em] mb-6 opacity-0 translate-y-4 animate-[fade-in-up_0.8s_ease-out_forwards]">Jelajahi Keindahan</span>
            <h1 class="font-h1-mobile lg:font-h1 text-h1-mobile lg:text-h1 text-ink mb-6 max-w-3xl opacity-0 translate-y-4 animate-[fade-in-up_0.8s_ease-out_0.2s_forwards]">
                Pesona Alam <br><span class="text-primary italic">Air Naningan</span>
            </h1>
            <p class="font-body-lg text-body-lg text-ink-dim max-w-2xl mx-auto opacity-0 translate-y-4 animate-[fade-in-up_0.8s_ease-out_0.4s_forwards]">
                Temukan surga tersembunyi di hamparan hijau perbukitan. Destinasi wisata alam yang menawarkan ketenangan dan petualangan tak terlupakan.
            </p>
        </div>
    </section>

    <!-- Destinations Grid Section -->
    <section class="w-full max-w-container-max mx-auto px-container-pad-mobile lg:px-container-pad-desktop -mt-16 relative z-30">
        <?php if (empty($items)): ?>
            <div class="flex flex-col items-center justify-center py-24 text-center gap-4">
                <span class="material-symbols-outlined text-[48px] text-ink-dim/30">landscape</span>
                <p class="font-body-md text-ink-dim">Belum ada destinasi wisata yang tersedia saat ini.</p>
                <a href="<?= htmlspecialchars($base, ENT_QUOTES, 'UTF-8') ?>/kontak" class="px-6 py-3 rounded-full bg-primary text-on-primary font-label-mono text-label-mono uppercase tracking-wider">
                    Hubungi Kami
                </a>
            </div>
        <?php else: ?>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
            <?php foreach ($items as $item): ?>
            <?php
                $slug      = htmlspecialchars($item['slug'] ?? '', ENT_QUOTES, 'UTF-8');
                $nama      = htmlspecialchars($item['nama'] ?? '', ENT_QUOTES, 'UTF-8');
                $katLabel  = htmlspecialchars($item['kategori_label'] ?? '', ENT_QUOTES, 'UTF-8');
                $katIcon   = htmlspecialchars($item['kategori_icon'] ?? 'landscape', ENT_QUOTES, 'UTF-8');
                $deskripsi = htmlspecialchars($item['deskripsi'] ?? '', ENT_QUOTES, 'UTF-8');
                $htm       = htmlspecialchars($item['jarak'] ?? '', ENT_QUOTES, 'UTF-8'); // field 'jarak' dipakai sebagai HTM
                $foto      = htmlspecialchars(mediaUrl($item['foto'] ?? '', $base), ENT_QUOTES, 'UTF-8');
                $mapsUrl   = htmlspecialchars($item['maps_url'] ?? '#', ENT_QUOTES, 'UTF-8');
                $fasilitas = $item['fasilitas'] ?? [];
                $offset    = !empty($item['offset']);
            ?>
            <article class="group bg-surface-container rounded-2xl overflow-hidden shadow-xl transition-transform duration-500 hover:-translate-y-2 border border-line flex flex-col h-full<?= $offset ? ' lg:translate-y-12' : '' ?>">
                <div class="relative h-72 w-full overflow-hidden">
                    <?php if ($foto): ?>
                    <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                         alt="<?= $nama ?> di Air Naningan"
                         loading="lazy"
                         onerror="this.onerror=null; this.src='<?= $base ?>/assets/images/placeholder.webp';"
                         src="<?= $foto ?>">
                    <?php else: ?>
                    <div class="w-full h-full bg-surface-container-high flex items-center justify-center">
                        <span class="material-symbols-outlined text-[64px] text-ink-dim/30">landscape</span>
                    </div>
                    <?php endif; ?>
                    <div class="absolute top-4 left-4 bg-bg/80 backdrop-blur-md px-3 py-1 rounded-full border border-line">
                        <span class="font-label-mono text-[10px] text-primary uppercase tracking-wider flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[14px]"><?= $katIcon ?></span>
                            <?= $katLabel ?>
                        </span>
                    </div>
                </div>
                <div class="p-8 flex flex-col flex-grow">
                    <div class="flex justify-between items-start mb-4">
                        <h2 class="font-h2 text-[28px] text-ink leading-tight"><?= $nama ?></h2>
                        <?php if ($htm): ?>
                        <span class="flex items-center gap-1 text-ink-dim font-label-mono text-label-mono shrink-0 ml-4">
                            <span class="material-symbols-outlined text-[16px] text-gold-soft">confirmation_number</span>
                            <?= $htm ?>
                        </span>
                        <?php endif; ?>
                    </div>
                    <p class="font-body-md text-body-md text-ink-dim mb-6 flex-grow line-clamp-3">
                        <?= $deskripsi ?>
                    </p>
                    <?php if (!empty($fasilitas)): ?>
                    <div class="flex flex-wrap gap-2 mb-8">
                        <?php foreach ($fasilitas as $fas): ?>
                        <span class="px-3 py-1 bg-surface-2 rounded-full text-ink-dim text-xs font-label-mono flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]"><?= htmlspecialchars($fas['icon'] ?? 'check', ENT_QUOTES, 'UTF-8') ?></span>
                            <?= htmlspecialchars($fas['label'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                        </span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    <a class="inline-flex items-center justify-between w-full px-6 py-4 bg-primary text-on-primary rounded-full font-label-mono text-label-mono uppercase tracking-wider hover:bg-gold-soft transition-colors"
                       href="<?= $mapsUrl ?>" target="_blank" rel="noopener noreferrer">
                        <span>Buka di Peta</span>
                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    </a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </section>

    <!-- Info & Map Section -->
    <section class="w-full max-w-container-max mx-auto px-container-pad-mobile lg:px-container-pad-desktop mt-section-v-desktop">
        <div class="flex flex-col lg:flex-row gap-12 items-center">
            <div class="w-full lg:w-1/3 flex flex-col gap-8">
                <div>
                    <h3 class="font-h3 text-h3 text-ink mb-4">Peta Kawasan Wisata</h3>
                    <p class="font-body-md text-body-md text-ink-dim">
                        Gunakan panduan ini untuk merencanakan rute perjalanan Anda. Sebagian besar rute dapat diakses kendaraan roda dua, namun untuk titik tertentu disarankan berjalan kaki.
                    </p>
                </div>
                <div class="flex flex-col gap-4">
                    <div class="flex items-start gap-4 p-4 rounded-xl bg-surface-container border border-line">
                        <div class="w-10 h-10 rounded-full bg-surface-2 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-gold-soft">info</span>
                        </div>
                        <div>
                            <h4 class="font-label-mono text-label-mono text-ink uppercase tracking-wider mb-1">Tiket Masuk</h4>
                            <p class="font-body-md text-sm text-ink-dim">Retribusi desa Rp 10.000/orang dialokasikan untuk pemeliharaan fasilitas umum.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full lg:w-2/3">
                <div class="relative w-full h-[400px] rounded-2xl overflow-hidden border border-line-strong shadow-2xl">
                    <div class="w-full h-full bg-cover bg-center"
                         style="background-image: url('<?= htmlspecialchars($base . '/uploads/konten/wisata-pemandangan.png', ENT_QUOTES, 'UTF-8') ?>')">
                    </div>
                    <div class="absolute inset-0 bg-surface/10 pointer-events-none"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="w-full max-w-container-max mx-auto px-container-pad-mobile lg:px-container-pad-desktop mt-section-v-desktop mb-8">
        <div class="relative w-full rounded-3xl overflow-hidden bg-surface-container flex flex-col md:flex-row items-center border border-line-strong p-8 md:p-12">
            <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-full blur-[80px] pointer-events-none"></div>
            <div class="flex-1 md:pr-12 text-center md:text-left z-10 mb-8 md:mb-0">
                <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest block mb-3">Butuh Bantuan?</span>
                <h2 class="font-h2 text-h2 text-ink mb-4">Gunakan Pemandu Lokal</h2>
                <p class="font-body-md text-body-md text-ink-dim mb-8 max-w-lg">
                    Maksimalkan pengalaman eksplorasi Anda dengan pemandu lokal yang memahami setiap jalur tersembunyi dan cerita di baliknya. Dukung juga perekonomian warga setempat.
                </p>
                <a href="<?= htmlspecialchars($base, ENT_QUOTES, 'UTF-8') ?>/kontak" class="inline-flex items-center justify-center gap-3 px-8 py-4 bg-ink text-bg rounded-full font-label-mono text-label-mono uppercase tracking-widest hover:bg-white transition-colors group">
                    Hubungi Pemandu
                    <span class="material-symbols-outlined text-[18px] group-hover:translate-x-1 transition-transform">chat</span>
                </a>
            </div>
            <div class="w-full md:w-1/3 shrink-0 z-10">
                <div class="relative w-full aspect-square rounded-full overflow-hidden border-4 border-surface shadow-2xl mx-auto max-w-[250px]">
                    <img class="w-full h-full object-cover"
                         alt="Pemandu lokal wisata Air Naningan"
                         loading="lazy"
                         onerror="this.onerror=null; this.src='<?= $base ?>/assets/images/placeholder.webp';"
                         src="<?= htmlspecialchars($base . '/uploads/konten/pemandu-lokal.jpg', ENT_QUOTES, 'UTF-8') ?>">
                </div>
            </div>
        </div>
    </section>

</div>

<style>
    @keyframes fade-in-up {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }
</style>

<?php require __DIR__ . '/../partials/footer.php'; ?>
