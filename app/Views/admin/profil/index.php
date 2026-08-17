<?php
/* ======================================================
   HALAMAN PENGATURAN AKUN ADMIN (ADMIN PROFILE SETTINGS)

   Halaman ini adalah "buku pengaturan akun pengelola":
   dari sini admin pekon bisa mengganti Username, nama admin, nomor WhatsApp,
   foto profil avatar admin (dikompres & disimpan ke /uploads/profil/),
   serta mengganti kata sandi (password) admin yang dienkripsi menggunakan `password_hash()`.

   Kredensial tersimpan di file rahasia `/secure/admin_credentials.json`.
====================================================== */

$pageTitle = 'Profil';
$activeNav = 'profil';
require __DIR__ . '/../partials/header.php';

$akun  = $akun ?? [];
$csrf  = (string) ($_SESSION['csrf_token'] ?? '');
$base  = defined('APP_BASE') ? APP_BASE : '';
$fotoUrl = ($akun['foto'] ?? '') !== '' ? $base . '/' . $akun['foto'] : '';
$waVal = preg_replace('/^(62|0)/', '', (string) ($akun['whatsapp'] ?? ''));
?>

<div class="flex flex-col w-full px-container-pad-mobile lg:px-container-pad-desktop pb-section-v-desktop gap-10">

    <div class="flex flex-col gap-2 pt-10 max-w-2xl">
        <h1 class="font-h1-mobile lg:font-h1 text-h1-mobile lg:text-h1 text-ink">Profil</h1>
        <p class="font-body-lg text-body-lg text-ink-dim">Kelola akun administrator panel Air Naningan — identitas, kontak, foto profil, dan keamanan password.</p>
    </div>


    <form id="form-akun" class="flex flex-col gap-8" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" name="hapus_foto" id="hapus-foto" value="0">

        <!-- Identitas & Kontak -->
        <section class="bg-surface-container rounded-2xl p-6 md:p-8 flex flex-col gap-6 shadow-sm">
            <div class="flex items-center gap-3 border-b border-line pb-4">
                <div class="w-10 h-10 rounded-xl bg-surface-2 flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary">manage_accounts</span>
                </div>
                <h2 class="font-h3 text-h3 text-ink">Identitas &amp; Kontak</h2>
            </div>

            <div class="flex flex-col sm:flex-row gap-6 items-center sm:items-start">
                <!-- Foto profil -->
                <div class="flex flex-col items-center gap-3 shrink-0">
                    <div class="relative">
                        <div id="foto-profil-preview"
                             class="w-24 h-24 rounded-full bg-surface-2 border border-line-strong flex items-center justify-center overflow-hidden">
                            <?php if ($fotoUrl !== ''): ?>
                                <img src="<?= htmlspecialchars($fotoUrl, ENT_QUOTES, 'UTF-8') ?>" alt="Foto profil admin"
                                     class="w-full h-full object-cover" id="foto-profil-img">
                            <?php else: ?>
                                <span class="material-symbols-outlined text-ink-dim/40 text-[40px]">person</span>
                            <?php endif; ?>
                        </div>
                        <label for="foto-profil-input"
                               class="absolute -bottom-1 -right-1 w-9 h-9 rounded-full bg-primary text-on-primary flex items-center justify-center cursor-pointer hover:bg-primary-fixed transition-colors shadow-md" title="Ganti foto">
                            <span class="material-symbols-outlined text-[18px]">photo_camera</span>
                        </label>
                        <input id="foto-profil-input" name="foto_profil" type="file" accept=".jpg,.jpeg,.png,.gif,.webp,image/jpeg,image/png,image/gif,image/webp" class="hidden">
                    </div>
                    <button type="button" id="btn-hapus-foto"
                            class="text-[11px] font-label-mono uppercase tracking-widest text-danger hover:opacity-80 transition-opacity <?= $fotoUrl === '' ? 'hidden' : '' ?>">
                        Hapus Foto
                    </button>
                    <p class="text-[11px] text-ink-dim text-center">Maks 2MB · JPG, PNG, GIF, WebP</p>
                </div>

                <!-- Fields -->
                <div class="flex-1 w-full flex flex-col gap-4">
                    <label class="flex flex-col gap-2">
                        <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest">Nama Lengkap</span>
                        <input name="nama_lengkap" type="text" required maxlength="80" autocomplete="off"
                               placeholder="Nama lengkap administrator"
                               value="<?= htmlspecialchars($akun['nama_lengkap'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                               class="bg-surface border border-line-strong rounded-xl p-3 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary">
                    </label>
                    <label class="flex flex-col gap-2">
                        <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest">Username</span>
                        <input name="username" type="text" required autocomplete="off"
                               value="<?= htmlspecialchars($akun['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                               class="bg-surface border border-line-strong rounded-xl p-3 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary">
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label class="flex flex-col gap-2">
                            <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest">No. WhatsApp</span>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-ink-dim text-sm">+62</span>
                                <input name="whatsapp" type="tel" inputmode="numeric" placeholder="823 8974 4302"
                                       value="<?= htmlspecialchars($waVal, ENT_QUOTES, 'UTF-8') ?>"
                                       class="w-full bg-surface border border-line-strong rounded-xl pl-12 pr-4 py-3 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary">
                            </div>
                            <span class="text-xs text-ink-dim">Ketik tanpa awalan 0 atau +62 — otomatis ditambahkan saat disimpan.</span>
                        </label>
                        <label class="flex flex-col gap-2">
                            <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest">Email</span>
                            <input name="email" type="email" placeholder="admin@airnaningan.desa.id"
                                   value="<?= htmlspecialchars($akun['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                   class="bg-surface border border-line-strong rounded-xl p-3 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary">
                        </label>
                    </div>
                </div>
            </div>
        </section>

        <!-- Keamanan -->
        <section class="bg-surface-container rounded-2xl p-6 md:p-8 flex flex-col gap-6 shadow-sm">
            <div class="flex items-center gap-3 border-b border-line pb-4">
                <div class="w-10 h-10 rounded-xl bg-surface-2 flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary">lock</span>
                </div>
                <h2 class="font-h3 text-h3 text-ink">Ubah Password</h2>
            </div>
            <p class="font-body-md text-body-md text-ink-dim text-sm -mt-2">Kosongkan ketiga kolom jika tidak ingin mengganti password.</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <label class="flex flex-col gap-2 md:col-span-2">
                    <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest">Password Saat Ini</span>
                    <input name="password_saat_ini" type="password" autocomplete="current-password"
                           class="bg-surface border border-line-strong rounded-xl p-3 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary" placeholder="••••••••">
                </label>
                <label class="flex flex-col gap-2">
                    <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest">Password Baru</span>
                    <input name="password_baru" type="password" autocomplete="new-password"
                           class="bg-surface border border-line-strong rounded-xl p-3 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary" placeholder="Minimal 8 karakter">
                </label>
                <label class="flex flex-col gap-2">
                    <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest">Konfirmasi Password Baru</span>
                    <input name="konfirmasi_password" type="password" autocomplete="new-password"
                           class="bg-surface border border-line-strong rounded-xl p-3 text-on-surface font-body-md focus:outline-none focus:ring-1 focus:ring-primary" placeholder="Ulangi password baru">
                </label>
            </div>
        </section>

        <!-- Actions -->
        <div class="flex items-center justify-between gap-3 pt-2">
            <p class="font-body-md text-body-md text-ink-dim text-sm hidden sm:block">Perubahan tersimpan langsung ke data admin.</p>
            <button id="btn-simpan-akun" type="submit"
                    class="bg-primary hover:bg-primary-fixed text-on-primary font-body-md font-medium px-6 py-2.5 rounded-full transition-colors flex items-center gap-2 shadow-md shadow-primary/20">
                <span class="material-symbols-outlined text-[18px]">save</span>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
