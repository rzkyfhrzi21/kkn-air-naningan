<?php
$pageTitle = 'Kelola Wisata';
$activeNav = 'kelola-wisata';
require __DIR__ . '/../partials/header.php';

$csrf = (string) ($_SESSION['csrf_token'] ?? '');
$base = defined('APP_BASE') ? APP_BASE : '';

$kategoriList = [
    'air-terjun'    => 'Air Terjun',
    'titik-pandang' => 'Titik Pandang',
    'wisata-alam'   => 'Wisata Alam',
    'agrowisata'    => 'Agrowisata',
];
?>
<div class="flex flex-col w-full">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6 pt-10 px-container-pad-mobile lg:px-container-pad-desktop">
        <div class="flex flex-col gap-2 max-w-2xl">
            <h1 class="font-h1-mobile lg:font-h1 text-h1-mobile lg:text-h1 text-ink">Kelola Wisata</h1>
            <p class="font-body-lg text-body-lg text-ink-dim">Data mengikuti katalog publik <code class="text-gold-soft">/wisata</code>. Tambah, edit, hapus destinasi wisata desa.</p>
        </div>
        <div class="flex items-center gap-3">
            <button type="button" id="btn-tambah-wisata"
                    class="flex items-center justify-center gap-2 px-6 py-3 rounded-full bg-primary text-on-primary font-label-mono text-label-mono uppercase tracking-widest hover:bg-primary-fixed transition-colors shadow-lg shadow-primary/10">
                <span class="material-symbols-outlined text-[20px]">add</span>
                Tambah Wisata
            </button>
        </div>
    </div>

    <!-- Toast -->


    <div class="flex-1 px-container-pad-mobile lg:px-container-pad-desktop pt-10 pb-section-v-desktop">
        <div class="flex flex-col gap-6">

            <!-- Stats Card -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-surface-2 rounded-2xl p-5 border border-line flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-primary text-[22px]">landscape</span>
                    </div>
                    <div>
                        <div class="font-label-mono text-label-mono text-ink-dim uppercase text-[10px] mb-0.5">Total Destinasi</div>
                        <div class="font-h2 text-h2 text-ink leading-none" id="stat-total">—</div>
                    </div>
                </div>
                <div class="bg-surface-2 rounded-2xl p-5 border border-line flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-primary text-[22px]">check_circle</span>
                    </div>
                    <div>
                        <div class="font-label-mono text-label-mono text-ink-dim uppercase text-[10px] mb-0.5">Status Buka</div>
                        <div class="font-h2 text-h2 text-ink leading-none" id="stat-buka">—</div>
                    </div>
                </div>
                <div class="bg-surface-2 rounded-2xl p-5 border border-line flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-surface-container border border-line flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-ink-dim text-[22px]">cancel</span>
                    </div>
                    <div>
                        <div class="font-label-mono text-label-mono text-ink-dim uppercase text-[10px] mb-0.5">Status Tutup</div>
                        <div class="font-h2 text-h2 text-ink-dim leading-none" id="stat-tutup">—</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <section class="lg:col-span-2 bg-surface rounded-2xl border border-line p-5 md:p-6" aria-labelledby="wisata-category-chart-title">
                    <div class="flex items-start justify-between gap-4 mb-2">
                        <div><h2 id="wisata-category-chart-title" class="font-h3 text-h3 text-ink">Sebaran Kategori</h2><p class="text-sm text-ink-dim mt-1">Jumlah destinasi pada setiap kategori wisata.</p></div>
                        <span class="font-label-mono text-[10px] uppercase tracking-widest text-gold-soft">Data Live</span>
                    </div>
                    <div id="wisata-category-chart" class="min-h-[280px]"></div>
                </section>
                <section class="bg-surface rounded-2xl border border-line p-5 md:p-6" aria-labelledby="wisata-status-chart-title">
                    <div><h2 id="wisata-status-chart-title" class="font-h3 text-h3 text-ink">Status Destinasi</h2><p class="text-sm text-ink-dim mt-1">Perbandingan destinasi buka dan tutup.</p></div>
                    <div id="wisata-status-chart" class="min-h-[280px]"></div>
                </section>
            </div>

            <!-- Filter Bar -->
            <div class="flex flex-col md:flex-row items-center justify-between gap-4 bg-surface p-4 rounded-xl border border-line">
                <div class="relative w-full md:w-96">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="material-symbols-outlined text-ink-dim text-[20px]">search</span>
                    </div>
                    <input id="search-wisata" class="w-full bg-surface-container-high border-none text-ink text-body-md font-body-md rounded-lg pl-10 pr-4 py-2.5 focus:ring-1 focus:ring-primary focus:outline-none placeholder:text-ink-dim/50" placeholder="Cari nama atau deskripsi wisata..." type="text"/>
                </div>
                <div class="flex items-center gap-2 w-full md:w-auto overflow-x-auto pb-1 md:pb-0" id="filter-wisata">
                    <button type="button" data-kat="all" class="kat-btn px-4 py-2 rounded-full bg-surface-2 text-ink border border-line-strong font-label-mono text-label-mono whitespace-nowrap">Semua</button>
                    <?php foreach ($kategoriList as $key => $label): ?>
                    <button type="button" data-kat="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>"
                            class="kat-btn px-4 py-2 rounded-full bg-transparent text-ink-dim border border-transparent font-label-mono text-label-mono whitespace-nowrap hover:bg-surface-2 transition-colors">
                        <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
                    </button>
                    <?php endforeach; ?>
                </div>
                <button type="button" id="btn-reset-filter" class="shrink-0 flex items-center gap-1.5 px-4 py-2.5 rounded-lg bg-surface border border-line text-ink-dim hover:text-ink hover:border-line-strong font-label-mono text-[11px] uppercase tracking-wider transition-colors" title="Reset semua filter">
                    <span class="material-symbols-outlined text-[16px]">refresh</span> Reset
                </button>
            </div>

            <!-- Table -->
            <div class="bg-surface rounded-2xl border border-line overflow-hidden flex flex-col shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[760px]">
                        <thead>
                            <tr class="border-b border-line-strong bg-surface-container/50">
                                <th class="py-4 px-6 font-label-mono text-label-mono text-ink-dim uppercase w-16">No</th>
                                <th class="py-4 px-6 font-label-mono text-label-mono text-ink-dim uppercase">Destinasi</th>
                                <th class="py-4 px-6 font-label-mono text-label-mono text-ink-dim uppercase w-40">Kategori</th>
                                <th class="py-4 px-6 font-label-mono text-label-mono text-ink-dim uppercase w-32">HTM</th>
                                <th class="py-4 px-6 font-label-mono text-label-mono text-ink-dim uppercase w-28">Status</th>
                                <th class="py-4 px-6 font-label-mono text-label-mono text-ink-dim uppercase w-24 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="wisata-tbody" class="font-body-md text-body-md text-ink">
                            <tr>
                                <td colspan="6" class="py-12 text-center text-ink-dim">
                                    <span class="inline-block w-6 h-6 border-2 border-primary border-t-transparent rounded-full animate-spin"></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="flex items-center justify-between px-6 py-4 border-t border-line bg-surface-container/30">
                    <span id="wisata-meta" class="font-label-mono text-label-mono text-ink-dim text-xs">—</span>
                    <div class="flex gap-2">
                        <button type="button" id="btn-prev" disabled class="px-4 py-2 rounded-full border border-line text-ink-dim font-label-mono text-xs uppercase disabled:opacity-40">Prev</button>
                        <button type="button" id="btn-next" disabled class="px-4 py-2 rounded-full border border-line text-ink-dim font-label-mono text-xs uppercase disabled:opacity-40">Next</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Form Tambah/Edit -->
