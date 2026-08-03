<?php
$base = defined('APP_BASE') ? APP_BASE : '';
$footerAlamat = env('KONTAK_ALAMAT') ?? 'Jl. Raya Air Naningan, Tanggamus';
?>
</main>

<footer class="bg-surface-container-lowest border-t border-line-strong pt-section-v-mobile lg:pt-section-v-desktop pb-12">
    <div class="max-w-container-max mx-auto px-container-pad-mobile lg:px-container-pad-desktop">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter mb-12">
            <div class="flex flex-col gap-4">
                <div class="flex items-center gap-3">
                    <img class="h-8 w-auto"
                         alt="Logo Pekon Air Naningan"
                         src="https://lh3.googleusercontent.com/aida-public/AB6AXuDK8H8Se06RoiwH3wJUlg_WHE-OgxxczJXVk5QpS3z5Nrh9E-j4DWfOQGQdYjrZ_4qD0b0knh3Lh9z4-Br0W2p7XHqFOeTE-coRDtrzlAeBSeX1RxZH9ViZAjV2cFI4G7ELLcfStWHI4FnE7oINSXOgQPfezfdHZoTBjjUgsqfBaXdugJDSTO1KXsNlpryA9s7n8dKAynQE5letH5Wym17CkRm5ou_ywSF0k0_ETyMzzyqNSuTW_EDbWw">
                    <span class="font-h3 text-h3 text-ink">Air Naningan</span>
                </div>
                <p class="text-ink-dim text-body-md max-w-xs">
                    Mewujudkan masyarakat pekon yang mandiri, berbudaya, dan sejahtera melalui optimalisasi potensi alam.
                </p>
            </div>
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
        <div class="border-t border-line pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-label-mono text-ink-dim">
            <p>© <?= date('Y') ?> PEKON AIR NANINGAN. ALL RIGHTS RESERVED.</p>
            <div class="flex gap-6">
                <a class="hover:text-ink transition-colors" href="#">KEBIJAKAN PRIVASI</a>
                <a class="hover:text-ink transition-colors" href="#">SYARAT &amp; KETENTUAN</a>
            </div>
        </div>
    </div>
</footer>

<script>
    // Mobile menu toggle
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

        // Close menu when a nav link inside it is clicked
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
</body>
</html>