(function () {
    'use strict';
    const base  = <?= json_encode($base, JSON_UNESCAPED_SLASHES) ?>;
    const csrf  = <?= json_encode($csrf) ?>;
    const form  = document.getElementById('form-akun');
    const previewBox = document.getElementById('foto-profil-preview');
    const fileInput = document.getElementById('foto-profil-input');
    const hapusFotoInput = document.getElementById('hapus-foto');
    const btnHapusFoto = document.getElementById('btn-hapus-foto');

    function toast(msg, ok = true) {
        window.showAdminToast(msg, ok);
    }

    function renderFotoPreview(src) {
        previewBox.innerHTML = src
            ? `<img src="${src}" alt="Foto profil admin" class="w-full h-full object-cover">`
            : `<span class="material-symbols-outlined text-ink-dim/40 text-[40px]">person</span>`;
        btnHapusFoto.classList.toggle('hidden', !src);
    }

    // ── Preview foto sebelum upload ───────────────────────────────────────────
    fileInput.addEventListener('change', () => {
        const file = fileInput.files && fileInput.files[0];
        if (!file) return;
        if (file.size > 2 * 1024 * 1024) {
            toast('Ukuran foto profil maksimal 2MB.', false);
            fileInput.value = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = (e) => renderFotoPreview(String(e.target.result));
        reader.readAsDataURL(file);
        hapusFotoInput.value = '0';
    });

    // ── Hapus foto ────────────────────────────────────────────────────────────
    btnHapusFoto.addEventListener('click', () => {
        hapusFotoInput.value = '1';
        fileInput.value = '';
        renderFotoPreview('');
    });

    // ── Submit ────────────────────────────────────────────────────────────────
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const btn = document.getElementById('btn-simpan-akun');
        btn.disabled = true;
        btn.classList.add('opacity-60');

        try {
            const fd = new FormData(form);
            const res = await fetch(base + '/admin/ajax/store-akun', {
                method: 'POST',
                body: fd,
                credentials: 'same-origin',
            });
            const json = await res.json();
            toast(json.message || (json.success ? 'Akun berhasil disimpan.' : 'Gagal menyimpan.'), !!json.success);
            if (json.success && json.data) {
                if (json.data.foto) renderFotoPreview(base + '/' + json.data.foto);
                hapusFotoInput.value = '0';
            }
        } catch (err) {
            toast('Gagal terhubung ke server atau terjadi kesalahan internal. Periksa koneksi internet Anda dan coba lagi.', false);
        } finally {
            btn.disabled = false;
            btn.classList.remove('opacity-60');
        }
    });
})();
</script>
<?php require __DIR__ . '/../partials/footer.php'; ?>
