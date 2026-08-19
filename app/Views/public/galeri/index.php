<?php
/* ======================================================
   HALAMAN GALERI FOTO & VIDEO PEKON

   Halaman ini adalah "album foto" desa: menampilkan semua
   foto/video dalam susunan rapatan (masonry). Pengunjung
   bisa memfilter per kategori dan mengeklik media untuk
   melihatnya besar.

   Data yang dikirim Controller:
   - $items    : daftar media galeri. Kolom yang dipakai:
                 kategori, kategori_label, judul, deskripsi,
                 file, rasio, tipe (foto/video)
   - $kategori : daftar kategori untuk tombol filter,
                 bentuknya kode => label (misal "kegiatan"
                 => "Kegiatan")
====================================================== */
$currentPage     = 'galeri';
$pageTitle       = 'Galeri Pekon Air Naningan | Kegiatan dan Potensi Desa';
$metaDescription = 'Lihat galeri Pekon Air Naningan, Tanggamus yang mendokumentasikan kegiatan warga, pesona alam, potensi desa, budaya, dan pembangunan pekon.';
$metaKeywords    = 'galeri Air Naningan, foto Pekon Air Naningan, potensi desa Tanggamus, kegiatan warga Air Naningan';
require __DIR__ . '/../partials/header.php';
?>

