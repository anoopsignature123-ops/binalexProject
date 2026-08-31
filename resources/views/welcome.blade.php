<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BINALEX - Premier Decentralized Token Growth Ecosystem</title>
    <meta name="description" content="BINALEX is a premier digital asset engineered to empower a global network of modern investors through decentralized multi-chain staking and earnings.">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" type="image/x-icon">
    <style>
        :root {
            --bg-dark: #07090e;
            --bg-card: rgba(14, 18, 28, 0.88);
            --gold-primary: #f5b82e;
            --gold-light: #ffd700;
            --gold-gradient: linear-gradient(135deg, #ffd700 0%, #f5b82e 50%, #c88909 100%);
            --gold-glow: 0 0 35px rgba(245, 184, 46, 0.35);
            --border-gold: rgba(245, 184, 46, 0.3);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif; }

        html { scroll-behavior: smooth; }

        body {
            background: linear-gradient(135deg, rgba(7, 9, 14, 0.72) 0%, rgba(12, 16, 26, 0.85) 100%), url('{{ asset("images/auth_bg.jpg") }}') no-repeat center center fixed;
            background-size: cover;
            color: var(--text-main);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* --- Header Navigation Bar --- */
        .navbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
            background: rgba(7, 9, 14, 0.92);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-gold);
            padding: 0.9rem 2.2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 10px 30px rgba(0,0,0,0.6);
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .brand-logo-img {
            height: 44px;
            width: 44px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 0 18px rgba(245, 184, 46, 0.5);
            border: 1px solid var(--border-gold);
            transition: transform 0.3s ease;
        }

        .nav-brand:hover .brand-logo-img {
            transform: scale(1.08) rotate(5deg);
        }

        .brand-text {
            font-size: 1.35rem;
            font-weight: 900;
            color: var(--gold-light);
            letter-spacing: 1.5px;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 1.8rem;
            list-style: none;
        }

        .nav-links a {
            color: var(--text-main);
            text-decoration: none;
            font-size: 0.92rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .nav-links a:hover {
            color: var(--gold-light);
            text-shadow: 0 0 10px rgba(245, 184, 46, 0.5);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .btn-gold {
            background: var(--gold-gradient);
            color: #000;
            padding: 0.65rem 1.4rem;
            border-radius: 10px;
            font-weight: 800;
            font-size: 0.9rem;
            text-decoration: none;
            border: none;
            cursor: pointer;
            box-shadow: var(--gold-glow);
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 40px rgba(245, 184, 46, 0.6);
        }

        .btn-outline {
            background: rgba(255, 255, 255, 0.08);
            color: var(--text-main);
            border: 1px solid var(--border-gold);
            padding: 0.65rem 1.4rem;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-outline:hover {
            border-color: var(--gold-light);
            color: var(--gold-light);
            background: rgba(245, 184, 46, 0.1);
        }

        /* --- Mobile Navigation Drawer Styling --- */
        .mobile-menu-toggle {
            display: none;
            background: rgba(245, 184, 46, 0.15);
            border: 1px solid var(--border-gold);
            color: var(--gold-light);
            padding: 8px 12px;
            border-radius: 10px;
            font-size: 1.2rem;
            cursor: pointer;
            box-shadow: var(--gold-glow);
        }

        .mobile-menu-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.85);
            backdrop-filter: blur(10px);
            z-index: 1999;
            display: none;
        }

        .mobile-menu-drawer {
            position: fixed;
            top: 0; right: -320px; bottom: 0;
            width: 300px;
            background: rgba(12, 16, 26, 0.98);
            border-left: 1px solid var(--border-gold);
            padding: 2rem 1.5rem;
            z-index: 2000;
            transition: right 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: -10px 0 30px rgba(0,0,0,0.9);
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        .mobile-menu-drawer.open {
            right: 0;
        }

        /* --- Container & Hero Section --- */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .hero {
            padding: 9.5rem 0 4rem 0;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            width: 100%;
        }

        .hero-header-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            width: 100%;
            margin-bottom: 1rem;
        }

        .hero-floating-logo {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 0 45px rgba(245, 184, 46, 0.7);
            border: 2px solid var(--gold-light);
            margin: 0 auto 1.2rem auto;
            display: block;
            animation: heroFloat 4s infinite ease-in-out;
        }

        @keyframes heroFloat {
            0%, 100% { transform: translateY(0px) rotate(0deg); box-shadow: 0 0 35px rgba(245, 184, 46, 0.5); }
            50% { transform: translateY(-14px) rotate(3deg); box-shadow: 0 0 55px rgba(245, 184, 46, 0.8); }
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: rgba(245, 184, 46, 0.12);
            border: 1px solid var(--border-gold);
            padding: 6px 18px;
            border-radius: 30px;
            color: var(--gold-light);
            font-size: 0.85rem;
            font-weight: 800;
            letter-spacing: 1.5px;
            margin: 0 auto 1.2rem auto;
            box-shadow: var(--gold-glow);
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 900;
            line-height: 1.15;
            margin-bottom: 1.2rem;
            text-transform: uppercase;
        }

        .hero-title span {
            background: var(--gold-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtitle {
            font-size: 1.2rem;
            color: var(--text-muted);
            max-width: 780px;
            margin: 0 auto 2.5rem auto;
            line-height: 1.6;
        }

        .hero-cta {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1.2rem;
            flex-wrap: wrap;
            margin-bottom: 4rem;
        }

        .features-pills {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.2rem;
            margin-top: 2.5rem;
            width: 100%;
        }

        .pill-card {
            background: var(--bg-card);
            border: 1px solid var(--border-gold);
            border-radius: 16px;
            padding: 1.4rem;
            backdrop-filter: blur(20px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            gap: 14px;
            text-align: left;
            transition: all 0.3s ease;
        }

        .pill-card:hover {
            transform: translateY(-5px);
            border-color: var(--gold-light);
            box-shadow: var(--gold-glow);
        }

        .pill-icon {
            width: 48px;
            height: 48px;
            background: rgba(245, 184, 46, 0.15);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gold-light);
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        /* --- Live Ticker Bar --- */
        .stats-bar {
            background: rgba(14, 18, 28, 0.92);
            border: 1px solid var(--gold-primary);
            border-radius: 20px;
            padding: 1.8rem 2rem;
            margin: 2rem 0 5rem 0;
            box-shadow: 0 15px 40px rgba(0,0,0,0.6), var(--gold-glow);
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            text-align: center;
        }

        .stat-item h4 {
            font-size: 0.85rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.4rem;
        }

        .stat-item p {
            font-size: 1.6rem;
            font-weight: 900;
            color: var(--gold-light);
        }

        /* --- Section Styling & Funnel Scroll Animations --- */
        .reveal-on-scroll {
            opacity: 0;
            transform: translateY(35px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .reveal-on-scroll.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        .section-title {
            text-align: center;
            margin-bottom: 3.5rem;
        }

        .section-title h3 {
            font-size: 0.9rem;
            color: var(--gold-light);
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 900;
        }

        /* --- About Section --- */
        .about-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            align-items: stretch;
            margin-bottom: 6rem;
        }

        .about-card {
            background: var(--bg-card);
            border: 1px solid var(--border-gold);
            border-radius: 24px;
            padding: 2.5rem;
            backdrop-filter: blur(25px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.6);
            transition: transform 0.3s ease;
        }
        .about-card:hover {
            transform: translateY(-4px);
            border-color: var(--gold-light);
        }

        .about-card h3 {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--gold-light);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .about-card p {
            color: var(--text-muted);
            line-height: 1.7;
            font-size: 1rem;
        }

        /* --- Staking Packages Grid --- */
        .packages-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            margin-bottom: 6rem;
        }

        .package-card {
            background: var(--bg-card);
            border: 1px solid var(--border-gold);
            border-radius: 24px;
            padding: 2.2rem;
            text-align: center;
            position: relative;
            backdrop-filter: blur(20px);
            transition: all 0.35s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .package-card:hover {
            transform: translateY(-8px);
            border-color: var(--gold-light);
            box-shadow: var(--gold-glow);
        }

        .package-card.popular {
            border: 2px solid var(--gold-primary);
            box-shadow: var(--gold-glow);
        }

        .package-badge {
            width: 48px;
            height: 48px;
            background: var(--gold-gradient);
            color: #000;
            font-weight: 900;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem auto;
            font-size: 1.1rem;
            box-shadow: 0 0 15px rgba(245, 184, 46, 0.4);
        }

        .package-price {
            font-size: 2.8rem;
            font-weight: 900;
            color: #fff;
            margin: 0.5rem 0 1.2rem 0;
        }

        .package-details {
            list-style: none;
            margin-bottom: 2rem;
            text-align: left;
        }

        .package-details li {
            padding: 0.65rem 0;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            display: flex;
            justify-content: space-between;
            font-size: 0.92rem;
        }

        .package-details li span { color: var(--text-muted); }
        .package-details li strong { color: #fff; }

        /* --- Estimator Calculator Card --- */
        .calc-card {
            background: rgba(14, 18, 28, 0.95);
            border: 2px solid var(--gold-primary);
            border-radius: 24px;
            padding: 2.5rem;
            max-width: 800px;
            margin: 0 auto 6rem auto;
            box-shadow: var(--gold-glow);
        }

        .calc-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .form-control {
            width: 100%;
            background: rgba(8, 10, 15, 0.8);
            border: 1px solid var(--border-gold);
            padding: 0.8rem 1.2rem;
            border-radius: 12px;
            color: #fff;
            font-size: 1rem;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--gold-light);
            box-shadow: var(--gold-glow);
        }

        /* --- Income Breakdown Tables --- */
        .table-card {
            background: var(--bg-card);
            border: 1px solid var(--border-gold);
            border-radius: 24px;
            padding: 2.2rem;
            margin-bottom: 3rem;
            backdrop-filter: blur(20px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.5);
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .custom-table th, .custom-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .custom-table th {
            background: rgba(245, 184, 46, 0.1);
            color: var(--gold-light);
            font-size: 0.85rem;
            text-transform: uppercase;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .custom-table td {
            font-size: 0.95rem;
        }

        /* --- Footer --- */
        footer {
            background: rgba(7, 9, 14, 0.95);
            border-top: 1px solid var(--border-gold);
            padding: 4rem 0 2rem 0;
            margin-top: 4rem;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 3rem;
            margin-bottom: 3rem;
        }

        .footer-col h4 {
            color: var(--gold-light);
            font-size: 1rem;
            margin-bottom: 1.2rem;
            font-weight: 800;
        }

        .footer-col ul {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
        }

        .footer-col ul a {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .footer-col ul a:hover {
            color: var(--gold-light);
        }

        .copyright {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid rgba(255,255,255,0.06);
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        /* --- Mobile Responsiveness --- */
        @media (max-width: 992px) {
            .navbar { padding: 0.8rem 1.2rem; }
            .hero { padding: 7.5rem 0 3rem 0; }
            .hero-title { font-size: 2.5rem; }
            .hero-subtitle { font-size: 1.05rem; padding: 0 0.5rem; }
            .features-pills { grid-template-columns: repeat(2, 1fr); gap: 1rem; }
            .stats-bar { grid-template-columns: repeat(2, 1fr); gap: 1rem; padding: 1.4rem 1rem; }
            .packages-grid { grid-template-columns: 1fr; gap: 1.5rem; }
            .about-grid { grid-template-columns: 1fr; gap: 1.5rem; }
            .nav-links, .nav-actions { display: none; }
            .mobile-menu-toggle { display: block; }
            .table-card { padding: 1.2rem 1rem; }
            .calc-card { padding: 1.5rem 1.2rem; }
        }

        @media (max-width: 600px) {
            .container { padding: 0 1rem; }
            .hero { padding: 6.5rem 0 2.5rem 0; }
            .hero-floating-logo { width: 85px; height: 85px; }
            .hero-title { font-size: 1.8rem; line-height: 1.2; }
            .hero-subtitle { font-size: 0.95rem; margin-bottom: 1.8rem; }
            .hero-cta { flex-direction: column; width: 100%; gap: 0.8rem; }
            .hero-cta a { width: 100%; justify-content: center; }
            .features-pills { grid-template-columns: 1fr; }
            .stats-bar { grid-template-columns: 1fr; gap: 1rem; }
            .stat-item p { font-size: 1.35rem; }
            .calc-grid { grid-template-columns: 1fr; }
            .footer-grid { grid-template-columns: 1fr; gap: 2rem; }
            .section-title h2 { font-size: 1.8rem; }
            .section-title h3 { font-size: 0.8rem; }
            .table-card { padding: 1rem 0.8rem; border-radius: 18px; }
            .custom-table th, .custom-table td { padding: 0.75rem 0.6rem; font-size: 0.8rem; }
        }

        @media (max-width: 380px) {
            .hero-title { font-size: 1.55rem; }
            .brand-text { font-size: 1.15rem; }
            .package-price { font-size: 2.2rem; }
        }

        /* --- Mobile Responsive Table-to-Card Cards --- */
        @media (max-width: 768px) {
            .mobile-responsive-table {
                border: none !important;
                background: transparent !important;
                overflow: visible !important;
            }
            .mobile-responsive-table table, 
            .mobile-responsive-table thead, 
            .mobile-responsive-table tbody, 
            .mobile-responsive-table th, 
            .mobile-responsive-table td, 
            .mobile-responsive-table tr {
                display: block !important;
                width: 100% !important;
            }
            
            .mobile-responsive-table thead tr {
                position: absolute !important;
                top: -9999px !important;
                left: -9999px !important;
            }
            
            .mobile-responsive-table tr {
                background: rgba(14, 18, 28, 0.95) !important;
                border: 1px solid var(--border-gold) !important;
                border-radius: 16px !important;
                padding: 1.2rem !important;
                margin-bottom: 1rem !important;
                box-shadow: 0 10px 25px rgba(0,0,0,0.5) !important;
            }

            .mobile-responsive-table tr:last-child {
                margin-bottom: 0 !important;
            }

            .mobile-responsive-table td {
                border: none !important;
                border-bottom: 1px dashed rgba(255,255,255,0.1) !important;
                position: relative !important;
                padding: 0.75rem 0 !important;
                display: flex !important;
                justify-content: space-between !important;
                align-items: center !important;
                font-size: 0.9rem !important;
                text-align: right !important;
                gap: 10px !important;
            }

            .mobile-responsive-table td:last-child {
                border-bottom: none !important;
                padding-bottom: 0 !important;
            }

            .mobile-responsive-table td::before {
                content: attr(data-label);
                font-weight: 800;
                color: var(--gold-light);
                font-size: 0.75rem;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                text-align: left;
                flex-shrink: 0;
            }
        }

        .live-pulse-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid #10b981;
            color: #34d399;
            font-size: 0.75rem;
            font-weight: 800;
            padding: 2px 10px;
            border-radius: 20px;
        }
        .live-pulse-dot {
            width: 7px;
            height: 7px;
            background: #34d399;
            border-radius: 50%;
            box-shadow: 0 0 8px #34d399;
            animation: pulseDot 1.5s infinite;
        }
        @keyframes pulseDot {
            0% { transform: scale(0.9); opacity: 0.7; }
            50% { transform: scale(1.3); opacity: 1; }
            100% { transform: scale(0.9); opacity: 0.7; }
        }
    </style>
</head>
<body>
    <!-- High-End Interactive Gold Particle Canvas -->
    <canvas id="heroParticlesCanvas" style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: -1; pointer-events: none;"></canvas>

    <!-- Header Navigation -->
    <nav class="navbar">
        <a href="#" class="nav-brand">
            <img src="{{ asset('images/logo.png') }}" alt="Binalex Logo" class="brand-logo-img">
            <span class="brand-text">BINALEX</span>
        </a>

        <ul class="nav-links">
            <li><a href="#about">About</a></li>
            <li><a href="#packages">Staking Plans</a></li>
            <li><a href="#calculator">Estimator</a></li>
            <li><a href="#incomes">Rewards</a></li>
            <li><a href="#terms">Terms</a></li>
        </ul>

        <div class="nav-actions">
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="btn-gold"><i class="fa-solid fa-gauge"></i> Admin Dashboard</a>
                @else
                    <a href="{{ route('user.dashboard') }}" class="btn-gold"><i class="fa-solid fa-chart-line"></i> User Dashboard</a>
                @endif
            @else
                <a href="{{ route('user.login') }}" class="btn-outline"><i class="fa-solid fa-right-to-bracket"></i> Sign In</a>
                <a href="{{ route('user.register') }}" class="btn-gold"><i class="fa-solid fa-user-plus"></i> Get Started</a>
            @endauth
        </div>

        <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
            <i class="fa-solid fa-bars"></i>
        </button>
    </nav>

    <!-- Mobile Drawer -->
    <div class="mobile-menu-overlay" id="mobileMenuOverlay" onclick="toggleMobileMenu()"></div>
    <div class="mobile-menu-drawer" id="mobileMenuDrawer">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <a href="#" class="nav-brand" style="text-decoration: none;">
                <img src="{{ asset('images/logo.png') }}" alt="Binalex Logo" class="brand-logo-img" style="height: 38px; width: 38px;">
                <span class="brand-text" style="font-size: 1.1rem;">BINALEX</span>
            </a>
            <button onclick="toggleMobileMenu()" style="background: none; border: none; color: var(--gold-light); font-size: 1.4rem;"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <ul style="list-style: none; display: flex; flex-direction: column; gap: 1.2rem; margin-bottom: 2rem;">
            <li><a href="#about" onclick="toggleMobileMenu()" style="color: #fff; text-decoration: none; font-weight: 600;"><i class="fa-solid fa-circle-info" style="color: var(--gold-light);"></i> About Binalex</a></li>
            <li><a href="#packages" onclick="toggleMobileMenu()" style="color: #fff; text-decoration: none; font-weight: 600;"><i class="fa-solid fa-cubes" style="color: var(--gold-light);"></i> Staking Plans</a></li>
            <li><a href="#calculator" onclick="toggleMobileMenu()" style="color: #fff; text-decoration: none; font-weight: 600;"><i class="fa-solid fa-calculator" style="color: var(--gold-light);"></i> Live Estimator</a></li>
            <li><a href="#incomes" onclick="toggleMobileMenu()" style="color: #fff; text-decoration: none; font-weight: 600;"><i class="fa-solid fa-trophy" style="color: var(--gold-light);"></i> Earning Opportunities</a></li>
            <li><a href="#terms" onclick="toggleMobileMenu()" style="color: #fff; text-decoration: none; font-weight: 600;"><i class="fa-solid fa-clipboard-list" style="color: var(--gold-light);"></i> Terms & Rules</a></li>
        </ul>

        <div style="display: flex; flex-direction: column; gap: 1rem;">
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="btn-gold" style="justify-content: center;"><i class="fa-solid fa-gauge"></i> Admin Dashboard</a>
                @else
                    <a href="{{ route('user.dashboard') }}" class="btn-gold" style="justify-content: center;"><i class="fa-solid fa-chart-line"></i> User Dashboard</a>
                @endif
            @else
                <a href="{{ route('user.login') }}" class="btn-outline" style="justify-content: center;"><i class="fa-solid fa-right-to-bracket"></i> Sign In</a>
                <a href="{{ route('user.register') }}" class="btn-gold" style="justify-content: center;"><i class="fa-solid fa-user-plus"></i> Get Started</a>
            @endauth
        </div>
    </div>

    <!-- Main Content Container -->
    <div class="container">
        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-header-box">
                <img src="{{ asset('images/logo.png') }}" alt="Binalex Logo 3D" class="hero-floating-logo">
                <div class="hero-badge">
                    <span class="live-pulse-dot"></span> DECENTRALIZED TOKEN ECOSYSTEM
                </div>
            </div>

            <h1 class="hero-title">
                THE FUTURE OF DECENTRALIZED<br><span>STAKING & EARNINGS</span>
            </h1>

            <p class="hero-subtitle">
                BINALEX is engineered to empower a global network of investors. Stake BINALEX coins, earn daily ROI yield up to 3.0x return, and unlock a 4-Fold income reward ecosystem.
            </p>

            <div class="hero-cta">
                <a href="{{ route('user.register') }}" class="btn-gold" style="padding: 0.85rem 2rem; font-size: 1rem;"><i class="fa-solid fa-rocket"></i> GET STARTED NOW</a>
                <a href="#calculator" class="btn-outline" style="padding: 0.85rem 2rem; font-size: 1rem;"><i class="fa-solid fa-calculator"></i> LIVE ESTIMATOR</a>
            </div>

            <!-- Features Pills -->
            <div class="features-pills">
                <div class="pill-card">
                    <div class="pill-icon"><i class="fa-solid fa-shield-halved"></i></div>
                    <div>
                        <strong style="display: block; color: #fff; font-size: 0.95rem;">BEP20 Network</strong>
                        <span style="font-size: 0.8rem; color: var(--text-muted);">Binance Smart Chain</span>
                    </div>
                </div>
                <div class="pill-card">
                    <div class="pill-icon"><i class="fa-solid fa-bolt"></i></div>
                    <div>
                        <strong style="display: block; color: #fff; font-size: 0.95rem;">1% Daily Yield</strong>
                        <span style="font-size: 0.8rem; color: var(--text-muted);">300 Days Growth</span>
                    </div>
                </div>
                <div class="pill-card">
                    <div class="pill-icon"><i class="fa-solid fa-right-left"></i></div>
                    <div>
                        <strong style="display: block; color: #fff; font-size: 0.95rem;">P2P Transfers</strong>
                        <span style="font-size: 0.8rem; color: var(--text-muted);">Instant Coin Moves</span>
                    </div>
                </div>
                <div class="pill-card">
                    <div class="pill-icon"><i class="fa-solid fa-trophy"></i></div>
                    <div>
                        <strong style="display: block; color: #fff; font-size: 0.95rem;">Rank Rewards</strong>
                        <span style="font-size: 0.8rem; color: var(--text-muted);">60:40 Power Leg Rule</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Live Valuation Stats Bar -->
        @php
            $pricing = \App\Models\TokenPricing::first();
            $rate = $pricing ? (float) $pricing->coin_rate_usd : 0.0200;
        @endphp

        <div class="stats-bar reveal-on-scroll">
            <div class="stat-item">
                <h4>TOTAL TOKEN SUPPLY POOL</h4>
                <p>{{ number_format($pricing->total_pool_coins ?? 15000000, 0) }} COINS</p>
            </div>
            <div class="stat-item">
                <h4>VALUATION POOL (USD)</h4>
                <p style="color: #34d399;">${{ number_format($pricing->total_pool_usd ?? 300000, 2) }} USD</p>
            </div>
            <div class="stat-item">
                <h4>LIVE EXCHANGE RATE</h4>
                <p>${{ number_format($rate, 4) }} USD</p>
            </div>
            <div class="stat-item">
                <h4>MINIMUM WITHDRAWAL</h4>
                <p style="color: #38bdf8;">5 COINS</p>
            </div>
        </div>

        <!-- About Section -->
        <section id="about" class="reveal-on-scroll">
            <div class="section-title">
                <h3>THE VISION & MISSION</h3>
                <h2>ABOUT BINALEX ECOSYSTEM</h2>
            </div>

            <div class="about-grid">
                <div class="about-card">
                    <h3><i class="fa-solid fa-eye"></i> Our Vision</h3>
                    <p>
                        BINALEX is built on the foundation of financial sovereignty, multi-chain liquidity, and community prosperity. We provide a transparent, algorithmically balanced ecosystem where every token holder earns daily ROI and team growth bonuses directly in BINALEX coins.
                    </p>
                </div>
                <div class="about-card">
                    <h3><i class="fa-solid fa-bullseye"></i> Our Mission</h3>
                    <p>
                        To pioneer a robust decentralized token model with dynamic bonding curve pricing, instant USDT deposits, P2P token liquidity, and automated reward distribution for network leaders worldwide.
                    </p>
                </div>
            </div>
        </section>

        <!-- Staking Packages Section -->
        <section id="packages" class="reveal-on-scroll">
            <div class="section-title">
                <h3>CHOICE OF STAKING PLANS</h3>
                <h2>SELF STAKE PLANS</h2>
            </div>

            <div class="packages-grid">
                <!-- $2 Plan -->
                <div class="package-card">
                    <div class="package-badge">$2</div>
                    <h3 style="color: var(--gold-light); font-size: 1.4rem;">$2 STAKE PLAN</h3>
                    <div class="package-price">$2 <span style="font-size: 1rem; color: var(--gold-light);">= 10 COINS</span></div>
                    <div style="color: var(--text-muted); font-size: 0.85rem; margin-top: -0.8rem; margin-bottom: 1rem;">(10 Coins Staked at Live Rate)</div>

                    <ul class="package-details">
                        <li><span>Coins Staked:</span> <strong>10.00 Coins</strong></li>
                        <li><span>Staking Duration:</span> <strong>300 Days</strong></li>
                        <li><span>Multiplier:</span> <strong style="color: var(--gold-light);">2.00x Total Yield</strong></li>
                        <li><span>Total Return:</span> <strong style="color: var(--gold-light);">20.00 Coins</strong></li>
                        <li><span>Daily Return:</span> <strong style="color: #34d399;">0.06 Coins/Day</strong></li>
                        <li><span>Direct Referral Bonus:</span> <strong>10% (1.00 Coin)</strong></li>
                    </ul>

                    <a href="{{ route('user.register') }}" class="btn-gold" style="width: 100%; justify-content: center;"><i class="fa-solid fa-cart-shopping"></i> STAKE $2 PLAN</a>
                </div>

                <!-- $4 Plan -->
                <div class="package-card popular">
                    <div style="position: absolute; top: -12px; left: 50%; transform: translateX(-50%); background: var(--gold-gradient); color: #000; font-weight: 800; font-size: 0.75rem; padding: 3px 14px; border-radius: 20px; text-transform: uppercase;">MOST POPULAR</div>
                    <div class="package-badge">$4</div>
                    <h3 style="color: var(--gold-light); font-size: 1.4rem;">$4 STAKE PLAN</h3>
                    <div class="package-price">$4 <span style="font-size: 1rem; color: var(--gold-light);">= 20 COINS</span></div>
                    <div style="color: var(--text-muted); font-size: 0.85rem; margin-top: -0.8rem; margin-bottom: 1rem;">(20 Coins Staked at Live Rate)</div>

                    <ul class="package-details">
                        <li><span>Coins Staked:</span> <strong>20.00 Coins</strong></li>
                        <li><span>Staking Duration:</span> <strong>300 Days</strong></li>
                        <li><span>Multiplier:</span> <strong style="color: var(--gold-light);">2.50x Total Yield</strong></li>
                        <li><span>Total Return:</span> <strong style="color: var(--gold-light);">50.00 Coins</strong></li>
                        <li><span>Daily Return:</span> <strong style="color: #34d399;">0.16 Coins/Day</strong></li>
                        <li><span>Direct Referral Bonus:</span> <strong>17.5% (3.50 Coins)</strong></li>
                    </ul>

                    <a href="{{ route('user.register') }}" class="btn-gold" style="width: 100%; justify-content: center; padding: 0.85rem;"><i class="fa-solid fa-rocket"></i> STAKE $4 PLAN</a>
                </div>

                <!-- $6 Plan -->
                <div class="package-card">
                    <div class="package-badge">$6</div>
                    <h3 style="color: var(--gold-light); font-size: 1.4rem;">$6 STAKE PLAN</h3>
                    <div class="package-price">$6 <span style="font-size: 1rem; color: var(--gold-light);">= 30 COINS</span></div>
                    <div style="color: var(--text-muted); font-size: 0.85rem; margin-top: -0.8rem; margin-bottom: 1rem;">(30 Coins Staked at Live Rate)</div>

                    <ul class="package-details">
                        <li><span>Coins Staked:</span> <strong>30.00 Coins</strong></li>
                        <li><span>Staking Duration:</span> <strong>300 Days</strong></li>
                        <li><span>Multiplier:</span> <strong style="color: var(--gold-light);">3.00x Total Yield</strong></li>
                        <li><span>Total Return:</span> <strong style="color: var(--gold-light);">90.00 Coins</strong></li>
                        <li><span>Daily Return:</span> <strong style="color: #34d399;">0.30 Coins/Day</strong></li>
                        <li><span>Direct Referral Bonus:</span> <strong>30% (9.00 Coins)</strong></li>
                    </ul>

                    <a href="{{ route('user.register') }}" class="btn-gold" style="width: 100%; justify-content: center;"><i class="fa-solid fa-cart-shopping"></i> STAKE $6 PLAN</a>
                </div>
            </div>
        </section>

        <!-- Interactive Calculator Section -->
        <section id="calculator" class="reveal-on-scroll">
            <div class="section-title">
                <h3>REAL-TIME ESTIMATOR</h3>
                <h2>TOKEN STAKING CALCULATOR</h2>
            </div>

            <div class="calc-card">
                <div style="text-align: center; margin-bottom: 1.5rem;">
                    <span style="color: var(--gold-light); font-weight: 700;">Current Live Exchange Rate:</span>
                    <strong style="color: #fff; font-size: 1.2rem;">${{ number_format($rate, 4) }} USD / Coin</strong>
                    <span class="live-pulse-badge" style="margin-left: 8px;"><span class="live-pulse-dot"></span> LIVE</span>
                </div>

                <div class="calc-grid">
                    <div>
                        <label style="display: block; margin-bottom: 0.4rem; font-size: 0.85rem; color: var(--gold-light); font-weight: 700;">STAKING AMOUNT ($ USD)</label>
                        <input type="number" id="calcUsdInput" class="form-control" value="10" min="1" step="1" oninput="calculateStakingYield()">
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 0.4rem; font-size: 0.85rem; color: var(--gold-light); font-weight: 700;">COINS CREDIT</label>
                        <input type="text" id="calcStakedCoins" class="form-control" readonly style="background: rgba(255,255,255,0.05); color: var(--gold-light); font-weight: 800;">
                    </div>
                </div>

                <div class="calc-grid" style="margin-top: 1rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.4rem; font-size: 0.85rem; color: var(--gold-light); font-weight: 700;">DAILY ROI YIELD (1%)</label>
                        <input type="text" id="calcDailyRoi" class="form-control" readonly style="background: rgba(255,255,255,0.05); color: #34d399; font-weight: 800;">
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 0.4rem; font-size: 0.85rem; color: var(--gold-light); font-weight: 700;">TOTAL RETURN (COINS)</label>
                        <input type="text" id="calcTotalYield" class="form-control" readonly style="background: rgba(255,255,255,0.05); color: var(--gold-light); font-weight: 900;">
                    </div>
                </div>
            </div>
        </section>

        <!-- Incomes Breakdown & Tables -->
        <section id="incomes" class="reveal-on-scroll">
            <div class="section-title">
                <h3>FOUR-FOLD REWARD ECOSYSTEM</h3>
                <h2>EARNING OPPORTUNITIES IN BINALEX COINS</h2>
            </div>

            <!-- Direct Referral Income Card -->
            <div class="table-card">
                <h3 style="color: var(--gold-light); margin-bottom: 1.2rem;"><i class="fa-solid fa-bolt"></i> Direct Referral Income</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.2rem;">
                    <div style="background: rgba(8,10,15,0.6); border: 1px solid var(--border-gold); padding: 1.2rem; border-radius: 16px; text-align: center;">
                        <span style="font-size: 0.8rem; color: var(--text-muted); font-weight: 800;">$2 PLAN (10 COINS)</span>
                        <div style="font-size: 1.6rem; font-weight: 900; color: var(--gold-light); margin: 0.4rem 0;">10% BONUS</div>
                        <div style="color: #34d399; font-weight: 800; font-size: 1.1rem;">1.00 Coin</div>
                    </div>
                    <div style="background: rgba(8,10,15,0.6); border: 1px solid var(--border-gold); padding: 1.2rem; border-radius: 16px; text-align: center;">
                        <span style="font-size: 0.8rem; color: var(--text-muted); font-weight: 800;">$4 PLAN (20 COINS)</span>
                        <div style="font-size: 1.6rem; font-weight: 900; color: var(--gold-light); margin: 0.4rem 0;">17.5% BONUS</div>
                        <div style="color: #34d399; font-weight: 800; font-size: 1.1rem;">3.50 Coins</div>
                    </div>
                    <div style="background: rgba(8,10,15,0.6); border: 1px solid var(--border-gold); padding: 1.2rem; border-radius: 16px; text-align: center;">
                        <span style="font-size: 0.8rem; color: var(--text-muted); font-weight: 800;">$6 PLAN (30 COINS)</span>
                        <div style="font-size: 1.6rem; font-weight: 900; color: var(--gold-light); margin: 0.4rem 0;">30% BONUS</div>
                        <div style="color: #34d399; font-weight: 800; font-size: 1.1rem;">9.00 Coins</div>
                    </div>
                </div>
            </div>

            <!-- 10-Level Team Referral Income Table -->
            <div class="table-card" style="border-top: 3px solid var(--gold-light);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 10px;">
                    <div>
                        <span class="live-pulse-badge" style="font-size: 0.72rem; margin-bottom: 4px;">UNLIMITED TEAM LEVERAGE</span>
                        <h3 style="color: var(--gold-light); font-size: 1.5rem; font-weight: 800; margin: 0;"><i class="fa-solid fa-sitemap"></i> 10-Level Team Referral Income</h3>
                    </div>
                    <span style="color: #34d399; font-weight: 800; font-size: 0.88rem; background: rgba(16, 185, 129, 0.15); border: 1px solid #10b981; padding: 6px 14px; border-radius: 20px;"><i class="fa-solid fa-coins"></i> Paid Directly in BINALEX Coins</span>
                </div>

                <div class="table-responsive mobile-responsive-table" style="border-radius: 16px; overflow: hidden; border: 1px solid var(--border-gold);">
                    <table class="custom-table" style="margin-top: 0; background: rgba(8, 10, 15, 0.75);">
                        <thead>
                            <tr style="background: linear-gradient(135deg, rgba(245, 184, 46, 0.2) 0%, rgba(14, 18, 28, 0.9) 100%);">
                                <th style="padding: 1.1rem 1.2rem; font-size: 0.82rem; color: var(--gold-light); font-weight: 900; letter-spacing: 1px;"><i class="fa-solid fa-layer-group"></i> LEVEL GENERATION</th>
                                <th style="padding: 1.1rem 1.2rem; font-size: 0.82rem; color: var(--gold-light); font-weight: 900; letter-spacing: 1px;"><i class="fa-solid fa-percent"></i> COMMISSION RATE</th>
                                <th style="padding: 1.1rem 1.2rem; font-size: 0.82rem; color: var(--gold-light); font-weight: 900; letter-spacing: 1px;"><i class="fa-solid fa-user-check"></i> SPONSOR REQUIREMENT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="transition: all 0.2s ease;">
                                <td data-label="LEVEL GENERATION" style="padding: 1rem 1.2rem;"><span class="badge-status badge-active" style="background: var(--gold-gradient); color: #000; font-weight: 900; font-size: 0.78rem; padding: 4px 12px; border-radius: 20px;">LEVEL 1</span> <strong style="color: #fff; margin-left: 8px;">Direct Team Bonus</strong></td>
                                <td data-label="COMMISSION RATE" style="padding: 1rem 1.2rem;"><strong style="color: var(--gold-light); font-size: 1.25rem; font-weight: 900; text-shadow: 0 0 10px rgba(245,184,46,0.3);">7.00%</strong></td>
                                <td data-label="SPONSOR REQUIREMENT" style="padding: 1rem 1.2rem;"><span class="badge-status badge-approved" style="font-size: 0.8rem; padding: 4px 12px;"><i class="fa-solid fa-check-circle"></i> No Direct Requirement</span></td>
                            </tr>
                            <tr style="transition: all 0.2s ease;">
                                <td data-label="LEVEL GENERATION" style="padding: 1rem 1.2rem;"><span class="badge-status" style="background: rgba(56, 189, 248, 0.2); border: 1px solid #38bdf8; color: #38bdf8; font-weight: 900; font-size: 0.78rem; padding: 4px 12px; border-radius: 20px;">LEVEL 2</span> <strong style="color: #fff; margin-left: 8px;">2nd Generation</strong></td>
                                <td data-label="COMMISSION RATE" style="padding: 1rem 1.2rem;"><strong style="color: #38bdf8; font-size: 1.25rem; font-weight: 900; text-shadow: 0 0 10px rgba(56,189,248,0.3);">6.00%</strong></td>
                                <td data-label="SPONSOR REQUIREMENT" style="padding: 1rem 1.2rem;"><span class="badge-status badge-active" style="font-size: 0.8rem; padding: 4px 12px;"><i class="fa-solid fa-user-plus"></i> Requires 2 Active Directs</span></td>
                            </tr>
                            <tr style="transition: all 0.2s ease;">
                                <td data-label="LEVEL GENERATION" style="padding: 1rem 1.2rem;"><span class="badge-status" style="background: rgba(52, 211, 153, 0.2); border: 1px solid #34d399; color: #34d399; font-weight: 900; font-size: 0.78rem; padding: 4px 12px; border-radius: 20px;">LEVEL 3</span> <strong style="color: #fff; margin-left: 8px;">3rd Generation</strong></td>
                                <td data-label="COMMISSION RATE" style="padding: 1rem 1.2rem;"><strong style="color: #34d399; font-size: 1.25rem; font-weight: 900; text-shadow: 0 0 10px rgba(52,211,153,0.3);">5.00%</strong></td>
                                <td data-label="SPONSOR REQUIREMENT" style="padding: 1rem 1.2rem;"><span class="badge-status badge-approved" style="font-size: 0.8rem; padding: 4px 12px;"><i class="fa-solid fa-user-plus"></i> Requires +1 Direct Referral</span></td>
                            </tr>
                            <tr style="transition: all 0.2s ease;">
                                <td data-label="LEVEL GENERATION" style="padding: 1rem 1.2rem;"><span class="badge-status" style="background: rgba(168, 85, 247, 0.2); border: 1px solid #a855f7; color: #c084fc; font-weight: 900; font-size: 0.78rem; padding: 4px 12px; border-radius: 20px;">LEVEL 4</span> <strong style="color: #fff; margin-left: 8px;">4th Generation</strong></td>
                                <td data-label="COMMISSION RATE" style="padding: 1rem 1.2rem;"><strong style="color: #c084fc; font-size: 1.25rem; font-weight: 900;">4.00%</strong></td>
                                <td data-label="SPONSOR REQUIREMENT" style="padding: 1rem 1.2rem;"><span class="badge-status" style="background: rgba(168, 85, 247, 0.15); color: #c084fc; font-size: 0.8rem; padding: 4px 12px;"><i class="fa-solid fa-user-plus"></i> Requires +1 Direct Referral</span></td>
                            </tr>
                            <tr style="transition: all 0.2s ease;">
                                <td data-label="LEVEL GENERATION" style="padding: 1rem 1.2rem;"><span class="badge-status" style="background: rgba(245, 158, 11, 0.2); border: 1px solid #f59e0b; color: #fbbf24; font-weight: 900; font-size: 0.78rem; padding: 4px 12px; border-radius: 20px;">LEVEL 5</span> <strong style="color: #fff; margin-left: 8px;">5th Generation</strong></td>
                                <td data-label="COMMISSION RATE" style="padding: 1rem 1.2rem;"><strong style="color: #fbbf24; font-size: 1.25rem; font-weight: 900;">3.00%</strong></td>
                                <td data-label="SPONSOR REQUIREMENT" style="padding: 1rem 1.2rem;"><span class="badge-status badge-pending" style="font-size: 0.8rem; padding: 4px 12px;"><i class="fa-solid fa-user-plus"></i> Requires +1 Direct Referral</span></td>
                            </tr>
                            <tr style="transition: all 0.2s ease;">
                                <td data-label="LEVEL GENERATION" style="padding: 1rem 1.2rem;"><span class="badge-status" style="background: rgba(244, 63, 94, 0.2); border: 1px solid #f43f5e; color: #fb7185; font-weight: 900; font-size: 0.78rem; padding: 4px 12px; border-radius: 20px;">LEVEL 6</span> <strong style="color: #fff; margin-left: 8px;">6th Generation</strong></td>
                                <td data-label="COMMISSION RATE" style="padding: 1rem 1.2rem;"><strong style="color: #fb7185; font-size: 1.25rem; font-weight: 900;">2.00%</strong></td>
                                <td data-label="SPONSOR REQUIREMENT" style="padding: 1rem 1.2rem;"><span class="badge-status badge-rejected" style="font-size: 0.8rem; padding: 4px 12px;"><i class="fa-solid fa-user-plus"></i> Requires +1 Direct Referral</span></td>
                            </tr>
                            <tr style="transition: all 0.2s ease;">
                                <td data-label="LEVEL GENERATION" style="padding: 1rem 1.2rem;"><span class="badge-status" style="background: rgba(99, 102, 241, 0.2); border: 1px solid #6366f1; color: #818cf8; font-weight: 900; font-size: 0.78rem; padding: 4px 12px; border-radius: 20px;">LEVEL 7</span> <strong style="color: #fff; margin-left: 8px;">7th Generation</strong></td>
                                <td data-label="COMMISSION RATE" style="padding: 1rem 1.2rem;"><strong style="color: #818cf8; font-size: 1.25rem; font-weight: 900;">1.00%</strong></td>
                                <td data-label="SPONSOR REQUIREMENT" style="padding: 1rem 1.2rem;"><span class="badge-status" style="background: rgba(99, 102, 241, 0.15); color: #818cf8; font-size: 0.8rem; padding: 4px 12px;"><i class="fa-solid fa-user-plus"></i> Requires +1 Direct Referral</span></td>
                            </tr>
                            <tr style="transition: all 0.2s ease;">
                                <td data-label="LEVEL GENERATION" style="padding: 1rem 1.2rem;"><span class="badge-status" style="background: rgba(20, 184, 166, 0.2); border: 1px solid #20b2aa; color: #2dd4bf; font-weight: 900; font-size: 0.78rem; padding: 4px 12px; border-radius: 20px;">LEVEL 8</span> <strong style="color: #fff; margin-left: 8px;">8th Generation</strong></td>
                                <td data-label="COMMISSION RATE" style="padding: 1rem 1.2rem;"><strong style="color: #2dd4bf; font-size: 1.25rem; font-weight: 900;">1.00%</strong></td>
                                <td data-label="SPONSOR REQUIREMENT" style="padding: 1rem 1.2rem;"><span class="badge-status" style="background: rgba(20, 184, 166, 0.15); color: #2dd4bf; font-size: 0.8rem; padding: 4px 12px;"><i class="fa-solid fa-user-plus"></i> Requires +1 Direct Referral</span></td>
                            </tr>
                            <tr style="transition: all 0.2s ease;">
                                <td data-label="LEVEL GENERATION" style="padding: 1rem 1.2rem;"><span class="badge-status" style="background: rgba(245, 184, 46, 0.2); border: 1px solid var(--gold-primary); color: var(--gold-light); font-weight: 900; font-size: 0.78rem; padding: 4px 12px; border-radius: 20px;">LEVEL 9 - 10</span> <strong style="color: #fff; margin-left: 8px;">Deep Network Bonus</strong></td>
                                <td data-label="COMMISSION RATE" style="padding: 1rem 1.2rem;"><strong style="color: var(--gold-light); font-size: 1.25rem; font-weight: 900;">0.50%</strong></td>
                                <td data-label="SPONSOR REQUIREMENT" style="padding: 1rem 1.2rem;"><span class="badge-status badge-approved" style="font-size: 0.8rem; padding: 4px 12px;"><i class="fa-solid fa-user-plus"></i> Requires +1 Direct Referral per level</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Award & Rank Rewards Table -->
            <div class="table-card" style="border-top: 3px solid #34d399;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 10px;">
                    <div>
                        <span class="live-pulse-badge" style="font-size: 0.72rem; margin-bottom: 4px; background: rgba(52, 211, 153, 0.15); border-color: #34d399; color: #34d399;">60:40 POWER LEG RULE</span>
                        <h3 style="color: var(--gold-light); font-size: 1.5rem; font-weight: 800; margin: 0;"><i class="fa-solid fa-trophy"></i> Award & Rank Rewards</h3>
                    </div>
                    <span style="color: var(--gold-light); font-weight: 800; font-size: 0.88rem; background: rgba(245, 184, 46, 0.15); border: 1px solid var(--gold-primary); padding: 6px 14px; border-radius: 20px;"><i class="fa-solid fa-award"></i> Up to 2,000 Coins Reward Bonus</span>
                </div>

                <div class="table-responsive mobile-responsive-table" style="border-radius: 16px; overflow: hidden; border: 1px solid var(--border-gold);">
                    <table class="custom-table" style="margin-top: 0; background: rgba(8, 10, 15, 0.75);">
                        <thead>
                            <tr style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.2) 0%, rgba(14, 18, 28, 0.9) 100%);">
                                <th style="padding: 1.1rem 1.2rem; font-size: 0.82rem; color: #34d399; font-weight: 900; letter-spacing: 1px;"><i class="fa-solid fa-crown"></i> RANK TITLE</th>
                                <th style="padding: 1.1rem 1.2rem; font-size: 0.82rem; color: #34d399; font-weight: 900; letter-spacing: 1px;"><i class="fa-solid fa-chart-line"></i> TEAM BUSINESS VOLUME</th>
                                <th style="padding: 1.1rem 1.2rem; font-size: 0.82rem; color: #34d399; font-weight: 900; letter-spacing: 1px;"><i class="fa-solid fa-shield-halved"></i> POWER LEG (60%)</th>
                                <th style="padding: 1.1rem 1.2rem; font-size: 0.82rem; color: #34d399; font-weight: 900; letter-spacing: 1px;"><i class="fa-solid fa-network-wired"></i> OTHER LEGS (40%)</th>
                                <th style="padding: 1.1rem 1.2rem; font-size: 0.82rem; color: #34d399; font-weight: 900; letter-spacing: 1px;"><i class="fa-solid fa-coins"></i> REWARD BONUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td data-label="RANK TITLE" style="padding: 1rem 1.2rem;"><strong style="color: var(--gold-light); font-size: 1rem;">🥉 Rank 1 Executive</strong></td>
                                <td data-label="BUSINESS VOLUME" style="padding: 1rem 1.2rem;"><strong style="color: #fff; font-size: 1.05rem;">$50.00 USD</strong></td>
                                <td data-label="POWER LEG (60%)" style="padding: 1rem 1.2rem;"><span style="color: #38bdf8; font-weight: 800;">$30.00 USD</span></td>
                                <td data-label="OTHER LEGS (40%)" style="padding: 1rem 1.2rem;"><span style="color: var(--gold-light); font-weight: 800;">$20.00 USD</span></td>
                                <td data-label="REWARD BONUS" style="padding: 1rem 1.2rem;"><span style="background: rgba(16, 185, 129, 0.15); border: 1px solid #10b981; color: #34d399; padding: 4px 14px; border-radius: 20px; font-weight: 900; font-size: 0.95rem;"><i class="fa-solid fa-coins"></i> 10 Coins</span></td>
                            </tr>
                            <tr>
                                <td data-label="RANK TITLE" style="padding: 1rem 1.2rem;"><strong style="color: var(--gold-light); font-size: 1rem;">🥈 Rank 2 Bronze Leader</strong></td>
                                <td data-label="BUSINESS VOLUME" style="padding: 1rem 1.2rem;"><strong style="color: #fff; font-size: 1.05rem;">$150.00 USD</strong></td>
                                <td data-label="POWER LEG (60%)" style="padding: 1rem 1.2rem;"><span style="color: #38bdf8; font-weight: 800;">$90.00 USD</span></td>
                                <td data-label="OTHER LEGS (40%)" style="padding: 1rem 1.2rem;"><span style="color: var(--gold-light); font-weight: 800;">$60.00 USD</span></td>
                                <td data-label="REWARD BONUS" style="padding: 1rem 1.2rem;"><span style="background: rgba(16, 185, 129, 0.15); border: 1px solid #10b981; color: #34d399; padding: 4px 14px; border-radius: 20px; font-weight: 900; font-size: 0.95rem;"><i class="fa-solid fa-coins"></i> 30 Coins</span></td>
                            </tr>
                            <tr>
                                <td data-label="RANK TITLE" style="padding: 1rem 1.2rem;"><strong style="color: var(--gold-light); font-size: 1rem;">🥇 Rank 3 Silver Leader</strong></td>
                                <td data-label="BUSINESS VOLUME" style="padding: 1rem 1.2rem;"><strong style="color: #fff; font-size: 1.05rem;">$400.00 USD</strong></td>
                                <td data-label="POWER LEG (60%)" style="padding: 1rem 1.2rem;"><span style="color: #38bdf8; font-weight: 800;">$240.00 USD</span></td>
                                <td data-label="OTHER LEGS (40%)" style="padding: 1rem 1.2rem;"><span style="color: var(--gold-light); font-weight: 800;">$160.00 USD</span></td>
                                <td data-label="REWARD BONUS" style="padding: 1rem 1.2rem;"><span style="background: rgba(16, 185, 129, 0.15); border: 1px solid #10b981; color: #34d399; padding: 4px 14px; border-radius: 20px; font-weight: 900; font-size: 0.95rem;"><i class="fa-solid fa-coins"></i> 60 Coins</span></td>
                            </tr>
                            <tr>
                                <td data-label="RANK TITLE" style="padding: 1rem 1.2rem;"><strong style="color: var(--gold-light); font-size: 1rem;">💎 Rank 4 Gold Director</strong></td>
                                <td data-label="BUSINESS VOLUME" style="padding: 1rem 1.2rem;"><strong style="color: #fff; font-size: 1.05rem;">$1,000.00 USD</strong></td>
                                <td data-label="POWER LEG (60%)" style="padding: 1rem 1.2rem;"><span style="color: #38bdf8; font-weight: 800;">$600.00 USD</span></td>
                                <td data-label="OTHER LEGS (40%)" style="padding: 1rem 1.2rem;"><span style="color: var(--gold-light); font-weight: 800;">$400.00 USD</span></td>
                                <td data-label="REWARD BONUS" style="padding: 1rem 1.2rem;"><span style="background: rgba(16, 185, 129, 0.15); border: 1px solid #10b981; color: #34d399; padding: 4px 14px; border-radius: 20px; font-weight: 900; font-size: 0.95rem;"><i class="fa-solid fa-coins"></i> 100 Coins</span></td>
                            </tr>
                            <tr>
                                <td data-label="RANK TITLE" style="padding: 1rem 1.2rem;"><strong style="color: var(--gold-light); font-size: 1rem;">💠 Rank 5 Platinum Master</strong></td>
                                <td data-label="BUSINESS VOLUME" style="padding: 1rem 1.2rem;"><strong style="color: #fff; font-size: 1.05rem;">$1,700.00 USD</strong></td>
                                <td data-label="POWER LEG (60%)" style="padding: 1rem 1.2rem;"><span style="color: #38bdf8; font-weight: 800;">$1,020.00 USD</span></td>
                                <td data-label="OTHER LEGS (40%)" style="padding: 1rem 1.2rem;"><span style="color: var(--gold-light); font-weight: 800;">$680.00 USD</span></td>
                                <td data-label="REWARD BONUS" style="padding: 1rem 1.2rem;"><span style="background: rgba(16, 185, 129, 0.15); border: 1px solid #10b981; color: #34d399; padding: 4px 14px; border-radius: 20px; font-weight: 900; font-size: 0.95rem;"><i class="fa-solid fa-coins"></i> 200 Coins</span></td>
                            </tr>
                            <tr>
                                <td data-label="RANK TITLE" style="padding: 1rem 1.2rem;"><strong style="color: var(--gold-light); font-size: 1rem;">👑 Rank 6 Diamond Ambassador</strong></td>
                                <td data-label="BUSINESS VOLUME" style="padding: 1rem 1.2rem;"><strong style="color: #fff; font-size: 1.05rem;">$3,000.00 USD</strong></td>
                                <td data-label="POWER LEG (60%)" style="padding: 1rem 1.2rem;"><span style="color: #38bdf8; font-weight: 800;">$1,800.00 USD</span></td>
                                <td data-label="OTHER LEGS (40%)" style="padding: 1rem 1.2rem;"><span style="color: var(--gold-light); font-weight: 800;">$1,200.00 USD</span></td>
                                <td data-label="REWARD BONUS" style="padding: 1rem 1.2rem;"><span style="background: rgba(16, 185, 129, 0.15); border: 1px solid #10b981; color: #34d399; padding: 4px 14px; border-radius: 20px; font-weight: 900; font-size: 0.95rem;"><i class="fa-solid fa-coins"></i> 500 Coins</span></td>
                            </tr>
                            <tr>
                                <td data-label="RANK TITLE" style="padding: 1rem 1.2rem;"><strong style="color: var(--gold-light); font-size: 1rem;">🌌 Rank 7 Crown President</strong></td>
                                <td data-label="BUSINESS VOLUME" style="padding: 1rem 1.2rem;"><strong style="color: #fff; font-size: 1.05rem;">$7,000.00 USD</strong></td>
                                <td data-label="POWER LEG (60%)" style="padding: 1rem 1.2rem;"><span style="color: #38bdf8; font-weight: 800;">$4,200.00 USD</span></td>
                                <td data-label="OTHER LEGS (40%)" style="padding: 1rem 1.2rem;"><span style="color: var(--gold-light); font-weight: 800;">$2,800.00 USD</span></td>
                                <td data-label="REWARD BONUS" style="padding: 1rem 1.2rem;"><span style="background: rgba(16, 185, 129, 0.15); border: 1px solid #10b981; color: #34d399; padding: 4px 14px; border-radius: 20px; font-weight: 900; font-size: 0.95rem;"><i class="fa-solid fa-coins"></i> 1,000 Coins</span></td>
                            </tr>
                            <tr style="background: rgba(245, 184, 46, 0.08);">
                                <td data-label="RANK TITLE" style="padding: 1rem 1.2rem;"><strong style="color: var(--gold-light); font-size: 1.05rem;">⚡ Rank 8 Global Legend</strong></td>
                                <td data-label="BUSINESS VOLUME" style="padding: 1rem 1.2rem;"><strong style="color: #fff; font-size: 1.1rem;">$15,000.00 USD</strong></td>
                                <td data-label="POWER LEG (60%)" style="padding: 1rem 1.2rem;"><span style="color: #38bdf8; font-weight: 800;">$9,000.00 USD</span></td>
                                <td data-label="OTHER LEGS (40%)" style="padding: 1rem 1.2rem;"><span style="color: var(--gold-light); font-weight: 800;">$6,000.00 USD</span></td>
                                <td data-label="REWARD BONUS" style="padding: 1rem 1.2rem;"><span style="background: var(--gold-gradient); color: #000; padding: 6px 16px; border-radius: 20px; font-weight: 900; font-size: 1rem; box-shadow: 0 0 15px rgba(245, 184, 46, 0.5);"><i class="fa-solid fa-crown"></i> 2,000 Coins</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- Terms & Conditions Section -->
        <section id="terms" class="reveal-on-scroll">
            <div class="section-title">
                <h3>TRANSPARENT SYSTEM RULES</h3>
                <h2>TERMS & CONDITIONS</h2>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.5rem; margin-bottom: 5rem;">
                <div class="pill-card" style="padding: 1.8rem; border-top: 3px solid var(--gold-light); flex-direction: column; align-items: flex-start; gap: 1rem; backdrop-filter: blur(25px); background: rgba(14, 18, 28, 0.9);">
                    <div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
                        <div class="pill-icon" style="width: 52px; height: 52px; font-size: 1.5rem;"><i class="fa-solid fa-hand-holding-dollar"></i></div>
                        <span class="live-pulse-badge" style="font-size: 0.72rem;">RULE #1</span>
                    </div>
                    <div>
                        <h4 style="color: #fff; font-size: 1.15rem; font-weight: 800; margin-bottom: 0.5rem;">Minimum Withdrawal Limit</h4>
                        <p style="font-size: 0.92rem; color: var(--text-muted); line-height: 1.6;">
                            The minimum coin withdrawal limit is <strong style="color: var(--gold-light);">5 Coins</strong>. Working income withdrawals are processed at a fixed rate of <strong style="color: #34d399;">$0.10 USD per coin</strong>.
                        </p>
                    </div>
                </div>

                <div class="pill-card" style="padding: 1.8rem; border-top: 3px solid #f87171; flex-direction: column; align-items: flex-start; gap: 1rem; backdrop-filter: blur(25px); background: rgba(14, 18, 28, 0.9);">
                    <div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
                        <div class="pill-icon" style="width: 52px; height: 52px; font-size: 1.5rem; background: rgba(239, 68, 68, 0.15); color: #f87171;"><i class="fa-solid fa-percent"></i></div>
                        <span class="live-pulse-badge" style="background: rgba(239, 68, 68, 0.15); border-color: #ef4444; color: #f87171; font-size: 0.72rem;">RULE #2</span>
                    </div>
                    <div>
                        <h4 style="color: #fff; font-size: 1.15rem; font-weight: 800; margin-bottom: 0.5rem;">Withdrawal Deduction</h4>
                        <p style="font-size: 0.92rem; color: var(--text-muted); line-height: 1.6;">
                            A standard <strong style="color: #f87171;">10% service deduction</strong> is applicable to all coin withdrawal payout requests across the ecosystem.
                        </p>
                    </div>
                </div>

                <div class="pill-card" style="padding: 1.8rem; border-top: 3px solid #38bdf8; flex-direction: column; align-items: flex-start; gap: 1rem; backdrop-filter: blur(25px); background: rgba(14, 18, 28, 0.9);">
                    <div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
                        <div class="pill-icon" style="width: 52px; height: 52px; font-size: 1.5rem; background: rgba(56, 189, 248, 0.15); color: #38bdf8;"><i class="fa-solid fa-sitemap"></i></div>
                        <span class="live-pulse-badge" style="background: rgba(56, 189, 248, 0.15); border-color: #0ea5e9; color: #38bdf8; font-size: 0.72rem;">RULE #3</span>
                    </div>
                    <div>
                        <h4 style="color: #fff; font-size: 1.15rem; font-weight: 800; margin-bottom: 0.5rem;">60:40 Reward Leg Rule</h4>
                        <p style="font-size: 0.92rem; color: var(--text-muted); line-height: 1.6;">
                            Award & Rank Rewards calculate <strong style="color: #38bdf8;">60% volume from Power Leg</strong> and <strong style="color: var(--gold-light);">40% volume from all other legs</strong> combined.
                        </p>
                    </div>
                </div>

                <div class="pill-card" style="padding: 1.8rem; border-top: 3px solid #34d399; flex-direction: column; align-items: flex-start; gap: 1rem; backdrop-filter: blur(25px); background: rgba(14, 18, 28, 0.9);">
                    <div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
                        <div class="pill-icon" style="width: 52px; height: 52px; font-size: 1.5rem; background: rgba(16, 185, 129, 0.15); color: #34d399;"><i class="fa-solid fa-shield-halved"></i></div>
                        <span class="live-pulse-badge" style="font-size: 0.72rem;">RULE #4</span>
                    </div>
                    <div>
                        <h4 style="color: #fff; font-size: 1.15rem; font-weight: 800; margin-bottom: 0.5rem;">USDT BEP20 Network</h4>
                        <p style="font-size: 0.92rem; color: var(--text-muted); line-height: 1.6;">
                            All USDT deposits and coin withdrawal payouts are powered on the <strong style="color: #34d399;">Binance Smart Chain (BEP20)</strong> network.
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Footer (Strictly Clean, NO Admin Links) -->
    <footer>
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <a href="#" class="nav-brand" style="margin-bottom: 1rem; display: flex; align-items: center; gap: 12px; text-decoration: none;">
                        <img src="{{ asset('images/logo.png') }}" alt="Binalex Logo" class="brand-logo-img">
                        <span class="brand-text">BINALEX</span>
                    </a>
                    <p style="font-size: 0.88rem; color: var(--text-muted); line-height: 1.6;">
                        Empowering a global network of modern investors through multi-chain blockchain innovation. Trade, Earn, and Grow together.
                    </p>
                </div>

                <div class="footer-col">
                    <h4>QUICK NAVIGATION</h4>
                    <ul>
                        <li><a href="#about">About Binalex</a></li>
                        <li><a href="#packages">Staking Plans</a></li>
                        <li><a href="#calculator">Estimator</a></li>
                        <li><a href="#incomes">Earning Opportunities</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4>MEMBER PORTAL</h4>
                    <ul>
                        <li><a href="{{ route('user.login') }}">User Sign In</a></li>
                        <li><a href="{{ route('user.register') }}">Create Account</a></li>
                    </ul>
                </div>
            </div>

            <div class="copyright">
                &copy; {{ date('Y') }} <strong>BINALEX</strong>. All Rights Reserved. Build • Earn • Grow.
            </div>
        </div>
    </footer>

    <script>
        const COIN_RATE = {{ $pricing->coin_rate_usd ?? 0.0200 }};

        // --- High-End Interactive Gold Particle Canvas ---
        const canvas = document.getElementById('heroParticlesCanvas');
        if (canvas) {
            const ctx = canvas.getContext('2d');
            let width = canvas.width = window.innerWidth;
            let height = canvas.height = window.innerHeight;

            window.addEventListener('resize', () => {
                width = canvas.width = window.innerWidth;
                height = canvas.height = window.innerHeight;
            });

            const particles = [];
            const particleCount = Math.min(65, Math.floor(width / 22));

            for (let i = 0; i < particleCount; i++) {
                particles.push({
                    x: Math.random() * width,
                    y: Math.random() * height,
                    vx: (Math.random() - 0.5) * 0.5,
                    vy: (Math.random() - 0.5) * 0.5,
                    radius: Math.random() * 2 + 1,
                    alpha: Math.random() * 0.6 + 0.2
                });
            }

            function animateParticles() {
                ctx.clearRect(0, 0, width, height);

                particles.forEach((p, index) => {
                    p.x += p.vx;
                    p.y += p.vy;

                    if (p.x < 0 || p.x > width) p.vx *= -1;
                    if (p.y < 0 || p.y > height) p.vy *= -1;

                    ctx.beginPath();
                    ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
                    ctx.fillStyle = `rgba(245, 184, 46, ${p.alpha})`;
                    ctx.shadowBlur = 8;
                    ctx.shadowColor = '#ffd700';
                    ctx.fill();

                    for (let j = index + 1; j < particles.length; j++) {
                        const p2 = particles[j];
                        const dx = p.x - p2.x;
                        const dy = p.y - p2.y;
                        const dist = Math.sqrt(dx * dx + dy * dy);

                        if (dist < 130) {
                            ctx.beginPath();
                            ctx.moveTo(p.x, p.y);
                            ctx.lineTo(p2.x, p2.y);
                            ctx.strokeStyle = `rgba(245, 184, 46, ${0.22 * (1 - dist / 130)})`;
                            ctx.lineWidth = 0.75;
                            ctx.stroke();
                        }
                    }
                });

                requestAnimationFrame(animateParticles);
            }
            animateParticles();
        }

        function toggleMobileMenu() {
            const drawer = document.getElementById('mobileMenuDrawer');
            const overlay = document.getElementById('mobileMenuOverlay');
            if (drawer && overlay) {
                drawer.classList.toggle('open');
                overlay.style.display = drawer.classList.contains('open') ? 'block' : 'none';
            }
        }

        function calculateStakingYield() {
            const usd = parseFloat(document.getElementById('calcUsdInput').value) || 0;
            const coins = usd / COIN_RATE;
            const dailyRoi = coins * 0.01;
            const totalYield = coins * 3.0;

            document.getElementById('calcStakedCoins').value = coins.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' Coins';
            document.getElementById('calcDailyRoi').value = dailyRoi.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' Coins/Day';
            document.getElementById('calcTotalYield').value = totalYield.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' Coins';
        }

        // Initialize calculator & Funnel Scroll Observer
        document.addEventListener('DOMContentLoaded', () => {
            calculateStakingYield();

            const observerOptions = { threshold: 0.12 };
            const scrollObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.reveal-on-scroll').forEach(el => scrollObserver.observe(el));
        });
    </script>
</body>
</html>
