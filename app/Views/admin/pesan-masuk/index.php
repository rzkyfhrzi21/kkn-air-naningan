<?php
$pageTitle = 'Pesan Masuk';
$activeNav = 'pesan-masuk';
require __DIR__ . '/../partials/header.php';
?>
<div class="flex flex-col w-full px-container-pad-mobile md:px-container-pad-desktop py-8 md:py-12 gap-8">

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 border-b border-line pb-6">
        <div class="flex flex-col gap-2">
            <h1 class="font-h1-mobile md:font-h1 text-h1-mobile md:text-h1 text-ink">Pesan Masuk</h1>
            <p class="font-body-md text-body-md text-on-surface-variant max-w-2xl">
                Kelola dan balas pertanyaan, masukan, atau keluhan dari masyarakat yang dikirim melalui formulir kontak.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <button class="flex items-center gap-2 px-4 py-2.5 rounded-full bg-surface-container hover:bg-surface-container-high text-ink font-body-md text-[14px] transition-colors border border-line">
                <span class="material-symbols-outlined text-[20px]">filter_list</span>
                <span>Filter</span>
            </button>
            <button class="flex items-center gap-2 px-4 py-2.5 rounded-full bg-surface-container hover:bg-surface-container-high text-ink font-body-md text-[14px] transition-colors border border-line">
                <span class="material-symbols-outlined text-[20px]">archive</span>
                <span>Arsip</span>
            </button>
        </div>
    </div>

    <!-- Split View: Message List + Detail -->
    <div class="flex flex-col lg:flex-row gap-6 h-[calc(100vh-280px)] min-h-[600px]">

        <!-- Left: Message List -->
        <div class="w-full lg:w-[400px] xl:w-[450px] flex flex-col bg-surface rounded-xl border border-line overflow-hidden shadow-sm shrink-0">

            <!-- Search -->
            <div class="p-4 border-b border-line flex items-center justify-between bg-surface-container-lowest">
                <div class="relative w-full">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
                    <input id="search-pesan" class="w-full bg-surface-container text-ink font-body-md text-[14px] rounded-lg pl-10 pr-4 py-2.5 outline-none focus:ring-1 focus:ring-primary/50 transition-all border border-transparent focus:border-line placeholder:text-on-surface-variant/50" placeholder="Cari pesan..." type="text"/>
                </div>
            </div>

            <!-- Message Items -->
            <div class="flex-1 overflow-y-auto" id="message-list">

                <!-- Pesan 1: Aktif/Baru -->
                <button class="w-full text-left p-4 border-l-4 border-primary bg-surface-container-low hover:bg-surface-container transition-colors group flex flex-col gap-2 relative border-b border-line">
                    <div class="flex justify-between items-start w-full">
                        <span class="font-h3 text-[16px] text-ink truncate pr-4">Budi Santoso</span>
                        <span class="font-label-mono text-label-mono text-primary shrink-0 pt-1">BARU</span>
                    </div>
                    <span class="font-body-md text-[14px] text-on-surface-variant truncate w-full group-hover:text-ink transition-colors">Tanya Jadwal Wisata Air Terjun</span>
                    <div class="flex items-center justify-between w-full mt-1">
                        <span class="font-body-md text-[12px] text-on-surface-variant/70">Hari ini, 09:42 WIB</span>
                        <span class="material-symbols-outlined text-primary text-[16px]">fiber_manual_record</span>
                    </div>
                </button>

                <!-- Pesan 2 -->
                <button class="w-full text-left p-4 border-l-4 border-transparent hover:bg-surface-container-low transition-colors group flex flex-col gap-2 relative border-b border-line">
                    <div class="flex justify-between items-start w-full">
                        <span class="font-h3 text-[16px] text-on-surface-variant group-hover:text-ink truncate pr-4 transition-colors">Siti Aminah</span>
                        <span class="font-label-mono text-label-mono text-on-surface-variant/50 shrink-0 pt-1">KEMARIN</span>
                    </div>
                    <span class="font-body-md text-[14px] text-on-surface-variant/70 truncate w-full group-hover:text-on-surface-variant transition-colors">Laporan Infrastruktur Jalan Rusak di Dusun 3</span>
                    <div class="flex items-center justify-between w-full mt-1">
                        <span class="font-body-md text-[12px] text-on-surface-variant/50">Kemarin, 15:20 WIB</span>
                    </div>
                </button>

                <!-- Pesan 3 -->
                <button class="w-full text-left p-4 border-l-4 border-transparent hover:bg-surface-container-low transition-colors group flex flex-col gap-2 relative border-b border-line">
                    <div class="flex justify-between items-start w-full">
                        <span class="font-h3 text-[16px] text-on-surface-variant group-hover:text-ink truncate pr-4 transition-colors">Komunitas Kopi Lampung</span>
                        <span class="font-label-mono text-label-mono text-on-surface-variant/50 shrink-0 pt-1">12 OKT</span>
                    </div>
                    <span class="font-body-md text-[14px] text-on-surface-variant/70 truncate w-full group-hover:text-on-surface-variant transition-colors">Undangan Kolaborasi Festival Kopi</span>
                    <div class="flex items-center justify-between w-full mt-1">
                        <span class="font-body-md text-[12px] text-on-surface-variant/50">12 Okt 2023, 10:05 WIB</span>
                    </div>
                </button>

                <!-- Pesan 4 -->
                <button class="w-full text-left p-4 border-l-4 border-transparent hover:bg-surface-container-low transition-colors group flex flex-col gap-2 relative border-b border-line">
                    <div class="flex justify-between items-start w-full">
                        <span class="font-h3 text-[16px] text-on-surface-variant group-hover:text-ink truncate pr-4 transition-colors">Agus Pratama</span>
                        <span class="font-label-mono text-label-mono text-on-surface-variant/50 shrink-0 pt-1">10 OKT</span>
                    </div>
                    <span class="font-body-md text-[14px] text-on-surface-variant/70 truncate w-full group-hover:text-on-surface-variant transition-colors">Kendala Registrasi UMKM</span>
                    <div class="flex items-center justify-between w-full mt-1">
                        <span class="font-body-md text-[12px] text-on-surface-variant/50">10 Okt 2023, 08:30 WIB</span>
                    </div>
                </button>

            </div>
        </div>

        <!-- Right: Message Detail -->
        <div class="flex-1 bg-surface-2 rounded-xl border border-line flex flex-col overflow-hidden shadow-md relative">

            <!-- Detail Header -->
            <div class="p-6 border-b border-line/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-surface-container-lowest/50 backdrop-blur-sm z-10 sticky top-0">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center font-h3 text-xl shrink-0 uppercase">
                        BS
                    </div>
                    <div class="flex flex-col">
                        <h2 class="font-h3 text-xl text-ink">Budi Santoso</h2>
                        <div class="flex items-center gap-2 text-on-surface-variant font-body-md text-[14px]">
                            <a class="hover:text-primary transition-colors" href="mailto:budi.s@example.com">budi.s@example.com</a>
                            <span class="w-1 h-1 rounded-full bg-line-strong"></span>
                            <span>0812-3456-7890</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <button aria-label="Tandai belum dibaca" class="p-2 rounded-full hover:bg-surface-container text-on-surface-variant hover:text-primary transition-colors">
                        <span class="material-symbols-outlined text-[22px]">mark_as_unread</span>
                    </button>
                    <button aria-label="Arsipkan" class="p-2 rounded-full hover:bg-surface-container text-on-surface-variant hover:text-ink transition-colors">
                        <span class="material-symbols-outlined text-[22px]">archive</span>
                    </button>
                    <button aria-label="Hapus" class="p-2 rounded-full hover:bg-error-container/20 text-on-surface-variant hover:text-error transition-colors">
                        <span class="material-symbols-outlined text-[22px]">delete</span>
                    </button>
                </div>
            </div>

            <!-- Message Body -->
            <div class="flex-1 overflow-y-auto p-6 md:p-8">
                <div class="max-w-3xl mx-auto">

                    <!-- Subject -->
                    <div class="mb-8">
                        <span class="inline-block px-3 py-1 rounded-full bg-primary/10 text-primary font-label-mono text-[10px] mb-4 border border-primary/20">PERTANYAAN UMUM</span>
                        <h3 class="font-h2 text-2xl md:text-3xl text-ink leading-tight mb-2">Tanya Jadwal Wisata Air Terjun</h3>
                        <p class="font-body-md text-[14px] text-on-surface-variant/70">Diterima pada 14 Oktober 2023, pukul 09:42 WIB</p>
                    </div>

                    <!-- Message Content -->
                    <div class="font-body-lg text-on-surface-variant space-y-6 leading-relaxed">
                        <p>Selamat Pagi Admin Pekon Air Naningan,</p>
                        <p>Perkenalkan saya Budi dari Bandar Lampung. Saya dan keluarga berencana untuk mengunjungi Air Terjun di wilayah Air Naningan pada akhir pekan ini (hari Minggu).</p>
                        <p>Saya ingin menanyakan beberapa hal terkait operasional wisata di sana:</p>
                        <ul class="list-disc pl-5 space-y-2 marker:text-primary/50">
                            <li>Apakah tempat wisata buka secara penuh pada hari Minggu?</li>
                            <li>Berapa harga tiket masuk per orang saat ini?</li>
                            <li>Apakah jalan menuju ke lokasi sudah bisa dilalui oleh mobil MPV biasa, atau harus menggunakan kendaraan khusus/motor?</li>
                        </ul>
                        <p>Mohon informasinya agar kami bisa mempersiapkan perjalanan dengan baik. Terima kasih banyak atas bantuannya.</p>
                        <p>Salam hangat,<br/>Budi Santoso</p>
                    </div>

                    <!-- Reply Form -->
                    <div class="mt-12 pt-8 border-t border-line/50">
                        <h4 class="font-h3 text-lg text-ink mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">reply</span> Balas Pesan
                        </h4>
                        <div class="bg-surface-container rounded-xl p-1 border border-line-strong focus-within:border-primary/50 focus-within:ring-1 focus-within:ring-primary/20 transition-all">
                            <textarea id="reply-text" class="w-full bg-transparent text-ink font-body-md p-3 outline-none resize-none placeholder:text-on-surface-variant/40" placeholder="Tulis balasan untuk Budi Santoso..." rows="5"></textarea>
                            <div class="flex justify-between items-center p-2 border-t border-line/30">
                                <div class="flex items-center gap-1">
                                    <button class="p-2 rounded-lg hover:bg-surface-container-high text-on-surface-variant transition-colors"><span class="material-symbols-outlined text-[20px]">format_bold</span></button>
                                    <button class="p-2 rounded-lg hover:bg-surface-container-high text-on-surface-variant transition-colors"><span class="material-symbols-outlined text-[20px]">format_italic</span></button>
                                    <button class="p-2 rounded-lg hover:bg-surface-container-high text-on-surface-variant transition-colors"><span class="material-symbols-outlined text-[20px]">link</span></button>
                                </div>
                                <button class="px-6 py-2 rounded-full bg-primary text-on-primary font-body-md font-medium hover:bg-primary-fixed transition-colors flex items-center gap-2 shadow-sm shadow-primary/20">
                                    <span>Kirim</span>
                                    <span class="material-symbols-outlined text-[18px]">send</span>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
