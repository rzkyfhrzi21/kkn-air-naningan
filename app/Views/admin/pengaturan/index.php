<?php
$pageTitle = 'Pengaturan Sistem';
$activeNav = 'pengaturan';
require __DIR__ . '/../partials/header.php';
?>
<div class="flex flex-col w-full max-w-container-max mx-auto px-container-pad-mobile md:px-container-pad-desktop py-12 md:py-16 gap-12">

    <div class="flex flex-col gap-2">
        <h1 class="font-h1-mobile md:font-h1 text-h1-mobile md:text-h1 text-ink">Pengaturan Sistem</h1>
        <p class="font-body-lg text-body-lg text-ink-dim max-w-2xl">Kelola informasi dasar desa dan pengaturan keamanan akun administrator untuk panel Air Naningan.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 md:gap-12 items-start">

        <!-- Tab Nav -->
        <div class="lg:col-span-3 lg:sticky lg:top-24 flex flex-col gap-2">
            <a href="#info-desa" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-surface-2 text-ink border border-line text-left transition-all">
                <span class="material-symbols-outlined text-[20px] text-primary">storefront</span>
                <span class="font-body-md text-body-md font-medium">Informasi Desa</span>
            </a>
            <a href="#akun-admin" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-surface-container-high text-ink-dim hover:text-ink text-left transition-all">
                <span class="material-symbols-outlined text-[20px]">manage_accounts</span>
                <span class="font-body-md text-body-md">Akun Admin</span>
            </a>
        </div>

        <!-- Forms -->
        <div class="lg:col-span-9 flex flex-col gap-12">

            <!-- Informasi Desa -->
            <section class="flex flex-col gap-6 scroll-mt-24" id="info-desa">
                <div class="flex flex-col gap-1 border-b border-line pb-4">
                    <h2 class="font-h3 text-h3 text-ink">Informasi Dasar Desa</h2>
                    <p class="font-body-md text-body-md text-ink-dim">Detail ini akan ditampilkan secara publik di beranda dan halaman kontak.</p>
                </div>
                <div class="bg-surface-container rounded-2xl p-6 md:p-8 flex flex-col gap-8 shadow-sm">
                    <div class="flex flex-col md:flex-row gap-6 items-start md:items-center">
                        <div class="w-24 h-24 rounded-2xl bg-surface border border-line flex items-center justify-center relative overflow-hidden group cursor-pointer">
                            <span class="material-symbols-outlined text-ink-dim/30 text-[40px]">image</span>
                            <div class="absolute inset-0 bg-background/60 backdrop-blur-sm flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <span class="material-symbols-outlined text-ink">upload</span>
                                <span class="font-label-mono text-label-mono text-ink mt-1">UBAH</span>
                            </div>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="font-body-md text-body-md font-medium text-ink">Logo Resmi Desa</span>
                            <span class="font-body-md text-body-md text-ink-dim text-sm">Rekomendasi: 512x512px. Format: PNG dengan latar transparan.</span>
                            <button class="mt-2 self-start font-label-mono text-label-mono text-primary uppercase hover:text-primary-fixed transition-colors text-[11px]">Hapus Logo</button>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex flex-col gap-2">
                            <label class="font-body-md text-[13px] text-ink-dim ml-1">Nama Desa / Pekon</label>
                            <input class="w-full bg-surface border border-line rounded-lg px-4 py-3 font-body-md text-ink focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all" type="text" value="Pekon Air Naningan"/>
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="font-body-md text-[13px] text-ink-dim ml-1">Nomor WhatsApp Resmi</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-ink-dim">+62</span>
                                <input class="w-full bg-surface border border-line rounded-lg pl-12 pr-4 py-3 font-body-md text-ink focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all" type="text" value="812-3456-7890"/>
                            </div>
                        </div>
                        <div class="flex flex-col gap-2 md:col-span-2">
                            <label class="font-body-md text-[13px] text-ink-dim ml-1">Alamat Lengkap</label>
                            <textarea class="w-full bg-surface border border-line rounded-lg px-4 py-3 font-body-md text-ink focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all resize-none" rows="3">Jl. Raya Air Naningan No. 1, Kec. Air Naningan, Kab. Tanggamus, Lampung 35379</textarea>
                        </div>
                        <div class="flex flex-col gap-2 md:col-span-2">
                            <label class="font-body-md text-[13px] text-ink-dim ml-1">Jam Operasional Pelayanan</label>
                            <input class="w-full bg-surface border border-line rounded-lg px-4 py-3 font-body-md text-ink focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all" type="text" value="Senin - Jumat, 08:00 - 15:00 WIB"/>
                        </div>
                    </div>
                    <div class="flex justify-end pt-4 border-t border-line">
                        <button class="bg-primary hover:bg-primary-fixed text-on-primary font-body-md font-medium px-6 py-2.5 rounded-full transition-colors flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">save</span>
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </section>

            <!-- Akun Admin -->
            <section class="flex flex-col gap-6 scroll-mt-24" id="akun-admin">
                <div class="flex flex-col gap-1 border-b border-line pb-4">
                    <h2 class="font-h3 text-h3 text-ink">Keamanan Akun</h2>
                    <p class="font-body-md text-body-md text-ink-dim">Perbarui kredensial login untuk akses panel admin.</p>
                </div>
                <div class="bg-surface-container rounded-2xl p-6 md:p-8 flex flex-col gap-6 shadow-sm">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex flex-col gap-2">
                            <label class="font-body-md text-[13px] text-ink-dim ml-1">Username Saat Ini</label>
                            <input class="w-full bg-surface-container-lowest border border-line/50 rounded-lg px-4 py-3 font-body-md text-ink-dim cursor-not-allowed" disabled type="text" value="admin_naningan"/>
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="font-body-md text-[13px] text-ink-dim ml-1">Username Baru</label>
                            <input class="w-full bg-surface border border-line rounded-lg px-4 py-3 font-body-md text-ink focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all" placeholder="Masukkan username baru" type="text"/>
                        </div>
                        <div class="col-span-1 md:col-span-2 h-px bg-line my-2"></div>
                        <div class="flex flex-col gap-2">
                            <label class="font-body-md text-[13px] text-ink-dim ml-1">Password Saat Ini</label>
                            <input class="w-full bg-surface border border-line rounded-lg px-4 py-3 font-body-md text-ink focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all" placeholder="••••••••" type="password"/>
                        </div>
                        <div class="hidden md:block"></div>
                        <div class="flex flex-col gap-2">
                            <label class="font-body-md text-[13px] text-ink-dim ml-1">Password Baru</label>
                            <input class="w-full bg-surface border border-line rounded-lg px-4 py-3 font-body-md text-ink focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all" placeholder="Minimal 8 karakter" type="password"/>
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="font-body-md text-[13px] text-ink-dim ml-1">Konfirmasi Password Baru</label>
                            <input class="w-full bg-surface border border-line rounded-lg px-4 py-3 font-body-md text-ink focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all" placeholder="Ulangi password baru" type="password"/>
                        </div>
                    </div>
                    <div class="flex justify-end pt-4 border-t border-line mt-2">
                        <button class="bg-surface-2 hover:bg-surface-container-highest text-ink font-body-md border border-line font-medium px-6 py-2.5 rounded-full transition-colors flex items-center gap-2">
                            Perbarui Kredensial
                        </button>
                    </div>
                </div>
            </section>

            <!-- SEO section dihapus sesuai permintaan -->

        </div>
    </div>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
