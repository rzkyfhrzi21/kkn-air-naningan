<?php
$currentPage     = 'umkm';
$pageTitle       = 'UMKM | Pekon Air Naningan';
$metaDescription = 'Katalog produk UMKM dan usaha warga Pekon Air Naningan — kopi robusta, kuliner olahan, kerajinan bambu, dan gula aren murni.';
require __DIR__ . '/../partials/header.php';

$items    = $items ?? [];
$kategori = $kategori ?? [];
?>

<div class="flex flex-col w-full relative min-h-screen pb-section-v-mobile lg:pb-section-v-desktop bg-bg text-on-surface">

    <div class="absolute top-0 inset-x-0 h-[512px] bg-gradient-to-b from-surface to-transparent pointer-events-none"></div>

    <section class="relative pt-section-v-mobile lg:pt-section-v-desktop px-container-pad-mobile lg:px-container-pad-desktop max-w-container-max mx-auto w-full z-10">

        <div class="flex flex-col md:flex-row gap-8 items-end justify-between mb-16">
            <div class="flex flex-col gap-4 max-w-2xl">
                <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest border border-gold-soft/30 rounded-full px-4 py-1.5 w-max">Katalog Lokal</span>
                <h1 class="font-h1-mobile lg:font-h1 text-h1-mobile lg:text-h1 text-ink">Produk Unggulan &amp; UMKM</h1>
                <p class="font-body-lg text-body-lg text-ink-dim">
                    Menjelajahi ragam karya, cita rasa, dan inovasi dari tangan-tangan terampil masyarakat Pekon Air Naningan. Dukung ekonomi lokal dengan bertransaksi langsung.
                </p>
            </div>
            <div class="w-full md:w-72">
                <div class="relative w-full">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-ink-dim">search</span>
                    <input class="w-full bg-surface border border-line rounded-xl py-3 pl-12 pr-4 text-ink font-body-md placeholder:text-ink-dim/50 focus:outline-none focus:border-gold-soft focus:ring-1 focus:ring-gold-soft transition-all"
                           id="searchInput"
                           placeholder="Cari produk atau UMKM..."
                           type="text">
                </div>
            </div>
        </div>

        <div class="flex flex-wrap gap-3 mb-12 border-b border-line pb-6" id="filterContainer">
            <button class="filter-btn active bg-primary text-on-primary font-body-md px-6 py-2 rounded-full transition-colors" data-filter="all">Semua Kategori</button>
            <?php foreach ($kategori as $key => $label): ?>
            <button class="filter-btn bg-surface-2 text-ink hover:bg-surface-container font-body-md px-6 py-2 rounded-full transition-colors border border-line hover:border-gold-soft/50" data-filter="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>">
                <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
            </button>
            <?php endforeach; ?>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gutter" id="umkmGrid">
            <?php if ($items === []): ?>
            <?php else: ?>
            <?php foreach ($items as $item):
                $kat      = (string) ($item['kategori'] ?? '');
                $katLabel = (string) ($item['kategori_label'] ?? ($kategori[$kat] ?? $kat));
                $nama     = (string) ($item['nama'] ?? '');
                $usaha    = (string) ($item['usaha'] ?? '');
                $desc     = (string) ($item['deskripsi'] ?? '');
                $pemilik  = (string) ($item['pemilik'] ?? '');
                $dusun    = (string) ($item['dusun'] ?? '');
                $foto     = (string) ($item['foto'] ?? '');
                $wa       = preg_replace('/\D+/', '', (string) ($item['no_wa'] ?? '')) ?? '';
                $waUrl    = $wa !== '' ? 'https://wa.me/' . $wa : '#';
            ?>
            <article class="umkm-card bg-surface-2 rounded-[14px] overflow-hidden group shadow-lg flex flex-col" data-category="<?= htmlspecialchars($kat, ENT_QUOTES, 'UTF-8') ?>">
                <div class="relative h-64 overflow-hidden bg-surface-container">
                    <?php if ($foto !== ''): ?>
                    <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out"
                         alt="<?= htmlspecialchars($nama, ENT_QUOTES, 'UTF-8') ?>"
                         src="<?= htmlspecialchars(mediaUrl($foto, $base), ENT_QUOTES, 'UTF-8') ?>">
                    <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-4xl text-ink-dim">storefront</span>
                    </div>
                    <?php endif; ?>
                    <div class="absolute top-4 left-4">
                        <span class="bg-surface/80 backdrop-blur-md text-gold-soft font-label-mono text-[10px] uppercase tracking-wider px-3 py-1 rounded-full border border-gold-soft/20"><?= htmlspecialchars($katLabel, ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                </div>
                <div class="p-6 flex flex-col flex-grow gap-4">
                    <div>
                        <h3 class="font-h3 text-h3 text-ink mb-1 group-hover:text-primary transition-colors"><?= htmlspecialchars($nama, ENT_QUOTES, 'UTF-8') ?></h3>
                        <?php if ($usaha !== ''): ?>
                        <p class="font-body-md text-sm text-ink-dim"><?= htmlspecialchars($usaha, ENT_QUOTES, 'UTF-8') ?></p>
                        <?php endif; ?>
                    </div>
                    <?php if ($desc !== ''): ?>
                    <p class="font-body-md text-ink-dim line-clamp-2 mt-2"><?= htmlspecialchars($desc, ENT_QUOTES, 'UTF-8') ?></p>
                    <?php endif; ?>
                    <div class="flex items-center gap-2 mt-auto pt-4 border-t border-line text-sm text-ink-dim font-body-md flex-wrap">
                        <?php if ($pemilik !== ''): ?>
                        <span class="material-symbols-outlined text-[18px] text-gold-soft">person</span>
                        <span><?= htmlspecialchars($pemilik, ENT_QUOTES, 'UTF-8') ?></span>
                        <?php endif; ?>
                        <?php if ($pemilik !== '' && $dusun !== ''): ?>
                        <span class="mx-2 text-line-strong">•</span>
                        <?php endif; ?>
                        <?php if ($dusun !== ''): ?>
                        <span class="material-symbols-outlined text-[18px] text-gold-soft">location_on</span>
                        <span><?= htmlspecialchars($dusun, ENT_QUOTES, 'UTF-8') ?></span>
                        <?php endif; ?>
                    </div>
                    <a href="<?= htmlspecialchars($waUrl, ENT_QUOTES, 'UTF-8') ?>"
                       <?= $wa !== '' ? 'target="_blank" rel="noopener"' : '' ?>
                       class="w-full mt-4 bg-primary text-on-primary font-body-md py-3 rounded-full hover:bg-primary-fixed-dim transition-colors flex items-center justify-center gap-2 font-medium <?= $wa === '' ? 'opacity-50 pointer-events-none' : '' ?>">
                        <span class="material-symbols-outlined text-[20px]">chat</span>
                        Hubungi via WhatsApp
                    </a>
                </div>
            </article>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="<?= $items === [] ? 'flex' : 'hidden' ?> flex-col items-center justify-center py-20 text-center border border-dashed border-line rounded-2xl bg-surface/30" id="emptyState">
            <div class="w-20 h-20 bg-surface-2 rounded-full flex items-center justify-center mb-6 border border-line">
                <span class="material-symbols-outlined text-4xl text-ink-dim">search_off</span>
            </div>
            <h3 class="font-h3 text-h3 text-ink mb-2">Produk Tidak Ditemukan</h3>
            <p class="font-body-md text-ink-dim max-w-md">Maaf, kami tidak dapat menemukan UMKM atau produk yang sesuai dengan kriteria pencarian Anda. Silakan coba kata kunci lain.</p>
            <button class="mt-6 px-6 py-2 rounded-full border border-line hover:border-primary text-ink hover:text-primary transition-colors font-label-mono uppercase tracking-widest text-xs" id="resetSearchBtn">Reset Pencarian</button>
        </div>

    </section>

</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const filterBtns   = document.querySelectorAll('.filter-btn');
        const searchInput  = document.getElementById('searchInput');
        const emptyState   = document.getElementById('emptyState');
        const grid         = document.getElementById('umkmGrid');
        const resetBtn     = document.getElementById('resetSearchBtn');
        let currentFilter  = 'all';
        let searchQuery    = '';

        function cards() {
            return document.querySelectorAll('.umkm-card');
        }

        function filterCards() {
            let visibleCount = 0;
            cards().forEach(card => {
                const category     = card.dataset.category;
                const title        = (card.querySelector('h3')?.textContent || '').toLowerCase();
                const desc         = (card.querySelector('p')?.textContent || '').toLowerCase();
                const matchFilter  = currentFilter === 'all' || category === currentFilter;
                const matchSearch  = searchQuery === '' || title.includes(searchQuery) || desc.includes(searchQuery);
                if (matchFilter && matchSearch) { card.style.display = 'flex'; visibleCount++; }
                else { card.style.display = 'none'; }
            });
            if (visibleCount === 0) {
                grid.classList.add('hidden');
                emptyState.classList.remove('hidden'); emptyState.classList.add('flex');
            } else {
                grid.classList.remove('hidden');
                emptyState.classList.add('hidden'); emptyState.classList.remove('flex');
            }
        }

        filterBtns.forEach(btn => {
            btn.addEventListener('click', e => {
                filterBtns.forEach(b => { b.classList.remove('bg-primary','text-on-primary','active'); b.classList.add('bg-surface-2','text-ink'); });
                e.currentTarget.classList.remove('bg-surface-2','text-ink'); e.currentTarget.classList.add('bg-primary','text-on-primary','active');
                currentFilter = e.currentTarget.dataset.filter;
                filterCards();
            });
        });

        searchInput?.addEventListener('input', e => { searchQuery = e.target.value.toLowerCase(); filterCards(); });

        resetBtn?.addEventListener('click', () => {
            if (searchInput) searchInput.value = '';
            searchQuery = '';
            filterBtns.forEach(b => {
                if (b.dataset.filter === 'all') { b.classList.remove('bg-surface-2','text-ink'); b.classList.add('bg-primary','text-on-primary','active'); }
                else { b.classList.remove('bg-primary','text-on-primary','active'); b.classList.add('bg-surface-2','text-ink'); }
            });
            currentFilter = 'all'; filterCards();
        });
    });
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
