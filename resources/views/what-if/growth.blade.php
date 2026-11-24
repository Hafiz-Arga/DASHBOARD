@extends('layouts.app')

@section('title', 'Regional Growth Allocation | Superstore EIS')

@section('content')
    @php
        $money = fn($value) => '$' . number_format((float) $value, 2);
        $number = fn($value) => number_format((float) $value, 0);
        $percent = fn($value) => number_format((float) $value, 2) . '%';
        $signedMoney = fn($value) => ($value > 0 ? '+' : '') . '$' . number_format((float) $value, 2);
        $signedPercent = fn($value) => ($value > 0 ? '+' : '') . number_format((float) $value, 2) . '%';

        $actualSales = (float) ($growthKpis['actual_sales'] ?? 0);
        $actualProfit = (float) ($growthKpis['actual_profit'] ?? 0);
        $actualMargin = (float) ($growthKpis['actual_margin'] ?? 0);

        $baselineSales = (float) ($growthKpis['baseline_sales'] ?? 0);
        $baselineProfit = (float) ($growthKpis['baseline_profit'] ?? 0);
        $baselineMargin = (float) ($growthKpis['baseline_margin'] ?? 0);

        $alternativeSales = (float) ($growthKpis['alternative_sales'] ?? 0);
        $alternativeProfit = (float) ($growthKpis['alternative_profit'] ?? 0);
        $alternativeMargin = (float) ($growthKpis['alternative_margin'] ?? 0);

        $profitDifference = (float) ($growthKpis['profit_difference'] ?? 0);
        $salesDifference = (float) ($growthKpis['sales_difference'] ?? 0);
        $marginDifference = $alternativeMargin - $baselineMargin;
    @endphp

    <style>
        .whatif-page {
            width: 100%;
            min-width: 0;
            color: var(--text-primary);
            font-family: var(--font-main);
        }

        .whatif-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 1px solid #CBD5E1;
            background: #FFFFFF;
            color: var(--text-primary);
            border-radius: 7px;
            padding: 6px 12px;
            font-weight: 700;
            cursor: pointer;
            font-size: 12.5px;
            transition: all 0.2s ease;
        }

        .whatif-btn:hover {
            background: var(--primary-light);
            border-color: var(--primary);
            color: var(--primary-dark);
        }

        .whatif-panel {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px 22px;
            margin-bottom: 22px;
            box-shadow: var(--shadow);
        }

        .whatif-panel-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }

        .whatif-panel-title {
            font-size: var(--font-section-title);
            font-weight: 800;
            margin: 0 0 4px;
            color: var(--text-primary);
            letter-spacing: -0.3px;
        }

        .whatif-panel-sub {
            font-size: 13.5px;
            color: var(--text-muted);
            margin: 0;
            line-height: 1.5;
        }

        .whatif-baseline-pill {
            padding: 4px 10px;
            border-radius: 6px;
            background: #EEF5FE;
            color: #285EA8;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }

        /* FILTER BAR CONSISTENT WITH OTHER PAGES */
        .whatif-filter-grid {
            display: flex;
            flex-wrap: wrap;
            align-items: end;
            gap: 16px;
        }

        .whatif-field {
            display: flex;
            flex-direction: column;
            gap: 6px;
            min-width: 170px;
            flex: 1;
            max-width: 260px;
        }

        .whatif-field.full-w {
            max-width: none;
            min-width: 0;
        }

        .whatif-field label {
            display: block;
            font-size: 11.5px;
            font-weight: 700;
            color: var(--text-muted);
            letter-spacing: 0.4px;
            text-transform: uppercase;
        }

        .whatif-field select,
        .whatif-field input {
            width: 100%;
            min-height: 42px;
            border: 1px solid #CBD5E1;
            border-radius: 8px;
            padding: 8px 12px;
            color: var(--text-primary);
            background: #F8FAFC;
            font-size: 13.5px;
            font-weight: 600;
            outline: none;
            transition: all 0.2s ease;
        }

        .whatif-field select:hover,
        .whatif-field input:hover {
            border-color: #94A3B8;
            background: #FFFFFF;
        }

        .whatif-field select:focus,
        .whatif-field input:focus {
            background: #FFFFFF;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(57, 118, 197, .14);
        }

        .whatif-submit {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            min-height: 42px;
            border: 0;
            border-radius: 8px;
            padding: 10px 20px;
            background: var(--primary);
            color: #FFFFFF;
            font-size: 13.5px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .whatif-submit:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(40, 94, 168, 0.22);
        }

        /* REGION INPUT CARDS */
        .whatif-region-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
        }

        .whatif-region-card {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-top: 3px solid var(--primary);
            border-radius: 12px;
            padding: 16px 18px;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .whatif-region-card:hover {
            border-color: #93C5FD;
            box-shadow: 0 4px 12px rgba(23, 43, 77, 0.05);
        }

        .whatif-region-card-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .whatif-region-card h3 {
            font-size: 15px;
            font-weight: 800;
            margin: 0;
            color: var(--text-primary);
        }

        .whatif-region-badge {
            font-size: 11px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 5px;
            background: #E2E8F0;
            color: #475569;
        }

        .whatif-region-card input {
            width: 100%;
            min-height: 40px;
            border: 1px solid #CBD5E1;
            border-radius: 8px;
            padding: 0 12px;
            font-size: 14px;
            font-weight: 700;
            background: #FFFFFF;
            color: var(--text-primary);
        }

        .whatif-region-card small {
            display: block;
            margin-top: 8px;
            color: var(--text-muted);
            font-size: 12px;
            font-weight: 500;
        }

        .whatif-rate-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-top: 16px;
            padding-top: 14px;
            border-top: 1px solid var(--border-light);
            flex-wrap: wrap;
        }

        .whatif-note {
            font-size: 12.5px;
            color: var(--text-muted);
            line-height: 1.6;
            margin: 0;
        }

        /* KPI CARDS */
        .whatif-kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 24px;
        }

        .whatif-kpi {
            background: var(--surface);
            border: 1px solid var(--border);
            border-top: 3px solid var(--primary);
            border-radius: var(--radius);
            padding: 20px 22px;
            min-width: 0;
            box-shadow: var(--shadow);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .whatif-kpi.accent-green { border-top-color: #16834A; }
        .whatif-kpi.accent-indigo { border-top-color: #4F46E5; }
        .whatif-kpi.accent-amber { border-top-color: #D97706; }

        .whatif-kpi:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(23, 43, 77, .07);
        }

        .whatif-kpi-label {
            color: var(--text-muted);
            font-size: 12.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 10px;
        }

        .whatif-kpi-value {
            color: var(--text-primary);
            font-size: var(--font-2xl);
            font-weight: 800;
            line-height: 1.25;
            letter-spacing: -0.6px;
            overflow-wrap: anywhere;
        }

        .whatif-kpi-sub {
            color: var(--text-muted);
            font-size: 12.5px;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid var(--border-light);
            line-height: 1.5;
        }

        .whatif-positive {
            color: var(--success) !important;
            font-weight: 700;
        }

        .whatif-negative {
            color: var(--danger) !important;
            font-weight: 700;
        }

        /* TABLE & BREADCRUMB */
        .whatif-table-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            padding: 8px 14px;
            margin-bottom: 14px;
            border-radius: 9px;
            background: #F8FAFC;
            border: 1px solid #EDF1F6;
        }

        .whatif-breadcrumb {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 7px;
            font-size: 13px;
            color: var(--text-muted);
            margin: 0;
        }

        .whatif-crumb {
            border: 0;
            padding: 0;
            background: none;
            color: var(--primary);
            cursor: pointer;
            font-weight: 700;
        }

        .whatif-crumb:hover {
            text-decoration: underline;
        }

        .whatif-crumb.current {
            color: var(--text-primary);
            font-weight: 800;
            cursor: default;
            text-decoration: none;
        }

        .whatif-caption {
            color: var(--text-muted);
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 12px;
        }

        .whatif-table-wrap {
            overflow-x: auto;
        }

        .whatif-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1000px;
        }

        .whatif-table th {
            text-align: right;
            padding: 13px 14px;
            color: var(--text-muted);
            background: #F8FAFC;
            font-size: 11.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            white-space: nowrap;
            border-bottom: 1px solid var(--border-light);
        }

        .whatif-table th:first-child {
            text-align: left;
            padding: 13px 15px;
        }

        .whatif-table td {
            text-align: right;
            padding: 13px 14px;
            border-bottom: 1px solid var(--border-light);
            font-size: 13.5px;
            color: #334155;
            white-space: nowrap;
        }

        .whatif-table td:first-child {
            padding: 0;
            text-align: left;
            min-width: 170px;
        }

        .whatif-table tbody tr:hover {
            background: #F8FAFC;
        }

        .whatif-drill {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            min-height: 48px;
            padding: 0 15px;
            border: 0;
            background: transparent;
            color: var(--text-primary);
            font-weight: 700;
            cursor: pointer;
            font-size: 13.5px;
            text-align: left;
            transition: color 0.15s ease;
        }

        .whatif-drill::after {
            content: "›";
            color: var(--primary);
            font-size: 19px;
            font-weight: 700;
            margin-left: 12px;
        }

        .whatif-drill:hover {
            color: var(--primary);
        }

        .whatif-table td:first-child strong {
            display: block;
            padding: 13px 15px;
            font-weight: 700;
            color: var(--text-primary);
        }

        /* INSIGHT GRID */
        .whatif-insight-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .whatif-insight {
            border: 1px solid var(--border);
            border-left: 4px solid var(--primary);
            border-radius: 12px;
            padding: 16px 18px;
            background: #F8FAFC;
        }

        .whatif-insight h3 {
            font-size: 14.5px;
            font-weight: 800;
            margin: 0 0 6px;
            color: var(--text-primary);
        }

        .whatif-insight p {
            font-size: 13px;
            line-height: 1.6;
            margin: 0;
            color: var(--text-secondary);
        }

        /* MODAL */
        .whatif-modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, .52);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .whatif-modal.show {
            display: flex;
        }

        .whatif-modal-card {
            background: #FFFFFF;
            border-radius: 16px;
            max-width: 560px;
            width: 100%;
            padding: 26px;
            box-shadow: 0 18px 45px rgba(23, 43, 77, .22);
        }

        .whatif-modal-card h2 {
            font-size: 18px;
            font-weight: 800;
            margin: 0 0 12px;
            color: var(--text-primary);
        }

        .whatif-modal-card p,
        .whatif-modal-card li {
            font-size: 13.5px;
            line-height: 1.65;
            color: var(--text-secondary);
        }

        .whatif-modal-card ul {
            padding-left: 20px;
            margin: 10px 0;
        }

        .whatif-modal-close {
            float: right;
            width: 32px;
            height: 32px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: #F8FAFC;
            font-size: 20px;
            cursor: pointer;
            color: var(--text-secondary);
        }

        .whatif-modal-close:hover {
            background: var(--primary-light);
            color: var(--primary-dark);
        }

        @media(max-width:1150px) {
            .whatif-kpi-grid,
            .whatif-region-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media(max-width:768px) {
            .whatif-field {
                max-width: none;
            }

            .whatif-kpi-grid,
            .whatif-region-grid,
            .whatif-insight-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

<div class="analytics-shell">
    <div class="whatif-page">

        {{-- HERO HEADER --}}
        <div class="eis-hero-header">
            <div>
                <span class="eis-hero-eyebrow">SIMULATION & DECISION SUPPORT</span>
                <h1>Regional Growth Allocation</h1>
                <p>Simulasikan alternatif target pertumbuhan penjualan pada setiap wilayah untuk mengevaluasi potensi
                    dampaknya terhadap kinerja dan profit perusahaan.</p>
            </div>

            <div class="eis-hero-actions">
                <button type="button" class="eis-hero-help-btn" id="openGrowthHelp">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Panduan Analisis
                </button>
            </div>
        </div>

        <form action="{{ route('what-if.growth') }}" method="GET">
            <section class="whatif-panel">
                <div class="whatif-panel-head">
                    <div>
                        <h2 class="whatif-panel-title">Analysis Filters</h2>
                        <p class="whatif-panel-sub">Pilih tahun dasar dan kategori produk sebelum mengatur target pertumbuhan wilayah.</p>
                    </div>
                </div>

                <div class="whatif-filter-grid">
                    <div class="whatif-field">
                        <label for="growth-year">Tahun (Year)</label>
                        <select name="year" id="growth-year">
                            @foreach ($years as $year)
                                <option value="{{ $year }}"
                                    {{ (string) $selectedYear === (string) $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="whatif-field">
                        <label for="growth-category">Kategori (Category)</label>
                        <select name="category" id="growth-category">
                            <option value="">All Categories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category }}" {{ $selectedCategory === $category ? 'selected' : '' }}>
                                    {{ $category }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <button type="submit" class="whatif-submit">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            Apply Filters & Rates
                        </button>
                    </div>
                </div>
            </section>

            <section class="whatif-panel">
                <div class="whatif-panel-head">
                    <div>
                        <h2 class="whatif-panel-title">Regional Growth Assumptions</h2>
                        <p class="whatif-panel-sub">
                            Atur target pertumbuhan alternatif (%) untuk setiap wilayah dan bandingkan hasilnya terhadap kondisi baseline.
                        </p>
                    </div>
                    <span class="whatif-baseline-pill">Baseline: {{ $percent($baselineGrowthRate ?? 10) }}</span>
                </div>

                <div class="whatif-region-grid">
                    @foreach ($regions as $region)
                        <div class="whatif-region-card">
                            <div class="whatif-region-card-top">
                                <h3>{{ $region }}</h3>
                                <span class="whatif-region-badge">Region</span>
                            </div>
                            <div class="whatif-field full-w">
                                <label for="growth-rate-{{ Str::slug($region) }}">Alternative Growth Rate (%)</label>
                                <input type="number" id="growth-rate-{{ Str::slug($region) }}"
                                    name="growth_rates[{{ $region }}]"
                                    value="{{ old('growth_rates.' . $region, $growthRates[$region] ?? 10) }}"
                                    min="-100" max="500" step="1">
                            </div>
                            <small>Baseline seragam: {{ $percent($baselineGrowthRate ?? 10) }}</small>
                        </div>
                    @endforeach
                </div>

                <div class="whatif-rate-actions">
                    <p class="whatif-note">
                        Rentang input: -100% hingga 500%. Nilai negatif menunjukkan skenario kontraksi penjualan.
                    </p>
                    <button type="submit" class="whatif-submit">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Run Growth Simulation
                    </button>
                </div>
            </section>
        </form>

        <div class="whatif-kpi-grid">
            <div class="whatif-kpi">
                <div class="whatif-kpi-label">Alternative Sales</div>
                <div class="whatif-kpi-value">{{ $money($alternativeSales) }}</div>
                <div class="whatif-kpi-sub">
                    Actual: {{ $money($actualSales) }} · vs Actual:
                    <span class="{{ $alternativeSales >= $actualSales ? 'whatif-positive' : 'whatif-negative' }}">
                        {{ $signedMoney($alternativeSales - $actualSales) }}
                    </span>
                </div>
            </div>

            <div class="whatif-kpi accent-green">
                <div class="whatif-kpi-label">Alternative Profit</div>
                <div class="whatif-kpi-value {{ $alternativeProfit < 0 ? 'whatif-negative' : '' }}">
                    {{ $money($alternativeProfit) }}
                </div>
                <div class="whatif-kpi-sub">
                    Actual: {{ $money($actualProfit) }} · vs Actual:
                    <span class="{{ $alternativeProfit >= $actualProfit ? 'whatif-positive' : 'whatif-negative' }}">
                        {{ $signedMoney($alternativeProfit - $actualProfit) }}
                    </span>
                </div>
            </div>

            <div class="whatif-kpi accent-indigo">
                <div class="whatif-kpi-label">Alternative Profit Margin</div>
                <div class="whatif-kpi-value">{{ $percent($alternativeMargin) }}</div>
                <div class="whatif-kpi-sub">
                    Actual: {{ $percent($actualMargin) }} · vs Actual:
                    <span class="{{ $alternativeMargin - $actualMargin >= 0 ? 'whatif-positive' : 'whatif-negative' }}">
                        {{ $signedPercent($alternativeMargin - $actualMargin) }}
                    </span>
                </div>
            </div>

            <div class="whatif-kpi accent-amber">
                <div class="whatif-kpi-label">Incremental Profit vs Baseline</div>
                <div class="whatif-kpi-value {{ $profitDifference >= 0 ? 'whatif-positive' : 'whatif-negative' }}">
                    {{ $signedMoney($profitDifference) }}
                </div>
                <div class="whatif-kpi-sub">
                    Baseline Profit: {{ $money($baselineProfit) }}
                </div>
            </div>
        </div>

        <section class="whatif-panel">
            <div class="whatif-panel-head">
                <div>
                    <h2 class="whatif-panel-title">Growth Scenario Comparison</h2>
                    <p class="whatif-panel-sub">Ringkasan perbandingan antara kondisi aktual, baseline seragam, dan skenario alternatif.</p>
                </div>
            </div>
            <div class="whatif-insight-grid">
                <div class="whatif-insight">
                    <h3>Actual Performance</h3>
                    <p>
                        Kondisi aktual mencatat sales sebesar <strong>{{ $money($actualSales) }}</strong>
                        dan profit sebesar <strong>{{ $money($actualProfit) }}</strong>,
                        dengan profit margin <strong>{{ $percent($actualMargin) }}</strong>.
                    </p>
                </div>

                <div class="whatif-insight">
                    <h3>Baseline Scenario</h3>
                    <p>
                        Dengan asumsi pertumbuhan seragam sebesar
                        <strong>{{ $percent($baselineGrowthRate ?? 10) }}</strong>,
                        estimasi profit menjadi <strong>{{ $money($baselineProfit) }}</strong>
                        dan sales menjadi <strong>{{ $money($baselineSales) }}</strong>.
                    </p>
                </div>

                <div class="whatif-insight">
                    <h3>Alternative Scenario</h3>
                    <p>
                        Berdasarkan target pertumbuhan per wilayah yang ditentukan,
                        estimasi sales menjadi <strong>{{ $money($alternativeSales) }}</strong>
                        dan profit menjadi <strong>{{ $money($alternativeProfit) }}</strong>.
                    </p>
                </div>

                <div class="whatif-insight">
                    <h3>Incremental Profit</h3>
                    <p>
                        Alternatif alokasi pertumbuhan menghasilkan perubahan profit sebesar
                        <strong class="{{ $profitDifference >= 0 ? 'whatif-positive' : 'whatif-negative' }}">
                            {{ $signedMoney($profitDifference) }}
                        </strong>
                        dibandingkan baseline.
                        Perubahan profit margin terhadap baseline adalah
                        <strong>{{ $signedPercent($marginDifference) }}</strong>.
                    </p>
                </div>
            </div>
        </section>

        <section class="whatif-panel">
            <div class="whatif-panel-head">
                <div>
                    <h2 class="whatif-panel-title">Regional Performance Breakdown</h2>
                    <p class="whatif-panel-sub">Klik nama wilayah untuk menelusuri rincian performa hingga tingkat State dan City.</p>
                </div>
            </div>

            <div class="whatif-table-toolbar">
                <div class="whatif-breadcrumb" id="growthBreadcrumb">
                    <button class="whatif-crumb current" type="button">All Regions</button>
                </div>
                <button type="button" class="whatif-btn" id="growthBackBtn" style="display:none;">← Back</button>
            </div>

            <div class="whatif-caption" id="growthLevelCaption">Breakdown by Region</div>

            <div class="whatif-table-wrap">
                <table class="whatif-table">
                    <thead>
                        <tr>
                            <th id="growthFirstColumn">REGION</th>
                            <th>Actual Sales</th>
                            <th>Baseline Sales</th>
                            <th>Alternative Sales</th>
                            <th>Actual Profit</th>
                            <th>Baseline Profit</th>
                            <th>Alternative Profit</th>
                            <th>Profit Difference</th>
                        </tr>
                    </thead>
                    <tbody id="growthTableBody"></tbody>
                </table>
            </div>
        </section>

        <section class="whatif-panel">
            <div class="whatif-panel-head">
                <div>
                    <h2 class="whatif-panel-title">Simulation Methodology</h2>
                </div>
            </div>
            <p class="whatif-note">
                Baseline menggunakan asumsi tingkat pertumbuhan yang sama untuk seluruh wilayah.
                Alternative Scenario menggunakan tingkat pertumbuhan yang ditentukan secara terpisah
                untuk setiap wilayah. Nilai sales dihitung berdasarkan penerapan tingkat pertumbuhan
                terhadap data historis, sedangkan profit disimulasikan dengan mempertahankan struktur
                profit margin historis. Simulasi ini belum memperhitungkan perubahan kapasitas pasar,
                biaya operasional tambahan, elastisitas permintaan, maupun respons kompetitor.
                Oleh karena itu, hasilnya merupakan estimasi berbasis asumsi untuk mendukung evaluasi
                alokasi pertumbuhan, bukan proyeksi pasti.
            </p>
        </section>
    </div>
</div>

<div class="whatif-modal" id="growthHelpModal">
        <div class="whatif-modal-card">
            <button type="button" class="whatif-modal-close" id="closeGrowthHelp">&times;</button>
            <h2>Panduan Regional Growth Allocation</h2>
            <p>
                Analisis ini membantu manajemen mengevaluasi alternatif target pertumbuhan penjualan pada setiap wilayah.
            </p>
            <ul>
                <li><strong>Actual:</strong> kinerja historis berdasarkan filter yang dipilih.</li>
                <li><strong>Baseline:</strong> simulasi dengan tingkat pertumbuhan seragam untuk seluruh wilayah.</li>
                <li><strong>Alternative:</strong> simulasi dengan target pertumbuhan yang dapat diatur per wilayah.</li>
                <li><strong>Incremental Profit:</strong> selisih profit antara Alternative Scenario dan Baseline.</li>
                <li><strong>Drill-down:</strong> klik wilayah untuk melihat State, lalu klik State untuk melihat City.</li>
            </ul>
            <p>
                Gunakan perbedaan hasil antara baseline dan alternatif untuk mengevaluasi konsekuensi dari pilihan alokasi
                target pertumbuhan.
            </p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hierarchyData = @json($growthData);
            const tableBody = document.getElementById('growthTableBody');
            const breadcrumb = document.getElementById('growthBreadcrumb');
            const backBtn = document.getElementById('growthBackBtn');
            const caption = document.getElementById('growthLevelCaption');
            const firstColumn = document.getElementById('growthFirstColumn');

            let currentNodes = hierarchyData || [];
            let history = [];
            let currentLevel = 'Region';

            const money = value => '$' + Number(value || 0).toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            const signedMoney = value => (Number(value || 0) > 0 ? '+' : '') + money(value);
            const safe = value => Number(value || 0);

            function escapeHtml(value) {
                return String(value ?? '').replace(/[&<>"']/g, char => ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                })[char]);
            }

            function renderTable(nodes) {
                tableBody.innerHTML = '';

                if (!nodes || nodes.length === 0) {
                    tableBody.innerHTML =
                        '<tr><td colspan="8" style="text-align:center;color:#94a3b8;padding:25px;">No data available for this selection.</td></tr>';
                    return;
                }

                nodes.forEach((node, index) => {
                    const difference = safe(node.profit_difference);
                    const hasChildren = Array.isArray(node.children) && node.children.length > 0;
                    const row = document.createElement('tr');

                    row.innerHTML = `
                        <td>${hasChildren
                            ? `<button type="button" class="whatif-drill" data-index="${index}">
                                            <span>${escapeHtml(node.label)}</span>
                                       </button>`
                            : `<strong>${escapeHtml(node.label)}</strong>`}
                        </td>
                        <td>${money(node.actual_sales)}</td>
                        <td>${money(node.baseline_sales)}</td>
                        <td>${money(node.alternative_sales)}</td>
                        <td>${money(node.actual_profit)}</td>
                        <td>${money(node.baseline_profit)}</td>
                        <td>${money(node.alternative_profit)}</td>
                        <td class="${difference >= 0 ? 'whatif-positive' : 'whatif-negative'}">
                            ${signedMoney(difference)}
                        </td>
                    `;

                    tableBody.appendChild(row);
                });

                tableBody.querySelectorAll('.whatif-drill').forEach(button => {
                    button.addEventListener('click', function() {
                        const node = nodes[Number(this.dataset.index)];

                        history.push({
                            nodes: currentNodes,
                            level: currentLevel,
                            label: node.label
                        });

                        currentNodes = node.children || [];
                        currentLevel = currentLevel === 'Region' ? 'State' : 'City';

                        renderTable(currentNodes);
                        renderBreadcrumb();
                        updateControls();
                    });
                });
            }

            function renderBreadcrumb() {
                breadcrumb.innerHTML = '';

                const root = document.createElement('button');
                root.type = 'button';
                root.className = 'whatif-crumb' + (history.length === 0 ? ' current' : '');
                root.textContent = 'All Regions';
                root.addEventListener('click', resetTable);
                breadcrumb.appendChild(root);

                history.forEach((item, index) => {
                    const separator = document.createElement('span');
                    separator.textContent = '›';
                    breadcrumb.appendChild(separator);

                    const crumb = document.createElement('button');
                    crumb.type = 'button';
                    crumb.className = 'whatif-crumb' + (index === history.length - 1 ? ' current' : '');
                    crumb.textContent = item.label;
                    crumb.addEventListener('click', () => goToLevel(index));
                    breadcrumb.appendChild(crumb);
                });
            }

            function goToLevel(index) {
                if (index < 0) return resetTable();

                currentNodes = history[index].nodes;
                currentLevel = history[index].level;
                history = history.slice(0, index);

                renderTable(currentNodes);
                renderBreadcrumb();
                updateControls();
            }

            function resetTable() {
                currentNodes = hierarchyData || [];
                history = [];
                currentLevel = 'Region';

                renderTable(currentNodes);
                renderBreadcrumb();
                updateControls();
            }

            function updateControls() {
                backBtn.style.display = history.length ? 'inline-block' : 'none';
                caption.textContent = 'Breakdown by ' + currentLevel;
                firstColumn.textContent = currentLevel;
            }

            backBtn.addEventListener('click', function() {
                if (!history.length) return;

                const previous = history.pop();
                currentNodes = previous.nodes;
                currentLevel = previous.level;

                renderTable(currentNodes);
                renderBreadcrumb();
                updateControls();
            });

            renderTable(currentNodes);
            renderBreadcrumb();
            updateControls();

            const modal = document.getElementById('growthHelpModal');

            document.getElementById('openGrowthHelp').addEventListener('click', () => {
                modal.classList.add('show');
            });

            document.getElementById('closeGrowthHelp').addEventListener('click', () => {
                modal.classList.remove('show');
            });

            modal.addEventListener('click', event => {
                if (event.target === modal) {
                    modal.classList.remove('show');
                }
            });
        });
    </script>
@endsection
