<?php
$pageTitle = 'Kelola Profil Desa';
$activeNav = 'kelola-profil';
require __DIR__ . '/../partials/header.php';
?>
<div class="flex flex-col w-full px-container-pad-mobile md:px-container-pad-desktop py-section-v-mobile md:py-section-v-desktop gap-gutter max-w-container-max mx-auto">

    <div class="flex flex-col gap-2 mb-8">
        <h1 class="font-h1 text-h1-mobile md:text-h1 text-ink">Profil Desa</h1>
        <p class="font-body-lg text-body-lg text-ink-dim max-w-2xl">Kelola informasi fundamental, visi strategis, dan data demografis untuk Pekon Air Naningan.</p>
    </div>

    <form class="flex flex-col lg:flex-row gap-gutter">
        <div class="flex flex-col flex-1 gap-gutter">
            <!-- Visi & Misi -->
            <section class="bg-surface-container rounded-2xl p-6 md:p-8 flex flex-col gap-6 shadow-sm relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none opacity-50 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="flex items-center gap-3 border-b border-line pb-4">
                    <div class="w-10 h-10 rounded-xl bg-surface-2 flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary">visibility</span>
                    </div>
                    <h2 class="font-h3 text-h3 text-ink">Visi &amp; Misi</h2>
                </div>
                <div class="flex flex-col gap-4">
                    <label class="flex flex-col gap-2">
                        <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest">Visi Desa</span>
                        <textarea class="bg-surface border-line-strong rounded-xl p-4 min-h-[120px] text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary transition-shadow resize-y" placeholder="Masukkan visi strategis desa..."></textarea>
                    </label>
                </div>
                <div class="flex flex-col gap-4" id="misi-container">
                    <div class="flex justify-between items-end">
                        <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest">Misi Desa</span>
                        <button class="text-primary hover:text-primary-fixed flex items-center gap-1 font-label-mono text-[10px] uppercase transition-colors" onclick="addMisiItem()" type="button">
                            <span class="material-symbols-outlined text-[16px]">add</span> Tambah Misi
                        </button>
                    </div>
                    <div class="flex flex-col gap-3" id="misi-list">
                        <div class="flex items-start gap-3 group/item">
                            <span class="mt-3 text-ink-dim font-label-mono text-[10px] w-4 text-right">01</span>
                            <input class="flex-1 bg-surface border-line-strong rounded-xl p-3 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary transition-shadow" placeholder="Misi pertama..." type="text"/>
                            <button aria-label="Hapus misi" class="mt-2 p-2 text-danger opacity-0 group-hover/item:opacity-100 transition-opacity hover:bg-surface-2 rounded-lg" onclick="this.parentElement.remove()" type="button">
                                <span class="material-symbols-outlined text-[18px]">delete</span>
                            </button>
                        </div>
                        <div class="flex items-start gap-3 group/item">
                            <span class="mt-3 text-ink-dim font-label-mono text-[10px] w-4 text-right">02</span>
                            <input class="flex-1 bg-surface border-line-strong rounded-xl p-3 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary transition-shadow" placeholder="Misi kedua..." type="text"/>
                            <button aria-label="Hapus misi" class="mt-2 p-2 text-danger opacity-0 group-hover/item:opacity-100 transition-opacity hover:bg-surface-2 rounded-lg" onclick="this.parentElement.remove()" type="button">
                                <span class="material-symbols-outlined text-[18px]">delete</span>
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Sejarah Desa -->
            <section class="bg-surface-container rounded-2xl p-6 md:p-8 flex flex-col gap-6 shadow-sm relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none opacity-50 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="flex items-center gap-3 border-b border-line pb-4">
                    <div class="w-10 h-10 rounded-xl bg-surface-2 flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary">history_edu</span>
                    </div>
                    <h2 class="font-h3 text-h3 text-ink">Sejarah Desa</h2>
                </div>
                <div class="flex flex-col gap-2 rounded-xl bg-surface overflow-hidden border border-line-strong focus-within:ring-1 focus-within:ring-primary transition-shadow">
                    <div class="flex items-center gap-1 p-2 bg-surface-2 border-b border-line-strong">
                        <button class="p-1.5 hover:bg-surface-container hover:text-ink rounded text-ink-dim transition-colors" title="Bold" type="button"><span class="material-symbols-outlined text-[18px]">format_bold</span></button>
                        <button class="p-1.5 hover:bg-surface-container hover:text-ink rounded text-ink-dim transition-colors" title="Italic" type="button"><span class="material-symbols-outlined text-[18px]">format_italic</span></button>
                        <div class="w-px h-4 bg-line mx-1"></div>
                        <button class="p-1.5 hover:bg-surface-container hover:text-ink rounded text-ink-dim transition-colors" title="List" type="button"><span class="material-symbols-outlined text-[18px]">format_list_bulleted</span></button>
                        <button class="p-1.5 hover:bg-surface-container hover:text-ink rounded text-ink-dim transition-colors" title="Link" type="button"><span class="material-symbols-outlined text-[18px]">link</span></button>
                    </div>
                    <textarea class="w-full bg-transparent p-4 min-h-[240px] text-on-surface font-body-md focus:outline-none resize-y" placeholder="Tuliskan narasi sejarah terbentuknya Pekon Air Naningan..."></textarea>
                </div>
            </section>
        </div>

        <!-- Sidebar Kependudukan -->
        <div class="flex flex-col w-full lg:w-[400px] gap-gutter">
            <section class="bg-surface-container rounded-2xl p-6 flex flex-col gap-6 shadow-sm sticky top-24">
                <div class="flex items-center gap-3 border-b border-line pb-4">
                    <div class="w-10 h-10 rounded-xl bg-surface-2 flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary">groups</span>
                    </div>
                    <h2 class="font-h3 text-[20px] text-ink">Kependudukan</h2>
                </div>
                <div class="flex flex-col gap-4">
                    <h3 class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest">Populasi per Dusun</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <?php for ($d = 1; $d <= 4; $d++): ?>
                        <label class="flex flex-col gap-1.5">
                            <span class="text-xs text-ink-dim font-body-md">Dusun <?= $d ?></span>
                            <div class="relative">
                                <input class="w-full bg-surface border-line-strong rounded-lg p-2.5 pl-8 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary transition-shadow" placeholder="0" type="number"/>
                                <span class="material-symbols-outlined absolute left-2.5 top-1/2 -translate-y-1/2 text-[16px] text-ink-dim">person</span>
                            </div>
                        </label>
                        <?php endfor; ?>
                    </div>
                </div>
                <div class="w-full h-px bg-line my-1"></div>
                <div class="flex flex-col gap-4">
                    <h3 class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest">Mata Pencaharian (%)</h3>
                    <div class="flex flex-col gap-3">
                        <?php
                        $pekerjaan = [
                            ['agriculture', 'text-primary', 'Petani Kopi'],
                            ['storefront',  'text-secondary', 'Pedagang'],
                            ['engineering', 'text-tertiary-container', 'Pekerja Lepas'],
                        ];
                        foreach ($pekerjaan as [$icon, $color, $label]):
                        ?>
                        <label class="flex items-center gap-3 bg-surface p-2 rounded-lg border border-line-strong focus-within:ring-1 focus-within:ring-primary transition-shadow">
                            <span class="material-symbols-outlined text-[18px] <?= $color ?> p-1 bg-surface-2 rounded"><?= $icon ?></span>
                            <span class="flex-1 text-sm text-ink font-body-md"><?= $label ?></span>
                            <input class="w-16 bg-transparent text-right text-on-surface font-body-md focus:outline-none" max="100" placeholder="0" type="number"/>
                            <span class="text-ink-dim text-sm pr-2">%</span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-line flex items-center justify-end gap-3">
                    <button class="px-5 py-2.5 rounded-full font-label-mono text-[11px] uppercase tracking-wider text-ink bg-surface-2 hover:bg-surface-container-highest transition-colors" type="button">Batal</button>
                    <button class="px-6 py-2.5 rounded-full font-label-mono text-[11px] uppercase tracking-wider bg-primary text-on-primary hover:bg-primary-fixed shadow-md shadow-primary/20 transition-all flex items-center gap-2" type="submit">
                        <span class="material-symbols-outlined text-[16px]">save</span> Simpan Perubahan
                    </button>
                </div>
            </section>
        </div>
    </form>
</div>

<script>
function addMisiItem() {
    const list = document.getElementById('misi-list');
    const count = list.children.length + 1;
    const num = count < 10 ? `0${count}` : count;
    const item = document.createElement('div');
    item.className = 'flex items-start gap-3 group/item opacity-0 -translate-y-2 transition-all duration-300';
    item.innerHTML = `
        <span class="mt-3 text-ink-dim font-label-mono text-[10px] w-4 text-right">${num}</span>
        <input type="text" class="flex-1 bg-surface border-line-strong rounded-xl p-3 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary transition-shadow" placeholder="Misi baru...">
        <button type="button" class="mt-2 p-2 text-danger opacity-0 group-hover/item:opacity-100 transition-opacity hover:bg-surface-2 rounded-lg" aria-label="Hapus misi" onclick="this.parentElement.remove()">
            <span class="material-symbols-outlined text-[18px]">delete</span>
        </button>`;
    list.appendChild(item);
    void item.offsetWidth;
    item.classList.remove('opacity-0', '-translate-y-2');
    item.querySelector('input').focus();
}
</script>
<?php require __DIR__ . '/../partials/footer.php'; ?>
