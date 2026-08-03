<?php
$pageTitle = 'Kelola Potensi';
$activeNav = 'kelola-potensi';
require __DIR__ . '/../partials/header.php';
?>
<div class="flex flex-col w-full">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6 pt-10 px-container-pad-mobile lg:px-container-pad-desktop">
        <div class="flex flex-col gap-2 max-w-2xl">
            <h1 class="font-h1-mobile lg:font-h1 text-h1-mobile lg:text-h1 text-ink">Kelola Potensi</h1>
            <p class="font-body-lg text-body-lg text-ink-dim max-w-2xl">Kelola potensi unggulan desa meliputi pertanian, wisata, UMKM, dan peternakan di Pekon Air Naningan.</p>
        </div>
        <button class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-primary hover:bg-primary-fixed-dim text-on-primary rounded-full transition-all duration-300 font-label-mono text-label-mono uppercase tracking-widest shadow-lg shadow-primary/20 shrink-0" id="btn-create">
            <span class="material-symbols-outlined text-[18px]">add</span>
            Tambah Potensi
        </button>
    </div>

    <!-- Main Content -->
    <div class="flex-1 px-container-pad-mobile lg:px-container-pad-desktop pt-10 pb-section-v-desktop">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            
            <!-- Sidebar Stats -->
            <div class="lg:col-span-1 flex flex-col gap-6">
                <!-- Total Card -->
                <div class="bg-surface-2 rounded-2xl p-6 border border-line flex flex-col gap-4">
                    <div class="flex items-center justify-between">
                        <span class="font-label-mono text-label-mono text-ink-dim uppercase">Total Potensi</span>
                        <div class="w-8 h-8 rounded-full bg-surface-container flex items-center justify-center border border-line">
                            <span class="material-symbols-outlined text-gold-soft text-[16px]">emoji_objects</span>
                        </div>
                    </div>
                    <div class="font-h1 text-h1 text-ink" id="total-count">0</div>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="inline-flex items-center px-2 py-1 rounded bg-surface text-ink-dim font-label-mono text-[10px]">4 Kategori</span>
                    </div>
                </div>

                <!-- Filter -->
                <div class="bg-surface-container rounded-2xl p-6 border border-line flex flex-col gap-4">
                    <div class="flex items-center gap-3 text-gold-soft">
                        <span class="material-symbols-outlined">filter_list</span>
                        <h3 class="font-h3 text-h3">Filter</h3>
                    </div>
                    <div class="flex flex-col gap-2">
                        <button class="filter-kategori active text-left py-2 px-3 rounded-lg bg-surface-2 text-ink border border-primary text-sm font-label-mono" data-kategori="">Semua</button>
                        <button class="filter-kategori text-left py-2 px-3 rounded-lg bg-surface text-ink-dim border border-line hover:bg-surface-2 text-sm font-label-mono transition-colors" data-kategori="pertanian">Pertanian</button>
                        <button class="filter-kategori text-left py-2 px-3 rounded-lg bg-surface text-ink-dim border border-line hover:bg-surface-2 text-sm font-label-mono transition-colors" data-kategori="wisata">Wisata</button>
                        <button class="filter-kategori text-left py-2 px-3 rounded-lg bg-surface text-ink-dim border border-line hover:bg-surface-2 text-sm font-label-mono transition-colors" data-kategori="umkm">UMKM</button>
                        <button class="filter-kategori text-left py-2 px-3 rounded-lg bg-surface text-ink-dim border border-line hover:bg-surface-2 text-sm font-label-mono transition-colors" data-kategori="peternakan">Peternakan</button>
                    </div>
                </div>
            </div>

            <!-- Main Table -->
            <div class="lg:col-span-3">
                <div class="bg-surface rounded-2xl border border-line overflow-hidden flex flex-col shadow-md">
                    
                    <!-- Search Bar -->
                    <div class="p-4 border-b border-line flex items-center justify-between bg-surface-container/50">
                        <div class="relative w-64">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-ink-dim text-[18px]">search</span>
                            <input id="search-input" class="w-full bg-surface-container-high text-ink font-body-md text-sm rounded-lg pl-10 pr-4 py-2 border border-line focus:outline-none focus:border-gold-soft transition-colors placeholder:text-ink-dim/50" placeholder="Cari potensi..." type="text"/>
                        </div>
                        <button type="button" id="btn-reset-filter" class="shrink-0 flex items-center gap-1.5 px-3 py-2 rounded-lg bg-surface border border-line text-ink-dim hover:text-ink hover:border-line-strong font-label-mono text-[10px] uppercase tracking-wider transition-colors" title="Reset semua filter">
                            <span class="material-symbols-outlined text-[15px]">refresh</span> Reset
                        </button>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto flex-1">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-line-strong bg-surface-container/30">
                                    <th class="py-4 px-6 font-label-mono text-label-mono text-ink-dim uppercase tracking-wider font-normal">Nama Potensi</th>
                                    <th class="py-4 px-6 font-label-mono text-label-mono text-ink-dim uppercase tracking-wider font-normal">Kategori</th>
                                    <th class="py-4 px-6 font-label-mono text-label-mono text-ink-dim uppercase tracking-wider font-normal">Kapasitas</th>
                                    <th class="py-4 px-6 font-label-mono text-label-mono text-ink-dim uppercase tracking-wider font-normal">Status</th>
                                    <th class="py-4 px-6 font-label-mono text-label-mono text-ink-dim uppercase tracking-wider font-normal text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="table-body" class="divide-y divide-line">
                                <tr>
                                    <td colspan="5" class="py-20 text-center">
                                        <div class="flex items-center justify-center">
                                            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-primary"></div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="p-4 border-t border-line flex items-center justify-between bg-surface-container/30">
                        <span id="pagination-info" class="font-body-md text-[13px] text-ink-dim">Memuat...</span>
                        <div class="flex items-center gap-1">
                            <button id="btn-prev" class="w-8 h-8 flex items-center justify-center rounded bg-surface-container border border-line text-ink-dim disabled:opacity-50 disabled:cursor-not-allowed hover:border-gold-soft transition-colors" disabled>
                                <span class="material-symbols-outlined text-[16px]">chevron_left</span>
                            </button>
                            <button id="btn-next" class="w-8 h-8 flex items-center justify-center rounded bg-surface-container border border-line text-ink-dim disabled:opacity-50 disabled:cursor-not-allowed hover:border-gold-soft transition-colors" disabled>
                                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Form -->
