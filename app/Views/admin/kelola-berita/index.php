<?php
/* ======================================================
   HALAMAN KELOLA BERITA (MENULIS & MENGATUR ARTIKEL)

   Halaman ini adalah "meja kerja redaksi" panel admin.
   Dari sini admin bisa:
   - Melihat jumlah berita (total, terbit, draft),
   - Menulis berita baru atau mengedit berita lama,
   - Menghapus berita,
   - Melihat pratinjau (preview) tampilan berita.

   Data berita disimpan di file JSON dan dipanggil melalui
   Model Berita. Daftar berita TIDAK dirender langsung oleh
   PHP — tabelnya dikosongkan lalu diisi oleh JavaScript
   melalui AJAX (lihat blok <script> di bagian bawah).

   Variabel yang dipakai:
   - $kategoriList : daftar kategori berita untuk dropdown
   - $csrf         : token pengaman (CSRF) untuk form
====================================================== */
$pageTitle = 'Kelola Berita';
$activeNav = 'kelola-berita';
$base = defined('APP_BASE') ? APP_BASE : '';
$csrf = $_SESSION['csrf_token'] ?? '';
require __DIR__ . '/../partials/header.php';
?>
<div class="flex flex-col w-full">

    <!-- ======================================================
         JUDUL HALAMAN + TOMBOL "TULIS BERITA BARU"

         Bagian paling atas halaman. Menampilkan judul
         "Kelola Berita" dan tombol utama berwarna emas.
         Saat tombol "Tulis Berita Baru" diklik, JavaScript
         akan membuka modal editor kosong (id btn-create-news
         → openEditor('create')).
    ====================================================== -->
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6 pt-10 px-container-pad-mobile lg:px-container-pad-desktop">
        <div class="flex flex-col gap-2 max-w-2xl">
            <h1 class="font-h1-mobile lg:font-h1 text-h1-mobile lg:text-h1 text-ink">Kelola Berita</h1>
            <p class="font-body-lg text-body-lg text-ink-dim">Publikasikan informasi terkini, pengumuman desa, dan liputan kegiatan Pekon Air Naningan.</p>
        </div>
        <button id="btn-create-news" class="bg-primary text-on-primary hover:bg-primary-fixed transition-colors rounded-full px-6 py-3 font-label-mono text-label-mono uppercase tracking-widest flex items-center justify-center gap-2 flex-shrink-0 shadow-xl shadow-primary/10">
            <span class="material-symbols-outlined text-[20px]">add</span>
            Tulis Berita Baru
        </button>
    </div>

    <!-- News List -->
    <div class="w-full px-container-pad-mobile lg:px-container-pad-desktop pt-10 pb-section-v-desktop flex flex-col gap-6">

        <!-- ======================================================
             KARTU STATISTIK BERITA (3 KARTU ANGKA)

             Tiga kotak ringkasan yang awalnya bertuliskan "—"
             dan akan diisi oleh JavaScript setelah data datang
             dari server (lihat fungsi loadList di <script>):
             - id="stat-total"  → jumlah seluruh berita
             - id="stat-terbit" → jumlah berita berstatus Terbit
             - id="stat-draft"  → jumlah berita berstatus Draft

             Angkanya diambil dari jawaban JSON endpoint
             list-berita (kolom stat_total, stat_terbit, stat_draft).
        ====================================================== -->
        <!-- Stats Card -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-surface-2 rounded-2xl p-5 border border-line flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-primary text-[22px]">newspaper</span>
                </div>
                <div>
                    <div class="font-label-mono text-label-mono text-ink-dim uppercase text-[10px] mb-0.5">Total Berita</div>
                    <div class="font-h2 text-h2 text-ink leading-none" id="stat-total">—</div>
                </div>
            </div>
            <div class="bg-surface-2 rounded-2xl p-5 border border-line flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-primary text-[22px]">check_circle</span>
                </div>
                <div>
                    <div class="font-label-mono text-label-mono text-ink-dim uppercase text-[10px] mb-0.5">Status Terbit</div>
                    <div class="font-h2 text-h2 text-ink leading-none" id="stat-terbit">—</div>
                </div>
            </div>
            <div class="bg-surface-2 rounded-2xl p-5 border border-line flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-surface-container border border-line flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-ink-dim text-[22px]">cancel</span>
                </div>
                <div>
                    <div class="font-label-mono text-label-mono text-ink-dim uppercase text-[10px] mb-0.5">Status Draft</div>
                    <div class="font-h2 text-h2 text-ink-dim leading-none" id="stat-draft">—</div>
                </div>
            </div>
        </div>

        <!-- ======================================================
             GRAFIK STATISTIK BERITA (SEBARAN KATEGORI & STATUS)

             Dua grafik yang digambar oleh pustaka ApexCharts:
             1) "Sebaran Kategori" (id chart-kategori) → grafik
                batang berapa banyak berita di tiap kategori.
             2) "Status Publikasi" (id chart-status) → grafik
                donat perbandingan berita Terbit vs Draft.

             Data grafik diminta lewat AJAX ke endpoint
             admin/ajax/chart-berita, lalu digambar oleh
             JavaScript (fungsi loadChart). Kotak grafik masih
             kosong sampai data datang.
        ====================================================== -->
        <!-- Statistik Berita -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <section class="lg:col-span-2 bg-surface rounded-2xl border border-line p-5 md:p-6" aria-labelledby="berita-category-chart-title">
                <div class="flex items-start justify-between gap-4 mb-2">
                    <div><h2 id="berita-category-chart-title" class="font-h3 text-h3 text-ink">Sebaran Kategori</h2><p class="text-sm text-ink-dim mt-1">Jumlah berita pada setiap kategori.</p></div>
                    <span class="font-label-mono text-[10px] uppercase tracking-widest text-gold-soft">Data Live</span>
                </div>
                <div id="chart-kategori" class="min-h-[280px]"></div>
            </section>
            <section class="bg-surface rounded-2xl border border-line p-5 md:p-6" aria-labelledby="berita-status-chart-title">
                <div><h2 id="berita-status-chart-title" class="font-h3 text-h3 text-ink">Status Publikasi</h2><p class="text-sm text-ink-dim mt-1">Perbandingan berita terbit dan draft.</p></div>
                <div id="chart-status" class="min-h-[280px]"></div>
            </section>
        </div>

        <!-- ======================================================
             KOTAK CARI + FILTER BERITA

             Alat bantu untuk menyaring daftar berita:
             - Kotak "Cari judul berita..." (id search-berita):
               setiap ketikan menunggu 0,3 detik lalu meminta
               ulang data (agar tidak membebani server).
             - Dropdown Kategori (id filter-kategori): isinya
               dibuat lewat foreach pada $kategoriList, satu
               <option> untuk satu kategori.
             - Dropdown Status (id filter-status): pilihan
               tetap Terbit / Draft.
             - Tombol "Reset" (id btn-reset-filter): mengosongkan
               semua filter dan menampilkan semua berita lagi.

             Semua filter dikirim ke endpoint list-berita
             bersama permintaan data.
        ====================================================== -->
        <!-- Filter & Search -->
        <div class="p-5 bg-surface-container rounded-2xl border border-line flex flex-col sm:flex-row gap-4 items-center justify-between">
            <div class="relative w-full sm:w-80">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-ink-dim text-[20px]">search</span>
                <input id="search-berita" class="w-full bg-surface border border-line rounded-lg py-2.5 pl-10 pr-4 text-ink font-body-md focus:outline-none focus:border-primary transition-colors placeholder:text-surface-variant" placeholder="Cari judul berita..." type="text"/>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                <div class="flex items-center gap-2 w-full sm:w-60">
                    <label for="filter-kategori" class="font-label-mono text-[10px] uppercase tracking-widest text-ink-dim whitespace-nowrap">Kategori</label>
                    <select id="filter-kategori" class="flex-1 bg-surface border border-line rounded-lg px-3 py-2.5 text-ink font-body-md focus:outline-none focus:border-primary appearance-none">
                        <option value="">Semua</option>
                        <?php foreach ($kategoriList as $kategori): ?>
                        <option value="<?= htmlspecialchars($kategori, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($kategori, ENT_QUOTES, 'UTF-8') ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex items-center gap-2 w-full sm:w-48">
                    <label for="filter-status" class="font-label-mono text-[10px] uppercase tracking-widest text-ink-dim whitespace-nowrap">Status</label>
                    <select id="filter-status" class="flex-1 bg-surface border border-line rounded-lg px-3 py-2.5 text-ink font-body-md focus:outline-none focus:border-primary appearance-none">
                        <option value="">Semua</option>
                        <option value="terbit">Terbit</option>
                        <option value="draft">Draft</option>
                    </select>
                </div>
                <button type="button" id="btn-reset-filter" class="shrink-0 flex items-center gap-1.5 px-4 py-2.5 rounded-lg bg-surface border border-line text-ink-dim hover:text-ink hover:border-line-strong font-label-mono text-[11px] uppercase tracking-wider transition-colors" title="Reset semua filter">
                    <span class="material-symbols-outlined text-[16px]">refresh</span> Reset
                </button>
            </div>
        </div>

        <!-- ======================================================
             BARIS JUDUL KOLOM DAFTAR BERITA

             Sekadar penanda nama kolom (tidak berisi data):
             No, Judul Berita, Kategori, Status, Tanggal, Aksi.
             Baris data aslinya diisi oleh JavaScript ke dalam
             wadah #berita-tbody di bawah (lihat blok <script>).
        ====================================================== -->
        <!-- Table Header -->
        <div class="hidden sm:grid grid-cols-12 gap-4 px-8 py-4 border-b border-line bg-surface-container-lowest font-label-mono text-[10px] text-ink-dim uppercase tracking-widest">
            <div class="col-span-1">No</div>
            <div class="col-span-4">Judul Berita</div>
            <div class="col-span-2">Kategori</div>
            <div class="col-span-2">Status</div>
            <div class="col-span-2 text-right">Tanggal</div>
            <div class="col-span-1 text-right">Aksi</div>
        </div>

        <!-- ======================================================
             WADAH DAFTAR BERITA (ISI DARI JAVASCRIPT)

             Div kosong ini adalah "panggung" tempat daftar
             berita ditampilkan. JavaScript memanggil endpoint
             admin/ajax/list-berita lalu menggambar 1 baris
             kartu untuk setiap berita dengan data kolom:
             - row.judul / row.ringkasan / row.kategori
             - row.status & row.is_published / row.is_scheduled
             - row.tanggal_terbit
             - row.foto_sampul (gambar sampul berita, diambil
               dari folder uploads/ — path digabung dengan $base)
             - tombol Preview (lihat isi) & Hapus per baris
        ====================================================== -->
        <!-- News Items -->
        <div id="berita-tbody" class="divide-y divide-line">
            <!-- Data loaded via JS -->
        </div>

        <!-- ======================================================
             NAVIGASI HALAMAN (PREV / NEXT)

             Daftar berita dibatasi 10 per halaman. Teks
             "Menampilkan X–Y dari Z berita" ada di
             id="berita-meta", tombol Prev (btn-prev) dan
             Next (btn-next) untuk berpindah halaman. Tombol
             akan otomatis mati (disabled) bila sudah di
             halaman pertama/terakhir.
        ====================================================== -->
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
</div>

