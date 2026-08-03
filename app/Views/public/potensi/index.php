<?php
$currentPage     = 'potensi';
$pageTitle       = 'Potensi Desa | Pekon Air Naningan';
$metaDescription = 'Mengenal potensi agrikultur Pekon Air Naningan — kopi robusta, kakao, dan gula aren organik dari tanah vulkanik lereng Tanggamus.';
require __DIR__ . '/../partials/header.php';
?>

<div class="flex flex-col w-full">

    <!-- Hero Section -->
    <section class="relative w-full h-[614px] md:h-[716px] flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center w-full h-full scale-105 transition-transform duration-1000 ease-out" id="hero-bg"
             style="background-image: url('<?= htmlspecialchars($base . '/uploads/konten/potensi-hero.jpg', ENT_QUOTES, 'UTF-8') ?>')">
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-bg via-bg/60 to-transparent"></div>
        <div class="relative z-10 max-w-container-max mx-auto px-container-pad-mobile lg:px-container-pad-desktop text-center mt-12 flex flex-col items-center">
            <span class="font-label-mono text-label-mono text-primary tracking-widest uppercase mb-4">Sumber Daya Alam</span>
            <h1 class="font-h1-mobile md:font-h1 text-h1-mobile md:text-h1 text-ink mb-6 max-w-3xl mx-auto">
                Tulang Punggung Ekonomi Lereng Gunung
            </h1>
            <p class="font-body-lg text-body-lg text-ink-dim max-w-2xl mx-auto">
                Mengenal lebih dekat potensi agrikultur Pekon Air Naningan. Dari tanah vulkanik yang subur, lahir komoditas unggulan yang menghidupi masyarakat dan menjaga warisan alam.
            </p>
        </div>
    </section>

    <!-- Commodity Deep Dive -->
    <section class="py-section-v-mobile lg:py-section-v-desktop max-w-container-max mx-auto px-container-pad-mobile lg:px-container-pad-desktop w-full">

        <!-- Kopi Robusta -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 lg:gap-16 items-start mb-24">
            <div class="md:col-span-5 sticky top-28">
                <span class="font-label-mono text-label-mono text-gold-soft mb-2 block">01 / Komoditas Utama</span>
                <h2 class="font-h2 text-h2 text-ink mb-6">Kopi Robusta<br>Air Naningan</h2>
                <p class="font-body-md text-body-md text-ink-dim mb-8">
                    Ditanam pada ketinggian ideal dengan iklim mikro lereng gunung, Kopi Robusta kami menawarkan profil rasa yang pekat dengan body yang kuat dan sentuhan earthy yang khas. Proses pasca-panen dilakukan secara teliti oleh kelompok tani lokal.
                </p>
                <div class="grid grid-cols-2 gap-4 mb-8">
                    <div class="bg-surface-2 p-4 rounded-xl">
                        <span class="font-label-mono text-label-mono text-ink-dim uppercase block mb-1">Estimasi Produksi</span>
                        <span class="font-h3 text-h3 text-primary block">150 Ton</span>
                        <span class="font-body-md text-body-md text-ink-dim text-sm block">per Tahun</span>
                    </div>
                    <div class="bg-surface-2 p-4 rounded-xl">
                        <span class="font-label-mono text-label-mono text-ink-dim uppercase block mb-1">Luas Lahan</span>
                        <span class="font-h3 text-h3 text-primary block">450 Ha</span>
                        <span class="font-body-md text-body-md text-ink-dim text-sm block">Produktif</span>
                    </div>
                </div>
            </div>
            <div class="md:col-span-7 grid gap-6">
                <div class="relative w-full h-[400px] rounded-xl overflow-hidden shadow-xl group">
                    <img class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                         alt="Petani memetik ceri kopi robusta merah"
                         src="<?= htmlspecialchars($base . '/uploads/konten/kopi-pemetik.jpg', ENT_QUOTES, 'UTF-8') ?>" loading="lazy" onerror="this.onerror=null; this.src='<?= $base ?>/assets/images/placeholder.webp';">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                    <div class="absolute bottom-6 left-6 right-6">
                        <h3 class="font-h3 text-h3 text-ink mb-1">Panen Selektif</h3>
                        <p class="font-body-md text-body-md text-ink-dim">Memastikan hanya ceri matang sempurna yang diproses.</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="relative w-full h-[250px] rounded-xl overflow-hidden shadow-lg group">
                        <img class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                             alt="Biji kopi dijemur dengan proses natural"
                             src="<?= htmlspecialchars($base . '/uploads/konten/kopi-dijemur.jpg', ENT_QUOTES, 'UTF-8') ?>" loading="lazy" onerror="this.onerror=null; this.src='<?= $base ?>/assets/images/placeholder.webp';">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent"></div>
                        <div class="absolute bottom-4 left-4 right-4">
                            <span class="font-label-mono text-label-mono text-primary block mb-1">Proses Natural</span>
                            <p class="font-body-md text-body-md text-ink text-sm">Penjemuran di bawah sinar matahari langsung.</p>
                        </div>
                    </div>
                    <div class="relative w-full h-[250px] rounded-xl overflow-hidden shadow-lg group">
                        <img class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                             alt="Biji kopi hijau siap distribusi"
                             src="<?= htmlspecialchars($base . '/uploads/konten/kopi-hijau.jpg', ENT_QUOTES, 'UTF-8') ?>" loading="lazy" onerror="this.onerror=null; this.src='<?= $base ?>/assets/images/placeholder.webp';">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent"></div>
                        <div class="absolute bottom-4 left-4 right-4">
                            <span class="font-label-mono text-label-mono text-primary block mb-1">Penyortiran Mutu</span>
                            <p class="font-body-md text-body-md text-ink text-sm">Quality control ketat sebelum distribusi.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kakao & Aren Split -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-12 mt-24">
            <!-- Kakao -->
            <div class="flex flex-col">
                <div class="relative w-full h-[350px] rounded-xl overflow-hidden mb-8 shadow-xl">
                    <img class="absolute inset-0 w-full h-full object-cover hover:scale-105 transition-transform duration-700"
                         alt="Buah kakao kuning di pohon"
                         src="<?= htmlspecialchars($base . '/uploads/konten/kakao-buah.jpg', ENT_QUOTES, 'UTF-8') ?>" loading="lazy" onerror="this.onerror=null; this.src='<?= $base ?>/assets/images/placeholder.webp';">
                </div>
                <span class="font-label-mono text-label-mono text-gold-soft mb-2 block">02 / Komoditas Pendukung</span>
                <h3 class="font-h3 text-h3 text-ink mb-4">Kakao Berkualitas</h3>
                <p class="font-body-md text-body-md text-ink-dim mb-6">
                    Tanaman kakao tumbuh subur berdampingan dengan kopi sebagai tanaman penaung. Biji kakao Air Naningan diproses melalui fermentasi untuk menghasilkan aroma cokelat yang kaya dan kompleks.
                </p>
                <ul class="flex flex-col gap-3 mb-6 font-body-md text-ink-dim">
                    <li class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-xl">check_circle</span>
                        Varietas unggul lokal
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-xl">check_circle</span>
                        Proses fermentasi standar
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-xl">check_circle</span>
                        Produksi tahunan ~75 Ton
                    </li>
                </ul>
            </div>

            <!-- Gula Aren -->
            <div class="flex flex-col">
                <div class="relative w-full h-[350px] rounded-xl overflow-hidden mb-8 shadow-xl">
                    <img class="absolute inset-0 w-full h-full object-cover hover:scale-105 transition-transform duration-700"
                         alt="Proses pembuatan gula aren tradisional"
                         src="<?= htmlspecialchars($base . '/uploads/konten/gula-aren.jpg', ENT_QUOTES, 'UTF-8') ?>" loading="lazy" onerror="this.onerror=null; this.src='<?= $base ?>/assets/images/placeholder.webp';">
                </div>
                <span class="font-label-mono text-label-mono text-gold-soft mb-2 block">03 / Produk Olahan Tradisional</span>
                <h3 class="font-h3 text-h3 text-ink mb-4">Gula Aren Organik</h3>
                <p class="font-body-md text-body-md text-ink-dim mb-6">
                    Memanfaatkan rimbunnya pohon aren di sekitar kawasan hutan desa, pengrajin lokal mengolah nira menjadi gula aren murni. Proses tradisional dipertahankan untuk menjaga keaslian rasa dan aroma karamel yang khas.
                </p>
                <ul class="flex flex-col gap-3 mb-6 font-body-md text-ink-dim">
                    <li class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-xl">park</span>
                        100% Organik &amp; Alami
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-xl">local_fire_department</span>
                        Pengolahan kayu bakar tradisional
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-xl">storefront</span>
                        Tersedia bentuk cetak &amp; semut
                    </li>
                </ul>
            </div>
        </div>

    </section>

    <!-- Geographic Advantage Banner -->
    <section class="w-full bg-surface-2 py-20 relative overflow-hidden my-12">
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-primary/5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-tertiary-fixed-dim/5 rounded-full blur-3xl"></div>
        <div class="max-w-container-max mx-auto px-container-pad-mobile lg:px-container-pad-desktop relative z-10 flex flex-col md:flex-row items-center gap-12">
            <div class="md:w-1/3">
                <svg class="w-full max-w-[280px] h-auto text-primary" fill="currentColor" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10,180 L190,180 L190,190 L10,190 Z" opacity="0.2"></path>
                    <path d="M20,150 L180,150 L180,180 L20,180 Z" opacity="0.4"></path>
                    <path d="M40,120 L160,120 L160,150 L40,150 Z" opacity="0.6"></path>
                    <path d="M60,90 L140,90 L140,120 L60,120 Z" opacity="0.8"></path>
                    <path d="M80,60 L120,60 L120,90 L80,90 Z"></path>
                    <path d="M100,55 C100,55 90,45 90,30 C90,15 100,10 100,10 C100,10 110,15 110,30 C110,45 100,55 100,55 Z"></path>
                    <path d="M100,55 C100,55 80,50 70,40 C60,30 65,20 65,20 C65,20 75,25 85,35 C95,45 100,55 100,55 Z"></path>
                    <path d="M100,55 C100,55 120,50 130,40 C140,30 135,20 135,20 C135,20 125,25 115,35 C105,45 100,55 100,55 Z"></path>
                </svg>
            </div>
            <div class="md:w-2/3">
                <h2 class="font-h2 text-h2 text-ink mb-6">Keunggulan Tanah Vulkanik Lereng Gunung</h2>
                <p class="font-body-lg text-body-lg text-ink-dim mb-6">
                    Kekayaan agrikultur Air Naningan tidak lepas dari lokasinya yang berada di lereng gunung. Material vulkanik yang kaya akan mineral penting menciptakan media tanam alami yang sangat subur.
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-6 border-t border-line-strong">
                    <div>
                        <span class="font-h3 text-h3 text-gold-soft block mb-1">pH 6.0–6.5</span>
                        <span class="font-label-mono text-label-mono text-ink-dim uppercase">Tingkat Keasaman Ideal</span>
                    </div>
                    <div>
                        <span class="font-h3 text-h3 text-gold-soft block mb-1">600–800</span>
                        <span class="font-label-mono text-label-mono text-ink-dim uppercase">Mdpl (Ketinggian)</span>
                    </div>
                    <div>
                        <span class="font-h3 text-h3 text-gold-soft block mb-1">Iklim</span>
                        <span class="font-label-mono text-label-mono text-ink-dim uppercase">Tropis Basah Mikro</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="py-section-v-mobile lg:py-section-v-desktop max-w-container-max mx-auto px-container-pad-mobile lg:px-container-pad-desktop w-full">
        <div class="text-center mb-16">
            <h2 class="font-h2 text-h2 text-ink mb-4">Potret Perkebunan Warga</h2>
            <p class="font-body-md text-body-md text-ink-dim max-w-xl mx-auto">
                Keseharian masyarakat Pekon Air Naningan yang menyatu dengan alam, merawat dan memanen hasil bumi dengan penuh dedikasi.
            </p>
        </div>
        <!-- Bento Grid Gallery -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 auto-rows-[200px] md:auto-rows-[250px]">
            <div class="col-span-2 row-span-2 relative rounded-xl overflow-hidden group">
                <img class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105"
                     alt="Panorama luas kebun kopi berteras di Air Naningan"
                     src="<?= htmlspecialchars($base . '/uploads/konten/panorama-kebun.jpg', ENT_QUOTES, 'UTF-8') ?>" loading="lazy" onerror="this.onerror=null; this.src='<?= $base ?>/assets/images/placeholder.webp';">
                <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-colors duration-500"></div>
            </div>
            <div class="col-span-1 row-span-1 relative rounded-xl overflow-hidden group">
                <img class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                     alt="Petani memanen ceri kopi merah"
                     src="<?= htmlspecialchars($base . '/uploads/konten/kopi-memanen.jpg', ENT_QUOTES, 'UTF-8') ?>" loading="lazy" onerror="this.onerror=null; this.src='<?= $base ?>/assets/images/placeholder.webp';">
            </div>
            <div class="col-span-1 row-span-1 relative rounded-xl overflow-hidden group">
                <img class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                     alt="Fermentasi biji kakao dalam kotak kayu"
                     src="<?= htmlspecialchars($base . '/uploads/konten/kakao-fermentasi.jpg', ENT_QUOTES, 'UTF-8') ?>" loading="lazy" onerror="this.onerror=null; this.src='<?= $base ?>/assets/images/placeholder.webp';">
            </div>
            <div class="col-span-2 row-span-1 relative rounded-xl overflow-hidden group">
                <img class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                     alt="Kawasan hutan pohon aren"
                     src="<?= htmlspecialchars($base . '/uploads/konten/aren-hutan.jpg', ENT_QUOTES, 'UTF-8') ?>" loading="lazy" onerror="this.onerror=null; this.src='<?= $base ?>/assets/images/placeholder.webp';">
                <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/80 to-transparent">
                    <span class="font-body-md text-ink block">Kawasan Hutan Aren</span>
                </div>
            </div>
        </div>
    </section>

</div>

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }
</style>
<script>
    window.addEventListener('scroll', () => {
        const heroBg = document.getElementById('hero-bg');
        if (heroBg) {
            heroBg.style.transform = `translateY(${window.pageYOffset * 0.4}px) scale(1.05)`;
        }
    });
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
