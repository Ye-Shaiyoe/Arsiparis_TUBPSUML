<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - {{ config('app.name', 'Surat Metrologi') }}</title>

    <link rel="icon" href="{{ asset('images/metrologi.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:300,400,500,600,700|playfair-display:700,900i&display=swap" rel="stylesheet" />
    {{-- Pakai Sora (tebal+geometris) + Lora (serif elegan) --}}
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=Lora:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --navy:   #0b1d3a;
            --ink:    #1a2c4e;
            --steel:  #3b5278;
            --sky:    #4f7ec4;
            --accent: #e8b84b;   /* gold accent — authoritative, gov vibes */
            --mist:   #f0f4fa;
            --white:  #ffffff;
            --code-size: clamp(7rem, 22vw, 14rem);
        }

        html, body {
            height: 100%;
            background: var(--mist);
            font-family: 'Sora', sans-serif;
            color: var(--navy);
            overflow: hidden;
        }

        /* ── GRID LAYOUT ──────────────────────────────── */
        .page {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 100vh;
        }

        /* ── LEFT PANEL ───────────────────────────────── */
        .panel-left {
            position: relative;
            background: var(--navy);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 3rem 3rem 2.5rem;
            overflow: hidden;
        }

        /* Subtle dot-grid texture */
        .panel-left::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle, rgba(255,255,255,0.07) 1px, transparent 1px);
            background-size: 28px 28px;
            pointer-events: none;
        }

        /* Gold stripe accent */
        .panel-left::after {
            content: '';
            position: absolute;
            top: 0; right: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, var(--accent) 0%, transparent 100%);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: .9rem;
            animation: fadeUp .6s ease both;
        }

        .brand-logo-wrap {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            background: rgba(255,255,255,0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            padding: 6px;
        }
        .brand-logo-wrap img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            /* filter: brightness(0) invert(1); */
        }

        .brand-name {
            font-size: .78rem;
            font-weight: 600;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: rgba(255,255,255,.55);
            line-height: 1.3;
        }

        .big-code {
            font-family: 'Sora', sans-serif;
            font-size: var(--code-size);
            font-weight: 800;
            line-height: .9;
            color: transparent;
            -webkit-text-stroke: 2px rgba(255,255,255,.12);
            user-select: none;
            position: absolute;
            bottom: -1.5rem;
            left: -0.5rem;
            animation: fadeIn 1s ease .2s both;
        }

        .left-footer {
            position: relative;
            z-index: 1;
            font-size: .72rem;
            color: rgba(255,255,255,.3);
            letter-spacing: .06em;
        }

        /* ── RIGHT PANEL ──────────────────────────────── */
        .panel-right {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 4rem 4rem 4rem 5rem;
            position: relative;
        }

        /* Subtle circle ornament */
        .panel-right::before {
            content: '';
            position: absolute;
            top: -6rem; right: -6rem;
            width: 22rem; height: 22rem;
            border-radius: 50%;
            border: 60px solid var(--accent);
            opacity: .06;
            pointer-events: none;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: var(--sky);
            margin-bottom: 1.4rem;
            animation: fadeUp .5s ease .1s both;
        }

        .eyebrow::before {
            content: '';
            display: block;
            width: 2rem;
            height: 2px;
            background: var(--accent);
        }

        .error-title {
            font-family: 'Lora', serif;
            font-size: clamp(1.9rem, 4vw, 2.8rem);
            font-weight: 700;
            line-height: 1.2;
            color: var(--navy);
            margin-bottom: 1.2rem;
            animation: fadeUp .6s ease .2s both;
        }

        .error-desc {
            font-size: .93rem;
            font-weight: 400;
            line-height: 1.75;
            color: var(--steel);
            max-width: 34ch;
            margin-bottom: 3rem;
            animation: fadeUp .6s ease .3s both;
        }

        /* ── BUTTONS ───────────────────────────────────── */
        .btn-group {
            display: flex;
            flex-wrap: wrap;
            gap: .9rem;
            animation: fadeUp .6s ease .4s both;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .75rem 1.5rem;
            border-radius: .6rem;
            font-size: .82rem;
            font-weight: 600;
            letter-spacing: .04em;
            text-decoration: none;
            transition: all .2s ease;
            cursor: pointer;
            border: none;
        }

        .btn svg { width: 16px; height: 16px; flex-shrink: 0; }

        .btn-primary {
            background: var(--navy);
            color: #fff;
            box-shadow: 0 4px 18px rgba(11,29,58,.18);
        }
        .btn-primary:hover {
            background: var(--ink);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(11,29,58,.26);
        }

        .btn-ghost {
            background: transparent;
            color: var(--steel);
            border: 1.5px solid rgba(59,82,120,.25);
        }
        .btn-ghost:hover {
            border-color: var(--sky);
            color: var(--sky);
            background: rgba(79,126,196,.06);
        }

        /* ── DIVIDER ───────────────────────────────────── */
        .divider {
            width: 3rem;
            height: 3px;
            background: var(--accent);
            border-radius: 2px;
            margin-bottom: 1.8rem;
            animation: scaleX .5s ease .15s both;
            transform-origin: left;
        }

        /* ── FLOATING SHAPES (right panel decoration) ─── */
        .shape {
            position: absolute;
            pointer-events: none;
        }
        .shape-ring {
            bottom: 3rem; right: 2rem;
            width: 5rem; height: 5rem;
            border-radius: 50%;
            border: 1.5px solid var(--accent);
            opacity: .18;
            animation: spin 20s linear infinite;
        }
        .shape-dot {
            bottom: 5.5rem; right: 4.5rem;
            width: .5rem; height: .5rem;
            border-radius: 50%;
            background: var(--accent);
            opacity: .4;
        }
        .shape-sq {
            top: 3rem; right: 3rem;
            width: 1.4rem; height: 1.4rem;
            border: 1.5px solid var(--sky);
            opacity: .2;
            transform: rotate(20deg);
            animation: floatY 4s ease-in-out infinite;
        }

        /* ── ANIMATIONS ────────────────────────────────── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }
        @keyframes scaleX {
            from { transform: scaleX(0); }
            to   { transform: scaleX(1); }
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        @keyframes floatY {
            0%, 100% { transform: rotate(20deg) translateY(0); }
            50%       { transform: rotate(20deg) translateY(-8px); }
        }

        /* ── RESPONSIVE ────────────────────────────────── */
        @media (max-width: 768px) {
            html, body { overflow: auto; }
            .page {
                grid-template-columns: 1fr;
                min-height: 100vh;
            }
            .panel-left {
                padding: 2rem 2rem 6rem;
                min-height: 45vw;
            }
            .panel-right {
                padding: 3rem 2rem;
            }
            .panel-right::before { display: none; }
        }
    </style>
</head>
<body>
<div class="page">

    {{-- LEFT: navy panel with ghost code number --}}
    <div class="panel-left">
        <div class="brand">
            <div class="brand-logo-wrap">
                <img src="{{ asset('images/metrologi.png') }}" alt="Logo Metrologi">
            </div>
            <div class="brand-name">Balai Metrologi<br>Legal</div>
        </div>

        <div class="big-code">@yield('code')</div>

        <div class="left-footer">
            &copy; {{ date('Y') }} {{ config('app.name', 'Surat Metrologi') }}
        </div>
    </div>

    {{-- RIGHT: content panel --}}
    <div class="panel-right">

        {{-- decorative shapes --}}
        <div class="shape shape-ring"></div>
        <div class="shape shape-dot"></div>
        <div class="shape shape-sq"></div>

        <div class="eyebrow">Error @yield('code')</div>

        <div class="divider"></div>

        <h1 class="error-title">@yield('message')</h1>

        <p class="error-desc">@yield('description')</p>

        <div class="btn-group">
            <a href="{{ url('/') }}" class="btn btn-primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Ke Beranda
            </a>
            <a href="{{ url()->previous() }}" class="btn btn-ghost">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </div>

</div>
</body>
</html>