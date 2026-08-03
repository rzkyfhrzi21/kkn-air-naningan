<?php
$pageTitle = 'Kelola Berita';
$activeNav = 'kelola-berita';
$base = defined('APP_BASE') ? APP_BASE : '';
$csrf = $_SESSION['csrf_token'] ?? '';
require __DIR__ . '/../partials/header.php';
?>
<div class="flex flex-col w-full min-h-full">

    <!-- Header Section -->
    <div class="px-container-pad-desktop py-8 md:py-12 flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-line">
        <div class="max-w-2xl">
            <h1 class="font-h1 text-h1-mobile md:text-h1 text-ink mb-3">Kelola Berita</h1>
            <p class="font-body-lg text-body-lg text-ink-dim">Publikasikan informasi terkini, pengumuman desa, dan liputan kegiatan Pekon Air Naningan.</p>
        </div>
        <button id="btn-create-news" class="bg-primary text-on-primary hover:bg-primary-fixed transition-colors rounded-full px-6 py-3 font-label-mono uppercase tracking-widest flex items-center justify-center gap-2 flex-shrink-0 shadow-xl shadow-primary/10">
            <span class="material-symbols-outlined text-[20px]">add</span>
            Tulis Berita Baru
        </button>
    </div>

    <!-- Main Content: Split View -->
    <div class="flex-1 flex flex-col xl:flex-row relative">

        <!-- News List (Left Panel) -->
        <div id="news-list-panel" class="w-full xl:w-1/2 2xl:w-7/12 border-r-0 xl:border-r border-line flex flex-col transition-all duration-500">

            <!-- Filter & Search -->
            <div class="p-6 border-b border-line bg-surface-container-low flex flex-col sm:flex-row gap-4 items-center justify-between sticky top-16 z-20">
                <div class="relative w-full sm:w-64">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-ink-dim text-[20px]">search</span>
                    <input id="search-berita" class="w-full bg-surface border border-line rounded-lg py-2.5 pl-10 pr-4 text-ink font-body-md focus:outline-none focus:border-primary transition-colors placeholder:text-surface-variant" placeholder="Cari judul berita..." type="text"/>
                </div>
                <div class="flex items-center gap-2 w-full sm:w-auto overflow-x-auto pb-1 sm:pb-0" id="filter-berita">
                    <button class="kat-btn active px-4 py-1.5 rounded-full bg-surface-2 text-ink border border-line-strong font-label-mono text-[11px] whitespace-nowrap" data-kat="">Semua</button>
                    <button class="kat-btn px-4 py-1.5 rounded-full bg-transparent text-ink-dim hover:text-ink border border-transparent hover:border-line font-label-mono text-[11px] whitespace-nowrap transition-colors" data-kat="Pengumuman">Pengumuman</button>
                    <button class="kat-btn px-4 py-1.5 rounded-full bg-transparent text-ink-dim hover:text-ink border border-transparent hover:border-line font-label-mono text-[11px] whitespace-nowrap transition-colors" data-kat="Kegiatan">Kegiatan</button>
                    <button class="kat-btn px-4 py-1.5 rounded-full bg-transparent text-ink-dim hover:text-ink border border-transparent hover:border-line font-label-mono text-[11px] whitespace-nowrap transition-colors" data-kat="Bantuan Sosial">Bansos</button>
                    <button class="kat-btn px-4 py-1.5 rounded-full bg-transparent text-ink-dim hover:text-ink border border-transparent hover:border-line font-label-mono text-[11px] whitespace-nowrap transition-colors" data-kat="Pertanian">Pertanian</button>
                    <button class="kat-btn px-4 py-1.5 rounded-full bg-transparent text-ink-dim hover:text-ink border border-transparent hover:border-line font-label-mono text-[11px] whitespace-nowrap transition-colors text-tertiary-fixed-dim" data-kat="_draft">Draft</button>
                </div>
            </div>

            <!-- Table Header -->
            <div class="hidden sm:grid grid-cols-12 gap-4 px-8 py-4 border-b border-line bg-surface-container-lowest font-label-mono text-[10px] text-ink-dim uppercase tracking-widest sticky top-[137px] z-10">
                <div class="col-span-6">Judul Berita</div>
                <div class="col-span-2">Kategori</div>
                <div class="col-span-2">Status</div>
                <div class="col-span-2 text-right">Tanggal</div>
            </div>

            <!-- News Items -->
            <div id="berita-tbody" class="flex-1 overflow-y-auto divide-y divide-line">
                <!-- Data loaded via JS -->
            </div>

            <!-- Pagination -->
            <div class="p-6 border-t border-line flex items-center justify-between text-ink-dim font-label-mono text-[11px]">
                <span id="berita-meta">Menampilkan 0 data</span>
                <div class="flex items-center gap-2">
                    <button id="btn-prev" class="w-8 h-8 rounded border border-line flex items-center justify-center hover:bg-surface-2 hover:text-ink transition-colors disabled:opacity-50">
                        <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                    </button>
                    <button id="btn-next" class="w-8 h-8 rounded border border-line flex items-center justify-center hover:bg-surface-2 hover:text-ink transition-colors disabled:opacity-50">
                        <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Editor Panel (Right Panel) -->
        <div id="editor-panel" class="w-full xl:w-1/2 2xl:w-5/12 bg-surface-container-lowest flex flex-col xl:sticky xl:top-16 xl:h-[calc(100vh-4rem)] border-t xl:border-t-0 border-line">
            
            <form id="form-berita" autocomplete="off" class="flex flex-col h-full">
                <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                <input type="hidden" name="id" id="berita-id" value="">
                <input type="hidden" name="status" id="berita-status-input" value="terbit">

                <!-- Editor Header -->
                <div class="px-8 py-5 border-b border-line flex items-center justify-between bg-surface/50 backdrop-blur-md sticky top-0 z-20">
                    <div class="flex items-center gap-3">
                        <span class="w-2 h-2 rounded-full bg-primary animate-pulse hidden" id="editor-pulse"></span>
                        <span class="font-label-mono text-[11px] text-ink uppercase tracking-widest" id="editor-title">Tulis Berita Baru</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="button" id="btn-save-draft" class="text-ink-dim hover:text-ink font-label-mono text-[11px] px-3 py-2 rounded-md hover:bg-surface-2 transition-colors">Simpan Draft</button>
                        <button type="button" id="btn-save-publish" class="bg-primary text-on-primary px-5 py-2 rounded-full font-label-mono text-[11px] hover:bg-primary-fixed transition-colors shadow-lg shadow-primary/10">Publikasikan</button>
                    </div>
                </div>

                <!-- Editor Body -->
                <div class="flex-1 overflow-y-auto p-8 space-y-8">
                    <!-- Title -->
                    <div class="space-y-2">
                        <label class="font-body-md text-[13px] text-ink-dim block">Judul Berita</label>
                        <textarea name="judul" id="berita-judul" required class="w-full bg-transparent border-b border-transparent hover:border-line focus:border-primary p-2 text-h2 font-h2 text-ink focus:ring-0 resize-none placeholder:text-surface-variant leading-tight transition-colors" placeholder="Masukkan judul berita..." rows="2"></textarea>
                    </div>
                    
                    <!-- Meta Info -->
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="font-body-md text-[13px] text-ink-dim block">Kategori</label>
                            <select name="kategori" id="berita-kategori" class="w-full bg-surface border border-line rounded-lg px-4 py-3 text-ink font-body-md focus:outline-none focus:border-primary appearance-none cursor-pointer">
                                <option value="Pengumuman">Pengumuman</option>
                                <option value="Kegiatan">Kegiatan</option>
                                <option value="Bantuan Sosial">Bantuan Sosial</option>
                                <option value="Pertanian">Pertanian</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="font-body-md text-[13px] text-ink-dim block">Tanggal Publikasi</label>
                            <input name="tanggal_terbit" id="berita-tanggal" required class="w-full bg-surface border border-line rounded-lg px-4 py-3 text-ink font-body-md focus:outline-none focus:border-primary" type="date" value="<?= date('Y-m-d') ?>"/>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="font-body-md text-[13px] text-ink-dim block">Ringkasan</label>
                        <textarea name="ringkasan" id="berita-ringkasan" required class="w-full bg-surface border border-line rounded-lg px-4 py-3 text-ink font-body-md focus:outline-none focus:border-primary resize-y" rows="3" placeholder="Ringkasan singkat untuk ditampilkan di kartu..."></textarea>
                    </div>

                    <!-- Cover Image -->
                    <div class="space-y-3">
                        <label class="font-body-md text-[13px] text-ink-dim block">Gambar Sampul</label>
                        <input type="file" name="foto_file" id="berita-foto-file" accept=".jpg,.jpeg,.png,.webp" class="hidden">
                        
                        <!-- Area Klik Upload -->
                        <div id="berita-foto-trigger" class="w-full h-48 rounded-xl border border-dashed border-outline-variant bg-surface-container hover:bg-surface-2 transition-colors flex flex-col items-center justify-center cursor-pointer group relative overflow-hidden">
                            <!-- Preview Foto Baru -->
                            <img id="berita-foto-new-img" class="absolute inset-0 w-full h-full object-cover hidden" src="" alt="New Cover"/>
                            
                            <!-- Preview Foto Lama -->
                            <img id="berita-foto-old-img" class="absolute inset-0 w-full h-full object-cover opacity-60 group-hover:opacity-40 transition-opacity hidden" src="" alt="Cover"/>
                            
                            <div class="relative z-10 flex flex-col items-center gap-2 bg-background/80 p-4 rounded-lg backdrop-blur-sm border border-line">
                                <span class="material-symbols-outlined text-primary text-[28px]">photo_camera</span>
                                <span class="font-label-mono text-[11px] text-ink" id="berita-foto-label">Ganti/Pilih Gambar</span>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="button" id="berita-foto-clear" class="text-xs text-danger hover:underline hidden">Hapus pilihan foto baru</button>
                        </div>
                    </div>

                    <!-- Content Area (Simple Textarea for now to ensure safe markup, can be replaced by WYSIWYG later) -->
                    <div class="space-y-2 flex-1 flex flex-col">
                        <label class="font-body-md text-[13px] text-ink-dim block">Isi Berita</label>
                        <p class="text-[11px] text-ink-dim font-label-mono">Gunakan tag HTML dasar: &lt;p&gt;, &lt;strong&gt;, &lt;h2&gt;, &lt;ul&gt;, dll.</p>
                        <textarea name="konten" id="berita-konten" required class="w-full bg-surface border border-line rounded-lg p-4 text-ink font-body-md focus:outline-none focus:border-primary flex-1 min-h-[300px] font-mono text-sm leading-relaxed" placeholder="<p>Mulai menulis berita di sini...</p>"></textarea>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Toast -->
