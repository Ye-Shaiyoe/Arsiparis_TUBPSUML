<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BPSUML — Balai Pengelolaan Standar Ukuran & Metrologi Legal</title>
    <link rel="icon" href="{{ asset('images/metrologi.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500&family=DM+Mono:wght@300;400&display=swap"
        rel="stylesheet">

    {{-- GSAP + ScrollTrigger --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    {{-- Anime.js --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    {{-- Chart.js --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
    {{-- Lenis Smooth Scroll --}}
    <script src="https://unpkg.com/lenis@1.1.20/dist/lenis.min.js"></script>

    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --glass-bg: rgba(255, 255, 255, 0.06);
            --glass-border: rgba(255, 255, 255, 0.11);
            --glass-blur: blur(20px);
            --accent: #C8A96E;
            --accent-rgb: 200, 169, 110;
            --accent-dim: rgba(200, 169, 110, 0.15);
            --white: #F4F1EC;
            --muted: rgba(244, 241, 236, 0.50);
            --muted2: rgba(244, 241, 236, 0.22);
            --deep: #070B13;
            --navy: #0D1526;
            --radius: 16px;
            --font-display: 'Cormorant Garamond', serif;
            --font-body: 'DM Sans', sans-serif;
            --font-mono: 'DM Mono', monospace;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: var(--font-body);
            background: var(--deep);
            color: var(--white);
            overflow-x: hidden;
            cursor: none;
        }

        /* ── Custom Cursor ── */
        #cursor {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 9999;
            pointer-events: none;
            width: 8px;
            height: 8px;
            background: var(--accent);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            mix-blend-mode: difference;
        }

        #cursor-ring {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 9998;
            pointer-events: none;
            width: 34px;
            height: 34px;
            border: 1px solid rgba(200, 169, 110, 0.45);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width .3s, height .3s, opacity .3s, border-color .3s;
        }

        .cursor-hover #cursor-ring {
            width: 50px;
            height: 50px;
            opacity: 0.5;
            border-color: rgba(200, 169, 110, 0.7);
        }

        .cursor-hover #cursor {
            width: 16px;
            height: 16px;
        }

        /* ── BG ── */
        .bg-mesh {
            position: fixed;
            inset: 0;
            z-index: 0;
            background:
                radial-gradient(ellipse 80% 60% at 18% 8%, rgba(28, 48, 100, 0.55) 0%, transparent 60%),
                radial-gradient(ellipse 60% 55% at 82% 78%, rgba(12, 28, 68, 0.50) 0%, transparent 60%),
                radial-gradient(ellipse 38% 38% at 58% 32%, rgba(200, 169, 110, 0.07) 0%, transparent 58%),
                linear-gradient(160deg, #070B13 0%, #0D1526 52%, #050910 100%);
            pointer-events: none;
        }

        .bg-grid {
            position: fixed;
            inset: 0;
            z-index: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.022) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.022) 1px, transparent 1px);
            background-size: 64px 64px;
            pointer-events: none;
        }

        .bg-orb {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            filter: blur(90px);
            z-index: 0;
        }

        .bg-orb-1 {
            width: 520px;
            height: 520px;
            top: -130px;
            left: -170px;
            background: rgba(25, 55, 130, 0.22);
        }

        .bg-orb-2 {
            width: 380px;
            height: 380px;
            bottom: 8%;
            right: -100px;
            background: rgba(200, 169, 110, 0.07);
        }

        .bg-orb-3 {
            width: 280px;
            height: 280px;
            top: 40%;
            left: 40%;
            background: rgba(15, 35, 90, 0.18);
            animation: orbFloat 8s ease-in-out infinite;
        }

        @keyframes orbFloat {

            0%,
            100% {
                transform: translateY(0)
            }

            50% {
                transform: translateY(-30px)
            }
        }

        /* ── Scroll Progress ── */
        #scroll-bar {
            position: fixed;
            top: 0;
            left: 0;
            height: 2px;
            width: 0%;
            background: linear-gradient(90deg, var(--accent), rgba(200, 169, 110, 0.25));
            z-index: 1001;
            transition: width .08s linear;
        }

        /* ── Particles canvas ── */
        #particles-canvas {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            opacity: 0.35;
        }

        /* ── Navbar ── */
        nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 900;
            padding: 18px 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: background .4s, border-color .4s, padding .3s;
            will-change: background, padding, backdrop-filter;
        }

        nav.scrolled {
            background: linear-gradient(to bottom, 
                rgba(7, 11, 19, 0.8) 0%, 
                rgba(7, 11, 19, 0.4) 60%, 
                transparent 100%) !important;
            backdrop-filter: blur(12px) !important;
            -webkit-backdrop-filter: blur(12px) !important;
            border-bottom: none !important;
            padding: 14px 60px;
            box-shadow: none !important;
        }

        .nav-logo {
            font-family: var(--font-mono);
            font-size: 12px;
            color: var(--accent);
            letter-spacing: 0.14em;
            text-transform: uppercase;
            text-decoration: none;
        }

        .nav-center {
            display: flex;
            gap: 32px;
            list-style: none;
        }

        .nav-center a {
            font-size: 11px;
            font-weight: 400;
            color: var(--muted);
            text-decoration: none;
            letter-spacing: 0.09em;
            text-transform: uppercase;
            transition: color .25s;
            position: relative;
        }

        .nav-center a::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 0;
            height: 1px;
            background: var(--accent);
            transition: width .3s;
        }

        .nav-center a:hover {
            color: var(--white);
        }

        .nav-center a:hover::after {
            width: 100%;
        }

        /* Nav auth buttons */
        .nav-auth {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .nav-btn-login {
            display: inline-flex;
            align-items: center;
            padding: 8px 20px;
            border: 1px solid var(--glass-border);
            background: transparent;
            color: var(--muted);
            font-family: var(--font-body);
            font-size: 11px;
            font-weight: 400;
            letter-spacing: 0.09em;
            text-transform: uppercase;
            text-decoration: none;
            border-radius: 100px;
            cursor: none;
            transition: border-color .25s, color .25s, background .25s;
        }

        .nav-btn-login:hover {
            border-color: rgba(200, 169, 110, 0.5);
            color: var(--white);
            background: rgba(200, 169, 110, 0.07);
        }

        .nav-btn-register {
            display: inline-flex;
            align-items: center;
            padding: 8px 20px;
            background: var(--accent);
            color: var(--deep);
            font-family: var(--font-body);
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.09em;
            text-transform: uppercase;
            text-decoration: none;
            border-radius: 100px;
            cursor: none;
            transition: transform .25s, box-shadow .25s, opacity .25s;
        }

        .nav-btn-register:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 28px rgba(200, 169, 110, 0.28);
        }

        /* ── Section base ── */
        section {
            position: relative;
            z-index: 1;
        }

        /* ── HERO ── */
        #hero {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            padding: 140px 60px 90px;
            overflow: hidden;
        }

        .hero-eyebrow {
            display: flex;
            align-items: center;
            gap: 14px;
            font-family: var(--font-mono);
            font-size: 11px;
            color: var(--accent);
            letter-spacing: 0.22em;
            text-transform: uppercase;
            margin-bottom: 36px;
            opacity: 0;
        }

        .hero-eyebrow-line {
            display: block;
            height: 1px;
            width: 0;
            background: var(--accent);
        }

        .hero-title {
            font-family: var(--font-display);
            font-size: clamp(52px, 8.5vw, 118px);
            font-weight: 300;
            line-height: 1.0;
            letter-spacing: -0.02em;
            margin-bottom: 24px;
            overflow: hidden;
        }

        .hero-title .line {
            display: block;
            opacity: 0;
            transform: translateY(70px);
        }

        .hero-title em {
            font-style: italic;
            color: var(--accent);
        }

        .hero-subtitle {
            max-width: 500px;
            font-size: 15px;
            font-weight: 300;
            line-height: 1.75;
            color: var(--muted);
            margin-bottom: 56px;
            opacity: 0;
        }

        .hero-cta {
            display: flex;
            gap: 14px;
            align-items: center;
            flex-wrap: wrap;
            opacity: 0;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 15px 34px;
            background: var(--accent);
            color: var(--deep);
            font-family: var(--font-body);
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 0.10em;
            text-transform: uppercase;
            text-decoration: none;
            border: none;
            cursor: none;
            border-radius: 100px;
            transition: transform .3s, box-shadow .3s;
            position: relative;
            overflow: hidden;
        }

        .btn-primary::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.12);
            transform: translateX(-105%);
            transition: transform .4s;
        }

        .btn-primary:hover::after {
            transform: translateX(0);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 44px rgba(200, 169, 110, 0.32);
        }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 32px;
            background: transparent;
            color: var(--white);
            font-family: var(--font-body);
            font-size: 12px;
            font-weight: 400;
            letter-spacing: 0.10em;
            text-transform: uppercase;
            text-decoration: none;
            cursor: none;
            border: 1px solid rgba(200, 169, 110, 0.4);
            border-radius: 100px;
            transition: border-color .3s, background .3s, color .3s, transform .3s;
        }

        .btn-secondary:hover {
            border-color: var(--accent);
            background: var(--accent-dim);
            transform: translateY(-2px);
        }

        .btn-ghost {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            font-weight: 400;
            color: var(--muted);
            letter-spacing: 0.08em;
            text-transform: uppercase;
            text-decoration: none;
            cursor: none;
            transition: color .25s;
        }

        .btn-ghost:hover {
            color: var(--white);
        }

        .btn-ghost svg {
            transition: transform .25s;
        }

        .btn-ghost:hover svg {
            transform: translateX(5px);
        }

        .cta-divider {
            width: 1px;
            height: 28px;
            background: var(--glass-border);
        }

        .hero-scroll-hint {
            position: absolute;
            bottom: 44px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            font-family: var(--font-mono);
            font-size: 10px;
            color: var(--muted2);
            letter-spacing: 0.15em;
            text-transform: uppercase;
            opacity: 0;
        }

        .scroll-line {
            width: 1px;
            height: 42px;
            background: linear-gradient(to bottom, var(--accent), transparent);
            animation: scrollLine 2.2s ease-in-out infinite;
        }

        @keyframes scrollLine {
            0% {
                transform: scaleY(0);
                transform-origin: top;
            }

            50% {
                transform: scaleY(1);
                transform-origin: top;
            }

            51% {
                transform: scaleY(1);
                transform-origin: bottom;
            }

            100% {
                transform: scaleY(0);
                transform-origin: bottom;
            }
        }

        .hero-badge {
            position: absolute;
            right: 80px;
            top: 50%;
            transform: translateY(-50%);
            width: 130px;
            height: 130px;
            border-radius: 50%;
            border: 1px solid var(--glass-border);
            background: var(--glass-bg);
            backdrop-filter: var(--glass-blur);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            animation: rotateBadge 20s linear infinite;
            opacity: 0;
        }

        .hero-badge-inner {
            font-family: var(--font-mono);
            font-size: 28px;
            font-weight: 300;
            color: var(--accent);
            line-height: 1;
        }

        .hero-badge-label {
            font-family: var(--font-mono);
            font-size: 9px;
            color: var(--muted);
            letter-spacing: 0.15em;
            text-transform: uppercase;
            text-align: center;
        }

        @keyframes rotateBadge {
            from {
                transform: translateY(-50%) rotate(0deg);
            }

            to {
                transform: translateY(-50%) rotate(360deg);
            }
        }

        .hero-float-cards {
            position: absolute;
            right: 250px;
            top: 50%;
            transform: translateY(-60%);
            display: flex;
            flex-direction: column;
            gap: 12px;
            opacity: 0;
        }

        .hero-float-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            backdrop-filter: var(--glass-blur);
            border-radius: 12px;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 200px;
        }

        .hero-float-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: var(--accent-dim);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .hero-float-icon svg {
            width: 16px;
            height: 16px;
            color: var(--accent);
        }

        .hero-float-text {
            font-size: 12px;
            font-weight: 400;
            color: var(--white);
        }

        .hero-float-sub {
            font-size: 11px;
            color: var(--muted);
            margin-top: 2px;
        }

        .nav-user-pill {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 4px 4px 4px 16px;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 100px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .nav-user-pill:hover {
            border-color: var(--accent);
            background: rgba(255, 255, 255, 0.1);
        }

        .user-info {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .user-name {
            font-size: 11px;
            font-weight: 500;
            color: var(--white);
        }

        .user-role {
            font-size: 9px;
            color: var(--muted);
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .user-avatar-small {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--accent);
            color: var(--deep);
            font-size: 10px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .user-avatar-small img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* TICKER */
        .ticker-wrap {
            overflow: hidden;
            border-top: 1px solid var(--glass-border);
            border-bottom: 1px solid var(--glass-border);
            background: rgba(200, 169, 110, 0.03);
            padding: 14px 0;
            z-index: 2;
            position: relative;
        }

        .ticker-track {
            display: flex;
            white-space: nowrap;
            animation: tickerScroll 30s linear infinite;
        }

        .ticker-track:hover {
            animation-play-state: paused;
        }

        .ticker-item {
            display: inline-flex;
            align-items: center;
            gap: 24px;
            padding: 0 44px;
            font-family: var(--font-mono);
            font-size: 11px;
            color: var(--muted);
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .ticker-dot {
            display: inline-block;
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background: var(--accent);
        }

        @keyframes tickerScroll {
            0% {
                transform: translateX(0)
            }

            100% {
                transform: translateX(-50%)
            }
        }

        /* ABOUT */
        #about {
            padding: 130px 60px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 88px;
            align-items: center;
        }

        .about-label {
            display: flex;
            align-items: center;
            gap: 12px;
            font-family: var(--font-mono);
            font-size: 11px;
            color: var(--accent);
            letter-spacing: 0.2em;
            text-transform: uppercase;
            margin-bottom: 28px;
        }

        .about-label::before {
            content: '02';
            color: var(--muted2);
        }

        .about-title {
            font-family: var(--font-display);
            font-size: clamp(36px, 4vw, 58px);
            font-weight: 300;
            line-height: 1.14;
            margin-bottom: 28px;
        }

        .about-title em {
            font-style: italic;
            color: var(--accent);
        }

        .about-body {
            font-size: 14px;
            font-weight: 300;
            line-height: 1.85;
            color: var(--muted);
            margin-bottom: 22px;
        }

        .about-cards {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .about-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            backdrop-filter: var(--glass-blur);
            border-radius: var(--radius);
            padding: 26px 30px;
            transition: border-color .3s, background .3s, transform .3s;
            position: relative;
            overflow: hidden;
        }

        .about-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background: var(--accent);
            transform: scaleY(0);
            transform-origin: top;
            transition: transform .4s;
        }

        .about-card:hover {
            border-color: rgba(200, 169, 110, 0.3);
            background: rgba(200, 169, 110, 0.04);
            transform: translateX(4px);
        }

        .about-card:hover::before {
            transform: scaleY(1);
        }

        .about-card-icon {
            width: 34px;
            height: 34px;
            margin-bottom: 12px;
            color: var(--accent);
        }

        .about-card-title {
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 7px;
        }

        .about-card-body {
            font-size: 13px;
            font-weight: 300;
            color: var(--muted);
            line-height: 1.65;
        }

        /* STATS */
        #stats {
            padding: 110px 60px;
            background: rgba(255, 255, 255, 0.018);
            border-top: 1px solid var(--glass-border);
            border-bottom: 1px solid var(--glass-border);
        }

        .stats-header {
            text-align: center;
            margin-bottom: 72px;
        }

        .stats-label {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            font-family: var(--font-mono);
            font-size: 11px;
            color: var(--accent);
            letter-spacing: 0.2em;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .stats-label::before {
            content: '03';
            color: var(--muted2);
            margin-right: 4px;
        }

        .stats-title {
            font-family: var(--font-display);
            font-size: clamp(32px, 4vw, 54px);
            font-weight: 300;
            line-height: 1.15;
        }

        .stats-title em {
            font-style: italic;
            color: var(--accent);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 70px;
        }

        .stat-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            backdrop-filter: var(--glass-blur);
            border-radius: var(--radius);
            padding: 36px 26px;
            text-align: center;
            transition: transform .3s, border-color .3s;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 1px;
            background: var(--accent);
            transition: width .4s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            border-color: rgba(200, 169, 110, 0.28);
        }

        .stat-card:hover::before {
            width: 70%;
        }

        .stat-number {
            font-family: var(--font-display);
            font-size: 54px;
            font-weight: 300;
            line-height: 1;
            color: var(--accent);
            margin-bottom: 8px;
        }

        .stat-label {
            font-size: 12px;
            font-weight: 400;
            color: var(--muted);
            letter-spacing: 0.05em;
            line-height: 1.4;
        }

        .stat-type {
            position: absolute;
            top: 14px;
            right: 14px;
            font-family: var(--font-mono);
            font-size: 9px;
            color: var(--muted2);
            letter-spacing: 0.15em;
            text-transform: uppercase;
        }

        /* CHARTS */
        #charts {
            padding: 120px 60px;
            position: relative;
            z-index: 1;
        }

        .charts-header {
            margin-bottom: 64px;
        }

        .charts-label {
            display: flex;
            align-items: center;
            gap: 12px;
            font-family: var(--font-mono);
            font-size: 11px;
            color: var(--accent);
            letter-spacing: 0.2em;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .charts-label::before {
            content: '04';
            color: var(--muted2);
        }

        .charts-title {
            font-family: var(--font-display);
            font-size: clamp(32px, 4vw, 52px);
            font-weight: 300;
            line-height: 1.15;
        }

        .charts-title em {
            font-style: italic;
            color: var(--accent);
        }

        .charts-subtitle {
            font-size: 14px;
            font-weight: 300;
            color: var(--muted);
            margin-top: 14px;
            max-width: 480px;
            line-height: 1.7;
        }

        .charts-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 24px;
        }

        .charts-grid-bottom {
            display: grid;
            grid-template-columns: 1.6fr 1fr;
            gap: 24px;
        }

        .chart-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            backdrop-filter: var(--glass-blur);
            border-radius: var(--radius);
            padding: 28px 28px 24px;
            transition: border-color .3s;
            position: relative;
            overflow: hidden;
        }

        .chart-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(200, 169, 110, 0.3), transparent);
            opacity: 0;
            transition: opacity .4s;
        }

        .chart-card:hover {
            border-color: rgba(200, 169, 110, 0.22);
        }

        .chart-card:hover::after {
            opacity: 1;
        }

        .chart-card-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 22px;
        }

        .chart-card-title {
            font-size: 13px;
            font-weight: 500;
            color: var(--white);
            letter-spacing: 0.02em;
        }

        .chart-card-sub {
            font-size: 11px;
            color: var(--muted);
            margin-top: 4px;
            font-family: var(--font-mono);
        }

        .chart-badge {
            font-family: var(--font-mono);
            font-size: 10px;
            color: var(--accent);
            border: 1px solid rgba(200, 169, 110, 0.3);
            border-radius: 100px;
            padding: 4px 10px;
            letter-spacing: 0.1em;
        }

        .chart-badge.up {
            color: #5DCAA5;
            border-color: rgba(93, 202, 165, 0.3);
        }

        .chart-badge.warn {
            color: #EF9F27;
            border-color: rgba(239, 159, 39, 0.3);
        }

        .chart-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 16px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            color: var(--muted);
        }

        .legend-dot {
            width: 8px;
            height: 8px;
            border-radius: 2px;
            flex-shrink: 0;
        }

        .sla-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .sla-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 7px;
        }

        .sla-name {
            font-size: 12px;
            color: var(--muted);
        }

        .sla-val {
            font-size: 12px;
            font-family: var(--font-mono);
            color: var(--accent);
        }

        .sla-bar-wrap {
            height: 4px;
            border-radius: 100px;
            background: rgba(255, 255, 255, 0.07);
            overflow: hidden;
        }

        .sla-bar {
            height: 100%;
            border-radius: 100px;
            width: 0;
            transition: width 1.4s cubic-bezier(0.22, 1, 0.36, 1);
        }

        .spiral-container {
            position: relative;
            height: 260px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            perspective: 800px;
            margin-top: 20px;
        }

        .spiral-track {
            position: relative;
            width: 100%;
            height: 100%;
            transform-style: preserve-3d;
        }

        .spiral-item {
            position: absolute;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 100px;
            padding: 9px 20px;
            font-family: var(--font-mono);
            font-size: 11px;
            color: var(--muted);
            letter-spacing: 0.1em;
            text-transform: uppercase;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .spiral-item .dot {
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background: var(--accent);
        }

        /* ── HORIZONTAL FEATURE SHOWCASE (diperbaiki) ── */
        #features-scroller {
            position: relative;
            overflow: clip;
        }

        .features-sticky-wrap {
            position: sticky;
            top: 0;
            height: 100vh;
            width: 100%;
            overflow: hidden;
            display: flex;
            align-items: center;
        }

        .features-horizontal-track {
            display: flex;
            flex-wrap: nowrap;
            height: 100%;
            width: max-content;
            will-change: transform;
        }

        .feature-slide {
            width: 100vw;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            padding: 0 60px;
        }

        .feature-slide.first-slide {
            width: 80vw;
            justify-content: flex-start;
        }

        .feature-slide.last-slide {
            width: 80vw;
            justify-content: center;
        }

        .feature-content {
            max-width: 600px;
        }

        .feature-label {
            font-family: var(--font-mono);
            font-size: 11px;
            color: var(--accent);
            letter-spacing: 0.22em;
            text-transform: uppercase;
            margin-bottom: 24px;
        }

        .feature-display-title {
            font-family: var(--font-display);
            font-size: clamp(48px, 6vw, 92px);
            font-weight: 300;
            line-height: 1.0;
            letter-spacing: -0.02em;
            margin-bottom: 28px;
        }

        .feature-display-title em {
            font-style: italic;
            color: var(--accent);
        }

        .feature-desc {
            font-size: 16px;
            font-weight: 300;
            line-height: 1.7;
            color: var(--muted);
            max-width: 480px;
        }

        .feature-hint {
            margin-top: 56px;
            display: flex;
            align-items: center;
            gap: 16px;
            font-family: var(--font-mono);
            font-size: 10px;
            color: var(--muted2);
            text-transform: uppercase;
            letter-spacing: 0.15em;
        }

        .hint-line {
            width: 60px;
            height: 1px;
            background: var(--muted2);
            position: relative;
            overflow: hidden;
        }

        .hint-line::after {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 30%;
            background: var(--accent);
            animation: hintLineMove 1.5s infinite linear;
        }

        @keyframes hintLineMove {
            0% {
                left: -30%;
            }

            100% {
                left: 100%;
            }
        }

        /* Varied Feature Slides */
        .feature-slide {
            width: 100vw;
            height: 100vh;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 10vw;
            position: relative;
            z-index: 2;
        }

        .feature-slide.wide {
            padding: 0 5vw;
        }

        .feature-visual-wrap {
            display: flex;
            align-items: center;
            gap: 60px;
            width: 100%;
        }

        .feature-text-side {
            flex: 1;
        }

        .feature-visual-side {
            flex: 1.2;
            position: relative;
        }

        /* Tracking Flow Visual */
        .tracking-flow {
            display: flex;
            flex-direction: column;
            gap: 40px;
            position: relative;
            padding: 30px;
            background: linear-gradient(135deg, rgba(255,255,255,0.03), rgba(255,255,255,0.01));
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 28px;
            backdrop-filter: blur(12px);
            box-shadow: 0 24px 60px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.05);
        }

        .flow-stepper {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 40px 15px;
            position: relative;
            z-index: 1;
        }

        .flow-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            text-align: center;
            position: relative;
        }

        /* Horizontal Lines for Row 1 and Row 2 */
        .flow-step:not(:nth-child(5n))::after {
            content: '';
            position: absolute;
            top: 23px;
            left: calc(50% + 23px);
            width: calc(100% - 46px);
            height: 2px;
            background: rgba(255, 255, 255, 0.06);
            z-index: -1;
            transition: all 0.3s;
        }

        .flow-step.active:not(:nth-child(5n))::after {
            background: var(--accent);
            box-shadow: 0 0 12px rgba(200, 169, 110, 0.6);
            animation: linePulse 2s infinite;
        }

        @keyframes linePulse {
            0% { opacity: 0.4; }
            50% { opacity: 1; }
            100% { opacity: 0.4; }
        }

        /* U-Turn Connector from 5 to 6 */
        .flow-step:nth-child(5)::before {
            content: '';
            position: absolute;
            top: 23px;
            left: calc(50% + 23px);
            width: 30px;
            height: calc(100% + 40px);
            border-top: 2px solid rgba(255, 255, 255, 0.06);
            border-right: 2px solid rgba(255, 255, 255, 0.06);
            border-bottom: 2px solid rgba(255, 255, 255, 0.06);
            border-radius: 0 18px 18px 0;
            z-index: -1;
            transition: all 0.3s;
        }

        .flow-step.active:nth-child(5)::before {
            border-color: var(--accent);
            box-shadow: 6px 0 16px rgba(200, 169, 110, 0.15), inset -2px 0 6px rgba(200, 169, 110, 0.1);
        }

        .step-node {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            background: linear-gradient(145deg, #1a202c, #0f131a);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.05), 0 4px 12px rgba(0,0,0,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
            position: relative;
            z-index: 2;
        }

        .step-num {
            position: absolute;
            top: -6px;
            left: -6px;
            width: 20px;
            height: 20px;
            background: #111;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 6px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--muted);
            font-weight: 700;
            z-index: 3;
            box-shadow: 0 2px 6px rgba(0,0,0,0.5);
            transition: all 0.3s ease;
        }

        .flow-step.active .step-node {
            background: linear-gradient(145deg, rgba(200, 169, 110, 0.2), rgba(200, 169, 110, 0.05));
            border-color: rgba(200, 169, 110, 0.5);
            color: #fff;
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.2), 0 12px 30px rgba(200, 169, 110, 0.25);
            transform: scale(1.15) translateY(-2px);
        }

        .flow-step.active .step-num {
            background: var(--accent);
            color: #000;
            border-color: #e0c897;
            box-shadow: 0 4px 10px rgba(200, 169, 110, 0.4);
        }

        .step-label {
            font-size: 11px;
            font-weight: 600;
            color: var(--muted);
            line-height: 1.2;
            max-width: 80px;
        }

        /* Global Track Line */
        .features-horizontal-track {
            position: relative;
            display: flex;
            height: 100%;
        }

        .features-main-line {
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(to right,
                    rgba(200, 169, 110, 0) 0%,
                    rgba(200, 169, 110, 0.2) 10%,
                    rgba(200, 169, 110, 0.2) 90%,
                    rgba(200, 169, 110, 0) 100%);
            z-index: 1;
            transform: translateY(-50%);
            pointer-events: none;
        }

        .features-main-line::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, var(--accent), transparent);
            opacity: 0.1;
            filter: blur(8px);
        }

        /* Document Scan Visual */
        .doc-preview {
            width: 240px;
            height: 320px;
            background: white;
            border-radius: 8px;
            padding: 25px;
            position: relative;
            margin: 0 auto;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            transform: rotate(-5deg);
            z-index: 5;
        }

        .doc-line {
            height: 6px;
            background: #eee;
            margin-bottom: 12px;
            border-radius: 3px;
        }

        .doc-line.short {
            width: 60%;
        }

        .doc-qr {
            position: absolute;
            bottom: 25px;
            right: 25px;
            width: 60px;
            height: 60px;
            background: #111;
            padding: 5px;
            border-radius: 4px;
        }

        .scan-bar {
            position: absolute;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--accent);
            box-shadow: 0 0 15px var(--accent);
            z-index: 10;
            animation: scanMove 3s ease-in-out infinite;
        }

        @keyframes scanMove {

            0%,
            100% {
                top: 10%;
            }

            50% {
                top: 90%;
            }
        }

        /* Feature Card Enhancements */
        .feature-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            transition: transform .5s, border-color .5s;
            position: relative;
        }

        .feature-card.gold-border {
            border-color: rgba(200, 169, 110, 0.3);
        }

        .feature-card-num {
            font-family: var(--font-mono);
            font-size: 13px;
            color: var(--muted2);
            margin-bottom: 40px;
        }

        .feature-card-icon {
            font-size: 44px;
            color: var(--accent);
            margin-bottom: 28px;
        }

        .feature-card-title {
            font-family: var(--font-display);
            font-size: 32px;
            font-weight: 300;
            color: var(--white);
            margin-bottom: 18px;
            line-height: 1.2;
        }

        .feature-card-text {
            font-size: 14px;
            font-weight: 300;
            line-height: 1.8;
            color: var(--muted);
            margin-bottom: auto;
        }

        .feature-card-footer {
            font-family: var(--font-mono);
            font-size: 10px;
            color: var(--muted2);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding-top: 24px;
            border-top: 1px solid var(--glass-border);
        }

        /* PORTALS */
        #portals {
            padding: 120px 60px;
        }

        .portals-header {
            margin-bottom: 56px;
        }

        .portals-label {
            display: flex;
            align-items: center;
            gap: 12px;
            font-family: var(--font-mono);
            font-size: 11px;
            color: var(--accent);
            letter-spacing: 0.2em;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .portals-label::before {
            content: '05';
            color: var(--muted2);
        }

        .portals-title {
            font-family: var(--font-display);
            font-size: clamp(32px, 4vw, 52px);
            font-weight: 300;
            line-height: 1.15;
        }

        .portals-title em {
            font-style: italic;
            color: var(--accent);
        }

        .portals-subtitle {
            font-size: 14px;
            font-weight: 300;
            color: var(--muted);
            margin-top: 14px;
            max-width: 520px;
            line-height: 1.7;
        }

        .portals-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .portal-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            backdrop-filter: var(--glass-blur);
            border-radius: var(--radius);
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 18px;
            text-decoration: none;
            cursor: none;
            transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
            position: relative;
            overflow: hidden;
        }

        .portal-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at var(--x) var(--y), rgba(200, 169, 110, 0.15) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.4s;
        }

        .portal-card:hover {
            border-color: rgba(200, 169, 110, 0.4);
            transform: translateY(-5px);
            background: rgba(200, 169, 110, 0.04);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        .portal-card:hover::before {
            opacity: 1;
        }

        .portal-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: var(--accent-dim);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: var(--accent);
            transition: transform 0.4s;
            overflow: hidden;
            padding: 10px;
        }

        .portal-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        }

        .portal-card:hover .portal-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .portal-info {
            flex: 1;
        }

        .portal-name {
            font-size: 14px;
            font-weight: 500;
            color: var(--white);
            margin-bottom: 4px;
        }

        .portal-desc {
            font-size: 12px;
            color: var(--muted);
            font-weight: 300;
        }

        .portal-arrow {
            color: var(--muted2);
            transition: all 0.3s;
            transform: translateX(-10px);
            opacity: 0;
        }

        .portal-card:hover .portal-arrow {
            transform: translateX(0);
            opacity: 1;
            color: var(--accent);
        }

        /* FOOTER */
        #footer {
            padding: 90px 60px 52px;
            border-top: 1px solid var(--glass-border);
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.8fr 1fr 1fr 1fr;
            gap: 52px;
            margin-bottom: 64px;
        }

        .footer-brand {
            font-family: var(--font-mono);
            font-size: 11px;
            color: var(--accent);
            letter-spacing: 0.2em;
            text-transform: uppercase;
            margin-bottom: 18px;
        }

        .footer-desc {
            font-size: 13px;
            font-weight: 300;
            color: var(--muted);
            line-height: 1.75;
            max-width: 280px;
            margin-bottom: 24px;
        }

        .footer-address {
            font-size: 12px;
            font-weight: 300;
            color: var(--muted2);
            line-height: 1.8;
            font-style: normal;
        }

        .footer-col-title {
            font-family: var(--font-mono);
            font-size: 10px;
            color: var(--accent);
            letter-spacing: 0.2em;
            text-transform: uppercase;
            margin-bottom: 18px;
        }

        .footer-links {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 11px;
        }

        .footer-links a {
            font-size: 13px;
            font-weight: 300;
            color: var(--muted);
            text-decoration: none;
            transition: color .25s;
        }

        .footer-links a:hover {
            color: var(--white);
        }

        .footer-cta-band {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius);
            padding: 36px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 32px;
            margin-bottom: 48px;
        }

        .footer-cta-text {
            font-family: var(--font-display);
            font-size: 28px;
            font-weight: 300;
            line-height: 1.2;
        }

        .footer-cta-text em {
            font-style: italic;
            color: var(--accent);
        }

        .footer-cta-btns {
            display: flex;
            gap: 12px;
            flex-shrink: 0;
        }

        .footer-socials {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .social-link {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--glass-border);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--muted);
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.23, 1, 0.32, 1);
            font-size: 16px;
            padding: 8px;
            /* Tambahan biar logo gak nempel ke pinggir */
        }

        .social-link img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .social-link:hover {
            transform: translateY(-3px);
            border-color: var(--accent);
            color: var(--white);
            background: rgba(200, 169, 110, 0.1);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .social-link.ig:hover {
            color: #E4405F;
            border-color: #E4405F;
            background: rgba(228, 64, 95, 0.1);
        }

        .social-link.mail:hover {
            color: #EA4335;
            border-color: #EA4335;
            background: rgba(234, 67, 53, 0.1);
        }

        .social-link.fb:hover {
            color: #1877F2;
            border-color: #1877F2;
            background: rgba(24, 119, 242, 0.1);
        }

        /* Futuristic Timer */
        .timer-circle-wrap {
            position: relative;
            width: 280px;
            height: 280px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .timer-circle-bg {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.05);
            background: radial-gradient(circle, rgba(200, 169, 110, 0.05) 0%, transparent 70%);
        }

        .timer-progress-ring {
            position: absolute;
            inset: -10px;
            border-radius: 50%;
            border: 2px solid transparent;
            border-top-color: var(--accent);
            border-right-color: var(--accent);
            opacity: 0.5;
            animation: rotateClock 10s linear infinite;
        }

        @keyframes rotateClock {
            from {
                transform: rotate(0);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .timer-display {
            text-align: center;
            z-index: 2;
        }

        .timer-val {
            font-size: 42px;
            font-weight: 800;
            color: white;
            font-family: var(--font-mono);
            letter-spacing: -1px;
            text-shadow: 0 0 20px rgba(200, 169, 110, 0.3);
        }

        /* Floating Archive Stack */
        .archive-stack {
            position: relative;
            width: 350px;
            height: 400px;
        }

        .archive-item {
            position: absolute;
            width: 240px;
            height: 300px;
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 20px;
            transition: all 0.5s ease;
        }

        .archive-item:nth-child(1) {
            transform: translate(0, 0) rotate(-10deg);
            z-index: 3;
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .archive-item:nth-child(2) {
            transform: translate(40px, 30px) rotate(5deg);
            z-index: 2;
            opacity: 0.6;
        }

        .archive-item:nth-child(3) {
            transform: translate(-30px, 60px) rotate(-5deg);
            z-index: 1;
            opacity: 0.3;
        }

        .archive-item i {
            font-size: 40px;
            color: var(--accent);
            margin-bottom: 15px;
            display: block;
        }

        .archive-item .doc-skeleton {
            height: 6px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
            margin-bottom: 10px;
        }

        /* Tech Stack Section (Welcome Style) */
        .dev-section {
            padding: 120px 0 80px;
            position: relative;
            z-index: 10;
            overflow: hidden;
            background: rgba(0,0,0,0.2);
        }

        .dev-header-minimal {
            text-align: center;
            margin-bottom: 60px;
            padding: 0 24px;
        }

        .dev-header-minimal h3 {
            font-family: var(--font-display);
            font-size: clamp(32px, 4vw, 48px);
            color: white;
            margin-bottom: 15px;
        }

        .dev-header-minimal p {
            color: var(--muted);
            max-width: 600px;
            margin: 0 auto;
            font-size: 16px;
        }

        .tm-wrap {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-top: 40px;
            width: 100%;
        }

        .tm-label {
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--accent);
            opacity: 0.6;
            text-align: center;
            margin-bottom: 5px;
        }

        .tm-track {
            overflow: hidden;
            mask-image: linear-gradient(to right, transparent, black 15%, black 85%, transparent);
            -webkit-mask-image: linear-gradient(to right, transparent, black 15%, black 85%, transparent);
            user-select: none;
            padding: 10px 0;
        }

        .tm-row {
            display: flex;
            gap: 20px;
            width: max-content;
        }

        .tm-row.go-right { animation: scrollR 60s linear infinite; }
        .tm-row.go-left  { animation: scrollL 65s linear infinite; }
        .tm-row.go-right2 { animation: scrollR 55s linear infinite; }

        @keyframes scrollR { from { transform: translateX(-50%); } to { transform: translateX(0); } }
        @keyframes scrollL { from { transform: translateX(0); } to { transform: translateX(-50%); } }

        .tm-track:hover .tm-row {
            animation-play-state: paused;
        }

        /* ─── 3D SPIRAL SCROLL ANIMATION ─── */
        #spiral-section {
            position: relative;
            height: 400vh;
            z-index: 1;
        }

        .spiral-sticky {
            position: sticky;
            top: 0;
            height: 100vh;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .spiral-bg-gradient {
            position: absolute;
            inset: 0;
            pointer-events: none;
        }

        body:not(.light-mode) .spiral-bg-gradient {
            background: radial-gradient(ellipse 60% 60% at 50% 50%, rgba(200, 169, 110, 0.06) 0%, transparent 70%);
        }

        body.light-mode .spiral-bg-gradient {
            background: radial-gradient(ellipse 60% 60% at 50% 50%, rgba(26, 115, 232, 0.12) 0%, transparent 70%);
        }

        .spiral-stage {
            position: relative;
            width: 100%;
            height: 100%;
            perspective: 1200px;
            perspective-origin: 50% 50%;
            overflow: hidden;
        }

        .spiral-label-center {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            z-index: 10;
            pointer-events: none;
        }

        .spiral-label-center .slc-num {
            font-family: var(--font-mono);
            font-size: 10px;
            color: var(--accent);
            letter-spacing: .25em;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        .spiral-label-center h2 {
            font-family: var(--font-display);
            font-size: clamp(42px, 5vw, 76px);
            font-weight: 300;
            line-height: 1.1;
            color: var(--white);
        }

        .spiral-label-center h2 em {
            font-style: italic;
            color: var(--accent)
        }

        .spiral-label-center p {
            font-size: 14px;
            color: var(--muted);
            font-weight: 300;
            margin-top: 14px;
            max-width: 400px;
        }

        .spiral-item-3d {
            position: absolute;
            left: 50%;
            top: 50%;
            transform-style: preserve-3d;
            will-change: transform;
            pointer-events: none;
        }

        .spiral-pill {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            backdrop-filter: var(--glass-blur);
            border-radius: 100px;
            padding: 10px 22px;
            font-family: var(--font-mono);
            font-size: 12px;
            color: var(--muted);
            letter-spacing: .12em;
            text-transform: uppercase;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 8px;
            transform: translate(-50%, -50%);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: background 0.3s, border-color 0.3s, color 0.3s;
        }

        .spiral-pill .sdot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: var(--accent);
            flex-shrink: 0;
        }

        .spiral-progress {
            position: absolute;
            bottom: 48px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .sp-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: var(--glass-border);
            transition: background .3s, transform .3s;
        }

        .sp-dot.active {
            background: var(--accent);
            transform: scale(1.4)
        }

        .tech-chip {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 22px;
            border-radius: 16px;
            border: 1px solid var(--glass-border);
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(10px);
            font-size: 14px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.7);
            white-space: nowrap;
            transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .tech-chip:hover {
            border-color: var(--accent);
            background: rgba(200, 169, 110, 0.1);
            transform: translateY(-5px) rotate(2deg);
            color: white;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .tech-chip img {
            width: 20px;
            height: 20px;
            object-fit: contain;
        }

        .tech-chip .chip-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .social-links-dev {
            display: flex;
            gap: 15px;
            margin-top: 40px;
        }

        .btn-dev-social {
            padding: 12px 24px;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--glass-border);
            color: white;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
        }

        .btn-dev-social:hover {
            background: white;
            color: #111;
            border-color: white;
            transform: translateY(-3px);
        }

        @media (max-width: 768px) {
            .dev-header {
                flex-direction: column;
                gap: 20px;
                text-align: center;
                align-items: center;
            }

            .dev-card {
                padding: 30px 20px;
            }

            .social-links-dev {
                justify-content: center;
            }
        }

        .social-link.tw:hover {
            color: #ffffff;
            border-color: #ffffff;
            background: rgba(255, 255, 255, 0.1);
        }

        .footer-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 28px;
            border-top: 1px solid var(--glass-border);
        }

        .footer-copy {
            font-family: var(--font-mono);
            font-size: 11px;
            color: var(--muted2);
            letter-spacing: 0.08em;
        }

        .footer-tagline {
            font-family: var(--font-display);
            font-style: italic;
            font-size: 14px;
            color: var(--muted2);
        }

        @media (max-width: 1100px) {
            .hero-float-cards {
                display: none;
            }

            .hero-badge {
                right: 40px;
            }

            .charts-grid,
            .charts-grid-bottom {
                grid-template-columns: 1fr;
            }

            .footer-grid {
                grid-template-columns: 1fr 1fr;
                gap: 36px;
            }

            .feature-slide.first-slide,
            .feature-slide.last-slide {
                width: 100vw;
                justify-content: center;
            }

            .feature-slide {
                padding: 0 24px;
            }

            .feature-card {
                width: 360px;
                height: 480px;
                padding: 36px 28px;
            }
        }

        @media (max-width: 900px) {
            nav {
                padding: 18px 24px;
            }

            nav.scrolled {
                padding: 13px 24px;
            }

            .nav-center {
                display: none;
            }

            #hero {
                padding: 120px 24px 90px;
            }

            .hero-badge {
                display: none;
            }

            #about {
                grid-template-columns: 1fr;
                padding: 80px 24px;
                gap: 44px;
            }

            #stats,
            #charts {
                padding: 80px 24px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            #footer {
                padding: 60px 24px 40px;
            }

            .footer-grid {
                grid-template-columns: 1fr;
                gap: 36px;
            }

            .footer-cta-band {
                flex-direction: column;
                align-items: flex-start;
            }

            .footer-bottom {
                flex-direction: column;
                gap: 12px;
                text-align: center;
            }

            .portals-grid {
                grid-template-columns: 1fr 1fr;
            }
        }
    </style>
