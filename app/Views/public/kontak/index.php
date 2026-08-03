<?php
$currentPage     = 'kontak';
$pageTitle       = 'Kontak | Pekon Air Naningan';
$metaDescription = 'Hubungi Pekon Air Naningan — layanan informasi, pengaduan, dan saran. Kami siap melayani pada jam kerja operasional balai pekon.';

$alamat      = env('KONTAK_ALAMAT') ?? 'Balai Pekon Air Naningan, Kecamatan Air Naningan, Kabupaten Tanggamus';
$whatsapp    = env('KONTAK_WHATSAPP') ?? '';
$whatsappTel = preg_match('/^0(\d+)$/', $whatsapp, $m) ? '62' . $m[1] : preg_replace('/\D/', '', $whatsapp);
$mapsEmbed   = env('MAPS_EMBED_URL') ?? '';

$jamLayanan = [
    'Senin'   => env('JAM_SENIN')   ?? '08:00 - 15:00',
    'Selasa'  => env('JAM_SELASA')  ?? '08:00 - 15:00',
    'Rabu'    => env('JAM_RABU')    ?? '08:00 - 15:00',
    'Kamis'   => env('JAM_KAMIS')   ?? '08:00 - 15:00',
    'Jumat'   => env('JAM_JUMAT')   ?? '08:00 - 15:00',
    'Sabtu'   => env('JAM_SABTU')   ?? 'Tutup',
    'Minggu'  => env('JAM_MINGGU')  ?? 'Tutup',
];
require __DIR__ . '/../partials/header.php';
?>

