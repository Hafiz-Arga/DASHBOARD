@extends('layouts.app')

@section('title', 'Executive Dashboard | Superstore EIS')

@section('content')
<div class="analytics-shell">

    {{-- HERO PAGE HEADER --}}
    <div class="eis-hero-header">
        <div>
            <span class="eis-hero-eyebrow">BUSINESS PERFORMANCE</span>
            <h1>Executive Dashboard</h1>
            <p>
                Pantau tren profitabilitas dan kinerja penjualan
                berdasarkan wilayah, kategori produk, dan kelompok diskon.
            </p>
        </div>

        <div class="eis-hero-actions">
            <span class="eis-scope-pill">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/>
                </svg>
                Interactive Drill-Down Aktif
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
                <p>Ringkasan kinerja dibandingkan periode yang sama tahun sebelumnya (YoY).</p>
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
                <h2 id="kpi-sales">$0.00</h2>
                <p id="yoy-sales" class="yoy-indicator"></p>
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
                <h2 id="kpi-profit">$0.00</h2>
                <p id="yoy-profit" class="yoy-indicator"></p>
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
                <h2 id="kpi-margin">0.00%</h2>
                <p id="yoy-margin" class="yoy-indicator"></p>
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
                <h2 id="kpi-negative">0.00%</h2>
                <p id="yoy-negative" class="yoy-indicator"></p>
            </div>

        </div>
    </section>


    {{-- CHART GRID --}}
    <section class="dashboard-section">

        <div class="section-heading">
            <div>
                <h2>Business Performance Analysis</h2>
                <p>Analisis tren dan distribusi profitabilitas berdasarkan beberapa dimensi bisnis.</p>
            </div>
        </div>

        <div class="chart-grid">

            {{-- 1. PROFIT MARGIN TREND --}}
            <div class="chart-card">

                <div class="chart-header">
                    <div>
                        <h3>Profit Margin Trend</h3>
                        <p>Perkembangan profit margin berdasarkan kuartal.</p>
                    </div>
                    <span class="chart-hint-badge">Quarterly</span>
                </div>

                <div class="chart-wrapper">
                    <canvas id="trendChart"></canvas>
                </div>

            </div>


            {{-- 2. PROFIT BY REGION / STATE / CITY --}}
            <div class="chart-card">

                <div class="chart-header">
                    <div>
                        <h3 id="geo-title">Profit by Region</h3>
                        <p>Klik batang grafik untuk melihat rincian State hingga City.</p>
                    </div>
                    <span class="chart-hint-badge">Drill-Down</span>
                </div>

                <div class="drill-bar">
                    <div class="drill-breadcrumb" id="geo-breadcrumb"></div>
                    <div class="drill-actions" id="geo-actions"></div>
                </div>

                <div class="chart-wrapper">
                    <canvas id="stateChart"></canvas>
                </div>

            </div>


            {{-- 3. PROFIT BY CATEGORY / SUB-CATEGORY / PRODUCT --}}
            <div class="chart-card">

                <div class="chart-header">
                    <div>
                        <h3 id="product-title">Profit by Category</h3>
                        <p>Klik batang grafik untuk melihat Sub-Category hingga Product.</p>
                    </div>
                    <span class="chart-hint-badge">Drill-Down</span>
                </div>

                <div class="drill-bar">
                    <div class="drill-breadcrumb" id="product-breadcrumb"></div>
                    <div class="drill-actions" id="product-actions"></div>
                </div>

                <div class="chart-wrapper">
                    <canvas id="categoryChart"></canvas>
                </div>

            </div>


            {{-- 4. PROFIT MARGIN BY DISCOUNT GROUP --}}
            <div class="chart-card">

                <div class="chart-header">
                    <div>
                        <h3>Profit Margin by Discount Group</h3>
                        <p>Perbandingan profit margin berdasarkan tingkat pemberian diskon.</p>
                    </div>
                    <span class="chart-hint-badge">Discount Impact</span>
                </div>

                <div class="chart-wrapper">
                    <canvas id="discountChart"></canvas>
                </div>

            </div>

        </div>
    </section>

