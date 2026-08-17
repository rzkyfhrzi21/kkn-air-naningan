<?php
/* ======================================================
   HALAMAN KOTAK PESAN MASUK (INBOX MESSAGES)

   Halaman ini adalah "kotak surat pengaduan warga" panel admin.
   Dari sini admin bisa:
   - Melihat pesan/pengaduan/aspirasi yang dikirim pengunjung lewat form `/kontak`,
   - Memfilter pesan yang "Belum Dibaca" atau "Sudah Dibaca",
   - Membaca isi pesan lengkap dalam modal baca,
   - Menandai pesan sebagai "Sudah Dibaca",
   - Menghapus pesan dari sistem.

   Tabel data pesan diisi secara interaktif oleh JavaScript via AJAX (list-pesan.php).
====================================================== */

$pageTitle = 'Pesan Masuk';
$activeNav = 'pesan-masuk';
require __DIR__ . '/../partials/header.php';

$csrf = (string) ($_SESSION['csrf_token'] ?? '');
$base = defined('APP_BASE') ? APP_BASE : '';
?>

<div class="flex flex-col w-full">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6 pt-10 px-container-pad-mobile lg:px-container-pad-desktop">
        <div class="flex flex-col gap-2 max-w-2xl">
            <h1 class="font-h1-mobile lg:font-h1 text-h1-mobile lg:text-h1 text-ink">Pesan Masuk</h1>
            <p class="font-body-lg text-body-lg text-ink-dim">Pesan yang dikirim pengunjung melalui halaman <code class="text-gold-soft">/kontak</code>. Mode hanya baca.</p>
        </div>
    </div>

    <!-- Toast -->

    <div class="flex-1 px-container-pad-mobile lg:px-container-pad-desktop pt-10 pb-section-v-desktop">
        <div class="flex flex-col gap-6">

            <!-- Stat Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-surface-2 rounded-2xl p-5 border border-line flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-primary text-[22px]">mail</span>
                    </div>
                    <div>
                        <div class="font-label-mono text-label-mono text-ink-dim uppercase text-[10px] mb-0.5">Total Pesan</div>
                        <div class="font-h2 text-h2 text-ink leading-none" id="stat-total">—</div>
                    </div>
                </div>
                <div class="bg-surface-2 rounded-2xl p-5 border border-line flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-amber-400 text-[22px]">mark_email_unread</span>
                    </div>
                    <div>
                        <div class="font-label-mono text-label-mono text-ink-dim uppercase text-[10px] mb-0.5">Belum Dibaca</div>
                        <div class="font-h2 text-h2 text-ink leading-none" id="stat-baru">—</div>
                    </div>
                </div>
                <div class="bg-surface-2 rounded-2xl p-5 border border-line flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-surface-container border border-line flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-ink-dim text-[22px]">drafts</span>
                    </div>
                    <div>
                        <div class="font-label-mono text-label-mono text-ink-dim uppercase text-[10px] mb-0.5">Sudah Dibaca</div>
                        <div class="font-h2 text-h2 text-ink-dim leading-none" id="stat-dibaca">—</div>
                    </div>
                </div>
            </div>

            <!-- Split View -->
            <div class="flex flex-col lg:flex-row gap-0 bg-surface rounded-2xl border border-line overflow-hidden shadow-sm min-h-[600px]">

                <!-- Left: List Panel -->
                <div class="w-full lg:w-[360px] shrink-0 border-b lg:border-b-0 lg:border-r border-line flex flex-col">
                    <!-- Search + Filter -->
                    <div class="p-4 border-b border-line flex flex-col gap-3">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="material-symbols-outlined text-ink-dim text-[18px]">search</span>
                            </div>
                            <input id="search-pesan"
                                   class="w-full bg-surface-container-high border-none text-ink text-body-md font-body-md rounded-lg pl-9 pr-4 py-2 focus:ring-1 focus:ring-primary focus:outline-none placeholder:text-ink-dim/50"
                                   placeholder="Cari nama atau pesan..."
                                   type="text"/>
                        </div>
                        <div class="flex gap-2" id="filter-pesan">
                            <button type="button" data-filter="all"    class="filter-btn px-3 py-1.5 rounded-full bg-surface-2 text-ink border border-line-strong font-label-mono text-[11px] whitespace-nowrap">Semua</button>
                            <button type="button" data-filter="unread"  class="filter-btn px-3 py-1.5 rounded-full bg-transparent text-ink-dim border border-transparent font-label-mono text-[11px] whitespace-nowrap hover:bg-surface-2 transition-colors">Belum Dibaca</button>
                            <button type="button" data-filter="read"    class="filter-btn px-3 py-1.5 rounded-full bg-transparent text-ink-dim border border-transparent font-label-mono text-[11px] whitespace-nowrap hover:bg-surface-2 transition-colors">Sudah Dibaca</button>
                        </div>
                        <div class="flex justify-end">
                            <button type="button" id="btn-reset-filter" class="flex items-center gap-1.5 px-3 py-2 rounded-lg bg-surface border border-line text-ink-dim hover:text-ink hover:border-line-strong font-label-mono text-[10px] uppercase tracking-wider transition-colors" title="Reset semua filter">
                                <span class="material-symbols-outlined text-[15px]">refresh</span> Reset
                            </button>
                        </div>
                    </div>

                    <!-- Message List -->
                    <div id="pesan-list" class="flex-1 overflow-y-auto">
                        <div class="py-12 flex items-center justify-center">
                            <span class="w-6 h-6 border-2 border-primary border-t-transparent rounded-full animate-spin"></span>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="flex items-center justify-between px-4 py-3 border-t border-line bg-surface-container/30">
                        <span id="pesan-meta" class="font-label-mono text-[11px] text-ink-dim">—</span>
                        <div class="flex gap-2">
                            <button type="button" id="btn-prev" disabled class="px-3 py-1.5 rounded-full border border-line text-ink-dim font-label-mono text-[11px] uppercase disabled:opacity-40">Prev</button>
                            <button type="button" id="btn-next" disabled class="px-3 py-1.5 rounded-full border border-line text-ink-dim font-label-mono text-[11px] uppercase disabled:opacity-40">Next</button>
                        </div>
                    </div>
                </div>

                <!-- Right: Detail Panel -->
                <div id="pesan-detail" class="flex-1 flex flex-col">
                    <!-- Empty state (default) -->
                    <div id="detail-empty" class="flex-1 flex flex-col items-center justify-center gap-4 p-8 text-center">
                        <span class="material-symbols-outlined text-5xl text-ink-dim/20">inbox</span>
                        <p class="text-ink-dim font-body-md">Pilih pesan dari daftar untuk melihat isinya.</p>
                    </div>
                    <!-- Detail content (hidden by default) -->
                    <div id="detail-content" class="hidden flex-col flex-1 overflow-y-auto">
                        <!-- Header detail -->
                        <div class="flex items-start justify-between p-6 border-b border-line gap-4">
                            <div class="flex items-center gap-4">
                                <div id="detail-avatar" class="w-11 h-11 rounded-full bg-primary flex items-center justify-center shrink-0">
                                    <span class="font-h3 text-on-primary text-base" id="detail-initials">—</span>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span id="detail-nama" class="font-h3 text-base text-ink font-semibold">—</span>
                                        <span id="detail-badge" class="text-[10px] font-label-mono px-2 py-0.5 rounded-full"></span>
                                    </div>
                                    <div id="detail-kontak" class="font-body-md text-sm text-ink-dim mt-0.5">—</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <button id="btn-toggle-read" type="button" title="Tandai Belum Dibaca"
                                        class="p-2 rounded-xl text-ink-dim hover:text-ink hover:bg-surface-container-high transition-colors">
                                    <span class="material-symbols-outlined text-[20px]">mark_email_read</span>
                                </button>
                                <button id="btn-delete-pesan" type="button" title="Hapus Pesan"
                                        class="p-2 rounded-xl text-ink-dim hover:text-danger hover:bg-danger/10 transition-colors">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </div>
                        </div>
                        <!-- Meta info -->
                        <div class="flex items-center gap-4 px-6 py-3 border-b border-line bg-surface-container/30">
                            <span class="font-label-mono text-[10px] text-ink-dim uppercase tracking-widest">Kategori</span>
                            <span id="detail-kategori" class="font-label-mono text-[11px] text-gold-soft bg-gold-soft/10 px-2 py-0.5 rounded-full">—</span>
                            <span class="w-px h-4 bg-line"></span>
                            <span class="font-label-mono text-[10px] text-ink-dim uppercase tracking-widest">Waktu</span>
                            <span id="detail-waktu" class="font-label-mono text-[11px] text-ink-dim">—</span>
                        </div>
                        <!-- Message body -->
                        <div class="flex-1 p-6 md:p-8 lg:p-10">
                            <div id="detail-pesan"
                                 class="font-body-md text-body-md text-ink leading-relaxed whitespace-pre-wrap min-h-[140px] max-w-3xl text-[15px] md:text-[16px]">—</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal hapus -->
