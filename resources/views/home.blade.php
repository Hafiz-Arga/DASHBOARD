@extends('layouts.guest')

@section('title', 'Beranda | Superstore EIS')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --home-navy: #172B4D;
            --home-blue: #285EA8;
            --home-primary: #3976C5;
            --home-light: #EAF2FC;
            --home-bg: #F4F7FB;
            --home-white: #FFFFFF;
            --home-text: #1E293B;
            --home-muted: #64748B;
            --home-border: #E2E8F0;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--home-bg);
            color: var(--home-text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        button, a { font-family: inherit; }

        /* TOPBAR (COMPACT) */
        .home-topbar {
            width: 100%;
            height: 58px;
            padding: 0 5%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--home-navy);
            color: var(--home-white);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            flex-shrink: 0;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: var(--home-white);
        }

        .brand-mark {
            width: 32px;
            height: 32px;
            display: block;
            object-fit: contain;
            flex-shrink: 0;
        }

        .brand-info {
            display: flex;
            flex-direction: column;
        }

        .brand-title {
            font-size: 14.5px;
            font-weight: 800;
            letter-spacing: 0.5px;
            line-height: 1.15;
        }

        .brand-sub {
            font-size: 10.5px;
            color: #9FB7D6;
            font-weight: 500;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 5px 12px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.10);
            border: 1px solid rgba(255, 255, 255, 0.14);
            color: #E2ECF9;
            font-size: 12.5px;
            font-weight: 600;
        }

        .user-status-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #22C55E;
        }

        .logout-button {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 13px;
            border: 1px solid rgba(255, 255, 255, 0.28);
            border-radius: 7px;
            background: rgba(255, 255, 255, 0.04);
            color: #FFFFFF;
            font-size: 12.5px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .logout-button:hover {
            background: rgba(239, 68, 68, 0.18);
            border-color: rgba(248, 113, 113, 0.65);
            color: #FECACA;
        }

        /* MAIN CONTAINER (FITS IN SINGLE VIEWPORT WITHOUT SCROLL) */
        .home-container {
            width: min(1160px, 92%);
            margin: 0 auto;
            padding: 18px 0 14px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* HERO WELCOME BANNER (SLEEK & COMPACT) */
        .hero-banner {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #172B4D 0%, #214478 60%, #2B62AE 100%);
            border-radius: 14px;
            padding: 20px 28px;
            color: #FFFFFF;
            margin-bottom: 18px;
            box-shadow: 0 8px 20px rgba(23, 43, 77, 0.10);
        }

        .hero-banner::after {
            content: '';
            position: absolute;
            right: -40px;
            top: -50px;
            width: 210px;
            height: 210px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.10) 0%, transparent 70%);
            pointer-events: none;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 860px;
        }

        .eyebrow {
            display: inline-block;
            padding: 3px 9px;
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.14);
            color: #DCEAFB;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.1px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .hero-banner h1 {
            margin: 0 0 6px;
            color: #FFFFFF;
            font-size: clamp(22px, 2.5vw, 28px);
            line-height: 1.2;
            font-weight: 800;
            letter-spacing: -0.6px;
        }

        .hero-banner p {
            margin: 0;
            color: #D8E6F8;
            font-size: 13.5px;
            line-height: 1.5;
        }

        /* SECTION HEADER */
        .section-head-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 12px;
            flex-wrap: wrap;
        }

        .section-heading {
            margin: 0;
            color: var(--home-navy);
            font-size: 16.5px;
            font-weight: 800;
            letter-spacing: -0.2px;
        }

        .section-subhint {
            font-size: 12.5px;
            color: var(--home-muted);
            font-weight: 500;
        }

        /* MODULE CARDS GRID */
        .menu-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            align-items: stretch;
        }

        .menu-card {
            display: flex;
            flex-direction: column;
            padding: 20px 24px;
            background: var(--home-white);
            border: 1px solid var(--home-border);
            border-radius: 14px;
            box-shadow: 0 4px 14px rgba(23, 43, 77, 0.04);
            transition: border-color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
        }

        .menu-card:hover {
            border-color: #93C5FD;
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(23, 43, 77, 0.08);
        }

        .dashboard-card {
            border-top: 4px solid var(--home-primary);
        }

        .etl-card {
            border-top: 4px solid #172B4D;
        }

        .card-header-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 10px;
        }

        .card-title-group {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-icon-box {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .dashboard-card .card-icon-box {
            background: #E8F1FD;
            color: #285EA8;
        }

        .etl-card .card-icon-box {
            background: #EDF2F7;
            color: #172B4D;
        }

        .card-label {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 5px;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.9px;
            text-transform: uppercase;
            flex-shrink: 0;
        }

        .dashboard-card .card-label {
            background: #EEF5FE;
            color: #285EA8;
        }

        .etl-card .card-label {
            background: #F1F5F9;
            color: #475569;
        }

        .card-title {
            margin: 0;
            color: var(--home-navy);
            font-size: 19px;
            font-weight: 800;
            letter-spacing: -0.4px;
        }

        .card-desc {
            margin: 0 0 12px;
            color: var(--home-muted);
            font-size: 13px;
            line-height: 1.5;
        }

        .feature-list {
            display: grid;
            gap: 7px;
            margin: 0 0 16px;
            padding: 0;
            list-style: none;
        }

        .feature-list li {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 7px 11px;
            border-radius: 8px;
            background: #F8FAFC;
            border: 1px solid #EDF2F7;
            color: #334155;
            font-size: 13px;
            font-weight: 600;
        }

        .feature-check {
            width: 18px;
            height: 18px;
            border-radius: 5px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 10.5px;
            font-weight: 800;
            flex-shrink: 0;
        }

        .dashboard-card .feature-check {
            background: #DCEAFB;
            color: #285EA8;
        }

        .etl-card .feature-check {
            background: #E2E8F0;
            color: #172B4D;
        }

        .card-action {
            margin-top: auto;
            padding-top: 12px;
            border-top: 1px solid #F1F5F9;
        }

        .action-button {
            width: 100%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 18px;
            border: 1px solid var(--home-primary);
            border-radius: 9px;
            background: var(--home-primary);
            color: #FFFFFF;
            font-size: 13.5px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .action-button:hover {
            background: var(--home-blue);
            border-color: var(--home-blue);
            box-shadow: 0 5px 14px rgba(40, 94, 168, 0.20);
        }

        .etl-card .action-button {
            border-color: #CBD5E1;
            background: #FFFFFF;
            color: var(--home-navy);
        }

        .etl-card .action-button:hover {
            border-color: var(--home-navy);
            background: var(--home-navy);
            color: #FFFFFF;
            box-shadow: 0 5px 14px rgba(23, 43, 77, 0.15);
        }

        /* FOOTER */
        .home-footer {
            margin-top: 14px;
            padding-top: 10px;
            border-top: 1px solid var(--home-border);
            color: #94A3B8;
            font-size: 11.5px;
            text-align: center;
        }

        /* DESKTOP VIEWPORT FIT (768px - 900px height): NO SCROLL */
        @media (min-width: 821px) {
            body {
                height: 100vh;
                overflow: hidden;
            }
            .home-container {
                justify-content: center;
                padding: 14px 0 12px;
            }
        }

        /* MOBILE / TABLET */
        @media (max-width: 820px) {
            .menu-grid { grid-template-columns: 1fr; }
            .hero-banner { padding: 20px; }
        }

        @media (max-width: 540px) {
            .home-topbar { height: 56px; padding: 0 5%; }
            .brand-sub, .user-badge { display: none; }
            .menu-card { padding: 18px; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { transition: none !important; }
        }
    </style>
@endpush

@section('content')

    {{-- TOPBAR --}}
    <header class="home-topbar">
        <div class="brand">
            <img src="{{ asset('images/favicon.png?v=2') }}" alt="Superstore EIS Logo" class="brand-mark">
            <div class="brand-info">
                <span class="brand-title">SUPERSTORE EIS</span>
                <span class="brand-sub">Executive Decision Support</span>
            </div>
        </div>

        <div class="topbar-right">
            <span class="user-badge">
                <span class="user-status-dot"></span>
                {{ Auth::user()->name ?? 'Administrator' }}
            </span>

            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="logout-button">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </header>

    {{-- MAIN --}}
    <main class="home-container">

        <div>
            {{-- HERO BANNER --}}
            <section class="hero-banner">
                <div class="hero-content">
                    <span class="eyebrow">EXECUTIVE INFORMATION SYSTEM</span>

                    <h1>Superstore Executive Information System</h1>

                    <p>
                        Sistem informasi eksekutif untuk memantau kinerja bisnis
                        Superstore melalui pengolahan data, visualisasi performa,
                        dan simulasi kebijakan diskon sebagai pendukung pengambilan keputusan.
                    </p>
                </div>
            </section>

            {{-- PILIH MODUL --}}
            <section>
                <div class="section-head-row">
                    <h2 class="section-heading">Pilih Modul Sistem</h2>
                    <span class="section-subhint">Pilih ruang kerja sesuai kebutuhan analisis atau pengelolaan data</span>
                </div>

                <div class="menu-grid">

                    {{-- DASHBOARD --}}
                    <article class="menu-card dashboard-card">
                        <div class="card-header-top">
                            <div class="card-title-group">
                                <div class="card-icon-box">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.1" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                                <h3 class="card-title">Dashboard Analitik</h3>
                            </div>
                            <span class="card-label">BUSINESS INTELLIGENCE</span>
                        </div>

                        <p class="card-desc">Pantau KPI utama, tren penjualan, profitabilitas wilayah, dan simulasi skenario bisnis.</p>

                        <ul class="feature-list">
                            <li><span class="feature-check">✓</span>Executive Summary</li>
                            <li><span class="feature-check">✓</span>Executive Dashboard</li>
                            <li><span class="feature-check">✓</span>What-If Analysis</li>
                        </ul>

                        <div class="card-action">
                            <a href="{{ route('executive-summary') }}" class="action-button">
                                Buka Dashboard
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </a>
                        </div>
                    </article>

                    {{-- ETL --}}
                    <article class="menu-card etl-card">
                        <div class="card-header-top">
                            <div class="card-title-group">
                                <div class="card-icon-box">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.1" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/>
                                    </svg>
                                </div>
                                <h3 class="card-title">ETL Data</h3>
                            </div>
                            <span class="card-label">DATA PREPARATION</span>
                        </div>

                        <p class="card-desc">Kelola pembaruan dataset CSV, validasi kualitas data, input manual, dan riwayat import.</p>

                        <ul class="feature-list">
                            <li><span class="feature-check">✓</span>Extract - pengambilan data</li>
                            <li><span class="feature-check">✓</span>Transform - pembersihan data</li>
                            <li><span class="feature-check">✓</span>Load - penyimpanan data</li>
                        </ul>

                        <div class="card-action">
                            <a href="{{ route('etl') }}" class="action-button">
                                Buka Halaman ETL
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </a>
                        </div>
                    </article>

                </div>
            </section>
        </div>

        <footer class="home-footer">
            Superstore Executive Information System · Academic Project
        </footer>

    </main>

@endsection