<div id="berita-toast" class="fixed bottom-6 right-6 z-50 transform transition-all duration-300 translate-y-20 opacity-0 flex items-center gap-3 px-5 py-4 rounded-xl shadow-2xl bg-inverse-surface text-inverse-on-surface">
    <span class="material-symbols-outlined text-primary" id="toast-icon">check_circle</span>
    <p class="font-body-md text-sm" id="toast-msg">Pesan toast</p>
</div>

<!-- Modal Hapus -->
<div id="modal-hapus" class="fixed inset-0 z-[60] hidden items-center justify-center">
    <div id="modal-hapus-backdrop" class="absolute inset-0 bg-scrim/40 backdrop-blur-sm"></div>
    <div class="relative bg-surface rounded-2xl shadow-2xl w-full max-w-sm mx-4 overflow-hidden animate-scale-in">
        <div class="p-6">
            <div class="w-12 h-12 rounded-full bg-danger/10 flex items-center justify-center mb-4">
                <span class="material-symbols-outlined text-danger text-[24px]">delete</span>
            </div>
            <h3 class="font-h3 text-h3 text-ink mb-2">Hapus Berita?</h3>
            <p class="font-body-md text-body-md text-ink-dim mb-6">Tindakan ini tidak dapat dibatalkan. Berita "<span id="hapus-nama" class="font-medium text-ink"></span>" akan dihapus permanen.</p>
            <div class="flex justify-end gap-3">
                <button type="button" id="hapus-batal" class="px-5 py-2.5 rounded-full font-label-mono text-[11px] uppercase bg-surface-2 text-ink">Batal</button>
                <button type="button" id="hapus-ya" class="px-5 py-2.5 rounded-full font-label-mono text-[11px] uppercase bg-danger text-white">Hapus</button>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    const base = <?= json_encode($base, JSON_UNESCAPED_SLASHES) ?>;
    const csrf = <?= json_encode($csrf) ?>;
    let page = 1, search = '', kategori = '', status = '', hasNext = false, hasPrev = false;
    let deleteId = null, searchTimer = null;

    const tbody = document.getElementById('berita-tbody');
    const meta = document.getElementById('berita-meta');
    const btnPrev = document.getElementById('btn-prev');
    const btnNext = document.getElementById('btn-next');
    const toastEl = document.getElementById('berita-toast');
    const form = document.getElementById('form-berita');
    
    function toast(msg, success = true) {
        document.getElementById('toast-msg').textContent = msg;
        document.getElementById('toast-icon').textContent = success ? 'check_circle' : 'error';
        document.getElementById('toast-icon').className = success ? 'material-symbols-outlined text-primary' : 'material-symbols-outlined text-danger';
        toastEl.classList.remove('translate-y-20', 'opacity-0');
        setTimeout(() => toastEl.classList.add('translate-y-20', 'opacity-0'), 3000);
    }
    
    function mediaUrl(path) {
        if (!path) return '';
        if (path.startsWith('http') || path.startsWith('data:')) return path;
        return base + '/' + path.replace(/^\//, '');
    }

    async function loadList() {
        tbody.innerHTML = '<div class="p-12 text-center flex justify-center"><span class="inline-block w-6 h-6 border-2 border-primary border-t-transparent rounded-full animate-spin"></span></div>';
        const fd = new FormData();
        fd.append('page', page);
        fd.append('search', search);
        if (kategori === '_draft') {
            fd.append('status', 'draft');
        } else {
            fd.append('kategori', kategori);
        }

        try {
            const res = await fetch(base + '/admin/ajax/list-berita', { method: 'POST', body: fd, credentials: 'same-origin' });
            const json = await res.json();
            if (!json.success) { toast(json.message || 'Gagal memuat.', false); return; }
            hasNext = json.has_next; hasPrev = json.has_prev;
            btnPrev.disabled = !hasPrev; btnNext.disabled = !hasNext;
            meta.textContent = 'Menampilkan ' + json.data.length + ' dari ' + json.total + ' berita';
            
            if (!json.data.length) {
                tbody.innerHTML = '<div class="p-12 text-center text-ink-dim font-body-md">Belum ada berita.</div>';
                return;
            }
            
            tbody.innerHTML = json.data.map(row => {
                const isTerbit = (row.status === 'terbit');
                const badgeKatClass = "bg-primary-container/20 text-primary-fixed-dim";
                const bgKat = "bg-primary";
                
                const cover = row.foto_sampul ? mediaUrl(row.foto_sampul) : null;
                
                return `
                <div class="group cursor-pointer hover:bg-surface-2 transition-colors relative" data-id="${row.id}">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary scale-y-0 group-hover:scale-y-100 transition-transform origin-center"></div>
                    <div class="p-6 sm:px-8 sm:py-5 grid grid-cols-1 sm:grid-cols-12 gap-4 items-center relative">
                        <div class="sm:col-span-6 flex gap-4 items-start">
                            <div class="w-16 h-16 rounded-lg bg-surface-container overflow-hidden flex-shrink-0 relative">
                                ${cover ? `<img class="w-full h-full object-cover" src="${cover}" alt="Cover"/>` : `<div class="absolute inset-0 flex items-center justify-center text-ink-dim bg-surface-2"><span class="material-symbols-outlined">newspaper</span></div>`}
                            </div>
                            <div class="min-w-0 pr-12">
                                <h3 class="font-h3 text-[18px] text-ink leading-snug truncate mb-1 group-hover:text-primary transition-colors edit-trigger" data-edit="${row.id}">${row.judul}</h3>
                                <p class="text-ink-dim font-body-md text-[13px] line-clamp-1">${row.ringkasan}</p>
                            </div>
                        </div>
                        <div class="sm:col-span-2 flex items-center">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md ${badgeKatClass} font-label-mono text-[10px]">
                                <span class="w-1.5 h-1.5 rounded-full ${bgKat}"></span>${row.kategori}
                            </span>
                        </div>
                        <div class="sm:col-span-2 flex items-center">
                            ${isTerbit ? 
                                `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-surface-container-highest text-ink font-label-mono text-[10px]"><span class="material-symbols-outlined text-[14px]">public</span>Terbit</span>` :
                                `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-surface-container text-ink-dim font-label-mono text-[10px]"><span class="material-symbols-outlined text-[14px]">edit_document</span>Draft</span>`
                            }
                        </div>
                        <div class="sm:col-span-2 sm:text-right flex items-center sm:justify-end gap-4 sm:gap-0 text-ink-dim font-label-mono text-[11px]">
                            <span>${row.tanggal_terbit}</span>
                        </div>
                        <!-- Action buttons appear on hover -->
                        <div class="absolute right-6 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-2">
                            <button type="button" class="w-8 h-8 rounded-full bg-danger/10 text-danger hover:bg-danger hover:text-white flex items-center justify-center transition-colors del-trigger" data-del="${row.id}" data-nama="${row.judul}">
                                <span class="material-symbols-outlined text-[18px]">delete</span>
                            </button>
                        </div>
                    </div>
                </div>`;
            }).join('');
        } catch (err) { toast('Gagal memuat data.', false); }
    }

    function resetForm() {
        form.reset();
        document.getElementById('berita-id').value = '';
        document.getElementById('editor-title').textContent = 'Tulis Berita Baru';
        document.getElementById('editor-pulse').classList.add('hidden');
        document.getElementById('berita-foto-old-img').classList.add('hidden');
        document.getElementById('berita-foto-old-img').src = '';
        document.getElementById('berita-foto-new-img').classList.add('hidden');
        document.getElementById('berita-foto-new-img').src = '';
        document.getElementById('berita-foto-clear').classList.add('hidden');
        
        // set default date
        const d = new Date();
        const m = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        document.getElementById('berita-tanggal').value = `${d.getFullYear()}-${m}-${day}`;
    }

    document.getElementById('btn-create-news')?.addEventListener('click', resetForm);

    document.getElementById('search-berita')?.addEventListener('input', (e) => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => { search = e.target.value.trim(); page = 1; loadList(); }, 300);
    });

    document.getElementById('filter-berita')?.addEventListener('click', (e) => {
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

    btnPrev?.addEventListener('click', () => { if (hasPrev) { page--; loadList(); } });
    btnNext?.addEventListener('click', () => { if (hasNext) { page++; loadList(); } });

    // Hapus Modal Logic
    const modalHapus = document.getElementById('modal-hapus');
    function closeHapus() {
        modalHapus.classList.add('hidden');
        modalHapus.classList.remove('flex');
        deleteId = null;
    }
    document.getElementById('hapus-batal')?.addEventListener('click', closeHapus);
    document.getElementById('modal-hapus-backdrop')?.addEventListener('click', closeHapus);

    tbody?.addEventListener('click', async (e) => {
        const delBtn = e.target.closest('.del-trigger');
        const editArea = e.target.closest('.group');
        
        if (delBtn) {
            e.stopPropagation(); // prevent edit selection
            deleteId = delBtn.dataset.del;
            document.getElementById('hapus-nama').textContent = delBtn.dataset.nama || '';
            modalHapus.classList.remove('hidden');
            modalHapus.classList.add('flex');
            return;
        }

        if (editArea && editArea.dataset.id) {
            const id = editArea.dataset.id;
            
            // visually set active
            document.querySelectorAll('#berita-tbody .group').forEach(el => el.classList.remove('bg-surface-2'));
            editArea.classList.add('bg-surface-2');

            const fd = new FormData();
            fd.append('id', id);
            try {
                const res = await fetch(base + '/admin/ajax/get-berita', { method: 'POST', body: fd, credentials: 'same-origin' });
                const json = await res.json();
                if (!json.success) { toast('Gagal memuat detail berita.', false); return; }
                const d = json.data;
                
                document.getElementById('berita-id').value = d.id;
                document.getElementById('berita-judul').value = d.judul;
                document.getElementById('berita-kategori').value = d.kategori;
                document.getElementById('berita-tanggal').value = d.tanggal_terbit;
                document.getElementById('berita-ringkasan').value = d.ringkasan;
                document.getElementById('berita-konten').value = d.konten;
                
                document.getElementById('editor-title').textContent = 'Sedang Mengedit';
                document.getElementById('editor-pulse').classList.remove('hidden');

                // Foto handling
                document.getElementById('berita-foto-file').value = '';
                document.getElementById('berita-foto-new-img').classList.add('hidden');
                document.getElementById('berita-foto-clear').classList.add('hidden');

                const oldImg = document.getElementById('berita-foto-old-img');
                if (d.foto_sampul) {
                    oldImg.src = mediaUrl(d.foto_sampul);
                    oldImg.classList.remove('hidden');
                } else {
                    oldImg.classList.add('hidden');
                    oldImg.src = '';
                }

                // Scroll to form on mobile
                if (window.innerWidth < 1280) {
                    document.getElementById('editor-panel').scrollIntoView({ behavior: 'smooth' });
                }
            } catch (err) { toast('Gagal memuat berita.', false); }
        }
    });

    document.getElementById('hapus-ya')?.addEventListener('click', async () => {
        if (!deleteId) return;
        const fd = new FormData();
        fd.append('csrf_token', csrf);
        fd.append('id', deleteId);
        try {
            const res = await fetch(base + '/admin/ajax/delete-berita', { method: 'POST', body: fd, credentials: 'same-origin' });
            const json = await res.json();
            toast(json.message || (json.success ? 'Dihapus.' : 'Gagal.'), !!json.success);
            if (json.success) { 
                closeHapus(); 
                if (document.getElementById('berita-id').value === deleteId) {
                    resetForm();
                }
                loadList(); 
            }
        } catch (err) { toast('Gagal menghubungi server.', false); }
    });

    // File input preview
    const fileInput = document.getElementById('berita-foto-file');
    document.getElementById('berita-foto-trigger').addEventListener('click', () => fileInput.click());
    
    fileInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        const newImg = document.getElementById('berita-foto-new-img');
        const oldImg = document.getElementById('berita-foto-old-img');
        const clearBtn = document.getElementById('berita-foto-clear');
        
        if (file) {
            if (file.size > 2 * 1024 * 1024) {
                toast('Ukuran foto maks 2MB.', false);
                e.target.value = '';
                return;
            }
            newImg.src = URL.createObjectURL(file);
            newImg.classList.remove('hidden');
            oldImg.classList.add('hidden');
            clearBtn.classList.remove('hidden');
        }
    });

    document.getElementById('berita-foto-clear').addEventListener('click', () => {
        fileInput.value = '';
        document.getElementById('berita-foto-new-img').classList.add('hidden');
        document.getElementById('berita-foto-new-img').src = '';
        document.getElementById('berita-foto-clear').classList.add('hidden');
        if (document.getElementById('berita-foto-old-img').src) {
            document.getElementById('berita-foto-old-img').classList.remove('hidden');
        }
    });

    // Save Handlers
    async function saveForm(statusVal) {
        if (!form.reportValidity()) return;
        document.getElementById('berita-status-input').value = statusVal;
        
        const btnId = statusVal === 'terbit' ? 'btn-save-publish' : 'btn-save-draft';
        const btn = document.getElementById(btnId);
        const originalText = btn.textContent;
        btn.textContent = 'Menyimpan...';
        btn.disabled = true;

        try {
            const fd = new FormData(form);
            const res = await fetch(base + '/admin/ajax/store-berita', { method: 'POST', body: fd, credentials: 'same-origin' });
            const json = await res.json();
            toast(json.message || (json.success ? 'Tersimpan.' : 'Gagal.'), !!json.success);
            if (json.success) {
                resetForm();
                loadList();
            }
        } catch (err) { toast('Gagal menyimpan berita.', false); }
        finally {
            btn.textContent = originalText;
            btn.disabled = false;
        }
    }

    document.getElementById('btn-save-publish').addEventListener('click', () => saveForm('terbit'));
    document.getElementById('btn-save-draft').addEventListener('click', () => saveForm('draft'));

    loadList();
})();
</script>
<?php require __DIR__ . '/../partials/footer.php'; ?>
