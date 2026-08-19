<?php
/* ======================================================
   HALAMAN KELOLA UMKM (KATALOG PRODUCT MANAGEMENT)

   Halaman ini adalah "meja pendaftaran usaha warga" panel admin.
   Dari sini admin bisa:
   - Melihat daftar usaha UMKM warga pekon,
   - Menambahkan produk UMKM baru / mengedit data UMKM lama (nama, kategori, pemilik, no WA, foto, status),
   - Menghapus data UMKM.

   Tabel data UMKM diisi secara interaktif oleh JavaScript via AJAX (list-umkm.php).
====================================================== */

$pageTitle = 'Kelola UMKM';
$activeNav = 'kelola-umkm';
require __DIR__ . '/../partials/header.php';

$kategori = $kategori ?? [];
$csrf     = (string) ($_SESSION['csrf_token'] ?? '');
$base     = defined('APP_BASE') ? APP_BASE : '';
?>

<div class="flex flex-col w-full">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6 pt-10 px-container-pad-mobile lg:px-container-pad-desktop">
        <div class="flex flex-col gap-2 max-w-2xl">
            <h1 class="font-h1-mobile lg:font-h1 text-h1-mobile lg:text-h1 text-ink">Kelola UMKM</h1>
            <p class="font-body-lg text-body-lg text-ink-dim">Data mengikuti katalog publik <code class="text-gold-soft">/umkm</code>. Tambah, edit, hapus produk usaha warga.</p>
        </div>
        <div class="flex items-center gap-3">
            <button type="button" id="btn-tambah-umkm"
                    class="flex items-center justify-center gap-2 px-6 py-3 rounded-full bg-primary text-on-primary font-label-mono text-label-mono uppercase tracking-widest hover:bg-primary-fixed transition-colors shadow-lg shadow-primary/10">
                <span class="material-symbols-outlined text-[20px]">add</span>
                Tambah UMKM
            </button>
        </div>
    </div>


    <div class="flex-1 px-container-pad-mobile lg:px-container-pad-desktop pt-10 pb-section-v-desktop">
        <div class="flex flex-col gap-6">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="bg-surface-2 rounded-2xl p-5 border border-line flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-primary text-[22px]">storefront</span></div>
                    <div><div class="font-label-mono text-ink-dim uppercase text-[10px] mb-0.5">Total UMKM</div><div class="font-h2 text-h2 text-ink leading-none" id="stat-total">—</div></div>
                </div>
                <div class="bg-surface-2 rounded-2xl p-5 border border-line flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-primary text-[22px]">check_circle</span></div>
                    <div><div class="font-label-mono text-ink-dim uppercase text-[10px] mb-0.5">Status Aktif</div><div class="font-h2 text-h2 text-ink leading-none" id="stat-aktif">—</div></div>
                </div>
                <div class="bg-surface-2 rounded-2xl p-5 border border-line flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-surface-container border border-line flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-ink-dim text-[22px]">cancel</span></div>
                    <div><div class="font-label-mono text-ink-dim uppercase text-[10px] mb-0.5">Nonaktif</div><div class="font-h2 text-h2 text-ink-dim leading-none" id="stat-nonaktif">—</div></div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <section class="lg:col-span-2 bg-surface rounded-2xl border border-line p-5 md:p-6" aria-labelledby="umkm-category-chart-title">
                    <div class="flex items-start justify-between gap-4 mb-2">
                        <div><h2 id="umkm-category-chart-title" class="font-h3 text-h3 text-ink">Sebaran Kategori</h2><p class="text-sm text-ink-dim mt-1">Jumlah UMKM pada setiap kategori.</p></div>
                        <span class="font-label-mono text-[10px] uppercase tracking-widest text-gold-soft">Data Live</span>
                    </div>
                    <div id="umkm-category-chart" class="min-h-[280px]"></div>
                </section>
                <section class="bg-surface rounded-2xl border border-line p-5 md:p-6" aria-labelledby="umkm-status-chart-title">
                    <div><h2 id="umkm-status-chart-title" class="font-h3 text-h3 text-ink">Status Publikasi</h2><p class="text-sm text-ink-dim mt-1">Perbandingan data aktif dan nonaktif.</p></div>
                    <div id="umkm-status-chart" class="min-h-[280px]"></div>
                </section>
            </div>

            <div class="flex flex-col md:flex-row items-center justify-between gap-4 bg-surface p-4 rounded-xl border border-line">
                <div class="relative w-full md:w-96">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="material-symbols-outlined text-ink-dim text-[20px]">search</span>
                    </div>
                    <input id="search-umkm" class="w-full bg-surface-container-high border-none text-ink text-body-md font-body-md rounded-lg pl-10 pr-4 py-2.5 focus:ring-1 focus:ring-primary focus:outline-none placeholder:text-ink-dim/50" placeholder="Cari nama usaha atau pemilik..." type="text"/>
                </div>
                <div class="flex items-center gap-2 w-full md:w-auto overflow-x-auto pb-1 md:pb-0" id="filter-umkm">
                    <button type="button" data-kat="all" class="kat-btn px-4 py-2 rounded-full bg-surface-2 text-ink border border-line-strong font-label-mono text-label-mono whitespace-nowrap">Semua</button>
                    <?php foreach ($kategori as $key => $label): ?>
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

            <div class="bg-surface rounded-2xl border border-line overflow-hidden flex flex-col shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[800px]">
                        <thead>
                            <tr class="border-b border-line-strong bg-surface-container/50">
                                <th class="py-4 px-6 font-label-mono text-label-mono text-ink-dim uppercase w-16">No</th>
                                <th class="py-4 px-6 font-label-mono text-label-mono text-ink-dim uppercase">Nama Produk</th>
                                <th class="py-4 px-6 font-label-mono text-label-mono text-ink-dim uppercase w-40">Kategori</th>
                                <th class="py-4 px-6 font-label-mono text-label-mono text-ink-dim uppercase w-40">Dusun</th>
                                <th class="py-4 px-6 font-label-mono text-label-mono text-ink-dim uppercase w-28">Status</th>
                                <th class="py-4 px-6 font-label-mono text-label-mono text-ink-dim uppercase w-24 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="umkm-tbody" class="font-body-md text-body-md text-ink">
                            <tr>
                                <td colspan="6" class="py-12 text-center text-ink-dim">
                                    <span class="inline-block w-6 h-6 border-2 border-primary border-t-transparent rounded-full animate-spin"></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="flex items-center justify-between px-6 py-4 border-t border-line bg-surface-container/30">
                    <span id="umkm-meta" class="font-label-mono text-label-mono text-ink-dim text-xs">—</span>
                    <div class="flex gap-2">
                        <button type="button" id="btn-prev" disabled class="px-4 py-2 rounded-full border border-line text-ink-dim font-label-mono text-xs uppercase disabled:opacity-40">Prev</button>
                        <button type="button" id="btn-next" disabled class="px-4 py-2 rounded-full border border-line text-ink-dim font-label-mono text-xs uppercase disabled:opacity-40">Next</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal form -->
