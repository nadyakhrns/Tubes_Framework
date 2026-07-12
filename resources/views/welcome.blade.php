<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Skevva — the next generation of online learning. Premium courses, expert instructors, and a beautiful interface designed for focus and mastery.">
    <meta name="author" content="Skevva">
    <meta property="og:title" content="Skevva — Learn Without Limits">
    <meta property="og:description" content="Premium E-Learning platform with expert-led courses, interactive quizzes, and real-time progress tracking.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta name="twitter:card" content="summary_large_image">
    <title>{{ config('app.name', 'Skevva') }} — Learn Without Limits</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        /* ==========================================================================
           Aurora UI — Design System Variables
           ========================================================================== */
        :root {
            --aurora-primary: #0080FF;
            --aurora-secondary: #FF1493;
            --aurora-tertiary: #00FFFF;
            --aurora-orange: #FF6B35;
            --aurora-purple: #7C3AED;
            --aurora-yellow: #FBBF24;

            --gradient-colors-1: var(--aurora-primary), var(--aurora-tertiary);
            --gradient-colors-2: var(--aurora-secondary), var(--aurora-purple);
            --animation-duration: 10s;
            --blend-mode: screen;
            --color-saturation: 1.2;

            --surface: rgba(255, 255, 255, 0.72);
            --surface-hover: rgba(255, 255, 255, 0.88);
            --surface-border: rgba(255, 255, 255, 0.3);
            --ink: #0f172a;
            --ink-muted: #475569;
            --ink-subtle: #94a3b8;
            --radius: 16px;
            --radius-sm: 12px;
            --radius-xs: 8px;
        }

        /* ==========================================================================
           Reset & Base
           ========================================================================== */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: var(--ink);
            line-height: 1.6;
            overflow-x: hidden;
            background: #060918;
            min-height: 100vh;
        }

        a { text-decoration: none; color: inherit; cursor: pointer; }
        img { max-width: 100%; display: block; }

        /* ==========================================================================
           Aurora Animated Background
           ========================================================================== */
        @keyframes aurora-shift {
            0%   { background-position: 0% 50%; }
            50%  { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        @keyframes aurora-rotate {
            0%   { transform: rotate(0deg) scale(1.5); }
            100% { transform: rotate(360deg) scale(1.5); }
        }

        @keyframes float-y {
            0%, 100% { transform: translateY(0px); }
            50%      { transform: translateY(-20px); }
        }

        @keyframes pulse-glow {
            0%, 100% { opacity: 0.6; transform: scale(1); }
            50%      { opacity: 1; transform: scale(1.05); }
        }

        @keyframes iridescent {
            0%   { filter: hue-rotate(0deg) saturate(1.2); }
            50%  { filter: hue-rotate(30deg) saturate(1.4); }
            100% { filter: hue-rotate(0deg) saturate(1.2); }
        }

        .aurora-canvas {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            z-index: 0;
            overflow: hidden;
            pointer-events: none;
        }

        .aurora-canvas::before {
            content: "";
            position: absolute;
            top: -60%; left: -60%;
            width: 220%; height: 220%;
            background:
                radial-gradient(ellipse 80% 60% at 20% 40%, rgba(0, 128, 255, 0.18), transparent),
                radial-gradient(ellipse 60% 80% at 80% 20%, rgba(255, 20, 147, 0.14), transparent),
                radial-gradient(ellipse 70% 50% at 50% 80%, rgba(0, 255, 255, 0.16), transparent),
                radial-gradient(ellipse 50% 40% at 70% 60%, rgba(124, 58, 237, 0.10), transparent);
            background-size: 200% 200%;
            animation: aurora-shift var(--animation-duration) ease-in-out infinite;
            mix-blend-mode: screen;
        }

        .aurora-canvas::after {
            content: "";
            position: absolute;
            top: -40%; left: -40%;
            width: 180%; height: 180%;
            background:
                conic-gradient(from 0deg at 30% 70%, rgba(0, 128, 255, 0.08), rgba(255, 20, 147, 0.06), rgba(0, 255, 255, 0.08), rgba(124, 58, 237, 0.06), rgba(0, 128, 255, 0.08));
            animation: aurora-rotate 30s linear infinite;
            mix-blend-mode: screen;
            opacity: 0.5;
        }

        .page-wrap {
            position: relative;
            z-index: 1;
        }

        /* ==========================================================================
           Glassmorphism Utilities
           ========================================================================== */
        .glass {
            background: var(--surface);
            backdrop-filter: blur(24px) saturate(1.2);
            -webkit-backdrop-filter: blur(24px) saturate(1.2);
            border: 1px solid var(--surface-border);
        }

        .glass-dark {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(24px) saturate(1.2);
            -webkit-backdrop-filter: blur(24px) saturate(1.2);
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: #e2e8f0;
        }

        /* ==========================================================================
           Layout
           ========================================================================== */
        .container-lp {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }

        section {
            padding: clamp(64px, 10vw, 120px) 0;
        }

        /* ==========================================================================
           Navbar
           ========================================================================== */
        .lp-navbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
            padding: 16px 0;
            transition: all 0.3s ease;
        }

        .lp-navbar.scrolled {
            background: rgba(6, 9, 24, 0.85);
            backdrop-filter: blur(20px) saturate(1.3);
            -webkit-backdrop-filter: blur(20px) saturate(1.3);
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            padding: 10px 0;
        }

        .lp-navbar .container-lp {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .lp-logo {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            font-weight: 800;
            font-size: 1.35rem;
            color: #ffffff;
            letter-spacing: -0.02em;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .lp-logo:hover { opacity: 0.85; }

        .lp-logo-icon {
            width: 40px; height: 40px;
            border-radius: var(--radius-xs);
            background: linear-gradient(135deg, var(--aurora-primary), var(--aurora-tertiary));
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 16px rgba(0, 128, 255, 0.35);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 32px;
            list-style: none;
        }

        .nav-links a {
            font-size: 0.9rem;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.7);
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .nav-links a:hover { color: #ffffff; }

        .nav-cta {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-ghost {
            padding: 10px 22px;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.85);
            background: transparent;
            border: 1.5px solid rgba(255, 255, 255, 0.2);
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-ghost:hover {
            border-color: rgba(255, 255, 255, 0.5);
            background: rgba(255, 255, 255, 0.06);
            color: #fff;
            transform: translateY(-1px);
        }

        .btn-aurora {
            padding: 10px 28px;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 0.9rem;
            color: #ffffff;
            background: linear-gradient(135deg, var(--aurora-primary), #0066d6);
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 16px rgba(0, 128, 255, 0.3);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-aurora:hover {
            box-shadow: 0 8px 28px rgba(0, 128, 255, 0.45);
            transform: translateY(-2px);
        }
        .btn-aurora:active { transform: translateY(0); }

        .btn-aurora-lg {
            padding: 16px 40px;
            font-size: 1.05rem;
            border-radius: var(--radius-sm);
        }

        .btn-aurora-outline {
            padding: 16px 40px;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 1.05rem;
            color: #ffffff;
            background: rgba(255, 255, 255, 0.06);
            border: 1.5px solid rgba(255, 255, 255, 0.18);
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-aurora-outline:hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.35);
            transform: translateY(-2px);
        }

        /* Mobile Menu Toggle */
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
        }

        /* ==========================================================================
           Hero Section
           ========================================================================== */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding-top: 100px;
            position: relative;
        }

        .hero .container-lp {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 64px;
            align-items: center;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 16px 6px 8px;
            border-radius: 100px;
            background: rgba(0, 128, 255, 0.12);
            border: 1px solid rgba(0, 128, 255, 0.2);
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--aurora-tertiary);
            margin-bottom: 24px;
            animation: iridescent 8s ease-in-out infinite;
        }

        .hero-badge-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: var(--aurora-tertiary);
            box-shadow: 0 0 8px var(--aurora-tertiary);
        }

        .hero h1 {
            font-size: clamp(2.8rem, 5.5vw, 4.2rem);
            font-weight: 900;
            line-height: 1.1;
            letter-spacing: -0.03em;
            color: #ffffff;
            margin-bottom: 24px;
        }

        .hero-gradient-text {
            background: linear-gradient(135deg, var(--aurora-primary), var(--aurora-tertiary), var(--aurora-secondary));
            background-size: 200% auto;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: aurora-shift var(--animation-duration) ease-in-out infinite;
        }

        .hero p {
            font-size: 1.15rem;
            color: var(--ink-subtle);
            max-width: 520px;
            margin-bottom: 40px;
            line-height: 1.7;
        }

        .hero-actions {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }

        .hero-stats {
            display: flex;
            gap: 40px;
            margin-top: 48px;
            padding-top: 32px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .hero-stat-value {
            font-size: 1.6rem;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.02em;
        }

        .hero-stat-label {
            font-size: 0.8rem;
            color: var(--ink-subtle);
            margin-top: 4px;
        }

        /* 3D Orb Visual */
        .hero-visual {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .orb-container {
            position: relative;
            width: 420px;
            height: 420px;
        }

        .orb {
            position: absolute;
            border-radius: 50%;
            animation: float-y 6s ease-in-out infinite;
        }

        .orb-main {
            width: 280px; height: 280px;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            background: radial-gradient(circle at 35% 35%,
                rgba(0, 128, 255, 0.9),
                rgba(124, 58, 237, 0.6),
                rgba(255, 20, 147, 0.4));
            box-shadow:
                0 0 80px rgba(0, 128, 255, 0.4),
                0 0 160px rgba(124, 58, 237, 0.2),
                inset 0 0 60px rgba(255, 255, 255, 0.1);
            animation: float-y 6s ease-in-out infinite, iridescent var(--animation-duration) ease-in-out infinite;
        }

        .orb-ring {
            width: 360px; height: 360px;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            border: 2px solid rgba(0, 255, 255, 0.15);
            animation: aurora-rotate 20s linear infinite;
        }

        .orb-ring::before {
            content: "";
            position: absolute;
            top: -6px; left: 50%;
            width: 12px; height: 12px;
            border-radius: 50%;
            background: var(--aurora-tertiary);
            box-shadow: 0 0 20px var(--aurora-tertiary);
        }

        .orb-dot {
            width: 10px; height: 10px;
            background: var(--aurora-secondary);
            box-shadow: 0 0 16px var(--aurora-secondary);
            animation-delay: -2s;
        }
        .orb-dot-1 { top: 15%; left: 20%; }
        .orb-dot-2 { top: 75%; right: 15%; animation-delay: -4s; background: var(--aurora-tertiary); box-shadow: 0 0 16px var(--aurora-tertiary); }
        .orb-dot-3 { bottom: 10%; left: 35%; animation-delay: -1s; background: var(--aurora-primary); box-shadow: 0 0 16px var(--aurora-primary); }

        .orb-glow {
            position: absolute;
            width: 420px; height: 420px;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            background: radial-gradient(circle, rgba(0, 128, 255, 0.12), transparent 70%);
            animation: pulse-glow 5s ease-in-out infinite;
            pointer-events: none;
        }

        /* ==========================================================================
           Section Headers
           ========================================================================== */
        .section-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--aurora-tertiary);
            margin-bottom: 16px;
        }

        .section-eyebrow-line {
            width: 32px; height: 2px;
            background: linear-gradient(90deg, var(--aurora-primary), var(--aurora-tertiary));
            border-radius: 2px;
        }

        .section-title {
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 800;
            letter-spacing: -0.03em;
            line-height: 1.15;
            color: #ffffff;
            margin-bottom: 16px;
        }

        .section-subtitle {
            font-size: 1.1rem;
            color: var(--ink-subtle);
            max-width: 600px;
            line-height: 1.7;
        }

        .section-header {
            text-align: center;
            margin-bottom: 64px;
        }

        .section-header .section-subtitle {
            margin: 0 auto;
        }

        /* ==========================================================================
           Features Section
           ========================================================================== */
        .features { position: relative; }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .feature-card {
            border-radius: var(--radius);
            padding: 40px 32px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--aurora-primary), var(--aurora-tertiary));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-6px);
            background: rgba(15, 23, 42, 0.8);
            border-color: rgba(0, 128, 255, 0.2);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .feature-card:hover::before { opacity: 1; }

        .feature-icon {
            width: 56px; height: 56px;
            border-radius: var(--radius-xs);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            position: relative;
        }

        .feature-icon-blue { background: rgba(0, 128, 255, 0.12); color: var(--aurora-primary); }
        .feature-icon-pink { background: rgba(255, 20, 147, 0.12); color: var(--aurora-secondary); }
        .feature-icon-cyan { background: rgba(0, 255, 255, 0.12); color: var(--aurora-tertiary); }

        .feature-card h3 {
            font-size: 1.2rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 12px;
            letter-spacing: -0.01em;
        }

        .feature-card p {
            font-size: 0.95rem;
            color: var(--ink-subtle);
            line-height: 1.65;
        }

        /* ==========================================================================
           Testimonials Section
           ========================================================================== */
        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .testimonial-card {
            border-radius: var(--radius);
            padding: 36px 32px;
            transition: all 0.3s ease;
            cursor: default;
        }

        .testimonial-card:hover {
            transform: translateY(-4px);
            border-color: rgba(0, 128, 255, 0.15);
        }

        .testimonial-stars {
            display: flex;
            gap: 4px;
            margin-bottom: 20px;
        }

        .testimonial-stars svg { color: var(--aurora-yellow); }

        .testimonial-text {
            font-size: 0.95rem;
            color: #cbd5e1;
            line-height: 1.7;
            margin-bottom: 24px;
            font-style: italic;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .testimonial-avatar {
            width: 44px; height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1rem;
            color: #fff;
        }

        .testimonial-name {
            font-weight: 600;
            font-size: 0.95rem;
            color: #f1f5f9;
        }

        .testimonial-role {
            font-size: 0.8rem;
            color: var(--ink-subtle);
            margin-top: 2px;
        }

        /* ==========================================================================
           Pricing Section
           ========================================================================== */
        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            align-items: start;
        }

        .pricing-card {
            border-radius: var(--radius);
            padding: 40px 32px;
            transition: all 0.3s ease;
            position: relative;
            cursor: pointer;
        }

        .pricing-card:hover {
            transform: translateY(-4px);
        }

        .pricing-card.featured {
            background: linear-gradient(135deg, rgba(0, 128, 255, 0.15), rgba(124, 58, 237, 0.1));
            border-color: rgba(0, 128, 255, 0.3);
            box-shadow: 0 0 60px rgba(0, 128, 255, 0.1);
        }

        .pricing-badge {
            position: absolute;
            top: -14px;
            left: 50%;
            transform: translateX(-50%);
            padding: 6px 20px;
            border-radius: 100px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            background: linear-gradient(135deg, var(--aurora-primary), var(--aurora-purple));
            color: #ffffff;
            box-shadow: 0 4px 16px rgba(0, 128, 255, 0.3);
        }

        .pricing-name {
            font-size: 1.1rem;
            font-weight: 700;
            color: #f1f5f9;
            margin-bottom: 8px;
        }

        .pricing-desc {
            font-size: 0.85rem;
            color: var(--ink-subtle);
            margin-bottom: 24px;
        }

        .pricing-price {
            margin-bottom: 32px;
        }

        .pricing-amount {
            font-size: 3rem;
            font-weight: 900;
            color: #ffffff;
            letter-spacing: -0.03em;
        }

        .pricing-period {
            font-size: 0.9rem;
            color: var(--ink-subtle);
        }

        .pricing-features {
            list-style: none;
            margin-bottom: 32px;
        }

        .pricing-features li {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 0;
            font-size: 0.9rem;
            color: #cbd5e1;
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
        }

        .pricing-features li:last-child { border-bottom: none; }

        .pricing-features svg { flex-shrink: 0; }

        .pricing-btn {
            display: block;
            width: 100%;
            text-align: center;
            padding: 14px;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.25s ease;
        }

        .pricing-btn-outline {
            background: transparent;
            border: 1.5px solid rgba(255, 255, 255, 0.15);
            color: #e2e8f0;
        }
        .pricing-btn-outline:hover {
            border-color: rgba(255, 255, 255, 0.35);
            background: rgba(255, 255, 255, 0.04);
            color: #fff;
        }

        .pricing-btn-fill {
            background: linear-gradient(135deg, var(--aurora-primary), #0066d6);
            border: none;
            color: #ffffff;
            box-shadow: 0 4px 20px rgba(0, 128, 255, 0.3);
        }
        .pricing-btn-fill:hover {
            box-shadow: 0 8px 32px rgba(0, 128, 255, 0.5);
            transform: translateY(-1px);
            color: #fff;
        }

        /* ==========================================================================
           Final CTA Section
           ========================================================================== */
        .final-cta {
            text-align: center;
            position: relative;
        }

        .final-cta-box {
            border-radius: calc(var(--radius) + 8px);
            padding: clamp(48px, 8vw, 80px);
            background: linear-gradient(135deg, rgba(0, 128, 255, 0.08), rgba(124, 58, 237, 0.06), rgba(255, 20, 147, 0.04));
            border: 1px solid rgba(0, 128, 255, 0.15);
            position: relative;
            overflow: hidden;
        }

        .final-cta-box::before {
            content: "";
            position: absolute;
            top: -100px; right: -100px;
            width: 300px; height: 300px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(0, 128, 255, 0.12), transparent);
            pointer-events: none;
        }

        .cta-actions {
            display: flex;
            justify-content: center;
            gap: 16px;
            margin-top: 40px;
            flex-wrap: wrap;
        }

        /* ==========================================================================
           Footer
           ========================================================================== */
        .lp-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            padding: 64px 0 32px;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 48px;
            margin-bottom: 48px;
        }

        .footer-brand {
            max-width: 280px;
        }

        .footer-brand p {
            font-size: 0.9rem;
            color: var(--ink-subtle);
            margin-top: 16px;
            line-height: 1.65;
        }

        .footer-heading {
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: rgba(255, 255, 255, 0.5);
            margin-bottom: 20px;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li { margin-bottom: 12px; }

        .footer-links a {
            font-size: 0.9rem;
            color: var(--ink-subtle);
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .footer-links a:hover { color: #ffffff; }

        .footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 32px;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
        }

        .footer-copy {
            font-size: 0.85rem;
            color: var(--ink-subtle);
        }

        .footer-socials {
            display: flex;
            gap: 16px;
        }

        .footer-socials a {
            width: 40px; height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.06);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--ink-subtle);
            transition: all 0.25s ease;
            cursor: pointer;
        }
        .footer-socials a:hover {
            background: rgba(0, 128, 255, 0.15);
            color: var(--aurora-primary);
            transform: translateY(-2px);
        }

        /* ==========================================================================
           Responsive Design
           ========================================================================== */
        @media (max-width: 1024px) {
            .hero .container-lp {
                grid-template-columns: 1fr;
                text-align: center;
                gap: 48px;
            }
            .hero p { margin-left: auto; margin-right: auto; }
            .hero-actions { justify-content: center; }
            .hero-stats { justify-content: center; }
            .hero-visual { order: -1; }
            .orb-container { width: 300px; height: 300px; }
            .orb-main { width: 200px; height: 200px; }
            .orb-ring { width: 260px; height: 260px; }
            .orb-glow { width: 300px; height: 300px; }

            .features-grid,
            .testimonials-grid,
            .pricing-grid {
                grid-template-columns: 1fr;
                max-width: 480px;
                margin: 0 auto;
            }

            .footer-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 768px) {
            .nav-links { display: none; }
            .nav-cta .btn-ghost { display: none; }
            .menu-toggle { display: block; }

            .hero h1 { font-size: 2.2rem; }
            .hero-stats { gap: 24px; flex-wrap: wrap; }

            .footer-grid { grid-template-columns: 1fr; }
            .footer-bottom { flex-direction: column; gap: 24px; text-align: center; }
        }

        @media (max-width: 480px) {
            .hero-actions { flex-direction: column; align-items: center; }
            .btn-aurora-lg, .btn-aurora-outline { width: 100%; text-align: center; }
            .cta-actions { flex-direction: column; align-items: center; }
        }
    </style>
