<?php
$currentPage     = 'profil-desa';
$pageTitle       = 'Profil Desa | Pekon Air Naningan';
$metaDescription = 'Profil Pekon Air Naningan — sejarah, visi misi, struktur pemerintahan, data demografi, transparansi anggaran, dan peta administrasi.';
require __DIR__ . '/../partials/header.php';

$profil   = $profil ?? [];
$visi     = (string) ($profil['visi'] ?? '');
$misi     = is_array($profil['misi'] ?? null) ? $profil['misi'] : [];
$tagline  = (string) ($profil['tagline'] ?? '');
$tahun    = (int) ($profil['tahun_berdiri'] ?? 0);
$bakti    = (string) ($profil['masa_bakti'] ?? '');
$struktur = is_array($profil['struktur'] ?? null) ? $profil['struktur'] : [];
$demo     = is_array($profil['demografi'] ?? null) ? $profil['demografi'] : [];
$jobs     = is_array($profil['mata_pencaharian'] ?? null) ? $profil['mata_pencaharian'] : [];
$apbdes   = is_array($profil['apbdes'] ?? null) ? $profil['apbdes'] : [];
$apbItems = is_array($apbdes['items'] ?? null) ? $apbdes['items'] : [];
$sejarah  = is_array($profil['sejarah'] ?? null) ? $profil['sejarah'] : [];
$paras    = is_array($sejarah['paragraf'] ?? null) ? $sejarah['paragraf'] : [];
$quote    = (string) ($sejarah['quote'] ?? '');

$totalJiwa = (int) ($demo['total_jiwa'] ?? 0);
$kk        = (int) ($demo['kepala_keluarga'] ?? 0);
$luas      = $demo['luas_wilayah'] ?? 0;
$luasSat   = (string) ($demo['luas_satuan'] ?? 'km²');
$tinggi    = $demo['ketinggian'] ?? 0;
$tinggiSat = (string) ($demo['ketinggian_satuan'] ?? 'mdpl');
$apbTahun  = (int) ($apbdes['tahun'] ?? date('Y'));
$laporanUrl = trim((string) ($apbdes['laporan_url'] ?? ''));

$fmtNum = static fn($n) => number_format((float) $n, 0, ',', '.');
?>