<div id="modal-umkm" data-modal class="hidden fixed inset-0 z-[110] items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60" id="modal-umkm-backdrop"></div>
    <div class="relative w-full max-w-2xl bg-surface border border-line rounded-2xl shadow-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-line sticky top-0 bg-surface z-10">
            <h2 id="modal-umkm-title" class="font-h3 text-h3 text-ink">Tambah UMKM</h2>
            <button type="button" id="modal-umkm-close" class="w-9 h-9 rounded-full hover:bg-surface-2 flex items-center justify-center text-ink-dim hover:text-ink" aria-label="Tutup">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form id="form-umkm" class="p-6 flex flex-col gap-4">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>">
            <input type="hidden" name="id" id="umkm-id" value="">

            <label class="flex flex-col gap-1.5">
                <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest text-[10px]">Nama Produk *</span>
                <input name="nama" id="umkm-nama" required type="text" class="bg-surface-2 border border-line-strong rounded-xl p-3 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary" placeholder="Kopi Bubuk Robusta...">
            </label>
            <label class="flex flex-col gap-1.5">
                <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest text-[10px]">Nama Usaha</span>
                <input name="usaha" id="umkm-usaha" type="text" class="bg-surface-2 border border-line-strong rounded-xl p-3 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary" placeholder="Kopi Naningan Jaya Raya">
            </label>
            <div class="grid grid-cols-2 gap-3">
                <label class="flex flex-col gap-1.5">
                    <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest text-[10px]">Kategori</span>
                    <select name="kategori" id="umkm-kategori" class="bg-surface-2 border border-line-strong rounded-xl p-3 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary">
                        <?php foreach ($kategori as $key => $label): ?>
                        <option value="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label class="flex flex-col gap-1.5">
                    <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest text-[10px]">Status</span>
                    <select name="status" id="umkm-status" class="bg-surface-2 border border-line-strong rounded-xl p-3 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </label>
            </div>
            <label class="flex flex-col gap-1.5">
                <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest text-[10px]">Deskripsi</span>
                <textarea name="deskripsi" id="umkm-deskripsi" rows="3" class="bg-surface-2 border border-line-strong rounded-xl p-3 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary resize-y" placeholder="Deskripsi produk..."></textarea>
            </label>
            <div class="grid grid-cols-2 gap-3">
                <label class="flex flex-col gap-1.5">
                    <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest text-[10px]">Pemilik</span>
                    <input name="pemilik" id="umkm-pemilik" type="text" class="bg-surface-2 border border-line-strong rounded-xl p-3 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary" placeholder="Bpk. Suherman">
                </label>
                <label class="flex flex-col gap-1.5">
                    <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest text-[10px]">Dusun</span>
                    <input name="dusun" id="umkm-dusun" type="text" class="bg-surface-2 border border-line-strong rounded-xl p-3 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary" placeholder="Dusun Sinar Jaya">
                </label>
            </div>
            <label class="flex flex-col gap-1.5">
                <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest text-[10px]">No. WhatsApp (62...)</span>
                <input name="no_wa" id="umkm-wa" type="text" class="bg-surface-2 border border-line-strong rounded-xl p-3 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary" placeholder="6281234567890">
            </label>
            <!-- Foto Upload -->
            <div class="flex flex-col gap-1.5">
                <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest text-[10px]">Foto</span>
                <!-- Preview foto lama (mode edit) -->
                <div id="umkm-foto-preview" class="hidden items-center gap-3 p-3 bg-surface-container-high rounded-xl border border-line">
                    <button type="button" class="w-14 h-14 rounded-lg overflow-hidden shrink-0 border border-line-strong bg-surface-container cursor-zoom-in" data-photo-preview aria-label="Perbesar foto saat ini">
                        <img id="umkm-foto-preview-img" src="" alt="Foto saat ini" class="w-full h-full object-cover">
                    </button>
                    <div class="flex flex-col gap-0.5 min-w-0">
                        <span class="font-label-mono text-[10px] text-ink-dim uppercase tracking-wider">Foto saat ini</span>
                        <p class="text-[11px] text-ink-dim/70 truncate" id="umkm-foto-preview-url"></p>
                        <span class="text-[11px] text-ink-dim/50">Unggah file baru untuk menggantinya</span>
                    </div>
                </div>
                <!-- File picker -->
                <label for="umkm-foto-file" class="cursor-pointer flex items-center gap-3 p-3 bg-surface-2 border-2 border-dashed border-line-strong rounded-xl hover:border-primary transition-colors group">
                    <div class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center shrink-0 group-hover:bg-primary/10">
                        <span class="material-symbols-outlined text-ink-dim group-hover:text-primary text-[20px]">upload</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <span class="block font-body-md text-sm text-ink" id="umkm-foto-label">Klik untuk pilih foto</span>
                        <span class="block text-[11px] text-ink-dim mt-0.5">Maks 2MB &middot; JPG, PNG, GIF, WebP</span>
                    </div>
                </label>
                <input type="file" name="foto_file" id="umkm-foto-file" accept="image/jpeg,image/png,image/gif,image/webp" class="sr-only">
                <!-- Preview file baru -->
                <div id="umkm-foto-new-preview" class="hidden flex-col gap-2">
                    <button type="button" class="w-full rounded-xl overflow-hidden border border-line-strong cursor-zoom-in" data-photo-preview aria-label="Perbesar foto yang dipilih">
                        <img id="umkm-foto-new-img" src="" alt="Preview foto yang dipilih" class="w-full max-h-40 object-cover">
                    </button>
                    <button type="button" id="umkm-foto-clear" class="self-start flex items-center gap-1 text-[11px] text-ink-dim hover:text-danger font-label-mono">
                        <span class="material-symbols-outlined text-[14px]">close</span> Hapus pilihan
                    </button>
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-2 border-t border-line mt-2">
                <button type="button" id="modal-umkm-batal" class="px-5 py-2.5 rounded-full font-label-mono text-[11px] uppercase tracking-wider text-ink bg-surface-2 hover:bg-surface-container-highest">Batal</button>
                <button type="submit" id="btn-simpan-umkm" class="px-6 py-2.5 rounded-full font-label-mono text-[11px] uppercase tracking-wider bg-primary text-on-primary hover:bg-primary-fixed flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">save</span> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal preview foto -->
