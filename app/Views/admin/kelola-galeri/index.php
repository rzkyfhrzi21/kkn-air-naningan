<?php
/* ======================================================
   HALAMAN KELOLA GALERI (FOTO & VIDEO DESA)

   Halaman ini adalah "album foto & video" panel admin.
   Dari sini admin bisa:
   - Melihat daftar media galeri foto dan video,
   - Mengunggah foto baru (maks 2MB, JPG/PNG/GIF/WebP) atau video baru (maks 15MB, MP4/WebM/MOV),
   - Menghapus media galeri,
   - Memutar video atau memperbesar foto lewat modal preview.

   Tabel data galeri diisi secara interaktif oleh JavaScript via AJAX (list-galeri.php).
====================================================== */

$pageTitle = 'Kelola Galeri';
$activeNav = 'kelola-galeri';
require __DIR__ . '/../partials/header.php';

$csrf     = (string) ($_SESSION['csrf_token'] ?? '');
$base     = defined('APP_BASE') ? APP_BASE : '';
$kategori = $kategori ?? Galeri::KATEGORI;
?>

<div class="flex flex-col w-full px-container-pad-mobile lg:px-container-pad-desktop pb-section-v-desktop gap-10">


    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6 pt-10">
        <div class="flex flex-col gap-2 max-w-2xl">
            <h1 class="font-h1-mobile lg:font-h1 text-h1-mobile lg:text-h1 text-ink">Kelola Galeri</h1>
            <p class="font-body-lg text-body-lg text-ink-dim">Kelola koleksi foto dan video yang menampilkan keindahan, budaya, dan aktivitas Pekon Air Naningan.</p>
        </div>
        <div class="flex items-center gap-3">
            <button type="button" id="btn-delete-selected" disabled class="px-6 py-3 rounded-full bg-surface-2 text-ink border border-line flex items-center gap-2 hover:bg-surface-container-high transition-colors disabled:opacity-40">
                <span class="material-symbols-outlined text-[20px]">delete</span>
                <span class="font-label-mono text-label-mono uppercase tracking-widest" id="btn-delete-label">Hapus Dipilih (0)</span>
            </button>
            <button type="button" id="btn-create" class="px-6 py-3 rounded-full bg-primary text-on-primary font-label-mono text-label-mono uppercase tracking-widest hover:bg-primary-fixed transition-colors shadow-lg shadow-primary/20">
                Tambah Media
            </button>
        </div>
    </div>

    <!-- Upload + Filter (sejajar horizontal) -->
    <div class="flex flex-col lg:flex-row gap-6 items-stretch">

        <!-- Upload Card -->
        <div class="w-full lg:w-[380px] shrink-0 bg-surface-container rounded-2xl p-6 border border-line flex flex-col gap-4">
            <div class="flex items-center gap-3 text-gold-soft">
                <span class="material-symbols-outlined">cloud_upload</span>
                <h3 class="font-h3 text-h3">Upload Aset</h3>
            </div>
            <div id="drop-zone" class="border-2 border-dashed border-line-strong rounded-xl p-8 flex flex-col items-center justify-center text-center gap-4 hover:border-primary/50 transition-colors cursor-pointer bg-surface/50 group flex-1" role="button" tabindex="0" aria-label="Pilih file media galeri">
                <div class="w-16 h-16 rounded-full bg-surface-2 flex items-center justify-center text-ink-dim group-hover:text-primary transition-colors group-hover:scale-110 duration-300">
                    <span class="material-symbols-outlined text-3xl">add_photo_alternate</span>
                </div>
                <div class="flex flex-col gap-1">
                    <span class="font-body-md text-body-md text-ink">Drag &amp; drop media di sini</span>
                    <span class="font-label-mono text-label-mono text-ink-dim">Foto maks 2MB · Video maks 15MB</span>
                    <span class="font-label-mono text-[10px] text-ink-dim/70">JPG, PNG, GIF, WebP · MP4, MOV, WebM</span>
                </div>
                <span class="px-4 py-2 mt-2 rounded-full bg-surface-2 text-ink border border-line group-hover:border-primary transition-colors font-label-mono text-[10px] uppercase tracking-widest">
                    Browse Files
                </span>
            </div>
            <input type="file" id="quick-upload-file" class="sr-only" accept="image/jpeg,image/png,image/gif,image/webp,video/mp4,video/webm,video/quicktime">
        </div>

        <!-- Filters Card (kanan, meregang) -->
        <div class="flex-1 min-w-0 bg-surface-container rounded-2xl p-6 border border-line flex flex-col gap-5">
            <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-3 text-gold-soft">
                <span class="material-symbols-outlined">filter_list</span>
                <h3 class="font-h3 text-h3">Filter</h3>
                <button type="button" id="btn-reset-filter" class="flex items-center gap-1.5 px-3 py-2 rounded-lg bg-surface border border-line text-ink-dim hover:text-ink hover:border-line-strong font-label-mono text-[10px] uppercase tracking-wider transition-colors" title="Reset semua filter">
                    <span class="material-symbols-outlined text-[15px]">refresh</span> Reset
                </button>
            </div>
                <div class="flex items-center gap-4 shrink-0">
                    <div class="text-right">
                        <div class="font-label-mono text-[10px] uppercase tracking-wider text-ink-dim">Total</div>
                        <div class="font-body-md text-ink font-semibold leading-none mt-1" id="stat-total">—</div>
                    </div>
                    <div class="w-px h-8 bg-line"></div>
                    <div class="text-right">
                        <div class="font-label-mono text-[10px] uppercase tracking-wider text-ink-dim">Foto</div>
                        <div class="font-body-md text-ink font-semibold leading-none mt-1" id="stat-foto">—</div>
                    </div>
                    <div class="text-right">
                        <div class="font-label-mono text-[10px] uppercase tracking-wider text-ink-dim">Video</div>
                        <div class="font-body-md text-ink font-semibold leading-none mt-1" id="stat-video">—</div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-2">
                <label class="font-body-md text-body-md text-ink-dim" for="search-galeri">Cari</label>
                <input id="search-galeri" type="text" class="w-full bg-surface border border-line rounded-lg px-4 py-3 text-ink font-body-md focus:outline-none focus:border-primary" placeholder="Cari judul atau deskripsi...">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="flex flex-col gap-2">
                    <label class="font-body-md text-body-md text-ink-dim" for="filter-kategori">Kategori</label>
                    <select id="filter-kategori" class="w-full bg-surface border border-line rounded-lg px-4 py-3 text-ink font-body-md focus:outline-none focus:border-primary appearance-none">
                        <option value="">Semua Kategori</option>
                        <?php foreach ($kategori as $key => $label): ?>
                        <option value="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex flex-col gap-2">
                    <label class="font-body-md text-body-md text-ink-dim">Urutkan</label>
                    <div class="flex gap-2">
                        <button type="button" class="sort-btn flex-1 py-3 rounded-lg bg-surface-2 text-ink border border-primary text-sm font-label-mono" data-sort="newest">Terbaru</button>
                        <button type="button" class="sort-btn flex-1 py-3 rounded-lg bg-surface text-ink-dim border border-line hover:bg-surface-2 text-sm font-label-mono transition-colors" data-sort="oldest">Terlama</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gallery Grid -->
    <div class="flex flex-col gap-4">
        <div id="gallery-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 auto-rows-max">
            <div class="col-span-full py-16 text-center text-ink-dim">
                <span class="inline-block w-6 h-6 border-2 border-primary border-t-transparent rounded-full animate-spin"></span>
            </div>
        </div>
        <div class="flex items-center justify-between px-1">
            <span id="galeri-meta" class="font-label-mono text-label-mono text-ink-dim text-xs">—</span>
            <div class="flex gap-2">
                <button type="button" id="btn-prev" disabled class="px-4 py-2 rounded-full border border-line text-ink-dim font-label-mono text-xs uppercase disabled:opacity-40">Prev</button>
                <button type="button" id="btn-next" disabled class="px-4 py-2 rounded-full border border-line text-ink-dim font-label-mono text-xs uppercase disabled:opacity-40">Next</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Form -->