<!-- ======================================================
     NOTIFIKASI TOAST (PEMBERITAHUAN)

     Saat admin menyimpan / menghapus berita, muncul kotak
     notifikasi kecil di pojok kanan atas (toast) berisi
     pesan dari server, misalnya "Berita berhasil
     ditambahkan." atau "Gagal menyimpan." Kotak toast ini
     dibuat oleh fungsi window.showAdminToast() yang berada
     di file footer admin, jadi semua halaman admin memakai
     notifikasi yang sama.
====================================================== -->
<!-- Toast -->

<!-- ======================================================
     MODAL KONFIRMASI HAPUS BERITA

     Jendela popup (modal) yang muncul saat admin menekan
     tombol hapus. Berfungsi seperti bertanya sekali lagi
     "Anda yakin?" agar berita tidak terhapus tidak sengaja.
     - Judul berita yang akan dihapus ditulis ke id="hapus-nama"
     - Tombol "Batal" (hapus-batal) → menutup modal
     - Tombol "Hapus" (hapus-ya) → mengirim permintaan hapus
       via AJAX ke endpoint admin/ajax/delete-berita bersama
       token CSRF, lalu daftar dimuat ulang.
====================================================== -->
<!-- Modal Hapus -->
<div id="modal-hapus" data-modal class="fixed inset-0 z-[80] hidden items-center justify-center">
    <div id="modal-hapus-backdrop" class="absolute inset-0 bg-scrim/40 backdrop-blur-sm"></div>
    <div class="relative bg-surface rounded-2xl shadow-2xl w-full max-w-sm mx-4 overflow-hidden animate-scale-in">
        <div class="p-6">
            <div class="w-12 h-12 rounded-full bg-danger/10 flex items-center justify-center mb-4">
                <span class="material-symbols-outlined text-danger text-[24px]">delete</span>
            </div>
            <h3 class="font-h3 text-h3 text-ink mb-2">Hapus Berita?</h3>
            <p class="font-body-md text-body-md text-ink-dim mb-6">Tindakan ini tidak dapat dibatalkan. Berita "<span id="hapus-nama" class="font-medium text-ink"></span>" akan dihapus permanen.</p>
            <div class="flex justify-end gap-3">
                <button type="button" id="hapus-batal" data-modal-close class="px-5 py-2.5 rounded-full font-label-mono text-[11px] uppercase bg-surface-2 text-ink">Batal</button>
                <button type="button" id="hapus-ya" class="px-5 py-2.5 rounded-full font-label-mono text-[11px] uppercase bg-danger text-white">Hapus</button>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================
     MODAL EDITOR BERITA (TAMBAH / EDIT DALAM SATU FORM)

     Popup besar berisi formulir lengkap untuk menulis berita
     baru ATAU mengedit berita lama (mode ditentukan lewat
     input tersembunyi id="berita-id": kosong = tambah baru,
     berisi ID = edit).

     Daftar kolom isian (input) dalam formulir:
     - Judul (berita-judul, name="judul")            : wajib
     - Kategori (berita-kategori, name="kategori")   : wajib,
       dengan daftar saran dari foreach $kategoriList
     - Tanggal Publikasi (berita-tanggal, name="tanggal_terbit")
     - Ringkasan (berita-ringkasan, name="ringkasan") : wajib
     - Penulis (berita-penulis, name="penulis")
     - Tag (berita-tags, name="tags")
     - Gambar Sampul (berita-foto-file, name="foto_file"):
       upload foto, wajib di bawah 2MB (diperiksa JavaScript
       dan server). Foto lama tampil di id="berita-foto-old-img"
     - Isi Berita: editor teks (id="rte-berita") dengan
       toolbar format (tebal, miring, subjudul, kutipan,
       daftar, tautan). Hasilnya disalin ke textarea
       tersembunyi name="konten" (berita-konten).

     Tiga tombol di bawah:
     - "Simpan Draft" (btn-save-draft)   → status = draft
     - "Publikasikan" (btn-save-publish) → status = terbit
     Keduanya mengirim formulir via fetch() POST ke endpoint
     admin/ajax/store-berita bersama token CSRF
     (name="csrf_token" diisi nilai $csrf dari PHP).