<div class="flex flex-col w-full min-h-screen pb-section-v-desktop">

    <!-- Hero Section -->
    <section class="relative pt-32 pb-24 md:pt-48 md:pb-32 px-container-pad-mobile lg:px-container-pad-desktop max-w-container-max mx-auto w-full">
        <div class="flex flex-col gap-6 max-w-2xl relative z-10">
            <span class="font-label-mono text-label-mono text-primary tracking-widest uppercase flex items-center gap-4">
                <span class="w-8 h-[1px] bg-primary block"></span>
                Hubungi Kami
            </span>
            <h1 class="font-h1 text-h1-mobile md:text-h1 text-ink">
                Mari Terhubung dengan<br>
                <span class="text-gold-soft italic font-light">Pekon Air Naningan</span>
            </h1>
            <p class="font-body-lg text-body-lg text-ink-dim max-w-xl">
                Layanan informasi, pengaduan, dan saran untuk kemajuan bersama. Kami siap melayani Anda pada jam kerja operasional balai pekon.
            </p>
        </div>
        <!-- Decorative -->
        <div class="absolute top-0 right-0 w-full md:w-1/2 h-full opacity-30 mix-blend-screen pointer-events-none">
            <svg class="w-full h-full text-surface-2 animate-pulse" fill="currentColor" viewBox="0 0 100 100">
                <circle cx="80" cy="20" r="40" filter="blur(40px)"></circle>
            </svg>
        </div>
    </section>

    <!-- Main Content Grid -->
    <section class="px-container-pad-mobile lg:px-container-pad-desktop max-w-container-max mx-auto w-full">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter lg:gap-16 items-start">

            <!-- Contact Info & Map (Left Col) -->
            <div class="lg:col-span-5 flex flex-col gap-12 order-2 lg:order-1">

                <!-- Info Cards -->
                <div class="flex flex-col gap-8">
                    <div class="flex gap-6 items-start group">
                        <div class="w-12 h-12 rounded-full bg-surface-2 flex items-center justify-center shrink-0 border border-line group-hover:bg-primary group-hover:border-primary transition-colors duration-300">
                            <span class="material-symbols-outlined text-gold-soft group-hover:text-on-primary transition-colors">location_on</span>
                        </div>
                        <div class="flex flex-col gap-2 pt-1">
                            <h3 class="font-h3 text-[20px] text-ink leading-tight">Balai Pekon</h3>
                            <p class="font-body-md text-body-md text-ink-dim">
                                <?= htmlspecialchars($alamat, ENT_QUOTES, 'UTF-8') ?>
                            </p>
                        </div>
                    </div>
                    <div class="flex gap-6 items-start group">
                        <div class="w-12 h-12 rounded-full bg-surface-2 flex items-center justify-center shrink-0 border border-line group-hover:bg-primary group-hover:border-primary transition-colors duration-300">
                            <span class="material-symbols-outlined text-gold-soft group-hover:text-on-primary transition-colors">schedule</span>
                        </div>
                        <div class="flex flex-col gap-2 pt-1">
                            <h3 class="font-h3 text-[20px] text-ink leading-tight">Jam Layanan</h3>
                            <div class="font-body-md text-body-md text-ink-dim flex flex-col gap-1">
                                <?php foreach ($jamLayanan as $hari => $jam): ?>
                                <div class="flex justify-between w-48">
                                    <span><?= $hari ?></span>
                                    <span class="<?= $jam === 'Tutup' ? 'opacity-50' : 'text-on-surface' ?>"><?= htmlspecialchars($jam, ENT_QUOTES, 'UTF-8') ?></span>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-6 items-start group">
                        <div class="w-12 h-12 rounded-full bg-surface-2 flex items-center justify-center shrink-0 border border-line group-hover:bg-primary group-hover:border-primary transition-colors duration-300">
                            <span class="material-symbols-outlined text-gold-soft group-hover:text-on-primary transition-colors">call</span>
                        </div>
                        <div class="flex flex-col gap-2 pt-1">
                            <h3 class="font-h3 text-[20px] text-ink leading-tight">Kontak Digital</h3>
                            <p class="font-body-md text-body-md text-ink-dim flex flex-col gap-1">
                                <?php if ($whatsapp !== ''):
                                    $whatsappDisplay = preg_match('/^0(\d+)$/', $whatsapp, $wm) ? '+62' . $wm[1] : (str_starts_with($whatsapp, '62') ? '+' . $whatsapp : $whatsapp);
                                ?>
                                <a class="hover:text-gold-soft transition-colors flex items-center gap-2 mt-1"
                                   href="https://wa.me/<?= htmlspecialchars($whatsappTel, ENT_QUOTES, 'UTF-8') ?>"
                                   target="_blank" rel="noopener noreferrer" aria-label="Chat WhatsApp" title="Chat WhatsApp">
                                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" class="w-4 h-4 shrink-0">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                    </svg>
                                    <?= htmlspecialchars($whatsappDisplay, ENT_QUOTES, 'UTF-8') ?>
                                </a>
                                <?php endif; ?>
                                <a class="hover:text-gold-soft transition-colors flex items-center gap-2 mt-1" href="mailto:info@airnaningan.desa.id">
                                    <span class="material-symbols-outlined text-[16px]">mail</span>
                                    info@airnaningan.desa.id
                                </a>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Map Box -->
                <div class="w-full h-64 bg-surface-2 rounded-xl border border-line overflow-hidden relative shadow-lg">
                    <?php if ($mapsEmbed !== ''): ?>
                        <iframe src="<?= htmlspecialchars($mapsEmbed, ENT_QUOTES, 'UTF-8') ?>" title="Peta Lokasi Balai Pekon Air Naningan" class="w-full h-full border-0" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center font-label-mono text-label-mono text-ink-dim">PETA BELUM DIKONFIGURASI</div>
                    <?php endif; ?>
                    <div class="absolute bottom-4 left-4 right-4 bg-surface/90 backdrop-blur-md border border-line p-3 rounded-lg flex items-center justify-between">
                        <span class="font-body-md text-sm text-ink-dim">Peta Lokasi Kantor Pekon</span>
                        <a href="<?= htmlspecialchars($mapsEmbed !== '' ? $mapsEmbed : 'https://maps.google.com', ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer"
                           class="text-primary hover:text-gold-soft text-sm font-medium flex items-center gap-1 transition-colors">
                            Buka Map <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                        </a>
                    </div>
                </div>

            </div>

            <!-- Contact Form (Right Col) -->
            <div class="lg:col-span-7 bg-surface-container rounded-2xl p-6 md:p-10 border border-line shadow-xl order-1 lg:order-2">
                <div class="mb-8">
                    <h2 class="font-h2 text-h2 text-ink mb-2">Kirim Pesan</h2>
                    <p class="font-body-md text-body-md text-ink-dim">Gunakan formulir di bawah ini untuk mengirimkan pertanyaan, pengaduan, atau saran secara langsung.</p>
                </div>
                <form class="flex flex-col gap-6" id="contact-form" novalidate>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex flex-col gap-2">
                            <label class="font-body-md text-[13px] text-ink-dim ml-1" for="contact-name">Nama Lengkap <span class="text-danger">*</span></label>
                    <input name="nama" class="w-full bg-surface border border-line rounded-lg px-4 py-3 text-ink font-body-md focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/50 transition-all placeholder:text-ink-dim/40"
                                   id="contact-name" placeholder="Cth: Budi Santoso" required type="text">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="font-body-md text-[13px] text-ink-dim ml-1" for="contact-wa">No. WhatsApp / Email <span class="text-danger">*</span></label>
                    <input name="kontak" class="w-full bg-surface border border-line rounded-lg px-4 py-3 text-ink font-body-md focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/50 transition-all placeholder:text-ink-dim/40"
                                   id="contact-wa" placeholder="0812xxx atau email@domain.com" required type="text">
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="font-body-md text-[13px] text-ink-dim ml-1" for="contact-subject">Kategori / Subjek <span class="text-danger">*</span></label>
                        <div class="relative">
                            <select class="w-full bg-surface border border-line rounded-lg px-4 py-3 text-ink font-body-md focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/50 transition-all appearance-none cursor-pointer"
                                    name="kategori" id="contact-subject" required>
                                <option disabled selected value="">Pilih jenis pesan...</option>
                                <option value="info">Permintaan Informasi</option>
                                <option value="layanan">Layanan Administrasi</option>
                                <option value="pengaduan">Pengaduan Masyarakat</option>
                                <option value="saran">Kritik &amp; Saran</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-ink-dim pointer-events-none">expand_more</span>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="font-body-md text-[13px] text-ink-dim ml-1" for="contact-message">Isi Pesan <span class="text-danger">*</span></label>
                        <textarea class="w-full bg-surface border border-line rounded-lg px-4 py-3 text-ink font-body-md focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/50 transition-all resize-none placeholder:text-ink-dim/40"
                                  name="pesan" id="contact-message" placeholder="Tuliskan detail pesan Anda di sini..." required rows="5"></textarea>
                    </div>
                    <div class="pt-4 flex items-center justify-between border-t border-line mt-2">
                        <span class="text-[12px] font-body-md text-ink-dim/60 hidden md:block">Pesan akan dibalas pada jam kerja.</span>
                        <button class="w-full md:w-auto px-8 py-3 bg-primary text-on-primary rounded-full font-body-md font-medium hover:bg-gold-soft transition-colors flex items-center justify-center gap-2 shadow-lg shadow-primary/10"
                                type="submit" id="submit-btn">
                            Kirim Pesan
                            <span class="material-symbols-outlined text-[18px]">send</span>
                        </button>
                    </div>
                </form>
                <!-- Success state -->
                <div class="hidden mt-6 p-4 bg-surface-2 border border-primary/30 rounded-xl flex items-center gap-3 text-ink" id="form-success">
                    <span class="material-symbols-outlined text-primary text-[24px]">check_circle</span>
                    <p class="font-body-md">Pesan Anda berhasil dikirim! Kami akan merespons pada jam kerja berikutnya.</p>
                </div>
            </div>

        </div>
    </section>

