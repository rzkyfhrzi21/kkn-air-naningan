<?php
/* ======================================================
   HALAMAN DASHBOARD ADMIN (RINGKASAN OVERVIEW)

   File ini adalah "halaman beranda" panel admin. Ibarat
   etalase awal yang menampilkan ringkasan kondisi data
   Pekon Air Naningan (UMKM, Berita, Galeri) dalam bentuk
   angka besar, daftar aktivitas terbaru, dan menu pintasan.

   Data sudah disiapkan oleh DashboardController lalu
   dikirim ke sini dalam bentuk variabel:
   - $summaries       : daftar jumlah data (umkm, berita, galeri)
   - $recentActivity  : daftar aktivitas terakhir yang terjadi

   Halaman ini HANYA menampilkan data (tidak menyimpan apa pun),
   jadi tidak ada form isian di sini.
====================================================== */
$pageTitle = 'Dashboard Overview';
$activeNav = 'overview';
$base      = defined('APP_BASE') ? APP_BASE : '';
require __DIR__ . '/../partials/header.php';
?>

<div class="flex flex-col w-full px-container-pad-mobile md:px-8 py-8 md:py-12 gap-10">

    <!-- ======================================================
         BAGIAN JUDUL HALAMAN + TOMBOL AKSI CEPAT

         Di sini ada judul "Dashboard Overview" beserta dua
         tombol pintasan:
         - "Tambah UMKM"  → membuka halaman admin/kelola-umkm
         - "Tambah Berita" → membuka halaman admin/kelola-berita
         Nilai $base adalah alamat dasar website (misalnya
         http://localhost/kkn-air-naningan/public) sehingga semua
         link bisa otomatis mengarah ke folder yang benar.
    ====================================================== -->
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="flex flex-col gap-2 max-w-2xl">
            <h1 class="font-h2 text-h2 text-ink">Dashboard Overview</h1>
            <p class="font-body-lg text-body-lg text-ink-dim">Ringkasan statistik dan aktivitas terbaru Pekon Air Naningan.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="<?= $base ?>/admin/kelola-umkm"
               class="flex items-center gap-2 px-5 py-2.5 rounded-full bg-surface-2 text-ink hover:bg-surface-container-high transition-colors border border-line">
                <span class="material-symbols-outlined text-[20px]">add</span>
                <span class="font-label-mono text-label-mono uppercase tracking-widest">Tambah UMKM</span>
            </a>
            <a href="<?= $base ?>/admin/kelola-berita"
               class="flex items-center gap-2 px-5 py-2.5 rounded-full bg-primary text-on-primary hover:bg-primary-fixed-dim transition-colors shadow-lg shadow-primary/10">
                <span class="material-symbols-outlined text-[20px]">post_add</span>
                <span class="font-label-mono text-label-mono uppercase tracking-widest">Tambah Berita</span>
            </a>
        </div>
    </div>

    <!-- ======================================================
         KARTU STATISTIK (JUMLAH DATA UTAMA)

         Tiga kartu angka besar yang diletakkan sejajar:
         - UMKM Aktif   : membaca $summaries['umkm']   (jumlah usaha)
         - Total Berita : membaca $summaries['berita'] (jumlah artikel)
         - Foto Galeri  : membaca $summaries['galeri'] (jumlah foto)

         Angka diambil dari variabel $summaries yang dihitung
         oleh DashboardController dari file JSON data.
         number_format() membuat angka terlihat rapi (misal 1.250).
    ====================================================== -->
    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        <!-- UMKM Aktif -->
        <div class="bg-surface-container p-6 rounded-2xl flex flex-col gap-4 relative overflow-hidden group border border-line hover:border-line-strong transition-colors">
                        <div class="flex items-center justify-between relative z-10">
                <span class="font-label-mono text-label-mono text-ink-dim uppercase tracking-widest">UMKM Aktif</span>
                <div class="w-10 h-10 rounded-full bg-surface-2 flex items-center justify-center border border-line text-primary">
                    <span class="material-symbols-outlined text-[20px]">storefront</span>
                </div>
            </div>
            <div class="flex items-baseline gap-2 relative z-10">
                <span class="font-h2 text-h2 text-ink"><?= number_format($summaries['umkm'] ?? 0) ?></span>
                <span class="font-body-md text-[13px] text-ink-dim">terdaftar</span>
            </div>
        </div>

        <!-- Berita -->
        <div class="bg-surface-container p-6 rounded-2xl flex flex-col gap-4 relative overflow-hidden group border border-line hover:border-line-strong transition-colors">
                        <div class="flex items-center justify-between relative z-10">
                <span class="font-label-mono text-label-mono text-ink-dim uppercase tracking-widest">Total Berita</span>
                <div class="w-10 h-10 rounded-full bg-surface-2 flex items-center justify-center border border-line text-primary">
                    <span class="material-symbols-outlined text-[20px]">newspaper</span>
                </div>
            </div>
            <div class="flex items-baseline gap-2 relative z-10">
                <span class="font-h2 text-h2 text-ink"><?= number_format($summaries['berita'] ?? 0) ?></span>
                <span class="font-body-md text-[13px] text-ink-dim">artikel</span>
            </div>
        </div>

        <!-- Galeri -->
        <div class="bg-surface-container p-6 rounded-2xl flex flex-col gap-4 relative overflow-hidden group border border-line hover:border-line-strong transition-colors">
                        <div class="flex items-center justify-between relative z-10">
                <span class="font-label-mono text-label-mono text-ink-dim uppercase tracking-widest">Foto Galeri</span>
                <div class="w-10 h-10 rounded-full bg-surface-2 flex items-center justify-center border border-line text-tertiary-fixed-dim">
                    <span class="material-symbols-outlined text-[20px]">photo_library</span>
                </div>
            </div>
            <div class="flex items-baseline gap-2 relative z-10">
                <span class="font-h2 text-h2 text-ink"><?= number_format($summaries['galeri'] ?? 0) ?></span>
                <span class="font-body-md text-[13px] text-ink-dim">foto</span>
            </div>
        </div>

    </div>

    <!-- Bottom Grid: Aktivitas + Chart -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- ======================================================
             TABEL AKTIVITAS TERBARU

             Bagian ini memperlihatkan riwayat kegiatan terakhir
             (misalnya "Admin menambahkan berita baru"). Sistem
             mengecek variabel $recentActivity satu per satu lewat
             foreach — setiap satu aktivitas dibuatkan 1 baris tabel
             (1 baris = 1 kejadian).

             Kolom data yang dipakai dari setiap aktivitas:
             - $activity['icon']  : ikon kecil (misal ikon koran)
             - $activity['title'] : judul aktivitas
             - $activity['desc']  : keterangan singkat aktivitas
             - $activity['time']  : waktu kejadian (jam/tanggal)

             Jika tidak ada aktivitas sama sekali ($recentActivity
             kosong), ditampilkan tulisan "Belum ada aktivitas data."
             menggantikan baris-baris tabel.
        ====================================================== -->
        <!-- Aktivitas Terbaru -->
        <div class="lg:col-span-2 flex flex-col gap-6">
            <div class="flex items-center justify-between">
                <h3 class="font-h3 text-h3 text-ink">Aktivitas Terbaru</h3>
                <span class="font-label-mono text-label-mono text-ink-dim/50 uppercase tracking-widest text-[11px]">Data Live</span>
            </div>
            <div class="bg-surface-container rounded-2xl overflow-hidden border border-line">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-line-strong bg-surface-container-high/50">
                                <th class="py-4 px-6 font-label-mono text-[11px] text-ink-dim uppercase tracking-widest whitespace-nowrap">Tipe</th>
                                <th class="py-4 px-6 font-label-mono text-[11px] text-ink-dim uppercase tracking-widest min-w-[200px]">Aktivitas</th>
                                <th class="py-4 px-6 font-label-mono text-[11px] text-ink-dim uppercase tracking-widest whitespace-nowrap text-right">Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="font-body-md text-body-md">
                            <?php if (!empty($recentActivity)): ?>
                                <?php foreach ($recentActivity as $activity): ?>
                                    <tr class="border-b border-line hover:bg-surface-2/50 transition-colors cursor-pointer group">
                                        <td class="py-4 px-6">
                                            <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                                                <span class="material-symbols-outlined text-[16px]"><?= htmlspecialchars($activity['icon'], ENT_QUOTES, 'UTF-8') ?></span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6">
                                            <p class="text-ink truncate max-w-[250px] group-hover:text-primary transition-colors">
                                                <?= htmlspecialchars($activity['title'], ENT_QUOTES, 'UTF-8') ?>
                                            </p>
                                            <p class="text-[13px] text-ink-dim mt-0.5"><?= htmlspecialchars($activity['desc'], ENT_QUOTES, 'UTF-8') ?></p>
                                        </td>
                                        <td class="py-4 px-6 text-ink-dim text-right text-[14px]"><?= htmlspecialchars($activity['time'], ENT_QUOTES, 'UTF-8') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr class="border-b border-line">
                                    <td colspan="3" class="py-12 px-6 text-center text-ink-dim text-[14px]">Belum ada aktivitas data.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ======================================================
             PANEL "KELOLA CEPAT" + INFO SESI ADMIN

             Dua kotak panel di sisi kanan:
             1) Kelola Cepat — daftar pintasan menu (UMKM, Berita,
                Galeri) yang dibuat dengan perulangan foreach pada
                variabel $menuItems. Setiap item membawa:
                - $slug  : alamat halaman (misal 'kelola-umkm')
                - $icon  : nama ikon Material Symbols
                - $label : nama menu yang tampil
                - $count : jumlah data dari $summaries
                Ada pula link "Lihat Situs Publik" untuk membuka
                website pengunjung di tab baru.

             2) Info Sesi — menampilkan siapa yang sedang login:
                - $_SESSION['admin_username'] : nama pengguna admin
                - $_SESSION['login_at']       : jam pertama login
                Serta tombol "Keluar dari sesi" (logout).
        ====================================================== -->
        <!-- Panel Ringkasan Data -->
        <div class="flex flex-col gap-6">
            <h3 class="font-h3 text-h3 text-ink">Kelola Cepat</h3>
            <div class="bg-surface-container rounded-2xl p-6 border border-line flex flex-col gap-4">
                <?php
                $menuItems = [
                    ['kelola-umkm',   'storefront',          'UMKM',         $summaries['umkm']    ?? 0],
                    ['kelola-berita', 'newspaper',           'Berita',        $summaries['berita']  ?? 0],
                    ['kelola-galeri', 'photo_library',       'Galeri',        $summaries['galeri']  ?? 0],
                ];
                foreach ($menuItems as [$slug, $icon, $label, $count]):
                ?>
                    <a href="<?= $base ?>/admin/<?= $slug ?>"
                       class="flex items-center justify-between px-4 py-3 rounded-xl hover:bg-surface-container-high transition-colors group border border-transparent hover:border-line">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[20px] text-ink-dim group-hover:text-primary transition-colors"><?= $icon ?></span>
                            <span class="font-body-md text-ink"><?= $label ?></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="font-label-mono text-label-mono text-ink-dim"><?= $count ?></span>
                            <span class="material-symbols-outlined text-[16px] text-ink-dim/30 group-hover:text-primary/60 group-hover:translate-x-0.5 transition-all">arrow_forward_ios</span>
                        </div>
                    </a>
                <?php endforeach; ?>

                <div class="pt-3 border-t border-line mt-2">
                    <a href="<?= $base ?>/" target="_blank"
                       class="flex items-center gap-2 px-4 py-3 rounded-xl text-ink-dim hover:text-ink hover:bg-surface-container-high transition-colors text-[14px]">
                        <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                        Lihat Situs Publik
                    </a>
                </div>
            </div>

            <!-- Login info -->
            <div class="bg-surface-container rounded-2xl p-6 border border-line flex flex-col gap-3">
                <span class="font-label-mono text-label-mono text-ink-dim uppercase tracking-widest">Info Sesi</span>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-on-primary text-[16px]">person</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-body-md text-ink font-semibold">
                            <?= htmlspecialchars($_SESSION['admin_username'] ?? 'Administrator', ENT_QUOTES, 'UTF-8') ?>
                        </span>
                        <span class="font-label-mono text-[11px] text-ink-dim">
                            Login: <?= isset($_SESSION['login_at'])
                                ? date('d M Y, H:i', $_SESSION['login_at'])
                                : '—' ?>
                        </span>
                    </div>
                </div>
                <a href="<?= $base ?>/admin/logout"
                   class="mt-2 flex items-center gap-2 text-[13px] text-ink-dim hover:text-danger transition-colors font-label-mono">
                    <span class="material-symbols-outlined text-[16px]">logout</span>
                    Keluar dari sesi
                </a>
            </div>
        </div>

    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
