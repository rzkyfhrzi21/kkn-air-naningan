<?php
/**
 * View: Detail Berita
 *
 * Variabel dari BeritaController::detail():
 *   $berita  array   — data artikel (id, judul, slug, kategori, ringkasan, konten,
 *                       foto_sampul, penulis, status, tanggal_terbit, tags, created_at)
 *   $terkait array[] — berita terbit lain, satu artikel terbaru per kategori
 */

// Fallback supaya view tidak error jika di-preview tanpa controller
$berita = $berita ?? [
    'id'             => 'preview',
    'judul'          => 'Panen Raya Kopi Robusta Membawa Berkah Bagi Petani Air Naningan',
    'slug'           => 'panen-raya-kopi-robusta-2024',
    'kategori'       => 'Pertanian',
    'ringkasan'      => 'Musim kemarau yang berkepanjangan sempat menimbulkan kekhawatiran, namun hasil panen kopi Robusta tahun ini di Pekon Air Naningan justru menunjukkan kualitas yang luar biasa dengan peningkatan harga jual di pasaran.',
    'konten'         => '',
    'foto_sampul'    => '',
    'penulis'        => 'Admin Desa',
    'tanggal_terbit' => '2024-08-12',
    'tags'           => ['Kopi', 'Pertanian', 'Ekonomi'],
];
$terkait = $terkait ?? [];

// Format tanggal ke "12 Agustus 2024"
$bulan = ['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni',
           '07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'];
$tglParts   = explode('-', $berita['tanggal_terbit'] ?? date('Y-m-d'));
$tglFormatted = ($tglParts[2] ?? '') . ' ' . ($bulan[$tglParts[1] ?? ''] ?? '') . ' ' . ($tglParts[0] ?? '');

$pageTitle       = htmlspecialchars($berita['judul'], ENT_QUOTES, 'UTF-8') . ' — Pekon Air Naningan';
$currentPage     = 'berita';
$metaDescription = htmlspecialchars($berita['ringkasan'] ?? '', ENT_QUOTES, 'UTF-8');

require __DIR__ . '/../partials/header.php';
?>