<div class="modal fade" id="modalForm" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-surface border-2 border-line rounded-3xl shadow-2xl overflow-hidden">
            <div class="modal-header border-b border-line px-8 py-6 bg-surface-container-lowest">
                <h5 class="modal-title font-h3 text-h3 text-ink" id="modalFormLabel">Tambah Potensi</h5>
                <button type="button" class="text-ink-dim hover:text-ink transition-colors" data-bs-dismiss="modal" aria-label="Close">
                    <span class="material-symbols-outlined text-[24px]">close</span>
                </button>
            </div>
            <div class="modal-body px-8 py-6 space-y-6 max-h-[70vh] overflow-y-auto">
                <input type="hidden" id="potensi-id" value="">
                
                <div class="space-y-2">
                    <label class="font-body-md text-[13px] text-ink-dim block">Nama Potensi <span class="text-error">*</span></label>
                    <input id="potensi-nama" class="w-full bg-surface-container border border-line rounded-lg px-4 py-3 text-ink font-body-md focus:outline-none focus:border-primary transition-colors" type="text" placeholder="Kopi Robusta Air Naningan" required>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="font-body-md text-[13px] text-ink-dim block">Kategori <span class="text-error">*</span></label>
                        <select id="potensi-kategori" class="w-full bg-surface-container border border-line rounded-lg px-4 py-3 text-ink font-body-md focus:outline-none focus:border-primary appearance-none cursor-pointer" required>
                            <option value="">Pilih Kategori</option>
                            <option value="pertanian">Pertanian</option>
                            <option value="wisata">Wisata</option>
                            <option value="umkm">UMKM</option>
                            <option value="peternakan">Peternakan</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="font-body-md text-[13px] text-ink-dim block">Kapasitas</label>
                        <input id="potensi-kapasitas" class="w-full bg-surface-container border border-line rounded-lg px-4 py-3 text-ink font-body-md focus:outline-none focus:border-primary" type="text" placeholder="50 ton/tahun">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="font-body-md text-[13px] text-ink-dim block">Deskripsi <span class="text-error">*</span></label>
                    <textarea id="potensi-deskripsi" class="w-full bg-surface-container border border-line rounded-lg px-4 py-3 text-ink font-body-md focus:outline-none focus:border-primary resize-none" rows="4" placeholder="Deskripsikan potensi ini secara detail" required></textarea>
                </div>

                <div class="space-y-2">
                    <label class="font-body-md text-[13px] text-ink-dim block">URL Foto</label>
                    <input id="potensi-foto" class="w-full bg-surface-container border border-line rounded-lg px-4 py-3 text-ink font-body-md focus:outline-none focus:border-primary" type="url" placeholder="https://example.com/foto.jpg">
                </div>

                <div class="space-y-2">
                    <label class="font-body-md text-[13px] text-ink-dim block">Status</label>
                    <select id="potensi-status" class="w-full bg-surface-container border border-line rounded-lg px-4 py-3 text-ink font-body-md focus:outline-none focus:border-primary appearance-none cursor-pointer">
                        <option value="aktif">Aktif</option>
                        <option value="berkembang">Berkembang</option>
                        <option value="potensial">Potensial</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer border-t border-line px-8 py-6 bg-surface-container-lowest flex justify-end gap-3">
                <button type="button" class="px-6 py-2.5 rounded-full border border-line text-ink hover:bg-surface-2 transition-colors font-label-mono text-[11px] uppercase tracking-widest" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="btn-save" class="px-6 py-2.5 rounded-full bg-primary text-on-primary hover:bg-primary-fixed transition-colors font-label-mono text-[11px] uppercase tracking-widest shadow-lg shadow-primary/20">
                    Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentPage = 1;
