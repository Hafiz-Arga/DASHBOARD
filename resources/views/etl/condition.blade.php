@extends('layouts.app')

@section('title', 'Kondisi Data | Superstore EIS')

@section('content')
    <style>
        .etl-page {
            max-width: 1250px;
            margin: auto;
        }

        .etl-top-actions {
            display: flex;
            justify-content: flex-start;
            margin-bottom: 24px;
        }

        .etl-top-actions .etl-button {
            width: auto;
            min-height: 40px;
            padding: 9px 15px;
            font-size: 13px;
        }

        .etl-header {
            margin-bottom: 25px;
        }

        .etl-header small {
            color: #3976C5;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .etl-header h1 {
            margin: 9px 0;
            color: #172B4D;
            font-size: 29px;
            font-weight: 800;
        }

        .etl-header p {
            color: #64748B;
            font-size: 15px;
            line-height: 1.7;
        }

        .etl-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }

        .etl-stat {
            min-width: 0;
            padding: 20px;
            background: white;
            border: 1px solid #DCE3ED;
            border-radius: 10px;
        }

        .etl-stat span {
            display: block;
            margin-bottom: 9px;
            color: #64748B;
            font-size: 13px;
            font-weight: 600;
        }

        .etl-stat strong {
            color: #172B4D;
            font-size: 23px;
            font-weight: 800;
            overflow-wrap: anywhere;
        }

        .etl-panel {
            margin-bottom: 25px;
            padding: 24px;
            background: white;
            border: 1px solid #DCE3ED;
            border-radius: 12px;
        }

        .etl-panel h2 {
            margin: 0 0 8px;
            color: #172B4D;
            font-size: 19px;
            font-weight: 800;
        }

        .etl-panel-description {
            margin: 0;
            color: #64748B;
            font-size: 14px;
            line-height: 1.7;
        }

        .etl-table-wrap {
            width: 100%;
            overflow-x: auto;
            margin-top: 18px;
            border: 1px solid #E2E8F0;
            border-radius: 9px;
        }

        .etl-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .etl-table th,
        .etl-table td {
            padding: 14px 15px;
            border-bottom: 1px solid #E2E8F0;
            text-align: left;
            white-space: nowrap;
        }

        .etl-table th {
            background: #EAF2FC;
            color: #285EA8;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .etl-table td {
            color: #475569;
        }

        .etl-table tbody tr:hover {
            background: #F8FAFD;
        }

        .etl-table tbody tr:last-child td {
            border-bottom: none;
        }

        .etl-table th a {
            color: #285EA8;
            text-decoration: none;
        }

        .etl-table th a:hover {
            color: #172B4D;
        }

        .etl-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 20px;
        }

        .etl-button {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            padding: 11px 17px;
            border: none;
            border-radius: 8px;
            background: #3976C5;
            color: white;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: all .2s ease;
        }

        .etl-button:hover {
            background: #285EA8;
            color: white;
        }

        .etl-button-secondary {
            border: 1px solid #3976C5;
            background: #FFFFFF;
            color: #285EA8;
        }

        .etl-button-secondary:hover {
            background: #285EA8;
            color: #FFFFFF;
        }

        .etl-empty {
            padding: 30px;
            text-align: center;
            color: #64748B;
            background: white;
            border: 1px solid #DCE3ED;
            border-radius: 10px;
        }

        .etl-empty h2 {
            margin: 0 0 8px;
            color: #172B4D;
            font-size: 18px;
        }

        .etl-empty p {
            margin: 0 0 18px;
            font-size: 14px;
        }

        /* Bagian tabel penjualan */
        .sales-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 22px;
        }

        .sales-search {
            display: flex;
            flex: 1;
            min-width: 240px;
            max-width: 470px;
            gap: 8px;
        }

        .sales-search input {
            width: 100%;
            min-width: 0;
            padding: 11px 13px;
            border: 1px solid #DCE3ED;
            border-radius: 8px;
            outline: none;
            color: #334155;
            font-family: inherit;
            font-size: 13px;
        }

        .sales-search input:focus,
        .sales-per-page select:focus {
            border-color: #3976C5;
            box-shadow: 0 0 0 3px rgba(57, 118, 197, .10);
        }

        .sales-search button {
            flex-shrink: 0;
        }

        .sales-per-page {
            display: flex;
            align-items: center;
            gap: 9px;
            color: #64748B;
            font-size: 13px;
        }

        .sales-per-page select {
            padding: 9px 30px 9px 11px;
            border: 1px solid #DCE3ED;
            border-radius: 8px;
            background: white;
            color: #334155;
            font-family: inherit;
            font-size: 13px;
            outline: none;
        }

        .sales-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 18px;
            color: #64748B;
            font-size: 13px;
        }

        .sales-info strong {
            color: #172B4D;
        }

        .sales-pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 20px;
        }

        .pagination-pages {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 5px;
        }

        .page-link {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            min-width: 34px;
            height: 34px;
            padding: 0 10px;
            border: 1px solid #DCE3ED;
            border-radius: 7px;
            background: white;
            color: #475569;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            transition: .2s ease;
        }

        .page-link:hover {
            border-color: #3976C5;
            color: #285EA8;
        }

        .page-link.active {
            border-color: #3976C5;
            background: #3976C5;
            color: white;
        }

        .page-link.disabled {
            opacity: .4;
            pointer-events: none;
        }

        .sales-empty {
            padding: 35px 20px;
            text-align: center;
            color: #64748B;
            font-size: 14px;
        }

        .sales-empty strong {
            display: block;
            margin-bottom: 6px;
            color: #172B4D;
            font-size: 15px;
        }

        .sales-negative {
            color: #D64545 !important;
            font-weight: 700;
        }

        .sales-positive {
            color: #16834A !important;
            font-weight: 700;
        }

        @media(max-width: 850px) {
            .etl-stats {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media(max-width: 550px) {
            .etl-stats {
                grid-template-columns: 1fr;
            }

            .etl-panel {
                padding: 17px;
            }

            .etl-actions {
                flex-direction: column;
            }

            .sales-toolbar {
                align-items: stretch;
            }

            .sales-search {
                max-width: none;
            }

            .sales-per-page {
                justify-content: space-between;
            }

            .sales-pagination {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>

    @php
        $columns = [
            'Row ID',
            'Order ID',
            'Order Date',
            'Ship Date',
            'Ship Mode',
            'Customer ID',
            'Customer Name',
            'Segment',
            'Country',
            'City',
            'State',
            'Postal Code',
            'Region',
            'Product ID',
            'Category',
            'Sub-Category',
            'Product Name',
            'Sales',
            'Quantity',
            'Discount',
            'Profit',
        ];

        $nextDirection = $direction === 'asc' ? 'desc' : 'asc';
    @endphp

    <div class="etl-page">
        <div class="etl-top-actions">
            <a href="{{ route('etl') }}" class="etl-button etl-button-secondary">
                Kembali ke ETL
            </a>
        </div>

        <header class="etl-header">
            <small>DATA MONITORING</small>
            <h1>Kondisi Data</h1>
            <p>
                Lihat ringkasan dataset dan telusuri seluruh data penjualan
                yang tersimpan dalam sistem Superstore EIS.
            </p>
        </header>

        @if ($summary && $summary['total_rows'] > 0)
            {{-- RINGKASAN DATA --}}
            <section class="etl-stats">
                <div class="etl-stat">
                    <span>Total Baris</span>
                    <strong>{{ number_format($summary['total_rows'], 0, ',', '.') }}</strong>
                </div>

                <div class="etl-stat">
                    <span>Total Pesanan</span>
                    <strong>{{ number_format($summary['total_orders'], 0, ',', '.') }}</strong>
                </div>

                <div class="etl-stat">
                    <span>Total Sales</span>
                    <strong>${{ number_format($summary['total_sales'], 2, ',', '.') }}</strong>
                </div>

                <div class="etl-stat">
                    <span>Total Profit</span>
                    <strong>${{ number_format($summary['total_profit'], 2, ',', '.') }}</strong>
                </div>
            </section>

            {{-- TABEL SELURUH DATA PENJUALAN --}}
            <section class="etl-panel">
                <h2>Data Penjualan</h2>
                <p class="etl-panel-description">
                    Telusuri seluruh record transaksi yang tersimpan di database.
                    Gunakan pencarian, pengurutan kolom, dan navigasi halaman
                    untuk menemukan data yang dibutuhkan.
                </p>

                {{-- NOTIFIKASI INPUT MANUAL BERHASIL --}}
                @if (session('etl_saved'))
                    <div
                        style="display:flex;align-items:flex-start;gap:12px;margin-top:18px;padding:14px 16px;background:#EAF7EF;border:1px solid #B8E3C8;border-radius:9px;color:#16834A;">
                        <span style="font-size:19px;line-height:1;">✓</span>
                        <div>
                            <strong style="display:block;margin-bottom:4px;">Data berhasil ditambahkan.</strong>
                            <span style="font-size:13px;">
                                Transaksi dengan Row ID
                                <strong>{{ session('etl_saved') }}</strong>
                                berhasil disimpan ke dalam dataset.
                            </span>
                        </div>
                    </div>
                @endif

                {{-- SEARCH DAN JUMLAH DATA --}}
                <div class="sales-toolbar">
                    <form action="{{ route('etl.condition') }}" method="GET" class="sales-search">
                        <input type="text" name="search" value="{{ $search }}"
                            placeholder="Cari Order ID, Customer, Product, City..." aria-label="Cari data penjualan">

                        <input type="hidden" name="per_page" value="{{ $perPage }}">
                        <input type="hidden" name="sort" value="{{ $sort }}">
                        <input type="hidden" name="direction" value="{{ $direction }}">

                        <button type="submit" class="etl-button">
                            Cari
                        </button>

                        @if ($search !== '')
                            <a href="{{ route('etl.condition', [
                                'per_page' => $perPage,
                                'sort' => $sort,
                                'direction' => $direction,
                            ]) }}"
                                class="etl-button" style="background:#64748B;">
                                Reset
                            </a>
                        @endif
                    </form>

                    <form action="{{ route('etl.condition') }}" method="GET" class="sales-per-page">
                        <label for="per_page">Tampilkan</label>

                        <select name="per_page" id="per_page" onchange="this.form.submit()">
                            @foreach ($perPageOptions as $option)
                                <option value="{{ $option }}" {{ $perPage == $option ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>

                        <span>data per halaman</span>

                        <input type="hidden" name="search" value="{{ $search }}">
                        <input type="hidden" name="sort" value="{{ $sort }}">
                        <input type="hidden" name="direction" value="{{ $direction }}">
                    </form>
                </div>

                {{-- INFORMASI JUMLAH RECORD --}}
                <div class="sales-info">
                    <span>
                        Menampilkan
                        <strong>{{ $salesData->firstItem() ?? 0 }}</strong>
                        sampai
                        <strong>{{ $salesData->lastItem() ?? 0 }}</strong>
                        dari
                        <strong>{{ number_format($salesData->total(), 0, ',', '.') }}</strong>
                        data
                    </span>

                    @if ($search !== '')
                        <span>
                            Hasil pencarian:
                            <strong>{{ $search }}</strong>
                        </span>
                    @endif
                </div>

                {{-- TABEL --}}
                <div class="etl-table-wrap">
                    <table class="etl-table">
                        <thead>
                            <tr>
                                @foreach ($columns as $column)
                                    @php
                                        $columnDirection = $sort === $column ? $nextDirection : 'asc';

                                        $sortUrl = route('etl.condition', [
                                            'search' => $search,
                                            'per_page' => $perPage,
                                            'sort' => $column,
                                            'direction' => $columnDirection,
                                            'page' => 1,
                                        ]);
                                    @endphp

                                    <th>
                                        <a href="{{ $sortUrl }}">
                                            {{ $column }}

                                            @if ($sort === $column)
                                                {{ $direction === 'asc' ? '↑' : '↓' }}
                                            @else
                                                ↕
                                            @endif
                                        </a>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($salesData as $row)
                                <tr>
                                    @foreach ($columns as $column)
                                        @php
                                            $value = data_get($row, $column);
                                        @endphp

                                        <td
                                            @if ($column === 'Profit' && is_numeric($value)) class="{{ (float) $value < 0 ? 'sales-negative' : 'sales-positive' }}" @endif>
                                            @if ($column === 'Sales' || $column === 'Profit')
                                                ${{ number_format((float) $value, 2, '.', ',') }}
                                            @elseif ($column === 'Discount')
                                                {{ number_format((float) $value * 100, 2, ',', '.') }}%
                                            @elseif ($column === 'Order Date' || $column === 'Ship Date')
                                                {{ $value ? \Illuminate\Support\Carbon::parse($value)->format('d/m/Y') : '-' }}
                                            @elseif ($value === null || $value === '')
                                                -
                                            @else
                                                {{ $value }}
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ count($columns) }}">
                                        <div class="sales-empty">
                                            <strong>Data tidak ditemukan</strong>
                                            Tidak ada data yang cocok dengan kata kunci pencarian.
                                            Coba gunakan kata kunci lainnya.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- DOWNLOAD CSV --}}
                <div class="etl-actions">
                    <a href="{{ route('etl.export') }}" class="etl-button">
                        Download Data CSV
                    </a>
                </div>

                {{-- PAGINATION --}}
                @if ($salesData->total() > 0)
                    <div class="sales-pagination">
                        <div class="sales-info" style="margin-top:0;">
                            Halaman
                            <strong>{{ $salesData->currentPage() }}</strong>
                            dari
                            <strong>{{ $salesData->lastPage() }}</strong>
                        </div>

                        <div class="pagination-pages">
                            {{-- Halaman pertama --}}
                            <a href="{{ $salesData->url(1) }}"
                                class="page-link {{ $salesData->onFirstPage() ? 'disabled' : '' }}"
                                aria-label="Halaman pertama">
                                «
                            </a>

                            {{-- Sebelumnya --}}
                            <a href="{{ $salesData->previousPageUrl() ?? '#' }}"
                                class="page-link {{ $salesData->onFirstPage() ? 'disabled' : '' }}">
                                ‹
                            </a>

                            {{-- Nomor halaman --}}
                            @php
                                $currentPage = $salesData->currentPage();
                                $lastPage = $salesData->lastPage();

                                $startPage = max(1, $currentPage - 2);
                                $endPage = min($lastPage, $currentPage + 2);

                                if ($currentPage <= 3) {
                                    $endPage = min(5, $lastPage);
                                }

                                if ($currentPage >= $lastPage - 2) {
                                    $startPage = max(1, $lastPage - 4);
                                }
                            @endphp

                            @if ($startPage > 1)
                                <span style="padding:0 4px;color:#94A3B8;">...</span>
                            @endif

                            @for ($page = $startPage; $page <= $endPage; $page++)
                                <a href="{{ $salesData->url($page) }}"
                                    class="page-link {{ $currentPage == $page ? 'active' : '' }}">
                                    {{ $page }}
                                </a>
                            @endfor

                            @if ($endPage < $lastPage)
                                <span style="padding:0 4px;color:#94A3B8;">...</span>
                            @endif

                            {{-- Berikutnya --}}
                            <a href="{{ $salesData->nextPageUrl() ?? '#' }}"
                                class="page-link {{ !$salesData->hasMorePages() ? 'disabled' : '' }}">
                                ›
                            </a>

                            {{-- Halaman terakhir --}}
                            <a href="{{ $salesData->url($lastPage) }}"
                                class="page-link {{ !$salesData->hasMorePages() ? 'disabled' : '' }}"
                                aria-label="Halaman terakhir">
                                »
                            </a>
                        </div>
                    </div>
                @endif
            </section>
        @else
            <div class="etl-empty">
                <h2>Belum Ada Data</h2>
                <p>
                    Tabel masih kosong. Silakan import file CSV
                    atau tambahkan data secara manual.
                </p>

                <a href="{{ route('etl.import.page') }}" class="etl-button">
                    Import Data CSV
                </a>
            </div>
        @endif
    </div>
@endsection
