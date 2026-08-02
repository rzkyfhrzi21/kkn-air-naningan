<?php
$currentPage     = 'umkm';
$pageTitle       = 'UMKM | Pekon Air Naningan';
$metaDescription = 'Katalog produk UMKM dan usaha warga Pekon Air Naningan — kopi robusta, kuliner olahan, kerajinan bambu, dan gula aren murni.';
require __DIR__ . '/../partials/header.php';
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

        <!-- Filter buttons -->
        <div class="flex flex-wrap gap-3 mb-12 border-b border-line pb-6" id="filterContainer">
            <button class="filter-btn active bg-primary text-on-primary font-body-md px-6 py-2 rounded-full transition-colors" data-filter="all">Semua Kategori</button>
            <button class="filter-btn bg-surface-2 text-ink hover:bg-surface-container font-body-md px-6 py-2 rounded-full transition-colors border border-line hover:border-gold-soft/50" data-filter="kopi">Kopi &amp; Agrikultur</button>
            <button class="filter-btn bg-surface-2 text-ink hover:bg-surface-container font-body-md px-6 py-2 rounded-full transition-colors border border-line hover:border-gold-soft/50" data-filter="kuliner">Kuliner Olahan</button>
            <button class="filter-btn bg-surface-2 text-ink hover:bg-surface-container font-body-md px-6 py-2 rounded-full transition-colors border border-line hover:border-gold-soft/50" data-filter="kriya">Kriya &amp; Kerajinan</button>
            <button class="filter-btn bg-surface-2 text-ink hover:bg-surface-container font-body-md px-6 py-2 rounded-full transition-colors border border-line hover:border-gold-soft/50" data-filter="jasa">Jasa</button>
        </div>

        <!-- UMKM Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gutter" id="umkmGrid">

            <!-- Kopi Robusta -->
            <article class="umkm-card bg-surface-2 rounded-[14px] overflow-hidden group shadow-lg flex flex-col" data-category="kopi">
                <div class="relative h-64 overflow-hidden">
                    <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out"
                         alt="Kopi Bubuk Robusta Naningan"
                         src="https://lh3.googleusercontent.com/aida-public/AB6AXuAaKP4ffD4IWd9kcRdGk7hz4URWL994ZwNorvYHqJ7ixszHBWhvyZdlvehELGSeYbqtxuOZB9fQPN7INXv4ne6sl3AZb3LswtR58tCgpn0OgnRA7MswrAHk7CjCtw7H2L4O5Y-ZhkPv79ah0fd5hFFpyW07CHz1qL168xfTXl3qR0s44UpI-XjdK-ajOjLKmApqdiT6ghEOoMVfXzUIksPNVk5DlMkQ95_CI8yfamtjAcDoiZ8KHLhq-g">
                    <div class="absolute top-4 left-4">
                        <span class="bg-surface/80 backdrop-blur-md text-gold-soft font-label-mono text-[10px] uppercase tracking-wider px-3 py-1 rounded-full border border-gold-soft/20">Kopi &amp; Agrikultur</span>
                    </div>
                </div>
                <div class="p-6 flex flex-col flex-grow gap-4">
                    <div>
                        <h3 class="font-h3 text-h3 text-ink mb-1 group-hover:text-primary transition-colors">Kopi Bubuk Robusta Naningan</h3>
                        <p class="font-body-md text-sm text-ink-dim">Kopi Naningan Jaya Raya</p>
                    </div>
                    <p class="font-body-md text-ink-dim line-clamp-2 mt-2">Kopi robusta petik merah asli lereng tanggamus. Diolah secara tradisional untuk mempertahankan aroma dan rasa khas pekon.</p>
                    <div class="flex items-center gap-2 mt-auto pt-4 border-t border-line text-sm text-ink-dim font-body-md">
                        <span class="material-symbols-outlined text-[18px] text-gold-soft">person</span>
                        <span>Bpk. Suherman</span>
                        <span class="mx-2 text-line-strong">•</span>
                        <span class="material-symbols-outlined text-[18px] text-gold-soft">location_on</span>
                        <span>Dusun Sinar Jaya</span>
                    </div>
                    <button class="w-full mt-4 bg-primary text-on-primary font-body-md py-3 rounded-full hover:bg-primary-fixed-dim transition-colors flex items-center justify-center gap-2 font-medium">
                        <span class="material-symbols-outlined text-[20px]">chat</span>
                        Hubungi via WhatsApp
                    </button>
                </div>
            </article>

            <!-- Keripik Pisang -->
            <article class="umkm-card bg-surface-2 rounded-[14px] overflow-hidden group shadow-lg flex flex-col" data-category="kuliner">
                <div class="relative h-64 overflow-hidden">
                    <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out"
                         alt="Keripik Pisang Kepok Emas"
                         src="https://lh3.googleusercontent.com/aida-public/AB6AXuCetexOd4fJit-9DVoAGURRE3SbMOHAhaVGMnMte4znAq0tKRyoII36fdFLre-eaEdzExTMlqtYGA_cHPZlzrTmsQC7CqE20W3wfkIpZEf7KN1Bt4z38_uFga5t8cLf8llvfXB5cekRsj6cZaL8UiNhdZmu31a8O5qzI91yuj3-9OQ5AAuLC-Co74YZT53-eAAixctKkSAelxnTT_y4J0xCQe5zAbsXGfzawcRIGO-umtqTw8NUn7psEw">
                    <div class="absolute top-4 left-4">
                        <span class="bg-surface/80 backdrop-blur-md text-gold-soft font-label-mono text-[10px] uppercase tracking-wider px-3 py-1 rounded-full border border-gold-soft/20">Kuliner Olahan</span>
                    </div>
                </div>
                <div class="p-6 flex flex-col flex-grow gap-4">
                    <div>
                        <h3 class="font-h3 text-h3 text-ink mb-1 group-hover:text-primary transition-colors">Keripik Pisang Kepok Emas</h3>
                        <p class="font-body-md text-sm text-ink-dim">KWT Mekar Sari</p>
                    </div>
                    <p class="font-body-md text-ink-dim line-clamp-2 mt-2">Olahan pisang kepok lokal pilihan dengan berbagai varian rasa. Renyah, manis alami, dan diproduksi higienis oleh Kelompok Wanita Tani.</p>
                    <div class="flex items-center gap-2 mt-auto pt-4 border-t border-line text-sm text-ink-dim font-body-md">
                        <span class="material-symbols-outlined text-[18px] text-gold-soft">person</span>
                        <span>Ibu Sumiyati</span>
                        <span class="mx-2 text-line-strong">•</span>
                        <span class="material-symbols-outlined text-[18px] text-gold-soft">location_on</span>
                        <span>Dusun Induk</span>
                    </div>
                    <button class="w-full mt-4 bg-primary text-on-primary font-body-md py-3 rounded-full hover:bg-primary-fixed-dim transition-colors flex items-center justify-center gap-2 font-medium">
                        <span class="material-symbols-outlined text-[20px]">chat</span>
                        Hubungi via WhatsApp
                    </button>
                </div>
            </article>

            <!-- Anyaman Bambu -->
            <article class="umkm-card bg-surface-2 rounded-[14px] overflow-hidden group shadow-lg flex flex-col" data-category="kriya">
                <div class="relative h-64 overflow-hidden">
                    <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out"
                         alt="Kerajinan Anyaman Bambu"
                         src="https://lh3.googleusercontent.com/aida-public/AB6AXuAR6udTjHUTuCYC78xpmdS-u5I7zNyfv_34b87EyXsPDBFZ-MkMEM9dudNtjq-me-6Bd10M0y7KVG_5f0JSkuuZdAyHJ6UScfxZPexn1q1hDffJfu1DdFEe37xL0qyXaWK9BcKkyG_JUQNG5syw-ws7qHx9XRnRBrKwWQoTSuufx0s8Q5I9dVtILr97RrDAa8CE9PVxRnHH7XNft920Y3JDtEcblK8rB1BePQlyIcr1Vap-SiQjfohTGw">
                    <div class="absolute top-4 left-4">
                        <span class="bg-surface/80 backdrop-blur-md text-gold-soft font-label-mono text-[10px] uppercase tracking-wider px-3 py-1 rounded-full border border-gold-soft/20">Kriya &amp; Kerajinan</span>
                    </div>
                </div>
                <div class="p-6 flex flex-col flex-grow gap-4">
                    <div>
                        <h3 class="font-h3 text-h3 text-ink mb-1 group-hover:text-primary transition-colors">Kerajinan Anyaman Bambu</h3>
                        <p class="font-body-md text-sm text-ink-dim">Karya Bambu Lestari</p>
                    </div>
                    <p class="font-body-md text-ink-dim line-clamp-2 mt-2">Berbagai macam perabotan dan hiasan rumah tangga dari anyaman bambu berkualitas tinggi, kuat dan tahan lama.</p>
                    <div class="flex items-center gap-2 mt-auto pt-4 border-t border-line text-sm text-ink-dim font-body-md">
                        <span class="material-symbols-outlined text-[18px] text-gold-soft">person</span>
                        <span>Bpk. Tarjo</span>
                        <span class="mx-2 text-line-strong">•</span>
                        <span class="material-symbols-outlined text-[18px] text-gold-soft">location_on</span>
                        <span>Dusun Talang Baru</span>
                    </div>
                    <button class="w-full mt-4 bg-primary text-on-primary font-body-md py-3 rounded-full hover:bg-primary-fixed-dim transition-colors flex items-center justify-center gap-2 font-medium">
                        <span class="material-symbols-outlined text-[20px]">chat</span>
                        Hubungi via WhatsApp
                    </button>
                </div>
            </article>

            <!-- Gula Aren -->
            <article class="umkm-card bg-surface-2 rounded-[14px] overflow-hidden group shadow-lg flex flex-col" data-category="kuliner">
                <div class="relative h-64 overflow-hidden">
                    <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out"
                         alt="Gula Aren Murni Cetak"
                         src="https://lh3.googleusercontent.com/aida-public/AB6AXuC6pcIG4CaxK7bc_ubZxOih6MXit-2SH-vKsYUuzUgm3WILZAh3r3PolxDJvuLWoA3C5LY-azdYVlPBpMjhaFDNmVmln9BCFSs8cvfEWGJo94gKNVnt9tRg_X_Zx3kLKyIZWTOt5uj8yFx2GaGdxVH4MzygmyMtbJcmDsRr_UYy-FZHhrxMxRknnZMMoNmE8NYjJPIFG7HZzTsD6-KnFqe-ks5O94-lMzsVlqzqGHRPqLaU1opmQqCaXg">
                    <div class="absolute top-4 left-4">
                        <span class="bg-surface/80 backdrop-blur-md text-gold-soft font-label-mono text-[10px] uppercase tracking-wider px-3 py-1 rounded-full border border-gold-soft/20">Kuliner Olahan</span>
                    </div>
                </div>
                <div class="p-6 flex flex-col flex-grow gap-4">
                    <div>
                        <h3 class="font-h3 text-h3 text-ink mb-1 group-hover:text-primary transition-colors">Gula Aren Murni Cetak</h3>
                        <p class="font-body-md text-sm text-ink-dim">Gula Aren Naningan</p>
                    </div>
                    <p class="font-body-md text-ink-dim line-clamp-2 mt-2">Gula aren cetak murni dari nira aren pilihan tanpa campuran bahan kimia. Kualitas premium untuk berbagai kebutuhan dapur.</p>
                    <div class="flex items-center gap-2 mt-auto pt-4 border-t border-line text-sm text-ink-dim font-body-md">
                        <span class="material-symbols-outlined text-[18px] text-gold-soft">person</span>
                        <span>Bpk. Mulyadi</span>
                        <span class="mx-2 text-line-strong">•</span>
                        <span class="material-symbols-outlined text-[18px] text-gold-soft">location_on</span>
                        <span>Dusun Gunung Sari</span>
                    </div>
                    <button class="w-full mt-4 bg-primary text-on-primary font-body-md py-3 rounded-full hover:bg-primary-fixed-dim transition-colors flex items-center justify-center gap-2 font-medium">
                        <span class="material-symbols-outlined text-[20px]">chat</span>
                        Hubungi via WhatsApp
                    </button>
                </div>
            </article>

        </div>

        <!-- Empty state -->
        <div class="hidden flex-col items-center justify-center py-20 text-center border border-dashed border-line rounded-2xl bg-surface/30" id="emptyState">
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
        const cards        = document.querySelectorAll('.umkm-card');
        const searchInput  = document.getElementById('searchInput');
        const emptyState   = document.getElementById('emptyState');
        const grid         = document.getElementById('umkmGrid');
        const resetBtn     = document.getElementById('resetSearchBtn');
        let currentFilter  = 'all';
        let searchQuery    = '';

        function filterCards() {
            let visibleCount = 0;
            cards.forEach(card => {
                const category     = card.dataset.category;
                const title        = card.querySelector('h3').textContent.toLowerCase();
                const desc         = card.querySelector('p').textContent.toLowerCase();
                const matchFilter  = currentFilter === 'all' || category === currentFilter;
                const matchSearch  = title.includes(searchQuery) || desc.includes(searchQuery);
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
                filterBtns.forEach(b => { b.classList.remove('bg-primary','text-on-primary'); b.classList.add('bg-surface-2','text-ink'); });
                e.target.classList.remove('bg-surface-2','text-ink'); e.target.classList.add('bg-primary','text-on-primary');
                currentFilter = e.target.dataset.filter;
                filterCards();
            });
        });

        searchInput.addEventListener('input', e => { searchQuery = e.target.value.toLowerCase(); filterCards(); });

        resetBtn.addEventListener('click', () => {
            searchInput.value = ''; searchQuery = '';
            filterBtns.forEach(b => {
                if (b.dataset.filter === 'all') { b.classList.remove('bg-surface-2','text-ink'); b.classList.add('bg-primary','text-on-primary'); }
                else { b.classList.remove('bg-primary','text-on-primary'); b.classList.add('bg-surface-2','text-ink'); }
            });
            currentFilter = 'all'; filterCards();
        });
    });
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
