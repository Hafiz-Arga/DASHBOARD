@extends('layouts.guest')

@section('title', 'Login | Superstore EIS')

@push('styles')
    <style>
        :root {
            --navy: #172B4D;
            --blue: #285EA8;
            --primary: #3976C5;
            --light: #EAF2FC;
            --muted: #64748B;
            --border: #DCE3ED;
        }

        * {
            box-sizing: border-box;
        }

        body {
            overflow: hidden;
            background: #FFFFFF;
        }

        .login-page {
            display: grid;
            grid-template-columns: 1.05fr 0.95fr;
            width: 100%;
            height: 100vh;
            height: 100svh;
            min-height: 560px;
            overflow: hidden;
        }

        /* LEFT SIDE */

        .login-hero {
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 38px 54px;
            overflow: hidden;
            color: white;
            background: linear-gradient(135deg, #172B4D, #214C7F 65%, #285EA8);
        }

        .hero-glow {
            position: absolute;
            width: 430px;
            height: 430px;
            top: -190px;
            right: -130px;
            border-radius: 50%;
            background: rgba(113, 169, 230, 0.18);
            filter: blur(5px);
            animation: glowFloat 7s ease-in-out infinite alternate;
        }

        .hero-orbit {
            position: absolute;
            width: 520px;
            height: 520px;
            right: -270px;
            bottom: -260px;
            border: 1px solid rgba(255, 255, 255, .12);
            border-radius: 50%;
            animation: orbit 22s linear infinite;
        }

        .hero-orbit::before,
        .hero-orbit::after {
            content: "";
            position: absolute;
            inset: 35px;
            border: 1px solid rgba(255, 255, 255, .08);
            border-radius: 50%;
        }

        .hero-orbit::after {
            inset: 75px;
        }

        .brand {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 11px;
            animation: fadeDown .8s ease both;
        }

        .brand-mark {
            display: grid;
            place-items: center;
            width: 39px;
            height: 39px;
            border: 1px solid rgba(255, 255, 255, .35);
            border-radius: 10px;
            background: rgba(255, 255, 255, .1);
            font-size: 17px;
            font-weight: 800;
        }

        .brand-name {
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 1.1px;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 520px;
            margin: auto 0;
            padding: 35px 0;
            animation: fadeUp .9s .15s ease both;
        }

        .hero-label {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 20px;
            color: #BBD5F2;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.5px;
        }

        .hero-content h1 {
            margin: 0 0 18px;
            color: #FFFFFF;
            font-size: clamp(34px, 3.6vw, 49px);
            line-height: 1.18;
            font-weight: 700;
            letter-spacing: -1.8px;
        }

        .hero-content p {
            max-width: 430px;
            margin: 0;
            color: #C7D6E8;
            font-size: 13px;
            line-height: 1.9;
        }

        /* ANIMATED DASHBOARD CARD */

        .visual-card {
            position: relative;
            width: 100%;
            max-width: 420px;
            margin-top: 30px;
            padding: 17px 19px 15px;
            border: 1px solid rgba(255, 255, 255, .17);
            border-radius: 13px;
            background: rgba(255, 255, 255, .09);
            box-shadow: 0 15px 40px rgba(8, 25, 49, .14);
            backdrop-filter: blur(12px);
            animation: cardFloat 5s ease-in-out infinite;
        }

        .visual-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 17px;
        }

        .visual-title {
            font-size: 11px;
            font-weight: 600;
            color: #FFFFFF;
        }

        .visual-tag {
            padding: 4px 8px;
            border-radius: 20px;
            background: rgba(145, 218, 177, .14);
            color: #BDE8D0;
            font-size: 9px;
        }

        .visual-metrics {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 9px;
        }

        .metric {
            padding: 10px;
            border: 1px solid rgba(255, 255, 255, .1);
            border-radius: 8px;
            background: rgba(255, 255, 255, .06);
        }

        .metric span {
            display: block;
            margin-bottom: 5px;
            color: #B9CBE0;
            font-size: 9px;
        }

        .metric strong {
            color: #FFFFFF;
            font-size: 13px;
            font-weight: 700;
        }

        .chart-area {
            position: relative;
            display: flex;
            align-items: end;
            gap: 10px;
            height: 67px;
            margin-top: 17px;
            padding: 0 5px;
            border-bottom: 1px solid rgba(255, 255, 255, .18);
        }

        .chart-bar {
            flex: 1;
            height: var(--bar-height);
            min-width: 0;
            border-radius: 4px 4px 0 0;
            background: linear-gradient(180deg, #B7D9FA, #669BD4);
            transform-origin: bottom;
            animation: barGrow 1.2s cubic-bezier(.2, .7, .2, 1) both;
            animation-delay: var(--delay);
            transition: filter .3s ease;
        }

        .chart-bar:hover {
            filter: brightness(1.3);
        }

        .hero-footer {
            position: relative;
            z-index: 2;
            color: #AFC1D7;
            font-size: 10px;
            letter-spacing: .3px;
            animation: fadeUp 1s .4s ease both;
        }

        /* RIGHT SIDE */

        .login-panel {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 35px;
            overflow: hidden;
            background: #FFFFFF;
        }

        .login-panel::before {
            content: "";
            position: absolute;
            width: 350px;
            height: 350px;
            right: -250px;
            top: -220px;
            border-radius: 50%;
            background: #F2F7FD;
        }

        .login-card {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 365px;
            animation: formEnter .85s .2s cubic-bezier(.2, .7, .2, 1) both;
        }

        .login-icon {
            display: grid;
            place-items: center;
            width: 45px;
            height: 45px;
            margin-bottom: 26px;
            border: 1px solid #DCE8F7;
            border-radius: 12px;
            background: var(--light);
            color: var(--blue);
            font-size: 19px;
            font-weight: 800;
        }

        .login-eyebrow {
            display: block;
            margin-bottom: 9px;
            color: var(--primary);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.5px;
        }

        .login-card h2 {
            margin: 0 0 9px;
            color: var(--navy);
            font-size: 29px;
            font-weight: 700;
            letter-spacing: -1px;
        }

        .login-subtitle {
            margin: 0 0 29px;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.8;
        }

        /* FORM */

        .form-group {
            margin-bottom: 19px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #334155;
            font-size: 12px;
            font-weight: 600;
        }

        .form-group input {
            width: 100%;
            height: 48px;
            padding: 0 14px;
            border: 1px solid var(--border);
            border-radius: 7px;
            outline: none;
            background: #FFFFFF;
            color: var(--navy);
            font-family: inherit;
            font-size: 12px;
            transition: border-color .2s, box-shadow .2s, background .2s;
        }

        .form-group input::placeholder {
            color: #A0ACBC;
        }

        .form-group input:hover {
            border-color: #AFC1D7;
        }

        .form-group input:focus {
            border-color: var(--primary);
            background: #FCFDFF;
            box-shadow: 0 0 0 4px rgba(57, 118, 197, .10);
        }

        .form-group input.is-invalid {
            border-color: #D64545;
        }

        /* REMEMBER */

        .remember {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin: 0 0 23px;
            color: var(--muted);
            font-size: 11px;
            cursor: pointer;
        }

        .remember input {
            width: 14px;
            height: 14px;
            margin: 0;
            accent-color: var(--primary);
            cursor: pointer;
        }

        /* ERROR */

        .alert-error {
            margin-bottom: 19px;
            padding: 11px 13px;
            border: 1px solid #F2CACA;
            border-radius: 7px;
            background: #FFF5F5;
            color: #B42318;
            font-size: 11px;
            line-height: 1.6;
            animation: fadeUp .3s ease both;
        }

        /* BUTTON */

        .btn-login {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            height: 49px;
            overflow: hidden;
            border: none;
            border-radius: 7px;
            background: var(--blue);
            color: #FFFFFF;
            font-family: inherit;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: background .25s, transform .25s, box-shadow .25s;
        }

        .btn-login::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 65%;
            height: 100%;
            transform: skewX(-25deg);
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .18), transparent);
            transition: left .55s ease;
        }

        .btn-login:hover {
            background: #214F91;
            box-shadow: 0 7px 18px rgba(40, 94, 168, .2);
            transform: translateY(-2px);
        }

        .btn-login:hover::before {
            left: 130%;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-arrow {
            font-size: 16px;
            transition: transform .2s;
        }

        .btn-login:hover .btn-arrow {
            transform: translateX(4px);
        }

        .login-note {
            margin-top: 28px;
            padding-top: 17px;
            border-top: 1px solid #EDF0F5;
            color: #94A3B8;
            font-size: 10px;
            text-align: center;
        }

        /* ANIMATIONS */

        @keyframes fadeDown {
            from {
                opacity: 0;
                transform: translateY(-12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes formEnter {
            from {
                opacity: 0;
                transform: translateY(22px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes barGrow {
            from {
                transform: scaleY(0);
                opacity: .3;
            }

            to {
                transform: scaleY(1);
                opacity: 1;
            }
        }

        @keyframes cardFloat {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-7px);
            }
        }

        @keyframes glowFloat {
            from {
                transform: translate(0, 0) scale(1);
            }

            to {
                transform: translate(-35px, 25px) scale(1.15);
            }
        }

        @keyframes orbit {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        /* RESPONSIVE */

        @media (max-width: 900px) {
            .login-page {
                grid-template-columns: 1fr 0.9fr;
            }

            .login-hero {
                padding: 30px;
            }

            .hero-content h1 {
                font-size: 35px;
            }

            .visual-card {
                margin-top: 22px;
            }

            .login-panel {
                padding: 25px;
            }
        }

        @media (max-width: 700px) {
            body {
                overflow: auto;
            }

            .login-page {
                display: flex;
                flex-direction: column;
                height: auto;
                min-height: 100svh;
                overflow: visible;
            }

            .login-hero {
                min-height: 390px;
                padding: 25px 25px 30px;
            }

            .hero-content {
                padding: 35px 0 15px;
            }

            .hero-content h1 {
                font-size: 31px;
            }

            .hero-content p {
                font-size: 12px;
            }

            .visual-card {
                max-width: 100%;
                margin-top: 22px;
            }

            .hero-footer {
                display: none;
            }

            .login-panel {
                min-height: 520px;
                padding: 42px 25px;
            }

            .login-card {
                max-width: 420px;
            }
        }

        @media (max-height: 650px) and (min-width: 701px) {
            .login-hero {
                padding-top: 23px;
                padding-bottom: 23px;
            }

            .hero-content {
                padding: 15px 0;
            }

            .hero-content h1 {
                font-size: 34px;
            }

            .hero-content p {
                font-size: 12px;
            }

            .visual-card {
                margin-top: 15px;
                padding: 12px 15px;
            }

            .chart-area {
                height: 48px;
                margin-top: 10px;
            }

            .login-panel {
                padding-top: 20px;
                padding-bottom: 20px;
            }

            .login-icon {
                margin-bottom: 15px;
            }

            .login-subtitle {
                margin-bottom: 19px;
            }

            .form-group {
                margin-bottom: 13px;
            }

            .login-note {
                margin-top: 17px;
            }
        }

        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                animation: none !important;
                transition: none !important;
            }
        }
    </style>
@endpush

@section('content')

    <div class="login-page">

        {{-- LEFT PANEL --}}
        <section class="login-hero">

            <div class="hero-glow"></div>
            <div class="hero-orbit"></div>

            <div class="brand">
                <span class="brand-mark">S</span>
                <span class="brand-name">SUPERSTORE EIS</span>
            </div>

            <div class="hero-content">

                <span class="hero-label">BUSINESS INTELLIGENCE PLATFORM</span>

                <h1>
                    See the Bigger Picture.
                    <br>
                    Make Better Decisions.
                </h1>

                <p>
                    Pantau kinerja penjualan dan profitabilitas Superstore
                    melalui informasi bisnis yang terintegrasi untuk
                    mendukung pengambilan keputusan eksekutif.
                </p>

                <div class="visual-card">

                    <div class="visual-top">
                        <span class="visual-title">Business Performance Overview</span>
                        <span class="visual-tag">● Live Preview</span>
                    </div>

                    <div class="visual-metrics">
                        <div class="metric">
                            <span>Total Sales</span>
                            <strong>Sales</strong>
                        </div>

                        <div class="metric">
                            <span>Total Profit</span>
                            <strong>Profit</strong>
                        </div>

                        <div class="metric">
                            <span>Performance</span>
                            <strong>Trend</strong>
                        </div>
                    </div>

                    <div class="chart-area" aria-hidden="true">
                        <span class="chart-bar" style="--bar-height:38%;--delay:.1s"></span>
                        <span class="chart-bar" style="--bar-height:56%;--delay:.2s"></span>
                        <span class="chart-bar" style="--bar-height:46%;--delay:.3s"></span>
                        <span class="chart-bar" style="--bar-height:75%;--delay:.4s"></span>
                        <span class="chart-bar" style="--bar-height:62%;--delay:.5s"></span>
                        <span class="chart-bar" style="--bar-height:88%;--delay:.6s"></span>
                        <span class="chart-bar" style="--bar-height:100%;--delay:.7s"></span>
                    </div>

                </div>
            </div>

            <div class="hero-footer">
                Executive Business Performance Monitoring
            </div>

        </section>

        {{-- RIGHT PANEL --}}
        <section class="login-panel">

            <div class="login-card">

                <div class="login-icon">S</div>

                <span class="login-eyebrow">ACCOUNT ACCESS</span>

                <h2>Selamat datang</h2>

                <p class="login-subtitle">
                    Masuk menggunakan akun yang telah terdaftar
                    untuk melanjutkan ke dashboard.
                </p>

                @if ($errors->any())
                    <div class="alert-error" role="alert">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login.attempt') }}" method="POST" novalidate>
                    @csrf

                    <div class="form-group">
                        <label for="email">Email</label>

                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            class="{{ $errors->has('email') ? 'is-invalid' : '' }}" placeholder="nama@email.com"
                            autocomplete="email" required autofocus>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>

                        <input type="password" id="password" name="password"
                            class="{{ $errors->has('password') ? 'is-invalid' : '' }}" placeholder="Masukkan password"
                            autocomplete="current-password" required>
                    </div>

                    <label class="remember">
                        <input type="checkbox" name="remember" value="1">
                        <span>Ingat saya</span>
                    </label>

                    <button type="submit" class="btn-login">
                        Masuk ke Dashboard
                    </button>

                </form>

                <div class="login-note">
                    Superstore Executive Information System
                </div>

            </div>

        </section>

    </div>

@endsection
