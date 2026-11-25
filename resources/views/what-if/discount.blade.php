@extends('layouts.app')

@section('title', 'Discount Policy Simulation | Superstore EIS')

@section('content')

    @php
        $money = fn($value) => '$' . number_format((float) ($value ?? 0), 2, '.', ',');
        $percent = fn($value) => number_format((float) ($value ?? 0), 2) . '%';

        $kpis = $kpis ?? [];
        $categoryData = $categoryData ?? [];

        $salesBefore = $kpis['sales_before'] ?? 0;
        $salesAfter = $kpis['sales_after'] ?? 0;
        $profitBefore = $kpis['profit_before'] ?? 0;
        $profitAfter = $kpis['profit_after'] ?? 0;
        $marginBefore = $kpis['margin_before'] ?? 0;
        $marginAfter = $kpis['margin_after'] ?? 0;
        $negativeBefore = $kpis['negative_percentage_before'] ?? 0;
        $negativeAfter = $kpis['negative_percentage_after'] ?? 0;

        $profitDelta = $kpis['profit_delta'] ?? $profitAfter - $profitBefore;
        $salesDelta = $kpis['sales_delta'] ?? $salesAfter - $salesBefore;
        $marginDelta = $marginAfter - $marginBefore;
        $negativeDelta = $negativeAfter - $negativeBefore;

        $scenarioLabel = $scenarioLabel ?? 'Cap 20%';
    @endphp

    <style>
        .whatif-page {
            width: 100%;
            min-width: 0;
            color: var(--text-primary);
            font-family: var(--font-main);
        }

        /* FILTER */
        .whatif-filter-panel {
            display: flex;
            flex-wrap: wrap;
            align-items: end;
            gap: 16px;
            padding: 18px 22px;
            margin-bottom: 24px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        .whatif-filter {
            display: flex;
            flex-direction: column;
            gap: 6px;
            min-width: 160px;
            flex: 1;
        }

        .whatif-filter label {
            color: var(--text-muted);
            font-size: 11.5px;
            font-weight: 700;
            letter-spacing: 0.4px;
            text-transform: uppercase;
        }

        .whatif-filter select {
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

        .whatif-filter select:hover {
            border-color: #94A3B8;
            background: #FFFFFF;
        }

        .whatif-filter select:focus {
            background: #FFFFFF;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(57, 118, 197, .14);
        }

        .whatif-filter-submit {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            min-height: 42px;
            padding: 10px 22px;
            border: none;
            border-radius: 8px;
            background: var(--primary);
            color: #FFFFFF;
            font-size: 13.5px;
            font-weight: 700;
            cursor: pointer;
            transition: all .2s ease;
        }

        .whatif-filter-submit:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(40, 94, 168, 0.22);
        }

        /* SECTION */
        .whatif-section {
            margin: 26px 0;
        }

        .whatif-section-title {
            margin: 0 0 4px;
            color: var(--text-primary);
            font-size: var(--font-section-title);
            font-weight: 800;
            line-height: 1.3;
            letter-spacing: -0.3px;
        }

        .whatif-section-desc {
            margin: 0 0 16px;
            color: var(--text-muted);
            font-size: 13.5px;
            line-height: 1.55;
        }

        /* KPI */
        .whatif-kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
        }

        .whatif-kpi {
            min-width: 0;
            padding: 20px 22px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-top: 3px solid var(--primary);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            transition: transform .2s ease, box-shadow .2s ease;
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

        .whatif-kpi-main {
            color: var(--text-primary);
            font-size: var(--font-2xl);
            font-weight: 800;
            line-height: 1.25;
            letter-spacing: -.6px;
            overflow-wrap: anywhere;
        }

        .whatif-kpi-compare {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            margin-top: 12px;
            padding-top: 10px;
            border-top: 1px solid var(--border-light);
            color: var(--text-muted);
            font-size: 13px;
        }

        .whatif-kpi-compare strong {
            color: var(--text-primary);
            font-weight: 700;
            text-align: right;
        }

        .whatif-kpi-delta {
            display: inline-block;
            margin-top: 9px;
            padding: 3px 9px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
        }

        .whatif-positive {
            background: var(--success-bg);
            color: var(--success) !important;
        }

        .whatif-negative {
            background: var(--danger-bg);
            color: var(--danger) !important;
        }

        .whatif-neutral {
            background: var(--neutral-bg);
            color: var(--neutral) !important;
        }

        /* TABLE CARD */
        .whatif-card {
            min-width: 0;
            padding: 20px 22px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        .whatif-table-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            padding: 8px 14px;
            margin-bottom: 14px;
            border-radius: 9px;
            background: #F8FAFC;
            border: 1px solid #EDF1F6;
        }

        .whatif-breadcrumb {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            color: var(--text-muted);
            font-size: 13px;
        }

        .whatif-breadcrumb button {
            padding: 3px 0;
            border: none;
            background: transparent;
            color: var(--primary);
            font: inherit;
            font-weight: 700;
            cursor: pointer;
        }

        .whatif-breadcrumb button:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .whatif-breadcrumb .current {
            color: var(--text-primary);
            font-weight: 800;
        }

        .whatif-breadcrumb-separator {
            color: #94A3B8;
        }

        .whatif-back-btn {
            display: none;
            align-items: center;
            gap: 6px;
            min-height: 32px;
            padding: 5px 12px;
            border: 1px solid #CBD5E1;
            border-radius: 7px;
            background: #FFFFFF;
            color: var(--text-primary);
            font-size: 12.5px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .whatif-back-btn:hover {
            background: var(--primary-light);
            border-color: var(--primary);
            color: var(--primary-dark);
        }

        .whatif-back-btn.visible {
            display: inline-flex;
        }

        .whatif-level-caption {
            margin-bottom: 12px;
            color: var(--text-muted);
            font-size: 13px;
            font-weight: 500;
        }

        /* TABLE */
        .whatif-table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        .whatif-table {
            width: 100%;
            min-width: 850px;
            border-collapse: collapse;
            font-size: var(--font-table);
            line-height: 1.6;
        }

        .whatif-table th,
        .whatif-table td {
            padding: 13px 14px;
            border-bottom: 1px solid var(--border-light);
            text-align: right;
            white-space: nowrap;
        }

        .whatif-table th {
            background: #F8FAFC;
            color: var(--text-muted);
            font-size: 11.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        .whatif-table th:first-child {
            min-width: 210px;
            text-align: left;
        }

        .whatif-table td {
            color: #334155;
            font-size: var(--font-table);
        }

        .whatif-table td:first-child {
            text-align: left;
        }

        .whatif-table tbody tr:hover {
            background: #F8FAFC;
        }

        .whatif-table tbody tr:last-child td {
            border-bottom: none;
        }

        .whatif-table .whatif-data-row {
            cursor: pointer;
            transition: background .15s ease;
        }

        .whatif-table .whatif-data-row:hover {
            background: #F1F6FD;
        }

        .whatif-row-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 0;
            border: none;
            background: transparent;
            color: var(--text-primary);
            font: inherit;
            font-weight: 700;
            text-align: left;
            cursor: pointer;
        }

        .whatif-row-label:hover {
            color: var(--primary);
        }

        .whatif-row-arrow {
            color: var(--primary);
            font-size: 17px;
            line-height: 1;
        }

        .whatif-delta-cell {
            font-weight: 700;
            background: transparent !important;
        }

        .whatif-empty-state {
            padding: 35px 15px !important;
            color: var(--text-muted) !important;
            text-align: center !important;
        }

        .whatif-note {
            margin-top: 14px;
            padding: 12px 15px;
            border-radius: 9px;
            background: #F8FAFC;
            border: 1px solid #EDF1F6;
            color: var(--text-muted);
            font-size: 12.5px;
            line-height: 1.6;
        }

        /* INSIGHTS */
        .whatif-insight-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .whatif-insight {
            padding: 18px 20px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-left: 4px solid var(--primary);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            color: var(--text-secondary);
            font-size: 13.5px;
            line-height: 1.65;
        }

        .whatif-insight strong {
            display: block;
            margin-bottom: 6px;
            color: var(--text-primary);
            font-size: 15px;
            font-weight: 800;
        }

        /* MODAL */
        .whatif-modal {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 9999;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: rgba(15, 23, 42, .52);
        }

        .whatif-modal.active {
            display: flex;
        }

        .whatif-modal-content {
            width: 100%;
            max-width: 570px;
            max-height: 85vh;
            overflow-y: auto;
            padding: 26px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: 0 18px 45px rgba(23, 43, 77, .22);
        }

        .whatif-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            padding-bottom: 14px;
            border-bottom: 1px solid var(--border-light);
            margin-bottom: 14px;
        }

        .whatif-modal-header h3 {
            color: var(--text-primary);
            font-size: 18px;
            font-weight: 800;
        }

        .whatif-modal-close {
            width: 34px;
            height: 34px;
            flex-shrink: 0;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: #F8FAFC;
            color: var(--text-secondary);
            font-size: 19px;
            cursor: pointer;
        }

        .whatif-modal-close:hover {
            background: var(--primary-light);
            color: var(--primary-dark);
        }

        .whatif-modal-content h4 {
            margin: 16px 0 6px;
            color: var(--primary-dark);
            font-size: 14.5px;
            font-weight: 700;
        }

        .whatif-modal-content p,
        .whatif-modal-content li {
            color: var(--text-secondary);
            font-size: 13.5px;
            line-height: 1.65;
        }

        .whatif-modal-content ul {
            padding-left: 20px;
        }

        @media (max-width: 1150px) {
            .whatif-kpi-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 768px) {
            .whatif-filter-panel {
                align-items: stretch;
            }

            .whatif-filter {
                min-width: 130px;
            }

            .whatif-filter-submit {
                width: 100%;
            }

            .whatif-insight-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .whatif-kpi-grid {
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
                <h1>Discount Policy Simulation</h1>
                <p>
                    Evaluasi dampak perubahan kebijakan diskon terhadap penjualan
                    dan profitabilitas perusahaan melalui beberapa alternatif skenario.
                </p>
            </div>

            <div class="eis-hero-actions">
                <button type="button" class="eis-hero-help-btn" id="openWhatIfHelp">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Petunjuk Analisis
                </button>
            </div>
        </div>


        {{-- GLOBAL FILTER --}}
        <form action="{{ route('what-if.discount') }}" method="GET" class="whatif-filter-panel">

            <div class="whatif-filter">
                <label for="year">Tahun (Year)</label>
                <select name="year" id="year">
                    @foreach ($years as $year)
                        <option value="{{ $year }}"
                            {{ (string) $selectedYear === (string) $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="whatif-filter">
                <label for="region">Wilayah (Region)</label>
                <select name="region" id="region">
                    <option value="">All Regions</option>
                    @foreach ($regions as $region)
                        <option value="{{ $region }}" {{ $selectedRegion === $region ? 'selected' : '' }}>
                            {{ $region }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="whatif-filter">
                <label for="category">Kategori (Category)</label>
                <select name="category" id="category">
                    <option value="">All Categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category }}" {{ $selectedCategory === $category ? 'selected' : '' }}>
                            {{ $category }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="whatif-filter">
                <label for="scenario">Skenario Simulasi</label>
                <select name="scenario" id="scenario">
                    @foreach ($scenarios as $key => $scenario)
                        <option value="{{ $key }}" {{ $selectedScenario === $key ? 'selected' : '' }}>
                            {{ $scenario['label'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="whatif-filter-submit">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Apply Simulation
            </button>

        </form>


        {{-- KPI COMPARISON --}}
        <section class="whatif-section">

            <div>
                <h2 class="whatif-section-title">Executive Performance Comparison</h2>
                <p class="whatif-section-desc">
                    Perbandingan kondisi aktual dengan hasil simulasi skenario
                    <strong>{{ $scenarioLabel }}</strong>.
                </p>
            </div>

            <div class="whatif-kpi-grid">

                <div class="whatif-kpi">
                    <div class="whatif-kpi-label">Total Sales</div>
                    <div class="whatif-kpi-main">{{ $money($salesAfter) }}</div>

                    <div class="whatif-kpi-compare">
                        <span>Actual</span>
                        <strong>{{ $money($salesBefore) }}</strong>
                    </div>

                    <div class="whatif-kpi-delta {{ $salesDelta >= 0 ? 'whatif-positive' : 'whatif-negative' }}">
                        {{ $salesDelta >= 0 ? '+' : '' }}{{ $money($salesDelta) }} perubahan
                    </div>
                </div>

                <div class="whatif-kpi accent-green">
                    <div class="whatif-kpi-label">Total Profit</div>
                    <div class="whatif-kpi-main">{{ $money($profitAfter) }}</div>

                    <div class="whatif-kpi-compare">
                        <span>Actual</span>
                        <strong>{{ $money($profitBefore) }}</strong>
                    </div>

                    <div class="whatif-kpi-delta {{ $profitDelta >= 0 ? 'whatif-positive' : 'whatif-negative' }}">
                        {{ $profitDelta >= 0 ? '+' : '' }}{{ $money($profitDelta) }} perubahan
                    </div>
                </div>

                <div class="whatif-kpi accent-indigo">
                    <div class="whatif-kpi-label">Profit Margin</div>
                    <div class="whatif-kpi-main">{{ $percent($marginAfter) }}</div>

                    <div class="whatif-kpi-compare">
                        <span>Actual</span>
                        <strong>{{ $percent($marginBefore) }}</strong>
                    </div>

                    <div class="whatif-kpi-delta {{ $marginDelta >= 0 ? 'whatif-positive' : 'whatif-negative' }}">
                        {{ $marginDelta >= 0 ? '+' : '' }}{{ $percent($marginDelta) }} perubahan
                    </div>
                </div>

                <div class="whatif-kpi accent-amber">
                    <div class="whatif-kpi-label">Negative Profit Orders</div>
                    <div class="whatif-kpi-main">{{ $percent($negativeAfter) }}</div>

                    <div class="whatif-kpi-compare">
                        <span>Actual</span>
                        <strong>{{ $percent($negativeBefore) }}</strong>
                    </div>

                    <div class="whatif-kpi-delta {{ $negativeDelta <= 0 ? 'whatif-positive' : 'whatif-negative' }}">
                        {{ $negativeDelta > 0 ? '+' : '' }}{{ $percent($negativeDelta) }} perubahan
                    </div>
                </div>

            </div>
        </section>


        {{-- SCENARIO IMPACT BREAKDOWN --}}
        <section class="whatif-section">

            <div>
                <h2 class="whatif-section-title">Scenario Impact Breakdown</h2>
                <p class="whatif-section-desc">
                    Telusuri dampak simulasi dari kategori hingga kelompok diskon.
                    Pilih baris untuk melihat rincian pada tingkat berikutnya.
                </p>
            </div>

            <div class="whatif-card">

                <div class="whatif-table-toolbar">

                    <nav class="whatif-breadcrumb" id="whatifBreadcrumb" aria-label="Hierarchical navigation">
                    </nav>

                    <button type="button" class="whatif-back-btn" id="whatifBackBtn">
                        Kembali
                    </button>

                </div>

                <div class="whatif-level-caption" id="whatifLevelCaption">
                    Ringkasan berdasarkan Category
                </div>

                <div class="whatif-table-wrap">

                    <table class="whatif-table">

                        <thead>
                            <tr>
                                <th id="whatifFirstColumn">Category</th>
                                <th>Actual Sales</th>
                                <th>Scenario Sales</th>
                                <th>Actual Profit</th>
                                <th>Scenario Profit</th>
                                <th>Profit Change</th>
                            </tr>
                        </thead>

                        <tbody id="whatifTableBody">
                            <tr>
                                <td colspan="6" class="whatif-empty-state">
                                    Memuat data...
                                </td>
                            </tr>
                        </tbody>

                    </table>

                </div>

                <div class="whatif-note" id="whatifTableNote">
                    * Actual menunjukkan kondisi berdasarkan data historis.
                    Scenario menunjukkan estimasi hasil setelah kebijakan diskon diterapkan.
                </div>

            </div>
        </section>


        {{-- KEY INSIGHTS --}}
        <section class="whatif-section">

            <div>
                <h2 class="whatif-section-title">Key Insights</h2>
                <p class="whatif-section-desc">
                    Ringkasan perubahan kinerja berdasarkan skenario yang dipilih.
                </p>
            </div>

            <div class="whatif-insight-grid">

                <div class="whatif-insight">
                    <strong>Dampak terhadap Profit</strong>

                    @if ($profitDelta > 0)
                        Simulasi {{ $scenarioLabel }} menghasilkan peningkatan profit sebesar
                        {{ $money($profitDelta) }} dibandingkan kondisi aktual.
                    @elseif($profitDelta < 0)
                        Simulasi {{ $scenarioLabel }} menunjukkan penurunan profit sebesar
                        {{ $money(abs($profitDelta)) }} dibandingkan kondisi aktual.
                    @else
                        Simulasi {{ $scenarioLabel }} tidak mengubah total profit dibandingkan kondisi aktual.
                    @endif
                </div>

                <div class="whatif-insight">
                    <strong>Perubahan Profit Margin</strong>

                    @if ($marginDelta > 0)
                        Profit margin meningkat {{ $percent($marginDelta) }}.
                        Artinya, proporsi profit terhadap penjualan menjadi lebih tinggi.
                    @elseif($marginDelta < 0)
                        Profit margin menurun {{ $percent(abs($marginDelta)) }}.
                        Perlu evaluasi terhadap dampak kebijakan diskon.
                    @else
                        Profit margin tidak mengalami perubahan dibandingkan kondisi aktual.
                    @endif
                </div>

                <div class="whatif-insight">
                    <strong>Negative Profit Orders</strong>

                    @if ($negativeDelta < 0)
                        Persentase pesanan dengan total profit negatif menurun
                        {{ $percent(abs($negativeDelta)) }}.
                    @elseif($negativeDelta > 0)
                        Persentase pesanan dengan total profit negatif meningkat
                        {{ $percent($negativeDelta) }}. Kondisi ini perlu diperhatikan.
                    @else
                        Persentase pesanan dengan total profit negatif tidak berubah.
                    @endif
                </div>

                <div class="whatif-insight">
                    <strong>Interpretasi Skenario</strong>

                    @if ($selectedScenario === 'status_quo')
                        Status Quo digunakan sebagai kondisi dasar untuk membandingkan
                        hasil simulasi kebijakan diskon.
                    @else
                        Batas diskon diterapkan pada transaksi yang memiliki diskon aktual
                        melebihi batas skenario. Transaksi dengan diskon di bawah batas
                        tidak mengalami perubahan diskon.
                    @endif
                </div>

            </div>

            <div class="whatif-note">
                Catatan metodologi: simulasi mempertahankan biaya dan kuantitas pada kondisi historis.
                Perubahan permintaan pelanggan, perubahan harga, dan respons pasar tidak dimodelkan.
                Oleh karena itu, hasil merupakan estimasi dampak kebijakan, bukan prediksi penjualan aktual.
            </div>

        </section>

    </div>
</div>

{{-- PETUNJUK MODAL --}}
    <div class="whatif-modal" id="whatIfHelpModal">

        <div class="whatif-modal-content">

            <div class="whatif-modal-header">
                <h3>Panduan What-If Analysis</h3>

                <button type="button" class="whatif-modal-close" id="closeWhatIfHelp">
                    ×
                </button>
            </div>

            <h4>1. Tujuan Analisis</h4>
            <p>
                What-If Analysis digunakan untuk mengevaluasi bagaimana perubahan
                batas diskon dapat memengaruhi penjualan, profit, profit margin,
                dan jumlah pesanan yang menghasilkan profit negatif.
            </p>

            <h4>2. Pilihan Skenario</h4>
            <ul>
                <li><strong>Status Quo:</strong> mempertahankan kondisi diskon aktual.</li>
                <li><strong>Cap 20%:</strong> membatasi diskon maksimum sebesar 20%.</li>
                <li><strong>Cap 15%:</strong> membatasi diskon maksimum sebesar 15%.</li>
            </ul>

            <h4>3. Cara Membaca KPI</h4>
            <p>
                Nilai utama menunjukkan hasil skenario terpilih. Baris Actual
                menunjukkan kondisi historis, sedangkan perubahan menunjukkan
                selisih antara hasil simulasi dan kondisi aktual.
            </p>

            <h4>4. Cara Membaca Scenario Impact Breakdown</h4>
            <p>
                Tabel menampilkan satu tingkat hierarki dalam satu waktu.
                Pilih Category untuk melihat Sub-Category, kemudian pilih
                Sub-Category untuk melihat Discount Group. Gunakan breadcrumb
                atau tombol Kembali untuk berpindah ke tingkat sebelumnya.
            </p>

            <h4>5. Asumsi dan Batasan</h4>
            <p>
                Simulasi mengasumsikan biaya dan kuantitas tetap, serta tidak
                memperhitungkan perubahan perilaku pelanggan akibat perubahan diskon.
                Hasil simulasi digunakan sebagai bahan evaluasi kebijakan,
                bukan sebagai kepastian hasil bisnis di masa depan.
            </p>

        </div>

    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ==========================================
            // 1. DATA HIERARKI DARI CONTROLLER
            // ==========================================

            const hierarchyData = @json($categoryData);

            let currentLevel = 'category';
            let selectedCategory = null;
            let selectedSubcategory = null;

            const tableBody = document.getElementById('whatifTableBody');
            const breadcrumb = document.getElementById('whatifBreadcrumb');
            const backButton = document.getElementById('whatifBackBtn');
            const levelCaption = document.getElementById('whatifLevelCaption');
            const firstColumn = document.getElementById('whatifFirstColumn');


            // ==========================================
            // 2. FORMAT ANGKA
            // ==========================================

            const moneyFormatter = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            function formatMoney(value) {
                return moneyFormatter.format(Number(value) || 0);
            }

            function formatDelta(value) {
                const number = Number(value) || 0;

                if (number > 0) {
                    return '+' + formatMoney(number);
                }

                if (number < 0) {
                    return '-' + formatMoney(Math.abs(number));
                }

                return formatMoney(0);
            }


            // ==========================================
            // 3. MENGAMBIL DATA SESUAI LEVEL
            // ==========================================

            function getCurrentItems() {

                if (currentLevel === 'category') {
                    return hierarchyData || [];
                }

                if (currentLevel === 'subcategory') {
                    return selectedCategory?.children || [];
                }

                if (currentLevel === 'discount') {
                    return selectedSubcategory?.children || [];
                }

                return [];
            }


            // ==========================================
            // 4. MEMBANGUN BREADCRUMB
            // ==========================================

            function createBreadcrumbButton(label, action, isCurrent = false) {

                const button = document.createElement('button');

                button.type = 'button';
                button.textContent = label;

                if (isCurrent) {
                    button.classList.add('current');
                    button.setAttribute('aria-current', 'page');
                }

                button.addEventListener('click', action);

                return button;
            }

            function addBreadcrumbSeparator() {

                const separator = document.createElement('span');

                separator.className = 'whatif-breadcrumb-separator';
                separator.textContent = '/';

                breadcrumb.appendChild(separator);
            }

            function renderBreadcrumb() {

                breadcrumb.innerHTML = '';

                breadcrumb.appendChild(
                    createBreadcrumbButton(
                        'All Categories',
                        function() {
                            goToCategory();
                        },
                        currentLevel === 'category'
                    )
                );

                if (currentLevel === 'subcategory' || currentLevel === 'discount') {

                    addBreadcrumbSeparator();

                    breadcrumb.appendChild(
                        createBreadcrumbButton(
                            selectedCategory?.label || 'Category',
                            function() {
                                goToSubcategory();
                            },
                            currentLevel === 'subcategory'
                        )
                    );
                }

                if (currentLevel === 'discount') {

                    addBreadcrumbSeparator();

                    const current = document.createElement('span');

                    current.className = 'current';
                    current.textContent = selectedSubcategory?.label || 'Sub-Category';

                    breadcrumb.appendChild(current);
                }

                backButton.classList.toggle(
                    'visible',
                    currentLevel !== 'category'
                );
            }


            // ==========================================
            // 5. MEMBANGUN TABEL
            // ==========================================

            function createCell(value, className = '') {

                const cell = document.createElement('td');

                cell.textContent = value;

                if (className) {
                    cell.className = className;
                }

                return cell;
            }

            function createDataRow(item, index, isClickable) {

                const row = document.createElement('tr');

                row.className = isClickable ?
                    'whatif-data-row' :
                    '';

                const labelCell = document.createElement('td');

                if (isClickable) {

                    const button = document.createElement('button');

                    button.type = 'button';
                    button.className = 'whatif-row-label';

                    const label = document.createElement('span');
                    label.textContent = item.label || 'Untitled';

                    const arrow = document.createElement('span');
                    arrow.className = 'whatif-row-arrow';
                    arrow.textContent = '›';

                    button.appendChild(label);
                    button.appendChild(arrow);

                    labelCell.appendChild(button);

                    button.addEventListener('click', function() {
                        selectItem(index);
                    });

                    row.addEventListener('click', function(event) {

                        if (event.target.closest('button')) {
                            return;
                        }

                        selectItem(index);
                    });

                } else {

                    labelCell.textContent = item.label || 'Untitled';
                }

                row.appendChild(labelCell);

                row.appendChild(
                    createCell(formatMoney(item.sales_before))
                );

                row.appendChild(
                    createCell(formatMoney(item.sales_after))
                );

                row.appendChild(
                    createCell(formatMoney(item.profit_before))
                );

                row.appendChild(
                    createCell(formatMoney(item.profit_after))
                );

                const profitChange = Number(item.profit_delta) || 0;

                const deltaCell = createCell(
                    formatDelta(profitChange),
                    'whatif-delta-cell ' +
                    (profitChange > 0 ?
                        'whatif-positive' :
                        profitChange < 0 ?
                        'whatif-negative' :
                        'whatif-neutral')
                );

                row.appendChild(deltaCell);

                return row;
            }


            // ==========================================
            // 6. RENDER TABEL BERDASARKAN LEVEL
            // ==========================================

            function renderTable() {

                tableBody.innerHTML = '';

                let items = getCurrentItems();

                if (currentLevel === 'category') {

                    firstColumn.textContent = 'Category';
                    levelCaption.textContent =
                        'Ringkasan dampak simulasi berdasarkan Category';

                } else if (currentLevel === 'subcategory') {

                    firstColumn.textContent = 'Sub-Category';
                    levelCaption.textContent =
                        'Rincian Sub-Category dalam ' +
                        (selectedCategory?.label || 'Category');

                } else {

                    firstColumn.textContent = 'Discount Group';
                    levelCaption.textContent =
                        'Rincian kelompok diskon dalam ' +
                        (selectedSubcategory?.label || 'Sub-Category');
                }

                if (!items || items.length === 0) {

                    const emptyRow = document.createElement('tr');
                    const emptyCell = document.createElement('td');

                    emptyCell.colSpan = 6;
                    emptyCell.className = 'whatif-empty-state';
                    emptyCell.textContent =
                        'Tidak ada data untuk tingkat hierarki ini.';

                    emptyRow.appendChild(emptyCell);
                    tableBody.appendChild(emptyRow);

                    renderBreadcrumb();
                    return;
                }

                items.forEach(function(item, index) {

                    const clickable = currentLevel !== 'discount';

                    tableBody.appendChild(
                        createDataRow(item, index, clickable)
                    );
                });

                renderBreadcrumb();
            }


            // ==========================================
            // 7. NAVIGASI HIERARKI
            // ==========================================

            function selectItem(index) {

                const items = getCurrentItems();
                const selectedItem = items[index];

                if (!selectedItem) {
                    return;
                }

                if (currentLevel === 'category') {

                    selectedCategory = selectedItem;
                    selectedSubcategory = null;
                    currentLevel = 'subcategory';

                } else if (currentLevel === 'subcategory') {

                    selectedSubcategory = selectedItem;
                    currentLevel = 'discount';
                }

                renderTable();
            }

            function goToCategory() {

                currentLevel = 'category';
                selectedCategory = null;
                selectedSubcategory = null;

                renderTable();
            }

            function goToSubcategory() {

                if (!selectedCategory) {
                    goToCategory();
                    return;
                }

                currentLevel = 'subcategory';
                selectedSubcategory = null;

                renderTable();
            }

            backButton.addEventListener('click', function() {

                if (currentLevel === 'discount') {

                    goToSubcategory();

                } else if (currentLevel === 'subcategory') {

                    goToCategory();
                }
            });


            // ==========================================
            // 8. MODAL PETUNJUK
            // ==========================================

            const modal = document.getElementById('whatIfHelpModal');
            const openButton = document.getElementById('openWhatIfHelp');
            const closeButton = document.getElementById('closeWhatIfHelp');

            if (modal && openButton && closeButton) {

                openButton.addEventListener('click', function() {
                    modal.classList.add('active');
                });

                closeButton.addEventListener('click', function() {
                    modal.classList.remove('active');
                });

                modal.addEventListener('click', function(event) {

                    if (event.target === modal) {
                        modal.classList.remove('active');
                    }
                });

                document.addEventListener('keydown', function(event) {

                    if (event.key === 'Escape') {
                        modal.classList.remove('active');
                    }
                });
            }


            // ==========================================
            // 9. INITIAL RENDER
            // ==========================================

            renderTable();

        });
    </script>

@endsection