<div id="modal-galeri" data-modal class="hidden fixed inset-0 z-[110] items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60" id="modal-galeri-backdrop"></div>
    <div class="relative w-full max-w-2xl bg-surface border border-line rounded-2xl shadow-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-line sticky top-0 bg-surface z-10">
            <h2 id="modal-galeri-title" class="font-h3 text-h3 text-ink">Tambah Media</h2>
            <button type="button" id="modal-galeri-close" class="w-9 h-9 rounded-full hover:bg-surface-2 flex items-center justify-center text-ink-dim hover:text-ink" aria-label="Tutup">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form id="form-galeri" class="p-6 flex flex-col gap-4" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>">
            <input type="hidden" name="id" id="galeri-id" value="">

            <label class="flex flex-col gap-1.5">
                <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest text-[10px]">Judul *</span>
                <input name="judul" id="galeri-judul" required type="text" class="bg-surface-2 border border-line-strong rounded-xl p-3 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary" placeholder="Lembah Kabut Pagi">
            </label>

            <div class="grid grid-cols-2 gap-3">
                <label class="flex flex-col gap-1.5">
                    <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest text-[10px]">Kategori *</span>
                    <select name="kategori" id="galeri-kategori" required class="bg-surface-2 border border-line-strong rounded-xl p-3 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary">
                        <?php foreach ($kategori as $key => $label): ?>
                        <option value="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label class="flex flex-col gap-1.5">
                    <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest text-[10px]">Tipe Media *</span>
                    <select name="tipe" id="galeri-tipe" required class="bg-surface-2 border border-line-strong rounded-xl p-3 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary">
                        <option value="foto">Foto</option>
                        <option value="video">Video</option>
                    </select>
                </label>
            </div>

            <label class="flex flex-col gap-1.5">
                <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest text-[10px]">Deskripsi</span>
                <textarea name="deskripsi" id="galeri-deskripsi" rows="3" class="bg-surface-2 border border-line-strong rounded-xl p-3 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary resize-y" placeholder="Deskripsi singkat media..."></textarea>
            </label>

            <div class="grid grid-cols-2 gap-3">
                <label class="flex flex-col gap-1.5">
                    <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest text-[10px]">Rasio (opsional)</span>
                    <input name="rasio" id="galeri-rasio" type="text" class="bg-surface-2 border border-line-strong rounded-xl p-3 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary" placeholder="100%" value="100%">
                </label>
                <label class="flex flex-col gap-1.5">
                    <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest text-[10px]">Urutan</span>
                    <input name="urutan" id="galeri-urutan" type="number" min="0" class="bg-surface-2 border border-line-strong rounded-xl p-3 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary" placeholder="otomatis">
                </label>
            </div>

            <div class="flex flex-col gap-1.5">
                <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest text-[10px]">File Media</span>
                <div id="galeri-media-preview" class="hidden items-center gap-3 p-3 bg-surface-container-high rounded-xl border border-line">
                    <button type="button" class="w-14 h-14 rounded-lg overflow-hidden shrink-0 border border-line-strong bg-surface-container cursor-zoom-in" data-photo-preview aria-label="Perbesar media saat ini">
                        <img id="galeri-media-preview-img" src="" alt="Media saat ini" class="w-full h-full object-cover">
                    </button>
                    <div class="flex flex-col gap-0.5 min-w-0">
                        <span class="font-label-mono text-[10px] text-ink-dim uppercase tracking-wider">Media saat ini</span>
                        <p class="text-[11px] text-ink-dim/70 truncate" id="galeri-media-preview-url"></p>
                        <span class="text-[11px] text-ink-dim/50">Unggah file baru untuk menggantinya</span>
                    </div>
                </div>
                <label for="galeri-media-file" class="cursor-pointer flex items-center gap-3 p-3 bg-surface-2 border-2 border-dashed border-line-strong rounded-xl hover:border-primary transition-colors group">
                    <div class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center shrink-0 group-hover:bg-primary/10">
                        <span class="material-symbols-outlined text-ink-dim group-hover:text-primary text-[20px]">upload</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <span class="block font-body-md text-sm text-ink" id="galeri-media-label">Klik untuk pilih file</span>
                        <span class="block text-[11px] text-ink-dim mt-0.5" id="galeri-media-hint">Foto maks 2MB · Video maks 15MB</span>
                    </div>
                </label>
                <input type="file" name="media_file" id="galeri-media-file" accept="image/jpeg,image/png,image/gif,image/webp,video/mp4,video/webm,video/quicktime" class="sr-only">
                <div id="galeri-media-new-preview" class="hidden flex-col gap-2">
                    <button type="button" class="w-full rounded-xl overflow-hidden border border-line-strong cursor-zoom-in" data-photo-preview aria-label="Perbesar media yang dipilih">
                        <img id="galeri-media-new-img" src="" alt="Preview media yang dipilih" class="w-full max-h-40 object-cover">
                    </button>
                    <video id="galeri-media-new-video" controls class="hidden w-full max-h-40 rounded-xl border border-line-strong bg-black"></video>
                    <button type="button" id="galeri-media-clear" class="self-start flex items-center gap-1 text-[11px] text-ink-dim hover:text-danger font-label-mono">
                        <span class="material-symbols-outlined text-[14px]">close</span> Hapus pilihan
                    </button>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2 border-t border-line mt-2">
                <button type="button" id="modal-galeri-batal" class="px-5 py-2.5 rounded-full font-label-mono text-[11px] uppercase tracking-wider text-ink bg-surface-2 hover:bg-surface-container-highest">Batal</button>
                <button type="submit" id="btn-simpan-galeri" class="px-6 py-2.5 rounded-full font-label-mono text-[11px] uppercase tracking-wider bg-primary text-on-primary hover:bg-primary-fixed flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">save</span> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Hapus -->
