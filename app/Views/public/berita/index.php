<?php
$currentPage     = 'berita';
$pageTitle       = 'Berita | Pekon Air Naningan';
$metaDescription = 'Informasi terkini, pengumuman resmi, dan dokumentasi kegiatan masyarakat di lingkungan Pekon Air Naningan.';
$base            = defined('APP_BASE') ? APP_BASE : '';

if (!class_exists('Berita')) {
    $modelFile = defined('BASE_PATH') ? BASE_PATH . '/app/Models/Berita.php' : __DIR__ . '/../../../Models/Berita.php';
    if (file_exists($modelFile)) {
        require_once $modelFile;
    }
}
$beritaList = $beritaList ?? (class_exists('Berita') ? Berita::published() : []);

// Fallback dummy jika data kosong
if (empty($beritaList)) {
    $beritaList = [
        [
            'id' => 'berita-1',
            'judul' => 'Gotong Royong Perbaikan Saluran Irigasi Sambut Musim Tanam',
            'slug' => 'gotong-royong-perbaikan-saluran-irigasi',
            'kategori' => 'Kegiatan',
            'ringkasan' => 'Warga Pekon Air Naningan antusias mengikuti kegiatan gotong royong massal untuk membersihkan dan memperbaiki saluran irigasi utama menjelang musim tanam padi tahun ini.',
            'tanggal_terbit' => '2023-10-24',
            'penulis' => 'Admin Pekon',
            'foto_sampul' => '/uploads/konten/berita-irigasi.jpg'
        ],
        [
            'id' => 'berita-2',
            'judul' => 'Pendaftaran Bantuan Langsung Tunai (BLT) Dana Desa Tahap IV Dibuka',
            'slug' => 'pendaftaran-blt-dana-desa-tahap-iv-dibuka',
            'kategori' => 'Pengumuman',
            'ringkasan' => 'Pemerintah Pekon Air Naningan secara resmi membuka pendaftaran verifikasi penerima BLT Dana Desa untuk tahap keempat. Warga yang memenuhi kriteria diimbau segera melapor ke aparatur pekon.',
            'tanggal_terbit' => '2023-10-20',
            'penulis' => 'Sekretaris Desa',
            'foto_sampul' => '/uploads/konten/berita-blt.jpg'
        ],
        [
            'id' => 'berita-3',
            'judul' => 'Penyaluran Bantuan Sembako untuk Keluarga Prasejahtera Berjalan Lancar',
            'slug' => 'penyaluran-bantuan-sembako-keluarga-prasejahtera',
            'kategori' => 'Bantuan Sosial',
            'ringkasan' => 'Penyaluran program bantuan sembako bulanan untuk keluarga prasejahtera di Pekon Air Naningan telah selesai dilaksanakan. Proses distribusi dipusatkan di Balai Pekon dan berjalan tertib.',
            'tanggal_terbit' => '2023-10-15',
            'penulis' => 'Kasi Kesejahteraan',
            'foto_sampul' => '/uploads/konten/berita-sembako.jpg'
        ],
        [
            'id' => 'berita-4',
            'judul' => 'Panen Raya Kopi Robusta Membawa Berkah Bagi Petani Air Naningan',
            'slug' => 'panen-raya-kopi-robusta-2024',
            'kategori' => 'Pertanian',
            'ringkasan' => 'Musim kemarau yang berkepanjangan sempat menimbulkan kekhawatiran, namun hasil panen kopi Robusta tahun ini di Pekon Air Naningan justru menunjukkan kualitas yang luar biasa dengan peningkatan harga jual di pasaran.',
            'tanggal_terbit' => '2024-08-12',
            'penulis' => 'Admin Desa',
            'foto_sampul' => ''
        ]
    ];
}

