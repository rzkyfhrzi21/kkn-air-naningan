<?php
$currentPage     = 'wisata';
$pageTitle       = 'Wisata | Pekon Air Naningan';
$metaDescription = 'Temukan destinasi wisata alam di Pekon Air Naningan — air terjun, titik pandang pegunungan, dan pesona alam lereng Tanggamus.';
require __DIR__ . '/../partials/header.php';
?>

<div class="flex flex-col w-full overflow-hidden pb-section-v-desktop">

    <!-- Hero Section -->
    <section class="relative w-full h-[614px] min-h-[500px] flex items-center justify-center -mt-20 pt-20">
        <div class="absolute inset-0 bg-cover bg-center z-0"
             style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCFRHoh3JG1xbhX1MNkU8-Z41W68Koz9Il7gtIQCQ9-YdFxs1r6C6d4jddxoBgMtNuyxjpEhC_sALVaWRKlYL6jld6V19_5PlQwzUy4ual9tmz-xc2B2-Mb4BYSrdPPteDPTyk6tKzC5mL9v0fMeSy5stt5JulthWRFhgy19zCktL3Kr1uSdb92ERmkRJJl-qwZ90481PybLnSaG8caeJCec0wI0NglmDKo9lr9VTmVIFHS0glBONNO-A')">
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-bg via-bg/60 to-transparent z-10"></div>
        <div class="relative z-20 w-full max-w-container-max mx-auto px-container-pad-mobile lg:px-container-pad-desktop text-center flex flex-col items-center">
            <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-[0.2em] mb-6 opacity-0 translate-y-4 animate-[fade-in-up_0.8s_ease-out_forwards]">Jelajahi Keindahan</span>
            <h1 class="font-h1-mobile lg:font-h1 text-h1-mobile lg:text-h1 text-ink mb-6 max-w-3xl opacity-0 translate-y-4 animate-[fade-in-up_0.8s_ease-out_0.2s_forwards]">
                Pesona Alam <br><span class="text-primary italic">Air Naningan</span>
            </h1>
            <p class="font-body-lg text-body-lg text-ink-dim max-w-2xl mx-auto opacity-0 translate-y-4 animate-[fade-in-up_0.8s_ease-out_0.4s_forwards]">
                Temukan surga tersembunyi di hamparan hijau perbukitan. Destinasi wisata alam yang menawarkan ketenangan dan petualangan tak terlupakan.
            </p>
        </div>
    </section>

    <!-- Destinations Grid Section -->
    <section class="w-full max-w-container-max mx-auto px-container-pad-mobile lg:px-container-pad-desktop -mt-16 relative z-30">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">

            <!-- Card 1: Air Terjun -->
            <article class="group bg-surface-container rounded-2xl overflow-hidden shadow-xl transition-transform duration-500 hover:-translate-y-2 border border-line flex flex-col h-full">
                <div class="relative h-72 w-full overflow-hidden">
                    <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                         alt="Air terjun Curug Tirta Kencana di Air Naningan"
                         src="https://lh3.googleusercontent.com/aida-public/AB6AXuCwUA8gwPgZk37dZY0fg2zZyD9x-L261K1EtAmftnqQqyIDrXBOHFIwJf5oIH8BczPJRWpawLhiAWD32M24Y7XHSoV922g8l1KCen88YuZJ3qg9L64uGoev1ohbzUX0q5Gyuq4IUwuhvoYvtWqhUH1wUVp_RE3bQhNka0-IUnxvout-6H7xVZ4KFRcsfcFIdRG_CVERTuFPFZ_cGJ6tpucaWkeawLVkjiHbB6LX6YsfWXg9h00AzUvapA">
                    <div class="absolute top-4 left-4 bg-bg/80 backdrop-blur-md px-3 py-1 rounded-full border border-line">
                        <span class="font-label-mono text-[10px] text-primary uppercase tracking-wider flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[14px]">water_drop</span> Air Terjun
                        </span>
                    </div>
                </div>
                <div class="p-8 flex flex-col flex-grow">
                    <div class="flex justify-between items-start mb-4">
                        <h2 class="font-h2 text-[28px] text-ink leading-tight">Curug Tirta <br>Kencana</h2>
                        <span class="flex items-center gap-1 text-ink-dim font-label-mono text-label-mono">
                            <span class="material-symbols-outlined text-[16px] text-gold-soft">location_on</span> 2.5 km
                        </span>
                    </div>
                    <p class="font-body-md text-body-md text-ink-dim mb-6 flex-grow line-clamp-3">
                        Air terjun tersembunyi dengan debit air stabil sepanjang tahun. Dikelilingi hutan primer yang asri, menawarkan kolam alami untuk berenang dan bersantai menikmati suara gemericik air.
                    </p>
                    <div class="flex flex-wrap gap-2 mb-8">
                        <span class="px-3 py-1 bg-surface-2 rounded-full text-ink-dim text-xs font-label-mono flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">hiking</span> Akses Sedang</span>
                        <span class="px-3 py-1 bg-surface-2 rounded-full text-ink-dim text-xs font-label-mono flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">wc</span> Toilet</span>
                        <span class="px-3 py-1 bg-surface-2 rounded-full text-ink-dim text-xs font-label-mono flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">storefront</span> Warung</span>
                    </div>
                    <a class="inline-flex items-center justify-between w-full px-6 py-4 bg-primary text-on-primary rounded-full font-label-mono text-label-mono uppercase tracking-wider hover:bg-gold-soft transition-colors"
                       href="https://maps.google.com" target="_blank" rel="noopener noreferrer">
                        <span>Buka di Peta</span>
                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    </a>
                </div>
            </article>

            <!-- Card 2: Titik Pandang -->
            <article class="group bg-surface-container rounded-2xl overflow-hidden shadow-xl transition-transform duration-500 hover:-translate-y-2 border border-line flex flex-col h-full lg:translate-y-12">
                <div class="relative h-72 w-full overflow-hidden">
                    <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                         alt="Puncak Bintang titik pandang di Air Naningan"
                         src="https://lh3.googleusercontent.com/aida-public/AB6AXuATodpjCH4RizqTgSvvyJTRF3HgWV8NkhWHYk8mGmLiJG4j0Of65Iduq9v8i3QPIYKFznyn936zIETy26ljl7urPstshsOWKeDmIS2LhqX3M2cwNHD4B8eZ8YiZJrdJdj_0YbNOJx1q0r_TT_gLve6y_1cBjHwdsaTQl9Q3uy9fhfGyZwupWJuDC8ZLVBKGaEjuWxN3OAbKZq6qmEduUE7_awsv6Lo1OkjvHmFJI6s1czvkX4YbvRjw9w">
                    <div class="absolute top-4 left-4 bg-bg/80 backdrop-blur-md px-3 py-1 rounded-full border border-line">
                        <span class="font-label-mono text-[10px] text-primary uppercase tracking-wider flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[14px]">landscape</span> Titik Pandang
                        </span>
                    </div>
                </div>
                <div class="p-8 flex flex-col flex-grow">
                    <div class="flex justify-between items-start mb-4">
                        <h2 class="font-h2 text-[28px] text-ink leading-tight">Puncak <br>Bintang</h2>
                        <span class="flex items-center gap-1 text-ink-dim font-label-mono text-label-mono">
                            <span class="material-symbols-outlined text-[16px] text-gold-soft">location_on</span> 4.0 km
                        </span>
                    </div>
                    <p class="font-body-md text-body-md text-ink-dim mb-6 flex-grow line-clamp-3">
                        Titik tertinggi di Air Naningan yang menawarkan pemandangan 360 derajat lanskap pegunungan dan lautan awan di pagi hari. Spot ideal untuk fotografi dan berkemah.
                    </p>
                    <div class="flex flex-wrap gap-2 mb-8">
                        <span class="px-3 py-1 bg-surface-2 rounded-full text-ink-dim text-xs font-label-mono flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">terrain</span> Akses Menantang</span>
                        <span class="px-3 py-1 bg-surface-2 rounded-full text-ink-dim text-xs font-label-mono flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">camping</span> Area Kemah</span>
                        <span class="px-3 py-1 bg-surface-2 rounded-full text-ink-dim text-xs font-label-mono flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">local_parking</span> Parkir Motor</span>
                    </div>
                    <a class="inline-flex items-center justify-between w-full px-6 py-4 bg-primary text-on-primary rounded-full font-label-mono text-label-mono uppercase tracking-wider hover:bg-gold-soft transition-colors"
                       href="https://maps.google.com" target="_blank" rel="noopener noreferrer">
                        <span>Buka di Peta</span>
                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    </a>
                </div>
            </article>

        </div>
    </section>

    <!-- Info & Map Section -->
    <section class="w-full max-w-container-max mx-auto px-container-pad-mobile lg:px-container-pad-desktop mt-section-v-desktop">
        <div class="flex flex-col lg:flex-row gap-12 items-center">
            <div class="w-full lg:w-1/3 flex flex-col gap-8">
                <div>
                    <h3 class="font-h3 text-h3 text-ink mb-4">Peta Kawasan Wisata</h3>
                    <p class="font-body-md text-body-md text-ink-dim">
                        Gunakan panduan ini untuk merencanakan rute perjalanan Anda. Sebagian besar rute dapat diakses kendaraan roda dua, namun untuk titik tertentu disarankan berjalan kaki.
                    </p>
                </div>
                <div class="flex flex-col gap-4">
                    <div class="flex items-start gap-4 p-4 rounded-xl bg-surface-container border border-line">
                        <div class="w-10 h-10 rounded-full bg-surface-2 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-gold-soft">info</span>
                        </div>
                        <div>
                            <h4 class="font-label-mono text-label-mono text-ink uppercase tracking-wider mb-1">Tiket Masuk</h4>
                            <p class="font-body-md text-sm text-ink-dim">Retribusi desa Rp 10.000/orang dialokasikan untuk pemeliharaan fasilitas umum.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full lg:w-2/3">
                <div class="relative w-full h-[400px] rounded-2xl overflow-hidden border border-line-strong shadow-2xl">
                    <div class="w-full h-full bg-cover bg-center"
                         style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDQXDRR1NJGJPorwslAdBElThlZXeI9wXaEtkK-_BTsgW4wz1b6JPfGvdl5TVVN2DJasMUynFc-bM95Yr9cE2x-9RTUlb23xkJ-OTOUWKRec2-FvZW5sbTQGmJURWTAwbTXbatQSWMLS0ov-uC--ZWpehX8kjDAhqLxd2FFW-4R_laMOP2QGJPeaqL9_NN76Cdc4AJ9KqCDqfx4TLL9jqGnT2tgmvbZwYRyTGTXE-Tdy3J_N7mia6SO6g')">
                    </div>
                    <div class="absolute inset-0 bg-surface/10 pointer-events-none"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="w-full max-w-container-max mx-auto px-container-pad-mobile lg:px-container-pad-desktop mt-section-v-desktop mb-8">
        <div class="relative w-full rounded-3xl overflow-hidden bg-surface-container flex flex-col md:flex-row items-center border border-line-strong p-8 md:p-12">
            <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-full blur-[80px] pointer-events-none"></div>
            <div class="flex-1 md:pr-12 text-center md:text-left z-10 mb-8 md:mb-0">
                <span class="font-label-mono text-label-mono text-gold-soft uppercase tracking-widest block mb-3">Butuh Bantuan?</span>
                <h2 class="font-h2 text-h2 text-ink mb-4">Gunakan Pemandu Lokal</h2>
                <p class="font-body-md text-body-md text-ink-dim mb-8 max-w-lg">
                    Maksimalkan pengalaman eksplorasi Anda dengan pemandu lokal yang memahami setiap jalur tersembunyi dan cerita di baliknya. Dukung juga perekonomian warga setempat.
                </p>
                <a href="<?= $base ?>/kontak" class="inline-flex items-center justify-center gap-3 px-8 py-4 bg-ink text-bg rounded-full font-label-mono text-label-mono uppercase tracking-widest hover:bg-white transition-colors group">
                    Hubungi Pemandu
                    <span class="material-symbols-outlined text-[18px] group-hover:translate-x-1 transition-transform">chat</span>
                </a>
            </div>
            <div class="w-full md:w-1/3 shrink-0 z-10">
                <div class="relative w-full aspect-square rounded-full overflow-hidden border-4 border-surface shadow-2xl mx-auto max-w-[250px]">
                    <img class="w-full h-full object-cover"
                         alt="Pemandu lokal wisata Air Naningan"
                         src="https://lh3.googleusercontent.com/aida-public/AB6AXuCeXhQYS78zOWJiEJMEbUbwAgUPnbDq1BTtjYkd_XPecO5F6nUkhkFkFYZ_kFHkVv-uQFRYI4RqkUKRcTFBi03FlMxvC8oy08mOJ0DvzE5HZ_ibogU0kB4pmahkTx3AAVlmSCWGCGX78b6KSMoKrBSzDKN7vMx3UNhEPIR2n1dhe6QcJtnccvmj831Ztb6BSaE7ZVp5mMFnQx8AXil_4VD099Fa4mc0bNonJ8zrUj18XtAtnyxvU-R7Xg">
                </div>
            </div>
        </div>
    </section>

</div>

<style>
    @keyframes fade-in-up {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }
</style>

<?php require __DIR__ . '/../partials/footer.php'; ?>