<div id="modal-preview-foto-umkm" data-modal class="hidden fixed inset-0 z-[140] items-center justify-center p-4 md:p-8" role="dialog" aria-modal="true" aria-label="Preview foto UMKM">
    <div class="absolute inset-0 bg-black/80" id="modal-preview-foto-umkm-backdrop"></div>
    <div class="relative flex h-[88vh] w-[94vw] max-w-[1400px] items-center justify-center">
        <img id="modal-preview-foto-umkm-img" src="" alt="Preview foto UMKM" class="h-full w-full rounded-2xl border border-line bg-black/20 object-contain shadow-2xl">
        <button type="button" id="modal-preview-foto-umkm-close" class="absolute -right-2 -top-2 flex h-11 w-11 items-center justify-center rounded-full border border-line bg-surface text-ink shadow-lg hover:bg-surface-2" aria-label="Tutup preview foto">
            <span class="material-symbols-outlined">close</span>
        </button>
    </div>
</div>

<!-- Modal hapus -->
<div id="modal-hapus" data-modal class="hidden fixed inset-0 z-[110] items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60" id="modal-hapus-backdrop"></div>
    <div class="relative w-full max-w-sm bg-surface border border-line rounded-2xl shadow-2xl p-6 flex flex-col gap-4">
        <h2 class="font-h3 text-h3 text-ink">Hapus UMKM?</h2>
        <p class="font-body-md text-ink-dim text-sm">Yakin hapus <strong id="hapus-nama" class="text-ink"></strong>? Tindakan ini tidak bisa dibatalkan.</p>
        <div class="flex justify-end gap-3">
            <button type="button" id="hapus-batal" data-modal-close class="px-5 py-2.5 rounded-full font-label-mono text-[11px] uppercase bg-surface-2 text-ink">Batal</button>
            <button type="button" id="hapus-ya" class="px-5 py-2.5 rounded-full font-label-mono text-[11px] uppercase bg-danger text-white">Hapus</button>
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

    const tbody = document.getElementById('umkm-tbody');
    const meta = document.getElementById('umkm-meta');
    const btnPrev = document.getElementById('btn-prev');
    const btnNext = document.getElementById('btn-next');
    const modal = document.getElementById('modal-umkm');
    const modalHapus = document.getElementById('modal-hapus');
    const photoModal = document.getElementById('modal-preview-foto-umkm');
    const photoModalImg = document.getElementById('modal-preview-foto-umkm-img');
    const form = document.getElementById('form-umkm');
    let categoryChart = null, statusChart = null;

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
        return base + '/' + value.replace(/^\/+/, '');
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
            categoryChart = new ApexCharts(document.querySelector('#umkm-category-chart'), {
                chart: { type: 'bar', height: 280, toolbar: { show: false }, background: 'transparent', fontFamily: 'Public Sans, sans-serif' },
                series: [{ name: 'UMKM', data: categoryValues }],
                colors: [colors.primary],
                plotOptions: { bar: { borderRadius: 6, columnWidth: '42%', distributed: true } },
                dataLabels: { enabled: false }, legend: { show: false },
                xaxis: { categories, labels: { style: { colors: colors.inkDim, fontSize: '11px' } }, axisBorder: { color: colors.line }, axisTicks: { color: colors.line } },
                yaxis: { min: 0, forceNiceScale: true, labels: { style: { colors: colors.inkDim }, formatter: value => Number.isInteger(value) ? value : '' } },
                grid: { borderColor: colors.line, strokeDashArray: 4 }, tooltip: { theme: 'dark' }
            });
            categoryChart.render();
        } else {
            categoryChart.updateOptions({ xaxis: { categories } });
            categoryChart.updateSeries([{ name: 'UMKM', data: categoryValues }]);
        }
        const statusValues = [Number(stats.stat_aktif || 0), Number(stats.stat_nonaktif || 0)];
        if (!statusChart) {
            statusChart = new ApexCharts(document.querySelector('#umkm-status-chart'), {
                chart: { type: 'donut', height: 280, background: 'transparent', fontFamily: 'Public Sans, sans-serif' },
                series: statusValues, labels: ['Aktif', 'Nonaktif'], colors: [colors.primary, colors.tertiary],
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

    async function loadList() {
        tbody.innerHTML = '<tr><td colspan="6" class="py-12 text-center"><span class="inline-block w-6 h-6 border-2 border-primary border-t-transparent rounded-full animate-spin"></span></td></tr>';
        const fd = new FormData();
        fd.append('page', page);
        fd.append('search', search);
        fd.append('kategori', kategori);
        try {
            const res = await fetch(base + '/admin/ajax/list-umkm', { method: 'POST', body: fd, credentials: 'same-origin' });
            const json = await res.json();
            if (!json.success) { toast(json.message || 'Gagal memuat.', false); return; }
            hasNext = json.has_next; hasPrev = json.has_prev;
            btnPrev.disabled = !hasPrev; btnNext.disabled = !hasNext;
            meta.textContent = json.total + ' data · halaman ' + json.page;
            document.getElementById('stat-total').textContent = json.total;
            document.getElementById('stat-aktif').textContent = json.stat_aktif;
            document.getElementById('stat-nonaktif').textContent = json.stat_nonaktif;
            updateCharts(json);
            if (!json.data.length) {
                tbody.innerHTML = '<tr><td colspan="6" class="py-12 text-center text-ink-dim">Belum ada data UMKM.</td></tr>';
                return;
            }
            const start = (json.page - 1) * 10;
            tbody.innerHTML = json.data.map((row, i) => {
                const no = String(start + i + 1).padStart(2, '0');
                const aktif = (row.status || 'aktif') === 'aktif';
                return `<tr class="border-b border-line/50 hover:bg-surface-2 transition-colors group">
                    <td class="py-4 px-6 text-ink-dim font-label-mono text-label-mono">${no}</td>
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-3">
                            ${row.foto ? `<button type="button" data-photo-src="${esc(mediaUrl(row.foto))}" class="w-10 h-10 rounded-lg bg-surface-container-high overflow-hidden shrink-0 border border-line cursor-zoom-in" aria-label="Perbesar foto ${esc(row.nama)}"><img src="${esc(mediaUrl(row.foto))}" alt="Foto ${esc(row.nama)}" class="w-full h-full object-cover"></button>` : `<div class="w-10 h-10 rounded-lg bg-surface-container-high overflow-hidden shrink-0 border border-line"><span class="material-symbols-outlined text-gold-soft text-[20px] flex items-center justify-center w-full h-full">storefront</span></div>`}
                            <div>
                                <div class="font-medium text-ink">${esc(row.nama)}</div>
                                <div class="text-[13px] text-ink-dim mt-0.5">${esc(row.pemilik || row.usaha || '—')}</div>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-6"><span class="inline-flex px-2.5 py-1 rounded-md bg-surface-container-high text-gold-soft text-[12px] font-medium border border-gold-soft/20">${esc(row.kategori_label || row.kategori)}</span></td>
                    <td class="py-4 px-6 text-ink-dim">${esc(row.dusun || '—')}</td>
                    <td class="py-4 px-6">
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[12px] font-medium ${aktif ? 'bg-primary/10 text-primary border border-primary/20' : 'bg-surface-container text-ink-dim border border-line'}">
                            <div class="w-1.5 h-1.5 rounded-full ${aktif ? 'bg-primary' : 'bg-ink-dim'}"></div>${aktif ? 'Aktif' : 'Nonaktif'}
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
            toast('Gagal terhubung ke server atau terjadi kesalahan internal. Periksa koneksi internet Anda dan coba lagi.', false);
            tbody.innerHTML = '<tr><td colspan="6" class="py-12 text-center text-danger">Error memuat data.</td></tr>';
        }
    }

    function openModal(title) {
        document.getElementById('modal-umkm-title').textContent = title;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        form.reset();
        document.getElementById('umkm-id').value = '';
        const fi = document.getElementById('umkm-foto-file');
        if (fi) fi.value = '';
        document.getElementById('umkm-foto-label').textContent = 'Klik untuk pilih foto';
        document.getElementById('umkm-foto-preview')?.classList.replace('flex', 'hidden');
        document.getElementById('umkm-foto-preview')?.classList.add('hidden');
        document.getElementById('umkm-foto-new-preview')?.classList.replace('flex', 'hidden');
        document.getElementById('umkm-foto-new-preview')?.classList.add('hidden');
    }
    function closeHapus() { modalHapus.classList.add('hidden'); modalHapus.classList.remove('flex'); deleteId = null; }

    document.getElementById('btn-tambah-umkm')?.addEventListener('click', () => {
        form.reset();
        document.getElementById('umkm-id').value = '';
        openModal('Tambah UMKM');
    });
    document.getElementById('modal-umkm-close')?.addEventListener('click', closeModal);
    document.getElementById('modal-umkm-batal')?.addEventListener('click', closeModal);
    document.getElementById('modal-umkm-backdrop')?.addEventListener('click', closeModal);
    document.getElementById('hapus-batal')?.addEventListener('click', closeHapus);
    document.getElementById('modal-hapus-backdrop')?.addEventListener('click', closeHapus);
    document.getElementById('modal-preview-foto-umkm-close')?.addEventListener('click', closePhotoPreview);
    document.getElementById('modal-preview-foto-umkm-backdrop')?.addEventListener('click', closePhotoPreview);
    document.addEventListener('click', (e) => {
        const trigger = e.target.closest('[data-photo-preview]');
        if (!trigger) return;
        openPhotoPreview(trigger.querySelector('img')?.src || '');
    });

    tbody?.addEventListener('click', async (e) => {
        const photoBtn = e.target.closest('[data-photo-src]');
        const editBtn = e.target.closest('[data-edit]');
        const delBtn = e.target.closest('[data-del]');
        if (photoBtn) {
            openPhotoPreview(photoBtn.dataset.photoSrc);
            return;
        }
        if (editBtn) {
            const id = editBtn.dataset.edit;
            const fd = new FormData();
            fd.append('id', id);
            try {
                const res = await fetch(base + '/admin/ajax/get-umkm', { method: 'POST', body: fd, credentials: 'same-origin' });
                if (res.status === 401) {
                    toast('Sesi habis. Mengalihkan ke halaman login…', false);
                    setTimeout(() => { window.location.href = base + '/admin/login'; }, 1800);
                    return;
                }
                const json = await res.json();
                if (!json.success) { toast(json.message || 'Gagal memuat data.', false); return; }
                const d = json.data;
                document.getElementById('umkm-id').value = d.id || '';
                document.getElementById('umkm-nama').value = d.nama || '';
                document.getElementById('umkm-usaha').value = d.usaha || '';
                document.getElementById('umkm-kategori').value = d.kategori || 'kopi';
                document.getElementById('umkm-status').value = d.status || 'aktif';
                document.getElementById('umkm-deskripsi').value = d.deskripsi || '';
                document.getElementById('umkm-pemilik').value = d.pemilik || '';
                document.getElementById('umkm-dusun').value = d.dusun || '';
                document.getElementById('umkm-wa').value = d.no_wa || '';
                // Reset file input — foto lama ditampilkan di preview di bawah
                const fi = document.getElementById('umkm-foto-file');
                if (fi) fi.value = '';
                document.getElementById('umkm-foto-label').textContent = 'Klik untuk pilih foto';
                document.getElementById('umkm-foto-new-preview')?.classList.replace('flex', 'hidden');
                document.getElementById('umkm-foto-new-preview')?.classList.add('hidden');
                // Preview foto lama
                const previewBox = document.getElementById('umkm-foto-preview');
                const previewImg = document.getElementById('umkm-foto-preview-img');
                const previewUrl = document.getElementById('umkm-foto-preview-url');
                if (d.foto) {
                    previewImg.src = mediaUrl(d.foto);
                    previewUrl.textContent = d.foto;
                    previewBox.classList.remove('hidden');
                    previewBox.classList.add('flex');
                } else {
                    previewBox.classList.add('hidden');
                    previewBox.classList.remove('flex');
                }
                openModal('Edit UMKM');
            } catch (err) { console.error('get-umkm error:', err); toast('Gagal memuat data.', false); }
        }
        if (delBtn) {
            deleteId = delBtn.dataset.del;
            document.getElementById('hapus-nama').textContent = delBtn.dataset.nama || '';
            modalHapus.classList.remove('hidden');
            modalHapus.classList.add('flex');
        }
    });

    document.getElementById('hapus-ya')?.addEventListener('click', async () => {
        if (!deleteId) return;
        const fd = new FormData();
        fd.append('csrf_token', csrf);
        fd.append('id', deleteId);
        try {
            const res = await fetch(base + '/admin/ajax/delete-umkm', { method: 'POST', body: fd, credentials: 'same-origin' });
            const json = await res.json();
            toast(json.message || (json.success ? 'Dihapus.' : 'Gagal.'), !!json.success);
            if (json.success) { closeHapus(); loadList(); }
        } catch (err) { toast('Gagal terhubung ke server atau terjadi kesalahan internal. Periksa koneksi internet Anda dan coba lagi.', false); }
    });

    form?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const btn = document.getElementById('btn-simpan-umkm');
        const wa = (document.getElementById('umkm-wa').value || '').replace(/\D+/g, '');
        if (wa !== '' && !/^62\d{9,13}$/.test(wa)) {
            toast('Format No. WhatsApp tidak valid. Nomor harus diawali 62 dan terdiri dari 11-15 digit.', false);
            return;
        }
        btn.disabled = true;
        try {
            const fd = new FormData(form);
            const res = await fetch(base + '/admin/ajax/store-umkm', { method: 'POST', body: fd, credentials: 'same-origin' });
            const json = await res.json();
            toast(json.message || (json.success ? 'Tersimpan.' : 'Gagal.'), !!json.success);
            if (json.success) { closeModal(); loadList(); }
        } catch (err) { toast('Gagal terhubung ke server atau terjadi kesalahan internal. Periksa koneksi internet Anda dan coba lagi.', false); }
        finally { btn.disabled = false; }
    });

    document.getElementById('search-umkm')?.addEventListener('input', (e) => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => { search = e.target.value.trim(); page = 1; loadList(); }, 300);
    });

    document.getElementById('filter-umkm')?.addEventListener('click', (e) => {
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
        document.getElementById('search-umkm').value = '';
        search = '';
        document.querySelector('#filter-umkm .kat-btn[data-kat="all"]')?.click();
    });

    btnPrev?.addEventListener('click', () => { if (hasPrev) { page--; loadList(); } });
    btnNext?.addEventListener('click', () => { if (hasNext) { page++; loadList(); } });

    /* ── File input foto ── */
    document.getElementById('umkm-foto-file')?.addEventListener('change', (e) => {
        const file       = e.target.files[0];
        const label      = document.getElementById('umkm-foto-label');
        const newPreview = document.getElementById('umkm-foto-new-preview');
        const newImg     = document.getElementById('umkm-foto-new-img');
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
    document.getElementById('umkm-foto-clear')?.addEventListener('click', () => {
        const fi = document.getElementById('umkm-foto-file');
        if (fi) fi.value = '';
        document.getElementById('umkm-foto-label').textContent = 'Klik untuk pilih foto';
        const np = document.getElementById('umkm-foto-new-preview');
        np?.classList.add('hidden');
        np?.classList.remove('flex');
    });

    loadList();
})();
</script>
<?php require __DIR__ . '/../partials/footer.php'; ?>