<div class="flex flex-col w-full">

    <!-- ── Breadcrumb & Judul ─────────────────────────────────────────────── -->
    <section class="max-w-container-max mx-auto px-container-pad-mobile lg:px-container-pad-desktop pt-8 pb-10 w-full">

        <nav aria-label="Breadcrumb"
             class="flex items-center gap-2 text-label-mono font-label-mono text-ink-dim uppercase tracking-widest mb-8">
            <a class="hover:text-gold-soft transition-colors" href="<?= htmlspecialchars($base ?: '/', ENT_QUOTES) ?>">Beranda</a>
            <span class="text-line-strong" aria-hidden="true">/</span>
            <a class="hover:text-gold-soft transition-colors" href="<?= htmlspecialchars($base . '/berita', ENT_QUOTES) ?>">Berita</a>
            <span class="text-line-strong" aria-hidden="true">/</span>
            <span class="text-primary truncate max-w-[180px] md:max-w-xs lg:max-w-md">
                <?= htmlspecialchars($berita['judul'], ENT_QUOTES) ?>
            </span>
        </nav>

        <div class="max-w-4xl">
            <div class="flex flex-wrap items-center gap-3 mb-6">
                <span class="bg-primary/10 text-primary px-3 py-1 rounded-full text-label-mono font-label-mono uppercase tracking-widest">
                    <?= htmlspecialchars($berita['kategori'] ?? '', ENT_QUOTES) ?>
                </span>
                <span class="text-ink-dim font-body-md text-body-md flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                    <?= htmlspecialchars($tglFormatted, ENT_QUOTES) ?>
                </span>
                <span class="text-ink-dim font-body-md text-body-md flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">edit</span>
                    <?= htmlspecialchars($berita['penulis'] ?? 'Admin Desa', ENT_QUOTES) ?>
                </span>
            </div>

            <h1 class="text-h1-mobile md:text-h1 font-h1 text-gold-soft mb-2 leading-tight">
                <?= htmlspecialchars($berita['judul'], ENT_QUOTES) ?>
            </h1>
        </div>
    </section>

    <!-- ── Foto Sampul ────────────────────────────────────────────────────── -->
    <section class="max-w-container-max mx-auto px-container-pad-mobile lg:px-container-pad-desktop w-full mb-14">
        <div class="w-full h-[340px] md:h-[520px] rounded-xl overflow-hidden relative shadow-2xl bg-surface-container-highest">
            <?php if (!empty($berita['foto_sampul'])): ?>
                <img class="w-full h-full object-cover"
                     src="<?= htmlspecialchars(mediaUrl($berita['foto_sampul'], $base), ENT_QUOTES) ?>"
                     alt="<?= htmlspecialchars($berita['judul'], ENT_QUOTES) ?>">
            <?php else: ?>
                <div class="w-full h-full bg-surface-2 flex flex-col items-center justify-center gap-3">
                    <span class="material-symbols-outlined text-[72px] text-ink-dim/20">newspaper</span>
                </div>
            <?php endif; ?>
            <!-- Gradient overlay bawah -->
            <div class="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-bg/70 to-transparent pointer-events-none"></div>
            <?php if (!empty($berita['foto_sampul'])): ?>
            <p class="absolute bottom-4 left-6 text-ink-dim text-sm font-body-md">
                <?= htmlspecialchars($berita['judul'], ENT_QUOTES) ?> &mdash; Dokumentasi Desa
            </p>
            <?php endif; ?>
        </div>
    </section>

    <!-- ── Konten Artikel + Sidebar ──────────────────────────────────────── -->
    <section class="max-w-container-max mx-auto px-container-pad-mobile lg:px-container-pad-desktop
                    w-full pb-section-v-mobile lg:pb-section-v-desktop
                    grid grid-cols-1 lg:grid-cols-12 gap-gutter">

        <!-- ── Artikel Utama ──────────────────────────────────────────────── -->
        <article class="lg:col-span-8 flex flex-col gap-6 font-body-lg text-body-lg text-ink leading-relaxed">

            <?php if (!empty($berita['ringkasan'])): ?>
            <p class="text-xl font-medium border-l-4 border-primary pl-5 py-1 text-ink-dim">
                <?= htmlspecialchars($berita['ringkasan'], ENT_QUOTES) ?>
            </p>
            <?php endif; ?>

            <?php if (!empty($berita['konten'])): ?>
                <!-- Konten sudah di-sanitize strip_tags whitelist di Model::sanitizeHtml() -->
                <div class="artikel-konten flex flex-col gap-6 [&_h2]:text-h3 [&_h2]:font-h3 [&_h2]:text-gold-soft [&_h2]:mt-8 [&_h2]:mb-4 [&_h3]:text-[22px] [&_h3]:font-h3 [&_h3]:text-gold-soft [&_h3]:mt-6 [&_h3]:mb-3 [&_blockquote]:bg-surface-2 [&_blockquote]:p-6 [&_blockquote]:rounded-xl [&_blockquote]:border-l-4 [&_blockquote]:border-primary [&_blockquote]:italic [&_a]:text-primary [&_a]:underline [&_ul]:list-disc [&_ul]:pl-6 [&_ol]:list-decimal [&_ol]:pl-6">
                    <?= $berita['konten'] ?>
                </div>
            <?php endif; ?>

            <!-- ── Tags & Share ─────────────────────────────────────────── -->
            <div class="mt-10 pt-6 border-t border-line-strong flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <?php $tags = $berita['tags'] ?? []; ?>
                <?php if (!empty($tags)): ?>
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-label-mono font-label-mono text-ink-dim uppercase tracking-wider">Tags:</span>
                    <?php foreach ($tags as $tag): ?>
                        <a href="<?= htmlspecialchars($base . '/berita?tag=' . urlencode($tag), ENT_QUOTES) ?>"
                           class="bg-surface px-3 py-1 rounded text-sm text-primary hover:bg-surface-container transition-colors border border-line">
                            <?= htmlspecialchars($tag, ENT_QUOTES) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                <div class="flex items-center gap-3 shrink-0">
                    <span class="text-label-mono font-label-mono text-ink-dim uppercase tracking-wider">Bagikan:</span>
                    <button onclick="if(navigator.share){navigator.share({title:document.title,url:location.href})}else{navigator.clipboard.writeText(location.href);this.title='Disalin!'}"
                            title="Bagikan artikel ini"
                            class="w-9 h-9 rounded-full bg-surface-2 border border-line flex items-center justify-center text-ink hover:text-primary hover:border-primary transition-colors">
                        <span class="material-symbols-outlined text-[18px]">share</span>
                    </button>
                </div>
            </div>

            <!-- ── Navigasi Prev/Next ──────────────────────────────────── -->
            <div class="mt-4">
                <a href="<?= htmlspecialchars($base . '/berita', ENT_QUOTES) ?>"
                   class="inline-flex items-center gap-2 text-ink-dim hover:text-primary transition-colors font-label-mono text-label-mono uppercase tracking-wider">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    Semua Berita
                </a>
            </div>
        </article>

        <!-- ── Sidebar ────────────────────────────────────────────────────── -->
        <aside class="lg:col-span-4 flex flex-col gap-8 mt-10 lg:mt-0">

            <!-- Berita terbaru, satu dari setiap kategori -->
            <div class="bg-surface-container rounded-xl p-6 border border-line shadow-sm">
                <h3 class="text-h3 font-h3 text-gold-soft mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[22px]">newspaper</span>
                    Berita Terbaru
                </h3>

                <?php if (!empty($terkait)): ?>
                <div class="flex flex-col gap-5 divide-y divide-line">
                    <?php foreach ($terkait as $item): ?>
                    <a class="group flex gap-4 items-start pt-5 first:pt-0"
                       href="<?= htmlspecialchars($base . '/berita/' . ($item['slug'] ?? ''), ENT_QUOTES) ?>">
                        <div class="w-20 h-20 shrink-0 rounded-lg overflow-hidden bg-surface-2 border border-line flex items-center justify-center">
                            <?php if (!empty($item['foto_sampul'])): ?>
                                <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                     src="<?= htmlspecialchars(mediaUrl($item['foto_sampul'], $base), ENT_QUOTES) ?>"
                                     alt="<?= htmlspecialchars($item['judul'] ?? '', ENT_QUOTES) ?>">
                            <?php else: ?>
                                <span class="material-symbols-outlined text-[28px] text-ink-dim/30">newspaper</span>
                            <?php endif; ?>
                        </div>
                        <div class="flex flex-col min-w-0">
                            <span class="text-label-mono font-label-mono text-primary uppercase tracking-wider mb-1 text-[10px]">
                                <?= htmlspecialchars($item['kategori'] ?? '', ENT_QUOTES) ?>
                            </span>
                            <h4 class="text-body-md font-body-md text-ink group-hover:text-gold-soft transition-colors line-clamp-2 leading-snug">
                                <?= htmlspecialchars($item['judul'] ?? '', ENT_QUOTES) ?>
                            </h4>
                            <span class="text-xs text-ink-dim mt-1.5">
                                <?php
                                $tp = explode('-', $item['tanggal_terbit'] ?? '');
                                echo htmlspecialchars(($tp[2] ?? '') . ' ' . ($bulan[$tp[1] ?? ''] ?? '') . ' ' . ($tp[0] ?? ''), ENT_QUOTES);
                                ?>
                            </span>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <p class="text-ink-dim font-body-md text-sm">Belum ada berita lain.</p>
                <?php endif; ?>
            </div>

        </aside>
    </section>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
