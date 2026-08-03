<?php
$currentPage     = 'profil-desa';
$pageTitle       = 'Profil Desa | Pekon Air Naningan';
$metaDescription = 'Profil Pekon Air Naningan - sejarah, visi misi, struktur pemerintahan, data demografi, transparansi anggaran, dan peta administrasi.';
$base            = defined('APP_BASE') ? APP_BASE : '';
$escape          = static fn(mixed $value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
$misi            = is_array($profil['misi'] ?? null) ? $profil['misi'] : [];
$struktur        = is_array($profil['struktur'] ?? null) ? $profil['struktur'] : [];
$demografi       = is_array($profil['demografi'] ?? null) ? $profil['demografi'] : [];
$perDusun        = is_array($demografi['per_dusun'] ?? null) ? $demografi['per_dusun'] : [];
$pekerjaan       = is_array($profil['mata_pencaharian'] ?? null) ? $profil['mata_pencaharian'] : [];
$apbdes          = is_array($profil['apbdes'] ?? null) ? $profil['apbdes'] : [];
$apbItems        = is_array($apbdes['items'] ?? null) ? $apbdes['items'] : [];
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

    <section class="w-full py-section-v-mobile lg:py-section-v-desktop bg-surface-container-lowest">
        <div class="max-w-container-max mx-auto px-container-pad-mobile lg:px-container-pad-desktop">
            <div class="flex flex-col gap-12">
                <div class="flex flex-col gap-2 text-center">
                    <h2 class="font-h3 text-h3 text-ink">Struktur Pemerintahan</h2>
                    <?php if (($profil['masa_bakti'] ?? '') !== ''): ?>
                        <p class="font-body-md text-body-md text-ink-dim">Aparatur pekon masa bakti <?= $escape($profil['masa_bakti']) ?>.</p>
                    <?php endif; ?>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <?php foreach ($struktur as $aparatur): ?>
                        <?php $foto = trim((string) ($aparatur['foto'] ?? '')); ?>
                        <article class="bg-surface-2 rounded-xl border border-line overflow-hidden <?= (int) ($aparatur['level'] ?? 0) === 0 ? 'sm:col-span-2 lg:col-span-4 lg:max-w-sm lg:mx-auto lg:w-full border-primary' : '' ?>">
                            <?php if ($foto !== ''): ?>
                                <button type="button" class="block w-full aspect-[4/3] overflow-hidden cursor-zoom-in" data-profile-photo="<?= $escape($foto) ?>" data-profile-name="<?= $escape($aparatur['nama'] ?? '') ?>">
                                    <img src="<?= $escape($foto) ?>" alt="Foto <?= $escape($aparatur['nama'] ?? 'aparatur pekon') ?>" class="w-full h-full object-cover" loading="lazy">
                                </button>
                            <?php endif; ?>
                            <div class="px-5 py-4 text-center">
                                <p class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest mb-1"><?= $escape($aparatur['jabatan'] ?? '') ?></p>
                                <p class="font-body-md font-semibold text-ink"><?= $escape($aparatur['nama'] ?? '') ?></p>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <section class="w-full py-section-v-mobile lg:py-section-v-desktop bg-surface">
        <div class="max-w-container-max mx-auto px-container-pad-mobile lg:px-container-pad-desktop">
            <div class="mb-10">
                <h2 class="font-h2 text-h2 text-ink mb-2">Demografi &amp; Mata Pencaharian</h2>
                <p class="font-body-md text-body-md text-ink-dim">Ringkasan kondisi wilayah dan penduduk Pekon Air Naningan.</p>
            </div>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
                <?php
                $stats = [
                    ['label' => 'Total Penduduk', 'value' => number_format((int) ($demografi['total_jiwa'] ?? 0), 0, ',', '.') . ' jiwa'],
                    ['label' => 'Kepala Keluarga', 'value' => number_format((int) ($demografi['kepala_keluarga'] ?? 0), 0, ',', '.') . ' KK'],
                    ['label' => 'Luas Wilayah', 'value' => $escape($demografi['luas_wilayah'] ?? 0) . ' ' . $escape($demografi['luas_satuan'] ?? '')],
                    ['label' => 'Ketinggian', 'value' => $escape($demografi['ketinggian'] ?? 0) . ' ' . $escape($demografi['ketinggian_satuan'] ?? '')],
                ];
                foreach ($stats as $stat):
                ?>
                    <div class="bg-surface-2 border border-line rounded-xl p-5">
                        <p class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest mb-2"><?= $stat['label'] ?></p>
                        <p class="font-h3 text-h3 text-ink"><?= $stat['value'] ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                <div>
                    <h3 class="font-h3 text-h3 text-ink mb-6">Penduduk per Dusun</h3>
                    <div class="flex flex-col gap-4">
                        <?php foreach ($perDusun as $dusun): ?>
                            <?php $dusunPercent = (int) ($demografi['total_jiwa'] ?? 0) > 0 ? min(100, ((int) ($dusun['jumlah'] ?? 0) / (int) $demografi['total_jiwa']) * 100) : 0; ?>
                            <?php $dusunWidthClass = 'w-[' . round($dusunPercent, 2) . '%]'; ?>
                            <div>
                                <div class="flex justify-between gap-4 mb-2 text-sm"><span class="text-ink"><?= $escape($dusun['nama'] ?? '') ?></span><span class="text-ink-dim"><?= number_format((int) ($dusun['jumlah'] ?? 0), 0, ',', '.') ?> jiwa</span></div>
                                <div class="h-2 rounded-full bg-surface-container overflow-hidden"><div class="h-full bg-primary rounded-full <?= $escape($dusunWidthClass) ?>"></div></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div>
                    <h3 class="font-h3 text-h3 text-ink mb-6">Mata Pencaharian</h3>
                    <div class="flex flex-col gap-4">
                        <?php foreach ($pekerjaan as $item): ?>
                            <?php $jobWidthClass = 'w-[' . max(0, min(100, (int) ($item['persen'] ?? 0))) . '%]'; ?>
                            <div>
                                <div class="flex justify-between gap-4 mb-2 text-sm"><span class="text-ink"><?= $escape($item['jenis'] ?? '') ?></span><span class="text-gold-soft"><?= (int) ($item['persen'] ?? 0) ?>%</span></div>
                                <div class="h-2 rounded-full bg-surface-container overflow-hidden"><div class="h-full bg-primary rounded-full <?= $escape($jobWidthClass) ?>"></div></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="w-full py-section-v-mobile lg:py-section-v-desktop bg-surface-container-lowest">
        <div class="max-w-container-max mx-auto px-container-pad-mobile lg:px-container-pad-desktop">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-10 gap-4">
                <div>
                    <h2 class="font-h2 text-h2 text-ink mb-2">Transparansi Anggaran (<?= (int) ($apbdes['tahun'] ?? date('Y')) ?>)</h2>
                    <p class="font-body-md text-body-md text-ink-dim">Ringkasan realisasi Anggaran Pendapatan dan Belanja Desa.</p>
                </div>
                <?php if (trim((string) ($apbdes['laporan_url'] ?? '')) !== ''): ?>
                    <a href="<?= $escape($apbdes['laporan_url']) ?>" target="_blank" rel="noopener noreferrer" class="px-6 py-2 rounded-full border border-line text-ink font-label-mono text-label-mono hover:bg-surface-2 transition-colors">UNDUH LAPORAN LENGKAP</a>
                <?php endif; ?>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <?php foreach ($apbItems as $item): ?>
                    <article class="bg-surface-2 p-6 rounded-xl flex flex-col gap-6 border border-line relative overflow-hidden">
                        <div class="flex items-start justify-between">
                            <div class="w-10 h-10 rounded bg-surface border border-line flex items-center justify-center"><span class="material-symbols-outlined text-primary"><?= $escape($item['icon'] ?? 'account_balance') ?></span></div>
                            <span class="font-label-mono text-label-mono text-gold-soft bg-surface-container px-2 py-1 rounded"><?= (int) ($item['persen'] ?? 0) ?>%</span>
                        </div>
                        <div><p class="font-body-md text-body-md text-ink-dim mb-1"><?= $escape($item['nama'] ?? '') ?></p><p class="font-h3 text-h3 text-ink"><?= $escape($item['jumlah'] ?? '') ?></p></div>
                        <div class="absolute inset-x-0 bottom-0 h-1 bg-primary"></div>
                    </article>
                <?php endforeach; ?>
            </div>
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
</div>

<div id="profile-photo-modal" class="hidden fixed inset-0 z-[140] items-center justify-center p-4 md:p-8" role="dialog" aria-modal="true" aria-label="Preview foto aparatur">
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