<div id="modal-wisata" class="hidden fixed inset-0 z-[110] items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60" id="modal-wisata-backdrop"></div>
    <div class="relative w-full max-w-2xl bg-surface border border-line rounded-2xl shadow-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-line sticky top-0 bg-surface z-10">
            <h2 id="modal-wisata-title" class="font-h3 text-h3 text-ink">Tambah Wisata</h2>
            <button type="button" id="modal-wisata-close" class="w-9 h-9 rounded-full hover:bg-surface-2 flex items-center justify-center text-ink-dim hover:text-ink" aria-label="Tutup">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form id="form-wisata" class="p-6 flex flex-col gap-4">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>">
            <input type="hidden" name="id" id="wisata-id" value="">

            <label class="flex flex-col gap-1.5">
                <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest text-[10px]">Nama Destinasi *</span>
                <input name="nama" id="wisata-nama" required type="text" class="bg-surface-2 border border-line-strong rounded-xl p-3 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary" placeholder="Curug Tirta Kencana">
            </label>

            <div class="grid grid-cols-2 gap-3">
                <label class="flex flex-col gap-1.5">
                    <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest text-[10px]">Kategori</span>
                    <select name="kategori" id="wisata-kategori" class="bg-surface-2 border border-line-strong rounded-xl p-3 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary">
                        <?php foreach ($kategoriList as $key => $label): ?>
                        <option value="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label class="flex flex-col gap-1.5">
                    <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest text-[10px]">Status</span>
                    <select name="status" id="wisata-status" class="bg-surface-2 border border-line-strong rounded-xl p-3 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary">
                        <option value="buka">Buka</option>
                        <option value="tutup">Tutup</option>
                    </select>
                </label>
            </div>

            <label class="flex flex-col gap-1.5">
                <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest text-[10px]">Deskripsi *</span>
                <textarea name="deskripsi" id="wisata-deskripsi" rows="3" required class="bg-surface-2 border border-line-strong rounded-xl p-3 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary resize-y" placeholder="Deskripsi destinasi wisata..."></textarea>
            </label>

            <div class="grid grid-cols-2 gap-3">
                <label class="flex flex-col gap-1.5">
                    <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest text-[10px]">HTM (Tiket Masuk)</span>
                    <input name="jarak" id="wisata-jarak" type="text" class="bg-surface-2 border border-line-strong rounded-xl p-3 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary" placeholder="Rp 10.000 / Gratis">
                </label>
                <label class="flex flex-col gap-1.5">
                    <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest text-[10px]">URL Google Maps</span>
                    <input name="maps_url" id="wisata-maps" type="url" class="bg-surface-2 border border-line-strong rounded-xl p-3 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary" placeholder="https://maps.google.com/...">
                </label>
            </div>

            <!-- Foto Upload -->
            <div class="flex flex-col gap-1.5">
                <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest text-[10px]">Foto</span>
                <!-- Preview foto lama (mode edit) -->
                <div id="wisata-foto-preview" class="hidden items-center gap-3 p-3 bg-surface-container-high rounded-xl border border-line">
                    <button type="button" class="w-14 h-14 rounded-lg overflow-hidden shrink-0 border border-line-strong bg-surface-container cursor-zoom-in" data-photo-preview aria-label="Perbesar foto saat ini">
                        <img id="wisata-foto-preview-img" src="" alt="Foto saat ini" class="w-full h-full object-cover">
                    </button>
                    <div class="flex flex-col gap-0.5 min-w-0">
                        <span class="font-label-mono text-[10px] text-ink-dim uppercase tracking-wider">Foto saat ini</span>
                        <p class="text-[11px] text-ink-dim/70 truncate" id="wisata-foto-preview-url"></p>
                        <span class="text-[11px] text-ink-dim/50">Unggah file baru untuk menggantinya</span>
                    </div>
                </div>
                <!-- File picker -->
                <label for="wisata-foto-file" class="cursor-pointer flex items-center gap-3 p-3 bg-surface-2 border-2 border-dashed border-line-strong rounded-xl hover:border-primary transition-colors group">
                    <div class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center shrink-0 group-hover:bg-primary/10">
                        <span class="material-symbols-outlined text-ink-dim group-hover:text-primary text-[20px]">upload</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <span class="block font-body-md text-sm text-ink" id="wisata-foto-label">Klik untuk pilih foto</span>
                        <span class="block text-[11px] text-ink-dim mt-0.5">Maks 2MB &middot; JPG, PNG, GIF, WebP</span>
                    </div>
                </label>
                <input type="file" name="foto_file" id="wisata-foto-file" accept="image/jpeg,image/png,image/gif,image/webp" class="sr-only">
                <!-- Preview file baru -->
                <div id="wisata-foto-new-preview" class="hidden flex-col gap-2">
                    <button type="button" class="w-full rounded-xl overflow-hidden border border-line-strong cursor-zoom-in" data-photo-preview aria-label="Perbesar foto yang dipilih">
                        <img id="wisata-foto-new-img" src="" alt="Preview foto yang dipilih" class="w-full max-h-40 object-cover">
                    </button>
                    <button type="button" id="wisata-foto-clear" class="self-start flex items-center gap-1 text-[11px] text-ink-dim hover:text-danger font-label-mono">
                        <span class="material-symbols-outlined text-[14px]">close</span> Hapus pilihan
                    </button>
                </div>
            </div>

            <label class="flex flex-col gap-1.5">
                <div class="flex items-center gap-1.5">
                    <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest text-[10px]">Fasilitas</span>
                    <button type="button" id="btn-bantuan-ikon"
                       title="Cara mencari nama ikon Material Symbols"
                       aria-label="Cara mencari nama ikon Material Symbols"
                       class="w-4 h-4 rounded-full bg-surface-container border border-line flex items-center justify-center text-ink-dim hover:text-primary hover:border-primary transition-colors">
                        <span class="material-symbols-outlined text-[11px]">help</span>
                    </button>
                </div>
                <div id="fasilitas-list" class="flex flex-col gap-2 min-h-[36px]"></div>
                <button type="button" id="btn-add-fasilitas"
                        class="self-start flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-surface-container border border-line text-ink-dim hover:text-ink hover:border-line-strong text-[12px] font-label-mono transition-colors">
                    <span class="material-symbols-outlined text-[14px]">add</span> Tambah Fasilitas
                </button>
                <span class="text-[11px] text-ink-dim/50">Icon: nama Material Symbol — <code>hiking</code>, <code>wc</code>, <code>terrain</code>, <code>camping</code>, <code>local_parking</code>, <code>storefront</code>, <code>park</code>…</span>
            </label>

            <label class="flex items-center gap-2 cursor-pointer">
                <input name="offset" id="wisata-offset" type="checkbox" value="1" class="rounded border-line-strong text-primary focus:ring-primary">
                <span class="font-body-md text-sm text-ink">Geser card ke bawah (stagger layout halaman publik)</span>
            </label>

            <div class="flex justify-end gap-3 pt-2 border-t border-line mt-2">
                <button type="button" id="modal-wisata-batal" class="px-5 py-2.5 rounded-full font-label-mono text-[11px] uppercase tracking-wider text-ink bg-surface-2 hover:bg-surface-container-highest">Batal</button>
                <button type="submit" id="btn-simpan-wisata" class="px-6 py-2.5 rounded-full font-label-mono text-[11px] uppercase tracking-wider bg-primary text-on-primary hover:bg-primary-fixed flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">save</span> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal preview foto -->