</head>
<body>
    <!-- Aurora Animated Background -->
    <div class="aurora-canvas" aria-hidden="true"></div>

    <div class="page-wrap">
        <!-- ============================================================
             NAVBAR
             ============================================================ -->
        <nav class="lp-navbar" id="navbar">
            <div class="container-lp">
                <a href="/" class="lp-logo">
                    <span class="lp-logo-icon" style="font-weight:900;font-size:1.1rem;">S</span>
                    Skevva
                </a>

                <ul class="nav-links">
                    <li><a href="#features">Features</a></li>
                    <li><a href="#how-it-works">How It Works</a></li>
                    <li><a href="#faq">FAQ</a></li>
                </ul>

                <div class="nav-cta">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-aurora">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn-ghost">Log In</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-aurora">Get Started</a>
                        @endif
                    @endauth

                    <button class="menu-toggle" onclick="document.querySelector('.nav-links').style.display = document.querySelector('.nav-links').style.display === 'flex' ? 'none' : 'flex'" aria-label="Toggle menu">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.8)" stroke-width="2" stroke-linecap="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                    </button>
                </div>
            </div>
        </nav>

        <!-- ============================================================
             HERO SECTION
             ============================================================ -->
        <section class="hero">
            <div class="container-lp">
                <div class="hero-content">
                    <div class="hero-badge">
                        <span class="hero-badge-dot"></span>
                        Platform of the Year 2026
                    </div>

                    <h1>
                        Master New Skills<br>
                        <span class="hero-gradient-text">Without Limits</span>
                    </h1>

                    <p>
                        The premium learning experience built for the modern student. Expert-crafted courses, interactive quizzes, and real-time progress tracking that adapts to your pace.
                    </p>

                    <div class="hero-actions">
                        @guest
                            <a href="{{ route('register') }}" class="btn-aurora btn-aurora-lg">Start Learning Free</a>
                            <a href="{{ route('login') }}" class="btn-aurora-outline">Sign In</a>
                        @else
                            <a href="{{ url('/dashboard') }}" class="btn-aurora btn-aurora-lg">Go to Dashboard</a>
                        @endguest
                    </div>

                    <div class="hero-stats">
                        <div>
                            <div class="hero-stat-value">12k+</div>
                            <div class="hero-stat-label">Active Students</div>
                        </div>
                        <div>
                            <div class="hero-stat-value">350+</div>
                            <div class="hero-stat-label">Expert Courses</div>
                        </div>
                        <div>
                            <div class="hero-stat-value">98%</div>
                            <div class="hero-stat-label">Completion Rate</div>
                        </div>
                    </div>
                </div>

                <!-- 3D Abstract Orb Visual -->
                <div class="hero-visual" aria-hidden="true">
                    <div class="orb-container">
                        <div class="orb-glow"></div>
                        <div class="orb orb-ring"></div>
                        <div class="orb orb-main"></div>
                        <div class="orb orb-dot orb-dot-1"></div>
                        <div class="orb orb-dot orb-dot-2"></div>
                        <div class="orb orb-dot orb-dot-3"></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============================================================
             FEATURES SECTION
             ============================================================ -->
        <section class="features" id="features">
            <div class="container-lp">
                <div class="section-header">
                    <div class="section-eyebrow">
                        <span class="section-eyebrow-line"></span>
                        Why Skevva
                    </div>
                    <h2 class="section-title">Everything you need to<br><span class="hero-gradient-text">accelerate your growth</span></h2>
                    <p class="section-subtitle">Our platform combines cutting-edge technology with proven pedagogy to deliver an experience that keeps you engaged and progressing.</p>
                </div>

                <div class="features-grid">
                    <div class="feature-card glass-dark">
                        <div class="feature-icon feature-icon-blue" style="font-size:1.4rem;font-weight:800;">01</div>
                        <h3>Structured Curriculum</h3>
                        <p>Courses organized into sections and lessons with a clear learning path. Progress at your own pace with expert-designed content.</p>
                    </div>

                    <div class="feature-card glass-dark">
                        <div class="feature-icon feature-icon-pink" style="font-size:1.4rem;font-weight:800;">02</div>
                        <h3>Interactive Quizzes</h3>
                        <p>Test your knowledge with auto-graded quizzes after each lesson. Instant scoring and detailed feedback to track your mastery.</p>
                    </div>

                    <div class="feature-card glass-dark">
                        <div class="feature-icon feature-icon-cyan" style="font-size:1.4rem;font-weight:800;">03</div>
                        <h3>Progress Analytics</h3>
                        <p>Track your learning journey with real-time analytics. See scores, completion rates, and performance trends at a glance.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============================================================
             HOW IT WORKS SECTION
             ============================================================ -->
        <section class="how-it-works" id="how-it-works">
            <div class="container-lp">
                <div class="section-header">
                    <div class="section-eyebrow">
                        <span class="section-eyebrow-line"></span>
                        How It Works
                    </div>
                    <h2 class="section-title">Your path to<br><span class="hero-gradient-text">mastery</span></h2>
                    <p class="section-subtitle">Three simple steps to transform your skills and achieve your goals with Skevva.</p>
                </div>

                <div class="features-grid">
                    <div class="feature-card glass-dark">
                        <div class="feature-icon feature-icon-pink" style="font-size:1.4rem;font-weight:800;">1</div>
                        <h3>Create an Account</h3>
                        <p>Sign up for free and get instant access to our diverse catalog of premium courses taught by industry experts.</p>
                    </div>
                    <div class="feature-card glass-dark">
                        <div class="feature-icon feature-icon-cyan" style="font-size:1.4rem;font-weight:800;">2</div>
                        <h3>Learn and Practice</h3>
                        <p>Watch video lessons, read materials, and test your knowledge immediately with interactive quizzes that reinforce learning.</p>
                    </div>
                    <div class="feature-card glass-dark">
                        <div class="feature-icon feature-icon-blue" style="font-size:1.4rem;font-weight:800;">3</div>
                        <h3>Earn Certificates</h3>
                        <p>Complete your courses successfully and earn certificates to showcase your new skills to employers and your network.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============================================================
             FAQ SECTION
             ============================================================ -->
        <section class="faq" id="faq">
            <div class="container-lp">
                <div class="section-header">
                    <div class="section-eyebrow">
                        <span class="section-eyebrow-line"></span>
                        FAQ
                    </div>
                    <h2 class="section-title">Frequently Asked<br><span class="hero-gradient-text">Questions</span></h2>
                    <p class="section-subtitle">Got questions? We have got answers to help you get started on your learning journey.</p>
                </div>

                <div style="max-width: 800px; margin: 0 auto; display: flex; flex-direction: column; gap: 1.5rem;">
                    <div class="testimonial-card glass-dark" style="text-align: left; padding: 2rem;">
                        <h3 style="font-size: 1.2rem; margin-bottom: 0.5rem; color: #fff;">Is Skevva completely free?</h3>
                        <p style="color: var(--ink-muted); line-height: 1.6; margin: 0;">We offer both free and premium courses. You can create an account and explore many fundamental courses at absolutely no cost. Advanced specialized courses may require enrollment.</p>
                    </div>
                    <div class="testimonial-card glass-dark" style="text-align: left; padding: 2rem;">
                        <h3 style="font-size: 1.2rem; margin-bottom: 0.5rem; color: #fff;">Are the certificates recognized?</h3>
                        <p style="color: var(--ink-muted); line-height: 1.6; margin: 0;">Yes, upon successfully completing a course and passing the required quizzes, you will receive a verified certificate that you can attach to your resume or LinkedIn profile.</p>
                    </div>
                    <div class="testimonial-card glass-dark" style="text-align: left; padding: 2rem;">
                        <h3 style="font-size: 1.2rem; margin-bottom: 0.5rem; color: #fff;">Can I access courses on mobile?</h3>
                        <p style="color: var(--ink-muted); line-height: 1.6; margin: 0;">Absolutely. The Skevva platform is fully responsive and designed to provide a seamless learning experience across desktop, tablet, and mobile devices.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============================================================
             FINAL CTA
             ============================================================ -->
        <section class="final-cta">
            <div class="container-lp">
                <div class="final-cta-box">
                    <div class="section-eyebrow" style="justify-content: center;">
                        <span class="section-eyebrow-line"></span>
                        Ready to begin?
                    </div>
                    <h2 class="section-title">Start your learning<br>journey <span class="hero-gradient-text">today</span></h2>
                    <p class="section-subtitle" style="margin: 0 auto;">Join thousands of learners already advancing their careers with Skevva. Free to start, no credit card required.</p>

                    <div class="cta-actions">
                        @guest
                            <a href="{{ route('register') }}" class="btn-aurora btn-aurora-lg">Create Free Account</a>
                            <a href="{{ route('login') }}" class="btn-aurora-outline">Sign In</a>
                        @else
                            <a href="{{ url('/dashboard') }}" class="btn-aurora btn-aurora-lg">Go to Dashboard</a>
                        @endguest
                    </div>
                </div>
            </div>
        </section>

        <!-- ============================================================
             FOOTER
             ============================================================ -->
        <footer class="lp-footer">
            <div class="container-lp">
                <div class="footer-grid">
                    <div class="footer-brand">
                        <a href="/" class="lp-logo" style="font-size: 1.2rem;">
                            <span class="lp-logo-icon" style="width:36px; height:36px; font-weight:900; font-size:1rem;">S</span>
                            Skevva
                        </a>
                        <p>The premium learning platform built for focused, results-driven education. Master new skills at your own pace.</p>
                    </div>

                    <div>
                        <div class="footer-heading">Platform</div>
                        <ul class="footer-links">
                            <li><a href="#features">Features</a></li>
                            <li><a href="#how-it-works">How It Works</a></li>
                            <li><a href="#faq">FAQ</a></li>
                            <li><a href="#">Changelog</a></li>
                        </ul>
                    </div>

                    <div>
                        <div class="footer-heading">Resources</div>
                        <ul class="footer-links">
                            <li><a href="#">Documentation</a></li>
                            <li><a href="#">Help Center</a></li>
                            <li><a href="#">Community</a></li>
                            <li><a href="#">Contact Us</a></li>
                        </ul>
                    </div>

                    <div>
                        <div class="footer-heading">Legal</div>
                        <ul class="footer-links">
                            <li><a href="#">Privacy Policy</a></li>
                            <li><a href="#">Terms of Use</a></li>
                            <li><a href="#">Cookie Policy</a></li>
                            <li><a href="#">Licenses</a></li>
                        </ul>
                    </div>
                </div>

                <div class="footer-bottom">
                    <div class="footer-copy">&copy; 2026 Skevva. All rights reserved.</div>

                    <div class="footer-socials">
                        <!-- Twitter / X -->
                        <a href="#" aria-label="Twitter">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                            </svg>
                        </a>
                        <!-- GitHub -->
                        <a href="#" aria-label="GitHub">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                            </svg>
                        </a>
                        <!-- LinkedIn -->
                        <a href="#" aria-label="LinkedIn">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                        <!-- YouTube -->
                        <a href="#" aria-label="YouTube">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Navbar scroll effect -->
    <script>
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                e.preventDefault();
                const target = document.querySelector(targetId);
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>
</body>
</html>