<div id="modal-hapus-galeri" data-modal class="hidden fixed inset-0 z-[110] items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60" id="modal-hapus-galeri-backdrop"></div>
    <div class="relative w-full max-w-sm bg-surface border border-line rounded-2xl shadow-2xl p-6 flex flex-col gap-4">
        <h2 class="font-h3 text-h3 text-ink">Hapus Media?</h2>
        <p class="font-body-md text-ink-dim text-sm" id="hapus-galeri-text">Yakin hapus media ini? Tindakan ini tidak bisa dibatalkan.</p>
        <div class="flex justify-end gap-3">
            <button type="button" id="hapus-galeri-batal" data-modal-close class="px-5 py-2.5 rounded-full font-label-mono text-[11px] uppercase bg-surface-2 text-ink">Batal</button>
            <button type="button" id="hapus-galeri-ya" class="px-5 py-2.5 rounded-full font-label-mono text-[11px] uppercase bg-danger text-white">Hapus</button>
        </div>
    </div>
</div>

<!-- Modal Preview -->
<div id="modal-preview-galeri" data-modal class="hidden fixed inset-0 z-[140] items-center justify-center p-4 md:p-8" role="dialog" aria-modal="true" aria-label="Preview media galeri">
    <div class="absolute inset-0 bg-black/80" id="modal-preview-galeri-backdrop"></div>
    <div class="relative flex max-h-full max-w-5xl items-center justify-center">
        <img id="modal-preview-galeri-img" src="" alt="Preview foto galeri" class="max-h-[85vh] max-w-full rounded-2xl border border-line object-contain shadow-2xl">
        <video id="modal-preview-galeri-video" controls class="hidden max-h-[85vh] max-w-full rounded-2xl border border-line bg-black shadow-2xl"></video>
        <button type="button" id="modal-preview-galeri-close" class="absolute -right-2 -top-2 flex h-11 w-11 items-center justify-center rounded-full border border-line bg-surface text-ink shadow-lg hover:bg-surface-2" aria-label="Tutup preview media">
            <span class="material-symbols-outlined">close</span>
        </button>
    </div>