let currentSearch = '';
let currentKategori = '';

function loadData() {
    const tableBody = document.getElementById('table-body');
    tableBody.innerHTML = '<tr><td colspan="5" class="py-20 text-center"><div class="flex items-center justify-center"><div class="animate-spin rounded-full h-10 w-10 border-b-2 border-primary"></div></div></td></tr>';

    const formData = new FormData();
    formData.append('page', currentPage);
    formData.append('search', currentSearch);
    formData.append('kategori', currentKategori);

    fetch('/kkn-air-naningan/admin/ajax/list-potensi.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            renderTable(data.data);
            updatePagination(data);
            document.getElementById('total-count').textContent = data.total;
        }
    });
}

function renderTable(items) {
    const tableBody = document.getElementById('table-body');
    if (items.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="5" class="py-20 text-center text-ink-dim font-body-md">Tidak ada potensi ditemukan</td></tr>';
        return;
    }

    tableBody.innerHTML = items.map(item => {
        const kategoriColor = {
            'pertanian': 'bg-primary',
            'wisata': 'bg-secondary',
            'umkm': 'bg-tertiary',
            'peternakan': 'bg-outline'
        };
        const dotColor = kategoriColor[item.kategori] || 'bg-surface-variant';
        
        const statusColor = {
            'aktif': 'bg-surface-2 text-ink',
            'berkembang': 'bg-secondary-container/20 text-on-secondary-container',
            'potensial': 'bg-tertiary-container/20 text-tertiary-fixed-dim'
        };
        const statusBg = statusColor[item.status] || 'bg-surface-container text-ink-dim';
        
        return `
            <tr class="hover:bg-surface-2 transition-colors group">
                <td class="py-4 px-6">
                    <div class="flex flex-col">
                        <span class="font-body-md text-body-md text-ink font-medium group-hover:text-gold-soft transition-colors">${item.nama}</span>
                        <span class="font-body-md text-[13px] text-ink-dim line-clamp-1 mt-1">${item.deskripsi || ''}</span>
                    </div>
                </td>
                <td class="py-4 px-6">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-surface-container border border-line text-ink-dim font-label-mono text-[10px] uppercase">
                        <span class="w-1.5 h-1.5 rounded-full ${dotColor}"></span>
                        ${item.kategori_label || item.kategori}
                    </span>
                </td>
                <td class="py-4 px-6">
                    <span class="font-body-md text-[13px] text-ink">${item.kapasitas || '-'}</span>
                </td>
                <td class="py-4 px-6">
                    <span class="inline-flex items-center px-2 py-1 rounded-full ${statusBg} border border-line font-label-mono text-[10px] uppercase">${item.status || 'aktif'}</span>
                </td>
                <td class="py-4 px-6 text-right">
                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button onclick="editItem('${item.id}')" class="p-1.5 rounded-md hover:bg-surface-container-high text-ink-dim hover:text-primary transition-colors" title="Edit">
                            <span class="material-symbols-outlined text-[18px]">edit</span>
                        </button>
                        <button onclick="deleteItem('${item.id}', '${item.nama.replace(/'/g, "\\'")}')" class="p-1.5 rounded-md hover:bg-surface-container-high text-ink-dim hover:text-danger transition-colors" title="Hapus">
                            <span class="material-symbols-outlined text-[18px]">delete</span>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

function updatePagination(data) {
    document.getElementById('pagination-info').textContent = 
        `Menampilkan ${((data.page - 1) * 10) + 1}-${Math.min(data.page * 10, data.total)} dari ${data.total} potensi`;
    document.getElementById('btn-prev').disabled = !data.has_prev;
    document.getElementById('btn-next').disabled = !data.has_next;
}

function editItem(id) {
    const formData = new FormData();
    formData.append('id', id);

    fetch('/kkn-air-naningan/admin/ajax/get-potensi.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const item = data.data;
            document.getElementById('modalFormLabel').textContent = 'Edit Potensi';
            document.getElementById('potensi-id').value = item.id;
            document.getElementById('potensi-nama').value = item.nama;
            document.getElementById('potensi-kategori').value = item.kategori;
            document.getElementById('potensi-kapasitas').value = item.kapasitas || '';
            document.getElementById('potensi-deskripsi').value = item.deskripsi;
            document.getElementById('potensi-foto').value = item.foto || '';
            document.getElementById('potensi-status').value = item.status || 'aktif';
            
            const modal = new bootstrap.Modal(document.getElementById('modalForm'));
            modal.show();
        }
    });
}

function deleteItem(id, nama) {
    if (!confirm(`Yakin ingin menghapus potensi "${nama}"?`)) return;

    const formData = new FormData();
    formData.append('id', id);

    fetch('/kkn-air-naningan/admin/ajax/delete-potensi.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        showToast(data.message, data.success ? 'success' : 'error');
        if (data.success) loadData();
    });
}

function saveItem() {
    const formData = new FormData();
    formData.append('id', document.getElementById('potensi-id').value);
    formData.append('nama', document.getElementById('potensi-nama').value);
    formData.append('kategori', document.getElementById('potensi-kategori').value);
    formData.append('kapasitas', document.getElementById('potensi-kapasitas').value);
    formData.append('deskripsi', document.getElementById('potensi-deskripsi').value);
    formData.append('foto', document.getElementById('potensi-foto').value);
    formData.append('status', document.getElementById('potensi-status').value);

    fetch('/kkn-air-naningan/admin/ajax/store-potensi.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        showToast(data.message, data.success ? 'success' : 'error');
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('modalForm')).hide();
            loadData();
        }
    });
}

function showToast(message, type = 'success') {
    window.showAdminToast(message, type !== 'error');
}

function hideToast() {
    window.showAdminToast('', true);
}

document.addEventListener('DOMContentLoaded', () => {
    loadData();

    document.getElementById('btn-create').addEventListener('click', () => {
        document.getElementById('modalFormLabel').textContent = 'Tambah Potensi';
        document.getElementById('potensi-id').value = '';
        document.getElementById('potensi-nama').value = '';
        document.getElementById('potensi-kategori').value = '';
        document.getElementById('potensi-kapasitas').value = '';
        document.getElementById('potensi-deskripsi').value = '';
        document.getElementById('potensi-foto').value = '';
        document.getElementById('potensi-status').value = 'aktif';
        
        const modal = new bootstrap.Modal(document.getElementById('modalForm'));
        modal.show();
    });

    document.getElementById('btn-save').addEventListener('click', saveItem);

    document.getElementById('search-input').addEventListener('input', (e) => {
        currentSearch = e.target.value;
        currentPage = 1;
        loadData();
    });

    document.querySelectorAll('.filter-kategori').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.filter-kategori').forEach(b => {
                b.classList.remove('active', 'border-primary', 'bg-surface-2', 'text-ink');
                b.classList.add('border-line', 'text-ink-dim');
            });
            btn.classList.add('active', 'border-primary', 'bg-surface-2', 'text-ink');
            btn.classList.remove('border-line', 'text-ink-dim');
            currentKategori = btn.dataset.kategori;
            currentPage = 1;
            loadData();
        });
    });

    document.getElementById('btn-reset-filter').addEventListener('click', () => {
        document.getElementById('search-input').value = '';
        currentSearch = '';
        document.querySelector('.filter-kategori[data-kategori=""]')?.click();
    });

    document.getElementById('btn-prev').addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            loadData();
        }
    });

    document.getElementById('btn-next').addEventListener('click', () => {
        currentPage++;
        loadData();
    });
});
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
