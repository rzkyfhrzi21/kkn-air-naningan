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
$apbdes   = is_array($profil['apbdes'] ?? null) ? $profil['apbdes'] : [];
$apbItems = is_array($apbdes['items'] ?? null) ? $apbdes['items'] : [];
$sejarah  = is_array($profil['sejarah'] ?? null) ? $profil['sejarah'] : [];
$paras    = is_array($sejarah['paragraf'] ?? null) ? $sejarah['paragraf'] : [];
$quote    = (string) ($sejarah['quote'] ?? '');
$sejarahTeks = implode("\n\n", $paras);
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
if ($apbItems === []) {
    $apbItems = [
        ['nama' => 'Penyelenggaraan Pemerintahan', 'jumlah' => '', 'persen' => 0, 'icon' => 'account_balance'],
        ['nama' => 'Pelaksanaan Pembangunan', 'jumlah' => '', 'persen' => 0, 'icon' => 'construction'],
        ['nama' => 'Pembinaan Kemasyarakatan', 'jumlah' => '', 'persen' => 0, 'icon' => 'group'],
        ['nama' => 'Pemberdayaan Masyarakat', 'jumlah' => '', 'persen' => 0, 'icon' => 'trending_up'],
    ];
}
?>
<div class="flex flex-col w-full px-container-pad-mobile md:px-container-pad-desktop py-section-v-mobile md:py-section-v-desktop gap-gutter max-w-container-max mx-auto">

    <div class="flex flex-col gap-2 mb-4">
        <h1 class="font-h1 text-h1-mobile md:text-h1 text-ink">Profil Desa</h1>
        <p class="font-body-lg text-body-lg text-ink-dim max-w-2xl">Kelola data yang tampil di halaman publik <code class="text-gold-soft">/profil</code> — visi, misi, struktur, demografi, APBDes, dan sejarah.</p>
    </div>

    <div id="profil-toast" class="hidden fixed top-6 right-6 z-[100] max-w-sm px-5 py-4 rounded-xl border shadow-lg font-body-md text-sm" role="status" aria-live="polite"></div>

    <form id="form-profil" class="flex flex-col gap-gutter">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>">

        <div class="flex flex-col lg:flex-row gap-gutter">
            <div class="flex flex-col flex-1 gap-gutter">

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

                <!-- Sejarah -->
                <section class="bg-surface-container rounded-2xl p-6 md:p-8 flex flex-col gap-6 shadow-sm">
                    <div class="flex items-center gap-3 border-b border-line pb-4">
                        <div class="w-10 h-10 rounded-xl bg-surface-2 flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary">history_edu</span>
                        </div>
                        <h2 class="font-h3 text-h3 text-ink">Sejarah Desa</h2>
                    </div>
                    <label class="flex flex-col gap-2">
                        <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest">Narasi (pisah paragraf dengan baris kosong)</span>
                        <textarea name="sejarah_teks" rows="10"
                                  class="bg-surface border border-line-strong rounded-xl p-4 min-h-[200px] text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary resize-y"
                                  placeholder="Tuliskan sejarah pekon..."><?= htmlspecialchars($sejarahTeks, ENT_QUOTES, 'UTF-8') ?></textarea>
                    </label>
                    <label class="flex flex-col gap-2">
                        <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest">Kutipan / Quote</span>
                        <input name="sejarah_quote" type="text"
                               value="<?= htmlspecialchars($quote, ENT_QUOTES, 'UTF-8') ?>"
                               class="bg-surface border border-line-strong rounded-xl p-3 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary"
                               placeholder="Kutipan di tengah narasi sejarah...">
                    </label>
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
                    <p class="font-body-md text-body-md text-ink-dim text-sm">Level: 0 = Kepala, 1 = Sekretaris, 2 = staf (indentasi di publik).</p>
                    <div class="flex flex-col gap-3" id="struktur-list">
                        <?php foreach ($struktur as $s): ?>
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-2 items-start struktur-row bg-surface p-3 rounded-xl border border-line">
                            <input name="struktur_nama[]" type="text" placeholder="Nama"
                                   value="<?= htmlspecialchars((string) ($s['nama'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                                   class="md:col-span-3 bg-surface-2 border border-line-strong rounded-lg p-2.5 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary">
                            <input name="struktur_jabatan[]" type="text" placeholder="Jabatan"
                                   value="<?= htmlspecialchars((string) ($s['jabatan'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                                   class="md:col-span-3 bg-surface-2 border border-line-strong rounded-lg p-2.5 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary">
                            <input name="struktur_foto[]" type="url" placeholder="URL foto (opsional)"
                                   value="<?= htmlspecialchars((string) ($s['foto'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                                   class="md:col-span-4 bg-surface-2 border border-line-strong rounded-lg p-2.5 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary">
                            <input name="struktur_level[]" type="number" min="0" max="5" placeholder="Lv"
                                   value="<?= (int) ($s['level'] ?? 0) ?>"
                                   class="md:col-span-1 bg-surface-2 border border-line-strong rounded-lg p-2.5 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary">
                            <button type="button" class="md:col-span-1 p-2 text-danger hover:bg-surface-2 rounded-lg btn-remove-struktur" aria-label="Hapus">
                                <span class="material-symbols-outlined text-[18px]">delete</span>
                            </button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </section>

                <!-- APBDes -->
                <section class="bg-surface-container rounded-2xl p-6 md:p-8 flex flex-col gap-6 shadow-sm">
                    <div class="flex items-center justify-between border-b border-line pb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-surface-2 flex items-center justify-center">
                                <span class="material-symbols-outlined text-primary">account_balance</span>
                            </div>
                            <h2 class="font-h3 text-h3 text-ink">Transparansi APBDes</h2>
                        </div>
                        <button type="button" id="btn-add-apb" class="text-primary hover:text-primary-fixed flex items-center gap-1 font-label-mono text-[10px] uppercase transition-colors">
                            <span class="material-symbols-outlined text-[16px]">add</span> Item
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label class="flex flex-col gap-2">
                            <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest">Tahun Anggaran</span>
                            <input name="apbdes_tahun" type="number" min="2000" max="2100"
                                   value="<?= (int) ($apbdes['tahun'] ?? date('Y')) ?>"
                                   class="bg-surface border border-line-strong rounded-xl p-3 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary">
                        </label>
                        <label class="flex flex-col gap-2">
                            <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest">URL Laporan Lengkap</span>
                            <input name="apbdes_laporan_url" type="url" placeholder="https://..."
                                   value="<?= htmlspecialchars((string) ($apbdes['laporan_url'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                                   class="bg-surface border border-line-strong rounded-xl p-3 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary">
                        </label>
                    </div>
                    <div class="flex flex-col gap-3" id="apb-list">
                        <?php foreach ($apbItems as $a): ?>
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-2 items-start apb-row bg-surface p-3 rounded-xl border border-line">
                            <input name="apbdes_nama[]" type="text" placeholder="Nama pos"
                                   value="<?= htmlspecialchars((string) ($a['nama'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                                   class="md:col-span-4 bg-surface-2 border border-line-strong rounded-lg p-2.5 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary">
                            <input name="apbdes_jumlah[]" type="text" placeholder="Rp 420.5M"
                                   value="<?= htmlspecialchars((string) ($a['jumlah'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                                   class="md:col-span-3 bg-surface-2 border border-line-strong rounded-lg p-2.5 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary">
                            <input name="apbdes_persen[]" type="number" min="0" max="100" placeholder="%"
                                   value="<?= (int) ($a['persen'] ?? 0) ?>"
                                   class="md:col-span-2 bg-surface-2 border border-line-strong rounded-lg p-2.5 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary">
                            <input name="apbdes_icon[]" type="text" placeholder="icon"
                                   value="<?= htmlspecialchars((string) ($a['icon'] ?? 'account_balance'), ENT_QUOTES, 'UTF-8') ?>"
                                   class="md:col-span-2 bg-surface-2 border border-line-strong rounded-lg p-2.5 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary">
                            <button type="button" class="md:col-span-1 p-2 text-danger hover:bg-surface-2 rounded-lg btn-remove-apb" aria-label="Hapus">
                                <span class="material-symbols-outlined text-[18px]">delete</span>
                            </button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            </div>

            <!-- Sidebar demografi -->
            <div class="flex flex-col w-full lg:w-[400px] gap-gutter">
                <section class="bg-surface-container rounded-2xl p-6 flex flex-col gap-6 shadow-sm sticky top-24">
                    <div class="flex items-center gap-3 border-b border-line pb-4">
                        <div class="w-10 h-10 rounded-xl bg-surface-2 flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary">groups</span>
                        </div>
                        <h2 class="font-h3 text-[20px] text-ink">Demografi</h2>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
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
                            <input name="luas_wilayah" type="number" min="0" step="0.1"
                                   value="<?= htmlspecialchars((string) ($demo['luas_wilayah'] ?? 0), ENT_QUOTES, 'UTF-8') ?>"
                                   class="w-full bg-surface border border-line-strong rounded-lg p-2.5 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary">
                        </label>
                        <label class="flex flex-col gap-1.5">
                            <span class="text-xs text-ink-dim font-body-md">Satuan Luas</span>
                            <input name="luas_satuan" type="text"
                                   value="<?= htmlspecialchars((string) ($demo['luas_satuan'] ?? 'km²'), ENT_QUOTES, 'UTF-8') ?>"
                                   class="w-full bg-surface border border-line-strong rounded-lg p-2.5 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary">
                        </label>
                        <label class="flex flex-col gap-1.5">
                            <span class="text-xs text-ink-dim font-body-md">Ketinggian</span>
                            <input name="ketinggian" type="number" min="0" step="1"
                                   value="<?= htmlspecialchars((string) ($demo['ketinggian'] ?? 0), ENT_QUOTES, 'UTF-8') ?>"
                                   class="w-full bg-surface border border-line-strong rounded-lg p-2.5 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary">
                        </label>
                        <label class="flex flex-col gap-1.5">
                            <span class="text-xs text-ink-dim font-body-md">Satuan Tinggi</span>
                            <input name="ketinggian_satuan" type="text"
                                   value="<?= htmlspecialchars((string) ($demo['ketinggian_satuan'] ?? 'mdpl'), ENT_QUOTES, 'UTF-8') ?>"
                                   class="w-full bg-surface border border-line-strong rounded-lg p-2.5 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary">
                        </label>
                    </div>

                    <div class="w-full h-px bg-line"></div>

                    <div class="flex flex-col gap-3">
                        <h3 class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest">Populasi per Dusun</h3>
                        <div class="grid grid-cols-2 gap-3">
                            <?php foreach ($perDusun as $di => $d): ?>
                            <div class="flex flex-col gap-1.5">
                                <input name="dusun_nama[]" type="text"
                                       value="<?= htmlspecialchars((string) ($d['nama'] ?? ('Dusun ' . ($di + 1))), ENT_QUOTES, 'UTF-8') ?>"
                                       class="w-full bg-surface border border-line-strong rounded-lg p-2 text-xs text-on-surface focus:outline-none focus:ring-1 focus:ring-primary"
                                       placeholder="Nama dusun">
                                <input name="dusun_jumlah[]" type="number" min="0"
                                       value="<?= (int) ($d['jumlah'] ?? 0) ?>"
                                       class="w-full bg-surface border border-line-strong rounded-lg p-2.5 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary"
                                       placeholder="0">
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="w-full h-px bg-line"></div>

                    <div class="flex flex-col gap-3">
                        <div class="flex justify-between items-center">
                            <h3 class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest">Mata Pencaharian (%)</h3>
                            <button type="button" id="btn-add-job" class="text-primary text-[10px] font-label-mono uppercase flex items-center gap-0.5">
                                <span class="material-symbols-outlined text-[14px]">add</span>
                            </button>
                        </div>
                        <div class="flex flex-col gap-3" id="job-list">
                            <?php foreach ($jobs as $j): ?>
                            <div class="flex items-center gap-2 job-row bg-surface p-2 rounded-lg border border-line-strong">
                                <input name="pekerjaan_jenis[]" type="text"
                                       value="<?= htmlspecialchars((string) ($j['jenis'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                                       class="flex-1 bg-transparent text-sm text-ink font-body-md focus:outline-none"
                                       placeholder="Jenis pekerjaan">
                                <input name="pekerjaan_persen[]" type="number" min="0" max="100"
                                       value="<?= (int) ($j['persen'] ?? 0) ?>"
                                       class="w-14 bg-transparent text-right text-on-surface font-body-md focus:outline-none"
                                       placeholder="0">
                                <span class="text-ink-dim text-sm">%</span>
                                <button type="button" class="p-1 text-danger btn-remove-job" aria-label="Hapus">
                                    <span class="material-symbols-outlined text-[16px]">close</span>
                                </button>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-line flex items-center justify-end gap-3">
                        <a href="<?= htmlspecialchars($base . '/profil', ENT_QUOTES, 'UTF-8') ?>" target="_blank"
                           class="px-5 py-2.5 rounded-full font-label-mono text-[11px] uppercase tracking-wider text-ink bg-surface-2 hover:bg-surface-container-highest transition-colors">
                            Lihat Publik
                        </a>
                        <button id="btn-simpan-profil" class="px-6 py-2.5 rounded-full font-label-mono text-[11px] uppercase tracking-wider bg-primary text-on-primary hover:bg-primary-fixed shadow-md shadow-primary/20 transition-all flex items-center gap-2" type="submit">
                            <span class="material-symbols-outlined text-[16px]">save</span> Simpan Perubahan
                        </button>
                    </div>
                </section>
            </div>
        </div>
    </form>