<div class="flex flex-col w-full text-on-surface">

    <!-- Page Header & Breadcrumb -->
    <section class="w-full pt-12 pb-8 bg-surface-container-lowest">
        <div class="max-w-container-max mx-auto px-container-pad-mobile lg:px-container-pad-desktop">
            <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-label-mono text-ink-dim uppercase mb-6">
                <a class="hover:text-gold-soft transition-colors" href="<?= htmlspecialchars($base ?: '/', ENT_QUOTES, 'UTF-8') ?>">Beranda</a>
                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                <span class="text-primary">Profil Desa</span>
            </nav>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-end">
                <div>
                    <h1 class="font-h1-mobile lg:font-h1 text-h1-mobile lg:text-h1 text-ink mb-4">Profil Pekon Air Naningan</h1>
                    <?php if ($tagline !== ''): ?>
                    <p class="font-body-lg text-body-lg text-ink-dim max-w-xl">
                        <?= htmlspecialchars($tagline, ENT_QUOTES, 'UTF-8') ?>
                    </p>
                    <?php endif; ?>
                </div>
                <?php if ($tahun > 0): ?>
                <div class="hidden lg:flex justify-end pb-2">
                    <div class="flex items-center gap-4 bg-surface px-6 py-3 rounded-full border border-line">
                        <span class="font-label-mono text-label-mono text-gold-soft">BERDIRI SEJAK</span>
                        <span class="w-px h-4 bg-line-strong"></span>
                        <span class="font-h3 text-h3 text-ink"><?= $tahun ?></span>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Visi & Misi -->
    <section class="w-full py-section-v-mobile lg:py-section-v-desktop relative overflow-hidden">
        <div class="absolute inset-0 bg-surface -z-10"></div>
        <div class="absolute -top-64 -right-64 w-[800px] h-[800px] bg-gradient-to-br from-primary/5 to-transparent rounded-full blur-3xl -z-10 pointer-events-none"></div>
        <div class="max-w-container-max mx-auto px-container-pad-mobile lg:px-container-pad-desktop">
            <div class="flex flex-col lg:flex-row gap-16 lg:gap-24">
                <div class="flex-1 flex flex-col gap-6">
                    <div class="flex items-center gap-4">
                        <span class="w-12 h-px bg-primary"></span>
                        <h2 class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest">Arah Juang</h2>
                    </div>
                    <?php if ($visi !== ''): ?>
                    <h3 class="font-h2 text-h2 text-ink">"<?= htmlspecialchars($visi, ENT_QUOTES, 'UTF-8') ?>"</h3>
                    <?php endif; ?>
                </div>
                <div class="flex-1 flex flex-col gap-8">
                    <h4 class="font-label-mono text-label-mono text-ink-dim uppercase tracking-widest border-b border-line pb-4">Misi Pekon</h4>
                    <?php if ($misi !== []): ?>
                    <ul class="flex flex-col gap-6">
                        <?php foreach ($misi as $i => $item): ?>
                        <li class="flex gap-4">
                            <span class="font-h3 text-h3 text-primary/40"><?= str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
                            <p class="font-body-lg text-body-lg text-ink"><?= htmlspecialchars((string) $item, ENT_QUOTES, 'UTF-8') ?></p>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Struktur Organisasi & Data -->
    <section class="w-full py-section-v-mobile lg:py-section-v-desktop bg-surface-container-lowest">
        <div class="max-w-container-max mx-auto px-container-pad-mobile lg:px-container-pad-desktop">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter">

                <div class="lg:col-span-4 flex flex-col gap-8">
                    <div class="flex flex-col gap-2">
                        <h2 class="font-h3 text-h3 text-ink">Struktur Pemerintahan</h2>
                        <?php if ($bakti !== ''): ?>
                        <p class="font-body-md text-body-md text-ink-dim">Aparatur pekon masa bakti <?= htmlspecialchars($bakti, ENT_QUOTES, 'UTF-8') ?>.</p>
                        <?php endif; ?>
                    </div>
                    <?php if ($struktur !== []): ?>
                    <div class="flex flex-col gap-4 relative before:absolute before:inset-y-0 before:left-[19px] before:w-px before:bg-line-strong before:-z-10">
                        <?php foreach ($struktur as $row):
                            $level = (int) ($row['level'] ?? 0);
                            $ml = $level === 0 ? '' : ($level === 1 ? 'ml-4' : 'ml-8');
                            $foto = trim((string) ($row['foto'] ?? ''));
                            $nama = (string) ($row['nama'] ?? '');
                            $jab  = (string) ($row['jabatan'] ?? '');
                            $jabClass = $level === 0 ? 'text-gold-soft' : 'text-ink-dim';
                        ?>
                        <div class="flex items-center gap-4 group <?= $ml ?>">
                            <?php if ($foto !== ''): ?>
                            <div class="w-10 h-10 rounded-full bg-surface-2 overflow-hidden flex-shrink-0 border-2 border-surface shadow-sm group-hover:border-primary transition-colors">
                                <img class="w-full h-full object-cover"
                                     alt="<?= htmlspecialchars($jab . ' ' . $nama, ENT_QUOTES, 'UTF-8') ?>"
                                     src="<?= htmlspecialchars($foto, ENT_QUOTES, 'UTF-8') ?>">
                            </div>
                            <?php else: ?>
                            <div class="w-10 h-10 rounded-full bg-surface-container flex-shrink-0 border-2 border-surface shadow-sm group-hover:border-primary transition-colors flex items-center justify-center">
                                <span class="material-symbols-outlined text-ink-dim">person</span>
                            </div>
                            <?php endif; ?>
                            <div class="flex flex-col">
                                <span class="font-body-md text-body-md text-ink font-semibold"><?= htmlspecialchars($nama, ENT_QUOTES, 'UTF-8') ?></span>
                                <span class="font-label-mono text-label-mono <?= $jabClass ?>"><?= htmlspecialchars($jab, ENT_QUOTES, 'UTF-8') ?></span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="lg:col-span-8 flex flex-col gap-8">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-surface-2 p-6 rounded-xl flex flex-col gap-2 border border-line">
                            <span class="font-label-mono text-label-mono text-ink-dim uppercase">Total Jiwa</span>
                            <span class="font-h2 text-h2 text-primary"><?= $fmtNum($totalJiwa) ?></span>
                        </div>
                        <div class="bg-surface-2 p-6 rounded-xl flex flex-col gap-2 border border-line">
                            <span class="font-label-mono text-label-mono text-ink-dim uppercase">Kepala Keluarga</span>
                            <span class="font-h2 text-h2 text-primary"><?= $fmtNum($kk) ?></span>
                        </div>
                        <div class="bg-surface-2 p-6 rounded-xl flex flex-col gap-2 border border-line">
                            <span class="font-label-mono text-label-mono text-ink-dim uppercase">Luas Wilayah</span>
                            <span class="font-h2 text-h2 text-primary"><?= $fmtNum($luas) ?><span class="text-h3 font-h3 text-ink-dim"><?= htmlspecialchars($luasSat, ENT_QUOTES, 'UTF-8') ?></span></span>
                        </div>
                        <div class="bg-surface-2 p-6 rounded-xl flex flex-col gap-2 border border-line">
                            <span class="font-label-mono text-label-mono text-ink-dim uppercase">Ketinggian</span>
                            <span class="font-h2 text-h2 text-primary"><?= $fmtNum($tinggi) ?><span class="text-h3 font-h3 text-ink-dim"><?= htmlspecialchars($tinggiSat, ENT_QUOTES, 'UTF-8') ?></span></span>
                        </div>
                    </div>

                    <?php if ($jobs !== []): ?>
                    <div class="bg-surface rounded-xl p-8 border border-line flex flex-col gap-6">
                        <h3 class="font-h3 text-h3 text-ink">Distribusi Mata Pencaharian</h3>
                        <div class="flex flex-col gap-4 w-full">
                            <?php foreach ($jobs as $ji => $job):
                                $persen = (int) ($job['persen'] ?? 0);
                                $barClass = $ji === 0 ? 'bg-primary' : 'bg-primary-container';
                            ?>
                            <div class="flex flex-col gap-1 w-full">
                                <div class="flex justify-between items-end">
                                    <span class="font-body-md text-body-md text-ink"><?= htmlspecialchars((string) ($job['jenis'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span>
                                    <span class="font-label-mono text-label-mono text-gold-soft"><?= $persen ?>%</span>
                                </div>
                                <div class="w-full bg-surface-container-high h-2 rounded-full overflow-hidden">
                                    <div class="<?= $barClass ?> h-full rounded-full" style="width:<?= max(0, min(100, $persen)) ?>%"></div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </section>

    <!-- Transparansi APBDes -->
    <?php if ($apbItems !== []): ?>
    <section class="w-full py-section-v-mobile lg:py-section-v-desktop bg-surface">
        <div class="max-w-container-max mx-auto px-container-pad-mobile lg:px-container-pad-desktop">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-10 gap-4">
                <div>
                    <h2 class="font-h2 text-h2 text-ink mb-2">Transparansi Anggaran (<?= $apbTahun ?>)</h2>
                    <p class="font-body-md text-body-md text-ink-dim">Ringkasan realisasi Anggaran Pendapatan dan Belanja Desa.</p>
                </div>
                <?php if ($laporanUrl !== ''): ?>
                <a href="<?= htmlspecialchars($laporanUrl, ENT_QUOTES, 'UTF-8') ?>"
                   class="px-6 py-2 rounded-full border border-line text-ink font-label-mono text-label-mono hover:bg-surface-2 transition-colors"
                   target="_blank" rel="noopener">
                    UNDUH LAPORAN LENGKAP
                </a>
                <?php else: ?>
                <span class="px-6 py-2 rounded-full border border-line text-ink-dim font-label-mono text-label-mono opacity-50 cursor-not-allowed">
                    UNDUH LAPORAN LENGKAP
                </span>
                <?php endif; ?>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <?php foreach ($apbItems as $item): ?>
                <div class="bg-surface-2 p-6 rounded-xl flex flex-col gap-6 border border-line relative overflow-hidden group">
                    <div class="flex items-start justify-between relative z-10">
                        <div class="w-10 h-10 rounded bg-surface border border-line flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary"><?= htmlspecialchars((string) ($item['icon'] ?? 'account_balance'), ENT_QUOTES, 'UTF-8') ?></span>
                        </div>
                        <span class="font-label-mono text-label-mono text-gold-soft bg-surface-container px-2 py-1 rounded"><?= (int) ($item['persen'] ?? 0) ?>%</span>
                    </div>
                    <div class="flex flex-col relative z-10">
                        <span class="font-body-md text-body-md text-ink-dim mb-1"><?= htmlspecialchars((string) ($item['nama'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span>
                        <span class="font-h3 text-h3 text-ink"><?= htmlspecialchars((string) ($item['jumlah'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                    <div class="absolute inset-x-0 bottom-0 h-1 bg-primary/20 group-hover:bg-primary transition-colors"></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Sejarah Desa -->
    <?php if ($paras !== [] || $quote !== ''): ?>
    <section class="w-full py-section-v-mobile lg:py-section-v-desktop bg-surface-container-lowest border-t border-line">
        <div class="max-w-container-max mx-auto px-container-pad-mobile lg:px-container-pad-desktop">
            <div class="max-w-3xl mx-auto flex flex-col gap-12">
                <div class="text-center flex flex-col gap-4">
                    <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest">Napak Tilas</span>
                    <h2 class="font-h2 text-h2 text-ink">Sejarah Air Naningan</h2>
                </div>
                <div class="font-body-lg text-body-lg text-ink-dim">
                    <?php
                    $mid = (int) floor(count($paras) / 2);
                    foreach ($paras as $pi => $p):
                        if ($pi === $mid && $quote !== ''):
                    ?>
                    <blockquote class="my-10 pl-6 border-l-4 border-primary italic font-h3 text-h3 text-ink bg-surface-2/50 py-4 pr-6 rounded-r-xl">
                        "<?= htmlspecialchars($quote, ENT_QUOTES, 'UTF-8') ?>"
                    </blockquote>
                    <?php endif; ?>
                    <p class="<?= $pi < count($paras) - 1 ? 'mb-6' : '' ?>">
                        <?= htmlspecialchars((string) $p, ENT_QUOTES, 'UTF-8') ?>
                    </p>
                    <?php endforeach; ?>
                    <?php if ($paras === [] && $quote !== ''): ?>
                    <blockquote class="my-10 pl-6 border-l-4 border-primary italic font-h3 text-h3 text-ink bg-surface-2/50 py-4 pr-6 rounded-r-xl">
                        "<?= htmlspecialchars($quote, ENT_QUOTES, 'UTF-8') ?>"
                    </blockquote>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
