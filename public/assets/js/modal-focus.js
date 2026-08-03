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

    var FOCUSABLE = 'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])';

    var stack = [];      // modal yang sedang terbuka (tumpukan)
    var triggers = [];   // elemen pemicu per modal

    function isOpen(el) {
        return el && !el.classList.contains('hidden') && getComputedStyle(el).display !== 'none';
    }

    function closeBtn(el) {
        var btn = el.querySelector('[data-modal-close]');
        if (btn) return btn;
        return el.querySelector('button[aria-label*="Tutup" i], button[aria-label*="close" i]');
    }

    function onKey(e) {
        var top = stack[stack.length - 1];
        if (!top) return;

        if (e.key === 'Tab') {
            var focusables = top.querySelectorAll(FOCUSABLE);
            if (!focusables.length) return;
            var first = focusables[0];
            var last = focusables[focusables.length - 1];

            if (e.shiftKey && (document.activeElement === first || !top.contains(document.activeElement))) {
                e.preventDefault();
                last.focus();
            } else if (!e.shiftKey && document.activeElement === last) {
                e.preventDefault();
                first.focus();
            } else if (!top.contains(document.activeElement)) {
                e.preventDefault();
                first.focus();
            }
        } else if (e.key === 'Escape') {
            var btn = closeBtn(top);
            if (btn) btn.click();
        }
    }

    function openModal(el) {
        stack.push(el);
        triggers.push(document.activeElement);
        var first = el.querySelector(FOCUSABLE) || closeBtn(el);
        if (first && typeof first.focus === 'function') first.focus();
    }

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

    document.addEventListener('keydown', onKey, true);

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