<div id="modal-hapus-pesan" data-modal class="hidden fixed inset-0 z-[110] items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60" id="modal-hapus-pesan-backdrop"></div>
    <div class="relative w-full max-w-sm bg-surface border border-line rounded-2xl shadow-2xl p-6 flex flex-col gap-4">
        <h2 class="font-h3 text-h3 text-ink">Hapus Pesan?</h2>
        <p class="font-body-md text-ink-dim text-sm">Yakin hapus pesan dari <strong id="hapus-pesan-nama" class="text-ink"></strong>? Tindakan ini tidak bisa dibatalkan.</p>
        <div class="flex justify-end gap-3">
            <button type="button" id="hapus-pesan-batal" data-modal-close class="px-5 py-2.5 rounded-full font-label-mono text-[11px] uppercase bg-surface-2 text-ink">Batal</button>
            <button type="button" id="hapus-pesan-ya" class="px-5 py-2.5 rounded-full font-label-mono text-[11px] uppercase bg-danger text-white">Hapus</button>
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';
    const base  = '<?= htmlspecialchars($base, ENT_QUOTES, 'UTF-8') ?>';
    const csrf  = '<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>';

    let page    = 1;
    let search  = '';
    let filter  = 'all';
    let hasNext = false;
    let hasPrev = false;
    let searchTimer = null;
    let activePesanId = null;
    let activePesanNama = '';
    let deletePesanId = null;

    // ── Toast ──────────────────────────────────────────────────────────────
    function toast(msg, ok = true) {
        window.showAdminToast(msg, ok);
    }

    // ── Helpers ──────────────────────────────────────────────────────────────
    function esc(s) {
        const d = document.createElement('div');
        d.textContent = String(s ?? '');
        return d.innerHTML;
    }
    function formatDate(iso) {
        if (!iso) return '—';
        try {
            return new Intl.DateTimeFormat('id-ID', {
                day: '2-digit', month: 'long', year: 'numeric',
                hour: '2-digit', minute: '2-digit',
                timeZone: 'Asia/Jakarta'
            }).format(new Date(iso));
        } catch { return iso; }
    }
    function relativeTime(iso) {
        if (!iso) return '';
        const diff = Math.floor((Date.now() - new Date(iso).getTime()) / 1000);
        if (diff < 60)    return `${diff}d lalu`;
        if (diff < 3600)  return `${Math.floor(diff / 60)}m lalu`;
        if (diff < 86400) return `${Math.floor(diff / 3600)}j lalu`;
        return `${Math.floor(diff / 86400)}h lalu`;
    }
    function initials(name) {
        const parts = (name || 'A').trim().split(/\s+/);
        return (parts[0][0] + (parts[1]?.[0] || '')).toUpperCase();
    }

    const kategoriLabel = {
        info: 'Informasi', layanan: 'Layanan', pengaduan: 'Pengaduan',
        saran: 'Saran', lainnya: 'Lainnya'
    };

    // ── Load List ────────────────────────────────────────────────────────────
    function loadList() {
        const listEl = document.getElementById('pesan-list');
        listEl.innerHTML = `<div class="py-12 flex items-center justify-center"><span class="w-6 h-6 border-2 border-primary border-t-transparent rounded-full animate-spin"></span></div>`;

        const fd = new FormData();
        fd.append('page', page);
        fd.append('search', search);
        fd.append('read_filter', filter);

        fetch(base + '/admin/ajax/list-pesan', { method: 'POST', body: fd, credentials: 'same-origin' })
            .then(r => {
                if (!r.ok && r.status === 401) {
                    toast('Sesi habis. Mengalihkan ke login…', false);
                    setTimeout(() => { window.location.href = base + '/admin/login'; }, 1800);
                    throw new Error('Unauthorized');
                }
                return r.json();
            })
            .then(json => {
                if (!json.success) { toast(json.message || 'Gagal memuat pesan.', false); return; }

                // Update stats
                document.getElementById('stat-total').textContent   = json.stat_total  ?? '—';
                document.getElementById('stat-baru').textContent    = json.stat_baru   ?? '—';
                document.getElementById('stat-dibaca').textContent  = json.stat_dibaca ?? '—';

                hasNext = !!json.has_next;
                hasPrev = !!json.has_prev;
                document.getElementById('btn-prev')?.toggleAttribute('disabled', !hasPrev);
                document.getElementById('btn-next')?.toggleAttribute('disabled', !hasNext);
                document.getElementById('pesan-meta').textContent =
                    json.total ? `${json.total} pesan ditemukan` : '0 pesan';

                if (!json.data || json.data.length === 0) {
                    listEl.innerHTML = `
                        <div class="flex flex-col items-center justify-center py-16 gap-3 text-center px-4">
                            <span class="material-symbols-outlined text-4xl text-ink-dim/20">inbox</span>
                            <p class="text-ink-dim font-body-md text-sm">Belum ada pesan masuk.</p>
                        </div>`;
                    return;
                }

                listEl.innerHTML = json.data.map(item => {
                    const isUnread = !item.is_read;
                    const isActive = item.id === activePesanId;
                    return `
                    <button type="button"
                        class="pesan-item w-full text-left px-4 py-4 border-b border-line hover:bg-surface-container-high transition-colors ${isActive ? 'bg-surface-container-high border-l-2 border-l-primary' : ''} flex items-start gap-3"
                        data-id="${esc(item.id)}">
                        <div class="w-9 h-9 rounded-full bg-primary/20 flex items-center justify-center shrink-0 mt-0.5">
                            <span class="font-bold text-primary text-sm">${esc(initials(item.nama))}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <span class="font-body-md text-sm ${isUnread ? 'font-semibold text-ink' : 'text-ink-dim'} truncate">${esc(item.nama)}</span>
                                <span class="font-label-mono text-[10px] text-ink-dim shrink-0">${relativeTime(item.created_at)}</span>
                            </div>
                            <div class="flex items-center gap-2 mt-0.5">
                                ${isUnread ? '<span class="w-1.5 h-1.5 rounded-full bg-amber-400 shrink-0"></span>' : ''}
                                <span class="text-ink-dim text-xs truncate">${esc((item.pesan || '').substring(0, 60))}…</span>
                            </div>
                            <span class="inline-block mt-1 text-[9px] font-label-mono px-1.5 py-0.5 rounded-full ${isUnread ? 'bg-amber-400/15 text-amber-400' : 'bg-surface-container text-ink-dim'}">${isUnread ? 'BARU' : 'DIBACA'}</span>
                        </div>
                    </button>`;
                }).join('');

                // Attach click handlers
                listEl.querySelectorAll('.pesan-item').forEach(btn => {
                    btn.addEventListener('click', () => openPesan(btn.dataset.id));
                });
            })
            .catch(err => {
                if (err.message !== 'Unauthorized') {
                    listEl.innerHTML = `<div class="py-12 text-center text-ink-dim text-sm">Gagal memuat pesan.</div>`;
                }
            });
    }

    // ── Open Pesan Detail ────────────────────────────────────────────────────
    async function openPesan(id) {
        activePesanId = id;
        // Mark item active in list
        document.querySelectorAll('.pesan-item').forEach(btn => {
            const isActive = btn.dataset.id === id;
            btn.classList.toggle('bg-surface-container-high', isActive);
            btn.classList.toggle('border-l-2', isActive);
            btn.classList.toggle('border-l-primary', isActive);
        });

        // Show detail panel, hide empty
        document.getElementById('detail-empty').classList.add('hidden');
        const detailContent = document.getElementById('detail-content');
        detailContent.classList.remove('hidden');
        detailContent.classList.add('flex');

        // Show skeleton loading
        document.getElementById('detail-pesan').textContent = 'Memuat…';

        const fd = new FormData();
        fd.append('id', id);

        try {
            const res  = await fetch(base + '/admin/ajax/get-pesan', { method: 'POST', body: fd, credentials: 'same-origin' });
            const json = await res.json();

            if (!json.success) { toast(json.message || 'Gagal memuat pesan.', false); return; }

            const d = json.data;
            activePesanNama = d.nama || '';

            document.getElementById('detail-initials').textContent = initials(d.nama);
            document.getElementById('detail-nama').textContent     = d.nama || '—';
            document.getElementById('detail-kontak').textContent   = d.kontak || '—';
            document.getElementById('detail-pesan').textContent    = d.pesan || '—';
            document.getElementById('detail-waktu').textContent    = formatDate(d.created_at);

            const katLabel = kategoriLabel[d.kategori] || d.kategori || '—';
            document.getElementById('detail-kategori').textContent = katLabel;

            const badge = document.getElementById('detail-badge');
            if (d.is_read) {
                badge.textContent  = 'DIBACA';
                badge.className    = 'text-[10px] font-label-mono px-2 py-0.5 rounded-full bg-surface-container text-ink-dim';
            } else {
                badge.textContent  = 'BARU';
                badge.className    = 'text-[10px] font-label-mono px-2 py-0.5 rounded-full bg-amber-400/15 text-amber-400';
            }

            // Update toggle button state
            const toggleBtn = document.getElementById('btn-toggle-read');
            toggleBtn.dataset.id     = d.id;
            toggleBtn.dataset.isRead = d.is_read ? '1' : '0';
            const toggleIcon = toggleBtn.querySelector('span');
            toggleIcon.textContent = d.is_read ? 'mark_email_unread' : 'mark_email_read';
            toggleBtn.title        = d.is_read ? 'Tandai Belum Dibaca' : 'Tandai Sudah Dibaca';

            // Refresh list to update badge
            loadList();

        } catch (err) {
            toast('Gagal memuat isi pesan.', false);
        }
    }

    // ── Toggle Read Status ────────────────────────────────────────────────────
    document.getElementById('btn-toggle-read')?.addEventListener('click', async function () {
        const id     = this.dataset.id;
        const isRead = this.dataset.isRead === '1';
        if (!id) return;

        const fd = new FormData();
        fd.append('id', id);
        fd.append('is_read', isRead ? '0' : '1');

        try {
            const res  = await fetch(base + '/admin/ajax/update-pesan', { method: 'POST', body: fd, credentials: 'same-origin' });
            const json = await res.json();
            if (!json.success) { toast(json.message || 'Gagal memperbarui status.', false); return; }
            toast(isRead ? 'Pesan ditandai belum dibaca.' : 'Pesan ditandai sudah dibaca.');
            // Re-open to refresh detail
            openPesan(id);
        } catch {
            toast('Gagal terhubung ke server atau terjadi kesalahan internal. Periksa koneksi internet Anda dan coba lagi.', false);
        }
    });

    // ── Delete Pesan ──────────────────────────────────────────────────────────
    const hapusModal = document.getElementById('modal-hapus-pesan');

    function showHapusModal() {
        if (!activePesanId) return;
        deletePesanId = activePesanId;
        document.getElementById('hapus-pesan-nama').textContent = activePesanNama || 'pengirim';
        hapusModal.classList.remove('hidden');
        hapusModal.classList.add('flex');
    }

    function closeHapusModal() {
        hapusModal.classList.add('hidden');
        hapusModal.classList.remove('flex');
        deletePesanId = null;
    }

    document.getElementById('btn-delete-pesan')?.addEventListener('click', showHapusModal);
    document.getElementById('hapus-pesan-batal')?.addEventListener('click', closeHapusModal);
    document.getElementById('modal-hapus-pesan-backdrop')?.addEventListener('click', closeHapusModal);
    document.getElementById('hapus-pesan-ya')?.addEventListener('click', async function () {
        if (!deletePesanId) return;
        const btn = this;
        btn.disabled = true;

        const fd = new FormData();
        fd.append('id', deletePesanId);
        fd.append('csrf_token', csrf);

        try {
            const res  = await fetch(base + '/admin/ajax/delete-pesan', { method: 'POST', body: fd, credentials: 'same-origin' });
            const json = await res.json();
            if (!json.success) { toast(json.message || 'Gagal menghapus pesan.', false); return; }
            toast(json.message || 'Pesan berhasil dihapus.');
            closeHapusModal();

            // Reset detail panel ke empty state
            activePesanId = null;
            activePesanNama = '';
            document.getElementById('detail-content').classList.add('hidden');
            document.getElementById('detail-content').classList.remove('flex');
            document.getElementById('detail-empty').classList.remove('hidden');

            // Reset pagination ke halaman 1 lalu muat ulang daftar
            page = 1;
            loadList();
        } catch {
            toast('Gagal terhubung ke server atau terjadi kesalahan internal. Periksa koneksi internet Anda dan coba lagi.', false);
        } finally {
            btn.disabled = false;
        }
    });

    // ── Search ────────────────────────────────────────────────────────────────
    document.getElementById('search-pesan')?.addEventListener('input', (e) => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            search = e.target.value.trim();
            page   = 1;
            loadList();
        }, 300);
    });

    // ── Filter ────────────────────────────────────────────────────────────────
    document.getElementById('filter-pesan')?.addEventListener('click', (e) => {
        const btn = e.target.closest('.filter-btn');
        if (!btn) return;
        document.querySelectorAll('.filter-btn').forEach(b => {
            b.classList.remove('bg-surface-2', 'text-ink', 'border-line-strong');
            b.classList.add('bg-transparent', 'text-ink-dim', 'border-transparent');
        });
        btn.classList.remove('bg-transparent', 'text-ink-dim', 'border-transparent');
        btn.classList.add('bg-surface-2', 'text-ink', 'border-line-strong');
        filter = btn.dataset.filter;
        page   = 1;
        loadList();
    });

    // ── Reset filter ────────────────────────────────────────────────────────────
    document.getElementById('btn-reset-filter')?.addEventListener('click', () => {
        document.getElementById('search-pesan').value = '';
        search = '';
        document.querySelector('#filter-pesan .filter-btn[data-filter="all"]')?.click();
    });

    // ── Pagination ────────────────────────────────────────────────────────────
    document.getElementById('btn-prev')?.addEventListener('click', () => { if (hasPrev) { page--; loadList(); } });
    document.getElementById('btn-next')?.addEventListener('click', () => { if (hasNext) { page++; loadList(); } });

    // ── Init ──────────────────────────────────────────────────────────────────
    loadList();
})();
</script>
<?php require __DIR__ . '/../partials/footer.php'; ?>
