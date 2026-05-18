<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BPSUML — Balai Pengelolaan Standar Ukuran Metrologi Legal</title>
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
    {{-- Three.js --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>

    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --glass-bg: rgba(255, 255, 255, 0.75);
            --glass-border: rgba(200, 169, 110, 0.25);
            --glass-blur: blur(25px);
            --accent: #1A73E8;
            --accent-rgb: 26, 115, 232;
            --accent-dim: rgba(26, 115, 232, 0.10);
            --accent-gold: #C8A96E;
            --accent-gold-light: #E8D4B8;
            --white: #0F1419;
            --muted: rgba(15, 20, 25, 0.68);
            --muted2: rgba(15, 20, 25, 0.42);
            --deep: #FAFBFF;
            --navy: #F0F4FF;
            --radius: 20px;
            --font-display: 'Cormorant Garamond', serif;
            --font-body: 'DM Sans', sans-serif;
            --font-mono: 'DM Mono', monospace;
            --shadow-soft: 0 8px 32px rgba(26, 115, 232, 0.12);
            --shadow-card: 0 12px 48px rgba(0, 0, 0, 0.12);
            --shadow-premium: 0 20px 60px rgba(200, 169, 110, 0.15), 0 0 1px rgba(200, 169, 110, 0.3);
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: var(--font-body);
            background: var(--deep);
            color: var(--white);
            overflow-x: hidden;
        }

        /* ── Light Mode Animated Gradient Background ── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            z-index: -1;
            background:
                radial-gradient(ellipse 80% 50% at 20% 10%, rgba(200, 169, 110, 0.08) 0%, transparent 50%),
                radial-gradient(ellipse 60% 40% at 80% 80%, rgba(26, 115, 232, 0.06) 0%, transparent 50%),
                radial-gradient(ellipse 40% 40% at 50% 50%, rgba(26, 115, 232, 0.04) 0%, transparent 60%),
                linear-gradient(160deg, #FAFBFF 0%, #F5F7FF 35%, #EEF0FF 65%, #FAFBFF 100%);
            animation: bgShift 22s ease-in-out infinite;
        }

        @keyframes bgShift {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.85;
            }
        }



        /* ── BG ── */
        .bg-mesh {
            position: fixed;
            top: -200px;
            left: 0;
            right: 0;
            bottom: -200px;
            z-index: 0;
            background:
                radial-gradient(ellipse 80% 60% at 18% 8%, rgba(26, 115, 232, 0.06) 0%, transparent 60%),
                radial-gradient(ellipse 60% 55% at 82% 78%, rgba(99, 102, 241, 0.04) 0%, transparent 60%),
                radial-gradient(ellipse 38% 38% at 58% 32%, rgba(26, 115, 232, 0.03) 0%, transparent 58%);
            pointer-events: none;
            will-change: transform;
        }

        .bg-grid {
            position: fixed;
            top: -100px;
            left: 0;
            right: 0;
            bottom: -100px;
            z-index: 0;
            background-image:
                linear-gradient(rgba(26, 115, 232, 0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(26, 115, 232, 0.04) 1px, transparent 1px);
            background-size: 64px 64px;
            pointer-events: none;
            will-change: transform;
        }

        #threejs-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        #threejs-canvas.visible {
            opacity: 1;
        }

        /* Ensure content is above canvas */
        #about,
        #portals {
            position: relative;
            z-index: 10;
        }

        .bg-orb {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            filter: blur(120px);
            z-index: 0;
            will-change: transform, opacity;
        }

        .bg-orb-1 {
            width: 520px;
            height: 520px;
            top: -130px;
            left: -170px;
            background: rgba(26, 115, 232, 0.10);
            animation: orbFloat1 12s ease-in-out infinite;
        }

        .bg-orb-2 {
            width: 380px;
            height: 380px;
            bottom: 8%;
            right: -100px;
            background: rgba(99, 102, 241, 0.08);
            animation: orbFloat2 10s ease-in-out infinite;
        }

        .bg-orb-3 {
            width: 280px;
            height: 280px;
            top: 40%;
            left: 40%;
            background: rgba(26, 115, 232, 0.06);
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

        @keyframes orbFloat1 {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            33% {
                transform: translate(40px, 20px) scale(1.1);
            }

            66% {
                transform: translate(-20px, -10px) scale(0.95);
            }
        }

        @keyframes orbFloat2 {

            0%,
            100% {
                transform: translate(0, 0) rotate(0deg);
            }

            50% {
                transform: translate(-30px, -40px) rotate(5deg);
            }
        }

        /* ── Scroll Progress ── */
        #scroll-bar {
            position: fixed;
            top: 0;
            left: 0;
            height: 3px;
            width: 0%;
            background: linear-gradient(90deg, var(--accent-gold), var(--accent));
            z-index: 1001;
            transition: width .08s linear;
            box-shadow: 0 0 12px rgba(200, 169, 110, 0.4);
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
            background: rgba(26, 115, 232, 0.18) !important;
            backdrop-filter: blur(20px) !important;
            -webkit-backdrop-filter: blur(20px) !important;
            border-bottom: 1.5px solid rgba(26, 115, 232, 0.35) !important;
            padding: 14px 60px;
            box-shadow: 0 8px 32px rgba(26, 115, 232, 0.2) !important;
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
            padding: 10px 24px;
            border: 1.5px solid rgba(200, 169, 110, 0.3);
            background: rgba(255, 255, 255, 0.5);
            color: var(--muted);
            font-family: var(--font-body);
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.09em;
            text-transform: uppercase;
            text-decoration: none;
            border-radius: 8px;
            cursor: pointer;
            transition: border-color .25s, color .25s, background .25s, box-shadow .25s;
        }

        .nav-btn-login:hover {
            border-color: var(--accent-gold);
            color: var(--accent-gold);
            background: rgba(232, 212, 184, 0.15);
            box-shadow: 0 8px 24px rgba(200, 169, 110, 0.15);
        }

        .nav-btn-register {
            display: inline-flex;
            align-items: center;
            padding: 10px 24px;
            background: linear-gradient(135deg, var(--accent), #1557c0);
            color: #fff;
            font-family: var(--font-body);
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.09em;
            text-transform: uppercase;
            text-decoration: none;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: transform .25s, box-shadow .25s, opacity .25s;
            box-shadow: 0 8px 24px rgba(26, 115, 232, 0.25);
        }

        .nav-btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 36px rgba(26, 115, 232, 0.35);
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
            font-size: 12px;
            color: var(--accent-gold);
            letter-spacing: 0.22em;
            text-transform: uppercase;
            margin-bottom: 40px;
            opacity: 1;
            font-weight: 600;
        }

        .hero-eyebrow-line {
            display: block;
            height: 2px;
            width: 28px;
            background: var(--accent-gold);
        }

        .hero-title {
            font-family: var(--font-display);
            font-size: clamp(52px, 8.5vw, 128px);
            font-weight: 300;
            line-height: 1.0;
            letter-spacing: -0.015em;
            margin-bottom: 32px;
            overflow: hidden;
            color: var(--white);
            text-shadow: 0 2px 8px rgba(15, 20, 25, 0.08);
        }

        .hero-title .line {
            display: block;
        }

        .hero-title em {
            font-style: italic;
            color: var(--accent);
        }

        .hero-subtitle {
            max-width: 560px;
            font-size: 16px;
            font-weight: 400;
            line-height: 1.8;
            color: var(--muted);
            margin-bottom: 56px;
            opacity: 1;
        }

        .hero-cta {
            display: flex;
            gap: 14px;
            align-items: center;
            flex-wrap: wrap;
            opacity: 1;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 16px 40px;
            background: linear-gradient(135deg, var(--accent), #1557c0);
            color: #fff;
            font-family: var(--font-body);
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            text-decoration: none;
            border: none;
            cursor: pointer;
            border-radius: 12px;
            transition: transform .4s cubic-bezier(0.23, 1, 0.32, 1), box-shadow .4s cubic-bezier(0.23, 1, 0.32, 1);
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-premium);
            z-index: 1;
        }

        /* Ensure all direct children are above the liquid overlay */
        .btn-primary>* {
            position: relative;
            z-index: 2;
        }

        .btn-primary-liquid {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50% 30% 60% 40% / 40% 60% 30% 50%;
            transform: translate(-50%, -50%) rotate(0deg);
            transition: width 0.7s cubic-bezier(0.23, 1, 0.32, 1), height 0.7s cubic-bezier(0.23, 1, 0.32, 1), transform 0.7s cubic-bezier(0.23, 1, 0.32, 1);
            z-index: 1;
            pointer-events: none;
        }

        .btn-primary:hover .btn-primary-liquid {
            width: 380px;
            height: 380px;
            transform: translate(-50%, -50%) rotate(180deg);
        }

        .btn-primary:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 24px 60px rgba(26, 115, 232, 0.35), var(--shadow-premium);
        }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 15px 38px;
            background: rgba(255, 255, 255, 0.8);
            color: var(--white);
            font-family: var(--font-body);
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            text-decoration: none;
            cursor: pointer;
            border: 1.5px solid rgba(200, 169, 110, 0.35);
            border-radius: 12px;
            transition: border-color .4s cubic-bezier(0.23, 1, 0.32, 1), background .4s cubic-bezier(0.23, 1, 0.32, 1), color .4s cubic-bezier(0.23, 1, 0.32, 1), transform .4s cubic-bezier(0.23, 1, 0.32, 1), box-shadow .4s cubic-bezier(0.23, 1, 0.32, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(255, 255, 255, 0.3);
            z-index: 1;
        }

        .btn-secondary>* {
            position: relative;
            z-index: 2;
        }

        .btn-secondary-liquid {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(200, 169, 110, 0.15);
            border-radius: 40% 60% 50% 50% / 50% 40% 60% 50%;
            transform: translate(-50%, -50%) rotate(0deg);
            transition: width 0.7s cubic-bezier(0.23, 1, 0.32, 1), height 0.7s cubic-bezier(0.23, 1, 0.32, 1), transform 0.7s cubic-bezier(0.23, 1, 0.32, 1);
            z-index: 1;
            pointer-events: none;
        }

        .btn-secondary:hover .btn-secondary-liquid {
            width: 380px;
            height: 380px;
            transform: translate(-50%, -50%) rotate(-180deg);
        }

        .btn-secondary:hover {
            border-color: var(--accent-gold);
            background: rgba(232, 212, 184, 0.25);
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 16px 40px rgba(200, 169, 110, 0.25);
        }

        /* ─── TEXT SPLIT REVEAL STYLES ─── */
        .btn-text-inner {
            display: inline-block;
            position: relative;
            overflow: hidden;
            vertical-align: middle;
            height: 1.2em;
            line-height: 1.2;
        }

        .btn-text-inner span {
            display: inline-block;
            transition: transform 0.4s cubic-bezier(0.76, 0, 0.24, 1);
        }

        .btn-text-inner::after {
            content: attr(data-hover);
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            height: 100%;
            display: inline-block;
            transition: transform 0.4s cubic-bezier(0.76, 0, 0.24, 1);
            transform: translateY(0);
            font-weight: 600;
        }

        .btn-primary:hover .btn-text-inner span,
        .btn-secondary:hover .btn-text-inner span {
            transform: translateY(-100%);
        }

        .btn-primary:hover .btn-text-inner::after,
        .btn-secondary:hover .btn-text-inner::after {
            transform: translateY(-100%);
        }

        /* Micro-animations for SVGs inside buttons */
        .btn-primary svg,
        .btn-secondary svg {
            transition: transform 0.4s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .btn-primary:hover svg,
        .btn-secondary:hover svg {
            transform: translateX(4px);
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
            cursor: pointer;
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
            bottom: 48px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            font-family: var(--font-mono);
            font-size: 11px;
            color: var(--accent-gold);
            letter-spacing: 0.15em;
            text-transform: uppercase;
            opacity: 1;
            font-weight: 600;
        }

        .scroll-line {
            width: 2px;
            height: 48px;
            background: linear-gradient(to bottom, var(--accent-gold), transparent);
            animation: scrollLine 2.2s ease-in-out infinite;
            box-shadow: 0 0 8px rgba(200, 169, 110, 0.4);
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
            width: 140px;
            height: 140px;
            border-radius: 50%;
            border: 2px solid rgba(200, 169, 110, 0.3);
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: var(--glass-blur);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 6px;
            animation: rotateBadge 20s linear infinite;
            opacity: 1;
            box-shadow: 0 12px 36px rgba(200, 169, 110, 0.15);
        }

        .hero-badge-inner {
            font-family: var(--font-mono);
            font-size: 32px;
            font-weight: 600;
            color: var(--accent-gold);
            line-height: 1;
        }

        .hero-badge-label {
            font-family: var(--font-mono);
            font-size: 10px;
            color: var(--muted2);
            letter-spacing: 0.15em;
            text-transform: uppercase;
            text-align: center;
            font-weight: 600;
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
            gap: 14px;
            opacity: 1;
        }

        .hero-float-card {
            background: rgba(255, 255, 255, 0.85);
            border: 1.5px solid rgba(200, 169, 110, 0.3);
            backdrop-filter: var(--glass-blur);
            border-radius: 14px;
            padding: 16px 22px;
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 220px;
            box-shadow: 0 12px 36px rgba(200, 169, 110, 0.12);
            transition: all 0.3s ease;
        }

        .hero-float-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 16px 48px rgba(200, 169, 110, 0.15);
        }

        .hero-float-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: rgba(200, 169, 110, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .hero-float-icon svg {
            width: 18px;
            height: 18px;
            color: var(--accent-gold);
        }

        .hero-float-text {
            font-size: 13px;
            font-weight: 600;
            color: var(--white);
        }

        .hero-float-sub {
            font-size: 12px;
            color: var(--muted);
            margin-top: 2px;
            font-weight: 400;
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
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 1px solid rgba(26, 115, 232, 0.2);
        }

        .user-avatar-small img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* TICKER */
        .ticker-wrap {
            overflow: hidden;
            border-top: 1.5px solid rgba(200, 169, 110, 0.2);
            border-bottom: 1.5px solid rgba(200, 169, 110, 0.2);
            background: linear-gradient(90deg, rgba(200, 169, 110, 0.04) 0%, rgba(26, 115, 232, 0.02) 100%);
            padding: 16px 0;
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
            gap: 28px;
            padding: 0 48px;
            font-family: var(--font-mono);
            font-size: 12px;
            color: var(--muted);
            letter-spacing: 0.12em;
            text-transform: uppercase;
            font-weight: 500;
        }

        .ticker-dot {
            display: inline-block;
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: var(--accent-gold);
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
            font-size: 12px;
            color: var(--accent-gold);
            letter-spacing: 0.2em;
            text-transform: uppercase;
            margin-bottom: 32px;
            font-weight: 600;
        }

        .about-label::before {
            content: '02';
            color: var(--accent-gold);
            opacity: 0.6;
        }

        .about-title {
            font-family: var(--font-display);
            font-size: clamp(36px, 4vw, 64px);
            font-weight: 300;
            line-height: 1.14;
            margin-bottom: 32px;
            color: var(--white);
            text-shadow: 0 2px 8px rgba(15, 20, 25, 0.06);
        }

        .about-title em {
            font-style: italic;
            color: var(--accent-gold);
        }

        .about-body {
            font-size: 15px;
            font-weight: 400;
            line-height: 1.9;
            color: var(--muted);
            margin-bottom: 24px;
        }

        .about-cards {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .about-card {
            background: var(--glass-bg);
            border: 1.5px solid rgba(200, 169, 110, 0.2);
            backdrop-filter: var(--glass-blur);
            border-radius: var(--radius);
            padding: 32px 36px;
            transition: border-color .3s, background .3s, transform .3s, box-shadow .3s;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(200, 169, 110, 0.08);
        }

        .about-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(180deg, var(--accent-gold), transparent);
            transform: scaleY(0);
            transform-origin: top;
            transition: transform .4s;
        }

        .about-card:hover {
            border-color: rgba(200, 169, 110, 0.4);
            background: rgba(200, 169, 110, 0.06);
            transform: translateX(6px);
            box-shadow: 0 16px 48px rgba(200, 169, 110, 0.12);
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
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--white);
        }

        .about-card-body {
            font-size: 14px;
            font-weight: 400;
            color: var(--muted);
            line-height: 1.7;
        }

        /* STATS */
        #stats {
            padding: 110px 60px;
            background: rgba(26, 115, 232, 0.02);
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
            font-size: 12px;
            color: var(--accent-gold);
            letter-spacing: 0.2em;
            text-transform: uppercase;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .stats-label::before {
            content: '03';
            color: var(--accent-gold);
            opacity: 0.6;
            margin-right: 4px;
        }

        .stats-title {
            font-family: var(--font-display);
            font-size: clamp(32px, 4vw, 60px);
            font-weight: 300;
            line-height: 1.15;
            color: var(--white);
            text-shadow: 0 2px 8px rgba(15, 20, 25, 0.06);
        }

        .stats-title em {
            font-style: italic;
            color: var(--accent-gold);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 70px;
        }

        .stat-card {
            background: var(--glass-bg);
            border: 1.5px solid rgba(200, 169, 110, 0.2);
            backdrop-filter: var(--glass-blur);
            border-radius: var(--radius);
            padding: 42px 32px 36px;
            text-align: center;
            transition: transform .4s cubic-bezier(0.23, 1, 0.32, 1), border-color .4s cubic-bezier(0.23, 1, 0.32, 1), box-shadow .4s cubic-bezier(0.23, 1, 0.32, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 12px 40px rgba(200, 169, 110, 0.08);
            z-index: 1;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--accent-gold), transparent);
            transition: width .4s cubic-bezier(0.23, 1, 0.32, 1);
            z-index: 3;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at var(--x, 50%) var(--y, 50%), rgba(200, 169, 110, 0.12) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.4s ease;
            z-index: 0;
            pointer-events: none;
        }

        .stat-card:hover {
            transform: translateY(-8px);
            border-color: rgba(200, 169, 110, 0.35);
            box-shadow: 0 20px 60px rgba(200, 169, 110, 0.15);
        }

        .stat-card:hover::before {
            width: 100%;
        }

        .stat-card:hover::after {
            opacity: 1;
        }

        .stat-icon-wrapper {
            width: 54px;
            height: 54px;
            border-radius: 14px;
            background: rgba(200, 169, 110, 0.08);
            border: 1px solid rgba(200, 169, 110, 0.18);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
            position: relative;
            z-index: 2;
        }

        .stat-card:hover .stat-icon-wrapper {
            background: rgba(26, 115, 232, 0.12);
            border-color: rgba(26, 115, 232, 0.25);
            transform: scale(1.1) rotate(6deg);
            box-shadow: 0 8px 20px rgba(26, 115, 232, 0.08);
        }

        .stat-icon {
            width: 24px;
            height: 24px;
            color: var(--accent-gold);
            transition: color 0.4s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .stat-card:hover .stat-icon {
            color: var(--accent);
        }

        .stat-number {
            font-family: var(--font-display);
            font-size: 62px;
            font-weight: 300;
            line-height: 1;
            color: var(--accent-gold);
            margin-bottom: 10px;
            position: relative;
            z-index: 2;
        }

        .stat-label {
            font-size: 13px;
            font-weight: 500;
            color: var(--muted);
            letter-spacing: 0.05em;
            line-height: 1.5;
            position: relative;
            z-index: 2;
        }

        .stat-trend {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-family: var(--font-mono);
            font-size: 10px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 100px;
            margin-top: 14px;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            position: relative;
            z-index: 2;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.5);
        }

        .stat-trend.positive {
            background: rgba(93, 202, 165, 0.12);
            color: #1d9e75;
            border: 1px solid rgba(93, 202, 165, 0.2);
        }

        .stat-trend.neutral {
            background: rgba(26, 115, 232, 0.1);
            color: var(--accent);
            border: 1px solid rgba(26, 115, 232, 0.2);
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
            font-size: 12px;
            color: var(--accent-gold);
            letter-spacing: 0.2em;
            text-transform: uppercase;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .charts-label::before {
            content: '04';
            color: var(--accent-gold);
            opacity: 0.6;
        }

        .charts-title {
            font-family: var(--font-display);
            font-size: clamp(32px, 4vw, 58px);
            font-weight: 300;
            line-height: 1.15;
            color: var(--white);
            text-shadow: 0 2px 8px rgba(15, 20, 25, 0.06);
        }

        .charts-title em {
            font-style: italic;
            color: var(--accent-gold);
        }

        .charts-subtitle {
            font-size: 15px;
            font-weight: 400;
            color: var(--muted);
            margin-top: 16px;
            max-width: 500px;
            line-height: 1.8;
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
            border: 1.5px solid rgba(200, 169, 110, 0.2);
            backdrop-filter: var(--glass-blur);
            border-radius: var(--radius);
            padding: 32px 32px 28px;
            transition: border-color .3s, box-shadow .3s;
            position: relative;
            overflow: hidden;
            box-shadow: 0 12px 40px rgba(200, 169, 110, 0.08);
        }

        .chart-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(200, 169, 110, 0.4), transparent);
            opacity: 0;
            transition: opacity .4s;
        }

        .chart-card:hover {
            border-color: rgba(200, 169, 110, 0.35);
            box-shadow: 0 20px 60px rgba(200, 169, 110, 0.12);
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
            font-size: 14px;
            font-weight: 600;
            color: var(--white);
            letter-spacing: 0.02em;
        }

        .chart-card-sub {
            font-size: 12px;
            color: var(--muted2);
            margin-top: 5px;
            font-family: var(--font-mono);
        }

        .chart-badge {
            font-family: var(--font-mono);
            font-size: 10px;
            color: var(--accent);
            border: 1px solid rgba(26, 115, 232, 0.3);
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
            gap: 8px;
            font-size: 12px;
            color: var(--muted);
            font-weight: 500;
        }

        .legend-dot {
            width: 10px;
            height: 10px;
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
            font-size: 13px;
            color: var(--muted);
            font-weight: 500;
        }

        .sla-val {
            font-size: 13px;
            font-family: var(--font-mono);
            color: var(--accent-gold);
            font-weight: 600;
        }

        .sla-bar-wrap {
            height: 6px;
            border-radius: 100px;
            background: rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }

        .sla-bar {
            height: 100%;
            border-radius: 100px;
            width: 0;
            transition: width 1.4s cubic-bezier(0.22, 1, 0.36, 1);
            background: linear-gradient(90deg, var(--accent-gold), var(--accent));
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
            font-size: 12px;
            color: var(--accent-gold);
            letter-spacing: 0.22em;
            text-transform: uppercase;
            margin-bottom: 28px;
            font-weight: 600;
        }

        .feature-display-title {
            font-family: var(--font-display);
            font-size: clamp(48px, 6vw, 96px);
            font-weight: 300;
            line-height: 1.0;
            letter-spacing: -0.015em;
            margin-bottom: 32px;
            color: var(--white);
            text-shadow: 0 2px 8px rgba(15, 20, 25, 0.06);
        }

        .feature-display-title em {
            font-style: italic;
            color: var(--accent-gold);
        }

        .feature-desc {
            font-size: 17px;
            font-weight: 400;
            line-height: 1.75;
            color: var(--muted);
            max-width: 500px;
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
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.7), rgba(240, 244, 255, 0.5));
            border: 1px solid rgba(26, 115, 232, 0.1);
            border-radius: 28px;
            backdrop-filter: blur(12px);
            box-shadow: 0 24px 60px rgba(26, 115, 232, 0.06), inset 0 1px 0 rgba(255, 255, 255, 0.8);
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
            background: rgba(26, 115, 232, 0.1);
            z-index: -1;
            transition: all 0.3s;
        }

        .flow-step.active:not(:nth-child(5n))::after {
            background: var(--accent);
            box-shadow: 0 0 12px rgba(26, 115, 232, 0.4);
            animation: linePulse 2s infinite;
        }

        @keyframes linePulse {
            0% {
                opacity: 0.4;
            }

            50% {
                opacity: 1;
            }

            100% {
                opacity: 0.4;
            }
        }

        /* U-Turn Connector from 5 to 6 */
        .flow-step:nth-child(5)::before {
            content: '';
            position: absolute;
            top: 23px;
            left: calc(50% + 23px);
            width: 30px;
            height: calc(100% + 40px);
            border-top: 2px solid rgba(26, 115, 232, 0.1);
            border-right: 2px solid rgba(26, 115, 232, 0.1);
            border-bottom: 2px solid rgba(26, 115, 232, 0.1);
            border-radius: 0 18px 18px 0;
            z-index: -1;
            transition: all 0.3s;
        }

        .flow-step.active:nth-child(5)::before {
            border-color: var(--accent);
            box-shadow: 6px 0 16px rgba(26, 115, 232, 0.1), inset -2px 0 6px rgba(26, 115, 232, 0.08);
        }

        .step-node {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            background: linear-gradient(145deg, #f0f4ff, #e8eeff);
            border: 1px solid rgba(26, 115, 232, 0.12);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.8), 0 4px 12px rgba(0, 0, 0, 0.06);
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
            background: #fff;
            border: 1px solid rgba(26, 115, 232, 0.2);
            border-radius: 6px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--muted);
            font-weight: 700;
            z-index: 3;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .flow-step.active .step-node {
            background: linear-gradient(145deg, rgba(26, 115, 232, 0.15), rgba(26, 115, 232, 0.05));
            border-color: rgba(26, 115, 232, 0.4);
            color: var(--accent);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.5), 0 12px 30px rgba(26, 115, 232, 0.15);
            transform: scale(1.15) translateY(-2px);
        }

        .flow-step.active .step-num {
            background: var(--accent);
            color: #fff;
            border-color: #4A90E8;
            box-shadow: 0 4px 10px rgba(26, 115, 232, 0.3);
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

        /* Sleek Horizontal Progress Tracker Bar */
        .features-progress-bar-wrap {
            position: absolute;
            bottom: 48px;
            left: 10vw;
            right: 10vw;
            height: 3px;
            background: rgba(26, 115, 232, 0.08);
            border-radius: 100px;
            z-index: 100;
            overflow: hidden;
            border: 1.5px solid rgba(26, 115, 232, 0.05);
            backdrop-filter: blur(5px);
        }

        .features-progress-bar-inner {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, var(--accent-gold), var(--accent));
            border-radius: 100px;
            box-shadow: 0 0 12px rgba(26, 115, 232, 0.4);
            transition: width 0.1s ease-out;
        }

        /* Ambient Glow Behind Feature Slides */
        .feature-slide::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 700px;
            height: 700px;
            background: radial-gradient(circle, rgba(26, 115, 232, 0.03) 0%, transparent 70%);
            z-index: -1;
            pointer-events: none;
        }

        .feature-slide.wide::before {
            background: radial-gradient(circle, rgba(200, 169, 110, 0.04) 0%, transparent 70%);
        }

        /* 3D Depth Card Spotlight Halo styling */
        .tracking-flow,
        .doc-preview,
        .archive-item {
            position: relative;
            overflow: hidden;
            transition: transform 0.5s cubic-bezier(0.23, 1, 0.32, 1), box-shadow 0.5s ease;
        }

        .tracking-flow::after,
        .doc-preview::after,
        .archive-item::after {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at var(--x, 50%) var(--y, 50%), rgba(200, 169, 110, 0.1) 0%, transparent 60%);
            opacity: 0;
            transition: opacity 0.4s ease;
            z-index: 0;
            pointer-events: none;
        }

        .tracking-flow:hover::after,
        .doc-preview:hover::after,
        .archive-item:hover::after {
            opacity: 1;
        }

        /* Extra hover scales */
        .tracking-flow:hover {
            transform: translateY(-4px) scale(1.01);
            box-shadow: 0 32px 80px rgba(26, 115, 232, 0.1), inset 0 1px 0 rgba(255, 255, 255, 0.9);
        }

        .doc-preview:hover {
            transform: rotate(-3deg) translateY(-8px) scale(1.03) !important;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.35);
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
            margin-top: 10px;
        }

        .portals-header {
            margin-bottom: 56px;
        }

        .portals-label {
            display: flex;
            align-items: center;
            gap: 12px;
            font-family: var(--font-mono);
            font-size: 12px;
            color: var(--accent-gold);
            letter-spacing: 0.2em;
            text-transform: uppercase;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .portals-label::before {
            content: '05';
            color: var(--accent-gold);
            opacity: 0.6;
        }

        .portals-title {
            font-family: var(--font-display);
            font-size: clamp(32px, 4vw, 58px);
            font-weight: 300;
            line-height: 1.15;
            color: var(--white);
            text-shadow: 0 2px 8px rgba(15, 20, 25, 0.06);
        }

        .portals-title em {
            font-style: italic;
            color: var(--accent-gold);
        }

        .portals-subtitle {
            font-size: 15px;
            font-weight: 400;
            color: var(--muted);
            margin-top: 16px;
            max-width: 540px;
            line-height: 1.8;
        }

        .portals-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .portal-card {
            background: var(--glass-bg);
            border: 1.5px solid rgba(200, 169, 110, 0.2);
            backdrop-filter: var(--glass-blur);
            border-radius: var(--radius);
            padding: 28px;
            display: flex;
            align-items: center;
            gap: 20px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 12px 40px rgba(200, 169, 110, 0.08);
        }

        .portal-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at var(--x) var(--y), rgba(200, 169, 110, 0.12) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.4s;
        }

        .portal-card:hover {
            border-color: rgba(200, 169, 110, 0.35);
            transform: translateY(-6px);
            background: rgba(200, 169, 110, 0.05);
            box-shadow: 0 24px 60px rgba(200, 169, 110, 0.15);
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
            font-size: 15px;
            font-weight: 600;
            color: var(--white);
            margin-bottom: 5px;
        }

        .portal-desc {
            font-size: 13px;
            color: var(--muted);
            font-weight: 400;
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
            color: var(--accent-gold);
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
            font-size: 12px;
            color: var(--accent-gold);
            letter-spacing: 0.2em;
            text-transform: uppercase;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .footer-desc {
            font-size: 14px;
            font-weight: 400;
            color: var(--muted);
            line-height: 1.8;
            max-width: 300px;
            margin-bottom: 28px;
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
            font-size: 11px;
            color: var(--accent-gold);
            letter-spacing: 0.2em;
            text-transform: uppercase;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .footer-links {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 11px;
        }

        .footer-links a {
            font-size: 14px;
            font-weight: 400;
            color: var(--muted);
            text-decoration: none;
            transition: color .25s;
        }

        .footer-links a:hover {
            color: var(--white);
        }

        .footer-cta-band {
            background: linear-gradient(135deg, rgba(200, 169, 110, 0.12) 0%, rgba(26, 115, 232, 0.08) 100%);
            border: 1.5px solid rgba(200, 169, 110, 0.25);
            border-radius: var(--radius);
            padding: 44px 48px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 32px;
            margin-bottom: 56px;
            box-shadow: 0 16px 48px rgba(200, 169, 110, 0.1);
        }

        .footer-cta-text {
            font-family: var(--font-display);
            font-size: 32px;
            font-weight: 300;
            line-height: 1.2;
            color: var(--white);
        }

        .footer-cta-text em {
            font-style: italic;
            color: var(--accent-gold);
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
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0.6));
            border: 1.5px solid rgba(200, 169, 110, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--muted);
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.23, 1, 0.32, 1);
            font-size: 16px;
            padding: 8px;
            box-shadow: 0 8px 24px rgba(200, 169, 110, 0.1);
        }

        .social-link img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .social-link:hover {
            transform: translateY(-5px);
            border-color: var(--accent);
            color: var(--white);
            background: linear-gradient(135deg, rgba(26, 115, 232, 0.15), rgba(26, 115, 232, 0.08));
            box-shadow: 0 16px 40px rgba(26, 115, 232, 0.15);
        }

        .social-link.ig:hover {
            color: #E4405F;
            border-color: #E4405F;
            background: rgba(228, 64, 95, 0.15);
            box-shadow: 0 16px 40px rgba(228, 64, 95, 0.15);
        }

        .social-link.mail:hover {
            color: #EA4335;
            border-color: #EA4335;
            background: rgba(234, 67, 53, 0.15);
            box-shadow: 0 16px 40px rgba(234, 67, 53, 0.15);
        }

        .social-link.fb:hover {
            color: #1877F2;
            border-color: #1877F2;
            background: rgba(24, 119, 242, 0.15);
            box-shadow: 0 16px 40px rgba(24, 119, 242, 0.15);
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
            border: 2px solid rgba(26, 115, 232, 0.1);
            background: radial-gradient(circle, rgba(26, 115, 232, 0.05) 0%, transparent 70%);
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
            color: var(--white);
            font-family: var(--font-mono);
            letter-spacing: -1px;
            text-shadow: 0 0 20px rgba(26, 115, 232, 0.2);
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
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(26, 115, 232, 0.1);
            border-radius: 16px;
            padding: 20px;
            transition: all 0.5s ease;
        }

        .archive-item:nth-child(1) {
            transform: translate(0, 0) rotate(-10deg);
            z-index: 3;
            background: rgba(255, 255, 255, 0.8);
            border-color: rgba(26, 115, 232, 0.15);
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
            background: rgba(26, 115, 232, 0.1);
            border-radius: 3px;
            margin-bottom: 10px;
        }

        /* Tech Stack Section (Welcome Style) */
        .dev-section {
            padding: 120px 0 80px;
            position: relative;
            z-index: 10;
            overflow: hidden;
            background: rgba(26, 115, 232, 0.02);
        }

        .dev-header-minimal {
            text-align: center;
            margin-bottom: 60px;
            padding: 0 24px;
        }

        .dev-header-minimal h3 {
            font-family: var(--font-display);
            font-size: clamp(32px, 4vw, 48px);
            color: var(--white);
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

        .tm-row.go-right {
            animation: scrollR 60s linear infinite;
        }

        .tm-row.go-left {
            animation: scrollL 65s linear infinite;
        }

        .tm-row.go-right2 {
            animation: scrollR 55s linear infinite;
        }

        @keyframes scrollR {
            from {
                transform: translateX(-50%);
            }

            to {
                transform: translateX(0);
            }
        }

        @keyframes scrollL {
            from {
                transform: translateX(0);
            }

            to {
                transform: translateX(-50%);
            }
        }

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

        /* Upgraded 3D Floating Background Pills */
        .spiral-bg-3d-elements {
            position: absolute;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
            z-index: 1;
            perspective: 1000px;
            transform-style: preserve-3d;
        }

        .spiral-bg-pill {
            position: absolute;
            left: 50%;
            top: 50%;
            background: linear-gradient(135deg, rgba(6, 182, 212, 0.16), rgba(200, 169, 110, 0.16));
            border: 1.5px solid rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(3px);
            border-radius: 100px;
            pointer-events: none;
            will-change: transform, opacity;
            box-shadow: 0 8px 32px rgba(6, 182, 212, 0.04);
            transform-style: preserve-3d;
        }
        
        body.light-mode .spiral-bg-pill {
            background: linear-gradient(135deg, rgba(26, 115, 232, 0.1), rgba(93, 202, 165, 0.1));
            border-color: rgba(0, 0, 0, 0.04);
            box-shadow: 0 8px 32px rgba(26, 115, 232, 0.04);
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
            background: rgba(150, 150, 150, 0.6);
            border: 1px solid rgba(150, 150, 150, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 100px;
            padding: 10px 22px;
            font-family: var(--font-mono);
            font-size: 12px;
            color: #ffffff;
            letter-spacing: .12em;
            text-transform: uppercase;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 8px;
            transform: translate(-50%, -50%);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
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
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            font-size: 14px;
            font-weight: 600;
            color: rgba(26, 29, 38, 0.7);
            white-space: nowrap;
            transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .tech-chip:hover {
            border-color: var(--accent);
            background: rgba(26, 115, 232, 0.08);
            transform: translateY(-5px) rotate(2deg);
            color: var(--accent);
            box-shadow: 0 10px 20px rgba(26, 115, 232, 0.1);
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
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid var(--glass-border);
            color: var(--white);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
        }

        .btn-dev-social:hover {
            background: var(--accent);
            color: #fff;
            border-color: var(--accent);
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

        /* ─── NEW RESPONSIVE SYSTEM ─── */
        @media (max-width: 1200px) {
            .hero-badge {
                right: 20px;
                scale: 0.8;
            }

            .hero-float-cards {
                right: 180px;
                scale: 0.9;
            }
        }

        @media (max-width: 992px) {
            nav {
                padding: 16px 30px;
            }

            #hero {
                padding: 140px 30px 80px;
            }

            .hero-badge,
            .hero-float-cards {
                display: none;
            }

            .hero-title {
                font-size: clamp(48px, 10vw, 72px);
            }

            #about,
            #stats,
            #charts,
            #portals,
            #footer {
                padding-left: 30px;
                padding-right: 30px;
            }

            .about-cards {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 20px;
            }

            .portals-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 768px) {

            .nav-center,
            .nav-auth {
                display: none;
            }

            .nav-mobile-toggle {
                display: flex !important;
            }

            #hero {
                align-items: center;
                text-align: center;
                min-height: 90vh;
            }

            .hero-eyebrow {
                justify-content: center;
            }

            .hero-subtitle {
                margin-left: auto;
                margin-right: auto;
                font-size: 14px;
            }

            .hero-cta {
                flex-direction: column;
                width: 100%;
                max-width: 320px;
                margin: 0 auto;
            }

            .btn-primary,
            .btn-secondary {
                width: 100%;
                justify-content: center;
            }

            .cta-divider {
                display: none;
            }

            #about {
                grid-template-columns: 1fr;
                gap: 50px;
                padding: 100px 24px;
            }

            .about-cards {
                grid-template-columns: 1fr;
            }

            .about-title {
                font-size: 38px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .stat-card {
                padding: 30px;
            }

            .charts-grid,
            .charts-grid-bottom {
                grid-template-columns: 1fr;
            }

            .chart-card {
                padding: 24px;
            }

            .portals-grid {
                grid-template-columns: 1fr;
            }

            .portal-card {
                padding: 30px 24px;
            }

            .spiral-label-center h2 {
                font-size: 34px;
            }

            .spiral-pill {
                font-size: 10px;
                padding: 8px 16px;
            }

            #features-scroller {
                overflow: visible;
            }

            .features-sticky-wrap {
                position: relative !important;
                height: auto !important;
                overflow: visible !important;
                display: block !important;
            }

            .features-horizontal-track {
                display: block !important;
                width: 100% !important;
                transform: none !important;
            }

            .feature-slide {
                width: 100% !important;
                height: auto !important;
                min-height: 80vh;
                padding: 80px 24px !important;
                display: flex !important;
                flex-direction: column;
                justify-content: center;
                border-bottom: 1px solid var(--glass-border);
            }

            .feature-slide.first-slide,
            .feature-slide.last-slide {
                width: 100% !important;
            }

            .feature-visual-wrap {
                flex-direction: column !important;
                gap: 40px;
                text-align: center;
                display: flex !important;
            }

            .feature-display-title {
                font-size: clamp(32px, 8vw, 42px);
                line-height: 1.2;
            }

            .tracking-flow {
                padding: 15px;
                border-radius: 20px;
                margin-top: 30px;
            }

            .flow-stepper {
                grid-template-columns: repeat(3, 1fr);
                gap: 20px 10px;
            }

            .flow-step:not(:nth-child(5n))::after,
            .flow-step:nth-child(5)::before {
                display: none;
            }

            .footer-grid {
                grid-template-columns: 1fr;
                gap: 50px;
                text-align: center;
                padding-top: 40px;
            }

            .footer-brand {
                margin: 0 auto 20px;
                font-size: 24px;
            }

            .footer-links {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 15px 25px;
            }

            .footer-links li {
                margin-bottom: 0;
            }

            .footer-socials {
                justify-content: center;
                margin-top: 20px;
                gap: 20px;
            }

            .footer-cta-band {
                flex-direction: column;
                text-align: center;
                gap: 25px;
                padding: 40px 20px;
                align-items: center;
            }

            .footer-cta-text {
                font-size: clamp(22px, 7vw, 28px);
                line-height: 1.3;
                max-width: 100%;
                margin-bottom: 10px;
            }

            .footer-cta-btns {
                width: 100%;
                justify-content: center;
                gap: 12px;
                flex-wrap: wrap;
            }

            .btn-primary,
            .btn-secondary {
                flex: 1;
                min-width: 140px;
                justify-content: center;
            }

            .tm-label {
                font-size: 8px;
                letter-spacing: 0.15em;
                padding: 0 15px;
            }

            .footer-bottom {
                flex-direction: column;
                gap: 15px;
                text-align: center;
                padding: 30px 20px;
            }

            .dev-header-minimal h3 {
                font-size: 28px;
            }

            .social-links-dev {
                flex-direction: column;
                align-items: center;
            }

            .btn-dev-social {
                width: 100%;
                justify-content: center;
            }
        }

        /* Mobile Menu Overlay */
        #mobile-menu {
            position: fixed;
            inset: 0;
            background: rgba(250, 251, 255, 0.98);
            z-index: 999;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 40px;
            gap: 25px;
            transform: translateX(100%);
            transition: transform 0.6s cubic-bezier(0.23, 1, 0.32, 1);
        }

        #mobile-menu.open {
            transform: translateX(0);
        }

        .mobile-link {
            font-family: var(--font-display);
            font-size: 42px;
            color: var(--white);
            text-decoration: none;
            font-weight: 300;
            opacity: 0;
            transform: translateX(30px);
            transition: all 0.5s ease;
        }

        #mobile-menu.open .mobile-link {
            opacity: 1;
            transform: translateX(0);
        }

        .nav-mobile-toggle {
            display: none;
            width: 44px;
            height: 44px;
            align-items: center;
            justify-content: center;
            background: rgba(26, 115, 232, 0.06);
            border: 1px solid rgba(26, 115, 232, 0.12);
            border-radius: 12px;
            cursor: pointer;
            z-index: 1000;
            position: relative;
        }

        .hamburger {
            width: 20px;
            height: 1.5px;
            background: var(--accent);
            position: relative;
            transition: all 0.3s;
        }

        .hamburger::before,
        .hamburger::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: var(--accent);
            transition: all 0.3s;
        }

        .hamburger::before {
            top: -7px;
            left: 0;
        }

        .hamburger::after {
            bottom: -7px;
            left: 0;
        }

        .nav-mobile-toggle.active .hamburger {
            background: transparent;
        }

        .nav-mobile-toggle.active .hamburger::before {
            transform: rotate(45deg);
            top: 0;
        }

        .nav-mobile-toggle.active .hamburger::after {
            transform: rotate(-45deg);
            bottom: 0;
        }

        /* ─── CUSTOM CURSOR ─── */
        :root {
            --cursor-color: #1A73E8;
        }

        body {
            cursor: none;
        }

        .custom-cursor {
            position: fixed;
            width: 24px;
            height: 24px;
            border: 2.5px solid var(--cursor-color);
            border-radius: 50%;
            pointer-events: none;
            z-index: 10000;
            left: -12px;
            top: -12px;
            will-change: transform;
            box-shadow: 0 0 12px rgba(26, 115, 232, 0.4), inset 0 0 6px rgba(26, 115, 232, 0.2);
        }

        .custom-cursor::before {
            content: '';
            position: absolute;
            width: 6px;
            height: 6px;
            background: var(--cursor-color);
            border-radius: 50%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            box-shadow: 0 0 8px var(--cursor-color);
        }

        .custom-cursor.active {
            width: 32px;
            height: 32px;
            left: -16px;
            top: -16px;
            border-width: 3px;
            box-shadow: 0 0 20px rgba(26, 115, 232, 0.8), inset 0 0 10px rgba(26, 115, 232, 0.5);
        }

        /* Cursor color variants */
        body.cursor-blue {
            --cursor-color: #1A73E8;
        }

        body.cursor-black {
            --cursor-color: #0F1419;
        }

        body.cursor-brown {
            --cursor-color: #8B6F47;
        }

        /* ─── TEXT ANIMATIONS ─── */
        .text-animate {
            overflow: hidden;
            display: inline-block;
        }

        .text-animate-word {
            display: inline-block;
            margin: 0 4px;
            opacity: 0;
            transform: translateY(40px);
            will-change: opacity, transform;
        }

        /* ─── TYPING ANIMATION ─── */
        .typing-container {
            display: inline-block;
            position: relative;
        }

        .word-span {
            display: inline-block;
            opacity: 0;
            filter: blur(8px);
            transform: translateY(15px);
            will-change: opacity, transform, filter;
        }

        .typing-cursor {
            display: inline-block;
            width: 3px;
            height: 1.1em;
            background: var(--accent);
            margin-left: 4px;
            vertical-align: middle;
            border-radius: 4px;
            box-shadow: 0 0 10px var(--accent);
            opacity: 0;
        }

        /* Force default cursor to be hidden globally on all elements to ensure custom cursor is used */
        *,
        *::before,
        *::after,
        a,
        button,
        select,
        input,
        textarea,
        [role="button"] {
            cursor: none !important;
        }
    </style>