<div id="modal-preview-foto-wisata" class="hidden fixed inset-0 z-[140] items-center justify-center p-4 md:p-8" role="dialog" aria-modal="true" aria-label="Preview foto wisata">
    <div class="absolute inset-0 bg-black/80" id="modal-preview-foto-wisata-backdrop"></div>
    <div class="relative flex max-h-full max-w-5xl items-center justify-center">
        <img id="modal-preview-foto-wisata-img" src="" alt="Preview foto wisata" class="max-h-[85vh] max-w-full rounded-2xl border border-line object-contain shadow-2xl">
        <button type="button" id="modal-preview-foto-wisata-close" class="absolute -right-2 -top-2 flex h-11 w-11 items-center justify-center rounded-full border border-line bg-surface text-ink shadow-lg hover:bg-surface-2" aria-label="Tutup preview foto">
            <span class="material-symbols-outlined">close</span>
        </button>
    </div>
</div>

<!-- Modal Bantuan Ikon -->
<div id="modal-bantuan-ikon" class="hidden fixed inset-0 z-[130] items-center justify-center p-4" role="dialog" aria-modal="true" aria-labelledby="modal-bantuan-ikon-title">
    <div class="absolute inset-0 bg-black/60" id="modal-bantuan-ikon-backdrop"></div>
    <div class="relative w-full max-w-md bg-surface border border-line rounded-2xl shadow-2xl p-6 flex flex-col gap-4">
        <div class="flex items-start justify-between gap-4">
            <div>
                <span class="font-label-mono text-[10px] uppercase tracking-widest text-gold-soft">Referensi ikon</span>
                <h2 id="modal-bantuan-ikon-title" class="font-h3 text-h3 text-ink mt-1">Cari nama ikon Material Symbols</h2>
            </div>
            <button type="button" id="modal-bantuan-ikon-close" class="w-9 h-9 rounded-full hover:bg-surface-2 flex items-center justify-center text-ink-dim hover:text-ink shrink-0" aria-label="Tutup bantuan ikon">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <p class="font-body-md text-sm leading-6 text-ink-dim">Gunakan halaman Google Fonts Icons untuk mencari ikon yang sesuai. Salin nama ikon yang dipilih, lalu tempelkan ke kolom <strong class="text-ink">Icon</strong> pada fasilitas.</p>
        <a href="https://fonts.google.com/icons" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-between gap-3 rounded-xl border border-primary/30 bg-primary/10 px-4 py-3 text-sm text-primary hover:bg-primary/15 transition-colors">
            <span class="font-label-mono">fonts.google.com/icons</span>
            <span class="material-symbols-outlined text-[18px]">open_in_new</span>
        </a>
        <p class="text-[11px] text-ink-dim/60">Contoh nama ikon: <code>hiking</code>, <code>wc</code>, <code>terrain</code>, <code>camping</code>.</p>
    </div>
