<?php
$currentPage     = 'galeri';
$pageTitle       = 'Galeri | Pekon Air Naningan';
$metaDescription = 'Galeri visual Pekon Air Naningan — menangkap momen, merawat ingatan. Bingkai keseharian, pesona alam, dan geliat pembangunan.';
require __DIR__ . '/../partials/header.php';
?>

<div class="flex flex-col w-full min-h-screen">

    <!-- Page Header -->
    <section class="w-full max-w-container-max mx-auto px-container-pad-mobile lg:px-container-pad-desktop pt-12 lg:pt-24 pb-8 lg:pb-16 flex flex-col items-center justify-center text-center">
        <span class="font-label-mono text-label-mono text-gold-soft tracking-widest uppercase mb-4 opacity-80">Galeri Visual</span>
        <h1 class="font-h1-mobile lg:font-h1 text-h1-mobile lg:text-h1 text-ink mb-6">Potret Pekon</h1>
        <p class="font-body-lg text-body-lg text-ink-dim max-w-2xl mx-auto">
            Menangkap momen, merawat ingatan. Bingkai visual keseharian, pesona alam, dan geliat pembangunan di Pekon Air Naningan.
        </p>
    </section>

    <!-- Gallery Section -->
    <section class="w-full max-w-container-max mx-auto px-container-pad-mobile lg:px-container-pad-desktop pb-section-v-mobile lg:pb-section-v-desktop relative">

        <!-- Filter Bar (sticky) -->
        <div class="flex flex-wrap items-center justify-center gap-3 mb-12 sticky top-24 z-30 bg-bg/90 backdrop-blur-md py-4 rounded-full border border-line px-6 mx-auto w-fit shadow-lg shadow-black/20">
            <button class="gallery-filter-btn px-6 py-2 rounded-full font-label-mono text-label-mono bg-primary text-on-primary transition-all duration-300" data-filter="all">Semua</button>
            <button class="gallery-filter-btn px-6 py-2 rounded-full font-label-mono text-label-mono text-ink-dim hover:text-ink hover:bg-surface-2 transition-all duration-300" data-filter="kegiatan">Kegiatan Desa</button>
            <button class="gallery-filter-btn px-6 py-2 rounded-full font-label-mono text-label-mono text-ink-dim hover:text-ink hover:bg-surface-2 transition-all duration-300" data-filter="alam">Alam &amp; Wisata</button>
            <button class="gallery-filter-btn px-6 py-2 rounded-full font-label-mono text-label-mono text-ink-dim hover:text-ink hover:bg-surface-2 transition-all duration-300" data-filter="budaya">Budaya</button>
            <button class="gallery-filter-btn px-6 py-2 rounded-full font-label-mono text-label-mono text-ink-dim hover:text-ink hover:bg-surface-2 transition-all duration-300" data-filter="pembangunan">Pembangunan</button>
        </div>

        <!-- Masonry Grid -->
        <div class="columns-1 md:columns-2 lg:columns-3 gap-6 space-y-6" id="gallery-grid">

            <div class="gallery-item relative group overflow-hidden rounded-xl bg-surface-2 cursor-pointer break-inside-avoid" data-category="alam" onclick="openLightbox(this)">
                <div class="w-full pb-[125%] relative">
                    <img class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                         loading="lazy" alt="Lembah Kabut Pagi — Air Naningan"
                         src="https://lh3.googleusercontent.com/aida-public/AB6AXuCr3oXe1mVJhlKx66TAvGGy1or3o-KBBKt7M1lS4td4q6cxjIcnhFHIbSBW-WAOiYp4tzzPDknfzjRfNrzXhMZb-C9e8V3FpUe1yJu9NBDiNZhTmIhO97DAlYDGcOlcFHvBkrfzlckVQ5z66k3UwothiG_CEbep3yj_8hXaaScl3_BoV8SwkT_aUKJ0zKTKpn4MVAgFGy-upHCe8zuRHQz2z7kRbtaT09rkuNC0S3vL1j2cwQEHnHahNg">
                </div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
                    <span class="font-label-mono text-label-mono text-primary mb-2">Alam &amp; Wisata</span>
                    <h3 class="font-h3 text-h3 text-ink">Lembah Kabut Pagi</h3>
                    <p class="font-body-md text-body-md text-ink-dim mt-2 line-clamp-2">Pesona magis lembah hijau yang masih asri, tertutup kabut tipis di pagi hari.</p>
                </div>
            </div>

            <div class="gallery-item relative group overflow-hidden rounded-xl bg-surface-2 cursor-pointer break-inside-avoid" data-category="kegiatan" onclick="openLightbox(this)">
                <div class="w-full pb-[80%] relative">
                    <img class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                         loading="lazy" alt="Panen Raya Kopi — Air Naningan"
                         src="https://lh3.googleusercontent.com/aida-public/AB6AXuDK8g2I6lYCIDFHprnzr3hQ04OcUTRwNQHFB2Yp77GuR8mDTqVPIcOT32wJ3Mw-3qrzrFZIeuIviKr2dcC81Ui7ywupx7eIaqdKbXC22KmUbs60Y8QBfdpbVwmHF6Lmehgy2T-u2AbbMQms-lkPRKwSiRFkXW7ERYsqPAnsw0E4lv7b5dc6WzEFuwRu_ePbxoK2YpfrZa5In5CVs1EMUGbDoKvlDQvbUYTJBsT-J40AL9OIr1ecc64F2Q">
                </div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
                    <span class="font-label-mono text-label-mono text-primary mb-2">Kegiatan Desa</span>
                    <h3 class="font-h3 text-h3 text-ink">Panen Raya Kopi</h3>
                    <p class="font-body-md text-body-md text-ink-dim mt-2 line-clamp-2">Gotong royong petani lokal saat musim panen kopi tiba, tulang punggung ekonomi desa.</p>
                </div>
            </div>

            <div class="gallery-item relative group overflow-hidden rounded-xl bg-surface-2 cursor-pointer break-inside-avoid" data-category="budaya" onclick="openLightbox(this)">
                <div class="w-full pb-[150%] relative">
                    <img class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                         loading="lazy" alt="Penjaga Tradisi — Budaya Lampung"
                         src="https://lh3.googleusercontent.com/aida-public/AB6AXuDi465ac3lFyJDwsIuK7VvSxtwcABRomrv1kVPeduCA4Ffb3xJzPV-dTiUSBNwWigWDzWIdO9HgyteMGSjvjnOkoP8r5fMi1loFgeVy9FJSZPvLhi_thzLSPV5mnvAWXIQI8fcSjK1trX8sUVfk6LMhRiW5qg5hTdj9R8NUblnjtP9-4C6Ue1Ee7i5-fJ_U1rHoFWsBmHj8n1vOJfJ4h3wSjZNOQnOUOThZoxQ_gsP5kinz_GZF8MswFg">
                </div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
                    <span class="font-label-mono text-label-mono text-primary mb-2">Budaya</span>
                    <h3 class="font-h3 text-h3 text-ink">Penjaga Tradisi</h3>
                    <p class="font-body-md text-body-md text-ink-dim mt-2 line-clamp-2">Tokoh adat setempat dalam balutan pakaian tradisional, mewariskan nilai luhur.</p>
                </div>
            </div>

            <div class="gallery-item relative group overflow-hidden rounded-xl bg-surface-2 cursor-pointer break-inside-avoid" data-category="pembangunan" onclick="openLightbox(this)">
                <div class="w-full pb-[100%] relative">
                    <img class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                         loading="lazy" alt="Fasilitas Komunitas Baru — Pembangunan"
                         src="https://lh3.googleusercontent.com/aida-public/AB6AXuAa3iZnwUNRb2I5hgcIdE_ku9VPrzu7bLqMupQeK8LSPKVEyyUSyXyAVJY9iiqYMUyyg67tfZN9aP6yNeaeqOsR6PCVo8zPDwJM6L4gVmIUSRySo2__S8ZuAjY41hUNV68IxxDdsI5UrmGhfip-QDU_0npRTYrneL_aAFrF6secYCuyg3GI4GwZWWOWkKuwvScayy3uvdpJePxNvSGZKxF5c4C_H5zkWdnjcdwB_EnxK4HzeCDkJSj4xA">
                </div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
                    <span class="font-label-mono text-label-mono text-primary mb-2">Pembangunan</span>
                    <h3 class="font-h3 text-h3 text-ink">Fasilitas Komunitas Baru</h3>
                    <p class="font-body-md text-body-md text-ink-dim mt-2 line-clamp-2">Pengerjaan tahap awal balai desa serbaguna untuk kegiatan masyarakat.</p>
                </div>
            </div>

            <div class="gallery-item relative group overflow-hidden rounded-xl bg-surface-2 cursor-pointer break-inside-avoid" data-category="alam" onclick="openLightbox(this)">
                <div class="w-full pb-[75%] relative">
                    <img class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                         loading="lazy" alt="Air Terjun Tersembunyi — Alam Air Naningan"
                         src="https://lh3.googleusercontent.com/aida-public/AB6AXuDVPuiKhTCkgBpIAubqzaZt9P5wENKM-LsFSEM8TWgzYfA_zhNnE9QEe9tg2rpU6SG-m6T8HzZUBONuBxLE1nS1iz308Qq9komNlywmNDX7dsTbU5G95bIYxfeiLkhfT3nBhhT4HonRebUQf_jfn-Mn5wUDVRpMJw2hql2hUr1iuKoqhNJ7vSprLsKYdrvGxiYFMNgKt4dS9KPJ225BHnjt1bH-CTTwVV7I_sxuecQlcYus-WZtGChCjg">
                </div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
                    <span class="font-label-mono text-label-mono text-primary mb-2">Alam &amp; Wisata</span>
                    <h3 class="font-h3 text-h3 text-ink">Air Terjun Tersembunyi</h3>
                    <p class="font-body-md text-body-md text-ink-dim mt-2 line-clamp-2">Potensi ekowisata alam yang menawarkan kesegaran murni dari mata air pegunungan.</p>
                </div>
            </div>

            <div class="gallery-item relative group overflow-hidden rounded-xl bg-surface-2 cursor-pointer break-inside-avoid" data-category="kegiatan" onclick="openLightbox(this)">
                <div class="w-full pb-[110%] relative">
                    <img class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                         loading="lazy" alt="Geliat Pasar Desa — Kegiatan Desa"
                         src="https://lh3.googleusercontent.com/aida-public/AB6AXuDpd5ZRQAu1OvcucW3u9S3jGVrA9Qy8Rq_OsylYONzK-LU5fo6jO90rLRHkT5v3wDhGgUZ7D-_CNs7CqktTT_2KTSAh7giLMk8JjoraHa95741jXlTIBg_8LBqaskpsiqi8VynnBTrv6BzFZO2ZouFGucf8McQ0JnCAhwxGkRp63D1TWsez_FfVCdoigGO6Leu0F5_k50G2HbQGjYoR0hmw-aCVwI-ywnFBL8Usb9ieMITbzCUapl2AGQ">
                </div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
                    <span class="font-label-mono text-label-mono text-primary mb-2">Kegiatan Desa</span>
                    <h3 class="font-h3 text-h3 text-ink">Geliat Pasar Desa</h3>
                    <p class="font-body-md text-body-md text-ink-dim mt-2 line-clamp-2">Pusat interaksi sosial dan ekonomi warga setiap akhir pekan.</p>
                </div>
            </div>

            <div class="gallery-item relative group overflow-hidden rounded-xl bg-surface-2 cursor-pointer break-inside-avoid" data-category="budaya" onclick="openLightbox(this)">
                <div class="w-full pb-[90%] relative">
                    <img class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                         loading="lazy" alt="Tarian Penyambutan — Budaya Lampung"
                         src="https://lh3.googleusercontent.com/aida-public/AB6AXuAXdmvI5oklclNJGjEpbuq4ghSJIk-_0EGwxG1kwPLvDB1xsynu8b6qSV2_WHndoK6frvAtakH4EIsVC9hl_Eq7K82_zBN99k29ps28hD20Xx7REvuclGaahcdPaXQ1gvjq6G7n53aNeo8WSW8Pwu457Ggyj3bpg86ioh7o2ahDL6UcUbEJD8Eo1GR8yuObn_82Jv2En2lBwufv5eH9BSupoCtZ4PL1eqEAlicTrSMKAi1qkTqEFuVgGw">
                </div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
                    <span class="font-label-mono text-label-mono text-primary mb-2">Budaya</span>
                    <h3 class="font-h3 text-h3 text-ink">Tarian Penyambutan</h3>
                    <p class="font-body-md text-body-md text-ink-dim mt-2 line-clamp-2">Pelestarian seni tari daerah yang ditampilkan pada acara-acara besar desa.</p>
                </div>
            </div>

        </div>

        <div class="mt-16 flex justify-center">
            <button class="px-8 py-3 rounded-full border border-line hover:bg-surface-2 text-ink transition-colors font-label-mono text-label-mono flex items-center gap-2 group">
                <span>MUAT LEBIH BANYAK</span>
                <span class="material-symbols-outlined text-[16px] group-hover:translate-y-1 transition-transform">expand_more</span>
            </button>
        </div>

    </section>

    <!-- Lightbox Modal -->
    <div class="fixed inset-0 z-[100] bg-black/95 backdrop-blur-xl hidden opacity-0 transition-opacity duration-300 flex flex-col" id="lightbox">
        <div class="w-full p-6 flex justify-end absolute top-0 left-0 z-10">
            <button class="w-12 h-12 rounded-full bg-surface-2/50 hover:bg-surface-2 border border-line flex items-center justify-center text-ink transition-colors" onclick="closeLightbox()">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="flex-1 flex items-center justify-center p-4 lg:p-12">
            <div class="relative max-w-5xl w-full h-full flex flex-col md:flex-row items-center gap-8">
                <div class="flex-1 w-full h-[512px] md:h-[819px] relative flex items-center justify-center">
                    <img alt="Enlarged view" class="max-w-full max-h-full object-contain rounded-lg shadow-2xl" id="lightbox-img" src="">
                </div>
                <div class="w-full md:w-80 flex flex-col gap-4 bg-surface-container/80 p-6 rounded-2xl border border-line">
                    <span class="font-label-mono text-label-mono text-primary" id="lightbox-category"></span>
                    <h2 class="font-h2 text-h2 text-ink" id="lightbox-title"></h2>
                    <p class="font-body-lg text-body-lg text-ink-dim" id="lightbox-desc"></p>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const filterBtns   = document.querySelectorAll('.gallery-filter-btn');
        const galleryItems = document.querySelectorAll('.gallery-item');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                filterBtns.forEach(b => { b.classList.remove('bg-primary','text-on-primary'); b.classList.add('text-ink-dim'); });
                btn.classList.remove('text-ink-dim'); btn.classList.add('bg-primary','text-on-primary');
                const filter = btn.getAttribute('data-filter');
                galleryItems.forEach(item => {
                    if (filter === 'all' || item.getAttribute('data-category') === filter) {
                        item.style.display = 'block';
                        setTimeout(() => { item.style.opacity = '1'; item.style.transform = 'translateY(0)'; }, 50);
                    } else {
                        item.style.opacity = '0'; item.style.transform = 'translateY(20px)';
                        setTimeout(() => { item.style.display = 'none'; }, 300);
                    }
                });
            });
        });
    });

    function openLightbox(element) {
        const img      = element.querySelector('img').src;
        const category = element.querySelector('span').innerText;
        const title    = element.querySelector('h3').innerText;
        const desc     = element.querySelector('p').innerText;
        document.getElementById('lightbox-img').src      = img;
        document.getElementById('lightbox-category').innerText = category;
        document.getElementById('lightbox-title').innerText    = title;
        document.getElementById('lightbox-desc').innerText     = desc;
        const lb = document.getElementById('lightbox');
        lb.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        requestAnimationFrame(() => { lb.classList.remove('opacity-0'); });
    }

    function closeLightbox() {
        const lb = document.getElementById('lightbox');
        lb.classList.add('opacity-0');
        document.body.style.overflow = '';
        setTimeout(() => { lb.classList.add('hidden'); }, 300);
    }

    // Close lightbox on Escape key
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLightbox(); });
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
