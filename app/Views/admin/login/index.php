<?php
/**
 * Login view — standalone, tidak pakai admin layout.
 * Variabel dari AuthController::login():
 *   $error  string  — pesan error (kosong jika tidak ada)
 */
$error = $error ?? '';
$base  = defined('APP_BASE') ? APP_BASE : '';
?>
<!doctype html>
<html class="dark" lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | Pekon Air Naningan</title>
    <meta name="robots" content="noindex, nofollow">
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:ital,opsz,wght@0,6..72,400..800;1,6..72,400..800&family=Public+Sans:ital,wght@0,100..900;1,100..900&family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style type="text/tailwindcss">
        @theme {
            --color-bg: #12201A;
            --color-surface: #1B2B22;
            --color-surface-2: #24382C;
            --color-surface-container: #231f18;
            --color-surface-container-high: #2e2922;
            --color-ink: #F3ECDA;
            --color-ink-dim: #B9C4B4;
            --color-primary: #f2bf5d;
            --color-primary-fixed: #ffdea7;
            --color-on-primary: #412d00;
            --color-gold-soft: #E4C374;
            --color-line: rgba(243,236,218,0.12);
            --color-line-strong: rgba(243,236,218,0.22);
            --color-error: #ffb4ab;
            --color-danger: #C1653A;
            --font-family-h2: Newsreader, serif;
            --font-family-body-md: "Public Sans", sans-serif;
            --font-family-label-mono: "JetBrains Mono", monospace;
        }
        body { background-color: var(--color-bg); color: var(--color-ink); }
    </style>
