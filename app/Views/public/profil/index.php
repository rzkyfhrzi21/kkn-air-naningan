<?php
/* ======================================================
   VIEW PROFIL DESA PUBLIK (VILLAGE PROFILE PAGE VIEW)

   File ini ibarat "buku profil resmi desa":
   menampilkan sejarah singkat pekon, visi dan misi, struktur aparatur pekon,
   peta wilayah Google Maps, serta data statistik demografi warga.

   Data / Variabel yang dipanggil di file ini:
   - $profil['visi']       : Teks Visi pekon
   - $profil['misi']       : Array daftar Misi pekon
   - $profil['struktur']   : Array susunan aparatur desa (nama, jabatan, foto)
   - $profil['sejarah']    : Narasi sejarah pekon
   - $profil['peta']       : URL embed Google Maps balai pekon
====================================================== */

$currentPage     = 'profil-desa';
$pageTitle       = 'Profil Pekon Air Naningan Tanggamus | Pemerintahan';
$metaDescription = 'Kenali profil Pekon Air Naningan, Tanggamus: sejarah desa, visi misi, struktur pemerintahan, data demografi, transparansi, dan peta wilayah.';
$metaKeywords    = 'profil Pekon Air Naningan, sejarah Air Naningan, pemerintahan desa Tanggamus, demografi Air Naningan';
$base            = defined('APP_BASE') ? APP_BASE : '';
$escape          = static fn(mixed $value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
$misi            = is_array($profil['misi'] ?? null) ? $profil['misi'] : [];
$struktur        = is_array($profil['struktur'] ?? null) ? $profil['struktur'] : [];
$sejarah         = is_array($profil['sejarah'] ?? null) ? $profil['sejarah'] : [];
$paragrafSejarah = $sejarah['paragraf'] ?? [];
$peta            = is_array($profil['peta'] ?? null) ? $profil['peta'] : [];
require __DIR__ . '/../partials/header.php';
?>


<div class="flex flex-col w-full text-on-surface">
    <section class="w-full pt-12 pb-8 bg-surface-container-lowest">
        <div class="max-w-container-max mx-auto px-container-pad-mobile lg:px-container-pad-desktop">
            <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-label-mono text-ink-dim uppercase mb-6">
                <a class="hover:text-gold-soft transition-colors" href="<?= $escape($base . '/') ?>">Beranda</a>
                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                <span class="text-primary">Profil Desa</span>
            </nav>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-end">
                <div>
                    <h1 class="font-h1-mobile lg:font-h1 text-h1-mobile lg:text-h1 text-ink mb-4">Profil Pekon Air Naningan</h1>
                    <p class="font-body-lg text-body-lg text-ink-dim max-w-xl"><?= $escape($profil['tagline'] ?? '') ?></p>
                </div>
                <?php if ((int) ($profil['tahun_berdiri'] ?? 0) > 0): ?>
                    <div class="hidden lg:flex justify-end pb-2">
                        <div class="flex items-center gap-4 bg-surface px-6 py-3 rounded-full border border-line">
                            <span class="font-label-mono text-label-mono text-gold-soft">BERDIRI SEJAK</span>
                            <span class="w-px h-4 bg-line-strong"></span>
                            <span class="font-h3 text-h3 text-ink"><?= (int) $profil['tahun_berdiri'] ?></span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="w-full py-section-v-mobile lg:py-section-v-desktop bg-surface-container-lowest border-t border-line">
        <div class="max-w-container-max mx-auto px-container-pad-mobile lg:px-container-pad-desktop">
            <div class="max-w-3xl mx-auto flex flex-col gap-12">
                <div class="text-center flex flex-col gap-4"><span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest">Napak Tilas</span><h2 class="font-h2 text-h2 text-ink">Sejarah Air Naningan</h2></div>
                <div class="max-w-none text-ink-dim font-body-lg text-body-lg">
                    <?php if (is_string($paragrafSejarah)):
                        $sejarahHtml = $paragrafSejarah;
                        if (trim((string) ($sejarah['quote'] ?? '')) !== ''):
                            $quoteBlock = '<blockquote class="my-10 pl-6 border-l-4 border-primary italic font-h3 text-h3 text-ink bg-surface-2/50 py-4 pr-6 rounded-r-xl">&ldquo;' . $escape((string) $sejarah['quote']) . '&rdquo;</blockquote>';
                            $pos = stripos($sejarahHtml, '</p>');
                            $sejarahHtml = $pos !== false
                                ? substr($sejarahHtml, 0, $pos + 4) . $quoteBlock . substr($sejarahHtml, $pos + 4)
                                : $quoteBlock . $sejarahHtml;
                        endif;
                        echo $sejarahHtml;
                    else: ?>
                    <?php foreach ($paragrafSejarah as $index => $paragraph): ?>
                        <p class="mb-6"><?= nl2br($escape($paragraph)) ?></p>
                        <?php if ($index === 0 && trim((string) ($sejarah['quote'] ?? '')) !== ''): ?>
                            <blockquote class="my-10 pl-6 border-l-4 border-primary italic font-h3 text-h3 text-ink bg-surface-2/50 py-4 pr-6 rounded-r-xl">&ldquo;<?= $escape($sejarah['quote']) ?>&rdquo;</blockquote>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <section class="w-full py-section-v-mobile lg:py-section-v-desktop bg-surface">
        <div class="max-w-container-max mx-auto px-container-pad-mobile lg:px-container-pad-desktop">
            <div class="flex flex-col lg:flex-row gap-16 lg:gap-24">
                <div class="flex-1 flex flex-col gap-6">
                    <div class="flex items-center gap-4">
                        <span class="w-12 h-px bg-primary"></span>
                        <h2 class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest">Arah Juang</h2>
                    </div>
                    <h3 class="font-h2 text-h2 text-ink">&ldquo;<?= $escape($profil['visi'] ?? '') ?>&rdquo;</h3>
                </div>
                <div class="flex-1 flex flex-col gap-8">
                    <h4 class="font-label-mono text-label-mono text-ink-dim uppercase tracking-widest border-b border-line pb-4">Misi Pekon</h4>
                    <ul class="flex flex-col gap-6">
                        <?php foreach ($misi as $index => $item): ?>
                            <li class="flex gap-4">
                                <span class="font-h3 text-h3 text-primary/40"><?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?></span>
                                <p class="font-body-lg text-body-lg text-ink"><?= $escape($item) ?></p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <?php
    // Kelompokkan data struktur per peran
    $org_head  = null;
    $org_sek   = null;
    $org_kaur  = [];
    $org_kasi  = [];
    $org_staf  = [];
    $org_kadus = [];
    $jabatan_kaur = ['kaur tu', 'kaur umum', 'kaur keuangan', 'kaur perencanaan'];
    $jabatan_kadus = ['kepala dusun'];
    foreach ($struktur as $ap) {
        $jab_lower = mb_strtolower(trim($ap['jabatan'] ?? ''));
        $level = (int) ($ap['level'] ?? 0);
        if ($level === 0) { $org_head = $ap; }
        elseif ($level === 1) { $org_sek = $ap; }
        elseif ($level === 3 || str_starts_with($jab_lower, 'kepala dusun')) { $org_kadus[] = $ap; }
        elseif (str_starts_with($jab_lower, 'kaur')) { $org_kaur[] = $ap; }
        elseif (str_starts_with($jab_lower, 'staf')) { $org_staf[] = $ap; }
        else { $org_kasi[] = $ap; }
    }
    // Helper: render satu org card
    $org_card = function(array $p, string $extra = '') use ($escape, $base): void {
        $fotoRaw  = trim((string)($p['foto'] ?? ''));
        $foto     = $fotoRaw !== '' ? mediaUrl($fotoRaw, $base) : '';
        $nama     = $p['nama'] ?? '-';
        $jabatan  = $p['jabatan'] ?? '-';
        $initials = implode('', array_map(
            fn($w) => mb_strtoupper(mb_substr($w, 0, 1)),
            array_slice(explode(' ', trim($nama)), 0, 2)
        ));
        ?>
        <div class="flex flex-col items-center gap-3 bg-surface-container rounded-2xl border border-line px-5 py-5 text-center shadow-sm transition-colors hover:border-primary/40 <?= $extra ?>">
            <?php if ($foto !== ''): ?>
                <button type="button"
                        class="w-16 h-16 rounded-full overflow-hidden shrink-0 border-2 border-primary/20 cursor-zoom-in focus:outline-none focus:ring-2 focus:ring-primary"
                        data-profile-photo="<?= $escape($foto) ?>"
                        data-profile-name="<?= $escape($nama) ?>"
                        aria-label="Lihat foto <?= $escape($nama) ?>">
                    <img src="<?= $escape($foto) ?>" alt="Foto <?= $escape($nama) ?>"
                         class="w-full h-full object-cover"
                         loading="lazy"
                         onerror="this.onerror=null;this.closest('button').outerHTML='<div class=\'w-16 h-16 rounded-full shrink-0 flex items-center justify-center text-white font-bold text-lg select-none bg-primary/20 text-primary\'><?= $escape($initials) ?: '?' ?></div>';">
                </button>
            <?php else: ?>
                <div class="w-16 h-16 rounded-full shrink-0 flex items-center justify-center font-bold text-lg select-none bg-primary/20 text-primary">
                    <?= $escape($initials) ?: '?' ?>
                </div>
            <?php endif; ?>
            <div>
                <p class="font-semibold text-ink text-sm leading-snug"><?= $escape($nama) ?></p>
                <p class="text-gold-soft text-xs mt-0.5"><?= $escape($jabatan) ?></p>
            </div>
        </div>
        <?php
    };
    ?>
    <section class="w-full py-section-v-mobile lg:py-section-v-desktop bg-surface-container-lowest">
        <div class="max-w-container-max mx-auto px-container-pad-mobile lg:px-container-pad-desktop">

            <!-- Judul section -->
            <div class="flex flex-col gap-2 text-center mb-12">
                <h2 class="font-h3 text-h3 text-ink">Struktur Pemerintahan</h2>
                <?php if (($profil['masa_bakti'] ?? '') !== ''): ?>
                    <p class="font-body-md text-body-md text-ink-dim">Aparatur pekon masa bakti <?= $escape($profil['masa_bakti']) ?>.</p>
                <?php endif; ?>
            </div>

            <div class="flex flex-col items-center gap-0">

                <!-- 1. Kepala Pekon -->
                <?php if ($org_head): ?>
                <div class="w-full flex justify-center">
                    <?php $org_card($org_head, 'max-w-[220px] w-full border-primary/40 shadow-md'); ?>
                </div>
                <!-- garis penghubung -->
                <div class="w-px h-8 bg-gold-soft/40"></div>
                <?php endif; ?>

                <!-- 2. Sekretaris Pekon -->
                <?php if ($org_sek): ?>
                <div class="w-full flex justify-center">
                    <?php $org_card($org_sek, 'max-w-[220px] w-full'); ?>
                </div>
                <div class="w-px h-8 bg-line-strong"></div>
                <?php endif; ?>

                <!-- 3. KAUR -->
                <?php if (!empty($org_kaur)): ?>
                <div class="w-full flex flex-col items-center gap-4">
                    <p class="text-xs font-bold tracking-widest text-ink-dim uppercase">Kepala Urusan (KAUR)</p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 w-full max-w-2xl">
                        <?php foreach ($org_kaur as $ap): $org_card($ap); endforeach; ?>
                    </div>
                </div>
                <div class="w-px h-8 bg-line-strong"></div>
                <?php endif; ?>

                <!-- 4. KASI -->
                <?php if (!empty($org_kasi)): ?>
                <div class="w-full flex flex-col items-center gap-4">
                    <p class="text-xs font-bold tracking-widest text-ink-dim uppercase">Kepala Seksi (KASI)</p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 w-full max-w-2xl">
                        <?php foreach ($org_kasi as $ap): $org_card($ap); endforeach; ?>
                    </div>
                    <?php if (!empty($org_staf)): ?>
                    <div class="text-center mt-2">
                        <p class="text-[10px] text-ink-dim uppercase tracking-widest mb-2">Staf</p>
                        <div class="flex justify-center gap-4">
                            <?php foreach ($org_staf as $ap): $org_card($ap, 'max-w-[180px] w-full'); endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="w-px h-8 bg-line-strong"></div>
                <?php endif; ?>

                <!-- 5. Kepala Dusun -->
                <?php if (!empty($org_kadus)): ?>
                <div class="w-full flex flex-col items-center gap-4">
                    <p class="text-xs font-bold tracking-widest text-ink-dim uppercase">Kepala Dusun</p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-7 gap-3 w-full">
                        <?php foreach ($org_kadus as $ap): $org_card($ap); endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

            </div><!-- /bagan -->
        </div>
    </section>

    <section class="w-full py-section-v-mobile lg:py-section-v-desktop bg-surface">
        <div class="max-w-container-max mx-auto px-container-pad-mobile lg:px-container-pad-desktop">
            <h2 class="font-h2 text-h2 text-ink mb-6">Peta Administrasi</h2>
            <div class="w-full h-[400px] lg:h-[500px] rounded-2xl overflow-hidden border border-line bg-surface-2 shadow-xl">
                <?php if (trim((string) ($peta['embed_url'] ?? '')) !== ''): ?>
                    <iframe src="<?= $escape($peta['embed_url']) ?>" title="Peta <?= $escape($peta['lokasi'] ?? 'Pekon Air Naningan') ?>" class="w-full h-full border-0" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center p-6 text-center"><span class="font-label-mono text-label-mono text-ink-dim"><?= $escape($peta['lokasi'] ?? 'Peta belum dikonfigurasi') ?></span></div>
                <?php endif; ?>
            </div>
        </div>
    </section>

</div>

<div id="profile-photo-modal" data-modal class="hidden fixed inset-0 z-[140] items-center justify-center p-4 md:p-8" role="dialog" aria-modal="true" aria-label="Preview foto aparatur">
    <button type="button" class="absolute inset-0 bg-black/80" data-profile-close aria-label="Tutup preview"></button>
    <div class="relative z-10 max-w-4xl"><img id="profile-photo-modal-img" src="" alt="" class="max-h-[85vh] max-w-full rounded-2xl border border-line object-contain shadow-2xl"><button type="button" data-profile-close class="absolute -right-2 -top-2 flex h-11 w-11 items-center justify-center rounded-full border border-line bg-surface text-ink shadow-lg" aria-label="Tutup preview foto"><span class="material-symbols-outlined">close</span></button></div>
</div>
<script>
(() => {
    const modal = document.getElementById('profile-photo-modal');
    const image = document.getElementById('profile-photo-modal-img');
    document.addEventListener('click', (event) => {
        const trigger = event.target.closest('[data-profile-photo]');
        if (trigger) {
            image.src = trigger.dataset.profilePhoto;
            image.alt = 'Foto ' + (trigger.dataset.profileName || 'aparatur pekon');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
        if (event.target.closest('[data-profile-close]')) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            image.src = '';
        }
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !modal.classList.contains('hidden')) document.querySelector('[data-profile-close]')?.click();
    });
})();
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