</head>

<body>

    <div id="cursor"></div>
    <div id="cursor-ring"></div>
    <div id="scroll-bar"></div>
    <canvas id="particles-canvas"></canvas>
    <div class="bg-mesh"></div>
    <div class="bg-grid"></div>
    <div class="bg-orb bg-orb-1"></div>
    <div class="bg-orb bg-orb-2"></div>
    <div class="bg-orb bg-orb-3"></div>

    {{-- NAVBAR --}}
    <nav id="navbar">
        <a href="#" class="nav-logo" style="display: flex; align-items: center;">
            <img src="{{ asset('images/BP_SUML2.png') }}" alt="BPSUML" style="height: 36px; width: auto; object-fit: contain; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));">
        </a>
        <ul class="nav-center">
            <li><a href="{{ url('/?home=1') }}">Beranda</a></li>
            <li><a href="#about">Tentang</a></li>
            <li><a href="#stats">Statistik</a></li>
            <li><a href="#charts">Grafik</a></li>
            <li><a href="#portals">Portal</a></li>
            <li><a href="#features-scroller">Fitur</a></li>
            <li><a href="#developer">Developer</a></li>
            <li><a href="#footer">Kontak</a></li>
        </ul>
        <div class="nav-auth">
            @auth
                <a href="{{ route('dashboard') }}" class="nav-user-pill">
                    <div class="user-info">
                        <span class="user-name">{{ Auth::user()->name }}</span>
                        <span class="user-role">Dashboard</span>
                    </div>
                    <div class="user-avatar-small">
                        @if(Auth::user()->profile_photo)
                            <img src="{{ Storage::url(Auth::user()->profile_photo) }}" alt="Profile">
                        @else
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        @endif
                    </div>
                </a>
            @else
                <a href="{{ route('login') }}" class="nav-btn-login">Login</a>
                <a href="{{ route('register') }}" class="nav-btn-register">Daftar</a>
            @endauth
        </div>
    </nav>

    {{-- HERO --}}
    <section id="hero">
        <div class="hero-eyebrow">
            <span class="hero-eyebrow-line"></span>
            Sistem Arsip Digital
        </div>
        <h1 class="hero-title">
            <span class="line">Balai Pengelolaan</span>
            <span class="line"><em>Standar Ukuran</em></span>
            <span class="line">&amp; Metrologi Legal</span>
        </h1>
        <p class="hero-subtitle">
            Sistem pengelolaan surat dan dokumen resmi Balai Pengelolaan Standar Ukuran &amp;
            Metrologi Legal — terarsip, terstruktur, dan mudah diakses kapan saja.
        </p>
        <div class="hero-cta">
            <a href="{{ route('login') }}" class="btn-primary">
                Masuk Sistem
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7" />
                </svg>
            </a>
            <a href="{{ route('register') }}" class="btn-secondary">
                Daftar Akun
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2M12 11a4 4 0 100-8 4 4 0 000 8zM19 8v6M22 11h-6" />
                </svg>
            </a>
            <div class="cta-divider"></div>
            <a href="#about" class="btn-ghost">
                Pelajari lebih lanjut
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7" />
                </svg>
            </a>
        </div>
        <div class="hero-float-cards">
            <div class="hero-float-card">
                <div class="hero-float-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5">
                        <path
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg></div>
                <div>
                    <div class="hero-float-text">10 Tahap Approval</div>
                    <div class="hero-float-sub">Alur kerja terstruktur</div>
                </div>
            </div>
            <div class="hero-float-card">
                <div class="hero-float-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5">
                        <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg></div>
                <div>
                    <div class="hero-float-text">SLA 24 Jam Kerja</div>
                    <div class="hero-float-sub">Real-time countdown</div>
                </div>
            </div>
            <div class="hero-float-card">
                <div class="hero-float-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5">
                        <path
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg></div>
                <div>
                    <div class="hero-float-text">Verifikasi QR Code</div>
                    <div class="hero-float-sub">Autentikasi dokumen</div>
                </div>
            </div>
        </div>
        <div class="hero-badge">
            <span class="hero-badge-inner">{{ date('Y') }}</span>
            <span class="hero-badge-label">Manajemen surat<br>BPSUML</span>
        </div>
        <div class="hero-scroll-hint">
            <div class="scroll-line"></div>
            <span>Scroll</span>
        </div>
    </section>

    {{-- TICKER --}}
    <div class="ticker-wrap">
        <div class="ticker-track">
            @php
                $items = ['Surat Masuk', 'Surat Keluar', 'SK Internal', 'Nota Dinas', 'Pengelolaan Surat', 'Kalibrasi Alat', 'Metrologi Legal', 'SPBE', 'Dokumen Teknis', 'Direktorat Metrologi', 'Tera Ulang', 'Surat Edaran'];
            @endphp
            @foreach(array_merge($items, $items) as $item)
                <span class="ticker-item"><span class="ticker-dot"></span>{{ $item }}</span>
            @endforeach
        </div>
    </div>

    {{-- ABOUT --}}
    <section id="about">
        <div class="about-left" data-reveal>
            <div class="about-label">Tentang Kami</div>
            <h2 class="about-title">Manajemen surat digital<br>untuk <em>BPSUML</em></h2>
            <p class="about-body">Balai Pengelolaan Standar Ukuran dan Metrologi Legal (BPSUML) merupakan unit pelaksana
                teknis di bawah Direktorat Metrologi, Kementerian Perdagangan RI, yang bertugas menyelenggarakan
                Pengelolaan, kalibrasi, dan tera ulang alat ukur.</p>
            <p class="about-body">Sistem arsip digital ini hadir untuk mendukung tata kelola administrasi yang
                transparan, akuntabel, dan efisien dalam lingkungan pemerintahan yang modern.</p>
        </div>
        <div class="about-cards">
            <div class="about-card"><svg class="about-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.5">
                    <path
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <div class="about-card-title">Arsip Surat Digital</div>
                <div class="about-card-body">Pengelolaan surat masuk, surat keluar, dan dokumen internal secara terpusat
                    dan terdigitalisasi.</div>
            </div>
            <div class="about-card"><svg class="about-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.5">
                    <path
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <div class="about-card-title">Keamanan & Akuntabilitas</div>
                <div class="about-card-body">Sistem berbasis peran (role-based) dengan autentikasi aman dan log
                    aktivitas yang terekam lengkap.</div>
            </div>
            <div class="about-card"><svg class="about-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.5">
                    <path d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                </svg>
                <div class="about-card-title">Statistik & Pelaporan</div>
                <div class="about-card-body">Visualisasi data surat secara real-time untuk mendukung pengambilan
                    keputusan berbasis data.</div>
            </div>
        </div>
    </section>



    {{-- STATS --}}
    <section id="stats">
        <div class="stats-header">
            <div class="stats-label">Statistik</div>
            <h2 class="stats-title">Data <em>Arsip</em> Terkini</h2>
        </div>
        <div class="stats-grid">
            <div class="stat-card"><span class="stat-type">Masuk</span>
                <div class="stat-number" data-target="{{ $totalSuratMasuk ?? 284 }}">0</div>
                <div class="stat-label">Surat Masuk<br>Tercatat</div>
            </div>
            <div class="stat-card"><span class="stat-type">Keluar</span>
                <div class="stat-number" data-target="{{ $totalSuratKeluar ?? 197 }}">0</div>
                <div class="stat-label">Surat Keluar<br>Dikirim</div>
            </div>
            <div class="stat-card"><span class="stat-type">Aktif</span>
                <div class="stat-number" data-target="{{ $totalPengguna ?? 52 }}">0</div>
                <div class="stat-label">Pengguna<br>Terdaftar</div>
            </div>
            <div class="stat-card"><span class="stat-type">Total</span>
                <div class="stat-number" data-target="{{ ($totalSuratMasuk ?? 284) + ($totalSuratKeluar ?? 197) }}">0
                </div>
                <div class="stat-label">Dokumen<br>Terarsip</div>
            </div>
        </div>
    </section>

    <!-- 3D SPIRAL SCROLL SECTION -->
    <section id="spiral-section">
        <div class="spiral-sticky">
            <div class="spiral-bg-gradient"></div>
            <div class="spiral-stage" id="spiral-stage">
                <div class="spiral-label-center" id="spiral-center">
                    <div class="slc-num">06 — Fitur Unggulan</div>
                    <h2>Scroll untuk<br><em>Menjelajahi</em></h2>
                    <p>Semua fitur sistem persuratan BPSUML dalam satu tampilan interaktif</p>
                </div>
                <div id="spiral-items-container"></div>
                <div class="spiral-progress" id="spiral-progress"></div>
            </div>
        </div>
    </section>



    {{-- CHARTS --}}
    <section id="charts">
        <div class="charts-header" data-reveal>
            <div class="charts-label">Analitik</div>
            <h2 class="charts-title">Grafik & <em>Statistik</em> Sistem</h2>
            <p class="charts-subtitle">Visualisasi data surat, kepatuhan SLA, distribusi jenis dokumen, dan tren bulanan
                secara real-time.</p>
        </div>
        <div class="charts-grid">
            <div class="chart-card">
                <div class="chart-card-header">
                    <div>
                        <div class="chart-card-title">Tren Surat & Kepatuhan SLA</div>
                        <div class="chart-card-sub">6 bulan terakhir · mixed chart</div>
                    </div><span class="chart-badge up">↑ 12%</span>
                </div>
                <div class="chart-legend"><span class="legend-item"><span class="legend-dot"
                            style="background:#C8A96E"></span>Surat Masuk</span><span class="legend-item"><span
                            class="legend-dot" style="background:#1D9E75"></span>Surat Keluar</span><span
                        class="legend-item"><span class="legend-dot"
                            style="background:rgba(93,202,165,0.7);border:1px solid #5DCAA5"></span>SLA Rate %</span>
                </div>
                <div style="position:relative;width:100%;height:240px"><canvas id="chartMixed"></canvas></div>
            </div>
            <div class="chart-card">
                <div class="chart-card-header">
                    <div>
                        <div class="chart-card-title">Distribusi Jenis Surat</div>
                        <div class="chart-card-sub">Proporsi per kategori</div>
                    </div><span class="chart-badge">7 jenis</span>
                </div>
                <div id="doughnut-legend" class="chart-legend" style="flex-wrap:wrap;gap:8px 14px"></div>
                <div style="position:relative;width:100%;height:220px"><canvas id="chartDoughnut"></canvas></div>
            </div>
        </div>
        <div class="charts-grid-bottom">
            <div class="chart-card">
                <div class="chart-card-header">
                    <div>
                        <div class="chart-card-title">Aktivitas Arsip 12 Bulan</div>
                        <div class="chart-card-sub">Surat masuk vs keluar · stacked area</div>
                    </div><span class="chart-badge warn">{{ date('Y') }}</span>
                </div>
                <div class="chart-legend"><span class="legend-item"><span class="legend-dot"
                            style="background:#C8A96E;opacity:.7"></span>Masuk</span><span class="legend-item"><span
                            class="legend-dot" style="background:#378ADD;opacity:.7"></span>Keluar</span><span
                        class="legend-item"><span class="legend-dot"
                            style="background:#1D9E75;opacity:.7"></span>Selesai</span></div>
                <div style="position:relative;width:100%;height:240px"><canvas id="chartArea"></canvas></div>
            </div>
            <div class="chart-card">
                <div class="chart-card-header">
                    <div>
                        <div class="chart-card-title">Kepatuhan SLA per Jenis</div>
                        <div class="chart-card-sub">% tepat waktu · bulan ini</div>
                    </div><span class="chart-badge up">SLA</span>
                </div>
                <div class="sla-list" id="sla-list" style="margin-top:8px"></div>
            </div>
        </div>
    </section>

    {{-- PORTALS --}}
    <section id="portals">
        <div class="portals-header" data-reveal>
            <div class="portals-label">Ekosistem</div>
            <h2 class="portals-title">Layanan <em>Terkait</em></h2>
            <p class="portals-subtitle">Akses cepat ke portal dan aplikasi pendukung lainnya di lingkungan BPSUML.</p>
        </div>
        <div class="portals-grid">
            @php
                $portals = [['name' => 'SIMET', 'desc' => 'Sistem Informasi Metrologi', 'img' => 'logo.png', 'url' => 'https://metrologi.kemendag.go.id/'], ['name' => 'KEMENDAG', 'desc' => 'Kementerian Perdagangan', 'img' => 'kemendag.png', 'url' => 'https://www.kemendag.go.id/'], ['name' => 'SPBE', 'desc' => 'Informasi Aplikasi SISWASPK', 'img' => 'logo.png', 'url' => 'https://simpktn.kemendag.go.id/index.php/siswaspk'], ['name' => 'PPID', 'desc' => 'Informasi, Pelaporan & Dokumentasi', 'img' => 'kemendag.png', 'url' => 'https://metrologi.kemendag.go.id/pelaporan_ttu/web/home'], ['name' => 'LAPOR!', 'desc' => 'Layanan Pengaduan Online', 'img' => 'logo.png', 'url' => 'http://127.0.0.1:8000/dashboard'], ['name' => 'About BPSUML', 'desc' => 'Sistem BPSUML', 'img' => 'kemendag.png', 'url' => 'https://metrologi.kemendag.go.id/master_suml/']];
            @endphp
            @foreach($portals as $p)
                <a href="{{ $p['url'] }}" target="_blank" class="portal-card">
                    <div class="portal-icon"><img src="{{ asset('images/portals/' . $p['img']) }}" alt="{{ $p['name'] }}"
                            onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($p['name']) }}&background=C8A96E&color=070B13'">
                    </div>
                    <div class="portal-info">
                        <div class="portal-name">{{ $p['name'] }}</div>
                        <div class="portal-desc">{{ $p['desc'] }}</div>
                    </div>
                    <div class="portal-arrow"><svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7" />
                        </svg></div>
                </a>
            @endforeach
        </div>
    </section>

    {{-- HORIZONTAL FEATURES SHOWCASE (Diperbaiki) --}}
    <section id="features-scroller">
        <div class="features-sticky-wrap">
            <div class="features-horizontal-track" id="features-track">
                <div class="features-main-line"></div>
                <!-- Intro slide -->
                <div class="feature-slide first-slide">
                    <div class="feature-content">
                        <div class="feature-label">Fitur Unggulan</div>
                        <h2 class="feature-display-title">Eksplorasi<br><em>Ekosistem</em> Kami</h2>
                        <p class="feature-desc">Sistem yang dirancang untuk efisiensi, transparansi, dan keamanan data
                            dalam setiap proses administrasi persuratan.</p>
                        <div class="feature-hint"><span>Scroll untuk eksplorasi</span>
                            <div class="hint-line"></div>
                        </div>
                    </div>
                </div>
                <!-- Slide 2: Visual Tracking Flow -->
                <div class="feature-slide wide">
                    <div class="feature-visual-wrap">
                        <div class="feature-text-side">
                            <div class="feature-card-num">01</div>
                            <h3 class="feature-card-title">Tracking Alur Kerja</h3>
                            <p class="feature-card-text">Monitor setiap tahapan dokumen secara akurat. Dari
                                pengajuan awal, verifikasi, hingga approval dalam satu alur terpadu.</p>
                            <div class="feature-card-footer">10 Tahap Otomatis</div>
                        </div>
                        <div class="feature-visual-side">
                            <div class="tracking-flow">
                                <div class="flow-stepper">
                                    <div class="flow-step active">
                                        <div class="step-node"><span class="step-num">1</span>📤</div>
                                        <div class="step-label">Usulan Diajukan</div>
                                    </div>
                                    <div class="flow-step active">
                                        <div class="step-node"><span class="step-num">2</span>🔍</div>
                                        <div class="step-label">Verifikasi Arsiparis</div>
                                    </div>
                                    <div class="flow-step active">
                                        <div class="step-node"><span class="step-num">3</span>🏢</div>
                                        <div class="step-label">Verifikasi Kasubbag</div>
                                    </div>
                                    <div class="flow-step active">
                                        <div class="step-node"><span class="step-num">4</span>✍️</div>
                                        <div class="step-label">Persetujuan Kepala Balai</div>
                                    </div>
                                    <div class="flow-step">
                                        <div class="step-node"><span class="step-num">5</span>🔢</div>
                                        <div class="step-label">Penomoran Surat</div>
                                    </div>
                                    <div class="flow-step">
                                        <div class="step-node"><span class="step-num">10</span>✅</div>
                                        <div class="step-label">Selesai</div>
                                    </div>
                                    <div class="flow-step">
                                        <div class="step-node"><span class="step-num">9</span>🗄️</div>
                                        <div class="step-label">Pengarsipan</div>
                                    </div>
                                    <div class="flow-step">
                                        <div class="step-node"><span class="step-num">8</span>📧</div>
                                        <div class="step-label">Kirim Srikandi</div>
                                    </div>
                                    <div class="flow-step">
                                        <div class="step-node"><span class="step-num">7</span>📡</div>
                                        <div class="step-label">Kirim TNDe</div>
                                    </div>
                                    <div class="flow-step">
                                        <div class="step-node"><span class="step-num">6</span>🔏</div>
                                        <div class="step-label">Tanda Tangan</div>
                                    </div>
                                </div>
                                <div
                                    style="margin-top: 25px; text-align: center; color: var(--accent); font-size: 11px; font-family: var(--font-mono); letter-spacing: 1px; text-transform: uppercase;">
                                    <span style="display: inline-flex; align-items: center; gap: 8px;">
                                        <span
                                            style="width: 6px; height: 6px; background: var(--accent); border-radius: 50%; box-shadow: 0 0 10px var(--accent);"></span>
                                        Real-time Tracking Active
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 3: SLA Visual (Circular Chronometer) -->
                <div class="feature-slide">
                    <div class="feature-visual-wrap">
                        <div class="feature-visual-side" style="display: flex; justify-content: center;">
                            <div class="timer-circle-wrap">
                                <div class="timer-circle-bg"></div>
                                <div class="timer-progress-ring"></div>
                                <div class="timer-display">
                                    <div class="timer-val">23:54:12</div>
                                    <div
                                        style="font-size: 11px; color: var(--muted); text-transform: uppercase; letter-spacing: 2px;">
                                        Sisa Waktu</div>
                                </div>
                            </div>
                        </div>
                        <div class="feature-text-side">
                            <div class="feature-card-num">02</div>
                            <h3 class="feature-card-title">Akurasi Waktu Pelayanan</h3>
                            <p class="feature-card-text">Setiap dokumen dipantau dengan presisi melalui sistem
                                SLA 24 jam. Memastikan komitmen pelayanan tetap terjaga dan akuntabel.</p>
                            <div class="feature-card-footer">Chronometer Monitoring</div>
                        </div>
                    </div>
                </div>

                <!-- Slide 4: QR Code Visual -->
                <div class="feature-slide wide">
                    <div class="feature-visual-wrap">
                        <div class="feature-text-side">
                            <div class="feature-card-num">03</div>
                            <h3 class="feature-card-title">Verifikasi QR Code</h3>
                            <p class="feature-card-text">Keaslian dokumen terjamin , bisa di scan melalui HandPhone(HP) dan
                                verifikasi QR Code yang terintegrasi langsung dengan database sistem.</p>
                            <div class="feature-card-footer">Keamanan Berstandar</div>
                        </div>
                        <div class="feature-visual-side">
                            <div class="doc-preview">
                                <div class="scan-bar"></div>
                                <div class="doc-line"
                                    style="width: 40%; height: 10px; background: #ddd; margin-bottom: 25px;"></div>
                                <div class="doc-line"></div>
                                <div class="doc-line"></div>
                                <div class="doc-line" style="width: 80%;"></div>
                                <div class="doc-line"></div>
                                <div class="doc-line" style="width: 60%;"></div>
                                <div class="doc-qr">
                                    <svg viewBox="0 0 24 24" fill="white">
                                        <path
                                            d="M3 3h8v8H3V3zm2 2v4h4V5H5zm8-2h8v8h-8V3zm2 2v4h4V5h-4zM3 13h8v8H3v-8zm2 2v4h4v-4H5zm13-2h3v2h-3v-2zm-3 0h2v2h-2v-2zm3 3h3v2h-3v-2zm-3 3h2v2h-2v-2zm3-3h3v2h-3v-2zm-3 3h2v2h-2v-2zm3 0h3v2h-3v-2z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 5: Digitalisasi (Floating Stack) -->
                <div class="feature-slide wide">
                    <div class="feature-visual-wrap">
                        <div class="feature-text-side">
                            <div class="feature-card-num">04</div>
                            <h3 class="feature-card-title">Arsip Digital Terpadu</h3>
                            <p class="feature-card-text">Ubah tumpukan kertas menjadi aset digital yang terorganisir.
                                Seluruh dokumen terenkripsi dan tersimpan dalam ekosistem cloud yang aman.</p>
                            <div class="feature-card-footer">Cloud-Based Archiving</div>
                        </div>
                        <div class="feature-visual-side" style="display: flex; justify-content: center;">
                            <div class="archive-stack">
                                <div class="archive-item">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                    <div class="doc-skeleton" style="width: 80%;"></div>
                                    <div class="doc-skeleton" style="width: 60%;"></div>
                                    <div class="doc-skeleton" style="width: 90%;"></div>
                                </div>
                                <div class="archive-item">
                                    <i class="bi bi-file-earmark-word"></i>
                                    <div class="doc-skeleton"></div>
                                    <div class="doc-skeleton"></div>
                                </div>
                                <div class="archive-item">
                                    <i class="bi bi-file-earmark-text"></i>
                                    <div class="doc-skeleton" style="width: 70%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Final CTA -->
                <div class="feature-slide last-slide">
                    <div class="feature-content">
                        <h2 class="feature-display-title">Siap Mencoba?</h2>
                        <p class="feature-desc">Masuk ke dashboard untuk mulai mengelola surat Anda.</p>
                        <div style="margin-top: 2rem;"><a href="{{ route('login') }}" class="btn-primary">Buka
                                Dashboard</a></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- DEVELOPER SECTION (Repositioned & Redesigned) --}}
    <section id="developer" class="dev-section">
        <div class="dev-header-minimal" data-reveal>
            <h3>Teknologi <em>Modern</em></h3>
            <p>Dibangun menggunakan stack teknologi mutakhir untuk memastikan performa tinggi, keamanan maksimal, dan skalabilitas jangka panjang.</p>
        </div>

        <div class="tm-wrap">
            <div class="tm-label">Architecture & Data</div>
            <div class="tm-track">
                <div class="tm-row go-right" id="tm-row1"></div>
            </div>

            <div class="tm-label">Interface & Experience</div>
            <div class="tm-track">
                <div class="tm-row go-left" id="tm-row2"></div>
            </div>

            <div class="tm-label">Environment & Intelligence</div>
            <div class="tm-track">
                <div class="tm-row go-right2" id="tm-row3"></div>
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer id="footer">
        <div class="footer-cta-band">
            <div class="footer-cta-text">Siap menggunakan<br><em>sistem arsip digital</em>?</div>
            <div class="footer-cta-btns"><a href="{{ route('login') }}" class="btn-primary">Login <svg width="13"
                        height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7" />
                    </svg></a><a href="{{ route('register') }}" class="btn-secondary">Register <svg width="13"
                        height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 5v14M5 12h14" />
                    </svg></a></div>
        </div>
        <div class="footer-grid">
            <div>
                <div class="footer-brand">BPSUML</div>
                <p class="footer-desc">Balai Pengelolaan Standar Ukuran dan Metrologi Legal — unit pelaksana teknis
                    Direktorat Metrologi, Kementerian Perdagangan RI.</p>
                <address class="footer-address">Jl. Pasteur No. 27, Pasteur<br>Kec. Sukajadi, Kota Bandung<br>Jawa Barat
                    40161</address>
            </div>
            <div>
                <div class="footer-col-title">Navigasi</div>
                <ul class="footer-links">
                    <li><a href="#hero">Beranda</a></li>
                    <li><a href="#about">Tentang</a></li>
                    <li><a href="#stats">Statistik</a></li>
                    <li><a href="#charts">Grafik</a></li>
                    <li><a href="{{ route('login') }}">Masuk Sistem</a></li>
                </ul>
            </div>
            <div>
                <div class="footer-col-title">Akses</div>
                <ul class="footer-links">
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('register') }}">Register</a></li>
                </ul>
            </div>
            <div>
                <div class="footer-col-title">Kontak</div>
                <ul class="footer-links">
                    <li><a href="mailto:tubpsuml@gmail.com"><i class="bi bi-envelope-at me-2"></i>tubpsuml@gmail.com</a>
                    
                    </li>
                    <li><a href="https://metrologi.kemendag.go.id/" target="_blank"><i
                                class="bi bi-globe me-2"></i>metrologi.kemendag.go.id</a></li>
                </ul>
                <div class="footer-socials">
                    <a href="https://www.instagram.com/direktorat_metrologi/" target="_blank" class="social-link ig"
                        title="Instagram">
                        <img src="https://cdn.simpleicons.org/instagram/E4405F" alt="Instagram">
                    </a>
                    <a href="mailto:tubpsuml@gmail.com" class="social-link mail" title="Gmail">
                        <img src="https://cdn.simpleicons.org/gmail/EA4335" alt="Gmail">
                    </a>
                    <a href="#" target="_blank" class="social-link fb" title="Facebook">
                        <img src="https://cdn.simpleicons.org/facebook/1877F2" alt="Facebook">
                    </a>
                    <a href="https://x.com/DitMetrologi" target="_blank" class="social-link tw" title="Twitter/X">
                        <img src="https://cdn.simpleicons.org/x/white" alt="X">
                    </a>
                </div>
            </div>
        </div>
        <div class="footer-bottom"><span class="footer-copy">© {{ date('Y') }} BPSUML — Direktorat Metrologi
                RI</span><span class="footer-tagline">Mengukur dengan Adil, Melayani dengan Tepat</span></div>
    </footer>

    <script>
        // Cursor
        const cursor = document.getElementById('cursor');
        const ring = document.getElementById('cursor-ring');
        let mx = 0, my = 0, rx = 0, ry = 0;
        document.addEventListener('mousemove', e => { mx = e.clientX; my = e.clientY; });
        function animCursor() { cursor.style.left = mx + 'px'; cursor.style.top = my + 'px'; rx += (mx - rx) * 0.10; ry += (my - ry) * 0.10; ring.style.left = rx + 'px'; ring.style.top = ry + 'px'; requestAnimationFrame(animCursor); }
        animCursor();
        document.querySelectorAll('a,button').forEach(el => { el.addEventListener('mouseenter', () => document.body.classList.add('cursor-hover')); el.addEventListener('mouseleave', () => document.body.classList.remove('cursor-hover')); });

        // Scroll progress + nav
        window.addEventListener('scroll', () => { const p = window.scrollY / (document.body.scrollHeight - window.innerHeight) * 100; document.getElementById('scroll-bar').style.width = p + '%'; document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 40); });

        // Particles
        (function () { const cvs = document.getElementById('particles-canvas'); const ctx = cvs.getContext('2d'); function resize() { cvs.width = window.innerWidth; cvs.height = window.innerHeight; } resize(); window.addEventListener('resize', resize); const N = 55; const particles = Array.from({ length: N }, () => ({ x: Math.random() * window.innerWidth, y: Math.random() * window.innerHeight, r: Math.random() * 1.5 + 0.4, vx: (Math.random() - 0.5) * 0.25, vy: (Math.random() - 0.5) * 0.25, a: Math.random() * 0.5 + 0.15 })); function drawParticles() { ctx.clearRect(0, 0, cvs.width, cvs.height); particles.forEach(p => { p.x += p.vx; p.y += p.vy; if (p.x < 0) p.x = cvs.width; if (p.x > cvs.width) p.x = 0; if (p.y < 0) p.y = cvs.height; if (p.y > cvs.height) p.y = 0; ctx.beginPath(); ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2); ctx.fillStyle = `rgba(200,169,110,${p.a})`; ctx.fill(); }); for (let i = 0; i < N; i++) for (let j = i + 1; j < N; j++) { const dx = particles[i].x - particles[j].x; const dy = particles[i].y - particles[j].y; const dist = Math.sqrt(dx * dx + dy * dy); if (dist < 120) { ctx.beginPath(); ctx.moveTo(particles[i].x, particles[i].y); ctx.lineTo(particles[j].x, particles[j].y); ctx.strokeStyle = `rgba(200,169,110,${0.07 * (1 - dist / 120)})`; ctx.lineWidth = 0.5; ctx.stroke(); } } requestAnimationFrame(drawParticles); } drawParticles(); })();

        // Lenis Initialization
        const lenis = new Lenis({
            duration: 1.2,
            easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
            smoothWheel: true,
            wheelMultiplier: 1,
            infinite: false,
        });

        function raf(time) {
            lenis.raf(time);
            requestAnimationFrame(raf);
        }
        requestAnimationFrame(raf);

        // Sync Lenis with ScrollTrigger
        lenis.on('scroll', ScrollTrigger.update);
        gsap.ticker.add((time) => {
            lenis.raf(time * 1000);
        });
        gsap.ticker.lagSmoothing(0);

        // Magnetic Effect Function
        function initMagnetic() {
            const magnetics = document.querySelectorAll('.btn-primary, .btn-secondary, .nav-btn-register, .nav-user-pill');
            magnetics.forEach(el => {
                el.addEventListener('mousemove', function (e) {
                    const rect = this.getBoundingClientRect();
                    const x = e.clientX - rect.left - rect.width / 2;
                    const y = e.clientY - rect.top - rect.height / 2;
                    gsap.to(this, { x: x * 0.35, y: y * 0.35, duration: 0.4, ease: 'power2.out' });
                });
                el.addEventListener('mouseleave', function (e) {
                    gsap.to(this, { x: 0, y: 0, duration: 0.6, ease: 'elastic.out(1, 0.3)' });
                });
            });
        }
        initMagnetic();

        gsap.registerPlugin(ScrollTrigger);
        // Hero Parallax
        gsap.to('.hero-title', {
            y: -80,
            scrollTrigger: {
                trigger: '#hero',
                start: 'top top',
                end: 'bottom top',
                scrub: true
            }
        });

        gsap.to('.hero-float-card', {
            y: (i, target) => -120 - (i * 60),
            scrollTrigger: {
                trigger: '#hero',
                start: 'top top',
                end: 'bottom top',
                scrub: true
            }
        });

        gsap.to('.hero-badge', {
            y: -180,
            rotation: 180,
            scrollTrigger: {
                trigger: '#hero',
                start: 'top top',
                end: 'bottom top',
                scrub: true
            }
        });

        // Background Parallax
        gsap.to('.bg-mesh', {
            y: 200,
            opacity: 0.5,
            scrollTrigger: {
                trigger: '#hero',
                start: 'top top',
                end: 'bottom top',
                scrub: true
            }
        });

        gsap.to('.bg-orb', {
            y: (i) => 100 * (i + 1),
            x: (i) => (i % 2 === 0 ? 50 : -50),
            scrollTrigger: {
                trigger: '#hero',
                start: 'top top',
                end: 'bottom top',
                scrub: true
            }
        });

        gsap.to('.bg-grid', {
            y: 50,
            scrollTrigger: {
                trigger: '#hero',
                start: 'top top',
                end: 'bottom top',
                scrub: true
            }
        });

        // Hero animations
        anime({ targets: '.hero-eyebrow', opacity: [0, 1], duration: 600, delay: 300, easing: 'easeOutCubic' });
        anime({ targets: '.hero-eyebrow-line', width: [0, 32], duration: 800, delay: 500, easing: 'easeOutExpo' });
        const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });
        tl.to('.hero-title .line', { opacity: 1, y: 0, duration: 1.0, stagger: 0.14, delay: 0.5 }).to('.hero-subtitle', { opacity: 1, y: 0, duration: 0.8 }, '-=0.4').to('.hero-cta', { opacity: 1, y: 0, duration: 0.7 }, '-=0.35').to('.hero-float-cards', { opacity: 1, x: 0, duration: 0.8, ease: 'power2.out' }, '-=0.5').to('.hero-badge', { opacity: 1, duration: 0.8 }, '-=0.4').to('.hero-scroll-hint', { opacity: 1, duration: 0.6 }, '-=0.2');
        // About
        gsap.fromTo('.about-card', { opacity: 0, x: 50 }, { opacity: 1, x: 0, duration: 0.75, stagger: 0.12, scrollTrigger: { trigger: '#about', start: 'top 68%' } });
        gsap.fromTo('.about-left > *', { opacity: 0, x: -35 }, { opacity: 1, x: 0, duration: 0.75, stagger: 0.1, scrollTrigger: { trigger: '#about', start: 'top 68%' } });
        // Charts
        gsap.fromTo('.chart-card', { opacity: 0, y: 40 }, { opacity: 1, y: 0, duration: 0.7, stagger: 0.12, scrollTrigger: { trigger: '#charts', start: 'top 70%' } });
        gsap.fromTo('.charts-header > *', { opacity: 0, y: 25 }, { opacity: 1, y: 0, duration: 0.7, stagger: 0.1, scrollTrigger: { trigger: '#charts', start: 'top 75%' } });
        // Footer
        gsap.fromTo('#footer > *', { opacity: 0, y: 30 }, { opacity: 1, y: 0, duration: 0.7, stagger: 0.13, scrollTrigger: { trigger: '#footer', start: 'top 80%' } });
        // Stat counters
        document.querySelectorAll('.stat-number').forEach(el => { const target = parseInt(el.dataset.target) || 0; ScrollTrigger.create({ trigger: el, start: 'top 85%', once: true, onEnter: () => { anime({ targets: el, innerHTML: [0, target], round: 1, duration: 2000, easing: 'easeOutExpo', update: function (a) { el.innerHTML = Math.round(a.animations[0].currentValue); } }); } }); });
        gsap.fromTo('.stat-card', { opacity: 0, y: 38 }, { opacity: 1, y: 0, duration: 0.6, stagger: 0.1, scrollTrigger: { trigger: '#stats', start: 'top 70%' } });

        // ─── 3D SPIRAL SCROLL ANIMATION ───
        const spiralData = [
            { label: 'Surat Masuk', emoji: '📥' },
            { label: 'Surat Keluar', emoji: '📤' },
            { label: 'SK Internal', emoji: '📋' },
            { label: 'Nota Dinas', emoji: '📝' },
            { label: 'UTTP Kalibrasi', emoji: '⚖️' },
            { label: 'Tera Ulang', emoji: '🔬' },
            { label: 'Dokumen Teknis', emoji: '📐' },
            { label: 'Laporan Tahunan', emoji: '📊' },
            { label: 'Surat Edaran', emoji: '📢' },
            { label: 'Permohonan', emoji: '📌' },
            { label: 'QR Verifikasi', emoji: '🔐' },
            { label: '10 Tahap SLA', emoji: '⏱️' },
        ];

        const spiralContainer = document.getElementById('spiral-items-container');
        const spiralStage = document.getElementById('spiral-stage');
        const spiralCenter = document.getElementById('spiral-center');
        const spiralProgressEl = document.getElementById('spiral-progress');

        if (spiralContainer && spiralProgressEl) {
            // Build DOM items
            spiralData.forEach((d, i) => {
                const el = document.createElement('div');
                el.className = 'spiral-item-3d';
                el.id = `spiral-item-${i}`;
                el.innerHTML = `<div class="spiral-pill"><span class="sdot"></span>${d.emoji} ${d.label}</div>`;
                spiralContainer.appendChild(el);
            });

            // Progress dots
            spiralData.forEach((_, i) => {
                const dot = document.createElement('div');
                dot.className = 'sp-dot';
                dot.id = `sp-dot-${i}`;
                spiralProgressEl.appendChild(dot);
            });
        }

        // Spiral scroll logic
        const spiralSection = document.getElementById('spiral-section');

        function updateSpiral(progress) {
            const n = spiralData.length;
            const totalTurns = 2.5;

            spiralData.forEach((d, i) => {
                const el = document.getElementById(`spiral-item-${i}`);
                if (!el) return;

                const baseAngle = (i / n) * Math.PI * 2;
                const currentRotation = progress * totalTurns * Math.PI * 2;
                const angle = baseAngle - currentRotation;

                const radius = 280;
                const heightSpread = 180;

                const spiralT = (i / n + progress * totalTurns) % 1;
                const x = Math.cos(angle) * radius;
                const yMapped = ((((i / n) - progress * (totalTurns)) % 1) + 1) % 1;
                const y = (yMapped - 0.5) * heightSpread * 3;
                const z = Math.sin(angle) * radius;

                const depth = (z + radius) / (radius * 2);
                const scale = 0.6 + depth * 0.7;
                const opacity = 0.15 + depth * 0.85;

                const visible = Math.abs(y) < 500;

                el.style.left = '50%';
                el.style.top = '50%';
                el.style.transform = `translate(calc(-50% + ${x}px), calc(-50% + ${y}px)) scale(${scale})`;
                el.style.opacity = visible ? opacity : 0;
                el.style.zIndex = Math.round(depth * 100);

                const pill = el.querySelector('.spiral-pill');
                const isActive = Math.abs(y) < 60 && depth > 0.6;
                if (pill) {
                    if (isActive) {
                        pill.style.borderColor = 'var(--accent)';
                        pill.style.color = 'var(--white)';
                        pill.style.background = 'var(--accent-dim)';
                    } else {
                        pill.style.borderColor = '';
                        pill.style.color = '';
                        pill.style.background = '';
                    }
                }
            });

            const activeIdx = Math.floor(progress * n * totalTurns) % n;
            spiralData.forEach((_, i) => {
                const dot = document.getElementById(`sp-dot-${i}`);
                if (dot) dot.classList.toggle('active', i === activeIdx);
            });

            const step = Math.floor(progress * 4);
            const texts = [
                { h: 'Scroll untuk<br><em>Menjelajahi</em>', p: 'Semua fitur sistem persuratan BPSUML' },
                { h: 'Pengelolaan<br><em>Dokumen</em>', p: 'Dari surat masuk hingga pengarsipan digital' },
                { h: 'Keamanan<br><em>Terverifikasi</em>', p: 'QR Code & SLA monitoring real-time' },
                { h: 'Sistem<br><em>Terintegrasi</em>', p: 'Seluruh alur kerja dalam satu platform' },
            ];
            const t = texts[Math.min(step, texts.length - 1)];
            if (spiralCenter) {
                spiralCenter.querySelector('h2').innerHTML = t.h;
                spiralCenter.querySelector('p').textContent = t.p;
            }
        }

        if (spiralSection) {
            ScrollTrigger.create({
                trigger: spiralSection,
                start: 'top top',
                end: 'bottom bottom',
                scrub: 0.5,
                onUpdate: self => updateSpiral(self.progress)
            });
            updateSpiral(0);
        }

        // Chart.js: Mixed
        const months = ['Nov', 'Des', 'Jan', 'Feb', 'Mar', 'Apr']; const dataIn = [38, 45, 52, 41, 60, 55]; const dataOut = [30, 38, 44, 35, 52, 47]; const dataSLA = [88, 91, 85, 93, 96, 94];
        new Chart(document.getElementById('chartMixed'), { data: { labels: months, datasets: [{ type: 'bar', label: 'Surat Masuk', data: dataIn, backgroundColor: 'rgba(200,169,110,0.65)', borderColor: '#C8A96E', borderWidth: 1, borderRadius: 5, order: 2 }, { type: 'bar', label: 'Surat Keluar', data: dataOut, backgroundColor: 'rgba(29,158,117,0.55)', borderColor: '#1D9E75', borderWidth: 1, borderRadius: 5, order: 2 }, { type: 'line', label: 'SLA Rate %', data: dataSLA, borderColor: '#5DCAA5', backgroundColor: 'rgba(93,202,165,0.08)', borderWidth: 2.5, pointBackgroundColor: '#5DCAA5', pointRadius: 4, pointHoverRadius: 6, tension: 0.4, fill: true, yAxisID: 'y2', order: 1 }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false }, tooltip: { mode: 'index', intersect: false, backgroundColor: 'rgba(7,11,19,0.9)', borderColor: 'rgba(200,169,110,0.3)', borderWidth: 1, titleColor: '#C8A96E', bodyColor: 'rgba(244,241,236,0.7)', padding: 10 } }, scales: { x: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: 'rgba(244,241,236,0.45)', font: { size: 11 } } }, y: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: 'rgba(244,241,236,0.45)', font: { size: 11 } }, beginAtZero: true }, y2: { position: 'right', min: 70, max: 100, grid: { drawOnChartArea: false }, ticks: { color: 'rgba(93,202,165,0.6)', font: { size: 11 }, callback: v => v + '%' } } } } });
        // Doughnut
        const doughnutLabels = ['Nota Dinas', 'Surat Dinas', 'Surat Keputusan', 'Surat Pernyataan', 'Surat Keterangan', 'Surat Undangan', 'Lainnya']; const doughnutData = [28, 22, 15, 12, 10, 8, 5]; const doughnutColors = ['#C8A96E', '#1D9E75', '#378ADD', '#EF9F27', '#D85A30', '#D4537E', '#888780']; const dlegend = document.getElementById('doughnut-legend'); doughnutLabels.forEach((l, i) => { dlegend.innerHTML += `<span class="legend-item"><span class="legend-dot" style="background:${doughnutColors[i]}"></span>${l} ${doughnutData[i]}%</span>`; }); new Chart(document.getElementById('chartDoughnut'), { type: 'doughnut', data: { labels: doughnutLabels, datasets: [{ data: doughnutData, backgroundColor: doughnutColors.map(c => c + 'CC'), borderColor: doughnutColors, borderWidth: 1.5, hoverOffset: 8 }] }, options: { responsive: true, maintainAspectRatio: false, cutout: '64%', plugins: { legend: { display: false }, tooltip: { backgroundColor: 'rgba(7,11,19,0.9)', borderColor: 'rgba(200,169,110,0.3)', borderWidth: 1, titleColor: '#C8A96E', bodyColor: 'rgba(244,241,236,0.7)', padding: 10, callbacks: { label: ctx => ` ${ctx.label}: ${ctx.raw}%` } } } } });
        // Stacked Area
        const months12 = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']; new Chart(document.getElementById('chartArea'), { type: 'line', data: { labels: months12, datasets: [{ label: 'Masuk', data: [30, 38, 44, 41, 52, 58, 48, 62, 55, 70, 60, 55], borderColor: '#C8A96E', backgroundColor: 'rgba(200,169,110,0.12)', borderWidth: 2, pointRadius: 3, tension: 0.4, fill: true }, { label: 'Keluar', data: [22, 30, 38, 35, 44, 50, 40, 54, 46, 60, 52, 47], borderColor: '#378ADD', backgroundColor: 'rgba(55,138,221,0.08)', borderWidth: 2, pointRadius: 3, tension: 0.4, fill: true }, { label: 'Selesai', data: [18, 26, 34, 30, 40, 46, 36, 50, 42, 56, 48, 43], borderColor: '#1D9E75', backgroundColor: 'rgba(29,158,117,0.08)', borderWidth: 2, pointRadius: 3, tension: 0.4, fill: true }] }, options: { responsive: true, maintainAspectRatio: false, interaction: { mode: 'index', intersect: false }, plugins: { legend: { display: false }, tooltip: { backgroundColor: 'rgba(7,11,19,0.9)', borderColor: 'rgba(200,169,110,0.3)', borderWidth: 1, titleColor: '#C8A96E', bodyColor: 'rgba(244,241,236,0.7)', padding: 10 } }, scales: { x: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: 'rgba(244,241,236,0.45)', font: { size: 11 } } }, y: { stacked: false, grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: 'rgba(244,241,236,0.45)', font: { size: 11 } }, beginAtZero: true } } } });
        // SLA bars
        const slaData = [{ name: 'Nota Dinas', pct: 96, color: '#1D9E75' }, { name: 'Surat Dinas', pct: 91, color: '#5DCAA5' }, { name: 'Surat Keputusan', pct: 84, color: '#C8A96E' }, { name: 'Surat Pernyataan', pct: 88, color: '#C8A96E' }, { name: 'Surat Undangan', pct: 78, color: '#EF9F27' }, { name: 'Lainnya', pct: 72, color: '#D85A30' }]; const slaList = document.getElementById('sla-list'); slaData.forEach((s, i) => { slaList.innerHTML += `<div class="sla-item"><div class="sla-row"><span class="sla-name">${s.name}</span><span class="sla-val" id="sla-val-${i}">0%</span></div><div class="sla-bar-wrap"><div class="sla-bar" id="sla-bar-${i}" style="background:${s.color}"></div></div></div>`; }); ScrollTrigger.create({ trigger: '#sla-list', start: 'top 80%', once: true, onEnter: () => { slaData.forEach((s, i) => { anime({ targets: `#sla-bar-${i}`, width: s.pct + '%', duration: 1400, delay: i * 120, easing: 'easeOutExpo' }); anime({ targets: `#sla-val-${i}`, innerHTML: [0, s.pct], round: 1, duration: 1400, delay: i * 120, easing: 'easeOutExpo', update: function (a) { document.getElementById(`sla-val-${i}`).innerHTML = Math.round(a.animations[0].currentValue) + '%'; } }); }); } });
        // Portals reveal
        gsap.fromTo('.portal-card', { opacity: 0, scale: 0.95, y: 20 }, { opacity: 1, scale: 1, y: 0, duration: 0.6, stagger: 0.08, scrollTrigger: { trigger: '#portals', start: 'top 75%' } });
        gsap.fromTo('.portals-header > *', { opacity: 0, y: 25 }, { opacity: 1, y: 0, duration: 0.7, stagger: 0.1, scrollTrigger: { trigger: '#portals', start: 'top 80%' } });
        document.querySelectorAll('.portal-card').forEach(card => { card.addEventListener('mousemove', e => { const rect = card.getBoundingClientRect(); const x = e.clientX - rect.left; const y = e.clientY - rect.top; card.style.setProperty('--x', `${x}px`); card.style.setProperty('--y', `${y}px`); }); });

        // ========== HORIZONTAL FEATURE SCROLLER (FIX) ==========
        const scrollerSection = document.getElementById('features-scroller');
        const trackHorizontal = document.getElementById('features-track');
        if (scrollerSection && trackHorizontal) {
            // Hitung total lebar track
            let totalWidth = 0;
            const slides = trackHorizontal.querySelectorAll('.feature-slide');
            slides.forEach(slide => {
                totalWidth += slide.offsetWidth;
            });
            // Set lebar track secara eksplisit
            trackHorizontal.style.width = totalWidth + 'px';
            // Buat ScrollTrigger untuk horizontal scroll
            const horizontalScroll = gsap.to(trackHorizontal, {
                x: () => -(totalWidth - window.innerWidth),
                ease: 'none',
                scrollTrigger: {
                    trigger: scrollerSection,
                    start: 'top top',
                    end: () => `+=${totalWidth - window.innerWidth}`,
                    scrub: 1.2,
                    pin: true,
                    anticipatePin: 1,
                    invalidateOnRefresh: true
                }
            });
            // Refresh ScrollTrigger saat resize
            window.addEventListener('resize', () => {
                ScrollTrigger.refresh();
                // Update track width
                let newTotal = 0;
                slides.forEach(slide => { newTotal += slide.offsetWidth; });
                trackHorizontal.style.width = newTotal + 'px';
                horizontalScroll.vars.x = () => -(newTotal - window.innerWidth);
                horizontalScroll.invalidate();
            });
        }
        // Developer Stack Loop
        (function () {
            const stacks = {
                'tm-row1': [
                    { name: 'Laravel 11/12', img: 'https://cdn.simpleicons.org/laravel/FF2D20' },
                    { name: 'PHP 8.3', img: 'https://cdn.simpleicons.org/php/777BB4' },
                    { name: 'MySQL 8', img: 'https://cdn.simpleicons.org/mysql/4479A1' },
                    { name: 'Redis', img: 'https://cdn.simpleicons.org/redis/DC382D' },
                    { name: 'MongoDB', img: 'https://cdn.simpleicons.org/mongodb/47A248' },
                    { name: 'Eloquent ORM', dot: '#e11d48' },
                    { name: 'REST API', dot: '#0ea5e9' },
                    { name: 'Sanctum Auth', img: 'https://cdn.simpleicons.org/laravel/FF2D20' },
                    { name: 'Composer', img: 'https://cdn.simpleicons.org/composer/885630' },
                    { name: 'JQuery', img: 'https://cdn.simpleicons.org/jquery/1621A5' },
                ],
                'tm-row2': [
                    { name: 'Bootstrap 5', img: 'https://cdn.simpleicons.org/bootstrap/7952B3' },
                    { name: 'TailwindCSS', img: 'https://cdn.simpleicons.org/tailwindcss/38B2AC' },
                    { name: 'Alpine.js', img: 'https://cdn.simpleicons.org/alpinedotjs/8BC0D0' },
                    { name: 'JavaScript', img: 'https://cdn.simpleicons.org/javascript/F7DF1E' },
                    { name: 'Chart.js', img: 'https://cdn.simpleicons.org/chartdotjs/FF6384' },
                    { name: 'Three.js', img: 'https://cdn.simpleicons.org/threedotjs/000000' },
                    { name: 'Vite', img: 'https://cdn.simpleicons.org/vite/646CFF' },
                    { name: 'GSAP', img: 'https://cdn.simpleicons.org/greensock/88CE02' },
                    { name: 'Anime.js', img: 'https://cdn.simpleicons.org/anime.js/FF2D20' },
                ],
                'tm-row3': [
                    { name: 'Docker', img: 'https://cdn.simpleicons.org/docker/2496ED' },
                    { name: 'Git', img: 'https://cdn.simpleicons.org/git/F05032' },
                    { name: 'GitHub', img: 'https://cdn.simpleicons.org/github/white' },
                    { name: 'Gemini AI', img: 'https://cdn.simpleicons.org/googlegemini/4285F4' },
                    { name: 'reCAPTCHA v3', dot: '#4285F4' },
                    { name: 'Postman', img: 'https://cdn.simpleicons.org/postman/FF6C37' },
                    { name: 'npm', img: 'https://cdn.simpleicons.org/npm/CB3837' },
                    { name: 'Nginx', img: 'https://cdn.simpleicons.org/nginx/009639' },
                    { name: 'Ubuntu', img: 'https://cdn.simpleicons.org/ubuntu/E9430F' },
                ]
            };

            function makeChip(item) {
                const chip = document.createElement('div');
                chip.className = 'tech-chip';
                if (item.img) {
                    const img = document.createElement('img');
                    img.src = item.img;
                    img.alt = item.name;
                    img.onerror = function () { this.style.display = 'none'; };
                    chip.appendChild(img);
                } else {
                    const dot = document.createElement('span');
                    dot.className = 'chip-dot';
                    dot.style.background = item.dot;
                    chip.appendChild(dot);
                }
                const label = document.createElement('span');
                label.textContent = item.name;
                chip.appendChild(label);
                return chip;
            }

            Object.entries(stacks).forEach(([id, items]) => {
                const row = document.getElementById(id);
                if (!row) return;
                // Double items for seamless loop
                [...items, ...items, ...items].forEach(item => row.appendChild(makeChip(item)));
            });
        })();
    </script>
</body>

</html>