</div>

<script>
    document.getElementById('contact-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        if (!this.checkValidity()) { this.reportValidity(); return; }
        const btn = document.getElementById('submit-btn');
        btn.disabled = true;
        btn.innerHTML = '<span class="material-symbols-outlined animate-spin text-[18px]">progress_activity</span> Mengirim...';
        try {
            const response = await fetch('<?= htmlspecialchars((defined('APP_BASE') ? APP_BASE : ''), ENT_QUOTES, 'UTF-8') ?>/kirim-pesan', {
                method: 'POST',
                body: new FormData(this),
                credentials: 'same-origin'
            });
            const responseText = await response.text();
            let result;
            try {
                result = JSON.parse(responseText);
            } catch (error) {
                throw new Error('Server mengirim respons yang tidak valid. Silakan coba lagi.');
            }
            if (!response.ok || !result.success) throw new Error(result.message || 'Pesan gagal dikirim.');
            this.reset();
            const success = document.getElementById('form-success');
            success.querySelector('p').textContent = result.message;
            success.classList.remove('hidden');
            success.classList.add('flex');
            setTimeout(() => { success.classList.add('hidden'); success.classList.remove('flex'); }, 5000);
        } catch (error) {
            alert(error.message || 'Pesan gagal dikirim.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = 'Kirim Pesan <span class="material-symbols-outlined text-[18px]">send</span>';
        }
    });
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