====================================================== -->
<!-- Modal Editor (Tambah / Edit) -->
<div id="modal-editor" data-modal class="fixed inset-0 z-[70] hidden items-center justify-center">
    <div id="modal-editor-backdrop" class="absolute inset-0 bg-scrim/40 backdrop-blur-sm"></div>
    <div class="relative bg-surface rounded-2xl shadow-2xl w-full max-w-4xl max-h-[92vh] mx-4 flex flex-col overflow-hidden animate-scale-in">
        <form id="form-berita" autocomplete="off" class="flex flex-col min-h-0">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>">
            <input type="hidden" name="id" id="berita-id" value="">
            <input type="hidden" name="status" id="berita-status-input" value="terbit">

            <!-- Editor Header -->
            <div class="px-6 py-4 border-b border-line flex items-center justify-between bg-surface/80">
                <div class="flex items-center gap-3">
                    <span class="w-2 h-2 rounded-full bg-primary animate-pulse hidden" id="editor-pulse"></span>
                    <span class="font-label-mono text-[11px] text-ink uppercase tracking-widest" id="editor-title">Tulis Berita Baru</span>
                </div>
                <button type="button" id="btn-close-editor" class="w-9 h-9 rounded-full flex items-center justify-center text-ink-dim hover:text-ink hover:bg-surface-2 transition-colors" aria-label="Tutup">
                    <span class="material-symbols-outlined text-[22px]">close</span>
                </button>
            </div>

            <!-- Editor Body -->
            <div class="flex-1 overflow-y-auto p-6 md:p-8 space-y-6">
                <!-- Title -->
                <div class="space-y-2">
                    <label class="font-body-md text-[13px] text-ink-dim block" for="berita-judul">Judul Berita</label>
                    <textarea name="judul" id="berita-judul" required class="w-full bg-surface border border-line rounded-xl px-4 py-3 text-h2 font-h2 text-ink focus:outline-none focus:border-primary resize-none placeholder:text-surface-variant leading-tight transition-colors" placeholder="Masukkan judul berita..." rows="2"></textarea>
                </div>

                <!-- Meta Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="font-body-md text-[13px] text-ink-dim block" for="berita-kategori">Kategori</label>
                        <input name="kategori" id="berita-kategori" list="berita-kategori-list" required class="w-full bg-surface border border-line rounded-lg px-4 py-3 text-ink font-body-md focus:outline-none focus:border-primary" placeholder="Contoh: Kegiatan">
                        <datalist id="berita-kategori-list">
                            <?php foreach ($kategoriList as $kategori): ?>
                            <option value="<?= htmlspecialchars($kategori, ENT_QUOTES, 'UTF-8') ?>">
                            <?php endforeach; ?>
                        </datalist>
                    </div>
                    <div class="space-y-2">
                        <label class="font-body-md text-[13px] text-ink-dim block" for="berita-tanggal">Tanggal Publikasi</label>
                        <input name="tanggal_terbit" id="berita-tanggal" required class="w-full bg-surface border border-line rounded-lg px-4 py-3 text-ink font-body-md focus:outline-none focus:border-primary" type="date" value="<?= date('Y-m-d') ?>"/>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="font-body-md text-[13px] text-ink-dim block" for="berita-ringkasan">Ringkasan</label>
                    <textarea name="ringkasan" id="berita-ringkasan" required class="w-full bg-surface border border-line rounded-lg px-4 py-3 text-ink font-body-md focus:outline-none focus:border-primary resize-y" rows="3" placeholder="Ringkasan singkat untuk ditampilkan di kartu..."></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="font-body-md text-[13px] text-ink-dim block" for="berita-penulis">Penulis</label>
                        <input name="penulis" id="berita-penulis" class="w-full bg-surface border border-line rounded-lg px-4 py-3 text-ink font-body-md focus:outline-none focus:border-primary" placeholder="Admin Desa">
                    </div>
                    <div class="space-y-2">
                        <label class="font-body-md text-[13px] text-ink-dim block" for="berita-tags">Tag</label>
                        <input name="tags" id="berita-tags" class="w-full bg-surface border border-line rounded-lg px-4 py-3 text-ink font-body-md focus:outline-none focus:border-primary" placeholder="Contoh: Kopi, Ekonomi">
                    </div>
                </div>

                <!-- Cover Image -->
                <div class="space-y-3">
                    <label class="font-body-md text-[13px] text-ink-dim block">Gambar Sampul</label>
                    <input type="file" name="foto_file" id="berita-foto-file" accept=".jpg,.jpeg,.png,.webp" class="hidden">
                    <div id="berita-foto-trigger" class="w-full h-48 rounded-xl border border-dashed border-outline-variant bg-surface-container hover:bg-surface-2 transition-colors flex flex-col items-center justify-center cursor-pointer group relative overflow-hidden">
                        <img id="berita-foto-new-img" class="absolute inset-0 w-full h-full object-cover hidden" src="" alt="New Cover"/>
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

                <!-- Content Area -->
                <div class="space-y-2 flex-1 flex flex-col">
                    <label class="font-body-md text-[13px] text-ink-dim block" for="rte-berita">Isi Berita</label>
                    <div id="rte-toolbar" role="toolbar" aria-label="Format teks isi berita"
                         class="flex flex-wrap items-center gap-1 bg-surface-2 border border-line-strong border-b-0 rounded-t-xl px-2 py-1.5 select-none">
                        <button type="button" data-cmd="bold" class="rte-btn" title="Tebal" aria-label="Tebal"><span class="material-symbols-outlined text-[18px]">format_bold</span></button>
                        <button type="button" data-cmd="italic" class="rte-btn" title="Miring" aria-label="Miring"><span class="material-symbols-outlined text-[18px]">format_italic</span></button>
                        <button type="button" data-cmd="underline" class="rte-btn" title="Garis bawah" aria-label="Garis bawah"><span class="material-symbols-outlined text-[18px]">format_underlined</span></button>
                        <span class="w-px h-5 bg-line mx-1"></span>
                        <button type="button" data-cmd="formatBlock" data-val="h2" class="rte-btn" title="Subjudul (H2)" aria-label="Subjudul"><span class="material-symbols-outlined text-[18px]">title</span></button>
                        <button type="button" data-cmd="formatBlock" data-val="p" class="rte-btn" title="Paragraf biasa" aria-label="Paragraf biasa"><span class="material-symbols-outlined text-[18px]">format_paragraph</span></button>
                        <button type="button" data-cmd="formatBlock" data-val="blockquote" class="rte-btn" title="Kutipan" aria-label="Kutipan"><span class="material-symbols-outlined text-[18px]">format_quote</span></button>
                        <span class="w-px h-5 bg-line mx-1"></span>
                        <button type="button" data-cmd="insertUnorderedList" class="rte-btn" title="Daftar bullet" aria-label="Daftar bullet"><span class="material-symbols-outlined text-[18px]">format_list_bulleted</span></button>
                        <button type="button" data-cmd="insertOrderedList" class="rte-btn" title="Daftar bernomor" aria-label="Daftar bernomor"><span class="material-symbols-outlined text-[18px]">format_list_numbered</span></button>
                        <button type="button" data-cmd="createLink" class="rte-btn" title="Tautan" aria-label="Tautan"><span class="material-symbols-outlined text-[18px]">link</span></button>
                        <span class="w-px h-5 bg-line mx-1"></span>
                        <button type="button" data-cmd="undo" class="rte-btn" title="Urungkan" aria-label="Urungkan"><span class="material-symbols-outlined text-[18px]">undo</span></button>
                        <button type="button" data-cmd="redo" class="rte-btn" title="Ulangi" aria-label="Ulangi"><span class="material-symbols-outlined text-[18px]">redo</span></button>
                    </div>
                    <div id="rte-berita" contenteditable="true"
                         class="bg-surface border border-line-strong rounded-b-xl p-4 min-h-[300px] text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary prose max-w-none overflow-y-auto"
                         role="textbox" aria-multiline="true" aria-label="Isi berita"></div>
                    <textarea name="konten" id="berita-konten" class="hidden"></textarea>
                    <span class="text-xs text-ink-dim">Enter = paragraf baru · Shift+Enter = baris baru tanpa paragraf.</span>
                </div>
            </div>

            <!-- Editor Footer -->
            <div class="px-6 py-4 border-t border-line bg-surface/80 flex items-center justify-end gap-3">
                <button type="button" id="btn-save-draft" class="text-ink-dim hover:text-ink font-label-mono text-[11px] px-3 py-2 rounded-md hover:bg-surface-2 transition-colors">Simpan Draft</button>
                <button type="button" id="btn-save-publish" class="bg-primary text-on-primary px-5 py-2 rounded-full font-label-mono text-[11px] hover:bg-primary-fixed transition-colors shadow-lg shadow-primary/10">Publikasikan</button>
            </div>
        </form>
    </div>
