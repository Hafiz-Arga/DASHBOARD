@extends('layouts.app')

@section('title', 'Input Manual | Superstore EIS')

@section('content')
    <style>
        .etl-page {
            max-width: 1150px;
            margin: auto;
            padding-bottom: 30px;
        }

        /* PAGE BACKGROUND */
        .etl-page {
            color: #253247;
        }

        .etl-top-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 27px;
        }

        .etl-top-actions .etl-button {
            min-height: 40px;
            padding: 9px 15px;
            font-size: 13px;
        }

        .etl-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #52647A;
            text-decoration: none;
            font-size: 14px;
            font-weight: 700;
            transition: color .2s ease;
        }

        .etl-back:hover {
            color: #285EA8;
        }

        .etl-view-data {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 40px;
            padding: 9px 15px;
            border: 1px solid #3976C5;
            border-radius: 7px;
            background: #FFFFFF;
            color: #285EA8;
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            transition: all .2s ease;
        }

        .etl-view-data:hover {
            background: #285EA8;
            color: #FFFFFF;
        }

        /* HEADER */
        .etl-header {
            margin-bottom: 25px;
        }

        .etl-header small {
            color: #3976C5;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.3px;
        }

        .etl-header h1 {
            margin: 8px 0;
            color: #172B4D;
            font-size: 29px;
            font-weight: 800;
        }

        .etl-header p {
            margin: 0;
            color: #64748B;
            font-size: 14px;
            line-height: 1.7;
        }

        /* FORM PANEL */
        .etl-panel {
            padding: 30px;
            background: #FFFFFF;
            border: 1px solid #D8E0EA;
            border-radius: 11px;
            box-shadow: 0 5px 18px rgba(23, 43, 77, .055);
        }

        .etl-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 21px;
        }

        .etl-field {
            min-width: 0;
        }

        .etl-field label {
            display: block;
            margin-bottom: 8px;
            color: #334155;
            font-size: 13px;
            font-weight: 700;
        }

        .etl-field input,
        .etl-field select {
            width: 100%;
            min-height: 45px;
            padding: 10px 12px;
            border: 1px solid #CBD5E1;
            border-radius: 7px;
            background: #FFFFFF;
            color: #253247;
            font: inherit;
            font-size: 14px;
            transition: border-color .2s ease, box-shadow .2s ease;
        }

        .etl-field input::placeholder {
            color: #94A3B8;
        }

        .etl-field input:hover,
        .etl-field select:hover {
            border-color: #94A3B8;
        }

        .etl-field input:focus,
        .etl-field select:focus {
            outline: none;
            border-color: #3976C5;
            box-shadow: 0 0 0 3px rgba(57, 118, 197, .12);
        }

        .etl-field small {
            display: block;
            margin-top: 7px;
            color: #64748B;
            font-size: 12px;
            line-height: 1.6;
        }

        /* SECTION */
        .etl-divider {
            margin: 29px 0;
            border: 0;
            border-top: 1px solid #E2E8F0;
        }

        .etl-section-title {
            margin: 0 0 19px;
            padding-left: 12px;
            border-left: 4px solid #3976C5;
            color: #172B4D;
            font-size: 16px;
            font-weight: 800;
        }

        /* NOTE */
        .etl-note {
            margin: 22px 0 0;
            padding: 13px 15px;
            border: 1px solid #D8E0EA;
            border-radius: 7px;
            background: #F4F6F9;
            color: #52647A;
            font-size: 12px;
            line-height: 1.7;
        }

        /* ALERTS */
        .etl-alert {
            margin-bottom: 20px;
            padding: 15px 18px;
            border: 1px solid #F0C5C5;
            border-left: 4px solid #C83D3D;
            border-radius: 7px;
            background: #FFF5F5;
            color: #A52A2A;
            font-size: 13px;
            line-height: 1.8;
        }

        .etl-alert strong {
            display: block;
            margin-bottom: 4px;
        }

        .etl-success {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 20px;
            padding: 15px 18px;
            border: 1px solid #B9DDC5;
            border-left: 4px solid #16834A;
            border-radius: 7px;
            background: #F1F8F3;
            color: #176B3D;
            font-size: 13px;
            line-height: 1.7;
        }

        .etl-success-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #DCEFE2;
            color: #16834A;
            font-weight: 800;
        }

        /* BUTTONS */
        .etl-actions {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 12px;
            margin-top: 25px;
            padding-top: 22px;
            border-top: 1px solid #E2E8F0;
        }

        .etl-button {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            min-height: 45px;
            padding: 11px 20px;
            border: 1px solid transparent;
            border-radius: 7px;
            font: inherit;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: all .2s ease;
        }

        .etl-button-primary {
            background: #3976C5;
            color: #FFFFFF;
        }

        .etl-button-primary:hover {
            background: #285EA8;
            box-shadow: 0 3px 9px rgba(40, 94, 168, .2);
        }

        .etl-button-secondary {
            border-color: #3976C5;
            background: #FFFFFF;
            color: #285EA8;
        }

        .etl-button-secondary:hover {
            background: #285EA8;
            color: #FFFFFF;
        }

        @media(max-width: 850px) {
            .etl-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media(max-width: 580px) {
            .etl-panel {
                padding: 19px;
            }

            .etl-grid {
                grid-template-columns: 1fr;
            }

            .etl-top-actions {
                align-items: flex-start;
            }

            .etl-header h1 {
                font-size: 25px;
            }

            .etl-actions {
                flex-direction: column-reverse;
            }

            .etl-actions .etl-button {
                width: 100%;
            }
        }
    </style>

    <div class="etl-page">

        <div class="etl-top-actions">
            <a href="{{ route('etl') }}" class="etl-button etl-button-secondary">
                Kembali ke ETL
            </a>

            <a href="{{ route('etl.condition') }}" class="etl-view-data">
                Lihat Kondisi Data
            </a>
        </div>

        <header class="etl-header">
            <small>MANUAL DATA ENTRY</small>
            <h1>Input Data Manual</h1>
            <p>
                Tambahkan transaksi Superstore secara langsung melalui formulir berikut.
                Anda dapat menyimpan beberapa transaksi tanpa meninggalkan halaman ini.
            </p>
        </header>

        @if (session('etl_saved'))
            <div class="etl-success">
                <div class="etl-success-icon">✓</div>
                <div>
                    <strong>Transaksi berhasil ditambahkan!</strong>
                    <div>
                        Data berhasil disimpan dengan Row ID
                        <strong>{{ session('etl_saved') }}</strong>.
                        Formulir siap digunakan untuk transaksi berikutnya.
                    </div>
                </div>
            </div>
        @endif

        @if ($errors->getBag('manual')->any())
            <div class="etl-alert">
                <strong>Data belum berhasil disimpan. Periksa kembali isian berikut:</strong>
                @foreach ($errors->getBag('manual')->all() as $message)
                    <div>• {{ $message }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('etl.manual') }}" method="POST" class="etl-panel">
            @csrf

            <h2 class="etl-section-title">Informasi Pesanan</h2>

            <div class="etl-grid">
                <div class="etl-field">
                    <label for="order_id">Order ID</label>
                    <input type="text" id="order_id" name="order_id" value="{{ old('order_id') }}" maxlength="20"
                        placeholder="CA-2017-100001" required>
                </div>

                <div class="etl-field">
                    <label for="order_date">Order Date</label>
                    <input type="date" id="order_date" name="order_date" value="{{ old('order_date') }}" required>
                </div>

                <div class="etl-field">
                    <label for="ship_date">Ship Date</label>
                    <input type="date" id="ship_date" name="ship_date" value="{{ old('ship_date') }}" required>
                </div>

                <div class="etl-field">
                    <label for="ship_mode">Ship Mode</label>
                    <select id="ship_mode" name="ship_mode" required>
                        <option value="">Pilih Ship Mode</option>
                        @foreach ($shipModes as $option)
                            <option value="{{ $option }}" @selected(old('ship_mode') === $option)>
                                {{ $option }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="etl-field">
                    <label for="segment">Segment</label>
                    <select id="segment" name="segment" required>
                        <option value="">Pilih Segment</option>
                        @foreach ($segments as $option)
                            <option value="{{ $option }}" @selected(old('segment') === $option)>
                                {{ $option }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="etl-field">
                    <label for="region">Region</label>
                    <select id="region" name="region" required>
                        <option value="">Pilih Region</option>
                        @foreach ($regions as $option)
                            <option value="{{ $option }}" @selected(old('region') === $option)>
                                {{ $option }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <hr class="etl-divider">

            <h2 class="etl-section-title">Informasi Pelanggan dan Lokasi</h2>

            <div class="etl-grid">
                <div class="etl-field">
                    <label for="customer_id">Customer ID</label>
                    <input type="text" id="customer_id" name="customer_id" value="{{ old('customer_id') }}"
                        maxlength="20" placeholder="CG-12520" required>
                </div>

                <div class="etl-field">
                    <label for="customer_name">Customer Name</label>
                    <input type="text" id="customer_name" name="customer_name" value="{{ old('customer_name') }}"
                        maxlength="100" required>
                </div>

                <div class="etl-field">
                    <label for="country">Country</label>
                    <input type="text" id="country" name="country" value="{{ old('country', 'United States') }}"
                        maxlength="50" required>
                </div>

                <div class="etl-field">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" value="{{ old('city') }}" maxlength="100"
                        required>
                </div>

                <div class="etl-field">
                    <label for="state">State</label>
                    <input type="text" id="state" name="state" value="{{ old('state') }}" maxlength="100"
                        required>
                </div>

                <div class="etl-field">
                    <label for="postal_code">Postal Code</label>
                    <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code') }}"
                        maxlength="10" required>
                </div>
            </div>

            <hr class="etl-divider">

            <h2 class="etl-section-title">Informasi Produk</h2>

            <div class="etl-grid">
                <div class="etl-field">
                    <label for="product_id">Product ID</label>
                    <input type="text" id="product_id" name="product_id" value="{{ old('product_id') }}"
                        maxlength="30" placeholder="FUR-BO-10001798" required>
                </div>

                <div class="etl-field">
                    <label for="category">Category</label>
                    <select id="category" name="category" required>
                        <option value="">Pilih Category</option>
                        @foreach ($categories as $option)
                            <option value="{{ $option }}" @selected(old('category') === $option)>
                                {{ $option }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="etl-field">
                    <label for="sub_category">Sub-Category</label>
                    <input type="text" id="sub_category" name="sub_category" value="{{ old('sub_category') }}"
                        maxlength="50" list="sub-category-list" required>

                    <datalist id="sub-category-list">
                        @foreach ($subCategories as $option)
                            <option value="{{ $option }}"></option>
                        @endforeach
                    </datalist>
                </div>

                <div class="etl-field" style="grid-column: 1 / -1;">
                    <label for="product_name">Product Name</label>
                    <input type="text" id="product_name" name="product_name" value="{{ old('product_name') }}"
                        maxlength="255" required>
                </div>
            </div>

            <hr class="etl-divider">

            <h2 class="etl-section-title">Informasi Penjualan</h2>

            <div class="etl-grid">
                <div class="etl-field">
                    <label for="sales">Sales ($)</label>
                    <input type="number" id="sales" name="sales" value="{{ old('sales') }}" min="0"
                        step="0.0001" required>
                </div>

                <div class="etl-field">
                    <label for="quantity">Quantity</label>
                    <input type="number" id="quantity" name="quantity" value="{{ old('quantity', 1) }}"
                        min="1" step="1" required>
                </div>

                <div class="etl-field">
                    <label for="discount">Discount (%)</label>
                    <input type="number" id="discount" name="discount" value="{{ old('discount', '0') }}"
                        min="0" max="100" step="1" placeholder="Contoh: 20" required>

                    <small>
                        Masukkan bilangan bulat antara 0–100.
                        Contoh: masukkan 20 untuk diskon sebesar 20%.
                    </small>
                </div>

                <div class="etl-field">
                    <label for="profit">Profit ($)</label>
                    <input type="number" id="profit" name="profit" value="{{ old('profit') }}" step="0.0001"
                        required>
                </div>
            </div>

            <p class="etl-note">
                Gunakan titik (.) sebagai pemisah desimal untuk Sales dan Profit.
                Profit dapat bernilai negatif. Row ID akan dibuat otomatis oleh sistem.
            </p>

            <div class="etl-actions">
                <button type="submit" class="etl-button etl-button-primary">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
@endsection