</head>
<body class="bg-bg font-body-md text-ink min-h-screen flex items-center justify-center relative overflow-hidden group">

    <!-- Ambient background -->
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-1/4 -right-1/4 w-[800px] h-[800px] bg-primary/5 rounded-full blur-[120px] mix-blend-screen opacity-50"></div>
        <div class="absolute -bottom-1/4 -left-1/4 w-[600px] h-[600px] bg-surface-2/30 rounded-full blur-[100px] mix-blend-screen opacity-40"></div>
    </div>

    <!-- Decorative corner lines -->
    <div class="absolute top-8 left-8 w-16 h-16 border-t border-l border-line-strong/20 rounded-tl-3xl pointer-events-none hidden md:block"></div>
    <div class="absolute bottom-8 right-8 w-16 h-16 border-b border-r border-line-strong/20 rounded-br-3xl pointer-events-none hidden md:block"></div>

    <!-- Login Card -->
    <div class="relative w-full max-w-[440px] bg-surface-container rounded-2xl shadow-xl shadow-black/40 p-8 md:p-12 flex flex-col gap-8 z-10 border border-line-strong/30 backdrop-blur-sm mx-4 transition-transform duration-700 hover:scale-[1.005]">

        <!-- Logo & Title -->
        <div class="flex flex-col items-center gap-6 text-center">
            <div class="w-24 h-24 rounded-2xl bg-surface-2 flex items-center justify-center border border-line p-4 shadow-inner relative overflow-hidden group-hover:border-primary/30 transition-colors duration-500">
                <svg class="w-full h-full text-primary" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z" fill="currentColor" opacity="0.2"/>
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 6c-3.31 0-6 2.69-6 6s2.69 6 6 6 6-2.69 6-6-2.69-6-6-6zm0 10c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4z" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 6c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div class="flex flex-col gap-2">
                <h1 class="font-h2 text-h2 text-ink">Pekon Air Naningan</h1>
                <p class="font-label-mono text-label-mono text-gold-soft uppercase tracking-[0.2em]">Sistem Administrasi</p>
            </div>
        </div>

        <!-- Error Alert -->
        <?php if ($error !== ''): ?>
            <div class="flex items-start gap-3 px-4 py-3 bg-danger/10 border border-danger/30 rounded-xl text-error"
                 role="alert" id="login-error">
                <span class="material-symbols-outlined text-danger text-[20px] shrink-0 mt-0.5">error</span>
                <p class="font-body-md text-[14px] text-ink"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <form class="flex flex-col gap-5 w-full" method="POST"
              action="<?= htmlspecialchars($base . '/admin/login', ENT_QUOTES, 'UTF-8') ?>"
              id="login-form" novalidate>

            <!-- Username -->
            <div class="flex flex-col gap-1.5 group/input">
                <label class="font-body-md text-[13px] text-ink-dim transition-colors group-focus-within/input:text-primary"
                       for="username">Username</label>
                <div class="relative flex items-center">
                    <span class="material-symbols-outlined absolute left-4 text-ink-dim/50 group-focus-within/input:text-primary transition-colors text-[20px]">person</span>
                    <input class="w-full bg-surface border border-line rounded-lg py-3.5 pl-11 pr-4 font-body-md text-ink text-[15px] placeholder:text-ink-dim/30 focus:outline-none focus:border-primary/50 focus:bg-surface-2 transition-all"
                           id="username" name="username" type="text"
                           placeholder="admin"
                           autocomplete="username"
                           value="<?= htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                           required>
                </div>
            </div>

            <!-- Password -->
            <div class="flex flex-col gap-1.5 group/input">
                <div class="flex items-center justify-between">
                    <label class="font-body-md text-[13px] text-ink-dim transition-colors group-focus-within/input:text-primary"
                           for="password">Kata Sandi</label>
                </div>
                <div class="relative flex items-center">
                    <span class="material-symbols-outlined absolute left-4 text-ink-dim/50 group-focus-within/input:text-primary transition-colors text-[20px]">lock</span>
                    <input class="w-full bg-surface border border-line rounded-lg py-3.5 pl-11 pr-12 font-body-md text-ink text-[15px] placeholder:text-ink-dim/30 focus:outline-none focus:border-primary/50 focus:bg-surface-2 transition-all"
                           id="password" name="password" type="password"
                           placeholder="••••••••"
                           autocomplete="current-password"
                           required>
                    <button class="absolute right-4 text-ink-dim/50 hover:text-ink transition-colors focus:outline-none"
                            type="button" id="toggle-pw" aria-label="Tampilkan/sembunyikan kata sandi">
                        <span class="material-symbols-outlined text-[20px]" id="toggle-pw-icon">visibility</span>
                    </button>
                </div>
            </div>

            <!-- Submit -->
            <button class="mt-4 w-full bg-primary hover:bg-primary-fixed text-on-primary font-body-md font-semibold py-4 rounded-full flex items-center justify-center gap-2 transition-all shadow-lg shadow-primary/10 hover:shadow-primary/20 active:scale-[0.98] disabled:opacity-60 disabled:cursor-not-allowed"
                    type="submit" id="submit-btn">
                <span id="btn-text">Masuk ke Dashboard</span>
                <span class="material-symbols-outlined text-[20px]" id="btn-icon">arrow_forward</span>
            </button>
        </form>

        <!-- Footer note -->
        <div class="text-center">
            <p class="font-body-md text-[12px] text-ink-dim/60">
                Gunakan kredensial yang diberikan oleh Kepala Desa.<br>
                Akses sistem dilindungi.
            </p>
        </div>

        <!-- Back to public site -->
        <a class="flex items-center justify-center gap-2 text-ink-dim/50 hover:text-ink-dim font-label-mono text-label-mono transition-colors text-center"
           href="<?= htmlspecialchars($base ?: '/', ENT_QUOTES, 'UTF-8') ?>">
            <span class="material-symbols-outlined text-[16px]">arrow_back</span>
            Kembali ke situs
        </a>
    </div>

    <script>
        // Toggle password visibility
        document.getElementById('toggle-pw').addEventListener('click', function () {
            const pw   = document.getElementById('password');
            const icon = document.getElementById('toggle-pw-icon');
            if (pw.type === 'password') {
                pw.type = 'text';
                icon.textContent = 'visibility_off';
            } else {
                pw.type = 'password';
                icon.textContent = 'visibility';
            }
        });

        // Loading state saat submit
        document.getElementById('login-form').addEventListener('submit', function () {
            const btn  = document.getElementById('submit-btn');
            const text = document.getElementById('btn-text');
            const icon = document.getElementById('btn-icon');
            btn.disabled = true;
            text.textContent = 'Memverifikasi...';
            icon.textContent = 'progress_activity';
            icon.classList.add('animate-spin');
        });
    </script>
</body>
</html>