</div>

<!-- ======================================================
     MODAL PRATINJAU (PREVIEW) BERITA

     Popup yang menampilkan "wajah jadi" sebuah berita
     sebelum dipublikasikan — seperti melihat hasil cetak
     koran. Muncul saat admin mengklik ikon mata pada salah
     satu baris daftar.

     Data diambil lewat AJAX dari endpoint
     admin/ajax/get-berita lalu diisikan ke:
     - pv-status    : label Terbit / Terjadwal / Draft
     - pv-kategori  : nama kategori berita
     - pv-judul     : judul berita
     - pv-meta      : tanggal terbit & penulis
     - pv-foto      : gambar sampul (folder uploads/)
     - pv-konten    : isi berita lengkap

     Dari sini admin bisa langsung "Edit Berita" (pv-edit)
     kembali ke modal editor, atau "Hapus" (pv-hapus).
====================================================== -->
<!-- Modal Preview -->
<div id="modal-preview" data-modal class="fixed inset-0 z-[70] hidden items-center justify-center">
    <div id="modal-preview-backdrop" class="absolute inset-0 bg-scrim/40 backdrop-blur-sm"></div>
    <div class="relative bg-surface rounded-2xl shadow-2xl w-full max-w-3xl max-h-[92vh] mx-4 flex flex-col overflow-hidden animate-scale-in">
        <div class="px-6 py-4 border-b border-line flex items-center justify-between bg-surface/80 shrink-0">
            <span class="font-label-mono text-[11px] text-ink uppercase tracking-widest">Preview Berita</span>
            <button type="button" id="btn-close-preview" class="w-9 h-9 rounded-full flex items-center justify-center text-ink-dim hover:text-ink hover:bg-surface-2 transition-colors" aria-label="Tutup">
                <span class="material-symbols-outlined text-[22px]">close</span>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto p-6 md:p-8">
            <div class="flex flex-wrap items-center gap-2 mb-4">
                <span id="pv-status" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-surface-container text-ink-dim font-label-mono text-[10px]"><span class="material-symbols-outlined text-[14px]">edit_document</span>Draft</span>
                <span id="pv-kategori" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-primary-container/20 text-primary-fixed-dim font-label-mono text-[10px]">Kategori</span>
            </div>
            <h2 id="pv-judul" class="font-h2 text-h2 text-ink leading-tight mb-3"></h2>
            <p id="pv-meta" class="font-body-md text-body-md text-ink-dim mb-6"></p>
            <img id="pv-foto" src="" alt="Sampul berita" class="w-full max-h-80 object-cover rounded-xl border border-line mb-6 hidden"/>
            <div id="pv-konten" class="max-w-none text-ink-dim font-body-lg text-body-lg"></div>
        </div>
        <div class="px-6 py-4 border-t border-line bg-surface/80 flex items-center justify-between gap-3 shrink-0">
            <button type="button" id="pv-hapus" class="text-danger hover:bg-danger/10 font-label-mono text-[11px] px-4 py-2 rounded-full transition-colors flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[16px]">delete</span> Hapus
            </button>
            <div class="flex items-center gap-3">
                <button type="button" id="btn-close-preview-2" class="text-ink-dim hover:text-ink font-label-mono text-[11px] px-3 py-2 rounded-md hover:bg-surface-2 transition-colors">Tutup</button>
                <button type="button" id="pv-edit" class="bg-primary text-on-primary px-5 py-2 rounded-full font-label-mono text-[11px] hover:bg-primary-fixed transition-colors shadow-lg shadow-primary/10 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">edit</span> Edit Berita
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================
     ATURAN TAMPILAN TAMBAHAN (CSS KECIL)

     Gaya pelengkap yang tidak bisa dibuat pakai Tailwind:
     - tombol toolbar editor teks (rte-btn) saat ditekan,
     - tampilan paragraf/judul/kutipan dalam editor dan preview,
     - teks bayangan "Tulis isi berita di sini..." saat
       editor masih kosong.
