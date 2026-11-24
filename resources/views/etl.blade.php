/*
@extends('layouts.app')

@section('title', 'ETL Data | Superstore EIS')

@section('content')

    <style>
        .etl-page {
            width: 100%;
            max-width: 1280px;
            margin: 0 auto;
        }

        .etl-topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 30px;
        }

        .etl-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: var(--font-sm);
            font-weight: 600;
            transition: color .2s ease;
        }

        .etl-back:hover {
            color: var(--primary);
        }

        .etl-user {
            color: var(--text-muted);
            font-size: var(--font-xs);
        }

        .etl-heading {
            margin-bottom: 30px;
            padding-bottom: 24px;
            border-bottom: 1px solid var(--border);
        }

        .etl-eyebrow {
            display: block;
            margin-bottom: 8px;
            color: var(--primary);
            font-size: var(--font-xs);
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .etl-heading h1 {
            margin: 0 0 9px;
            color: var(--text-primary);
            font-size: var(--font-3xl);
            font-weight: 700;
            line-height: 1.35;
        }

        .etl-heading p {
            max-width: 760px;
            color: var(--text-secondary);
            font-size: var(--font-description);
            line-height: 1.7;
        }

        .etl-section {
            margin-bottom: 28px;
        }

        .etl-section-heading {
            margin-bottom: 15px;
        }

        .etl-section-heading h2 {
            color: var(--text-primary);
            font-size: var(--font-xl);
            font-weight: 700;
            margin-bottom: 5px;
        }

        .etl-section-heading p {
            color: var(--text-secondary);
            font-size: var(--font-description);
            line-height: 1.7;
        }

        /* Header kondisi tabel dan tombol download */
        .etl-section-heading-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        .etl-section-heading-top>div {
            min-width: 0;
        }

        .etl-download-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 44px;
            padding: 10px 18px;
            background: var(--primary);
            color: #FFFFFF;
            border: 1px solid var(--primary);
            border-radius: 7px;
            font-family: inherit;
            font-size: var(--font-sm);
            font-weight: 600;
            text-decoration: none;
            white-space: nowrap;
            transition: background .2s ease, border-color .2s ease;
        }

        .etl-download-button:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
            color: #FFFFFF;
        }

        .etl-panel {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 25px;
            box-shadow: var(--shadow);
        }

        .etl-alert {
            padding: 14px 18px;
            border-radius: 8px;
            font-size: var(--font-sm);
            line-height: 1.7;
            margin-bottom: 18px;
        }

        .etl-alert.success {
            background: var(--success-bg);
            color: var(--success);
        }

        .etl-alert.error {
            background: var(--danger-bg);
            color: var(--danger);
        }

        .etl-alert strong {
            font-weight: 700;
        }

        .etl-note {
            color: var(--text-muted);
            font-size: var(--font-xs);
            line-height: 1.7;
            margin-top: 13px;
        }

        .etl-errors {
            margin-top: 10px;
            padding-left: 20px;
            color: var(--text-secondary);
            font-size: var(--font-xs);
            line-height: 1.8;
        }

        .etl-file {
            width: 100%;
            min-height: 46px;
            padding: 9px 12px;
            border: 1px solid #CBD5E1;
            border-radius: 7px;
            background: #FFFFFF;
            color: var(--text-primary);
            font-size: var(--font-sm);
        }

        .etl-form-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
        }

        .etl-form-grid .filter-group {
            min-width: 0;
        }

        .etl-form-grid input,
        .etl-form-grid select {
            width: 100%;
            min-height: 44px;
            padding: 10px 12px;
            border: 1px solid #CBD5E1;
            border-radius: 7px;
            background: #FFFFFF;
            color: var(--text-primary);
            font-family: inherit;
            font-size: var(--font-sm);
        }

        .etl-form-grid input:focus,
        .etl-form-grid select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(57, 118, 197, .12);
        }

        .etl-form-grid .field-error {
            color: var(--danger);
            font-size: var(--font-xs);
        }

        .etl-check {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            max-width: 430px;
            color: var(--text-secondary);
            font-size: var(--font-sm);
            line-height: 1.6;
        }

        .etl-check input {
            margin-top: 5px;
            accent-color: var(--primary);
        }

        .etl-form-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .etl-button-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 44px;
            padding: 10px 19px;
            border: 1px solid var(--border);
            border-radius: 7px;
            background: #FFFFFF;
            color: var(--text-secondary);
            font-family: inherit;
            font-size: var(--font-sm);
            font-weight: 600;
            cursor: pointer;
            transition: .2s ease;
        }

        .etl-button-secondary:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--primary-light);
        }

        .etl-button-danger {
            background: #A93636;
        }

        .etl-button-danger:hover {
            background: #8E2929;
        }

        .etl-result-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 28px;
        }

        .etl-result-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 9px;
            padding: 18px;
        }

        .etl-result-card p {
            color: var(--text-secondary);
            font-size: var(--font-xs);
            font-weight: 600;
            margin-bottom: 8px;
        }

        .etl-result-card h3 {
            color: var(--text-primary);
            font-size: 25px;
            font-weight: 700;
        }

        .etl-result-card.highlight {
            border-color: #B5D0EE;
            background: #F7FAFE;
        }

        .etl-table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        .etl-table {
            width: 100%;
            border-collapse: collapse;
            font-size: var(--font-sm);
        }

        .etl-table th,
        .etl-table td {
            padding: 13px 15px;
            border-bottom: 1px solid var(--border-light);
            text-align: left;
            white-space: nowrap;
        }

        .etl-table th {
            background: #F8FAFC;
            color: var(--text-muted);
            font-size: var(--font-xs);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .3px;
        }

        .etl-table td {
            color: var(--text-secondary);
        }

        .etl-table tr:last-child td {
            border-bottom: none;
        }

        .etl-status-ok {
            color: var(--success) !important;
            font-weight: 700;
        }

        .etl-status-different {
            color: var(--danger) !important;
            font-weight: 700;
        }

        .etl-empty {
            padding: 28px;
            text-align: center;
            color: var(--text-muted);
            font-size: var(--font-sm);
        }

        .etl-divider {
            height: 1px;
            background: var(--border-light);
            margin: 24px 0;
        }

        @media (max-width: 1000px) {
            .etl-form-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .etl-result-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 600px) {
            .etl-page {
                padding: 0;
            }

            .etl-topbar {
                align-items: flex-start;
            }

            .etl-heading h1 {
                font-size: 25px;
            }

            .etl-panel {
                padding: 17px;
            }

            .etl-form-grid,
            .etl-result-grid {
                grid-template-columns: 1fr;
            }

            .etl-form-actions {
                justify-content: stretch;
            }

            .etl-form-actions button {
                width: 100%;
            }

            .etl-section-heading-top {
                align-items: flex-start;
                flex-direction: column;
            }

            .etl-download-button {
                width: 100%;
            }
        }
    </style>

    <div class="etl-page">

        {{-- PAGE HEADER --}}
        <div class="etl-heading">
            <span class="etl-eyebrow">DATA MANAGEMENT</span>
            <h1>ETL Data</h1>
            <p>
                Kelola data Superstore melalui proses Extract, Transform, dan Load.
                Anda dapat mengganti seluruh data, menambahkan data dari CSV,
                atau memasukkan satu data secara manual.
            </p>
        </div>

        {{-- SUCCESS: MANUAL INPUT --}}
        @if ($saved)
            <div class="etl-alert success">
                Data berhasil ditambahkan dengan Row ID <strong>{{ $saved }}</strong>.
            </div>
        @endif

        {{-- SUCCESS: CSV IMPORT --}}
        @if ($result)
            <div class="etl-alert success">
                @if ($result['mode'] === 'replace')
                    Import replace berhasil. Data lama telah diganti dengan
                    {{ number_format($result['loaded'], 0, ',', '.') }} baris data baru.
                @else
                    Import tambah berhasil. Sebanyak
                    {{ number_format($result['loaded'], 0, ',', '.') }} baris data baru ditambahkan.
                    Data lama tetap dipertahankan.
                @endif
            </div>

            <div class="etl-result-grid">
                <div class="etl-result-card">
                    <p>Baris Dibaca (Extract)</p>
                    <h3>{{ number_format($result['read'], 0, ',', '.') }}</h3>
                </div>

                <div class="etl-result-card">
                    <p>Duplikat dalam CSV</p>
                    <h3>{{ number_format($result['duplicates'], 0, ',', '.') }}</h3>
                </div>

                @if ($result['mode'] === 'append')
                    <div class="etl-result-card">
                        <p>Row ID Sudah Ada</p>
                        <h3>{{ number_format($result['skipped_existing'], 0, ',', '.') }}</h3>
                    </div>
                @endif

                <div class="etl-result-card">
                    <p>Baris Tidak Valid</p>
                    <h3>{{ number_format($result['invalid'], 0, ',', '.') }}</h3>
                </div>

                <div class="etl-result-card highlight">
                    <p>Baris Dimuat (Load)</p>
                    <h3>{{ number_format($result['loaded'], 0, ',', '.') }}</h3>
                </div>
            </div>

            @if (!empty($result['errors']))
                <div class="etl-panel" style="margin-bottom: 25px;">
                    <strong>Daftar baris yang dilewati (maksimal 20)</strong>
                    <ul class="etl-errors">
                        @foreach ($result['errors'] as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        @endif

        {{-- 1. IMPORT REPLACE --}}
        <section class="etl-section">
            <div class="etl-section-heading">
                <h2>01. Ganti Seluruh Data</h2>
                <p>
                    Menghapus seluruh isi tabel saat ini dan menggantinya dengan data dari file CSV.
                </p>
            </div>

            <div class="etl-panel">

                @if ($errors->getBag('replace')->any())
                    <div class="etl-alert error">
                        @foreach ($errors->getBag('replace')->all() as $message)
                            <div>{{ $message }}</div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('etl.replace') }}" method="POST" enctype="multipart/form-data"
                    onsubmit="return confirm('Seluruh data lama akan dihapus dan diganti. Apakah Anda yakin ingin melanjutkan?');">
                    @csrf

                    <div class="filter-group" style="max-width: 500px;">
                        <label for="csv_replace">Pilih File CSV</label>
                        <input type="file" id="csv_replace" name="csv" accept=".csv,.txt" class="etl-file" required>
                    </div>

                    <div class="etl-divider"></div>

                    <label class="etl-check">
                        <input type="checkbox" name="confirm" value="1" required>
                        <span>
                            Saya memahami bahwa seluruh data lama akan dihapus dan diganti
                            dengan data dari file yang dipilih.
                        </span>
                    </label>

                    <div class="etl-form-actions">
                        <button type="submit" class="filter-button etl-button-danger">
                            Ganti Seluruh Data
                        </button>
                    </div>
                </form>

                <p class="etl-note">
                    Proses dilakukan dalam satu transaksi. Jika proses gagal, data lama tetap utuh.
                    Transformasi meliputi penyesuaian format tanggal, angka desimal, spasi nama produk,
                    kode pos, dan pemeriksaan Row ID duplikat.
                </p>
            </div>
        </section>

        {{-- 2. IMPORT APPEND --}}
        <section class="etl-section">
            <div class="etl-section-heading">
                <h2>02. Tambahkan Data dari CSV</h2>
                <p>
                    Menambahkan baris baru tanpa menghapus atau menimpa data yang sudah tersimpan.
                </p>
            </div>

            <div class="etl-panel">

                @if ($errors->getBag('append')->any())
                    <div class="etl-alert error">
                        @foreach ($errors->getBag('append')->all() as $message)
                            <div>{{ $message }}</div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('etl.append') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="filter-group" style="max-width: 500px;">
                        <label for="csv_append">Pilih File CSV</label>
                        <input type="file" id="csv_append" name="csv" accept=".csv,.txt" class="etl-file" required>
                    </div>

                    <div class="etl-form-actions">
                        <button type="submit" class="filter-button">
                            Tambahkan Data
                        </button>
                    </div>
                </form>

                <p class="etl-note">
                    Format dan header CSV harus sesuai dengan Sample - Superstore.csv.
                    Row ID wajib diisi dan belum pernah digunakan.
                    Baris dengan Row ID yang sudah ada akan dilewati, bukan menimpa data lama.
                    Row ID terbesar saat ini:
                    <strong>
                        {{ $summary && $summary['total_rows'] > 0 ? number_format($summary['max_row_id'], 0, ',', '.') : '-' }}
                    </strong>.
                </p>
            </div>
        </section>

        {{-- 3. MANUAL INPUT --}}
        <section class="etl-section">
            <div class="etl-section-heading">
                <h2>03. Tambahkan Satu Data</h2>
                <p>
                    Memasukkan satu transaksi secara manual melalui formulir berikut.
                    Row ID akan dibuat secara otomatis.
                </p>
            </div>

            <div class="etl-panel">

                @if ($errors->getBag('manual')->any())
                    <div class="etl-alert error">
                        @foreach ($errors->getBag('manual')->all() as $message)
                            <div>{{ $message }}</div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('etl.manual') }}" method="POST">
                    @csrf

                    <div class="etl-form-grid">

                        <div class="filter-group">
                            <label for="order_id">Order ID</label>
                            <input type="text" id="order_id" name="order_id" value="{{ old('order_id') }}"
                                maxlength="20" placeholder="CA-2017-100001" required>
                        </div>

                        <div class="filter-group">
                            <label for="order_date">Order Date</label>
                            <input type="date" id="order_date" name="order_date" value="{{ old('order_date') }}"
                                required>
                        </div>

                        <div class="filter-group">
                            <label for="ship_date">Ship Date</label>
                            <input type="date" id="ship_date" name="ship_date" value="{{ old('ship_date') }}" required>
                        </div>

                        <div class="filter-group">
                            <label for="ship_mode">Ship Mode</label>
                            <select id="ship_mode" name="ship_mode" required>
                                @foreach ($shipModes as $option)
                                    <option value="{{ $option }}" @selected(old('ship_mode') === $option)>
                                        {{ $option }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-group">
                            <label for="customer_id">Customer ID</label>
                            <input type="text" id="customer_id" name="customer_id" value="{{ old('customer_id') }}"
                                maxlength="20" placeholder="CG-12520" required>
                        </div>

                        <div class="filter-group">
                            <label for="customer_name">Customer Name</label>
                            <input type="text" id="customer_name" name="customer_name"
                                value="{{ old('customer_name') }}" maxlength="100" required>
                        </div>

                        <div class="filter-group">
                            <label for="segment">Segment</label>
                            <select id="segment" name="segment" required>
                                @foreach ($segments as $option)
                                    <option value="{{ $option }}" @selected(old('segment') === $option)>
                                        {{ $option }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-group">
                            <label for="country">Country</label>
                            <input type="text" id="country" name="country"
                                value="{{ old('country', 'United States') }}" maxlength="50" required>
                        </div>

                        <div class="filter-group">
                            <label for="city">City</label>
                            <input type="text" id="city" name="city" value="{{ old('city') }}"
                                maxlength="100" required>
                        </div>

                        <div class="filter-group">
                            <label for="state">State</label>
                            <input type="text" id="state" name="state" value="{{ old('state') }}"
                                maxlength="100" required>
                        </div>

                        <div class="filter-group">
                            <label for="postal_code">Postal Code</label>
                            <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code') }}"
                                maxlength="10" required>
                        </div>

                        <div class="filter-group">
                            <label for="region">Region</label>
                            <select id="region" name="region" required>
                                @foreach ($regions as $option)
                                    <option value="{{ $option }}" @selected(old('region') === $option)>
                                        {{ $option }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-group">
                            <label for="product_id">Product ID</label>
                            <input type="text" id="product_id" name="product_id" value="{{ old('product_id') }}"
                                maxlength="30" placeholder="FUR-BO-10001798" required>
                        </div>

                        <div class="filter-group">
                            <label for="category">Category</label>
                            <select id="category" name="category" required>
                                @foreach ($categories as $option)
                                    <option value="{{ $option }}" @selected(old('category') === $option)>
                                        {{ $option }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-group">
                            <label for="sub_category">Sub-Category</label>
                            <input type="text" id="sub_category" name="sub_category"
                                value="{{ old('sub_category') }}" maxlength="50" list="sub-category-list" required>

                            <datalist id="sub-category-list">
                                @foreach ($subCategories as $option)
                                    <option value="{{ $option }}"></option>
                                @endforeach
                            </datalist>
                        </div>

                        <div class="filter-group">
                            <label for="product_name">Product Name</label>
                            <input type="text" id="product_name" name="product_name"
                                value="{{ old('product_name') }}" maxlength="255" required>
                        </div>

                        <div class="filter-group">
                            <label for="sales">Sales ($)</label>
                            <input type="number" id="sales" name="sales" value="{{ old('sales') }}"
                                min="0" step="0.0001" required>
                        </div>

                        <div class="filter-group">
                            <label for="quantity">Quantity</label>
                            <input type="number" id="quantity" name="quantity" value="{{ old('quantity', 1) }}"
                                min="1" step="1" required>
                        </div>

                        <div class="filter-group">
                            <label for="discount">Discount (0–1)</label>
                            <input type="number" id="discount" name="discount" value="{{ old('discount', '0') }}"
                                min="0" max="1" step="0.01" required>
                        </div>

                        <div class="filter-group">
                            <label for="profit">Profit ($)</label>
                            <input type="number" id="profit" name="profit" value="{{ old('profit') }}"
                                step="0.0001" required>
                        </div>

                    </div>

                    <div class="etl-form-actions">
                        <button type="submit" class="filter-button">
                            Simpan Data
                        </button>
                    </div>
                </form>

                <p class="etl-note">
                    Discount menggunakan nilai pecahan: 0.20 berarti 20%.
                    Profit dapat bernilai negatif. Gunakan titik sebagai pemisah desimal.
                </p>
            </div>
        </section>

        {{-- CURRENT TABLE CONDITION --}}
        <section class="etl-section">

            <div class="etl-section-heading etl-section-heading-top">
                <div>
                    <h2>Kondisi Tabel Saat Ini</h2>
                    <p>
                        Ringkasan kondisi data yang tersimpan pada tabel
                        <strong>sample_superstore</strong>.
                        Pembanding Superstore standar hanya digunakan untuk memeriksa
                        kesesuaian dataset asli.
                    </p>
                </div>

                @if ($summary && $summary['total_rows'] > 0)
                    <a href="{{ route('etl.export') }}" class="etl-download-button">
                        <span aria-hidden="true">↓</span>
                        Download Data CSV
                    </a>
                @endif
            </div>

            @if ($summary && $summary['total_rows'] > 0)

                <div class="etl-result-grid">
                    <div class="etl-result-card">
                        <p>Total Baris</p>
                        <h3>{{ number_format($summary['total_rows'], 0, ',', '.') }}</h3>
                    </div>

                    <div class="etl-result-card">
                        <p>Total Pesanan</p>
                        <h3>{{ number_format($summary['total_orders'], 0, ',', '.') }}</h3>
                    </div>

                    <div class="etl-result-card">
                        <p>Total Sales</p>
                        <h3>${{ number_format($summary['total_sales'], 2, ',', '.') }}</h3>
                    </div>

                    <div class="etl-result-card">
                        <p>Total Profit</p>
                        <h3>${{ number_format($summary['total_profit'], 2, ',', '.') }}</h3>
                    </div>
                </div>

                @php
                    $checks = [
                        [
                            'Jumlah baris',
                            number_format($summary['total_rows'], 0, ',', '.'),
                            '9.994',
                            $summary['total_rows'] === 9994,
                        ],
                        [
                            'Jumlah pesanan',
                            number_format($summary['total_orders'], 0, ',', '.'),
                            '5.009',
                            $summary['total_orders'] === 5009,
                        ],
                        [
                            'Total Sales',
                            '$' . number_format($summary['total_sales'], 2, ',', '.'),
                            '$2.297.200,86',
                            abs($summary['total_sales'] - 2297200.86) < 0.05,
                        ],
                        [
                            'Total Profit',
                            '$' . number_format($summary['total_profit'], 2, ',', '.'),
                            '$286.397,02',
                            abs($summary['total_profit'] - 286397.02) < 0.05,
                        ],
                        [
                            'Profit Margin',
                            number_format($summary['margin'], 2, ',', '.') . '%',
                            '12,47%',
                            abs($summary['margin'] - 12.47) < 0.01,
                        ],
                    ];
                @endphp

                <div class="etl-panel">
                    <div class="etl-table-wrap">
                        <table class="etl-table">
                            <thead>
                                <tr>
                                    <th>Pemeriksaan</th>
                                    <th>Tabel Saat Ini</th>
                                    <th>Superstore Standar</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($checks as [$label, $current, $expected, $ok])
                                    <tr>
                                        <td><strong>{{ $label }}</strong></td>
                                        <td>{{ $current }}</td>
                                        <td>{{ $expected }}</td>
                                        <td class="{{ $ok ? 'etl-status-ok' : 'etl-status-different' }}">
                                            {{ $ok ? 'Sesuai' : 'Berbeda' }}
                                        </td>
                                    </tr>
                                @endforeach

                                <tr>
                                    <td><strong>Periode Pesanan</strong></td>
                                    <td colspan="3">
                                        {{ $summary['first_date'] }} s.d. {{ $summary['last_date'] }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="etl-panel etl-empty">
                    Tabel masih kosong. Unggah file CSV untuk mulai mengisi data.
                </div>
            @endif
        </section>

        <div class="etl-note" style="text-align: center; margin: 35px 0 10px;">
            Superstore Executive Information System
        </div>

    </div>

@endsection
*/