</div>

<!-- Modal Hapus -->
<div id="modal-hapus-wisata" class="hidden fixed inset-0 z-[110] items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60" id="modal-hapus-wisata-backdrop"></div>
    <div class="relative w-full max-w-sm bg-surface border border-line rounded-2xl shadow-2xl p-6 flex flex-col gap-4">
        <h2 class="font-h3 text-h3 text-ink">Hapus Wisata?</h2>
        <p class="font-body-md text-ink-dim text-sm">Yakin hapus <strong id="hapus-wisata-nama" class="text-ink"></strong>? Tindakan ini tidak bisa dibatalkan.</p>
        <div class="flex justify-end gap-3">
            <button type="button" id="hapus-wisata-batal" class="px-5 py-2.5 rounded-full font-label-mono text-[11px] uppercase bg-surface-2 text-ink">Batal</button>
            <button type="button" id="hapus-wisata-ya" class="px-5 py-2.5 rounded-full font-label-mono text-[11px] uppercase bg-danger text-white">Hapus</button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
(function () {
    const base = <?= json_encode($base, JSON_UNESCAPED_SLASHES) ?>;
    const csrf = <?= json_encode($csrf) ?>;
    let page = 1, search = '', kategori = 'all', hasNext = false, hasPrev = false;
    let deleteId = null, searchTimer = null;

    const tbody    = document.getElementById('wisata-tbody');
    const meta     = document.getElementById('wisata-meta');
    const btnPrev  = document.getElementById('btn-prev');
    const btnNext  = document.getElementById('btn-next');
    const modal    = document.getElementById('modal-wisata');
    const modalDel = document.getElementById('modal-hapus-wisata');
    const modalIcon = document.getElementById('modal-bantuan-ikon');
    const photoModal = document.getElementById('modal-preview-foto-wisata');
    const photoModalImg = document.getElementById('modal-preview-foto-wisata-img');
    const form     = document.getElementById('form-wisata');
    let categoryChart = null, statusChart = null;

    /* ── Toast ── */
    function toast(msg, ok) {
        window.showAdminToast(msg, ok);
    }

    function esc(s) {
        const d = document.createElement('div');
        d.textContent = s ?? '';
        return d.innerHTML;
    }

    function mediaUrl(path) {
        const value = String(path || '').trim();
        if (!value || /^(https?:)?\/\//i.test(value) || value.startsWith('blob:') || value.startsWith('data:')) return value;
        if (value.startsWith(base + '/')) return value;
        return (value.startsWith('/') ? base : base + '/') + value.replace(/^\/+/, '');
    }

    function openPhotoPreview(src) {
        if (!src) return;
        photoModalImg.src = src;
        photoModal.classList.remove('hidden');
        photoModal.classList.add('flex');
    }

    function closePhotoPreview() {
        photoModal.classList.add('hidden');
        photoModal.classList.remove('flex');
        photoModalImg.src = '';
    }

    function chartColors() {
        const styles = getComputedStyle(document.documentElement);
        return {
            primary: styles.getPropertyValue('--color-primary').trim(),
            tertiary: styles.getPropertyValue('--color-tertiary-fixed-dim').trim(),
            ink: styles.getPropertyValue('--color-ink').trim(),
            inkDim: styles.getPropertyValue('--color-ink-dim').trim(),
            line: styles.getPropertyValue('--color-line').trim(),
        };
    }

    function updateCharts(stats) {
        if (typeof ApexCharts === 'undefined') return;
        const colors = chartColors();
        const categories = Object.keys(stats.stat_kategori || {});
        const categoryValues = Object.values(stats.stat_kategori || {}).map(Number);
        if (!categoryChart) {
            categoryChart = new ApexCharts(document.querySelector('#wisata-category-chart'), {
                chart: { type: 'bar', height: 280, toolbar: { show: false }, background: 'transparent', fontFamily: 'Public Sans, sans-serif' },
                series: [{ name: 'Destinasi', data: categoryValues }], colors: [colors.primary],
                plotOptions: { bar: { borderRadius: 6, columnWidth: '42%', distributed: true } },
                dataLabels: { enabled: false }, legend: { show: false },
                xaxis: { categories, labels: { style: { colors: colors.inkDim, fontSize: '11px' } }, axisBorder: { color: colors.line }, axisTicks: { color: colors.line } },
                yaxis: { min: 0, forceNiceScale: true, labels: { style: { colors: colors.inkDim }, formatter: value => Number.isInteger(value) ? value : '' } },
                grid: { borderColor: colors.line, strokeDashArray: 4 }, tooltip: { theme: 'dark' }
            });
            categoryChart.render();
        } else {
            categoryChart.updateOptions({ xaxis: { categories } });
            categoryChart.updateSeries([{ name: 'Destinasi', data: categoryValues }]);
        }
        const statusValues = [Number(stats.stat_buka || 0), Number(stats.stat_tutup || 0)];
        if (!statusChart) {
            statusChart = new ApexCharts(document.querySelector('#wisata-status-chart'), {
                chart: { type: 'donut', height: 280, background: 'transparent', fontFamily: 'Public Sans, sans-serif' },
                series: statusValues, labels: ['Buka', 'Tutup'], colors: [colors.primary, colors.tertiary],
                stroke: { width: 2, colors: [colors.line] }, dataLabels: { enabled: false },
                legend: { position: 'bottom', labels: { colors: colors.inkDim } },
                plotOptions: { pie: { donut: { size: '68%', labels: { show: true, total: { show: true, label: 'Total', color: colors.inkDim, formatter: () => String(stats.total || 0) }, value: { color: colors.ink } } } } },
                tooltip: { theme: 'dark' }
            });
            statusChart.render();
        } else {
            statusChart.updateSeries(statusValues);
            statusChart.updateOptions({ plotOptions: { pie: { donut: { labels: { total: { formatter: () => String(stats.total || 0) } } } } } });
        }
    }

    /* ── Load tabel dari AJAX ── */
    async function loadList() {
        tbody.innerHTML = '<tr><td colspan="6" class="py-12 text-center"><span class="inline-block w-6 h-6 border-2 border-primary border-t-transparent rounded-full animate-spin"></span></td></tr>';
        const fd = new FormData();
        fd.append('page', page);
        fd.append('search', search);
        fd.append('kategori', kategori);
        try {
            const res  = await fetch(base + '/admin/ajax/list-wisata', { method: 'POST', body: fd, credentials: 'same-origin' });
            const json = await res.json();
            if (!json.success) { toast(json.message || 'Gagal memuat.', false); return; }
            hasNext = json.has_next; hasPrev = json.has_prev;
            btnPrev.disabled = !hasPrev; btnNext.disabled = !hasNext;
            meta.textContent = json.total + ' data · halaman ' + json.page;
            // Update stats card (selalu update, bukan hanya saat filter kosong)
            document.getElementById('stat-total').textContent = json.total;
            if (json.stat_buka  !== undefined) document.getElementById('stat-buka').textContent  = json.stat_buka;
            if (json.stat_tutup !== undefined) document.getElementById('stat-tutup').textContent = json.stat_tutup;
            updateCharts(json);
            if (!json.data.length) {
                tbody.innerHTML = '<tr><td colspan="6" class="py-12 text-center text-ink-dim">Belum ada data wisata.</td></tr>';
                return;
            }
            const start = (json.page - 1) * 10;
            tbody.innerHTML = json.data.map((row, i) => {
                const no    = String(start + i + 1).padStart(2, '0');
                const buka  = (row.status || 'buka') === 'buka';
                const fasil = (row.fasilitas || []).map(f => esc(f.label)).join(', ') || '—';
                return `<tr class="border-b border-line/50 hover:bg-surface-2 transition-colors group">
                    <td class="py-4 px-6 text-ink-dim font-label-mono text-label-mono">${no}</td>
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-3">
                            ${row.foto ? `<button type="button" data-photo-src="${esc(mediaUrl(row.foto))}" class="w-10 h-10 rounded-lg bg-surface-container-high overflow-hidden shrink-0 border border-line cursor-zoom-in" aria-label="Perbesar foto ${esc(row.nama)}"><img src="${esc(mediaUrl(row.foto))}" alt="Foto ${esc(row.nama)}" class="w-full h-full object-cover"></button>` : `<div class="w-10 h-10 rounded-lg bg-surface-container-high overflow-hidden shrink-0 border border-line"><span class="material-symbols-outlined text-gold-soft text-[20px] flex items-center justify-center w-full h-full">landscape</span></div>`}
                            <div>
                                <div class="font-medium text-ink">${esc(row.nama)}</div>
                                <div class="text-[12px] text-ink-dim mt-0.5 line-clamp-1">${esc(row.deskripsi || '—')}</div>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-6"><span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-surface-container-high text-gold-soft text-[12px] font-medium border border-gold-soft/20">
                        <span class="material-symbols-outlined text-[14px]">${esc(row.kategori_icon || 'landscape')}</span>
                        ${esc(row.kategori_label || row.kategori)}
                    </span></td>
                    <td class="py-4 px-6 text-ink-dim font-label-mono text-[13px]">${esc(row.jarak || '—')}</td>
                    <td class="py-4 px-6">
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[12px] font-medium ${buka ? 'bg-primary/10 text-primary border border-primary/20' : 'bg-surface-container text-ink-dim border border-line'}">
                            <div class="w-1.5 h-1.5 rounded-full ${buka ? 'bg-primary' : 'bg-ink-dim'}"></div>${buka ? 'Buka' : 'Tutup'}
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center justify-end gap-1">
                            <button type="button" data-edit="${esc(row.id)}" class="w-8 h-8 rounded-full hover:bg-surface-container-highest flex items-center justify-center text-ink-dim hover:text-ink" title="Edit"><span class="material-symbols-outlined text-[18px]">edit</span></button>
                            <button type="button" data-del="${esc(row.id)}" data-nama="${esc(row.nama)}" class="w-8 h-8 rounded-full hover:bg-surface-container-highest flex items-center justify-center text-ink-dim hover:text-danger" title="Hapus"><span class="material-symbols-outlined text-[18px]">delete</span></button>
                        </div>
                    </td>
                </tr>`;
            }).join('');
        } catch (e) {
            toast('Gagal menghubungi server.', false);
            tbody.innerHTML = '<tr><td colspan="6" class="py-12 text-center text-danger">Error memuat data.</td></tr>';
        }
    }

    /* ── Modal helpers ── */
    function openModal(title) {
        document.getElementById('modal-wisata-title').textContent = title;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        form.reset();
        document.getElementById('wisata-id').value = '';
        clearFasilitas();
        // Reset file input
        const fi = document.getElementById('wisata-foto-file');
        if (fi) fi.value = '';
        document.getElementById('wisata-foto-label').textContent = 'Klik untuk pilih foto';
        document.getElementById('wisata-foto-preview')?.classList.replace('flex', 'hidden');
        document.getElementById('wisata-foto-preview')?.classList.add('hidden');
        document.getElementById('wisata-foto-new-preview')?.classList.replace('flex', 'hidden');
        document.getElementById('wisata-foto-new-preview')?.classList.add('hidden');
    }
    function openDel() {
        modalDel.classList.remove('hidden');
        modalDel.classList.add('flex');
    }
    function closeDel() {
        modalDel.classList.add('hidden');
        modalDel.classList.remove('flex');
        deleteId = null;
    }
    function openIconHelp() {
        modalIcon.classList.remove('hidden');
        modalIcon.classList.add('flex');
    }
    function closeIconHelp() {
        modalIcon.classList.add('hidden');
        modalIcon.classList.remove('flex');
    }

    /* ── Tombol Tambah ── */
    document.getElementById('btn-tambah-wisata')?.addEventListener('click', () => {
        form.reset();
        document.getElementById('wisata-id').value = '';
        clearFasilitas();
        addFasilitasRow(); // 1 baris kosong saat tambah baru
        openModal('Tambah Wisata');
    });
    document.getElementById('modal-wisata-close')?.addEventListener('click', closeModal);
    document.getElementById('modal-wisata-batal')?.addEventListener('click', closeModal);
    document.getElementById('modal-wisata-backdrop')?.addEventListener('click', closeModal);
    document.getElementById('hapus-wisata-batal')?.addEventListener('click', closeDel);
    document.getElementById('modal-hapus-wisata-backdrop')?.addEventListener('click', closeDel);
    document.getElementById('btn-bantuan-ikon')?.addEventListener('click', openIconHelp);
    document.getElementById('modal-bantuan-ikon-close')?.addEventListener('click', closeIconHelp);
    document.getElementById('modal-bantuan-ikon-backdrop')?.addEventListener('click', closeIconHelp);
    document.getElementById('modal-preview-foto-wisata-close')?.addEventListener('click', closePhotoPreview);
    document.getElementById('modal-preview-foto-wisata-backdrop')?.addEventListener('click', closePhotoPreview);
    document.addEventListener('click', (e) => {
        const trigger = e.target.closest('[data-photo-preview]');
        if (!trigger) return;
        openPhotoPreview(trigger.querySelector('img')?.src || '');
    });

    /* ── Fasilitas dynamic builder ── */
    let fasCounter = 0;

    function makeFasilitasRow(icon = '', label = '') {
        const idx = fasCounter++;
        const row = document.createElement('div');
        row.className = 'fasilitas-row flex items-center gap-2';
        row.innerHTML = `
            <div class="w-9 h-9 rounded-lg bg-surface-container border border-line flex items-center justify-center shrink-0" title="Preview icon">
                <span class="material-symbols-outlined text-gold-soft text-[18px] fas-icon-preview">${icon || 'check'}</span>
            </div>
            <input type="text" name="fasilitas[${idx}][icon]" placeholder="hiking"
                   value="${esc(icon)}"
                   class="w-28 shrink-0 bg-surface-2 border border-line-strong rounded-lg px-2.5 py-2 text-on-surface font-label-mono text-[12px] focus:outline-none focus:ring-1 focus:ring-primary"
                   title="Nama Material Symbol icon">
            <input type="text" name="fasilitas[${idx}][label]" placeholder="Nama fasilitas"
                   value="${esc(label)}"
                   class="flex-1 bg-surface-2 border border-line-strong rounded-lg px-2.5 py-2 text-on-surface font-body-md text-sm focus:outline-none focus:ring-1 focus:ring-primary">
            <button type="button" class="remove-fasilitas w-8 h-8 rounded-full hover:bg-danger/10 flex items-center justify-center text-ink-dim hover:text-danger shrink-0" title="Hapus">
                <span class="material-symbols-outlined text-[16px]">close</span>
            </button>`;
        // Live preview icon saat icon input berubah
        const iconInput = row.querySelector('[name$="[icon]"]');
        const iconPreview = row.querySelector('.fas-icon-preview');
        iconInput.addEventListener('input', () => {
            iconPreview.textContent = iconInput.value.trim() || 'check';
        });
        // Remove row
        row.querySelector('.remove-fasilitas').addEventListener('click', () => row.remove());
        return row;
    }

    function addFasilitasRow(icon = '', label = '') {
        document.getElementById('fasilitas-list').appendChild(makeFasilitasRow(icon, label));
    }

    function populateFasilitas(arr) {
        clearFasilitas();
        if (arr && arr.length) {
            arr.forEach(f => addFasilitasRow(f.icon || '', f.label || ''));
        } else {
            addFasilitasRow(); // 1 baris kosong
        }
    }

    function clearFasilitas() {
        document.getElementById('fasilitas-list').innerHTML = '';
    }

    document.getElementById('btn-add-fasilitas')?.addEventListener('click', () => addFasilitasRow());

    tbody?.addEventListener('click', async (e) => {
        const photoBtn = e.target.closest('[data-photo-src]');
        const editBtn = e.target.closest('[data-edit]');
        const delBtn  = e.target.closest('[data-del]');

        if (photoBtn) {
            openPhotoPreview(photoBtn.dataset.photoSrc);
            return;
        }

        if (editBtn) {
            const id = editBtn.dataset.edit;
            const fd = new FormData();
            fd.append('id', id);
            try {
                const res  = await fetch(base + '/admin/ajax/get-wisata', { method: 'POST', body: fd, credentials: 'same-origin' });
                if (res.status === 401) {
                    toast('Sesi habis. Mengalihkan ke halaman login…', false);
                    setTimeout(() => { window.location.href = base + '/admin/login'; }, 1800);
                    return;
                }
                const json = await res.json();
                if (!json.success) { toast(json.message || 'Gagal memuat data.', false); return; }
                const d = json.data;
                document.getElementById('wisata-id').value        = d.id || '';
                document.getElementById('wisata-nama').value      = d.nama || '';
                document.getElementById('wisata-kategori').value  = d.kategori || 'air-terjun';
                document.getElementById('wisata-status').value    = d.status || 'buka';
                document.getElementById('wisata-deskripsi').value = d.deskripsi || '';
                document.getElementById('wisata-jarak').value     = d.jarak || '';
                document.getElementById('wisata-maps').value      = d.maps_url || '';
                document.getElementById('wisata-offset').checked  = !!d.offset;
                // Reset file input
                const fi = document.getElementById('wisata-foto-file');
                if (fi) fi.value = '';
                document.getElementById('wisata-foto-label').textContent = 'Klik untuk pilih foto';
                document.getElementById('wisata-foto-new-preview')?.classList.replace('flex', 'hidden');
                document.getElementById('wisata-foto-new-preview')?.classList.add('hidden');
                // Fasilitas: populate dynamic builder
                populateFasilitas(d.fasilitas || []);
                // Preview foto lama
                const previewBox = document.getElementById('wisata-foto-preview');
                const previewImg = document.getElementById('wisata-foto-preview-img');
                const previewUrl = document.getElementById('wisata-foto-preview-url');
                if (d.foto) {
                     previewImg.src         = mediaUrl(d.foto);
                    previewUrl.textContent = d.foto;
                    previewBox.classList.remove('hidden');
                    previewBox.classList.add('flex');
                } else {
                    previewBox.classList.add('hidden');
                    previewBox.classList.remove('flex');
                }
                openModal('Edit Wisata');
            } catch (err) { console.error('get-wisata error:', err); toast('Gagal memuat data wisata.', false); }
        }

        if (delBtn) {
            deleteId = delBtn.dataset.del;
            document.getElementById('hapus-wisata-nama').textContent = delBtn.dataset.nama || '';
            openDel();
        }
    });

    /* ── Hapus ── */
    document.getElementById('hapus-wisata-ya')?.addEventListener('click', async () => {
        if (!deleteId) return;
        const fd = new FormData();
        fd.append('csrf_token', csrf);
        fd.append('id', deleteId);
        try {
            const res  = await fetch(base + '/admin/ajax/delete-wisata', { method: 'POST', body: fd, credentials: 'same-origin' });
            const json = await res.json();
            toast(json.message || (json.success ? 'Dihapus.' : 'Gagal.'), !!json.success);
            if (json.success) { closeDel(); loadList(); }
        } catch (err) { toast('Gagal menghubungi server.', false); }
    });

    /* ── Simpan (Tambah / Edit) ── */
    form?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const btn = document.getElementById('btn-simpan-wisata');
        btn.disabled = true;
        try {
            const fd = new FormData(form);
            if (!document.getElementById('wisata-offset').checked) fd.delete('offset');
            const res  = await fetch(base + '/admin/ajax/store-wisata', { method: 'POST', body: fd, credentials: 'same-origin' });
            const json = await res.json();
            toast(json.message || (json.success ? 'Tersimpan.' : 'Gagal.'), !!json.success);
            if (json.success) { closeModal(); loadList(); }
        } catch (err) { toast('Gagal menghubungi server.', false); }
        finally { btn.disabled = false; }
    });

    /* ── Search ── */
    document.getElementById('search-wisata')?.addEventListener('input', (e) => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => { search = e.target.value.trim(); page = 1; loadList(); }, 300);
    });

    /* ── Filter kategori ── */
    document.getElementById('filter-wisata')?.addEventListener('click', (e) => {
        const btn = e.target.closest('.kat-btn');
        if (!btn) return;
        document.querySelectorAll('.kat-btn').forEach(b => {
            b.classList.remove('bg-surface-2', 'text-ink', 'border-line-strong');
            b.classList.add('bg-transparent', 'text-ink-dim', 'border-transparent');
        });
        btn.classList.remove('bg-transparent', 'text-ink-dim', 'border-transparent');
        btn.classList.add('bg-surface-2', 'text-ink', 'border-line-strong');
        kategori = btn.dataset.kat;
        page = 1;
        loadList();
    });

    /* ── Reset filter ── */
    document.getElementById('btn-reset-filter')?.addEventListener('click', () => {
        document.getElementById('search-wisata').value = '';
        search = '';
        document.querySelector('#filter-wisata .kat-btn[data-kat="all"]')?.click();
    });

    /* ── Pagination ── */
    btnPrev?.addEventListener('click', () => { if (hasPrev) { page--; loadList(); } });
    btnNext?.addEventListener('click', () => { if (hasNext) { page++; loadList(); } });

    /* ── File input foto ── */
    document.getElementById('wisata-foto-file')?.addEventListener('change', (e) => {
        const file       = e.target.files[0];
        const label      = document.getElementById('wisata-foto-label');
        const newPreview = document.getElementById('wisata-foto-new-preview');
        const newImg     = document.getElementById('wisata-foto-new-img');
        if (file) {
            if (file.size > 2 * 1024 * 1024) {
                toast('Ukuran foto maks 2MB.', false);
                e.target.value = '';
                label.textContent = 'Klik untuk pilih foto';
                return;
            }
            label.textContent = file.name;
            newImg.src = URL.createObjectURL(file);
            newPreview.classList.remove('hidden');
            newPreview.classList.add('flex');
        } else {
            label.textContent = 'Klik untuk pilih foto';
            newPreview.classList.add('hidden');
            newPreview.classList.remove('flex');
        }
    });
    document.getElementById('wisata-foto-clear')?.addEventListener('click', () => {
        const fi = document.getElementById('wisata-foto-file');
        if (fi) fi.value = '';
        document.getElementById('wisata-foto-label').textContent = 'Klik untuk pilih foto';
        const np = document.getElementById('wisata-foto-new-preview');
        np?.classList.add('hidden');
        np?.classList.remove('flex');
    });

    loadList();
})();
</script>
<?php require __DIR__ . '/../partials/footer.php'; ?>