====================================================== -->
<style>
    .rte-btn { display: inline-flex; align-items: center; justify-content: center; width: 2rem; height: 2rem; border-radius: 0.5rem; color: var(--color-ink-dim, #64748b); transition: background-color .15s, color .15s; }
    .rte-btn:hover { background-color: var(--color-surface-container-highest, #e2e8f0); color: var(--color-ink, #0f172a); }
    .rte-btn[data-active="true"] { background-color: var(--color-primary-container, rgba(31,88,74,.15)); color: var(--color-primary, #1f584a); }
    #rte-berita:empty::before { content: "Tulis isi berita di sini..."; color: var(--color-ink-dim, #64748b); opacity: .6; pointer-events: none; }
    #rte-berita h2 { font-size: 1.4rem; font-weight: 700; margin: .75em 0 .4em; }
    #rte-berita h3 { font-size: 1.15rem; font-weight: 700; margin: .75em 0 .4em; }
    #rte-berita blockquote { border-left: 4px solid var(--color-primary, #1f584a); padding-left: 1rem; margin: .75em 0; font-style: italic; color: var(--color-ink-dim, #64748b); }
    #rte-berita ul { list-style: disc; padding-left: 1.5rem; margin: .5em 0; }
    #rte-berita ol { list-style: decimal; padding-left: 1.5rem; margin: .5em 0; }
    #rte-berita p { margin: .5em 0; }
    #rte-berita a { color: var(--color-primary, #1f584a); text-decoration: underline; }
    #pv-konten h2 { font-size: 1.4rem; font-weight: 700; color: var(--color-ink, #0f172a); margin: 1.2em 0 .5em; }
    #pv-konten h3 { font-size: 1.15rem; font-weight: 700; color: var(--color-ink, #0f172a); margin: 1.1em 0 .4em; }
    #pv-konten blockquote { border-left: 4px solid var(--color-primary, #1f584a); padding-left: 1rem; margin: 1em 0; font-style: italic; color: var(--color-ink-dim, #64748b); }
    #pv-konten ul { list-style: disc; padding-left: 1.5rem; margin: .75em 0; }
    #pv-konten ol { list-style: decimal; padding-left: 1.5rem; margin: .75em 0; }
    #pv-konten a { color: var(--color-primary, #1f584a); text-decoration: underline; }
    #pv-konten img { max-width: 100%; border-radius: .75rem; }
</style>

<script src="<?= htmlspecialchars($base, ENT_QUOTES, 'UTF-8') ?>/assets/js/vendor/apexcharts.min.js"></script>
<script>
(function() {
    const base = <?= json_encode($base, JSON_UNESCAPED_SLASHES) ?>;
    const csrf = <?= json_encode($csrf) ?>;
    let page = 1, search = '', kategori = '', status = '', hasNext = false, hasPrev = false;
    let deleteId = null, searchTimer = null;
    let previewData = null;

    const tbody = document.getElementById('berita-tbody');
    const meta = document.getElementById('berita-meta');
    const btnPrev = document.getElementById('btn-prev');
    const btnNext = document.getElementById('btn-next');
    const form = document.getElementById('form-berita');
    const modalEditor = document.getElementById('modal-editor');
    const modalPreview = document.getElementById('modal-preview');
    const modalHapus = document.getElementById('modal-hapus');

    function toast(msg, success = true) {
        window.showAdminToast(msg, success);
    }

    function mediaUrl(path) {
        if (!path) return '';
        if (path.startsWith('http') || path.startsWith('data:')) return path;
        return base + '/' + path.replace(/^\//, '');
    }

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, char => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
        })[char]);
    }

    // ── Rich text editor: Isi Berita ─────────────────────────────────────────
    const rte = document.getElementById('rte-berita');
    const rteInput = document.getElementById('berita-konten');
    const rteToolbar = document.getElementById('rte-toolbar');

    function normalizeRichHtml(html) {
        const doc = document.createElement('div');
        doc.innerHTML = html;
        doc.querySelectorAll('div').forEach(d => {
            const p = document.createElement('p');
            p.innerHTML = d.innerHTML;
            d.replaceWith(p);
        });
        return doc.innerHTML;
    }

    function syncRte() { rteInput.value = normalizeRichHtml(rte.innerHTML); }

    rteToolbar?.addEventListener('mousedown', (e) => e.preventDefault());

    rteToolbar?.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-cmd]');
        if (!btn) return;
        const cmd = btn.dataset.cmd;
        rte.focus();
        if (cmd === 'createLink') {
            const url = prompt('Alamat tautan (URL):');
            if (url) {
                const safe = String(url).trim().replace(/^javascript:/i, '#');
                document.execCommand('createLink', false, safe);
            }
            return;
        }
        document.execCommand(cmd, false, btn.dataset.val || null);
    });

    rte.addEventListener('input', syncRte);
    rte.addEventListener('blur', syncRte);

    function openModal(el) { el.classList.remove('hidden'); el.classList.add('flex'); }
    function closeModal(el) { el.classList.add('hidden'); el.classList.remove('flex'); }

    // ── Statistik (ApexCharts) ───────────────────────────────────────────────
    function cssVar(name, fallback) {
        return getComputedStyle(document.documentElement).getPropertyValue(name).trim() || fallback;
    }
    const chartColors = {
        primary: cssVar('--color-primary', '#1f584a'),
        tertiary: cssVar('--color-tertiary', '#705d2e'),
        line: cssVar('--color-line', '#d7dcd9'),
        ink: cssVar('--color-ink', '#17201c'),
        inkDim: cssVar('--color-ink-dim', '#5d6b64'),
    };
    let chartKategori = null, chartStatus = null;

    async function loadChart() {
        if (typeof ApexCharts === 'undefined') return;
        try {
            const res = await fetch(base + '/admin/ajax/chart-berita', { method: 'POST', body: new FormData(), credentials: 'same-origin' });
            const json = await res.json();
            if (!json.success) return;

            const katLabels = json.kategori.map(k => k.label);
            const katCounts = json.kategori.map(k => k.count);
            const stValues = [Number((json.status.find(s => s.label === 'Terbit') || {}).count || 0), Number((json.status.find(s => s.label === 'Draft') || {}).count || 0)];

            if (!chartKategori) {
                chartKategori = new ApexCharts(document.getElementById('chart-kategori'), {
                    chart: { type: 'bar', height: 280, toolbar: { show: false }, background: 'transparent', fontFamily: 'Public Sans, sans-serif' },
                    series: [{ name: 'Berita', data: katCounts }], colors: [chartColors.primary],
                    plotOptions: { bar: { borderRadius: 6, columnWidth: '42%', distributed: true } },
                    dataLabels: { enabled: false }, legend: { show: false },
                    xaxis: { categories: katLabels, labels: { style: { colors: chartColors.inkDim, fontSize: '11px' } }, axisBorder: { color: chartColors.line }, axisTicks: { color: chartColors.line } },
                    yaxis: { min: 0, forceNiceScale: true, labels: { style: { colors: chartColors.inkDim }, formatter: value => Number.isInteger(value) ? value : '' } },
                    grid: { borderColor: chartColors.line, strokeDashArray: 4 }, tooltip: { theme: 'dark' }
                });
                chartKategori.render();
            } else {
                chartKategori.updateOptions({ xaxis: { categories: katLabels } });
                chartKategori.updateSeries([{ name: 'Berita', data: katCounts }]);
            }

            if (!chartStatus) {
                chartStatus = new ApexCharts(document.getElementById('chart-status'), {
                    chart: { type: 'donut', height: 280, background: 'transparent', fontFamily: 'Public Sans, sans-serif' },
                    series: stValues, labels: ['Terbit', 'Draft'], colors: [chartColors.primary, chartColors.tertiary],
                    stroke: { width: 2, colors: [chartColors.line] }, dataLabels: { enabled: false },
                    legend: { position: 'bottom', labels: { colors: chartColors.inkDim } },
                    plotOptions: { pie: { donut: { size: '68%', labels: { show: true, total: { show: true, label: 'Total', color: chartColors.inkDim, formatter: () => String(stValues[0] + stValues[1]) }, value: { color: chartColors.ink } } } } },
                    tooltip: { theme: 'dark' }
                });
                chartStatus.render();
            } else {
                chartStatus.updateSeries(stValues);
                chartStatus.updateOptions({ plotOptions: { pie: { donut: { labels: { total: { formatter: () => String(stValues[0] + stValues[1]) } } } } } });
            }
        } catch (err) { /* chart is decorative — fail silently */ }
    }

    async function loadList() {
        tbody.innerHTML = '<div class="p-12 text-center flex justify-center"><span class="inline-block w-6 h-6 border-2 border-primary border-t-transparent rounded-full animate-spin"></span></div>';
        const fd = new FormData();
        fd.append('page', page);
        fd.append('search', search);
        fd.append('kategori', kategori);
        fd.append('status', status);

        try {
            const res = await fetch(base + '/admin/ajax/list-berita', { method: 'POST', body: fd, credentials: 'same-origin' });
            const json = await res.json();
            if (!json.success) { toast(json.message || 'Gagal memuat.', false); return; }
            hasNext = json.has_next; hasPrev = json.has_prev;
            btnPrev.disabled = !hasPrev; btnNext.disabled = !hasNext;
            const perPage = 10;
            const startNum = json.total === 0 ? 0 : (json.page - 1) * perPage + 1;
            const endNum = Math.min(json.page * perPage, json.total);
            meta.textContent = json.total === 0
                ? 'Tidak ada data'
                : 'Menampilkan ' + startNum + '–' + endNum + ' dari ' + json.total + ' berita';

            document.getElementById('stat-total').textContent = json.stat_total ?? json.total;
            if (json.stat_terbit !== undefined) document.getElementById('stat-terbit').textContent = json.stat_terbit;
            if (json.stat_draft !== undefined) document.getElementById('stat-draft').textContent = json.stat_draft;

            if (!json.data.length) {
                tbody.innerHTML = '<div class="p-12 text-center text-ink-dim font-body-md">Belum ada berita.</div>';
                return;
            }

            tbody.innerHTML = json.data.map((row, i) => {
                const isTerbit = (row.status === 'terbit' && row.is_published);
                const isScheduled = (row.status === 'terbit' && row.is_scheduled);
                const cover = row.foto_sampul ? mediaUrl(row.foto_sampul) : null;
                const id = escapeHtml(row.id);
                const title = escapeHtml(row.judul);
                const summary = escapeHtml(row.ringkasan);
                const category = escapeHtml(row.kategori);
                const publishedAt = escapeHtml(row.tanggal_terbit);
                const coverUrl = cover ? escapeHtml(cover) : '';
                const rowNum = (json.page - 1) * perPage + i + 1;

                return `
                <div class="group cursor-pointer hover:bg-surface-2 transition-colors relative" data-id="${id}">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary scale-y-0 group-hover:scale-y-100 transition-transform origin-center"></div>
                    <div class="p-6 sm:px-8 sm:py-5 grid grid-cols-1 sm:grid-cols-12 gap-4 items-center relative">
                        <div class="sm:col-span-1 text-ink-dim font-label-mono text-[11px]">${rowNum}</div>
                        <div class="sm:col-span-4 flex gap-4 items-start">
                            <div class="w-16 h-16 rounded-lg bg-surface-container overflow-hidden flex-shrink-0 relative">
                                ${cover ? `<img class="w-full h-full object-cover" src="${coverUrl}" alt="Sampul berita"/>` : `<div class="absolute inset-0 flex items-center justify-center text-ink-dim bg-surface-2"><span class="material-symbols-outlined">newspaper</span></div>`}
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-h3 text-[18px] text-ink leading-snug truncate mb-1 group-hover:text-primary transition-colors">${title}</h3>
                                <p class="text-ink-dim font-body-md text-[13px] line-clamp-1">${summary}</p>
                            </div>
                        </div>
                        <div class="sm:col-span-2 flex items-center">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-primary-container/20 text-primary-fixed-dim font-label-mono text-[10px]">
                                <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>${category}
                            </span>
                        </div>
                        <div class="sm:col-span-2 flex items-center">
                            ${isTerbit ?
                                `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-surface-container-highest text-ink font-label-mono text-[10px]"><span class="material-symbols-outlined text-[14px]">public</span>Terbit</span>` :
                                isScheduled ?
                                `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-primary-container/20 text-primary font-label-mono text-[10px]"><span class="material-symbols-outlined text-[14px]">schedule</span>Terjadwal</span>` :
                                `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-surface-container text-ink-dim font-label-mono text-[10px]"><span class="material-symbols-outlined text-[14px]">edit_document</span>Draft</span>`
                            }
                        </div>
                        <div class="sm:col-span-2 sm:text-right text-ink-dim font-label-mono text-[11px]">${publishedAt}</div>
                        <div class="sm:col-span-1 flex items-center justify-end gap-2">
                            <button type="button" class="w-8 h-8 rounded-full bg-surface-2 text-ink-dim hover:bg-primary hover:text-on-primary flex items-center justify-center transition-colors pv-trigger" data-pv="${id}" title="Preview berita">
                                <span class="material-symbols-outlined text-[18px]">visibility</span>
                            </button>
                            <button type="button" class="w-8 h-8 rounded-full bg-danger/10 text-danger hover:bg-danger hover:text-white flex items-center justify-center transition-colors del-trigger" data-del="${id}" data-nama="${title}" title="Hapus berita">
                                <span class="material-symbols-outlined text-[18px]">delete</span>
                            </button>
                        </div>
                    </div>
                </div>`;
            }).join('');
        } catch (err) { toast('Gagal memuat data.', false); }
    }

    // ── Reset / isi form editor ──────────────────────────────────────────────
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
        rte.innerHTML = '';
        syncRte();
        const d = new Date();
        const m = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        document.getElementById('berita-tanggal').value = `${d.getFullYear()}-${m}-${day}`;
    }

    function fillFormFromData(d) {
        document.getElementById('berita-id').value = d.id;
        document.getElementById('berita-judul').value = d.judul;
        document.getElementById('berita-kategori').value = d.kategori;
        document.getElementById('berita-tanggal').value = d.tanggal_terbit;
        document.getElementById('berita-ringkasan').value = d.ringkasan;
        rte.innerHTML = d.konten || '';
        syncRte();
        document.getElementById('berita-penulis').value = d.penulis || '';
        document.getElementById('berita-tags').value = Array.isArray(d.tags) ? d.tags.join(', ') : '';

        document.getElementById('editor-title').textContent = 'Sedang Mengedit';
        document.getElementById('editor-pulse').classList.remove('hidden');

        document.getElementById('berita-foto-file').value = '';
        document.getElementById('berita-foto-new-img').classList.add('hidden');
        document.getElementById('berita-foto-new-img').src = '';
        document.getElementById('berita-foto-clear').classList.add('hidden');

        const oldImg = document.getElementById('berita-foto-old-img');
        if (d.foto_sampul) {
            oldImg.src = mediaUrl(d.foto_sampul);
            oldImg.classList.remove('hidden');
        } else {
            oldImg.classList.add('hidden');
            oldImg.src = '';
        }
    }

    function openEditor(mode) {
        if (mode === 'create') {
            resetForm();
        } else if (previewData) {
            fillFormFromData(previewData);
        }
        openModal(modalEditor);
        document.getElementById('berita-judul').focus();
    }

    // ── Preview ──────────────────────────────────────────────────────────────
    async function openPreview(id) {
        const fd = new FormData();
        fd.append('id', id);
        try {
            const res = await fetch(base + '/admin/ajax/get-berita', { method: 'POST', body: fd, credentials: 'same-origin' });
            const json = await res.json();
            if (!json.success) { toast(json.message || 'Gagal memuat detail berita.', false); return; }
            const d = json.data;
            previewData = d;

            document.getElementById('pv-judul').textContent = d.judul;
            const isTerbit = d.status === 'terbit' && d.is_published;
            const isScheduled = d.status === 'terbit' && d.is_scheduled;
            const statusEl = document.getElementById('pv-status');
            statusEl.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md font-label-mono text-[10px] ' + (isTerbit ? 'bg-surface-container-highest text-ink' : isScheduled ? 'bg-primary-container/20 text-primary' : 'bg-surface-container text-ink-dim');
            statusEl.innerHTML = isTerbit
                ? '<span class="material-symbols-outlined text-[14px]">public</span>Terbit'
                : isScheduled
                ? '<span class="material-symbols-outlined text-[14px]">schedule</span>Terjadwal'
                : '<span class="material-symbols-outlined text-[14px]">edit_document</span>Draft';
            document.getElementById('pv-kategori').textContent = d.kategori || '-';
            document.getElementById('pv-meta').textContent = [d.tanggal_terbit, d.penulis].filter(Boolean).join(' · ');

            const foto = document.getElementById('pv-foto');
            if (d.foto_sampul) {
                foto.src = mediaUrl(d.foto_sampul);
                foto.classList.remove('hidden');
            } else {
                foto.classList.add('hidden');
                foto.src = '';
            }
            document.getElementById('pv-konten').innerHTML = d.konten || '';
            openModal(modalPreview);
        } catch (err) { toast('Gagal memuat berita.', false); }
    }

    // ── Event: list ──────────────────────────────────────────────────────────
    document.getElementById('btn-create-news')?.addEventListener('click', () => openEditor('create'));

    document.getElementById('search-berita')?.addEventListener('input', (e) => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => { search = e.target.value.trim(); page = 1; loadList(); }, 300);
    });

    document.getElementById('filter-kategori')?.addEventListener('change', (e) => {
        kategori = e.target.value;
        page = 1;
        loadList();
    });

    document.getElementById('filter-status')?.addEventListener('change', (e) => {
        status = e.target.value;
        page = 1;
        loadList();
    });

    /* ── Reset filter ── */
    document.getElementById('btn-reset-filter')?.addEventListener('click', () => {
        document.getElementById('search-berita').value = '';
        document.getElementById('filter-kategori').value = '';
        document.getElementById('filter-status').value = '';
        search = ''; kategori = ''; status = '';
        page = 1;
        loadList();
    });

    btnPrev?.addEventListener('click', () => { if (hasPrev) { page--; loadList(); } });
    btnNext?.addEventListener('click', () => { if (hasNext) { page++; loadList(); } });

    tbody?.addEventListener('click', async (e) => {
        const delBtn = e.target.closest('.del-trigger');
        const pvBtn = e.target.closest('.pv-trigger');
        const card = e.target.closest('.group');

        if (delBtn) {
            e.stopPropagation();
            deleteId = delBtn.dataset.del;
            document.getElementById('hapus-nama').textContent = delBtn.dataset.nama || '';
            openModal(modalHapus);
            return;
        }
        if (pvBtn) {
            e.stopPropagation();
            openPreview(pvBtn.dataset.pv);
            return;
        }
        if (card && card.dataset.id) {
            openPreview(card.dataset.id);
        }
    });

    // ── Modal close handlers ─────────────────────────────────────────────────
    function closeHapus() { closeModal(modalHapus); deleteId = null; }
    document.getElementById('hapus-batal')?.addEventListener('click', closeHapus);
    document.getElementById('modal-hapus-backdrop')?.addEventListener('click', closeHapus);

    document.getElementById('btn-close-editor')?.addEventListener('click', () => closeModal(modalEditor));
    document.getElementById('modal-editor-backdrop')?.addEventListener('click', () => closeModal(modalEditor));

    document.getElementById('btn-close-preview')?.addEventListener('click', () => closeModal(modalPreview));
    document.getElementById('btn-close-preview-2')?.addEventListener('click', () => closeModal(modalPreview));
    document.getElementById('modal-preview-backdrop')?.addEventListener('click', () => closeModal(modalPreview));

    document.getElementById('pv-edit')?.addEventListener('click', () => {
        if (!previewData) return;
        closeModal(modalPreview);
        openEditor('edit');
    });

    document.getElementById('pv-hapus')?.addEventListener('click', () => {
        if (!previewData) return;
        deleteId = previewData.id;
        document.getElementById('hapus-nama').textContent = previewData.judul || '';
        closeModal(modalPreview);
        openModal(modalHapus);
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
                const deletedId = deleteId;
                closeHapus();
                if (document.getElementById('berita-id').value === deletedId) resetForm();
                if (previewData && previewData.id === deletedId) { previewData = null; closeModal(modalPreview); }
                loadList();
                loadChart();
            }
        } catch (err) { toast('Gagal terhubung ke server atau terjadi kesalahan internal. Periksa koneksi internet Anda dan coba lagi.', false); }
    });

    // ── File input preview ───────────────────────────────────────────────────
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

    // ── Save Handlers ────────────────────────────────────────────────────────
    async function saveForm(statusVal) {
        syncRte();
        if (!rteInput.value.trim()) { toast('Isi berita wajib diisi.', false); return; }
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
                closeModal(modalEditor);
                previewData = null;
                resetForm();
                loadList();
                loadChart();
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
    loadChart();
})();
</script>
<?php require __DIR__ . '/../partials/footer.php'; ?>