<div class="flex flex-col w-full min-h-screen">

    <!-- ======================================================
         KEPALA HALAMAN GALERI

         Bagian pembuka: judul "Potret Pekon" dan keterangan
         singkat, disusun rata tengah di atas halaman.
    ====================================================== -->
    <!-- Page Header -->
    <section class="w-full max-w-container-max mx-auto px-container-pad-mobile lg:px-container-pad-desktop pt-12 lg:pt-24 pb-8 lg:pb-16 flex flex-col items-center justify-center text-center">
        <span class="font-label-mono text-label-mono text-gold-soft tracking-widest uppercase mb-4 opacity-80">Galeri Visual</span>
        <h1 class="font-h1-mobile lg:font-h1 text-h1-mobile lg:text-h1 text-ink mb-6">Potret Pekon</h1>
        <p class="font-body-lg text-body-lg text-ink-dim max-w-2xl mx-auto">
            Menangkap momen, merawat ingatan. Bingkai visual keseharian, pesona alam, dan geliat pembangunan di Pekon Air Naningan.
        </p>
    </section>

    <!-- ======================================================
         GALERI + BARIS TOMBOL FILTER

         Baris tombol filter ditempel di atas layar (sticky)
         saat halaman digulir, agar filter selalu terlihat.
         Tombol "Semua" ditambah 1 tombol per kategori lewat
         foreach $kategori ($kat = kode, $label = nama tampil).
         Setiap tombol menyimpan kode kategori pada atribut
         data-filter untuk dipakai JavaScript.
    ====================================================== -->
    <!-- Gallery Section -->
    <section class="w-full max-w-container-max mx-auto px-container-pad-mobile lg:px-container-pad-desktop pb-section-v-mobile lg:pb-section-v-desktop relative">

        <!-- Filter Bar (sticky) — overflow-x-auto untuk mobile -->
        <div class="sticky top-24 z-30 mb-12 overflow-x-auto -mx-container-pad-mobile lg:-mx-0 px-container-pad-mobile lg:px-0 no-scrollbar">
            <div class="flex items-center gap-3 w-max mx-auto bg-bg/90 backdrop-blur-md py-4 rounded-full border border-line px-6 shadow-lg shadow-black/20">
                <button class="gallery-filter-btn px-6 py-2 rounded-full font-label-mono text-label-mono bg-primary text-on-primary transition-all duration-300 whitespace-nowrap" data-filter="all">Semua</button>
                <?php foreach ($kategori as $kat => $label): ?>
                <button class="gallery-filter-btn px-6 py-2 rounded-full font-label-mono text-label-mono text-ink-dim hover:text-ink hover:bg-surface-2 transition-all duration-300 whitespace-nowrap" data-filter="<?= htmlspecialchars($kat, ENT_QUOTES, 'UTF-8') ?>">
                    <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
                </button>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- ======================================================
             KOTAK GALERI (SUSUNAN MASONRY)

             Susunan kolom: 3 kolom di semua ukuran layar
             (columns-3), dengan jarak antar media lebih rapat
             di layar kecil (gap-2.5) dan lebar di layar besar
             (gap-6).

             Perulangan: sistem memeriksa seluruh $items satu
             per satu lalu membuat 1 kartu untuk tiap media.
             Kolom yang dipakai:
             - $item['file']           : file media dari folder
               uploads/galeri/ lewat pembantu mediaUrl()
             - $item['rasio']          : tinggi kartu (agar
               susunan masonry rapi)
             - $item['tipe']           : "video" atau "foto"
             - $item['kategori']       : kode kategori (disalin
               ke data-category untuk filter)
             - $item['kategori_label'] : nama kategori tampil
             - $item['judul']          : judul media
             - $item['deskripsi']      : keterangan media
             Atribut data-file dan data-tipe dipakai tombol
             perbesar (lightbox) saat kartu diklik.
        ====================================================== -->
        <!-- Masonry Grid -->
        <div class="columns-3 gap-2.5 md:gap-6 space-y-2.5 md:space-y-6" id="gallery-grid">

            <?php if (empty($items)): ?>
            <div class="col-span-full py-12 text-center text-ink-dim w-full flex flex-col items-center justify-center gap-4">
                <span class="material-symbols-outlined text-[48px] opacity-20">photo_library</span>
                <p class="font-body-md text-sm">Belum ada foto di galeri.</p>
            </div>
            <?php else: ?>
            <?php foreach ($items as $item):
                // Siapkan isi kartu: kategori, judul, deskripsi,
                // file media, rasio tinggi, dan tipe (foto/video)
                $kat = htmlspecialchars($item['kategori'] ?? '', ENT_QUOTES, 'UTF-8');
                $katLabel = htmlspecialchars($item['kategori_label'] ?? '', ENT_QUOTES, 'UTF-8');
                $judul = htmlspecialchars($item['judul'] ?? '', ENT_QUOTES, 'UTF-8');
                $deskripsi = htmlspecialchars($item['deskripsi'] ?? '', ENT_QUOTES, 'UTF-8');
                $file = htmlspecialchars(mediaUrl($item['file'] ?? '', $base), ENT_QUOTES, 'UTF-8');
                $rasio = htmlspecialchars($item['rasio'] ?? '100%', ENT_QUOTES, 'UTF-8');
                $tipe = htmlspecialchars($item['tipe'] ?? 'foto', ENT_QUOTES, 'UTF-8');
            ?>
            <div class="gallery-item relative group overflow-hidden rounded-xl bg-surface-2 cursor-pointer break-inside-avoid" data-category="<?= $kat ?>" data-tipe="<?= $tipe ?>" data-file="<?= $file ?>" onclick="openLightbox(this)">
                <div class="w-full relative" style="padding-bottom: <?= $rasio ?>;">
                    <?php if ($tipe === 'video'): ?>
                    <video class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" src="<?= $file ?>" muted loop playsinline onmouseover="this.play()" onmouseout="this.pause()"></video>
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <span class="material-symbols-outlined text-white text-5xl drop-shadow-lg opacity-80">play_circle</span>
                    </div>
                    <?php else: ?>
                    <img class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                         loading="lazy" alt="<?= $judul ?> — <?= $katLabel ?>"
                         onerror="this.onerror=null; this.src='<?= $base ?>/assets/images/placeholder.webp';"
                         src="<?= $file ?>">
                    <?php endif; ?>
                </div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
                    <span class="font-label-mono text-label-mono text-primary mb-2"><?= $katLabel ?></span>
                    <h3 class="font-h3 text-h3 text-ink"><?= $judul ?></h3>
                    <?php if ($deskripsi !== ''): ?>
                    <p class="font-body-md text-body-md text-ink-dim mt-2 line-clamp-2"><?= $deskripsi ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>

        </div>

        <!-- ======================================================
             TOMBOL "MUAT LEBIH BANYAK"

             Awalnya hanya 6 media yang ditampilkan (diatur
             JavaScript). Tombol ini menambah 6 media lagi setiap
             kali diklik. Jika semua media sudah tampil, tombol
             ini disembunyikan.
        ====================================================== -->
        <div class="mt-16 flex justify-center" id="load-more-container">
            <button id="load-more-btn" class="px-8 py-3 rounded-full border border-line hover:bg-surface-2 text-ink transition-colors font-label-mono text-label-mono flex items-center gap-2 group">
                <span>MUAT LEBIH BANYAK</span>
                <span class="material-symbols-outlined text-[16px] group-hover:translate-y-1 transition-transform">expand_more</span>
            </button>
        </div>

    </section>

    <!-- ======================================================
         KOTAK PERBESAR MEDIA (LIGHTBOX)

         Saat sebuah foto/video diklik, kotak besar berlatar
         gelap ini muncul untuk memperlihatkan medianya.
         - Bagian tengah : foto atau video (video bisa diputar)
         - Panel kanan   : kategori, judul, dan keterangan
         - Tombol × / latar gelap / tombol Escape : menutup
    ====================================================== -->
    <!-- Lightbox Modal -->
    <div data-modal class="fixed inset-0 z-[100] bg-black/95 backdrop-blur-xl hidden opacity-0 transition-opacity duration-300 flex flex-col" id="lightbox">
        <div class="w-full p-6 flex justify-end absolute top-0 left-0 z-10">
            <button data-modal-close class="w-12 h-12 rounded-full bg-surface-2/50 hover:bg-surface-2 border border-line flex items-center justify-center text-ink transition-colors" onclick="closeLightbox()">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="flex-1 flex items-center justify-center p-4 lg:p-8">
            <div class="relative max-w-[96rem] w-full h-full flex flex-col md:flex-row items-center gap-6 lg:gap-8">
                <div class="flex-1 min-w-0 w-full h-[60vh] md:h-[88vh] relative flex items-center justify-center">
                    <img alt="Enlarged view" class="max-w-full max-h-full object-contain rounded-lg shadow-2xl" id="lightbox-img" src="">
                    <video class="max-w-full max-h-full object-contain rounded-lg shadow-2xl hidden" id="lightbox-video" src="" controls playsinline></video>
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
        const loadMoreBtn  = document.getElementById('load-more-btn');
        const loadMoreContainer = document.getElementById('load-more-container');
        
        let currentLimit = 6;
        let currentFilter = 'all';

        function updateGallery() {
            let visibleCount = 0;
            let matchCount = 0;

            galleryItems.forEach(item => {
                const category = item.getAttribute('data-category');
                if (currentFilter === 'all' || category === currentFilter) {
                    matchCount++;
                    if (visibleCount < currentLimit) {
                        item.style.display = 'block';
                        // Add a small delay for staggered animation
                        setTimeout(() => { item.style.opacity = '1'; item.style.transform = 'translateY(0)'; }, 50);
                        visibleCount++;
                    } else {
                        item.style.opacity = '0'; item.style.transform = 'translateY(20px)';
                        setTimeout(() => { item.style.display = 'none'; }, 300);
                    }
                } else {
                    item.style.opacity = '0'; item.style.transform = 'translateY(20px)';
                    setTimeout(() => { item.style.display = 'none'; }, 300);
                }
            });

            if (matchCount > currentLimit) {
                if (loadMoreContainer) loadMoreContainer.style.display = 'flex';
            } else {
                if (loadMoreContainer) loadMoreContainer.style.display = 'none';
            }
        }

        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                filterBtns.forEach(b => { b.classList.remove('bg-primary','text-on-primary'); b.classList.add('text-ink-dim'); });
                btn.classList.remove('text-ink-dim'); btn.classList.add('bg-primary','text-on-primary');
                currentFilter = btn.getAttribute('data-filter');
                currentLimit = 6; // Reset limit when changing category
                updateGallery();
            });
        });

        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', () => {
                currentLimit += 6;
                updateGallery();
            });
        }

        // Initialize gallery state
        updateGallery();
    });

    function openLightbox(element) {
        const file     = element.dataset.file;
        const tipe     = element.dataset.tipe;
        const category = element.querySelector('span.font-label-mono').innerText;
        const title    = element.querySelector('h3').innerText;
        const desc     = element.querySelector('p')?.innerText || '';
        const lbImg    = document.getElementById('lightbox-img');
        const lbVid    = document.getElementById('lightbox-video');

        if (tipe === 'video') {
            lbImg.classList.add('hidden');
            lbImg.src = '';
            lbVid.src = file;
            lbVid.classList.remove('hidden');
        } else {
            lbVid.classList.add('hidden');
            lbVid.src = '';
            lbImg.src = file;
            lbImg.classList.remove('hidden');
        }

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
        setTimeout(() => { 
            lb.classList.add('hidden');
            document.getElementById('lightbox-video').pause();
            document.getElementById('lightbox-video').src = '';
            document.getElementById('lightbox-img').src = '';
        }, 300);
    }

    // Close lightbox on Escape key
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLightbox(); });
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
