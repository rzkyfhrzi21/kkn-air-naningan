<?php
/* ======================================================
   BAGIAN KAKI SITUS PUBLIK (PUBLIC FOOTER PARTIAL)

   File ini ibarat "fondasi kaki & halaman belakang rumah desa":
   ditampilkan di paling bawah seluruh halaman publik situs Pekon Air Naningan.

   Isi Bagian Antarmuka (UI):
   (1) Logo pekon & Visi singkat pekon.
   (2) Instagram resmi pekon (@pkpm56_airnaningan1).
   (3) Informasi kontak desa (alamat kantor pekon dari file `.env`, email resmi).
   (4) Tautan cepat menuju halaman Profil, Berita, UMKM, dan Kontak.
   (5) Hak Cipta (Copyright) dengan tahun dinamis otomatis (`date('Y')`).
   (6) Skrip JavaScript penangan menu HP (Mobile Menu Toggle):
       - (6.1) Mendengarkan tombol garis tiga (hamburger menu) di layar HP.
       - (6.2) Buka/tutup menu tersembunyi dengan animasi backdrop blur.
       - (6.3) Tutup menu otomatis jika salah satu link diklik.
====================================================== */

$base = defined('APP_BASE') ? APP_BASE : '';
$footerAlamat = env('KONTAK_ALAMAT') ?? 'Jl. Raya Air Naningan, Tanggamus';
?>
</main>

<footer class="bg-surface-container-lowest border-t border-line-strong pt-section-v-mobile lg:pt-section-v-desktop pb-12">
    <div class="max-w-container-max mx-auto px-container-pad-mobile lg:px-container-pad-desktop">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter mb-12">
            <!-- (1) Logo & Deskripsi Singkat Pekon -->
            <div class="flex flex-col gap-4">
                <div class="flex items-center gap-3">
                    <img class="h-8 w-auto"
                         alt="Logo Pekon Air Naningan"
                         src="<?= htmlspecialchars($base . '/assets/images/logo.jpg', ENT_QUOTES) ?>">
                    <span class="font-h3 text-h3 text-ink">Air Naningan</span>
                </div>
                <p class="text-ink-dim text-body-md max-w-xs">
                    Mewujudkan masyarakat pekon yang mandiri, berbudaya, dan sejahtera melalui optimalisasi potensi alam.
                </p>
                <!-- (2) Link Instagram Resmi Pekon -->
                <a href="https://www.instagram.com/pkpm56_airnaningan1/"
                   target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-2.5 text-ink-dim hover:text-gold-soft transition-colors group w-fit"
                   aria-label="Ikuti kami di Instagram @pkpm56_airnaningan1">
                    <svg class="size-5 shrink-0 group-hover:scale-110 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <rect x="3" y="3" width="18" height="18" rx="5"></rect>
                        <circle cx="12" cy="12" r="4"></circle>
                        <circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"></circle>
                    </svg>
                    <span class="font-label-mono text-label-mono tracking-wider">@pkpm56_airnaningan1</span>
                </a>
            </div>
            <!-- (3) Informasi Alamat & Email Pekon -->
            <div class="flex flex-col gap-4">
                <h4 class="font-h3 text-h3 text-gold-soft">Kontak Kami</h4>
                <div class="flex flex-col gap-2 text-ink-dim text-body-md">
                    <span class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">location_on</span>
                        <?= htmlspecialchars($footerAlamat, ENT_QUOTES, 'UTF-8') ?>
                    </span>
                    <span class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">mail</span>
                        info@airnaningan.desa.id
                    </span>
                </div>
            </div>
            <!-- (4) Tautan Navigasi Cepat -->
            <div class="flex flex-col gap-4">
                <h4 class="font-h3 text-h3 text-gold-soft">Tautan Cepat</h4>
                <div class="grid grid-cols-2 gap-2 text-ink-dim text-body-md">
                    <a class="hover:text-gold-soft transition-colors" href="<?= $base ?>/profil">Profil Desa</a>
                    <a class="hover:text-gold-soft transition-colors" href="<?= $base ?>/berita">Berita</a>
                    <a class="hover:text-gold-soft transition-colors" href="<?= $base ?>/umkm">Produk UMKM</a>
                    <a class="hover:text-gold-soft transition-colors" href="<?= $base ?>/kontak">Pengaduan</a>
                </div>
            </div>
        </div>
        <!-- (5) Baris Hak Cipta & Kebijakan -->
        <div class="border-t border-line pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-label-mono text-ink-dim">
            <p>© <?= date('Y') ?> PEKON AIR NANINGAN. ALL RIGHTS RESERVED.</p>
            <div class="flex items-center gap-6">
                <a href="https://www.instagram.com/pkpm56_airnaningan1/"
                   target="_blank" rel="noopener noreferrer"
                   class="flex items-center gap-1.5 hover:text-gold-soft transition-colors group"
                   aria-label="Instagram @pkpm56_airnaningan1">
                    <svg class="size-4 shrink-0 group-hover:scale-110 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <rect x="3" y="3" width="18" height="18" rx="5"></rect>
                        <circle cx="12" cy="12" r="4"></circle>
                        <circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"></circle>
                    </svg>
                    <span>INSTAGRAM</span>
                </a>
                <a class="hover:text-ink transition-colors" href="#">KEBIJAKAN PRIVASI</a>
                <a class="hover:text-ink transition-colors" href="#">SYARAT &amp; KETENTUAN</a>
            </div>
        </div>
    </div>
</footer>

<!-- (6) Skrip JavaScript Penangan Menu HP -->
<script>
    /* ======================================================
       SKRIP JAVASCRIPT: OPEN/CLOSE MENU NAVIGASI HP (MOBILE MENU)
       
       Alur Kerjanya:
       (6.1) Dengarkan klik pada tombol garis tiga (#mobile-menu-btn).
       (6.2) Jika menu terbuka: sembunyikan menu & kembalikan scroll layar.
             Jika menu tertutup: tampilkan menu & kunci scroll layar (overflow hidden).
       (6.3) Jika pengguna mengklik salah satu link menu, tutup menu otomatis.
    ====================================================== */
    (function () {
        const btn    = document.getElementById('mobile-menu-btn');
        const menu   = document.getElementById('mobile-menu');
        const icon   = document.getElementById('menu-icon');
        if (!btn || !menu) return;

        btn.addEventListener('click', () => {
            const isOpen = menu.classList.contains('flex');
            if (isOpen) {
                menu.classList.remove('flex');
                menu.classList.add('hidden');
                icon.textContent = 'menu';
                document.body.style.overflow = '';
            } else {
                menu.classList.add('flex');
                menu.classList.remove('hidden');
                icon.textContent = 'close';
                document.body.style.overflow = 'hidden';
            }
        });

        // Tutup menu otomatis setelah link diklik
        menu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                menu.classList.remove('flex');
                menu.classList.add('hidden');
                icon.textContent = 'menu';
                document.body.style.overflow = '';
            });
        });
    })();
</script>

<script src="<?= htmlspecialchars($base, ENT_QUOTES, 'UTF-8') ?>/security-warning.js"></script>
<script src="<?= htmlspecialchars($base, ENT_QUOTES, 'UTF-8') ?>/assets/js/modal-focus.js"></script>
</body>
</html>
