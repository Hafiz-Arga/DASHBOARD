@extends('layouts.app')

@section('title', 'Executive Summary | Superstore EIS')

@section('content')
<div class="analytics-shell">

    {{-- HERO PAGE HEADER --}}
    <div class="eis-hero-header">
        <div>
            <span class="eis-hero-eyebrow">BUSINESS OVERVIEW</span>
            <h1>Executive Summary</h1>
            <p>
                Ringkasan kondisi dan kinerja bisnis Superstore
                berdasarkan periode dan cakupan yang dipilih.
            </p>
        </div>

        <div class="eis-hero-actions">
            <span class="eis-scope-pill">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                {{ $selectedYear ?: 'All Years' }} · {{ $selectedRegion ?: 'All Regions' }}
            </span>
        </div>
    </div>


    {{-- GLOBAL FILTER --}}
    @include('partials.global-filter')


    {{-- KPI OVERVIEW --}}
    <section class="dashboard-section">

        <div class="section-heading">
            <div>
                <h2>Key Performance Indicators</h2>
                <p>Indikator utama untuk memahami kondisi kinerja bisnis secara keseluruhan.</p>
            </div>
        </div>

        <div class="kpi-grid">

            <div class="kpi-card">
                <div class="kpi-card-top">
                    <p class="kpi-label">Total Sales</p>
                    <span class="kpi-icon-badge">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.1" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                </div>
                <h2>${{ number_format($totalSales, 2) }}</h2>
            </div>

            <div class="kpi-card accent-green">
                <div class="kpi-card-top">
                    <p class="kpi-label">Total Profit</p>
                    <span class="kpi-icon-badge">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.1" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </span>
                </div>
                <h2>${{ number_format($totalProfit, 2) }}</h2>
            </div>

            <div class="kpi-card accent-indigo">
                <div class="kpi-card-top">
                    <p class="kpi-label">Profit Margin</p>
                    <span class="kpi-icon-badge">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.1" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </span>
                </div>
                <h2>{{ number_format($profitMargin, 2) }}%</h2>
            </div>

            <div class="kpi-card accent-amber">
                <div class="kpi-card-top">
                    <p class="kpi-label">Negative Profit Orders</p>
                    <span class="kpi-icon-badge">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.1" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </span>
                </div>
                <h2>{{ number_format($negativeProfitPercentage, 2) }}%</h2>
            </div>

        </div>
    </section>


    {{-- BUSINESS PERFORMANCE OVERVIEW --}}
    <section class="summary-section">

        <div class="section-header">
            <div>
                <h2>Business Performance Overview</h2>
                <p>Kontribusi penjualan dan profit berdasarkan kategori produk.</p>
            </div>
        </div>

        <div class="summary-card">

            <div class="table-responsive">

                <table class="summary-table">

                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Total Sales</th>
                            <th>Sales Contribution</th>
                            <th>Total Profit</th>
                            <th>Profit Contribution</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($categoryPerformance as $category)
                            @php
                                $salesPct = max(0, min(100, (float) $category->sales_contribution));
                                $profitPct = $category->profit_contribution !== null ? max(0, min(100, (float) $category->profit_contribution)) : 0;
                            @endphp
                            <tr>
                                <td>
                                    <span class="category-name">
                                        {{ $category->Category }}
                                    </span>
                                </td>

                                <td>
                                    ${{ number_format($category->total_sales, 2) }}
                                </td>

                                <td>
                                    <div class="contrib-bar-wrap">
                                        <div class="contrib-bar-track">
                                            <div class="contrib-bar-fill" style="width: {{ $salesPct }}%"></div>
                                        </div>
                                        <span>{{ number_format($category->sales_contribution, 1) }}%</span>
                                    </div>
                                </td>

                                <td class="{{ $category->total_profit < 0 ? 'profit-negative' : 'profit-positive' }}">
                                    ${{ number_format($category->total_profit, 2) }}
                                </td>

                                <td>
                                    @if ($category->profit_contribution !== null)
                                        <div class="contrib-bar-wrap">
                                            <div class="contrib-bar-track">
                                                <div class="contrib-bar-fill profit" style="width: {{ $profitPct }}%"></div>
                                            </div>
                                            <span>{{ number_format($category->profit_contribution, 1) }}%</span>
                                        </div>
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="5" class="empty-state">
                                    Tidak ada data kategori.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </section>


    {{-- BOTTOM 5 PRODUCTS & KEY FINDINGS --}}
    <div class="summary-bottom-grid">

        {{-- BOTTOM 5 PRODUCTS --}}
        <section class="summary-section">

            <div class="section-header">
                <div>
                    <h2>Bottom 5 Products by Profit</h2>
                    <p>Lima produk dengan total profit terendah yang memerlukan perhatian.</p>
                </div>
            </div>

            <div class="summary-card">

                <div class="table-responsive">

                    <table class="summary-table">

                        <thead>
                            <tr>
                                <th style="width: 46px;">#</th>
                                <th>Product Name</th>
                                <th style="text-align: right;">Total Profit</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse ($bottomProducts as $idx => $product)
                                <tr>
                                    <td style="color: var(--text-muted); font-weight: 700;">{{ $idx + 1 }}</td>
                                    <td style="font-weight: 500; color: var(--text-primary);">
                                        {{ $product->{'Product Name'} }}
                                    </td>

                                    <td style="text-align: right;" class="{{ $product->total_profit < 0 ? 'profit-negative' : 'profit-positive' }}">
                                        ${{ number_format($product->total_profit, 2) }}
                                    </td>
                                </tr>

                            @empty

                                <tr>
                                    <td colspan="3" class="empty-state">
                                        Tidak ada data produk.
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </section>


        {{-- KEY FINDINGS --}}
        <section class="summary-section">

            <div class="section-header">
                <div>
                    <h2>Key Findings</h2>
                    <p>Temuan strategis utama berdasarkan cakupan data terpilih.</p>
                </div>
            </div>

            <div class="summary-card">

                <div class="findings-list">

                    @forelse ($keyFindings as $idx => $finding)
                        <div class="finding-item">
                            <span class="finding-marker">{{ $idx + 1 }}</span>
                            <p>{{ $finding }}</p>
                        </div>

                    @empty

                        <p class="empty-state">
                            Belum ada temuan yang dapat ditampilkan.
                        </p>
                    @endforelse

                </div>

            </div>

        </section>

    </div>

</div>
@endsection
