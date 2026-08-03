    </main><!-- /main -->

    <!-- Admin Footer -->
    <footer class="border-t border-line px-4 md:px-8 py-5 flex flex-col md:flex-row items-center justify-between gap-3">
        <span class="font-label-mono text-[10px] text-ink-dim tracking-widest uppercase">© 2026 Pekon Air Naningan. All Rights Reserved.</span>
        <?php
        require_once dirname(__DIR__, 4) . '/includes/env.php';
        loadEnv(dirname(__DIR__, 4) . '/.env');
        $instagramHandle = env('KONTAK_INSTAGRAM_HANDLE', '');
        $instagramUrl    = env('KONTAK_INSTAGRAM_URL', '');
        ?>
        <?php if ($instagramUrl !== '' && $instagramHandle !== ''): ?>
        <a class="flex items-center gap-2 font-label-mono text-[11px] text-ink-dim hover:text-primary transition-colors"
            href="<?= htmlspecialchars($instagramUrl, ENT_QUOTES, 'UTF-8') ?>"
            target="_blank" rel="noopener noreferrer"
            title="Instagram Pekon Air Naningan">
            <span class="material-symbols-outlined text-[14px]">photo_camera</span>
            <?= htmlspecialchars($instagramHandle, ENT_QUOTES, 'UTF-8') ?>
        </a>
        <?php endif; ?>
    </footer>
</div><!-- /.pl-[280px] -->

<?php $base = defined('APP_BASE') ? APP_BASE : ''; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc4s9bIOgUxi8T/jzmPM3LzGm/0+bMq+Cw/0m5DxNh" crossorigin="anonymous"></script>
<script src="<?= htmlspecialchars($base, ENT_QUOTES, 'UTF-8') ?>/security-warning.js"></script>
<script>
    const sidebarToggle  = document.getElementById('sidebar-toggle');
    const sidebarOverlay = document.getElementById('sidebar-overlay');
    const isDesktopView  = () => window.matchMedia('(min-width: 1024px)').matches;
    const SIDEBAR_KEY    = 'admin-sidebar-collapsed';

    if (isDesktopView() && localStorage.getItem(SIDEBAR_KEY) === '1') {
        document.body.classList.add('sidebar-collapsed');
    }

    sidebarToggle?.addEventListener('click', () => {
        if (isDesktopView()) {
            document.body.classList.toggle('sidebar-collapsed');
            localStorage.setItem(SIDEBAR_KEY, document.body.classList.contains('sidebar-collapsed') ? '1' : '0');
        } else {
            document.body.classList.toggle('sidebar-open');
        }
    });

    sidebarOverlay?.addEventListener('click', () => {
        document.body.classList.remove('sidebar-open');
    });

    window.addEventListener('resize', () => {
        if (isDesktopView()) document.body.classList.remove('sidebar-open');
    });

    /* ── Jam realtime (topbar) ── */
    const clockDate = document.getElementById('topbar-clock-date');
    const clockTime = document.getElementById('topbar-clock-time');
    const DAYS  = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const MONTHS = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    function updateClock() {
        const now = new Date();
        const pad = n => String(n).padStart(2, '0');
        if (clockDate) clockDate.textContent =
            `${DAYS[now.getDay()]}, ${now.getDate()} ${MONTHS[now.getMonth()]} ${now.getFullYear()}`;
        if (clockTime) clockTime.textContent =
            `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())} WIB`;
    }
    updateClock();
    setInterval(updateClock, 1000);

    /* ── Dropdown profil admin ── */
    const profileBtn  = document.getElementById('profile-menu-btn');
    const profileMenu = document.getElementById('profile-dropdown');
    profileBtn?.addEventListener('click', (e) => {
        e.stopPropagation();
        const isOpen = !profileMenu.classList.contains('hidden');
        profileMenu.classList.toggle('hidden', isOpen);
        profileBtn.setAttribute('aria-expanded', String(!isOpen));
    });
    document.addEventListener('click', (e) => {
        if (profileMenu && !profileMenu.contains(e.target)) {
            profileMenu.classList.add('hidden');
            profileBtn?.setAttribute('aria-expanded', 'false');
        }
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            profileMenu?.classList.add('hidden');
            profileBtn?.setAttribute('aria-expanded', 'false');
        }
    });