</head>

<body>

    {{-- CUSTOM CURSOR --}}
    <div class="custom-cursor" id="customCursor"></div>

    {{-- MOBILE MENU --}}
    <div id="mobile-menu">
        <a href="{{ url('/?home=1') }}" class="mobile-link" style="transition-delay: 0.1s;">Beranda</a>
        <a href="#about" class="mobile-link" style="transition-delay: 0.15s;">Tentang</a>
        <a href="#stats" class="mobile-link" style="transition-delay: 0.2s;">Statistik</a>
        <a href="#charts" class="mobile-link" style="transition-delay: 0.25s;">Grafik</a>
        <a href="#portals" class="mobile-link" style="transition-delay: 0.3s;">Portal</a>
        <a href="#features-scroller" class="mobile-link" style="transition-delay: 0.35s;">Fitur</a>
        <a href="{{ route('login') }}" class="mobile-link"
            style="color: var(--accent); margin-top: 20px; transition-delay: 0.4s;">Masuk</a>
    </div>
    <div id="scroll-bar"></div>
    <canvas id="particles-canvas"></canvas>
    <div class="bg-mesh"></div>
    <div class="bg-grid"></div>
    <div class="bg-orb bg-orb-1"></div>
    <div class="bg-orb bg-orb-2"></div>
    <div class="bg-orb bg-orb-3"></div>

    {{-- THREE.JS 3D CANVAS --}}
    <canvas id="threejs-canvas"></canvas>

    {{-- NAVBAR --}}
    <nav id="navbar">
        <a href="#" class="nav-logo" style="display: flex; align-items: center;">
            <img src="{{ asset('images/BP_SUML2.png') }}" alt="BPSUML"
                style="height: 36px; width: auto; object-fit: contain; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));">
        </a>
        <ul class="nav-center">
            <li><a href="{{ url('/?home=1') }}">Beranda</a></li>
            <li><a href="#about">Tentang</a></li>
            <li><a href="#stats">Statistik</a></li>
            <li><a href="#spiral-section">Jenis</a></li>
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
                <a href="{{ route('login') }}" class="nav-btn-login">Sign In</a>
                <a href="{{ route('register') }}" class="nav-btn-register">Sign Up</a>
            @endauth
        </div>

        <button class="nav-mobile-toggle" id="menu-toggle">
            <span class="hamburger"></span>
        </button>
    </nav>

    {{-- HERO --}}
    <section id="hero">
        <div class="hero-eyebrow">
            <span class="hero-eyebrow-line"></span>
            Sistem Persuratan Digital
        </div>
        <h1 class="hero-title" id="hero-title-main">
            <span class="line">Balai Pengelolaan</span>
            <span class="line"><em>Standar Ukuran</em></span>
            <span class="line">Metrologi Legal</span>
        </h1>
        <p class="hero-subtitle" id="hero-subtitle-main">
            Sistem Monitoring dan Pengelolaan Persuratan Balai Pengelolaan Standar Ukuran Metrologi Legal — Efisien,
            Efektif, Sistematis Serta Mudah Diakses Kapan Saja.
        </p>
        <div class="hero-cta">
            <a href="{{ route('login') }}" class="btn-primary">
                <span class="btn-primary-liquid"></span>
                <span class="btn-text-inner" data-hover="Masuk Ke Web">
                    <span>Masuk Ke Web</span>
                </span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7" />
                </svg>
            </a>
            <a href="{{ route('register') }}" class="btn-secondary">
                <span class="btn-secondary-liquid"></span>
                <span class="btn-text-inner" data-hover="Daftar Akun">
                    <span>Daftar Akun</span>
                </span>
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
                $items = ['Surat Masuk', 'Surat Keluar', 'Chart Data', 'Nota Dinas', 'Pengelolaan Surat', 'Pengajuan Surat', 'Notifikasi Realtime', 'Template Surat', 'Dokumen Teknis', 'Status Surat', 'Tracking Surat', 'Surat Revisi'];
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
            <h2 class="about-title">
                <span class="text-animate">
                    <span class="text-animate-word">Manajemen</span>
                    <span class="text-animate-word">Persuratan</span>
                    <span class="text-animate-word">Digital</span>
                    <span class="text-animate-word">Untuk</span>
                    <span class="text-animate-word"><em>BPSUML</em></span>
                </span>
            </h2>
            <p class="about-body">Balai Pengelolaan Standar Ukuran Metrologi Legal (BPSUML) merupakan unit pelaksana
                teknis di bawah Direktorat Metrologi, Kementerian Perdagangan RI, yang bertugas menyelenggarakan
                Pengelolaan, kalibrasi, dan tera ulang alat ukur.</p>
            <p class="about-body">Sistem manajemen persuratan digital ini hadir untuk mendukung tata kelola administrasi
                yang
                transparan, akuntabel, dan efisien dalam lingkungan pemerintahan yang modern.</p>
        </div>
        <div class="about-cards">
            <div class="about-card"><svg class="about-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.5">
                    <path
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <div class="about-card-title">Sistem Persuratan Digital</div>
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
    <section id="stats" style="position: relative; overflow: hidden;">
        <div
            style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 600px; height: 300px; background: radial-gradient(ellipse, rgba(200, 169, 110, 0.06) 0%, transparent 70%); z-index: 0; pointer-events: none;">
        </div>
        <div class="stats-header" style="position: relative; z-index: 2;">
            <div class="stats-label">Statistik</div>
            <h2 class="stats-title">Data <em>Surat</em> Terkini</h2>
        </div>
        <div class="stats-grid" style="position: relative; z-index: 2;">
            <div class="stat-card">
                <span class="stat-type">Masuk</span>
                <div class="stat-icon-wrapper">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="stat-icon">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                </div>
                <div class="stat-number" data-target="{{ $totalSuratMasuk }}">0</div>
                <div class="stat-label">Total Surat<br> Masuk</div>
                <span class="stat-trend positive">↑ 12% Minggu Ini</span>
            </div>
            <div class="stat-card">
                <span class="stat-type">Selesai</span>
                <div class="stat-icon-wrapper">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="stat-icon">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="stat-number" data-target="{{ $totalSuratKeluar }}">0</div>
                <div class="stat-label">Total Surat<br> Selesai</div>
                <span class="stat-trend positive">SLA 100% On-Time</span>
            </div>
            <div class="stat-card">
                <span class="stat-type">Aktif</span>
                <div class="stat-icon-wrapper">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="stat-icon">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A11.978 11.978 0 0112 20.25a11.978 11.978 0 01-3-1.013v-.11M8.284 15.633a4.125 4.125 0 00-7.533 2.493 9.337 9.337 0 004.117.953 9.38 9.38 0 002.625-.372M21 7.5a3 3 0 11-6 0 3 3 0 016 0zM7.5 7.5a3 3 0 11-6 0 3 3 0 016 0zM12 10.5a3 3 0 110-6 3 3 0 010 6z" />
                    </svg>
                </div>
                <div class="stat-number" data-target="{{ $totalPengguna }}">0</div>
                <div class="stat-label">Pengguna<br>Terdaftar</div>
                <span class="stat-trend neutral">Pegawai Aktif</span>
            </div>
            <div class="stat-card">
                <span class="stat-type">Terarsip</span>
                <div class="stat-icon-wrapper">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="stat-icon">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                    </svg>
                </div>
                <div class="stat-number" data-target="{{ $totalDokumenTerarsip }}">0</div>
                <div class="stat-label">Dokumen<br>Terarsip</div>
                <span class="stat-trend positive">Terlindungi Aman</span>
            </div>
        </div>
    </section>

    <!-- 3D SPIRAL SCROLL SECTION -->
    <section id="spiral-section">
        <div class="spiral-sticky">
            <div class="spiral-bg-gradient"></div>
            <div class="spiral-bg-3d-elements" id="spiral-bg-3d"></div>
            <div class="spiral-stage" id="spiral-stage">
                <div class="spiral-label-center" id="spiral-center">
                    <div class="slc-num">06 — Fitur Unggulan</div>
                    <h2>Scroll untuk<br><em>Jelajahi Fitur</em></h2>
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
            <h2 class="charts-title">
                <span class="text-animate">
                    <span class="text-animate-word">Grafik</span>
                    <span class="text-animate-word">&</span>
                    <span class="text-animate-word"><em>Statistik</em></span>
                    <span class="text-animate-word">Sistem</span>
                </span>
            </h2>
            <p class="charts-subtitle">Visualisasi data surat, kepatuhan SLA, distribusi jenis dokumen, dan tren bulanan
                secara real-time.</p>
        </div>
        <div class="charts-grid">
            <div class="chart-card">
                <div class="chart-card-header">
                    <div>
                        <div class="chart-card-title">Tren Surat & Kepatuhan SLA</div>
                        <div class="chart-card-sub">6 bulan terakhir · mixed chart</div>
                    </div> <span class="chart-badge {{ $growth >= 0 ? 'up' : 'down' }}">
                        {{ $growth >= 0 ? '↑' : '↓' }} {{ abs($growth) }}%
                    </span>
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
                    </div> <span class="chart-badge">{{ count($doughnutLabels) }} jenis</span>
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
                $portals = [
                    ['name' => 'SIMET', 'desc' => 'Sistem Informasi Metrologi', 'img' => 'logo.png', 'url' => 'https://metrologi.kemendag.go.id/'],
                    ['name' => 'KEMENDAG', 'desc' => 'Kementerian Perdagangan', 'img' => 'kemendag.png', 'url' => 'https://www.kemendag.go.id/'],
                    ['name' => 'SPBE', 'desc' => 'Informasi Aplikasi SISWASPK', 'img' => 'logo.png', 'url' => 'https://simpktn.kemendag.go.id/index.php/siswaspk'],
                    ['name' => 'PPID', 'desc' => 'Informasi, Pelaporan & Dokumentasi', 'img' => 'kemendag.png', 'url' => 'https://metrologi.kemendag.go.id/pelaporan_ttu/web/home'],
                    ['name' => 'ASPIRASI', 'desc' => 'Layanan Pengaduan Online', 'img' => 'logo.png', 'url' => route('user.aspirasi.index')],
                    ['name' => 'About BPSUML', 'desc' => 'Sistem BPSUML', 'img' => 'kemendag.png', 'url' => 'https://metrologi.kemendag.go.id/master_suml/']
                ];
            @endphp
            @foreach($portals as $p)
                <a href="{{ $p['url'] }}" target="_blank" class="portal-card">
                    <div class="portal-icon"><img src="{{ asset('images/portals/' . $p['img']) }}" alt="{{ $p['name'] }}"
                            onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($p['name']) }}&background=1A73E8&color=FFFFFF'">
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
                            <p class="feature-card-text">Keaslian dokumen terjamin , bisa di scan melalui HandPhone(HP)
                                dan
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
                        <div style="margin-top: 2rem;">
                            <a href="{{ route('login') }}" class="btn-primary">
                                <span class="btn-primary-liquid"></span>
                                <span class="btn-text-inner" data-hover="Buka Dashboard">
                                    <span>Buka Dashboard</span>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SLEEK HORIZONTAL PROGRESS INDICATOR -->
            <div class="features-progress-bar-wrap">
                <div class="features-progress-bar-inner" id="features-progress-bar"></div>
            </div>
        </div>
    </section>

    {{-- DEVELOPER SECTION (Repositioned & Redesigned) --}}
    <section id="developer" class="dev-section">
        <div class="dev-header-minimal" data-reveal>
            <h3>Teknologi <em>Modern</em></h3>
            <p>Dibangun menggunakan stack teknologi mutakhir untuk memastikan performa tinggi, keamanan maksimal, dan
                skalabilitas jangka panjang.</p>
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
            <div class="footer-cta-btns">
                <a href="{{ route('login') }}" class="btn-primary">
                    <span class="btn-primary-liquid"></span>
                    <span class="btn-text-inner" data-hover="Sign In">
                        <span>Sign In</span>
                    </span>
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7" />
                    </svg>
                </a>
                <a href="{{ route('register') }}" class="btn-secondary">
                    <span class="btn-secondary-liquid"></span>
                    <span class="btn-text-inner" data-hover="Sign Up">
                        <span>Sign Up</span>
                    </span>
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 5v14M5 12h14" />
                    </svg>
                </a>
            </div>
        </div>
        <div class="footer-grid">
            <div>
                <div class="footer-brand">BPSUML</div>
                <p class="footer-desc">Balai Pengelolaan Standar Ukuran Metrologi Legal — unit pelaksana teknis
                    Direktorat Metrologi.</p>
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
                    <li><a href="{{ route('login') }}">Sign In</a></li>
                    <li><a href="{{ route('register') }}">Sign Up</a></li>
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
            </span><span class="footer-tagline">Mengukur dengan Adil, Melayani dengan Tepat</span></div>
    </footer>

    <script>


        // Mobile Menu Logic
        const menuToggle = document.getElementById('menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        if (menuToggle && mobileMenu) {
            menuToggle.addEventListener('click', () => {
                menuToggle.classList.toggle('active');
                mobileMenu.classList.toggle('open');
                document.body.style.overflow = mobileMenu.classList.contains('open') ? 'hidden' : '';
            });
            mobileMenu.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', () => {
                    menuToggle.classList.remove('active');
                    mobileMenu.classList.remove('open');
                    document.body.style.overflow = '';
                });
            });
        }

        // Scroll progress + nav
        window.addEventListener('scroll', () => {
            const p = window.scrollY / (document.body.scrollHeight - window.innerHeight) * 100;
            const scrollBar = document.getElementById('scroll-bar');
            if (scrollBar) scrollBar.style.width = p + '%';
            document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 40);
        });

        // Particles Optimized
        (function () {
            const cvs = document.getElementById('particles-canvas');
            const ctx = cvs.getContext('2d');
            function resize() { cvs.width = window.innerWidth; cvs.height = window.innerHeight; }
            resize();
            window.addEventListener('resize', resize);
            const N = window.innerWidth < 768 ? 20 : 40;
            const particles = Array.from({ length: N }, () => ({
                x: Math.random() * window.innerWidth,
                y: Math.random() * window.innerHeight,
                r: Math.random() * 1.2 + 0.3,
                vx: (Math.random() - 0.5) * 0.2,
                vy: (Math.random() - 0.5) * 0.2,
                a: Math.random() * 0.3 + 0.05
            }));
            function drawParticles() {
                ctx.clearRect(0, 0, cvs.width, cvs.height);
                particles.forEach(p => {
                    p.x += p.vx; p.y += p.vy;
                    if (p.x < 0) p.x = cvs.width; if (p.x > cvs.width) p.x = 0;
                    if (p.y < 0) p.y = cvs.height; if (p.y > cvs.height) p.y = 0;
                    ctx.beginPath(); ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                    ctx.fillStyle = `rgba(26,115,232,${p.a})`; ctx.fill();
                });
                if (window.innerWidth > 768) {
                    for (let i = 0; i < N; i++) {
                        for (let j = i + 1; j < N; j++) {
                            const dx = particles[i].x - particles[j].x;
                            const dy = particles[i].y - particles[j].y;
                            const distSq = dx * dx + dy * dy;
                            if (distSq < 10000) {
                                const dist = Math.sqrt(distSq);
                                ctx.beginPath();
                                ctx.moveTo(particles[i].x, particles[i].y);
                                ctx.lineTo(particles[j].x, particles[j].y);
                                ctx.strokeStyle = `rgba(26,115,232,${0.06 * (1 - dist / 100)})`;
                                ctx.lineWidth = 0.4; ctx.stroke();
                            }
                        }
                    }
                }
                requestAnimationFrame(drawParticles);
            }
            drawParticles();
        })();

        /* ─── THREE.JS 3D BACKGROUND WITH INTERACTIVE BOXES ─── */
        (() => {
            const canvas = document.getElementById('threejs-canvas');
            if (!canvas) return;

            let scene, camera, renderer;
            let boxes = [];
            let mouseX = 0, mouseY = 0;
            let targetMouseX = 0, targetMouseY = 0;
            let isAboutInView = false;
            let isPortalsInView = false;
            let canvasVisible = false;

            function initThreeJS() {
                // Scene setup
                scene = new THREE.Scene();
                scene.background = new THREE.Color(0xfafbff);
                scene.fog = new THREE.Fog(0xfafbff, 500, 0.1);

                // Camera - adjusted for better view
                camera = new THREE.PerspectiveCamera(60, window.innerWidth / window.innerHeight, 0.1, 2000);
                camera.position.z = 100;

                // Renderer
                renderer = new THREE.WebGLRenderer({ canvas, alpha: false, antialias: true });
                renderer.setSize(window.innerWidth, window.innerHeight);
                renderer.setPixelRatio(window.devicePixelRatio);
                renderer.setClearColor(0xfafbff, 1);

                // Create grid of boxes
                const gridSize = 6;
                const spacing = 30;
                const startX = -(gridSize / 2) * spacing;
                const startY = -(gridSize / 2) * spacing;

                for (let i = 0; i < gridSize; i++) {
                    for (let j = 0; j < gridSize; j++) {
                        const geometry = new THREE.BoxGeometry(16, 16, 16);
                        const material = new THREE.MeshStandardMaterial({
                            color: 0x1a73e8,
                            emissive: 0x1a73e8,
                            emissiveIntensity: 0.3,
                            metalness: 0.3,
                            roughness: 0.4
                        });
                        const box = new THREE.Mesh(geometry, material);
                        box.position.x = startX + i * spacing;
                        box.position.y = startY + j * spacing;
                        box.position.z = (Math.random() - 0.5) * 60;
                        box.rotation.x = Math.random() * Math.PI;
                        box.rotation.y = Math.random() * Math.PI;

                        box.originalColor = new THREE.Color(0x1a73e8);
                        box.targetColor = new THREE.Color(0x1a73e8);
                        box.distanceToMouse = Infinity;
                        box.velocity = {
                            x: (Math.random() - 0.5) * 0.015,
                            y: (Math.random() - 0.5) * 0.015,
                            z: (Math.random() - 0.5) * 0.015
                        };

                        scene.add(box);
                        boxes.push(box);
                    }
                }

                // Lighting - stronger for visibility
                const ambientLight = new THREE.AmbientLight(0xffffff, 0.8);
                scene.add(ambientLight);

                const directionalLight = new THREE.DirectionalLight(0xffffff, 0.9);
                directionalLight.position.set(100, 100, 100);
                scene.add(directionalLight);

                // Handle window resize
                window.addEventListener('resize', onWindowResize);

                // Mouse tracking
                document.addEventListener('mousemove', onMouseMove);

                // About section scroll trigger
                const aboutSection = document.getElementById('about');
                const portalsSection = document.getElementById('portals');

                if (aboutSection) {
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            isAboutInView = entry.isIntersecting;
                            updateCanvasVisibility();
                        });
                    }, { threshold: 0.5, rootMargin: '-50px' });
                    observer.observe(aboutSection);
                }

                if (portalsSection) {
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            isPortalsInView = entry.isIntersecting;
                            updateCanvasVisibility();
                        });
                    }, { threshold: 0.5, rootMargin: '-50px' });
                    observer.observe(portalsSection);
                }

                // Start animation
                animate();
            }

            function updateCanvasVisibility() {
                const shouldBeVisible = isAboutInView || isPortalsInView;
                if (shouldBeVisible !== canvasVisible) {
                    canvasVisible = shouldBeVisible;
                    canvas.classList.toggle('visible', canvasVisible);
                }
            }

            function onMouseMove(event) {
                targetMouseX = (event.clientX / window.innerWidth) * 2 - 1;
                targetMouseY = -(event.clientY / window.innerHeight) * 2 + 1;
            }

            function onWindowResize() {
                camera.aspect = window.innerWidth / window.innerHeight;
                camera.updateProjectionMatrix();
                renderer.setSize(window.innerWidth, window.innerHeight);
            }

            function animate() {
                requestAnimationFrame(animate);

                // Always update rotation for visual interest
                boxes.forEach(box => {
                    box.rotation.x += box.velocity.x;
                    box.rotation.y += box.velocity.y;
                    box.rotation.z += box.velocity.z;

                    // Interactive effects only when about or portals in view
                    if (isAboutInView || isPortalsInView) {
                        // Calculate distance from mouse in normalized space
                        const boxScreenPos = new THREE.Vector3();
                        boxScreenPos.copy(box.position);
                        boxScreenPos.project(camera);

                        const distance = Math.sqrt(
                            Math.pow(boxScreenPos.x - targetMouseX, 2) +
                            Math.pow(boxScreenPos.y - targetMouseY, 2)
                        );

                        box.distanceToMouse = distance;

                        // Trigger color change when cursor near
                        if (distance < 0.25) {
                            box.targetColor.setHSL(0.11, 0.95, 0.55); // Gold/yellow
                            box.position.z += (Math.random() - 0.5) * 1.5;
                            box.material.emissiveIntensity = 0.8;
                        } else {
                            box.targetColor.copy(box.originalColor);
                            box.material.emissiveIntensity = 0.3;
                        }

                        // Smooth color transition
                        box.material.color.lerp(box.targetColor, 0.08);
                        box.material.emissive.lerp(box.targetColor, 0.08);

                        // Scale based on distance
                        const scale = 1 + Math.max(0, 0.25 - distance) * 0.4;
                        box.scale.set(scale, scale, scale);
                    } else {
                        // Neutral state when not in view
                        box.scale.set(1, 1, 1);
                        box.material.emissiveIntensity = 0.2;
                    }
                });

                renderer.render(scene, camera);
            }

            // Initialize when Three.js is loaded
            if (window.THREE) {
                initThreeJS();
            }
        })();

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

        // Magnetic Effect Optimized with quickTo
        function initMagnetic() {
            const magnetics = document.querySelectorAll('.btn-primary, .btn-secondary, .nav-btn-register, .nav-user-pill');
            magnetics.forEach(el => {
                const xTo = gsap.quickTo(el, "x", { duration: 0.4, ease: "power2.out" });
                const yTo = gsap.quickTo(el, "y", { duration: 0.4, ease: "power2.out" });

                el.addEventListener('mousemove', function (e) {
                    const rect = this.getBoundingClientRect();
                    const x = e.clientX - rect.left - rect.width / 2;
                    const y = e.clientY - rect.top - rect.height / 2;
                    xTo(x * 0.3);
                    yTo(y * 0.3);
                });
                el.addEventListener('mouseleave', function (e) {
                    xTo(0);
                    yTo(0);
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
            y: 120, // Reduced from 200 for subtler effect
            opacity: 0.85, // Maintain more brightness
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

        // ─── TYPING ANIMATION LOGIC ───
        const splitIntoWords = (elementId) => {
            const el = document.getElementById(elementId);
            if (!el) return;

            // For hero-title, we need to handle nested .line spans
            if (elementId === 'hero-title-main') {
                const lines = el.querySelectorAll('.line');
                lines.forEach(line => {
                    const text = line.innerText;
                    const words = text.split(' ');
                    const isItalic = line.querySelector('em');

                    let html = '';
                    words.forEach(word => {
                        html += `<span class="word-span">${word}</span> `;
                    });

                    if (isItalic) {
                        line.innerHTML = `<em>${html}</em>`;
                    } else {
                        line.innerHTML = html;
                    }
                });
            } else {
                const text = el.innerText;
                const words = text.split(' ');
                el.innerHTML = words.map(word => `<span class="word-span">${word}</span>`).join(' ');
            }
        };

        splitIntoWords('hero-title-main');
        splitIntoWords('hero-subtitle-main');

        // Typing Timeline
        const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });

        // Initial state for cursor
        gsap.set('.hero-title, .hero-subtitle', { opacity: 1 });

        tl.fromTo('.hero-eyebrow', { opacity: 0, x: -20 }, { opacity: 1, x: 0, duration: 0.8, delay: 0.2 })
            .fromTo('.hero-eyebrow-line', { width: 0 }, { width: 28, duration: 0.8 }, "-=0.6")
            .to('#hero-title-main .word-span', {
                opacity: 1,
                y: 0,
                filter: 'blur(0px)',
                duration: 0.5,
                stagger: 0.08,
                ease: "back.out(1.7)",
                delay: 0.5
            })
            .to('#hero-subtitle-main .word-span', {
                opacity: 1,
                y: 0,
                filter: 'blur(0px)',
                duration: 0.4,
                stagger: 0.04,
                ease: "power2.out"
            }, "-=0.3")
            .to('.hero-cta', { opacity: 1, y: 0, duration: 0.7 }, '-=0.35')
            .to('.hero-float-cards', { opacity: 1, x: 0, duration: 0.8, ease: 'power2.out' }, '-=0.5')
            .to('.hero-badge', { opacity: 1, duration: 0.8 }, '-=0.4')
            .to('.hero-scroll-hint', { opacity: 1, duration: 0.6 }, '-=0.2');

        // ─── ENHANCED ANIMATIONS ───

        // Continuous subtle float on hero float cards
        gsap.to('.hero-float-card', {
            y: -8, duration: 2.5, repeat: -1, yoyo: true,
            ease: 'sine.inOut', stagger: 0.3
        });

        // Ticker shimmer effect
        gsap.fromTo('.ticker-dot',
            { scale: 0.5, opacity: 0.3 },
            { scale: 1.5, opacity: 1, duration: 1.5, repeat: -1, yoyo: true, stagger: { each: 0.1, from: 'random' } }
        );

        // About - enhanced with rotation micro-animation
        gsap.fromTo('.about-card', { opacity: 0, x: 50, rotationY: 8 }, { opacity: 1, x: 0, rotationY: 0, duration: 0.85, stagger: 0.15, scrollTrigger: { trigger: '#about', start: 'top 68%' } });
        gsap.fromTo('.about-left > *', { opacity: 0, x: -35 }, { opacity: 1, x: 0, duration: 0.75, stagger: 0.1, scrollTrigger: { trigger: '#about', start: 'top 68%' } });

        // About card icon animation on hover
        document.querySelectorAll('.about-card').forEach(card => {
            card.addEventListener('mouseenter', () => {
                gsap.to(card.querySelector('.about-card-icon'), { rotation: 10, scale: 1.15, duration: 0.4, ease: 'back.out(1.7)' });
            });
            card.addEventListener('mouseleave', () => {
                gsap.to(card.querySelector('.about-card-icon'), { rotation: 0, scale: 1, duration: 0.3 });
            });
        });

        // Charts - scale up effect
        gsap.fromTo('.chart-card', { opacity: 0, y: 40, scale: 0.95 }, { opacity: 1, y: 0, scale: 1, duration: 0.7, stagger: 0.12, scrollTrigger: { trigger: '#charts', start: 'top 70%' } });
        gsap.fromTo('.charts-header > *', { opacity: 0, y: 25 }, { opacity: 1, y: 0, duration: 0.7, stagger: 0.1, scrollTrigger: { trigger: '#charts', start: 'top 75%' } });

        // Footer - wave entrance
        gsap.fromTo('#footer > *', { opacity: 0, y: 30 }, { opacity: 1, y: 0, duration: 0.7, stagger: 0.13, scrollTrigger: { trigger: '#footer', start: 'top 80%' } });

        // Stat counters with enhanced pulsing glow
        document.querySelectorAll('.stat-number').forEach(el => {
            const target = parseInt(el.dataset.target) || 0;
            ScrollTrigger.create({
                trigger: el, start: 'top 85%', once: true,
                onEnter: () => {
                    anime({ targets: el, innerHTML: [0, target], round: 1, duration: 2000, easing: 'easeOutExpo', update: function (a) { el.innerHTML = Math.round(a.animations[0].currentValue); } });
                    // Pulse glow on complete
                    gsap.fromTo(el, { textShadow: '0 0 0px rgba(26,115,232,0)' }, { textShadow: '0 0 20px rgba(26,115,232,0.3)', duration: 0.8, delay: 1.5, yoyo: true, repeat: 1 });
                }
            });
        });
        gsap.fromTo('.stat-card', { opacity: 0, y: 38, scale: 0.9 }, { opacity: 1, y: 0, scale: 1, duration: 0.6, stagger: 0.1, scrollTrigger: { trigger: '#stats', start: 'top 70%' } });

        // Dev section tech chips wave animation
        gsap.fromTo('.dev-header-minimal > *', { opacity: 0, y: 30 }, { opacity: 1, y: 0, duration: 0.7, stagger: 0.1, scrollTrigger: { trigger: '#developer', start: 'top 75%' } });

        // Scroll-linked background orb movement (continuous)
        gsap.to('.bg-orb-1', { x: 60, y: 40, scrollTrigger: { trigger: 'body', start: 'top top', end: 'bottom bottom', scrub: 2 } });
        gsap.to('.bg-orb-2', { x: -40, y: -60, scrollTrigger: { trigger: 'body', start: 'top top', end: 'bottom bottom', scrub: 3 } });
        gsap.to('.bg-orb-3', { x: 30, y: 80, scrollTrigger: { trigger: 'body', start: 'top top', end: 'bottom bottom', scrub: 1.5 } });

        // ─── 3D SPIRAL SCROLL ANIMATION ───
        const spiralData = [
            { label: 'Surat Masuk', emoji: '📥' },
            { label: 'Surat Selesai', emoji: '📤' },
            { label: 'Chart Data', emoji: '📊' },
            { label: 'Nota Dinas', emoji: '📝' },
            { label: 'Notifikasi', emoji: '🔔' },
            { label: 'Template Surat', emoji: '📋' },
            { label: 'Revisi Surat', emoji: '📨' },
            { label: 'Laporan Tahunan', emoji: '📊' },
            { label: 'Laporan Bulanan', emoji: '📆' },
            { label: 'Permohonan', emoji: '📌' },
            { label: 'QR Verifikasi', emoji: '🔐' },
            { label: '10 Tahap SLA', emoji: '⏱️' },
            { label: 'Aplikasi', emoji: '📱' },
            { label: 'Kontribusi', emoji: '🤝' },

        ];

        const spiralContainer = document.getElementById('spiral-items-container');
        const spiralStage = document.getElementById('spiral-stage');
        const spiralCenter = document.getElementById('spiral-center');
        const spiralProgressEl = document.getElementById('spiral-progress');
        const spiralBg3d = document.getElementById('spiral-bg-3d');
        const bgPillsData = [];

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

        // Initialize 3D Floating Background Pills dynamically
        if (spiralBg3d) {
            const numBgPills = 15;
            for (let i = 0; i < numBgPills; i++) {
                const el = document.createElement('div');
                el.className = 'spiral-bg-pill';
                
                // Randomize sizes and aspect ratios (pill shapes)
                const width = Math.random() * 150 + 90;
                const height = width * (Math.random() * 0.35 + 0.25); // Pill shape
                el.style.width = width + 'px';
                el.style.height = height + 'px';
                
                spiralBg3d.appendChild(el);
                
                // Save random factors for scroll 3D zoom & translation
                bgPillsData.push({
                    el: el,
                    startX: Math.random() * window.innerWidth,
                    startY: Math.random() * window.innerHeight,
                    // Starting depth (Z translation)
                    startZ: Math.random() * -800 - 300, 
                    // Floating directions/speeds
                    speedX: (Math.random() - 0.5) * 350,
                    speedY: (Math.random() - 0.5) * 350,
                    rotX: Math.random() * 360,
                    rotY: Math.random() * 360,
                    rotZ: Math.random() * 360,
                    rotSpeed: (Math.random() - 0.5) * 180
                });
            }
        }

        // Spiral scroll logic
        const spiralSection = document.getElementById('spiral-section');

        function updateSpiral(progress) {
            const n = spiralData.length;
            const totalTurns = 2.5;

            // Animate background 3D pills: they fly forward towards the camera (Z increases, scale grows)
            bgPillsData.forEach(p => {
                const currentZ = p.startZ + (progress * 1200); // Zoom in closer
                const currentX = p.startX + (progress * p.speedX);
                const currentY = p.startY + (progress * p.speedY);
                
                // Calculate scale so they get larger as they fly closer
                const distanceRatio = (currentZ + 1100) / 1400; // Normalized 0 to 1
                
                // Fade in at the start, fade out as they get extremely close
                let opacity = 0.4;
                if (distanceRatio < 0.25) {
                    opacity = (distanceRatio / 0.25) * 0.4;
                } else if (distanceRatio > 0.75) {
                    opacity = ((1 - distanceRatio) / 0.25) * 0.4;
                }

                gsap.set(p.el, {
                    x: currentX - window.innerWidth / 2,
                    y: currentY - window.innerHeight / 2,
                    z: currentZ,
                    rotationX: p.rotX + (progress * p.rotSpeed),
                    rotationY: p.rotY + (progress * p.rotSpeed),
                    rotationZ: p.rotZ + (progress * p.rotSpeed),
                    opacity: Math.max(0, opacity),
                    scale: 0.35 + (distanceRatio * 2.5) // Grows larger!
                });
            });

            spiralData.forEach((d, i) => {
                const el = document.getElementById(`spiral-item-${i}`);
                if (!el) return;

                const baseAngle = (i / n) * Math.PI * 2;
                const currentRotation = progress * totalTurns * Math.PI * 2;
                const angle = baseAngle - currentRotation;

                const isMobile = window.innerWidth < 768;
                const radius = isMobile ? 120 : 280;
                const heightSpread = isMobile ? 100 : 180;

                const x = Math.cos(angle) * radius;
                const yMapped = ((((i / n) - progress * (totalTurns)) % 1) + 1) % 1;
                const y = (yMapped - 0.5) * heightSpread * (isMobile ? 4 : 3);
                const z = Math.sin(angle) * radius;

                const depth = (z + radius) / (radius * 2);
                const scale = 0.6 + depth * 0.7;
                const opacity = 0.15 + depth * 0.85;

                const visible = Math.abs(y) < 500;

                // Use translate3d for hardware acceleration
                el.style.transform = `translate3d(calc(-50% + ${x}px), calc(-50% + ${y}px), 0) scale(${scale})`;
                el.style.opacity = visible ? opacity : 0;
                el.style.zIndex = Math.round(depth * 100);

                const pill = el.querySelector('.spiral-pill');
                const isActive = Math.abs(y) < 60 && depth > 0.6;
                if (pill) {
                    if (isActive) {
                        if (pill.style.borderColor !== 'var(--accent)') {
                            pill.style.borderColor = 'var(--accent)';
                            pill.style.color = 'var(--white)';
                            pill.style.background = 'var(--accent-dim)';
                        }
                    } else {
                        if (pill.style.borderColor !== '') {
                            pill.style.borderColor = '';
                            pill.style.color = '';
                            pill.style.background = '';
                        }
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
                { h: 'Scroll untuk<br><em>Melihat</em>', p: 'Semua fitur sistem persuratan BPSUML' },
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
        const months = {!! json_encode($chartMixedMonths) !!};
        const dataIn = {!! json_encode($chartMixedMasuk) !!};
        const dataOut = {!! json_encode($chartMixedKeluar) !!};
        const dataSLA = {!! json_encode($chartMixedSLA) !!};
        new Chart(document.getElementById('chartMixed'), { data: { labels: months, datasets: [{ type: 'bar', label: 'Surat Masuk', data: dataIn, backgroundColor: 'rgba(26,115,232,0.55)', borderColor: '#1A73E8', borderWidth: 1, borderRadius: 5, order: 2 }, { type: 'bar', label: 'Surat Keluar', data: dataOut, backgroundColor: 'rgba(29,158,117,0.55)', borderColor: '#1D9E75', borderWidth: 1, borderRadius: 5, order: 2 }, { type: 'line', label: 'SLA Rate %', data: dataSLA, borderColor: '#5DCAA5', backgroundColor: 'rgba(93,202,165,0.08)', borderWidth: 2.5, pointBackgroundColor: '#5DCAA5', pointRadius: 4, pointHoverRadius: 6, tension: 0.4, fill: true, yAxisID: 'y2', order: 1 }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false }, tooltip: { mode: 'index', intersect: false, backgroundColor: 'rgba(255,255,255,0.95)', borderColor: 'rgba(26,115,232,0.2)', borderWidth: 1, titleColor: '#1A73E8', bodyColor: 'rgba(26,29,38,0.7)', padding: 10 } }, scales: { x: { grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { color: 'rgba(26,29,38,0.5)', font: { size: 11 } } }, y: { grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { color: 'rgba(26,29,38,0.5)', font: { size: 11 } }, beginAtZero: true }, y2: { position: 'right', min: 70, max: 100, grid: { drawOnChartArea: false }, ticks: { color: 'rgba(93,202,165,0.6)', font: { size: 11 }, callback: v => v + '%' } } } } });
        // Doughnut
        const doughnutLabels = {!! json_encode($doughnutLabels) !!};
        const doughnutDataObj = {!! json_encode($doughnutData) !!};
        const doughnutData = doughnutDataObj.map(d => d.pct);
        const doughnutColors = ['#1A73E8', '#1D9E75', '#378ADD', '#EF9F27', '#D85A30', '#D4537E', '#6366F1'];
        const dlegend = document.getElementById('doughnut-legend');
        doughnutLabels.forEach((l, i) => {
            const d = doughnutDataObj[i];
            dlegend.innerHTML += `<span class="legend-item"><span class="legend-dot" style="background:${doughnutColors[i]}"></span>${l} <strong>(${d.count})</strong> ${d.pct}%</span>`;
        });
        new Chart(document.getElementById('chartDoughnut'), {
            type: 'doughnut',
            data: {
                labels: doughnutLabels,
                datasets: [{
                    data: doughnutData,
                    backgroundColor: doughnutColors.map(c => c + 'CC'),
                    borderColor: doughnutColors,
                    borderWidth: 1.5,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '64%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(255,255,255,0.95)',
                        borderColor: 'rgba(26,115,232,0.2)',
                        borderWidth: 1,
                        titleColor: '#1A73E8',
                        bodyColor: 'rgba(26,29,38,0.7)',
                        padding: 10,
                        callbacks: { label: ctx => ` ${ctx.label}: ${doughnutDataObj[ctx.dataIndex].count} surat (${ctx.raw}%)` }
                    }
                }
            }
        });
        // Stacked Area
        const months12 = {!! json_encode($chartAreaMonths) !!};
        new Chart(document.getElementById('chartArea'), {
            type: 'line',
            data: {
                labels: months12,
                datasets: [
                    { label: 'Masuk', data: {!! json_encode($chartAreaMasuk) !!}, borderColor: '#1A73E8', backgroundColor: 'rgba(26,115,232,0.10)', borderWidth: 2, pointRadius: 3, tension: 0.4, fill: true },
                    { label: 'Keluar', data: {!! json_encode($chartAreaKeluar) !!}, borderColor: '#378ADD', backgroundColor: 'rgba(55,138,221,0.08)', borderWidth: 2, pointRadius: 3, tension: 0.4, fill: true },
                    { label: 'Selesai', data: {!! json_encode($chartAreaSelesai) !!}, borderColor: '#1D9E75', backgroundColor: 'rgba(29,158,117,0.08)', borderWidth: 2, pointRadius: 3, tension: 0.4, fill: true }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: { backgroundColor: 'rgba(255,255,255,0.95)', borderColor: 'rgba(26,115,232,0.2)', borderWidth: 1, titleColor: '#1A73E8', bodyColor: 'rgba(26,29,38,0.7)', padding: 10 }
                },
                scales: {
                    x: { grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { color: 'rgba(26,29,38,0.5)', font: { size: 11 } } },
                    y: { stacked: false, grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { color: 'rgba(26,29,38,0.5)', font: { size: 11 } }, beginAtZero: true }
                }
            }
        });
        // SLA bars
        const slaData = {!! json_encode($slaPerJenis) !!};
        const slaList = document.getElementById('sla-list');
        slaData.forEach((s, i) => {
            slaList.innerHTML += `<div class="sla-item"><div class="sla-row"><span class="sla-name">${s.name}</span><span class="sla-val" id="sla-val-${i}">0%</span></div><div class="sla-bar-wrap"><div class="sla-bar" id="sla-bar-${i}" style="background:${s.color}"></div></div></div>`;
        });
        ScrollTrigger.create({
            trigger: '#sla-list',
            start: 'top 80%',
            once: true,
            onEnter: () => {
                slaData.forEach((s, i) => {
                    anime({ targets: `#sla-bar-${i}`, width: s.pct + '%', duration: 1400, delay: i * 120, easing: 'easeOutExpo' });

                    const obj = { val: 0 };
                    anime({
                        targets: obj,
                        val: s.pct,
                        round: 1,
                        duration: 1400,
                        delay: i * 120,
                        easing: 'easeOutExpo',
                        update: function () {
                            const el = document.getElementById(`sla-val-${i}`);
                            if (el) el.innerHTML = Math.round(obj.val) + '%';
                        }
                    });
                });
            }
        });
        // Portals reveal
        gsap.fromTo('.portal-card', { opacity: 0, scale: 0.95, y: 20 }, { opacity: 1, scale: 1, y: 0, duration: 0.6, stagger: 0.08, scrollTrigger: { trigger: '#portals', start: 'top 75%' } });
        gsap.fromTo('.portals-header > *', { opacity: 0, y: 25 }, { opacity: 1, y: 0, duration: 0.7, stagger: 0.1, scrollTrigger: { trigger: '#portals', start: 'top 80%' } });
        document.querySelectorAll('.portal-card, .stat-card, .tracking-flow, .doc-preview, .archive-item').forEach(card => { card.addEventListener('mousemove', e => { const rect = card.getBoundingClientRect(); const x = e.clientX - rect.left; const y = e.clientY - rect.top; card.style.setProperty('--x', `${x}px`); card.style.setProperty('--y', `${y}px`); }); });

        // ========== HORIZONTAL FEATURE SCROLLER (FIX) ==========
        const scrollerSection = document.getElementById('features-scroller');
        const trackHorizontal = document.getElementById('features-track');
        if (scrollerSection && trackHorizontal && window.innerWidth > 768) {
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
                    invalidateOnRefresh: true,
                    onUpdate: self => {
                        const progressBar = document.getElementById('features-progress-bar');
                        if (progressBar) {
                            progressBar.style.width = (self.progress * 100) + '%';
                        }
                    }
                }
            });

            // SLA Chronometer Pulse Animation
            const progressRing = document.querySelector('.timer-progress-ring');
            if (progressRing) {
                gsap.fromTo(progressRing, { scale: 0.96, opacity: 0.3 }, {
                    scale: 1.04, opacity: 0.8, duration: 1.5, repeat: -1, yoyo: true, ease: 'sine.inOut'
                });
            }

            // Live ticking down for SLA timer (1 second interval)
            setInterval(() => {
                const els = document.querySelectorAll('.timer-val');
                els.forEach(el => {
                    const parts = el.textContent.split(':');
                    if (parts.length === 3) {
                        let h = parseInt(parts[0]);
                        let m = parseInt(parts[1]);
                        let s = parseInt(parts[2]);
                        s--;
                        if (s < 0) {
                            s = 59;
                            m--;
                            if (m < 0) {
                                m = 59;
                                h--;
                                if (h < 0) {
                                    h = 23;
                                }
                            }
                        }
                        el.textContent =
                            String(h).padStart(2, '0') + ':' +
                            String(m).padStart(2, '0') + ':' +
                            String(s).padStart(2, '0');
                    }
                });
            }, 1000);

            // Side parallax entrance animations for each slide (Apple/Stripe Style)
            slides.forEach((slide) => {
                const textSide = slide.querySelector('.feature-text-side');
                const visualSide = slide.querySelector('.feature-visual-side');
                const introContent = slide.querySelector('.feature-content');

                if (textSide) {
                    gsap.fromTo(textSide, { x: 60, opacity: 0 }, {
                        x: 0, opacity: 1, duration: 1.2, ease: 'power2.out',
                        scrollTrigger: {
                            trigger: slide,
                            containerAnimation: horizontalScroll,
                            start: 'left 90%',
                            end: 'left 50%',
                            scrub: true
                        }
                    });
                }

                if (visualSide) {
                    gsap.fromTo(visualSide, { x: 120, scale: 0.9, rotationY: -10, opacity: 0 }, {
                        x: 0, scale: 1, rotationY: 0, opacity: 1, duration: 1.2, ease: 'power2.out',
                        scrollTrigger: {
                            trigger: slide,
                            containerAnimation: horizontalScroll,
                            start: 'left 90%',
                            end: 'left 45%',
                            scrub: true
                        }
                    });
                }

                if (introContent) {
                    gsap.fromTo(introContent, { x: -60, opacity: 0.4 }, {
                        x: 0, opacity: 1, duration: 1.2, ease: 'power2.out',
                        scrollTrigger: {
                            trigger: slide,
                            containerAnimation: horizontalScroll,
                            start: 'left 85%',
                            end: 'left 40%',
                            scrub: true
                        }
                    });
                }

                // Staggered timeline tracking nodes entrance (Slide 1)
                const flowSteps = slide.querySelectorAll('.flow-step');
                if (flowSteps.length > 0) {
                    gsap.fromTo(flowSteps, { scale: 0.6, opacity: 0, y: 30 }, {
                        scale: 1, opacity: 1, y: 0, stagger: 0.1, duration: 0.8, ease: 'back.out(1.2)',
                        scrollTrigger: {
                            trigger: slide,
                            containerAnimation: horizontalScroll,
                            start: 'left 80%',
                            end: 'left 45%',
                            scrub: true
                        }
                    });
                }

                // Archive Stack fanning out on enter (Slide 4)
                const archiveCards = slide.querySelectorAll('.archive-item');
                if (archiveCards.length > 0) {
                    gsap.fromTo(archiveCards[0], { x: 0, y: 0, rotation: 0 }, {
                        x: 0, y: 0, rotation: -10, duration: 1.2, ease: 'power2.out',
                        scrollTrigger: {
                            trigger: slide,
                            containerAnimation: horizontalScroll,
                            start: 'left 85%',
                            end: 'left 45%',
                            scrub: true
                        }
                    });
                    gsap.fromTo(archiveCards[1], { x: 0, y: 0, rotation: 0, opacity: 0 }, {
                        x: 40, y: 30, rotation: 5, opacity: 0.85, duration: 1.2, ease: 'power2.out',
                        scrollTrigger: {
                            trigger: slide,
                            containerAnimation: horizontalScroll,
                            start: 'left 85%',
                            end: 'left 45%',
                            scrub: true
                        }
                    });
                    gsap.fromTo(archiveCards[2], { x: 0, y: 0, rotation: 0, opacity: 0 }, {
                        x: -30, y: 60, rotation: -5, opacity: 0.45, duration: 1.2, ease: 'power2.out',
                        scrollTrigger: {
                            trigger: slide,
                            containerAnimation: horizontalScroll,
                            start: 'left 85%',
                            end: 'left 45%',
                            scrub: true
                        }
                    });
                }
            });

            // Refresh ScrollTrigger saat resize
            window.addEventListener('resize', () => {
                if (window.innerWidth > 768) {
                    ScrollTrigger.refresh();
                    let newTotal = 0;
                    slides.forEach(slide => { newTotal += slide.offsetWidth; });
                    trackHorizontal.style.width = newTotal + 'px';
                    horizontalScroll.vars.x = () => -(newTotal - window.innerWidth);
                    horizontalScroll.invalidate();
                }
            });
        } else if (trackHorizontal) {
            // Fallback for mobile: reset width and transforms
            trackHorizontal.style.width = '100%';
            trackHorizontal.style.transform = 'none';
            trackHorizontal.style.flexDirection = 'column';
        }
        // Developer Stack Loop
        (function () {
            const stacks = {
                'tm-row1': [
                    { name: 'Laravel 12', img: 'https://cdn.simpleicons.org/laravel/FF2D20' },
                    { name: 'PHP 8.2', img: 'https://cdn.simpleicons.org/php/777BB4' },
                    { name: 'MySQL 8', img: 'https://cdn.simpleicons.org/mysql/4479A1' },
                    { name: 'Redis', img: 'https://cdn.simpleicons.org/redis/DC382D' },
                    { name: 'MongoDB', img: 'https://cdn.simpleicons.org/mongodb/47A248' },
                    { name: 'Eloquent ORM', dot: '#e11d48' },
                    { name: 'REST API', dot: '#0ea5e9' },
                    { name: 'Sanctum Auth', img: 'https://cdn.simpleicons.org/laravel/FF2D20' },
                    { name: 'Composer', img: 'https://cdn.simpleicons.org/composer/885630' },
                    { name: 'JQuery', img: 'https://cdn.simpleicons.org/jquery/1621A5' },
                    { name: 'Golang', img: 'https://cdn.simpleicons.org/go/00ADD8' }
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

        /* ─── CUSTOM CURSOR SCRIPT ─── */
        (() => {
            const cursor = document.getElementById('customCursor');
            let mouseX = 0, mouseY = 0;
            let cursorX = 0, cursorY = 0;

            // Smooth cursor tracking dengan requestAnimationFrame
            const updateCursor = () => {
                cursorX += (mouseX - cursorX) * 0.15;
                cursorY += (mouseY - cursorY) * 0.15;
                cursor.style.transform = `translate3d(${cursorX}px, ${cursorY}px, 0)`;
                requestAnimationFrame(updateCursor);
            };
            updateCursor();

            document.addEventListener('mousemove', (e) => {
                mouseX = e.clientX;
                mouseY = e.clientY;
            });

            // Add active class on interactive elements
            const interactiveElements = document.querySelectorAll('a, button, .portal-card, .about-card, .stat-card, .chart-card, .nav-mobile-toggle, input, select, textarea');

            interactiveElements.forEach(el => {
                el.addEventListener('mouseenter', () => {
                    cursor.classList.add('active');
                });
                el.addEventListener('mouseleave', () => {
                    cursor.classList.remove('active');
                });
            });

            // Cursor color toggle
            window.setCursorColor = function (color) {
                document.body.classList.remove('cursor-blue', 'cursor-black', 'cursor-brown');
                document.body.classList.add('cursor-' + color);
                localStorage.setItem('cursorColor', color);
            };

            // Load saved cursor color
            const savedColor = localStorage.getItem('cursorColor') || 'blue';
            window.setCursorColor(savedColor);

            // Add keyboard shortcut: 1=blue, 2=black, 3=brown
            document.addEventListener('keydown', (e) => {
                if (e.key === '1') window.setCursorColor('blue');
                if (e.key === '2') window.setCursorColor('black');
                if (e.key === '3') window.setCursorColor('brown');
            });

            // Hide cursor when leaving window
            document.addEventListener('mouseleave', () => {
                cursor.style.opacity = '0';
            });

            document.addEventListener('mouseenter', () => {
                cursor.style.opacity = '1';
            });
        })();

        /* ─── TEXT ANIMATIONS WITH GSAP ─── */
        (() => {
            // Initialize GSAP animations for text
            const initTextAnimations = () => {
                document.querySelectorAll('.text-animate').forEach(section => {
                    const words = section.querySelectorAll('.text-animate-word');

                    // Create GSAP timeline
                    const tl = gsap.timeline({
                        scrollTrigger: {
                            trigger: section,
                            start: 'top 80%',
                            toggleActions: 'play none none none'
                        }
                    });

                    // Animate each word with stagger
                    words.forEach((word, index) => {
                        tl.to(word, {
                            opacity: 1,
                            y: 0,
                            duration: 0.6,
                            ease: 'back.out'
                        }, index * 0.08); // Stagger each word by 80ms
                    });
                });
            };

            // Wait for DOM to be ready and init
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initTextAnimations);
            } else {
                initTextAnimations();
            }
        })();
    </script>
</body>

</html>