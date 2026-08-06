<?php
$pageTitle = 'Kelola Profil Desa';
$activeNav = 'kelola-profil';
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
$sejarah  = is_array($profil['sejarah'] ?? null) ? $profil['sejarah'] : [];
$paragrafSejarah = $sejarah['paragraf'] ?? [];
if (is_array($paragrafSejarah)) {
    $sejarahHtml = '';
    foreach ($paragrafSejarah as $p) {
        $sejarahHtml .= '<p>' . nl2br(htmlspecialchars((string) $p, ENT_QUOTES, 'UTF-8')) . '</p>' . PHP_EOL;
    }
} else {
    $sejarahHtml = (string) $paragrafSejarah;
}
$quote    = (string) ($sejarah['quote'] ?? '');
$peta     = is_array($profil['peta'] ?? null) ? $profil['peta'] : [];
$perDusun = is_array($demo['per_dusun'] ?? null) ? $demo['per_dusun'] : [];
$csrf     = (string) ($_SESSION['csrf_token'] ?? '');
$base     = defined('APP_BASE') ? APP_BASE : '';

if ($misi === []) {
    $misi = [''];
}
if ($jobs === []) {
    $jobs = [
        ['jenis' => 'Petani Kopi & Kebun', 'persen' => 0],
        ['jenis' => 'Pedagang / Wiraswasta', 'persen' => 0],
        ['jenis' => 'Buruh Harian Lepas', 'persen' => 0],
        ['jenis' => 'PNS / TNI / Polri', 'persen' => 0],
    ];
}
if ($perDusun === []) {
    $perDusun = [
        ['nama' => 'Dusun 1', 'jumlah' => 0],
        ['nama' => 'Dusun 2', 'jumlah' => 0],
        ['nama' => 'Dusun 3', 'jumlah' => 0],
        ['nama' => 'Dusun 4', 'jumlah' => 0],
    ];
}
if ($struktur === []) {
    $struktur = [
        ['nama' => '', 'jabatan' => 'Kepala Pekon', 'foto' => '', 'level' => 0],
    ];
}
?>
<div class="flex flex-col w-full px-container-pad-mobile lg:px-container-pad-desktop pb-section-v-desktop gap-10">

    <div class="flex flex-col gap-2 pt-10 max-w-2xl">
        <h1 class="font-h1-mobile lg:font-h1 text-h1-mobile lg:text-h1 text-ink">Profil Desa</h1>
        <p class="font-body-lg text-body-lg text-ink-dim">Kelola data profil pekon — identitas, sejarah, peta, visi misi, struktur, demografi, dan mata pencaharian.</p>
    </div>


    <form id="form-profil" class="flex flex-col gap-gutter" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>">

        <!-- Tab Nav -->
        <nav class="flex gap-2 overflow-x-auto pb-1 -mx-1 px-1 no-scrollbar" aria-label="Tab kelola profil">
            <button type="button" data-tab="identitas"
                    class="profil-tab-btn flex items-center gap-2 px-4 py-2.5 rounded-xl border font-body-md text-sm whitespace-nowrap shrink-0 transition-all bg-primary border-primary text-on-primary shadow-md shadow-primary/20">
                <span class="material-symbols-outlined text-[18px]">badge</span>
                Identitas, Sejarah &amp; Peta
            </button>
            <button type="button" data-tab="visi-struktur"
                    class="profil-tab-btn flex items-center gap-2 px-4 py-2.5 rounded-xl border font-body-md text-sm whitespace-nowrap shrink-0 transition-all bg-surface-2 border-line text-ink-dim hover:text-ink">
                <span class="material-symbols-outlined text-[18px]">visibility</span>
                Visi, Misi &amp; Struktur
            </button>
            <button type="button" data-tab="demografi"
                    class="profil-tab-btn flex items-center gap-2 px-4 py-2.5 rounded-xl border font-body-md text-sm whitespace-nowrap shrink-0 transition-all bg-surface-2 border-line text-ink-dim hover:text-ink">
                <span class="material-symbols-outlined text-[18px]">groups</span>
                Demografi &amp; Pencaharian
            </button>
        </nav>

        <!-- Panel: Identitas, Sejarah & Peta -->
        <div data-panel="identitas" class="profil-tab-panel flex flex-col gap-gutter">

            <!-- Identitas singkat -->
            <section class="bg-surface-container rounded-2xl p-6 md:p-8 flex flex-col gap-6 shadow-sm">
                <div class="flex items-center gap-3 border-b border-line pb-4">
                    <div class="w-10 h-10 rounded-xl bg-surface-2 flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary">badge</span>
                    </div>
                    <h2 class="font-h3 text-h3 text-ink">Identitas</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="flex flex-col gap-2">
                        <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest">Tahun Berdiri</span>
                        <input name="tahun_berdiri" type="number" min="1800" max="2100"
                               value="<?= $tahun ?>"
                               class="bg-surface border border-line-strong rounded-xl p-3 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary">
                    </label>
                    <label class="flex flex-col gap-2">
                        <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest">Masa Bakti</span>
                        <input name="masa_bakti" type="text" placeholder="2022 - 2028"
                               value="<?= htmlspecialchars($bakti, ENT_QUOTES, 'UTF-8') ?>"
                               class="bg-surface border border-line-strong rounded-xl p-3 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary">
                    </label>
                </div>
                <label class="flex flex-col gap-2">
                    <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest">Tagline Header</span>
                    <textarea name="tagline" rows="2"
                              class="bg-surface border border-line-strong rounded-xl p-4 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary resize-y"
                              placeholder="Teks di bawah judul Profil..."><?= htmlspecialchars($tagline, ENT_QUOTES, 'UTF-8') ?></textarea>
                </label>
            </section>

            <!-- Sejarah -->
            <section class="bg-surface-container rounded-2xl p-6 md:p-8 flex flex-col gap-6 shadow-sm">
                <div class="flex items-center gap-3 border-b border-line pb-4">
                    <div class="w-10 h-10 rounded-xl bg-surface-2 flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary">history_edu</span>
                    </div>
                    <h2 class="font-h3 text-h3 text-ink">Sejarah Desa</h2>
                </div>
                <div class="flex flex-col gap-2">
                    <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest">Narasi (pisah paragraf dengan baris kosong)</span>
                    <div id="rte-toolbar" role="toolbar" aria-label="Format teks narasi"
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
                    <div id="rte-narasi" contenteditable="true"
                         class="bg-surface border border-line-strong rounded-b-xl p-4 min-h-[240px] text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary focus:ring-offset-0 prose max-w-none"
                         role="textbox" aria-multiline="true" aria-label="Narasi sejarah desa"></div>
                    <textarea name="sejarah_teks" id="sejarah-teks-input" class="hidden"></textarea>
                    <span class="text-xs text-ink-dim">Enter = paragraf baru · Shift+Enter = baris baru tanpa paragraf.</span>
                </div>
                <div class="flex flex-col gap-2">
                    <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest">Kutipan / Quote</span>
                    <textarea name="sejarah_quote" rows="2"
                              class="bg-surface border border-line-strong rounded-xl p-3 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary resize-y"
                              placeholder="Kutipan di tengah narasi sejarah..."><?= htmlspecialchars($quote, ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>
            </section>

            <!-- Peta -->
            <section class="bg-surface-container rounded-2xl p-6 md:p-8 flex flex-col gap-6 shadow-sm">
                <div class="flex items-center gap-3 border-b border-line pb-4">
                    <div class="w-10 h-10 rounded-xl bg-surface-2 flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary">map</span>
                    </div>
                    <h2 class="font-h3 text-h3 text-ink">Peta Administrasi</h2>
                </div>
                <label class="flex flex-col gap-2">
                    <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest">Nama Lokasi</span>
                    <input name="peta_lokasi" type="text"
                           value="<?= htmlspecialchars((string) ($peta['lokasi'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                           class="bg-surface border border-line-strong rounded-xl p-3 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary"
                           placeholder="Air Naningan, Tanggamus, Lampung, Indonesia">
                </label>
                <label class="flex flex-col gap-2">
                    <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest">URL Embed Google Maps</span>
                    <input name="peta_embed_url" type="url"
                           value="<?= htmlspecialchars((string) ($peta['embed_url'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                           class="bg-surface border border-line-strong rounded-xl p-3 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary"
                           placeholder="https://www.google.com/maps/embed?...">
                    <span class="text-xs text-ink-dim">Gunakan URL pada atribut <code>src</code> dari menu Bagikan &gt; Sematkan peta.</span>
                </label>
            </section>
        </div>

        <!-- Panel: Visi, Misi & Struktur -->
        <div data-panel="visi-struktur" class="profil-tab-panel hidden flex-col gap-gutter">

            <!-- Visi & Misi -->
            <section class="bg-surface-container rounded-2xl p-6 md:p-8 flex flex-col gap-6 shadow-sm">
                <div class="flex items-center gap-3 border-b border-line pb-4">
                    <div class="w-10 h-10 rounded-xl bg-surface-2 flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary">visibility</span>
                    </div>
                    <h2 class="font-h3 text-h3 text-ink">Visi &amp; Misi</h2>
                </div>
                <label class="flex flex-col gap-2">
                    <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest">Visi Desa</span>
                    <textarea name="visi" required rows="4"
                              class="bg-surface border border-line-strong rounded-xl p-4 min-h-[100px] text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary resize-y"
                              placeholder="Masukkan visi strategis desa..."><?= htmlspecialchars($visi, ENT_QUOTES, 'UTF-8') ?></textarea>
                </label>
                <div class="flex flex-col gap-4">
                    <div class="flex justify-between items-end">
                        <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest">Misi Desa</span>
                        <button class="text-primary hover:text-primary-fixed flex items-center gap-1 font-label-mono text-[10px] uppercase transition-colors" type="button" id="btn-add-misi">
                            <span class="material-symbols-outlined text-[16px]">add</span> Tambah Misi
                        </button>
                    </div>
                    <div class="flex flex-col gap-3" id="misi-list">
                        <?php foreach ($misi as $i => $m): ?>
                        <div class="flex items-start gap-3 group/item misi-row">
                            <span class="mt-3 text-ink-dim font-label-mono text-[10px] w-4 text-right misi-num"><?= str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
                            <input name="misi[]" type="text" value="<?= htmlspecialchars((string) $m, ENT_QUOTES, 'UTF-8') ?>"
                                   class="flex-1 bg-surface border border-line-strong rounded-xl p-3 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary"
                                   placeholder="Isi misi...">
                            <button type="button" aria-label="Hapus misi" class="mt-2 p-2 text-danger opacity-0 group-hover/item:opacity-100 transition-opacity hover:bg-surface-2 rounded-lg btn-remove-misi">
                                <span class="material-symbols-outlined text-[18px]">delete</span>
                            </button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

            <!-- Struktur -->
            <section class="bg-surface-container rounded-2xl p-6 md:p-8 flex flex-col gap-6 shadow-sm">
                <div class="flex items-center justify-between border-b border-line pb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-surface-2 flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary">account_tree</span>
                        </div>
                        <h2 class="font-h3 text-h3 text-ink">Struktur Pemerintahan</h2>
                    </div>
                    <button type="button" id="btn-add-struktur" class="text-primary hover:text-primary-fixed flex items-center gap-1 font-label-mono text-[10px] uppercase transition-colors">
                        <span class="material-symbols-outlined text-[16px]">add</span> Tambah
                    </button>
                </div>
                <p class="font-body-md text-body-md text-ink-dim text-sm">Level: <code>0</code> = Kepala Pekon &middot; <code>1</code> = Sekretaris &middot; <code>2</code> = KAUR/KASI/Staf &middot; <code>3</code> = Kepala Dusun. Foto: jpg/png/webp/gif maks 2 MB.</p>
                <div class="flex flex-col gap-3" id="struktur-list">
                    <?php foreach ($struktur as $s):
                        $fotoLama = trim((string) ($s['foto'] ?? ''));
                    ?>
                    <div class="flex flex-col md:flex-row gap-3 items-start struktur-row bg-surface p-3 rounded-xl border border-line">
                        <!-- Thumbnail + file input -->
                        <div class="flex flex-col items-center gap-1.5 shrink-0">
                            <div class="w-14 h-14 rounded-full overflow-hidden border-2 border-line-strong bg-surface-2 flex items-center justify-center struktur-foto-preview">
                                <?php if ($fotoLama !== ''): ?>
                                    <img src="<?= htmlspecialchars($fotoLama, ENT_QUOTES, 'UTF-8') ?>" class="w-full h-full object-cover" alt="">
                                <?php else: ?>
                                    <span class="material-symbols-outlined text-ink-dim text-[22px]">person</span>
                                <?php endif; ?>
                            </div>
                            <label class="cursor-pointer text-[9px] text-primary font-bold uppercase tracking-widest hover:underline">
                                Ganti
                                <input type="file" name="struktur_foto_file[]" accept="image/jpeg,image/png,image/webp,image/gif" class="hidden struktur-foto-input">
                            </label>
                            <!-- Path foto lama, dikosongkan jika ada file baru -->
                            <input type="hidden" name="struktur_foto[]" value="<?= htmlspecialchars($fotoLama, ENT_QUOTES, 'UTF-8') ?>" class="struktur-foto-hidden">
                        </div>
                        <!-- Fields -->
                        <div class="flex flex-1 flex-wrap gap-2 items-start">
                            <input name="struktur_nama[]" type="text" placeholder="Nama"
                                   value="<?= htmlspecialchars((string) ($s['nama'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                                   class="flex-1 min-w-[140px] bg-surface-2 border border-line-strong rounded-lg p-2.5 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary">
                            <input name="struktur_jabatan[]" type="text" placeholder="Jabatan"
                                   value="<?= htmlspecialchars((string) ($s['jabatan'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                                   class="flex-1 min-w-[140px] bg-surface-2 border border-line-strong rounded-lg p-2.5 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary">
                            <input name="struktur_level[]" type="number" min="0" max="5" placeholder="Lv"
                                   value="<?= (int) ($s['level'] ?? 0) ?>"
                                   class="w-16 bg-surface-2 border border-line-strong rounded-lg p-2.5 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary text-center">
                        </div>
                        <button type="button" class="p-2 text-danger hover:bg-surface-2 rounded-lg btn-remove-struktur self-center shrink-0" aria-label="Hapus">
                            <span class="material-symbols-outlined text-[18px]">delete</span>
                        </button>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>

        <!-- Panel: Demografi & Pencaharian -->
        <div data-panel="demografi" class="profil-tab-panel hidden flex-col gap-gutter">

            <!-- Demografi -->
            <section class="bg-surface-container rounded-2xl p-6 md:p-8 flex flex-col gap-6 shadow-sm">
                <div class="flex items-center gap-3 border-b border-line pb-4">
                    <div class="w-10 h-10 rounded-xl bg-surface-2 flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary">groups</span>
                    </div>
                    <h2 class="font-h3 text-h3 text-ink">Demografi</h2>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <label class="flex flex-col gap-1.5">
                        <span class="text-xs text-ink-dim font-body-md">Total Jiwa</span>
                        <input name="total_jiwa" type="number" min="0"
                               value="<?= (int) ($demo['total_jiwa'] ?? 0) ?>"
                               class="w-full bg-surface border border-line-strong rounded-lg p-2.5 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary">
                    </label>
                    <label class="flex flex-col gap-1.5">
                        <span class="text-xs text-ink-dim font-body-md">Kepala Keluarga</span>
                        <input name="kepala_keluarga" type="number" min="0"
                               value="<?= (int) ($demo['kepala_keluarga'] ?? 0) ?>"
                               class="w-full bg-surface border border-line-strong rounded-lg p-2.5 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary">
                    </label>
                    <label class="flex flex-col gap-1.5">
                        <span class="text-xs text-ink-dim font-body-md">Luas Wilayah</span>
                        <div class="flex gap-2">
                            <input name="luas_wilayah" type="number" min="0" step="0.1"
                                   value="<?= htmlspecialchars((string) ($demo['luas_wilayah'] ?? 0), ENT_QUOTES, 'UTF-8') ?>"
                                   class="w-full bg-surface border border-line-strong rounded-lg p-2.5 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary">
                            <input name="luas_satuan" type="text"
                                   value="<?= htmlspecialchars((string) ($demo['luas_satuan'] ?? 'km²'), ENT_QUOTES, 'UTF-8') ?>"
                                   class="w-20 bg-surface border border-line-strong rounded-lg p-2.5 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary">
                        </div>
                    </label>
                    <label class="flex flex-col gap-1.5">
                        <span class="text-xs text-ink-dim font-body-md">Ketinggian</span>
                        <div class="flex gap-2">
                            <input name="ketinggian" type="number" min="0" step="1"
                                   value="<?= htmlspecialchars((string) ($demo['ketinggian'] ?? 0), ENT_QUOTES, 'UTF-8') ?>"
                                   class="w-full bg-surface border border-line-strong rounded-lg p-2.5 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary">
                            <input name="ketinggian_satuan" type="text"
                                   value="<?= htmlspecialchars((string) ($demo['ketinggian_satuan'] ?? 'mdpl'), ENT_QUOTES, 'UTF-8') ?>"
                                   class="w-20 bg-surface border border-line-strong rounded-lg p-2.5 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary">
                        </div>
                    </label>
                </div>

                <div class="w-full h-px bg-line"></div>

                <div class="flex flex-col gap-3">
                    <div class="flex items-center justify-between">
                        <h3 class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest">Populasi per Dusun</h3>
                        <button type="button" id="btn-add-dusun" class="text-primary text-[10px] font-label-mono uppercase flex items-center gap-0.5">
                            <span class="material-symbols-outlined text-[14px]">add</span>
                        </button>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="dusun-list">
                        <?php foreach ($perDusun as $di => $d): ?>
                        <div class="grid grid-cols-[1fr_90px_28px] gap-1.5 items-center dusun-row">
                            <input name="dusun_nama[]" type="text"
                                   value="<?= htmlspecialchars((string) ($d['nama'] ?? ('Dusun ' . ($di + 1))), ENT_QUOTES, 'UTF-8') ?>"
                                   class="w-full bg-surface border border-line-strong rounded-lg p-2 text-xs text-on-surface focus:outline-none focus:ring-1 focus:ring-primary"
                                   placeholder="Nama dusun">
                            <input name="dusun_jumlah[]" type="number" min="0"
                                   value="<?= (int) ($d['jumlah'] ?? 0) ?>"
                                   class="w-full bg-surface border border-line-strong rounded-lg p-2.5 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary"
                                    placeholder="0">
                            <button type="button" class="p-1 text-danger btn-remove-dusun" aria-label="Hapus dusun">
                                <span class="material-symbols-outlined text-[16px]">close</span>
                            </button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

            <!-- Mata Pencaharian -->
            <section class="bg-surface-container rounded-2xl p-6 md:p-8 flex flex-col gap-6 shadow-sm">
                <div class="flex justify-between items-center border-b border-line pb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-surface-2 flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary">work</span>
                        </div>
                        <h2 class="font-h3 text-h3 text-ink">Mata Pencaharian</h2>
                    </div>
                    <button type="button" id="btn-add-job" class="text-primary text-[10px] font-label-mono uppercase flex items-center gap-0.5">
                        <span class="material-symbols-outlined text-[14px]">add</span> Tambah
                    </button>
                </div>
                <div class="flex flex-col gap-1">
                    <p class="font-body-md text-body-md text-ink-dim text-sm">Persentase per jenis pekerjaan penduduk.</p>
                    <p id="job-total" class="font-label-mono text-[11px] text-ink-dim">Total: 0% | Sisa: 100%</p>
                </div>
                <div class="flex flex-col gap-3" id="job-list">
                    <?php foreach ($jobs as $j): ?>
                    <div class="flex items-center gap-2 job-row bg-surface p-2 rounded-lg border border-line-strong">
                        <input name="pekerjaan_jenis[]" type="text"
                               value="<?= htmlspecialchars((string) ($j['jenis'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                               class="flex-1 bg-transparent text-sm text-ink font-body-md focus:outline-none"
                               placeholder="Jenis pekerjaan">
                        <input name="pekerjaan_persen[]" type="number" min="0" max="100" step="1"
                               value="<?= (int) ($j['persen'] ?? 0) ?>"
                               class="w-14 bg-transparent text-right text-on-surface font-body-md focus:outline-none job-percent-input"
                               placeholder="0">
                        <span class="text-ink-dim text-sm">%</span>
                        <button type="button" class="p-1 text-danger btn-remove-job" aria-label="Hapus">
                            <span class="material-symbols-outlined text-[16px]">close</span>
                        </button>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>

        <!-- Sticky Save Bar -->
        <div class="sticky bottom-0 z-30 -mx-4 md:-mx-8 px-4 md:px-8 py-4 bg-bg/85 backdrop-blur-md border-t border-line flex items-center justify-between gap-3 rounded-t-2xl">
            <a href="<?= htmlspecialchars($base . '/profil', ENT_QUOTES, 'UTF-8') ?>" target="_blank"
               class="px-5 py-2.5 rounded-full font-label-mono text-[11px] uppercase tracking-wider text-ink bg-surface-2 hover:bg-surface-container-highest transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                Lihat Publik
            </a>
            <button id="btn-simpan-profil" class="px-6 py-2.5 rounded-full font-label-mono text-[11px] uppercase tracking-wider bg-primary text-on-primary hover:bg-primary-fixed shadow-md shadow-primary/20 transition-all flex items-center gap-2" type="submit">
                <span class="material-symbols-outlined text-[16px]">save</span> Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .rte-btn { display: inline-flex; align-items: center; justify-content: center; width: 2rem; height: 2rem; border-radius: 0.5rem; color: var(--color-ink-dim, #64748b); transition: background-color .15s, color .15s; }
    .rte-btn:hover { background-color: var(--color-surface-container-highest, #e2e8f0); color: var(--color-ink, #0f172a); }
    .rte-btn[data-active="true"] { background-color: var(--color-primary-container, rgba(31,88,74,.15)); color: var(--color-primary, #1f584a); }
    #rte-narasi:empty::before { content: "Tuliskan sejarah pekon..."; color: var(--color-ink-dim, #64748b); opacity: .6; pointer-events: none; }
    #rte-narasi h2 { font-size: 1.25rem; font-weight: 700; margin: .75em 0 .4em; }
    #rte-narasi blockquote { border-left: 4px solid var(--color-primary, #1f584a); padding-left: 1rem; margin: .75em 0; font-style: italic; color: var(--color-ink-dim, #64748b); }
    #rte-narasi ul { list-style: disc; padding-left: 1.5rem; margin: .5em 0; }
    #rte-narasi ol { list-style: decimal; padding-left: 1.5rem; margin: .5em 0; }
    #rte-narasi p { margin: .5em 0; }
    #rte-narasi a { color: var(--color-primary, #1f584a); text-decoration: underline; }
</style>

<script>
(function () {
    const base = <?= json_encode($base, JSON_UNESCAPED_SLASHES) ?>;
    const form = document.getElementById('form-profil');

    function showToast(msg, ok) {
        window.showAdminToast(msg, ok);
    }

    function renumberMisi() {
        document.querySelectorAll('#misi-list .misi-row').forEach((row, i) => {
            const n = row.querySelector('.misi-num');
            if (n) n.textContent = String(i + 1).padStart(2, '0');
        });
    }

    // ── Tabs ──────────────────────────────────────────────────────────────────
    const tabNames = ['identitas', 'visi-struktur', 'demografi'];
    function activateTab(name) {
        document.querySelectorAll('.profil-tab-btn').forEach(b => {
            const active = b.dataset.tab === name;
            b.classList.toggle('bg-primary', active);
            b.classList.toggle('border-primary', active);
            b.classList.toggle('text-on-primary', active);
            b.classList.toggle('shadow-md', active);
            b.classList.toggle('shadow-primary/20', active);
            b.classList.toggle('bg-surface-2', !active);
            b.classList.toggle('border-line', !active);
            b.classList.toggle('text-ink-dim', !active);
        });
        document.querySelectorAll('.profil-tab-panel').forEach(p => {
            p.classList.toggle('hidden', p.dataset.panel !== name);
        });
        history.replaceState(null, '', '#' + name);
    }
    document.querySelectorAll('.profil-tab-btn').forEach(btn => {
        btn.addEventListener('click', () => activateTab(btn.dataset.tab));
    });
    const initial = location.hash.replace('#', '');
    activateTab(tabNames.includes(initial) ? initial : 'identitas');

    document.getElementById('btn-add-misi')?.addEventListener('click', () => {
        const list = document.getElementById('misi-list');
        const row = document.createElement('div');
        row.className = 'flex items-start gap-3 group/item misi-row';
        row.innerHTML = `
            <span class="mt-3 text-ink-dim font-label-mono text-[10px] w-4 text-right misi-num">00</span>
            <input name="misi[]" type="text" class="flex-1 bg-surface border border-line-strong rounded-xl p-3 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary" placeholder="Isi misi...">
            <button type="button" aria-label="Hapus misi" class="mt-2 p-2 text-danger opacity-0 group-hover/item:opacity-100 transition-opacity hover:bg-surface-2 rounded-lg btn-remove-misi">
                <span class="material-symbols-outlined text-[18px]">delete</span>
            </button>`;
        list.appendChild(row);
        renumberMisi();
        row.querySelector('input')?.focus();
    });

    document.getElementById('misi-list')?.addEventListener('click', (e) => {
        const btn = e.target.closest('.btn-remove-misi');
        if (!btn) return;
        const list = document.getElementById('misi-list');
        if (list.querySelectorAll('.misi-row').length <= 1) {
            showToast('Minimal satu misi harus ada.', false);
            return;
        }
        btn.closest('.misi-row')?.remove();
        renumberMisi();
    });

    // ── Helper: preview & validasi foto struktur ─────────────────────────────
    const FOTO_MAX_BYTES = 2 * 1024 * 1024; // 2 MB
    const FOTO_ACCEPT    = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

    function bindFotoInput(fileInput) {
        fileInput.addEventListener('change', () => {
            const file = fileInput.files[0];
            const row  = fileInput.closest('.struktur-row');
            const preview = row?.querySelector('.struktur-foto-preview');
            const hidden  = row?.querySelector('.struktur-foto-hidden');
            if (!file) return;
            // Validasi ukuran
            if (file.size > FOTO_MAX_BYTES) {
                showToast('Foto terlalu besar. Maksimal 2 MB.', false);
                fileInput.value = '';
                return;
            }
            // Validasi tipe
            if (!FOTO_ACCEPT.includes(file.type)) {
                showToast('Format foto tidak didukung. Gunakan JPG, PNG, WEBP, atau GIF.', false);
                fileInput.value = '';
                return;
            }
            // Tampilkan preview
            const reader = new FileReader();
            reader.onload = (ev) => {
                if (preview) preview.innerHTML = `<img src="${ev.target.result}" class="w-full h-full object-cover" alt="">`;
            };
            reader.readAsDataURL(file);
            // Kosongkan hidden (ada file baru, jangan pakai path lama)
            if (hidden) hidden.value = '';
        });
    }

    // Bind semua file input yang sudah ada saat load
    document.querySelectorAll('.struktur-foto-input').forEach(bindFotoInput);

    document.getElementById('btn-add-struktur')?.addEventListener('click', () => {
        const list = document.getElementById('struktur-list');
        const row  = document.createElement('div');
        row.className = 'flex flex-col md:flex-row gap-3 items-start struktur-row bg-surface p-3 rounded-xl border border-line';
        row.innerHTML = `
            <div class="flex flex-col items-center gap-1.5 shrink-0">
                <div class="w-14 h-14 rounded-full overflow-hidden border-2 border-line-strong bg-surface-2 flex items-center justify-center struktur-foto-preview">
                    <span class="material-symbols-outlined text-ink-dim text-[22px]">person</span>
                </div>
                <label class="cursor-pointer text-[9px] text-primary font-bold uppercase tracking-widest hover:underline">
                    Pilih Foto
                    <input type="file" name="struktur_foto_file[]" accept="image/jpeg,image/png,image/webp,image/gif" class="hidden struktur-foto-input">
                </label>
                <input type="hidden" name="struktur_foto[]" value="" class="struktur-foto-hidden">
            </div>
            <div class="flex flex-1 flex-wrap gap-2 items-start">
                <input name="struktur_nama[]" type="text" placeholder="Nama" class="flex-1 min-w-[140px] bg-surface-2 border border-line-strong rounded-lg p-2.5 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary">
                <input name="struktur_jabatan[]" type="text" placeholder="Jabatan" class="flex-1 min-w-[140px] bg-surface-2 border border-line-strong rounded-lg p-2.5 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary">
                <input name="struktur_level[]" type="number" min="0" max="5" value="2" class="w-16 bg-surface-2 border border-line-strong rounded-lg p-2.5 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary text-center">
            </div>
            <button type="button" class="p-2 text-danger hover:bg-surface-2 rounded-lg btn-remove-struktur self-center shrink-0" aria-label="Hapus">
                <span class="material-symbols-outlined text-[18px]">delete</span>
            </button>`;
        list.appendChild(row);
        // Bind file input baru
        bindFotoInput(row.querySelector('.struktur-foto-input'));
        row.querySelector('input[name="struktur_nama[]"]')?.focus();
    });

    document.getElementById('struktur-list')?.addEventListener('click', (e) => {
        const btn = e.target.closest('.btn-remove-struktur');
        if (btn) btn.closest('.struktur-row')?.remove();
    });

    document.getElementById('btn-add-job')?.addEventListener('click', () => {
        const list = document.getElementById('job-list');
        const row = document.createElement('div');
        row.className = 'flex items-center gap-2 job-row bg-surface p-2 rounded-lg border border-line-strong';
        row.innerHTML = `
            <input name="pekerjaan_jenis[]" type="text" class="flex-1 bg-transparent text-sm text-ink font-body-md focus:outline-none" placeholder="Jenis pekerjaan">
            <input name="pekerjaan_persen[]" type="number" min="0" max="100" step="1" value="0" class="w-14 bg-transparent text-right text-on-surface font-body-md focus:outline-none job-percent-input">
            <span class="text-ink-dim text-sm">%</span>
            <button type="button" class="p-1 text-danger btn-remove-job" aria-label="Hapus">
                <span class="material-symbols-outlined text-[16px]">close</span>
            </button>`;
        list.appendChild(row);
        updateJobPercentages();
    });

    function updateJobPercentages() {
        const inputs = [...document.querySelectorAll('#job-list .job-percent-input')];
        const total = inputs.reduce((sum, input) => sum + Math.max(0, Number(input.value) || 0), 0);
        const remaining = 100 - total;
        const totalLabel = document.getElementById('job-total');
        if (totalLabel) {
            totalLabel.textContent = `Total: ${total}% | Sisa: ${remaining}%`;
            totalLabel.classList.toggle('text-danger', total > 100);
            totalLabel.classList.toggle('text-primary', total === 100);
            totalLabel.classList.toggle('text-ink-dim', total < 100);
        }
        inputs.forEach(input => {
            const otherTotal = total - (Math.max(0, Number(input.value) || 0));
            input.max = String(Math.max(0, 100 - otherTotal));
        });
    }

    document.getElementById('job-list')?.addEventListener('input', (e) => {
        if (e.target.matches('input[name="pekerjaan_persen[]"]')) updateJobPercentages();
    });

    document.getElementById('job-list')?.addEventListener('click', (e) => {
        const btn = e.target.closest('.btn-remove-job');
        if (btn) {
            btn.closest('.job-row')?.remove();
            updateJobPercentages();
        }
    });

    updateJobPercentages();

    document.getElementById('btn-add-dusun')?.addEventListener('click', () => {
        const list = document.getElementById('dusun-list');
        const row = document.createElement('div');
        row.className = 'grid grid-cols-[1fr_90px_28px] gap-1.5 items-center dusun-row';
        row.innerHTML = `
            <input name="dusun_nama[]" type="text" class="w-full bg-surface border border-line-strong rounded-lg p-2 text-xs text-on-surface focus:outline-none focus:ring-1 focus:ring-primary" placeholder="Nama dusun">
            <input name="dusun_jumlah[]" type="number" min="0" value="0" class="w-full bg-surface border border-line-strong rounded-lg p-2.5 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary">
            <button type="button" class="p-1 text-danger btn-remove-dusun" aria-label="Hapus dusun"><span class="material-symbols-outlined text-[16px]">close</span></button>`;
        list.appendChild(row);
        row.querySelector('input')?.focus();
    });

    document.getElementById('dusun-list')?.addEventListener('click', (e) => {
        const btn = e.target.closest('.btn-remove-dusun');
        if (btn) btn.closest('.dusun-row')?.remove();
    });

    // ── Rich text editor: Narasi ─────────────────────────────────────────────
    const rte = document.getElementById('rte-narasi');
    const rteInput = document.getElementById('sejarah-teks-input');
    const rteToolbar = document.getElementById('rte-toolbar');
    const rteInitial = <?= json_encode($sejarahHtml, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?>;

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

    rte.innerHTML = rteInitial;
    rteInput.value = normalizeRichHtml(rte.innerHTML);

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

    rte.addEventListener('input', () => { rteInput.value = normalizeRichHtml(rte.innerHTML); });
    rte.addEventListener('blur', () => { rteInput.value = normalizeRichHtml(rte.innerHTML); });

    form?.addEventListener('submit', async (e) => {
        e.preventDefault();
        rteInput.value = normalizeRichHtml(rte.innerHTML);

        const jobInputs = [...document.querySelectorAll('#job-list input[name="pekerjaan_persen[]"]')];
        const jobTotal = jobInputs.reduce((sum, input) => sum + Math.max(0, Number(input.value) || 0), 0);
        if (jobInputs.length === 0) {
            showToast('Minimal satu jenis mata pencaharian harus diisi.', false);
            return;
        }
        if (jobTotal !== 100) {
            showToast(`Total persentase mata pencaharian harus tepat 100%. Saat ini ${jobTotal}%.`, false);
            return;
        }

        // Validasi client-side semua file foto sebelum kirim
        let fotoError = false;
        document.querySelectorAll('.struktur-foto-input').forEach(inp => {
            const file = inp.files[0];
            if (!file) return;
            if (file.size > FOTO_MAX_BYTES) {
                showToast(`Foto "${file.name}" melebihi batas 2 MB.`, false);
                fotoError = true;
            } else if (!FOTO_ACCEPT.includes(file.type)) {
                showToast(`Format foto "${file.name}" tidak didukung (JPG/PNG/WEBP/GIF).`, false);
                fotoError = true;
            }
        });
        if (fotoError) return;

        const btn = document.getElementById('btn-simpan-profil');
        btn.disabled = true;
        btn.classList.add('opacity-60');

        try {
            const fd = new FormData(form);
            const res = await fetch(base + '/admin/ajax/store-profil', {
                method: 'POST',
                body: fd,
                credentials: 'same-origin',
            });
            const json = await res.json();
            showToast(json.message || (json.success ? 'Tersimpan.' : 'Gagal.'), !!json.success);
        } catch (err) {
            showToast('Gagal terhubung ke server atau terjadi kesalahan internal. Periksa koneksi internet Anda dan coba lagi.', false);
        } finally {
            btn.disabled = false;
            btn.classList.remove('opacity-60');
        }
    });
})();
</script>
<?php require __DIR__ . '/../partials/footer.php'; ?>
