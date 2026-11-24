<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Superstore EIS')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png?v=2') }}">

    {{-- FONT PLUS JAKARTA SANS --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        /* =========================================
           1. GLOBAL DESIGN SYSTEM (UNIFIED MODERN EIS)
        ========================================= */

        :root {
            --primary: #3976C5;
            --primary-dark: #285EA8;
            --primary-light: #EAF2FC;

            --navy: #172B4D;
            --navy-soft: #1E3762;
            --background: #F4F7FB;
            --surface: #FFFFFF;

            --text-primary: #172B4D;
            --text-secondary: #475569;
            --text-muted: #64748B;

            --border: #E2E8F0;
            --border-light: #EDF1F6;

            --success: #16834A;
            --success-bg: #EAF7EF;

            --danger: #D64545;
            --danger-bg: #FDEEEE;

            --warning: #D97706;
            --warning-bg: #FFFBEB;

            --neutral: #64748B;
            --neutral-bg: #F1F5F9;

            --sidebar-width: 256px;
            --radius: 14px;
            --shadow: 0 3px 12px rgba(23, 43, 77, 0.04);

            /* Global Typography */
            --font-main: 'Plus Jakarta Sans', sans-serif;

            --font-xs: 12.5px;
            --font-sm: 13.5px;
            --font-md: 15px;
            --font-lg: 17.5px;
            --font-xl: 20px;
            --font-2xl: 27px;
            --font-3xl: 30px;

            /* Component Typography */
            --font-body: var(--font-md);
            --font-sidebar: 14px;
            --font-page-title: var(--font-3xl);
            --font-description: var(--font-sm);
            --font-section-title: var(--font-xl);
            --font-kpi-label: 13.5px;
            --font-kpi-value: var(--font-2xl);
            --font-card-title: var(--font-lg);
            --font-table: var(--font-sm);

            /* Chart */
            --chart-font-size: 13px;
        }


        /* =========================================
           2. RESET & GLOBAL
        ========================================= */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: var(--font-main);
            background: var(--background);
            color: var(--text-primary);
            font-size: var(--font-body);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        button,
        input,
        select,
        textarea {
            font-family: inherit;
        }

        button,
        select {
            cursor: pointer;
        }

        a {
            color: inherit;
        }


        /* =========================================
           3. MAIN LAYOUT
        ========================================= */

        .app-container {
            min-height: 100vh;
        }

        .main-content {
            min-width: 0;
            min-height: 100vh;
            margin-left: var(--sidebar-width);
            padding: 28px 36px 44px;
        }

        .analytics-shell {
            max-width: 1380px;
            margin: 0 auto;
        }

        /* Layout khusus halaman ETL tanpa sidebar */
        .main-content-full {
            width: 100%;
            max-width: 1440px;
            min-height: 100vh;
            margin: 0 auto;
            padding: 32px 44px;
        }


        /* =========================================
           4. SIDEBAR (ENHANCED WITH ICONS)
        ========================================= */

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 1000;

            width: var(--sidebar-width);
            height: 100vh;

            display: flex;
            flex-direction: column;

            overflow-y: auto;

            background: linear-gradient(180deg, #172B4D 0%, #12223E 100%);
            color: #FFFFFF;
            border-right: 1px solid rgba(255, 255, 255, 0.06);

            padding: 26px 18px;
        }

        .brand-container {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-bottom: 22px;
            margin-bottom: 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.09);
        }

        .brand-logo {
            width: 42px;
            height: 42px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .brand-text {
            min-width: 0;
        }

        .brand {
            font-size: 16.5px;
            font-weight: 800;
            letter-spacing: -0.3px;
            color: #FFFFFF;
            margin-bottom: 2px;
        }

        .brand-description {
            font-size: 11px;
            color: #9FB7D6;
            line-height: 1.4;
        }

        .menu-label {
            font-size: 11px;
            font-weight: 700;
            color: #8CA4C4;
            margin: 0 0 12px 12px;
            text-transform: uppercase;
            letter-spacing: 1.1px;
        }

        .nav-menu {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .nav-menu a {
            display: flex;
            align-items: center;
            gap: 11px;
            min-height: 46px;
            padding: 11px 14px;

            color: #D2DFEE;
            text-decoration: none;
            border-radius: 10px;

            font-size: var(--font-sidebar);
            font-weight: 500;
            line-height: 1.35;

            transition: all 0.2s ease;
        }

        .nav-menu a svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
            opacity: 0.85;
        }

        .nav-menu a.active {
            background: linear-gradient(135deg, #3976C5 0%, #2B62AE 100%);
            color: #FFFFFF;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(40, 94, 168, 0.35);
        }

        .nav-menu a.active svg {
            opacity: 1;
        }

        .nav-menu a:not(.active):hover {
            background: rgba(255, 255, 255, 0.08);
            color: #FFFFFF;
            transform: translateX(2px);
        }

        .sidebar-footer {
            margin-top: auto;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.09);
        }

        .nav-menu-home {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            min-height: 44px;
            padding: 10px 14px;

            color: #DCE5F1;
            text-align: center;
            text-decoration: none;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 10px;

            font-size: 13.5px;
            font-weight: 600;

            transition: all 0.2s ease;
        }

        .nav-menu-home.active {
            background: var(--primary);
            border-color: var(--primary);
            color: #FFFFFF;
        }

        .nav-menu-home:not(.active):hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.35);
            color: #FFFFFF;
        }


        /* =========================================
           5. PAGE HERO HEADER (CONSISTENT ACROSS PAGES)
        ========================================= */

        .eis-hero-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            background: linear-gradient(135deg, #172B4D 0%, #214478 65%, #2B62AE 100%);
            border-radius: 16px;
            padding: 24px 28px;
            color: #FFFFFF;
            margin-bottom: 22px;
            box-shadow: 0 10px 24px rgba(23, 43, 77, 0.10);
        }

        .eis-hero-eyebrow {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.14);
            color: #DCEAFB;
            font-size: 10.5px;
            font-weight: 800;
            letter-spacing: 1.1px;
            text-transform: uppercase;
            margin-bottom: 9px;
        }

        .eis-hero-header h1 {
            color: #FFFFFF;
            font-size: 26px;
            font-weight: 800;
            line-height: 1.25;
            letter-spacing: -0.5px;
            margin: 0 0 6px;
        }

        .eis-hero-header p {
            color: #D8E6F8;
            font-size: 13.5px;
            line-height: 1.6;
            max-width: 740px;
            margin: 0;
        }

        .eis-hero-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .eis-hero-help-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            min-height: 40px;
            padding: 9px 15px;
            border: 1px solid rgba(255, 255, 255, 0.28);
            border-radius: 9px;
            background: rgba(255, 255, 255, 0.10);
            color: #FFFFFF;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .eis-hero-help-btn:hover {
            background: rgba(255, 255, 255, 0.20);
            border-color: rgba(255, 255, 255, 0.5);
        }

        .eis-scope-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 13px;
            border-radius: 9px;
            background: rgba(255, 255, 255, 0.11);
            border: 1px solid rgba(255, 255, 255, 0.18);
            color: #E2ECF9;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }


        /* =========================================
           6. ETL NAVIGATION
        ========================================= */

        .etl-back-link {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 10px 16px;
            margin-bottom: 26px;
            background: #FFFFFF;
            color: #285EA8;
            border: 1px solid #DCE3ED;
            border-radius: 8px;
            font-family: var(--font-main);
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .etl-back-link:hover {
            background: #EAF2FC;
            border-color: #3976C5;
            color: #285EA8;
            transform: translateX(-2px);
        }


        /* =========================================
           7. GLOBAL FILTER BAR (COMPACT & SLEEK)
        ========================================= */

        .filter-container {
            display: flex;
            align-items: end;
            gap: 16px;
            flex-wrap: wrap;

            background: var(--surface);
            padding: 18px 22px;

            border: 1px solid var(--border);
            border-radius: var(--radius);

            margin-bottom: 24px;
            box-shadow: var(--shadow);
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
            min-width: 170px;
            flex: 1;
            max-width: 240px;
        }

        .filter-group label {
            font-size: 11.5px;
            font-weight: 700;
            color: var(--text-muted);
            letter-spacing: 0.4px;
            text-transform: uppercase;
        }

        .filter-group select {
            width: 100%;
            min-height: 42px;
            padding: 8px 12px;

            border: 1px solid #CBD5E1;
            border-radius: 8px;

            background: #F8FAFC;
            color: var(--text-primary);

            font-size: 13.5px;
            font-weight: 600;
            outline: none;

            transition: all 0.2s ease;
        }

        .filter-group select:hover {
            border-color: #94A3B8;
            background: #FFFFFF;
        }

        .filter-group select:focus {
            background: #FFFFFF;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(57, 118, 197, 0.14);
        }

        .filter-actions {
            display: flex;
            align-items: end;
            gap: 10px;
        }

        .filter-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            min-height: 42px;
            padding: 10px 20px;

            border: none;
            border-radius: 8px;

            background: var(--primary);
            color: #FFFFFF;

            font-size: 13.5px;
            font-weight: 700;

            transition: all 0.2s ease;
        }

        .filter-button:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(40, 94, 168, 0.22);
        }

        .filter-reset-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 42px;
            padding: 9px 14px;
            border-radius: 8px;
            border: 1px solid #CBD5E1;
            background: #FFFFFF;
            color: var(--text-secondary);
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .filter-reset-link:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--primary-light);
        }


        /* =========================================
           8. DASHBOARD SECTION HEADERS
        ========================================= */

        .dashboard-section {
            margin: 26px 0;
        }

        .section-heading,
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 12px;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }

        .section-heading h2,
        .section-header h2 {
            color: var(--text-primary);
            font-size: var(--font-section-title);
            font-weight: 800;
            line-height: 1.3;
            letter-spacing: -0.3px;
        }

        .section-heading p,
        .section-header p {
            margin-top: 4px;
            color: var(--text-muted);
            font-size: 13.5px;
            line-height: 1.5;
        }

        .summary-section {
            min-width: 0;
            margin: 26px 0;
        }


        /* =========================================
           9. KPI CARDS (WITH TOP ACCENT & ICON)
        ========================================= */

        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
        }

        .kpi-card {
            position: relative;
            min-width: 0;
            background: var(--surface);
            padding: 20px 22px;

            border: 1px solid var(--border);
            border-top: 3px solid var(--primary);
            border-radius: var(--radius);

            box-shadow: var(--shadow);

            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .kpi-card.accent-green { border-top-color: #16834A; }
        .kpi-card.accent-indigo { border-top-color: #4F46E5; }
        .kpi-card.accent-amber { border-top-color: #D97706; }

        .kpi-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(23, 43, 77, 0.07);
        }

        .kpi-card-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 10px;
        }

        .kpi-card .kpi-label,
        .kpi-card p.kpi-label {
            color: var(--text-muted);
            font-size: 13px;
            font-weight: 700;
            margin: 0;
        }

        .kpi-icon-badge {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: #EEF5FE;
            color: #285EA8;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .kpi-card.accent-green .kpi-icon-badge { background: #EAF7EF; color: #16834A; }
        .kpi-card.accent-indigo .kpi-icon-badge { background: #EEF2FF; color: #4F46E5; }
        .kpi-card.accent-amber .kpi-icon-badge { background: #FEF3C7; color: #D97706; }

        .kpi-card h2 {
            color: var(--text-primary);
            font-size: var(--font-kpi-value);
            font-weight: 800;
            line-height: 1.25;
            letter-spacing: -0.6px;
            overflow-wrap: anywhere;
            margin: 0;
        }


        /* =========================================
           10. YOY INDICATOR
        ========================================= */

        .yoy-indicator {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin: 10px 0 0;
            padding: 3px 9px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
        }

        .yoy-icon {
            font-size: 13px;
            line-height: 1;
        }

        .yoy-indicator.good {
            background: var(--success-bg);
            color: var(--success);
        }

        .yoy-indicator.bad {
            background: var(--danger-bg);
            color: var(--danger);
        }

        .yoy-indicator.yoy-neutral,
        .yoy-neutral {
            background: var(--neutral-bg);
            color: var(--neutral);
            font-size: 12px;
            font-weight: 600;
        }


        /* =========================================
           11. CHART GRID
        ========================================= */

        .chart-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
        }

        .chart-card {
            min-width: 0;
            background: var(--surface);
            padding: 22px 24px;

            border: 1px solid var(--border);
            border-radius: var(--radius);

            box-shadow: var(--shadow);
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 14px;
        }

        .chart-header h3,
        .chart-card h3 {
            color: var(--text-primary);
            font-size: var(--font-card-title);
            font-weight: 800;
            line-height: 1.4;
            margin: 0;
        }

        .chart-header p,
        .chart-card>p {
            color: var(--text-muted);
            font-size: 13px;
            line-height: 1.55;
            margin: 4px 0 0;
        }

        .chart-hint-badge {
            padding: 3px 9px;
            border-radius: 6px;
            background: #EEF5FE;
            color: #285EA8;
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .chart-wrapper {
            position: relative;
            width: 100%;
            height: 295px;
        }


        /* =========================================
           12. DRILL-DOWN
        ========================================= */

        .drill-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            min-height: 34px;
            padding: 6px 12px;
            margin-bottom: 12px;
            border-radius: 8px;
            background: #F8FAFC;
            border: 1px solid #EDF1F6;
        }

        .drill-breadcrumb {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 6px;
            font-size: 12.5px;
            color: var(--text-muted);
            margin: 0;
        }

        .drill-breadcrumb strong {
            color: var(--primary-dark);
            font-weight: 700;
        }

        .drill-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin: 0;
        }

        .drill-button {
            border: 1px solid #CBD5E1;
            background: #FFFFFF;
            color: var(--text-primary);
            border-radius: 6px;
            padding: 4px 10px;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .drill-button:hover {
            border-color: var(--primary);
            color: var(--primary-dark);
            background: var(--primary-light);
        }


        /* =========================================
           13. SUMMARY CARDS & TABLES
        ========================================= */

        .summary-card {
            min-width: 0;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px 22px;
            box-shadow: var(--shadow);
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            font-size: var(--font-table);
            line-height: 1.6;
        }

        .summary-table th,
        .summary-table td {
            padding: 13px 14px;
            text-align: left;
            border-bottom: 1px solid var(--border-light);
        }

        .summary-table th {
            color: var(--text-muted);
            font-size: 11.5px;
            font-weight: 700;
            background: #F8FAFC;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            white-space: nowrap;
        }

        .summary-table td {
            color: #334155;
            font-size: var(--font-table);
        }

        .summary-table tbody tr:hover {
            background: #F8FAFC;
        }

        .summary-table tbody tr:last-child td {
            border-bottom: none;
        }

        .category-name {
            color: var(--text-primary);
            font-weight: 700;
        }

        .contrib-bar-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 110px;
        }

        .contrib-bar-track {
            flex: 1;
            height: 6px;
            background: #E2E8F0;
            border-radius: 999px;
            overflow: hidden;
        }

        .contrib-bar-fill {
            height: 100%;
            background: #3976C5;
            border-radius: 999px;
        }

        .contrib-bar-fill.profit {
            background: #16834A;
        }

        .profit-negative {
            color: var(--danger) !important;
            font-weight: 700 !important;
        }

        .profit-positive {
            color: var(--success) !important;
            font-weight: 700 !important;
        }

        .empty-state {
            color: var(--text-muted) !important;
            text-align: center !important;
            padding: 24px !important;
        }


        /* =========================================
           16. SUMMARY BOTTOM GRID & KEY FINDINGS
        ========================================= */

        .summary-bottom-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
            align-items: stretch;
        }

        .summary-bottom-grid .summary-section {
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        .summary-bottom-grid .summary-card {
            flex: 1;
        }

        .findings-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .finding-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 10px;
            background: #F8FAFC;
            border: 1px solid #EDF1F6;
        }

        .finding-marker {
            width: 22px;
            height: 22px;
            min-width: 22px;
            border-radius: 6px;
            background: #DCEAFB;
            color: #285EA8;
            font-size: 11px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 1px;
        }

        .finding-item p {
            color: #334155;
            font-size: 13.5px;
            line-height: 1.6;
            margin: 0;
        }


        /* =========================================
           20. RESPONSIVE
        ========================================= */

        @media (max-width: 1150px) {
            :root {
                --sidebar-width: 230px;
                --font-kpi-value: 24px;
            }

            .main-content {
                padding: 24px 22px;
            }

            .kpi-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 768px) {
            :root {
                --sidebar-width: 0px;
            }

            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
                padding: 18px;
                overflow: visible;
            }

            .brand-container {
                margin-bottom: 16px;
                padding-bottom: 14px;
            }

            .nav-menu {
                flex-direction: row;
                flex-wrap: wrap;
            }

            .nav-menu li {
                flex: 1;
                min-width: 150px;
            }

            .main-content,
            .main-content-full {
                margin-left: 0;
                min-height: auto;
                padding: 20px 16px;
            }

            .eis-hero-header {
                flex-direction: column;
                padding: 20px;
            }

            .chart-grid,
            .summary-bottom-grid {
                grid-template-columns: 1fr;
            }

            .filter-container {
                align-items: stretch;
            }

            .filter-group {
                max-width: none;
                min-width: 130px;
            }

            .filter-actions {
                width: 100%;
            }

            .filter-button {
                flex: 1;
            }
        }

        @media (max-width: 480px) {
            .kpi-grid {
                grid-template-columns: 1fr;
            }

            .chart-wrapper {
                height: 260px;
            }
        }
    </style>
    @stack('styles')
</head>

<body>

    <div class="app-container">

        {{-- SIDEBAR: TIDAK DITAMPILKAN PADA HALAMAN ETL --}}
        @if (!request()->routeIs('etl*'))
            <aside class="sidebar">

                {{-- BRAND LOGO --}}
                <div class="brand-container">
                    <img src="{{ asset('images/favicon.png?v=2') }}" alt="Superstore Logo" class="brand-logo">

                    <div class="brand-text">
                        <div class="brand">
                            SUPERSTORE EIS
                        </div>

                        <div class="brand-description">
                            Executive Diagnostic Dashboard
                        </div>
                    </div>
                </div>

                <div class="menu-label">
                    MAIN MENU
                </div>

                <ul class="nav-menu">

                    <li>
                        <a href="{{ route('executive-summary') }}"
                            class="{{ request()->routeIs('executive-summary') ? 'active' : '' }}">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span>Executive Summary</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('/dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                            </svg>
                            <span>Executive Dashboard</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('what-if.discount') }}"
                            class="{{ request()->routeIs('what-if.discount') ? 'active' : '' }}">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <span>Discount Policy Simulation</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('what-if.growth') }}"
                            class="{{ request()->routeIs('what-if.growth') ? 'active' : '' }}">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                            <span>Regional Growth Allocation</span>
                        </a>
                    </li>

                </ul>

                {{-- MENU BERANDA DI BAGIAN BAWAH SIDEBAR --}}
                <div class="sidebar-footer">
                    <a href="{{ route('home') }}"
                        class="nav-menu-home {{ request()->routeIs('home') ? 'active' : '' }}">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.1" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Kembali ke Beranda</span>
                    </a>
                </div>
            </aside>
        @endif


        {{-- MAIN CONTENT --}}
        <main class="main-content {{ request()->routeIs('etl*') ? 'main-content-full' : '' }}">

            @yield('content')

        </main>

    </div>

    @stack('scripts')
</body>

</html>