$bulanMap = ['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni',
             '07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'];

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
                <a class="hover:text-primary transition-colors" href="<?= htmlspecialchars($base ?: '/', ENT_QUOTES) ?>">Beranda</a>
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
            <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto pb-2 md:pb-0" id="filter-buttons">
                <button data-cat="all" class="filter-btn px-6 py-2.5 rounded-full bg-primary text-on-primary font-body-md text-body-md whitespace-nowrap shadow-md shadow-primary/20 transition-all">Semua Berita</button>
                <?php
                $kategoriList = array_values(array_unique(array_filter(array_map(
                    static fn(array $item): string => trim((string) ($item['kategori'] ?? '')),
                    $beritaList
                ))));
                sort($kategoriList, SORT_NATURAL | SORT_FLAG_CASE);
                foreach ($kategoriList as $kategori):
                ?>
                <button data-cat="<?= htmlspecialchars($kategori, ENT_QUOTES, 'UTF-8') ?>" class="filter-btn px-6 py-2.5 rounded-full bg-surface-2 text-ink hover:text-primary border border-line font-body-md text-body-md whitespace-nowrap transition-colors"><?= htmlspecialchars($kategori, ENT_QUOTES, 'UTF-8') ?></button>
                <?php endforeach; ?>
            </div>
            <div class="relative w-full md:w-64">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-ink-dim">search</span>
                <input id="search-input" class="w-full bg-surface border border-line rounded-full py-2.5 pl-10 pr-4 text-body-md text-ink placeholder-ink-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all"
                       placeholder="Cari berita..." type="text">
            </div>
        </div>

        <!-- News Grid -->
        <div class="flex flex-col gap-6" id="news-grid">

            <?php foreach ($beritaList as $item):
                $slug = $item['slug'] ?? 'berita-' . ($item['id'] ?? '1');
                $detailUrl = htmlspecialchars($base . '/berita/' . $slug, ENT_QUOTES);
                $kategori = htmlspecialchars($item['kategori'] ?? 'Umum', ENT_QUOTES);
                $judul = htmlspecialchars($item['judul'] ?? '', ENT_QUOTES);
                $ringkasan = htmlspecialchars($item['ringkasan'] ?? '', ENT_QUOTES);
                $penulis = htmlspecialchars($item['penulis'] ?? 'Admin Pekon', ENT_QUOTES);
                $foto = !empty($item['foto_sampul']) ? $item['foto_sampul'] : '';

                // Format tanggal
                $tglStr = $item['tanggal_terbit'] ?? date('Y-m-d');
                $tParts = explode('-', $tglStr);
                $tFormatted = ($tParts[2] ?? '') . ' ' . ($bulanMap[$tParts[1] ?? ''] ?? '') . ' ' . ($tParts[0] ?? '');
            ?>
            <article class="news-card group flex flex-col md:flex-row bg-surface-2 rounded-[14px] overflow-hidden border border-line hover:border-line-strong transition-all duration-300 shadow-sm hover:shadow-md"
                     data-category="<?= $kategori ?>"
                     data-title="<?= mb_strtolower($judul) ?>"
                     data-summary="<?= mb_strtolower($ringkasan) ?>">
                <div class="md:w-1/3 h-56 md:h-auto relative overflow-hidden bg-surface-container-highest flex items-center justify-center">
                    <a href="<?= $detailUrl ?>" class="w-full h-full block">
                        <?php if (!empty($foto)): ?>
                            <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                                 alt="<?= $judul ?>"
                                 loading="lazy"
                                 onerror="this.onerror=null; this.src='<?= $base ?>/assets/images/placeholder.webp';"
                                  src="<?= htmlspecialchars(mediaUrl($foto, $base), ENT_QUOTES) ?>">
                        <?php else: ?>
                            <div class="w-full h-full bg-surface-2 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[64px] text-ink-dim/20">newspaper</span>
                            </div>
                        <?php endif; ?>
                    </a>
                    <div class="absolute inset-0 bg-gradient-to-t from-bg/60 to-transparent md:hidden pointer-events-none"></div>
                    <div class="absolute top-4 left-4 pointer-events-none">
                        <span class="px-3 py-1 bg-surface/80 backdrop-blur-md rounded-full text-label-mono text-gold-soft border border-line-strong uppercase text-xs"><?= $kategori ?></span>
                    </div>
                </div>
                <div class="flex-1 p-6 flex flex-col justify-between gap-4">
                    <div class="flex flex-col gap-3">
                        <div class="flex items-center gap-2 text-label-mono text-ink-dim text-xs">
                            <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                            <time datetime="<?= htmlspecialchars($tglStr, ENT_QUOTES) ?>"><?= htmlspecialchars($tFormatted, ENT_QUOTES) ?></time>
                            <span class="w-1 h-1 rounded-full bg-line-strong mx-2"></span>
                            <span class="material-symbols-outlined text-[16px]">person</span>
                            <span><?= $penulis ?></span>
                        </div>
                        <h2 class="font-h3 text-h3 text-ink group-hover:text-primary transition-colors line-clamp-2">
                            <a href="<?= $detailUrl ?>"><?= $judul ?></a>
                        </h2>
                        <p class="font-body-md text-body-md text-ink-dim line-clamp-3"><?= $ringkasan ?></p>
                    </div>
                    <a class="inline-flex items-center gap-2 text-gold-soft hover:text-primary font-body-md transition-colors w-fit font-medium"
                       href="<?= $detailUrl ?>">
                        Baca Selengkapnya
                        <span class="material-symbols-outlined text-[18px] transition-transform group-hover:translate-x-1">arrow_forward</span>
                    </a>
                </div>
            </article>
            <?php endforeach; ?>

        </div>

        <!-- Empty state hidden by default -->
        <div id="no-results" class="hidden text-center py-12 flex-col items-center gap-3">
            <span class="material-symbols-outlined text-[48px] text-ink-dim/40">search_off</span>
            <p class="text-ink-dim font-body-md">Tidak ada berita yang sesuai dengan pencarian atau filter.</p>
        </div>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const searchInput = document.getElementById('search-input');
    const cards = document.querySelectorAll('.news-card');
    const noResults = document.getElementById('no-results');

    let currentCat = 'all';

    function filterNews() {
        const query = searchInput.value.toLowerCase().trim();
        let visibleCount = 0;

        cards.forEach(card => {
            const cat = card.getAttribute('data-category');
            const title = card.getAttribute('data-title');
            const summary = card.getAttribute('data-summary');

            const matchCat = (currentCat === 'all' || cat.toLowerCase() === currentCat.toLowerCase());
            const matchSearch = !query || title.includes(query) || summary.includes(query);

            if (matchCat && matchSearch) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        if (visibleCount === 0) {
            noResults.classList.remove('hidden');
            noResults.classList.add('flex');
        } else {
            noResults.classList.add('hidden');
            noResults.classList.remove('flex');
        }
    }

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            filterBtns.forEach(b => {
                b.classList.remove('bg-primary', 'text-on-primary', 'shadow-md', 'shadow-primary/20');
                b.classList.add('bg-surface-2', 'text-ink', 'hover:text-primary', 'border', 'border-line');
            });
            this.classList.remove('bg-surface-2', 'text-ink', 'hover:text-primary', 'border', 'border-line');
            this.classList.add('bg-primary', 'text-on-primary', 'shadow-md', 'shadow-primary/20');

            currentCat = this.getAttribute('data-cat');
            filterNews();
        });
    });

    if (searchInput) {
        searchInput.addEventListener('input', filterNews);
    }
});
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