</div>

<script>
(function () {
    const base = <?= json_encode($base, JSON_UNESCAPED_SLASHES) ?>;
    const form = document.getElementById('form-profil');
    const toastEl = document.getElementById('profil-toast');

    function showToast(msg, ok) {
        toastEl.textContent = msg;
        toastEl.className = 'fixed top-6 right-6 z-[100] max-w-sm px-5 py-4 rounded-xl border shadow-lg font-body-md text-sm '
            + (ok
                ? 'bg-surface-2 border-primary text-ink'
                : 'bg-surface-2 border-danger text-ink');
        toastEl.classList.remove('hidden');
        clearTimeout(toastEl._t);
        toastEl._t = setTimeout(() => toastEl.classList.add('hidden'), 4000);
    }

    function renumberMisi() {
        document.querySelectorAll('#misi-list .misi-row').forEach((row, i) => {
            const n = row.querySelector('.misi-num');
            if (n) n.textContent = String(i + 1).padStart(2, '0');
        });
    }

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

    document.getElementById('btn-add-struktur')?.addEventListener('click', () => {
        const list = document.getElementById('struktur-list');
        const row = document.createElement('div');
        row.className = 'grid grid-cols-1 md:grid-cols-12 gap-2 items-start struktur-row bg-surface p-3 rounded-xl border border-line';
        row.innerHTML = `
            <input name="struktur_nama[]" type="text" placeholder="Nama" class="md:col-span-3 bg-surface-2 border border-line-strong rounded-lg p-2.5 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary">
            <input name="struktur_jabatan[]" type="text" placeholder="Jabatan" class="md:col-span-3 bg-surface-2 border border-line-strong rounded-lg p-2.5 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary">
            <input name="struktur_foto[]" type="url" placeholder="URL foto (opsional)" class="md:col-span-4 bg-surface-2 border border-line-strong rounded-lg p-2.5 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary">
            <input name="struktur_level[]" type="number" min="0" max="5" value="2" class="md:col-span-1 bg-surface-2 border border-line-strong rounded-lg p-2.5 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary">
            <button type="button" class="md:col-span-1 p-2 text-danger hover:bg-surface-2 rounded-lg btn-remove-struktur" aria-label="Hapus">
                <span class="material-symbols-outlined text-[18px]">delete</span>
            </button>`;
        list.appendChild(row);
    });

    document.getElementById('struktur-list')?.addEventListener('click', (e) => {
        const btn = e.target.closest('.btn-remove-struktur');
        if (btn) btn.closest('.struktur-row')?.remove();
    });

    document.getElementById('btn-add-apb')?.addEventListener('click', () => {
        const list = document.getElementById('apb-list');
        const row = document.createElement('div');
        row.className = 'grid grid-cols-1 md:grid-cols-12 gap-2 items-start apb-row bg-surface p-3 rounded-xl border border-line';
        row.innerHTML = `
            <input name="apbdes_nama[]" type="text" placeholder="Nama pos" class="md:col-span-4 bg-surface-2 border border-line-strong rounded-lg p-2.5 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary">
            <input name="apbdes_jumlah[]" type="text" placeholder="Rp 0" class="md:col-span-3 bg-surface-2 border border-line-strong rounded-lg p-2.5 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary">
            <input name="apbdes_persen[]" type="number" min="0" max="100" value="0" class="md:col-span-2 bg-surface-2 border border-line-strong rounded-lg p-2.5 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary">
            <input name="apbdes_icon[]" type="text" value="account_balance" class="md:col-span-2 bg-surface-2 border border-line-strong rounded-lg p-2.5 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary">
            <button type="button" class="md:col-span-1 p-2 text-danger hover:bg-surface-2 rounded-lg btn-remove-apb" aria-label="Hapus">
                <span class="material-symbols-outlined text-[18px]">delete</span>
            </button>`;
        list.appendChild(row);
    });

    document.getElementById('apb-list')?.addEventListener('click', (e) => {
        const btn = e.target.closest('.btn-remove-apb');
        if (btn) btn.closest('.apb-row')?.remove();
    });

    document.getElementById('btn-add-job')?.addEventListener('click', () => {
        const list = document.getElementById('job-list');
        const row = document.createElement('div');
        row.className = 'flex items-center gap-2 job-row bg-surface p-2 rounded-lg border border-line-strong';
        row.innerHTML = `
            <input name="pekerjaan_jenis[]" type="text" class="flex-1 bg-transparent text-sm text-ink font-body-md focus:outline-none" placeholder="Jenis pekerjaan">
            <input name="pekerjaan_persen[]" type="number" min="0" max="100" value="0" class="w-14 bg-transparent text-right text-on-surface font-body-md focus:outline-none">
            <span class="text-ink-dim text-sm">%</span>
            <button type="button" class="p-1 text-danger btn-remove-job" aria-label="Hapus">
                <span class="material-symbols-outlined text-[16px]">close</span>
            </button>`;
        list.appendChild(row);
    });

    document.getElementById('job-list')?.addEventListener('click', (e) => {
        const btn = e.target.closest('.btn-remove-job');
        if (btn) btn.closest('.job-row')?.remove();
    });

    form?.addEventListener('submit', async (e) => {
        e.preventDefault();
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
            showToast('Gagal menghubungi server.', false);
        } finally {
            btn.disabled = false;
            btn.classList.remove('opacity-60');
        }
    });
})();
</script>
<?php require __DIR__ . '/../partials/footer.php'; ?>