</div>

<script>
(function () {
    const base = <?= json_encode($base, JSON_UNESCAPED_SLASHES) ?>;
    const csrf = <?= json_encode($csrf) ?>;
    let page = 1, search = '', kategori = '', sort = 'newest';
    let hasNext = false, hasPrev = false, searchTimer = null;
    let deleteIds = [], selected = new Set(), pendingFile = null;

    const grid = document.getElementById('gallery-grid');
    const meta = document.getElementById('galeri-meta');
    const btnPrev = document.getElementById('btn-prev');
    const btnNext = document.getElementById('btn-next');
    const modal = document.getElementById('modal-galeri');
    const modalDel = document.getElementById('modal-hapus-galeri');
    const form = document.getElementById('form-galeri');
    const photoModal = document.getElementById('modal-preview-galeri');
    const photoImg = document.getElementById('modal-preview-galeri-img');
    const photoVideo = document.getElementById('modal-preview-galeri-video');
    const btnDeleteSelected = document.getElementById('btn-delete-selected');
    const btnDeleteLabel = document.getElementById('btn-delete-label');

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

    function relativeTime(iso) {
        if (!iso) return '—';
        const ts = Date.parse(iso);
        if (Number.isNaN(ts)) return '—';
        const diff = Math.max(0, Date.now() - ts);
        const m = Math.floor(diff / 60000);
        if (m < 1) return 'Baru saja';
        if (m < 60) return m + ' menit lalu';
        const h = Math.floor(m / 60);
        if (h < 24) return h + ' jam lalu';
        const d = Math.floor(h / 24);
        if (d < 30) return d + ' hari lalu';
        const mo = Math.floor(d / 30);
        if (mo < 12) return mo + ' bulan lalu';
        return Math.floor(mo / 12) + ' tahun lalu';
    }

    function openModal(title) {
        document.getElementById('modal-galeri-title').textContent = title;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        form.reset();
        document.getElementById('galeri-id').value = '';
        pendingFile = null;
        clearMediaInput();
    }
    function openDel(ids, text) {
        deleteIds = ids;
        document.getElementById('hapus-galeri-text').textContent = text;
        modalDel.classList.remove('hidden');
        modalDel.classList.add('flex');
    }
    function closeDel() {
        modalDel.classList.add('hidden');
        modalDel.classList.remove('flex');
        deleteIds = [];
    }

    function openPhotoPreview(src, isVideo) {
        if (!src) return;
        if (isVideo) {
            photoImg.classList.add('hidden');
            photoImg.src = '';
            photoVideo.classList.remove('hidden');
            photoVideo.src = src;
        } else {
            photoVideo.classList.add('hidden');
            photoVideo.pause();
            photoVideo.removeAttribute('src');
            photoImg.classList.remove('hidden');
            photoImg.src = src;
        }
        photoModal.classList.remove('hidden');
        photoModal.classList.add('flex');
    }
    function closePhotoPreview() {
        photoModal.classList.add('hidden');
        photoModal.classList.remove('flex');
        photoImg.src = '';
        photoVideo.pause();
        photoVideo.removeAttribute('src');
        photoVideo.classList.add('hidden');
    }

    function updateSelectedUi() {
        const n = selected.size;
        btnDeleteLabel.textContent = 'Hapus Dipilih (' + n + ')';
        btnDeleteSelected.disabled = n === 0;
    }

    function clearMediaInput() {
        const fi = document.getElementById('galeri-media-file');
        if (fi) fi.value = '';
        document.getElementById('galeri-media-label').textContent = 'Klik untuk pilih file';
        document.getElementById('galeri-media-preview')?.classList.replace('flex', 'hidden');
        document.getElementById('galeri-media-preview')?.classList.add('hidden');
        const np = document.getElementById('galeri-media-new-preview');
        np?.classList.add('hidden');
        np?.classList.remove('flex');
        const img = document.getElementById('galeri-media-new-img');
        const vid = document.getElementById('galeri-media-new-video');
        if (img) { img.src = ''; img.classList.remove('hidden'); }
        if (vid) { vid.pause(); vid.removeAttribute('src'); vid.classList.add('hidden'); }
    }

    function setPendingFile(file) {
        pendingFile = file || null;
        const label = document.getElementById('galeri-media-label');
        const np = document.getElementById('galeri-media-new-preview');
        const img = document.getElementById('galeri-media-new-img');
        const vid = document.getElementById('galeri-media-new-video');
        if (!file) {
            clearMediaInput();
            return;
        }
        label.textContent = file.name;
        const url = URL.createObjectURL(file);
        if (file.type.startsWith('video/')) {
            img.classList.add('hidden');
            img.src = '';
            vid.classList.remove('hidden');
            vid.src = url;
        } else {
            vid.classList.add('hidden');
            vid.pause();
            vid.removeAttribute('src');
            img.classList.remove('hidden');
            img.src = url;
        }
        np.classList.remove('hidden');
        np.classList.add('flex');
        if (file.type.startsWith('video/')) {
            document.getElementById('galeri-tipe').value = 'video';
        } else if (file.type.startsWith('image/')) {
            document.getElementById('galeri-tipe').value = 'foto';
        }
    }

    function validateClientFile(file, tipe) {
        if (!file) return true;
        const isVideo = tipe === 'video' || file.type.startsWith('video/');
        const max = isVideo ? 15 * 1024 * 1024 : 2 * 1024 * 1024;
        if (file.size > max) {
            toast(isVideo ? 'Ukuran video maksimal 15MB.' : 'Ukuran foto maksimal 2MB.', false);
            return false;
        }
        if (isVideo) {
            const ok = ['video/mp4', 'video/webm', 'video/quicktime'].includes(file.type);
            if (!ok) { toast('Format video tidak valid. Gunakan MP4, MOV, atau WebM.', false); return false; }
        } else {
            const ok = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'].includes(file.type);
            if (!ok) { toast('Format foto tidak valid. Gunakan JPG, PNG, GIF, atau WebP.', false); return false; }
        }
        return true;
    }

    async function loadList() {
        grid.innerHTML = '<div class="col-span-full py-16 text-center text-ink-dim"><span class="inline-block w-6 h-6 border-2 border-primary border-t-transparent rounded-full animate-spin"></span></div>';
        const fd = new FormData();
        fd.append('page', page);
        fd.append('search', search);
        fd.append('kategori', kategori);
        fd.append('sort', sort);
        try {
            const res = await fetch(base + '/admin/ajax/list-galeri', { method: 'POST', body: fd, credentials: 'same-origin' });
            const json = await res.json();
            if (!json.success) { toast(json.message || 'Gagal memuat.', false); return; }
            hasNext = json.has_next; hasPrev = json.has_prev;
            btnPrev.disabled = !hasPrev; btnNext.disabled = !hasNext;
            meta.textContent = json.total + ' data · halaman ' + json.page;
            document.getElementById('stat-total').textContent = json.stat_total ?? json.total ?? '—';
            document.getElementById('stat-foto').textContent = json.stat_foto ?? 0;
            document.getElementById('stat-video').textContent = json.stat_video ?? 0;
            selected.clear();
            updateSelectedUi();
            if (!json.data.length) {
                grid.innerHTML = '<div class="col-span-full py-16 text-center text-ink-dim">Belum ada media galeri.</div>';
                return;
            }
            grid.innerHTML = json.data.map(row => {
                const src = mediaUrl(row.file);
                const isVideo = (row.tipe || 'foto') === 'video';
                const checked = selected.has(row.id) ? 'checked' : '';
                return `<div class="group relative bg-surface-container rounded-2xl overflow-hidden border border-line hover:border-primary/50 transition-all shadow-sm hover:shadow-xl hover:-translate-y-1 duration-300" data-id="${esc(row.id)}">
                    <div class="absolute top-3 left-3 z-10">
                        <input type="checkbox" data-check="${esc(row.id)}" ${checked} class="w-5 h-5 rounded border-line-strong bg-surface/80 cursor-pointer accent-primary backdrop-blur-sm"/>
                    </div>
                    <div class="absolute top-3 right-3 z-10 bg-surface/80 backdrop-blur-md px-3 py-1 rounded-full border border-line">
                        <span class="font-label-mono text-[10px] text-gold-soft uppercase tracking-wider">${esc(row.kategori_label || row.kategori || '—')}</span>
                    </div>
                    <button type="button" data-preview="${esc(src)}" data-preview-type="${isVideo ? 'video' : 'foto'}" class="w-full aspect-[4/3] bg-surface-2 relative overflow-hidden block text-left cursor-zoom-in" aria-label="Perbesar ${esc(row.judul)}">
                        ${isVideo
                            ? `<video src="${esc(src)}" class="absolute inset-0 w-full h-full object-cover" muted preload="metadata"></video><span class="absolute inset-0 flex items-center justify-center"><span class="w-12 h-12 rounded-full bg-black/50 border border-line flex items-center justify-center text-ink"><span class="material-symbols-outlined">play_arrow</span></span></span>`
                            : `<div class="absolute inset-0 bg-cover bg-center group-hover:scale-105 transition-transform duration-700" style="background-image: url('${esc(src)}')"></div>`}
                        <div class="absolute inset-0 bg-gradient-to-t from-background/90 via-background/20 to-transparent opacity-60"></div>
                    </button>
                    <div class="p-5 flex flex-col gap-3">
                        <div class="flex flex-col gap-1">
                            <span class="text-ink font-body-lg line-clamp-1">${esc(row.judul)}</span>
                            <span class="font-label-mono text-[10px] text-ink-dim">Ditambah ${esc(relativeTime(row.created_at))}</span>
                        </div>
                        <div class="flex items-center justify-end gap-1">
                            <button type="button" data-edit="${esc(row.id)}" class="w-8 h-8 rounded-full hover:bg-surface-container-highest flex items-center justify-center text-ink-dim hover:text-ink" title="Edit"><span class="material-symbols-outlined text-[18px]">edit</span></button>
                            <button type="button" data-del="${esc(row.id)}" data-nama="${esc(row.judul)}" class="w-8 h-8 rounded-full hover:bg-surface-container-highest flex items-center justify-center text-ink-dim hover:text-danger" title="Hapus"><span class="material-symbols-outlined text-[18px]">delete</span></button>
                        </div>
                    </div>
                </div>`;
            }).join('');
        } catch (e) {
            toast('Gagal terhubung ke server atau terjadi kesalahan internal. Periksa koneksi internet Anda dan coba lagi.', false);
            grid.innerHTML = '<div class="col-span-full py-16 text-center text-danger">Error memuat data.</div>';
        }
    }

    function openCreate(prefillFile) {
        form.reset();
        document.getElementById('galeri-id').value = '';
        document.getElementById('galeri-rasio').value = '100%';
        clearMediaInput();
        if (prefillFile) setPendingFile(prefillFile);
        openModal('Tambah Media');
    }

    document.getElementById('btn-create')?.addEventListener('click', () => openCreate(null));
    document.getElementById('modal-galeri-close')?.addEventListener('click', closeModal);
    document.getElementById('modal-galeri-batal')?.addEventListener('click', closeModal);
    document.getElementById('modal-galeri-backdrop')?.addEventListener('click', closeModal);
    document.getElementById('hapus-galeri-batal')?.addEventListener('click', closeDel);
    document.getElementById('modal-hapus-galeri-backdrop')?.addEventListener('click', closeDel);
    document.getElementById('modal-preview-galeri-close')?.addEventListener('click', closePhotoPreview);
    document.getElementById('modal-preview-galeri-backdrop')?.addEventListener('click', closePhotoPreview);

    document.getElementById('drop-zone')?.addEventListener('click', () => document.getElementById('quick-upload-file')?.click());
    document.getElementById('drop-zone')?.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); document.getElementById('quick-upload-file')?.click(); }
    });
    ['dragenter', 'dragover'].forEach(ev => {
        document.getElementById('drop-zone')?.addEventListener(ev, (e) => {
            e.preventDefault(); e.stopPropagation();
            e.currentTarget.classList.add('border-primary');
        });
    });
    ['dragleave', 'drop'].forEach(ev => {
        document.getElementById('drop-zone')?.addEventListener(ev, (e) => {
            e.preventDefault(); e.stopPropagation();
            e.currentTarget.classList.remove('border-primary');
        });
    });
    document.getElementById('drop-zone')?.addEventListener('drop', (e) => {
        const file = e.dataTransfer?.files?.[0];
        if (!file) return;
        if (!validateClientFile(file, file.type.startsWith('video/') ? 'video' : 'foto')) return;
        openCreate(file);
    });
    document.getElementById('quick-upload-file')?.addEventListener('change', (e) => {
        const file = e.target.files?.[0];
        e.target.value = '';
        if (!file) return;
        if (!validateClientFile(file, file.type.startsWith('video/') ? 'video' : 'foto')) return;
        openCreate(file);
    });

    document.getElementById('galeri-media-file')?.addEventListener('change', (e) => {
        const file = e.target.files?.[0];
        if (!file) { setPendingFile(null); return; }
        const tipe = document.getElementById('galeri-tipe').value;
        if (!validateClientFile(file, tipe)) { e.target.value = ''; setPendingFile(null); return; }
        setPendingFile(file);
    });
    document.getElementById('galeri-media-clear')?.addEventListener('click', () => {
        pendingFile = null;
        clearMediaInput();
    });
    document.getElementById('galeri-tipe')?.addEventListener('change', () => {
        const fi = document.getElementById('galeri-media-file');
        const file = fi?.files?.[0] || pendingFile;
        if (file && !validateClientFile(file, document.getElementById('galeri-tipe').value)) {
            pendingFile = null;
            clearMediaInput();
        }
    });

    document.addEventListener('click', (e) => {
        const trigger = e.target.closest('[data-photo-preview]');
        if (!trigger) return;
        const img = trigger.querySelector('img');
        const vid = trigger.querySelector('video');
        if (vid && vid.src) openPhotoPreview(vid.src, true);
        else if (img && img.src) openPhotoPreview(img.src, false);
    });

    grid?.addEventListener('change', (e) => {
        const cb = e.target.closest('[data-check]');
        if (!cb) return;
        if (cb.checked) selected.add(cb.dataset.check);
        else selected.delete(cb.dataset.check);
        updateSelectedUi();
    });

    grid?.addEventListener('click', async (e) => {
        const previewBtn = e.target.closest('[data-preview]');
        const editBtn = e.target.closest('[data-edit]');
        const delBtn = e.target.closest('[data-del]');
        if (previewBtn) {
            openPhotoPreview(previewBtn.dataset.preview, previewBtn.dataset.previewType === 'video');
            return;
        }
        if (editBtn) {
            const fd = new FormData();
            fd.append('id', editBtn.dataset.edit);
            try {
                const res = await fetch(base + '/admin/ajax/get-galeri', { method: 'POST', body: fd, credentials: 'same-origin' });
                if (res.status === 401) {
                    toast('Sesi habis. Mengalihkan ke halaman login…', false);
                    setTimeout(() => { window.location.href = base + '/admin/login'; }, 1800);
                    return;
                }
                const json = await res.json();
                if (!json.success) { toast(json.message || 'Gagal memuat data.', false); return; }
                const d = json.data;
                document.getElementById('galeri-id').value = d.id || '';
                document.getElementById('galeri-judul').value = d.judul || '';
                document.getElementById('galeri-kategori').value = d.kategori || 'kegiatan';
                document.getElementById('galeri-tipe').value = d.tipe || 'foto';
                document.getElementById('galeri-deskripsi').value = d.deskripsi || '';
                document.getElementById('galeri-rasio').value = d.rasio || '100%';
                document.getElementById('galeri-urutan').value = d.urutan || '';
                pendingFile = null;
                clearMediaInput();
                const previewBox = document.getElementById('galeri-media-preview');
                const previewImg = document.getElementById('galeri-media-preview-img');
                const previewUrl = document.getElementById('galeri-media-preview-url');
                if (d.file) {
                    const src = mediaUrl(d.file);
                    previewImg.src = src;
                    previewUrl.textContent = d.file;
                    previewBox.classList.remove('hidden');
                    previewBox.classList.add('flex');
                }
                openModal('Edit Media');
            } catch (err) {
                toast('Gagal memuat data galeri.', false);
            }
            return;
        }
        if (delBtn) {
            openDel([delBtn.dataset.del], `Yakin hapus '${delBtn.dataset.nama || 'media ini'}'? Tindakan ini tidak bisa dibatalkan.`);
        }
    });

    btnDeleteSelected?.addEventListener('click', () => {
        if (!selected.size) return;
        openDel([...selected], `Yakin hapus ${selected.size} media terpilih? Tindakan ini tidak bisa dibatalkan.`);
    });

    document.getElementById('hapus-galeri-ya')?.addEventListener('click', async () => {
        if (!deleteIds.length) return;
        const fd = new FormData();
        fd.append('csrf_token', csrf);
        deleteIds.forEach(id => fd.append('ids[]', id));
        try {
            const res = await fetch(base + '/admin/ajax/delete-galeri', { method: 'POST', body: fd, credentials: 'same-origin' });
            const json = await res.json();
            toast(json.message || (json.success ? 'Dihapus.' : 'Gagal.'), !!json.success);
            if (json.success) { closeDel(); loadList(); }
        } catch (err) { toast('Gagal terhubung ke server atau terjadi kesalahan internal. Periksa koneksi internet Anda dan coba lagi.', false); }
    });

    form?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const btn = document.getElementById('btn-simpan-galeri');
        const id = document.getElementById('galeri-id').value;
        const tipe = document.getElementById('galeri-tipe').value;
        const fileInput = document.getElementById('galeri-media-file');
        const file = pendingFile || fileInput?.files?.[0] || null;
        if (!id && !file) { toast('File media wajib diunggah untuk item baru.', false); return; }
        if (file && !validateClientFile(file, tipe)) return;
        btn.disabled = true;
        try {
            const fd = new FormData(form);
            if (pendingFile) fd.set('media_file', pendingFile, pendingFile.name);
            if (!document.getElementById('galeri-urutan').value) fd.delete('urutan');
            const res = await fetch(base + '/admin/ajax/store-galeri', { method: 'POST', body: fd, credentials: 'same-origin' });
            const json = await res.json();
            toast(json.message || (json.success ? 'Tersimpan.' : 'Gagal.'), !!json.success);
            if (json.success) { closeModal(); loadList(); }
        } catch (err) { toast('Gagal terhubung ke server atau terjadi kesalahan internal. Periksa koneksi internet Anda dan coba lagi.', false); }
        finally { btn.disabled = false; }
    });

    document.getElementById('search-galeri')?.addEventListener('input', (e) => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => { search = e.target.value.trim(); page = 1; loadList(); }, 300);
    });
    document.getElementById('filter-kategori')?.addEventListener('change', (e) => {
        kategori = e.target.value;
        page = 1;
        loadList();
    });

    /* ── Reset filter ── */
    document.getElementById('btn-reset-filter')?.addEventListener('click', () => {
        document.getElementById('search-galeri').value = '';
        document.getElementById('filter-kategori').value = '';
        search = ''; kategori = '';
        page = 1;
        loadList();
    });
    document.querySelectorAll('.sort-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.sort-btn').forEach(b => {
                b.classList.remove('bg-surface-2', 'text-ink', 'border-primary');
                b.classList.add('bg-surface', 'text-ink-dim', 'border-line');
            });
            btn.classList.remove('bg-surface', 'text-ink-dim', 'border-line');
            btn.classList.add('bg-surface-2', 'text-ink', 'border-primary');
            sort = btn.dataset.sort || 'newest';
            page = 1;
            loadList();
        });
    });
    btnPrev?.addEventListener('click', () => { if (hasPrev) { page--; loadList(); } });
    btnNext?.addEventListener('click', () => { if (hasNext) { page++; loadList(); } });

    loadList();
})();
</script>
<?php require __DIR__ . '/../partials/footer.php'; ?>