{{-- CHART.JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // ==========================================
        // 1. DATA AWAL DARI LARAVEL
        // ==========================================

        let dashboardData = {
            kpis: @json($kpis),
            trend: @json($trend),
            profitByState: @json($profitByState),
            profitByCategory: @json($profitByCategory),
            discountGroups: @json($discountGroups),

            geoLevel: @json($geoLevel),
            geoRegion: @json($geoRegion),
            geoState: @json($geoState),

            productLevel: @json($productLevel),
            productCategory: @json($productCategory),
            productSubcategory: @json($productSubcategory)
        };


        // ==========================================
        // 2. HELPER FORMAT ANGKA
        // ==========================================

        function formatCurrency(value) {
            return '$' + Number(value).toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function formatPercentage(value) {
            return Number(value).toFixed(2) + '%';
        }


        // ==========================================
        // 3. UPDATE KPI DAN YOY
        // ==========================================

        function renderYoY(elementId, yoy, inverse = false) {

            const element = document.getElementById(elementId);

            if (yoy === null || yoy === undefined) {
                element.innerHTML = `
                    <span class="yoy-icon">—</span>
                    <span>Tidak tersedia vs LY</span>
                `;
                element.className = 'yoy-indicator yoy-neutral';
                return;
            }

            const value = Number(yoy);

            const isIncrease = value > 0;
            const isGood = inverse ? !isIncrease : isIncrease;

            const icon = isIncrease ?
                '↑' :
                (value < 0 ? '↓' : '→');

            const sign = value > 0 ?
                '+' :
                (value < 0 ? '-' : '');

            element.innerHTML = `
                <span class="yoy-icon">${icon}</span>
                <span>${sign}${Math.abs(value).toFixed(1)}% vs LY</span>
            `;

            element.className = 'yoy-indicator ' +
                (value === 0 ?
                    'yoy-neutral' :
                    (isGood ? 'good' : 'bad'));
        }


        function updateKPI(kpis) {

            document.getElementById('kpi-sales').textContent =
                formatCurrency(kpis.sales.value);

            document.getElementById('kpi-profit').textContent =
                formatCurrency(kpis.profit.value);

            document.getElementById('kpi-margin').textContent =
                formatPercentage(kpis.margin.value);

            document.getElementById('kpi-negative').textContent =
                formatPercentage(kpis.negative.value);

            renderYoY('yoy-sales', kpis.sales.yoy);
            renderYoY('yoy-profit', kpis.profit.yoy);
            renderYoY('yoy-margin', kpis.margin.yoy);
            renderYoY('yoy-negative', kpis.negative.yoy, true);
        }


        // ==========================================
        // 4. BREADCRUMB DAN TOMBOL DRILL-DOWN
        // ==========================================

        function updateGeoNavigation(data) {

            const level = data.geoLevel;

            const breadcrumb = document.getElementById('geo-breadcrumb');
            const title = document.getElementById('geo-title');
            const actions = document.getElementById('geo-actions');

            title.textContent = 'Profit by ' +
                (level === 'region' ?
                    'Region' :
                    level === 'state' ?
                    'State' :
                    'City');

            let path = '<span>Region</span>';

            if (data.geoRegion !== '') {
                path += ` <span>›</span> <strong>${escapeHTML(data.geoRegion)}</strong>`;
            }

            if (data.geoState !== '') {
                path += ` <span>›</span> <strong>${escapeHTML(data.geoState)}</strong>`;
            }

            breadcrumb.innerHTML = path;

            actions.innerHTML = '';

            if (level !== 'region') {

                actions.innerHTML += `
                    <button type="button" class="drill-button"
                        onclick="goBackGeo()">
                        Kembali
                    </button>

                    <button type="button" class="drill-button"
                        onclick="resetGeo()">
                        Reset
                    </button>
                `;
            }
        }


        function updateProductNavigation(data) {

            const level = data.productLevel;

            const breadcrumb = document.getElementById('product-breadcrumb');
            const title = document.getElementById('product-title');
            const actions = document.getElementById('product-actions');

            title.textContent = 'Profit by ' +
                (level === 'category' ?
                    'Category' :
                    level === 'sub-category' ?
                    'Sub-Category' :
                    'Product');

            let path = '<span>Category</span>';

            if (data.productCategory !== '') {
                path += ` <span>›</span> <strong>${escapeHTML(data.productCategory)}</strong>`;
            }

            if (data.productSubcategory !== '') {
                path += ` <span>›</span> <strong>${escapeHTML(data.productSubcategory)}</strong>`;
            }

            breadcrumb.innerHTML = path;

            actions.innerHTML = '';

            if (level !== 'category') {

                actions.innerHTML += `
                    <button type="button" class="drill-button"
                        onclick="goBackProduct()">
                        Kembali
                    </button>

                    <button type="button" class="drill-button"
                        onclick="resetProduct()">
                        Reset
                    </button>
                `;
            }
        }


        function escapeHTML(value) {

            return String(value).replace(/[&<>"']/g, function(character) {

                return {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                } [character];

            });
        }


        // ==========================================
        // 5. KONFIGURASI CHART
        // ==========================================

        let trendChart;
        let stateChart;
        let categoryChart;
        let discountChart;
        // ==========================================
        // PALET WARNA SUPERSTORE EIS
        // ==========================================

        const bluePalette = [
            '#D6E6F7',
            '#B5D0EE',
            '#8DB5E2',
            '#669BD4',
            '#4381C3',
            '#2F68A8',
            '#1D4E89'
        ];

        const bluePrimary = '#2F68A8';
        const blueDark = '#1D4E89';
        const blueNegative = '#D96B6B';

        // Warna otomatis berdasarkan nilai
        function getProfitColors(data) {
            return data.map((item, index) => {
                const value = Number(item.profit);

                if (value < 0) {
                    return blueNegative;
                }

                return bluePalette[index % bluePalette.length];
            });
        }

        function getMarginColors(data) {
            return data.map((item, index) => {
                const value = Number(item.margin);

                if (value < 0) {
                    return blueNegative;
                }

                return bluePalette[index % bluePalette.length];
            });
        }

        // Pengaturan visual umum mengikuti layout utama
        Chart.defaults.font.family = getComputedStyle(document.documentElement)
            .getPropertyValue('--font-main').trim();

        Chart.defaults.font.size = parseInt(
            getComputedStyle(document.documentElement)
            .getPropertyValue('--chart-font-size')
        );

        Chart.defaults.color = '#475569';

        Chart.defaults.plugins.tooltip.backgroundColor = '#172B4D';
        Chart.defaults.plugins.tooltip.titleColor = '#FFFFFF';
        Chart.defaults.plugins.tooltip.bodyColor = '#FFFFFF';
        Chart.defaults.plugins.tooltip.padding = 12;
        Chart.defaults.plugins.tooltip.cornerRadius = 8;


        // PROFIT MARGIN TREND

        function createTrendChart(data) {

            if (trendChart) {
                trendChart.destroy();
            }

            trendChart = new Chart(
                document.getElementById('trendChart'), {

                    type: 'line',

                    data: {
                        labels: data.map(item => item.period),

                        datasets: [{
                            label: 'Profit Margin (%)',
                            data: data.map(item => Number(item.margin)),

                            borderColor: bluePrimary,
                            backgroundColor: 'rgba(47, 104, 168, 0.12)',

                            pointBackgroundColor: '#FFFFFF',
                            pointBorderColor: bluePrimary,
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,

                            borderWidth: 3,
                            tension: 0.3,
                            fill: true
                        }]
                    },

                    options: {
                        responsive: true,
                        maintainAspectRatio: false,

                        plugins: {
                            legend: {
                                display: false
                            }
                        },

                        scales: {
                            x: {
                                grid: {
                                    display: false
                                }
                            },

                            y: {
                                beginAtZero: true,

                                grid: {
                                    color: '#EDF1F6'
                                },

                                ticks: {
                                    callback: value => value + '%'
                                }
                            }
                        }
                    }
                }
            );
        }


        // PROFIT BY REGION / STATE / CITY

        function createGeoChart(data) {

            if (stateChart) {
                stateChart.destroy();
            }

            stateChart = new Chart(
                document.getElementById('stateChart'), {

                    type: 'bar',

                    data: {
                        labels: data.map(item => item.label),

                        datasets: [{
                            label: 'Profit ($)',
                            data: data.map(item => Number(item.profit)),

                            backgroundColor: getProfitColors(data),
                            borderWidth: 0,
                            borderRadius: 5,
                            barThickness: 'flex',
                            maxBarThickness: 28
                        }]
                    },

                    options: {

                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,

                        plugins: {
                            legend: {
                                display: false
                            },

                            tooltip: {
                                callbacks: {
                                    label: context =>
                                        'Profit: ' + formatCurrency(context.raw)
                                }
                            }
                        },

                        scales: {
                            x: {
                                grid: {
                                    color: '#EDF1F6'
                                },

                                border: {
                                    display: false
                                },

                                ticks: {
                                    callback: value =>
                                        '$' + Number(value).toLocaleString('en-US')
                                }
                            },

                            y: {
                                grid: {
                                    display: false
                                },

                                border: {
                                    display: false
                                }
                            }
                        },

                        onClick: (event, elements) => {

                            if (!elements.length ||
                                dashboardData.geoLevel === 'city') {
                                return;
                            }

                            const index = elements[0].index;
                            const selectedValue = data[index].label;

                            const params =
                                new URLSearchParams(window.location.search);

                            if (dashboardData.geoLevel === 'region') {

                                params.set('geo_level', 'state');
                                params.set('geo_region', selectedValue);
                                params.delete('geo_state');

                            } else if (dashboardData.geoLevel === 'state') {

                                params.set('geo_level', 'city');
                                params.set('geo_state', selectedValue);
                            }

                            loadDashboard(params);
                        }
                    }
                }
            );
        }

        // PROFIT BY CATEGORY / SUB-CATEGORY / PRODUCT

        function createProductChart(data) {

            if (categoryChart) {
                categoryChart.destroy();
            }

            categoryChart = new Chart(
                document.getElementById('categoryChart'), {

                    type: 'bar',

                    data: {
                        labels: data.map(item => item.label),

                        datasets: [{
                            label: 'Profit ($)',
                            data: data.map(item => Number(item.profit)),

                            backgroundColor: getProfitColors(data),
                            borderWidth: 0,
                            borderRadius: 5,
                            barThickness: 'flex',
                            maxBarThickness: 24
                        }]
                    },

                    options: {

                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,

                        plugins: {
                            legend: {
                                display: false
                            },

                            tooltip: {
                                callbacks: {
                                    title: items => data[items[0].dataIndex].label,

                                    label: context =>
                                        'Profit: ' + formatCurrency(context.raw)
                                }
                            }
                        },

                        scales: {
                            x: {
                                grid: {
                                    color: '#EDF1F6'
                                },

                                border: {
                                    display: false
                                },

                                ticks: {
                                    callback: value =>
                                        '$' + Number(value).toLocaleString('en-US')
                                }
                            },

                            y: {
                                grid: {
                                    display: false
                                },

                                border: {
                                    display: false
                                },

                                ticks: {
                                    font: {
                                        size: Chart.defaults.font.size
                                    }
                                }
                            }
                        },

                        onClick: (event, elements) => {

                            if (!elements.length ||
                                dashboardData.productLevel === 'product') {
                                return;
                            }

                            const index = elements[0].index;
                            const selectedValue = data[index].label;

                            const params =
                                new URLSearchParams(window.location.search);

                            if (dashboardData.productLevel === 'category') {

                                params.set('product_level', 'sub-category');
                                params.set('product_category', selectedValue);
                                params.delete('product_subcategory');

                            } else if (dashboardData.productLevel === 'sub-category') {

                                params.set('product_level', 'product');
                                params.set('product_subcategory', selectedValue);
                            }

                            loadDashboard(params);
                        }
                    }
                }
            );
        }


        // PROFIT MARGIN BY DISCOUNT GROUP

        function createDiscountChart(data) {

            if (discountChart) {
                discountChart.destroy();
            }

            discountChart = new Chart(
                document.getElementById('discountChart'), {

                    type: 'bar',

                    data: {
                        labels: data.map(item => item.discount_group),

                        datasets: [{
                            label: 'Profit Margin (%)',
                            data: data.map(item => Number(item.margin)),

                            backgroundColor: getMarginColors(data),
                            borderWidth: 0,
                            borderRadius: 5,
                            maxBarThickness: 48
                        }]
                    },

                    options: {

                        responsive: true,
                        maintainAspectRatio: false,

                        plugins: {
                            legend: {
                                display: false
                            },

                            tooltip: {
                                callbacks: {
                                    label: context =>
                                        'Profit Margin: ' +
                                        Number(context.raw).toFixed(2) + '%'
                                }
                            }
                        },

                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },

                                border: {
                                    display: false
                                }
                            },

                            y: {
                                grid: {
                                    color: '#EDF1F6'
                                },

                                border: {
                                    display: false
                                },

                                ticks: {
                                    callback: value => value + '%'
                                }
                            }
                        }
                    }
                }
            );
        }


        // ==========================================
        // 6. RENDER SELURUH DASHBOARD
        // ==========================================

        function renderDashboard(data) {

            dashboardData = {
                ...dashboardData,
                ...data
            };

            updateKPI(data.kpis);

            updateGeoNavigation(data);
            updateProductNavigation(data);

            createTrendChart(data.trend);
            createGeoChart(data.profitByState);
            createProductChart(data.profitByCategory);
            createDiscountChart(data.discountGroups);
        }


        // ==========================================
        // 7. AJAX TANPA RELOAD HALAMAN
        // ==========================================

        async function loadDashboard(params) {

            const url = '{{ url('/dashboard') }}?' + params.toString();

            try {

                document.body.style.cursor = 'wait';

                const response = await fetch(url, {

                    method: 'GET',

                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error('Gagal mengambil data dashboard.');
                }

                const data = await response.json();

                window.history.pushState({}, '', url);

                renderDashboard(data);

            } catch (error) {

                console.error(error);

                alert('Terjadi kesalahan saat memperbarui dashboard.');

            } finally {

                document.body.style.cursor = 'default';
            }
        }


        // ==========================================
        // 8. NAVIGASI KEMBALI GEOGRAFIS
        // ==========================================

        function goBackGeo() {

            const params =
                new URLSearchParams(window.location.search);

            if (dashboardData.geoLevel === 'city') {

                params.set('geo_level', 'state');
                params.delete('geo_state');

            } else if (dashboardData.geoLevel === 'state') {

                params.set('geo_level', 'region');
                params.delete('geo_region');
                params.delete('geo_state');
            }

            loadDashboard(params);
        }


        function resetGeo() {

            const params =
                new URLSearchParams(window.location.search);

            params.delete('geo_level');
            params.delete('geo_region');
            params.delete('geo_state');
            params.delete('geo_value');

            loadDashboard(params);
        }


        // ==========================================
        // 9. NAVIGASI KEMBALI PRODUK
        // ==========================================

        function goBackProduct() {

            const params =
                new URLSearchParams(window.location.search);

            if (dashboardData.productLevel === 'product') {

                params.set('product_level', 'sub-category');
                params.delete('product_subcategory');

            } else if (dashboardData.productLevel === 'sub-category') {

                params.set('product_level', 'category');
                params.delete('product_category');
                params.delete('product_subcategory');
            }

            loadDashboard(params);
        }


        function resetProduct() {

            const params =
                new URLSearchParams(window.location.search);

            params.delete('product_level');
            params.delete('product_category');
            params.delete('product_subcategory');
            params.delete('product_value');

            loadDashboard(params);
        }


        // ==========================================
        // 10. TOMBOL BACK BROWSER
        // ==========================================

        window.addEventListener('popstate', async function() {

            const params =
                new URLSearchParams(window.location.search);

            const url = '{{ url('/dashboard') }}?' + params.toString();

            try {

                const response = await fetch(url, {

                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error('Gagal memuat data.');
                }

                const data = await response.json();

                renderDashboard(data);

            } catch (error) {

                console.error(error);
            }
        });


        // ==========================================
        // 11. TAMPILKAN DATA AWAL
        // ==========================================

        renderDashboard(dashboardData);
    </script>

</div>
@endsection