</script>

<!-- ── Global Toast (semua halaman admin) ─────────────────────────────────── -->
<div id="admin-toast-wrap" class="fixed top-4 right-4 z-[150] flex flex-col gap-2 items-end pointer-events-none"></div>
<style>
    .admin-toast {
        pointer-events: auto;
        display: flex;
        align-items: flex-start;
        gap: .75rem;
        min-width: 300px;
        max-width: 400px;
        padding: 1rem 1rem .75rem;
        border-radius: 1rem;
        background: var(--color-surface);
        border: 1px solid var(--color-line);
        box-shadow: 0 16px 48px rgba(0, 0, 0, .5);
        transform: translateX(120%);
        opacity: 0;
        transition: transform .3s cubic-bezier(.2, .8, .3, 1), opacity .3s ease;
    }
    .admin-toast.show { transform: translateX(0); opacity: 1; }
    .admin-toast.success { border-color: rgba(242, 191, 93, .35); }
    .admin-toast.error { border-color: rgba(255, 180, 171, .4); }
    .admin-toast .at-icon {
        width: 2.25rem; height: 2.25rem; border-radius: .75rem;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .admin-toast.success .at-icon { background: rgba(242, 191, 93, .14); color: var(--color-primary); }
    .admin-toast.error .at-icon { background: rgba(255, 180, 171, .12); color: var(--color-error); }
    .admin-toast .at-msg { flex: 1; min-width: 0; word-break: break-word; }
    .admin-toast .at-close {
        flex-shrink: 0; width: 1.75rem; height: 1.75rem;
        display: flex; align-items: center; justify-content: center;
        border-radius: .5rem; color: var(--color-ink-dim);
        transition: color .15s, background-color .15s;
    }
    .admin-toast .at-close:hover { color: var(--color-ink); background: var(--color-surface-2); }
    .admin-toast .at-bar {
        height: 3px; margin-top: .75rem; border-radius: 9999px;
        background: rgba(242, 191, 93, .55);
        transform-origin: left;
        animation: at-bar 2s linear forwards;
    }
    .admin-toast.error .at-bar { background: rgba(255, 180, 171, .65); }
    @keyframes at-bar { from { transform: scaleX(1); } to { transform: scaleX(0); } }
</style>
<script>
    function showAdminToast(message, ok = true) {
        const wrap = document.getElementById('admin-toast-wrap');
        if (!wrap) return;
        const toast = document.createElement('div');
        toast.className = 'admin-toast ' + (ok ? 'success' : 'error');
        toast.setAttribute('role', 'status');
        toast.innerHTML =
            '<span class="at-icon"><span class="material-symbols-outlined">' + (ok ? 'check_circle' : 'error') + '</span></span>'
            + '<div class="at-msg"><p class="font-body-md text-sm text-ink leading-snug"></p><div class="at-bar"></div></div>'
            + '<button type="button" class="at-close" aria-label="Tutup notifikasi"><span class="material-symbols-outlined text-[18px]">close</span></button>';
        toast.querySelector('p').textContent = String(message ?? '');
        wrap.appendChild(toast);
        requestAnimationFrame(() => toast.classList.add('show'));
        const close = () => {
            if (toast._closing) return;
            toast._closing = true;
            clearTimeout(toast._t);
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        };
        toast.querySelector('.at-close').addEventListener('click', close);
        toast._t = setTimeout(close, 2000);
    }
    window.showAdminToast = showAdminToast;
</script>
</body>
</html>
