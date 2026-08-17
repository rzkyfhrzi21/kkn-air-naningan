/* ======================================================
   SKRIP JAVASCRIPT: JEBAKAN FOKUS MODAL (MODAL FOCUS TRAP)

   File ini ibarat "petugas pengatur lalu lintas papan popup (modal)":
   saat sebuah modal popup muncul di layar, skrip ini memastikan pengguna
   yang menggunakan keyboard (tombol Tab) atau pembaca layar (screen reader)
   tidak "tersesat" keluar dari modal sebelum modal tersebut ditutup.

   Alur Kerjanya:
   (1) Mengamati perubahan di layar (MutationObserver) — jika ada modal dengan atribut `data-modal`
       yang muncul (kelas `.hidden` dilepas), jalankan jebakan fokus.
   (2) Saat modal terbuka, otomatis gerakkan kursor fokus ke elemen interaktif pertama (tombol/input).
   (3) Jika pengguna menekan tombol **Tab**, kunci kursor agar berputar di dalam modal saja
       (dari tombol terakhir kembali ke tombol pertama).
   (4) Jika pengguna menekan tombol **ESC**, otomatis simulasikan klik tombol silang (Close).
   (5) Saat modal ditutup, kembalikan kursor fokus ke tombol pemicu awal sebelum modal dibuka.
====================================================== */

/**
 * modal-focus.js — Focus trap ringan untuk modal custom.
 *
 * Menerapkan docs/BRIEF_STANDAR_INTERAKSI_DAN_KEAMANAN.md §12.4:
 *  - Saat elemen [data-modal] terbuka (kelas .hidden dilepas), fokus
 *    diarahkan ke elemen interaktif pertama (input / tombol ×).
 *  - Tombol Tab dikunci di dalam modal (cycle pertama→terakhir).
 *  - Tombol ESC menutup via tombol [data-modal-close] (fallback: aria-label Tutup).
 *  - Saat modal ditutup, fokus dikembalikan ke tombol pemicu awal.
 *
 * Tidak menyentuh logika open/close milik halaman: hanya mengobservasi
 * perubahan class pada elemen bertanda data-modal.
 */
(function () {
    'use strict';

    // Daftar selector elemen yang bisa menerima fokus kursor (tombol, input, link, dll)
    var FOCUSABLE = 'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])';

    var stack = [];      // Tumpukan modal yang sedang terbuka
    var triggers = [];   // Daftar tombol pemicu awal per modal

    // Fungsi cek: apakah elemen modal sedang tampil di layar (tidak tersembunyi)?
    function isOpen(el) {
        return el && !el.classList.contains('hidden') && getComputedStyle(el).display !== 'none';
    }

    // Fungsi cari: temukan tombol silang / tombol tutups di dalam modal
    function closeBtn(el) {
        var btn = el.querySelector('[data-modal-close]');
        if (btn) return btn;
        return el.querySelector('button[aria-label*="Tutup" i], button[aria-label*="close" i]');
    }

    // (3) & (4) Penangan tombol keyboard (Tab dan Escape)
    function onKey(e) {
        var top = stack[stack.length - 1]; // Ambil modal paling atas yang sedang aktif
        if (!top) return;

        // (3) Kunci tombol Tab di dalam modal
        if (e.key === 'Tab') {
            var focusables = top.querySelectorAll(FOCUSABLE);
            if (!focusables.length) return;
            var first = focusables[0];
            var last = focusables[focusables.length - 1];

            // Shift + Tab di elemen pertama → lempar fokus ke elemen terakhir
            if (e.shiftKey && (document.activeElement === first || !top.contains(document.activeElement))) {
                e.preventDefault();
                last.focus();
            } else if (!e.shiftKey && document.activeElement === last) {
                // Tab biasa di elemen terakhir → putar balik ke elemen pertama
                e.preventDefault();
                first.focus();
            } else if (!top.contains(document.activeElement)) {
                e.preventDefault();
                first.focus();
            }
        } else if (e.key === 'Escape') {
            // (4) Tekan ESC → klik tombol tutup
            var btn = closeBtn(top);
            if (btn) btn.click();
        }
    }

    // (2) Buka Modal: simpan elemen pemicu & pindahkan fokus ke input/tombol pertama
    function openModal(el) {
        stack.push(el);
        triggers.push(document.activeElement);
        var first = el.querySelector(FOCUSABLE) || closeBtn(el);
        if (first && typeof first.focus === 'function') first.focus();
    }

    // (5) Tutup Modal: keluarkan dari tumpukan & kembalikan fokus ke pemicu awal
    function closeModal(el) {
        var idx = stack.indexOf(el);
        if (idx === -1) return;
        stack.splice(idx, 1);
        var trigger = triggers.splice(idx, 1)[0];

        var next = stack[stack.length - 1];
        if (next) {
            var target = next.querySelector(FOCUSABLE) || next;
            if (target && typeof target.focus === 'function') target.focus();
        } else if (trigger && typeof trigger.focus === 'function' && document.contains(trigger)) {
            trigger.focus();
        }
    }

    // Pasang pendengar tombol keyboard pada dokumen
    document.addEventListener('keydown', onKey, true);

    // (1) Pengamat Perubahan DOM (MutationObserver) untuk mendeteksi kapan modal muncul/sembunyi
    var observer = new MutationObserver(function (mutations) {
        for (var i = 0; i < mutations.length; i++) {
            var m = mutations[i];
            var el = m.target;
            if (!el || el.nodeType !== 1 || typeof el.hasAttribute !== 'function' || !el.hasAttribute('data-modal')) continue;
            if (m.attributeName !== 'class') continue;

            if (isOpen(el)) {
                if (stack.indexOf(el) === -1) openModal(el);
            } else if (stack.indexOf(el) !== -1) {
                closeModal(el);
            }
        }
    });

    if (document.body) {
        observer.observe(document.body, { subtree: true, attributes: true, attributeFilter: ['class'] });
    }
})();

