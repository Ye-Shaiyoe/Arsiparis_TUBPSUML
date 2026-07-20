<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi — Sistem Surat Metrologi</title>
    <link rel="icon" href="{{ asset('images/metrologi.png') }}">

    {{-- reCAPTCHA v3 — invisible, auto-execute on page load --}}
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --navy:        #0a2540;
            --navy-mid:    #103262;
            --blue:        #1a56db;
            --blue-light:  #4f86f7;
            --accent:      #38bdf8;
            --gold:        #f5c842;
            --surface:     rgba(255,255,255,0.06);
            --surface-hov: rgba(255,255,255,0.11);
            --border:      rgba(255,255,255,0.13);
            --border-str:  rgba(255,255,255,0.28);
            --text:        #ffffff;
            --muted:       rgba(255,255,255,0.58);
            --faint:       rgba(255,255,255,0.30);
            --input-bg:    rgba(255,255,255,0.07);
            --input-focus: rgba(255,255,255,0.13);
            --error:       #fda4af;
            --shadow-card: 0 24px 64px rgba(0,0,0,0.45), 0 4px 16px rgba(0,0,0,0.28);
        }
        .grecaptcha-badge { visibility: hidden !important; }

        body {
            min-height: 100vh;
            font-family: 'DM Sans', sans-serif;
            background: var(--navy);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        .bg-layer { position: fixed; inset: 0; z-index: 0; overflow: hidden; }
        .bg-gradient {
            position: absolute; inset: 0;
            background:
                radial-gradient(ellipse 120% 80% at 20% 0%, #183e8f 0%, transparent 60%),
                radial-gradient(ellipse 80% 60% at 80% 100%, #0c4a6e 0%, transparent 55%),
                linear-gradient(160deg, #0a2540 0%, #071a30 60%, #030e1c 100%);
        }
        .bg-grid {
            position: absolute; inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
            background-size: 48px 48px;
        }
        .orb { position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.22; animation: drift 14s ease-in-out infinite alternate; }
        .orb-1 { width: 600px; height: 600px; background: #1d4ed8; top: -200px; left: -150px; animation-delay: 0s; }
        .orb-2 { width: 400px; height: 400px; background: #0ea5e9; bottom: -100px; right: -100px; animation-delay: -6s; }
        .orb-3 { width: 280px; height: 280px; background: #7c3aed; top: 30%; right: 20%; animation-delay: -9s; opacity: 0.14; }
        @keyframes drift {
            from { transform: translate(0,0) scale(1); }
            to   { transform: translate(40px,25px) scale(1.08); }
        }
        .bg-streak {
            position: absolute; width: 2px; height: 60%;
            background: linear-gradient(to bottom, transparent, rgba(99,179,237,0.18), transparent);
            top: 10%; left: 38%; transform: rotate(15deg); pointer-events: none;
        }

        .scene {
            position: relative; z-index: 10;
            width: min(96vw, 900px);
            display: flex; border-radius: 28px; overflow: hidden;
            box-shadow: var(--shadow-card), 0 0 0 1px var(--border);
            backdrop-filter: blur(2px);
        }

        .panel-left {
            flex: 0 0 38%;
            background: linear-gradient(160deg, rgba(26,86,219,0.35) 0%, rgba(10,37,64,0.6) 100%);
            border-right: 1px solid var(--border);
            backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            padding: 48px 32px; gap: 24px; position: relative; overflow: hidden;
        }
        .panel-left::before {
            content: ''; position: absolute; width: 320px; height: 320px;
            border-radius: 50%; border: 1px solid rgba(255,255,255,0.06);
            bottom: -100px; right: -100px; pointer-events: none;
        }
        .panel-left::after {
            content: ''; position: absolute; width: 200px; height: 200px;
            border-radius: 50%; border: 1px solid rgba(255,255,255,0.08);
            top: -60px; left: -60px; pointer-events: none;
        }
        .logo-outer {
            width: 92px; height: 92px; border-radius: 50%;
            border: 1px solid var(--border-str); background: rgba(255,255,255,0.1);
            backdrop-filter: blur(12px);
            display: flex; align-items: center; justify-content: center;
            position: relative;
            box-shadow: 0 0 0 8px rgba(255,255,255,0.04), inset 0 1px 0 rgba(255,255,255,0.2);
        }
        .logo-inner {
            width: 58px; height: 58px; border-radius: 14px;
            background: rgba(255,255,255,0.08);
            display: flex; align-items: center; justify-content: center; overflow: hidden;
        }
        .logo-inner img { width: 100%; height: 100%; object-fit: contain; }
        .logo-outer::before {
            content: ''; position: absolute; inset: -8px; border-radius: 50%;
            border: 1px solid rgba(99,179,237,0.25);
            animation: pulse-ring 3s ease-out infinite;
        }
        @keyframes pulse-ring {
            0%   { transform: scale(1); opacity: 0.5; }
            70%  { transform: scale(1.12); opacity: 0; }
            100% { opacity: 0; }
        }
        .brand-title { font-family: 'Sora', sans-serif; color: white; font-size: 15px; font-weight: 700; text-align: center; line-height: 1.5; letter-spacing: -0.01em; }
        .divider-line { width: 40px; height: 1px; background: linear-gradient(90deg, transparent, var(--border-str), transparent); }
        .brand-sub { color: var(--muted); font-size: 12px; text-align: center; line-height: 1.7; }
        .info-badge {
            background: rgba(255,255,255,0.07); border: 1px solid var(--border);
            border-radius: 12px; padding: 10px 18px; text-align: center; transition: background .2s;
        }
        .info-badge:hover { background: var(--surface-hov); }
        .badge-label { color: var(--faint); font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; }
        .badge-val { color: white; font-size: 14px; font-weight: 700; font-family: 'Sora', sans-serif; margin-top: 2px; }
        .status-pill {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(56,189,248,0.12); border: 1px solid rgba(56,189,248,0.25);
            border-radius: 999px; padding: 5px 12px; font-size: 11px; color: #7dd3fc;
        }
        .status-dot {
            width: 7px; height: 7px; border-radius: 50%; background: #38bdf8;
            box-shadow: 0 0 6px #38bdf8; animation: blink 2s ease-in-out infinite;
        }
        @keyframes blink { 0%,100% { opacity: 1; } 50% { opacity: 0.4; } }

        .panel-right {
            flex: 1; background: rgba(255,255,255,0.055);
            backdrop-filter: blur(36px); -webkit-backdrop-filter: blur(36px);
            display: flex; flex-direction: column; justify-content: center;
            padding: 48px 44px; position: relative; overflow: hidden;
        }
        .panel-right::before {
            content: ''; position: absolute; top: 0; left: 44px; right: 44px; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
        }
        .greeting-chip {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(245,200,66,0.12); border: 1px solid rgba(245,200,66,0.22);
            border-radius: 999px; padding: 4px 12px; font-size: 11px; color: #fde68a;
            margin-bottom: 14px; width: fit-content;
        }
        .form-heading { font-family: 'Sora', sans-serif; color: white; font-size: 26px; font-weight: 800; letter-spacing: -0.03em; line-height: 1.2; margin-bottom: 4px; }
        .form-heading span { background: linear-gradient(90deg, #7dd3fc, #818cf8); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .form-sub { color: var(--muted); font-size: 13px; margin-bottom: 28px; line-height: 1.6; }

        .alert-glass {
            background: rgba(253,100,116,0.12); border: 1px solid rgba(253,100,116,0.28);
            border-radius: 10px; color: #fca5a5; font-size: 12.5px;
            padding: 10px 14px; margin-bottom: 20px; display: flex; gap: 8px; align-items: flex-start;
        }
        .success-glass {
            background: rgba(52,211,153,0.12); border: 1px solid rgba(52,211,153,0.28);
            border-radius: 10px; color: #6ee7b7; font-size: 12.5px;
            padding: 10px 14px; margin-bottom: 20px; display: flex; gap: 8px; align-items: flex-start;
        }

        .field-group { margin-bottom: 18px; }
        .field-label { color: rgba(255,255,255,0.7); font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.09em; margin-bottom: 6px; display: block; }
        .input-wrap { position: relative; }
        .input-icon { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); color: var(--faint); font-size: 15px; pointer-events: none; transition: color .2s; }
        .field-input {
            width: 100%; padding: 11px 14px 11px 38px;
            background: var(--input-bg); border: 1px solid var(--border); border-radius: 11px;
            color: white; font-size: 13.5px; font-family: 'DM Sans', sans-serif;
            outline: none; transition: border-color .2s, background .2s, box-shadow .2s;
        }
        .field-input::placeholder { color: var(--faint); }
        .field-input:focus { border-color: rgba(99,179,237,0.55); background: var(--input-focus); box-shadow: 0 0 0 3px rgba(56,189,248,0.1); }
        .input-wrap:focus-within .input-icon { color: #7dd3fc; }
        .error-text { color: var(--error); font-size: 11px; margin-top: 4px; display: flex; align-items: center; gap: 4px; }

        .btn-submit {
            width: 100%; padding: 13px;
            background: linear-gradient(135deg, #1a56db 0%, #3b82f6 100%);
            color: white; border: none; border-radius: 12px;
            font-size: 14px; font-weight: 700; font-family: 'DM Sans', sans-serif;
            letter-spacing: 0.01em; cursor: pointer; position: relative; overflow: hidden;
            transition: transform .12s, box-shadow .18s;
            box-shadow: 0 4px 20px rgba(26,86,219,0.4), 0 1px 4px rgba(0,0,0,0.3);
        }
        .btn-submit::before { content: ''; position: absolute; inset: 0; background: linear-gradient(135deg, rgba(255,255,255,0.18) 0%, transparent 50%); pointer-events: none; }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(26,86,219,0.5), 0 2px 8px rgba(0,0,0,0.3); }
        .btn-submit:active { transform: translateY(0); }
        .btn-submit .arrow { display: inline-block; transition: transform .2s; margin-left: 6px; }
        .btn-submit:hover .arrow { transform: translateX(4px); }

        .register-row { display: flex; align-items: center; justify-content: center; gap: 6px; margin-top: 20px; }
        .register-row span { color: var(--muted); font-size: 12.5px; }
        .register-link { color: #7dd3fc; font-size: 12.5px; font-weight: 600; text-decoration: none; transition: color .15s; }
        .register-link:hover { color: #bae6fd; }

        .footer-bar {
            position: absolute; bottom: 0; left: 0; right: 0; height: 34px;
            background: rgba(0,0,0,0.15); border-top: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center;
            pointer-events: none; z-index: 2;
        }
        .footer-bar span { color: rgba(255,255,255,0.3); font-size: 10.5px; }

        @media (max-width: 640px) {
            body { overflow-y: auto; align-items: flex-start; padding: 24px 0; }
            .scene { flex-direction: column; border-radius: 20px; width: 92vw; }
            .panel-left { flex: 0 0 auto; border-right: none; border-bottom: 1px solid var(--border); flex-direction: row; padding: 20px 24px; gap: 14px; justify-content: flex-start; }
            .brand-title { font-size: 12px; text-align: left; }
            .brand-sub, .divider-line, .info-badge, .status-pill { display: none; }
            .panel-right { padding: 28px 24px 50px; }
            .form-heading { font-size: 22px; }
        }
    </style>
</head>
<body>

    <div class="bg-layer">
        <div class="bg-gradient"></div>
        <div class="bg-grid"></div>
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
        <div class="bg-streak"></div>
    </div>

    <div class="scene">

        <!-- Left: Branding -->
        <div class="panel-left">
            <div class="logo-outer">
                <div class="logo-inner">
                    <img src="{{ asset('images/metrologi.png') }}" alt="Logo Dinas">
                </div>
            </div>
            <div class="brand-title">Balai Pengelolaan SUML</div>
            <div class="divider-line"></div>
            <div class="brand-sub">Sistem Informasi<br>Monitoring Surat<br>Balai Pengelolaan SUML</div>
            <div class="divider-line"></div>
            <div class="info-badge">
                <div class="badge-label">Tahun Anggaran</div>
                <div class="badge-val">{{ date('Y') }}</div>
            </div>
            <div class="status-pill">
                <div class="status-dot"></div>
                Sistem aktif
            </div>
        </div>

        <!-- Right: Forgot Password Form -->
        <div class="panel-right">

            <div class="greeting-chip">
                <i class="bi bi-key" style="font-size:11px;"></i>
                Pemulihan akses akun
            </div>

            <div class="form-heading">
                Lupa <span>Kata Sandi?</span>
            </div>
            <div class="form-sub">
                Tidak perlu khawatir. Masukkan alamat email Anda dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi.
            </div>

            @if (session('status'))
            <div class="success-glass">
                <i class="bi bi-check-circle-fill" style="flex-shrink:0; margin-top:1px;"></i>
                <div>{{ session('status') }}</div>
            </div>
            @endif

            @if ($errors->any())
            <div class="alert-glass">
                <i class="bi bi-exclamation-triangle-fill" style="flex-shrink:0; margin-top:1px;"></i>
                <div>
                    @foreach ($errors->all() as $err)
                        <div>{{ $err }}</div>
                    @endforeach
                </div>
            </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" id="forgotPasswordForm">
                @csrf
                <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">

                <div class="field-group">
                    <label class="field-label" for="email">Alamat Email</label>
                    <div class="input-wrap">
                        <input
                            class="field-input"
                            id="email" type="email" name="email"
                            value="{{ old('email') }}"
                            placeholder="nama@instansi.go.id"
                            required autofocus autocomplete="email">
                        <i class="bi bi-envelope input-icon"></i>
                    </div>
                    @error('email')
                        <p class="error-text"><i class="bi bi-x-circle-fill" style="font-size:10px;"></i> {{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn-submit">
                    Kirim Tautan Reset
                    <span class="arrow">→</span>
                </button>
            </form>

            <div class="register-row">
                <span>Ingat kata sandi Anda?</span>
                <a href="{{ route('login') }}" class="register-link">Kembali ke Login</a>
            </div>

        </div>

        <!-- Footer bar -->
        <div class="footer-bar">
            <span>&copy; {{ date('Y') }} Balai Pengelolaan SUML &mdash; Hak cipta dilindungi undang-undang</span>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ── reCAPTCHA v3 — invisible auto-execute ──
        const RECAPTCHA_SITE_KEY = '{{ config('services.recaptcha.site_key') }}';
        let recaptchaToken = null;
        let recaptchaRefreshTimer = null;

        function refreshRecaptchaToken() {
            if (typeof grecaptcha === 'undefined') return;
            grecaptcha.ready(function () {
                grecaptcha.execute(RECAPTCHA_SITE_KEY, { action: 'forgot_password' })
                    .then(function (token) {
                        recaptchaToken = token;
                        const input = document.getElementById('g-recaptcha-response');
                        if (input) input.value = token;
                    })
                    .catch(function (err) {
                        console.warn('reCAPTCHA execute failed:', err);
                    });
            });
        }

        window.addEventListener('load', function () {
            if (typeof grecaptcha !== 'undefined') {
                refreshRecaptchaToken();
            } else {
                let retries = 0;
                const interval = setInterval(function () {
                    if (typeof grecaptcha !== 'undefined') {
                        clearInterval(interval);
                        refreshRecaptchaToken();
                        recaptchaRefreshTimer = setInterval(refreshRecaptchaToken, 90000);
                    } else if (++retries > 10) {
                        clearInterval(interval);
                    }
                }, 500);
            }
            recaptchaRefreshTimer = setInterval(refreshRecaptchaToken, 90000);
        });

        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('forgotPasswordForm');
            const submitBtn = document.querySelector('.btn-submit');
            if (form && submitBtn) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    submitBtn.disabled = true;
                    submitBtn.style.opacity = '0.75';
                    submitBtn.style.cursor = 'not-allowed';
                    submitBtn.innerHTML = `
                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true" 
                              style="width: 14px; height: 14px; border-width: 2px; vertical-align: middle; margin-top: -2px;"></span>
                        Mengirim Tautan...
                    `;

                    if (typeof grecaptcha !== 'undefined') {
                        grecaptcha.ready(function () {
                            grecaptcha.execute(RECAPTCHA_SITE_KEY, { action: 'forgot_password' })
                                .then(function (token) {
                                    document.getElementById('g-recaptcha-response').value = token;
                                    form.submit();
                                })
                                .catch(function () {
                                    form.submit();
                                });
                        });
                    } else {
                        form.submit();
                    }
                });
            }
        });
    </script>
</body>
</html>
