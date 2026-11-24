@extends('layouts.app')

@section('title', 'Import CSV | Superstore EIS')

@section('content')
    <style>
        .etl-page {
            max-width: 1100px;
            margin: auto;
        }

        .etl-top-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
        }

        .etl-top-actions .etl-button {
            width: auto;
            min-height: 40px;
            padding: 9px 15px;
            font-size: 13px;
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

        .etl-header {
            margin-bottom: 27px;
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

        .etl-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .etl-card {
            padding: 25px;
            background: white;
            border: 1px solid #DCE3ED;
            border-radius: 12px;
        }

        .etl-card.replace {
            border-top: 4px solid #D64545;
        }

        .etl-card.append {
            border-top: 4px solid #3976C5;
        }

        .etl-card h2 {
            margin: 0 0 10px;
            color: #172B4D;
            font-size: 20px;
            font-weight: 800;
        }

        .etl-card p {
            color: #64748B;
            font-size: 14px;
            line-height: 1.7;
        }

        .etl-file {
            width: 100%;
            margin: 16px 0;
            padding: 12px;
            border: 1px solid #CBD5E1;
            border-radius: 8px;
            font: inherit;
            font-size: 14px;
        }

        .etl-check {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            margin: 18px 0;
            color: #475569;
            font-size: 13px;
            line-height: 1.6;
        }

        .etl-check input {
            margin-top: 4px;
            accent-color: #3976C5;
        }

        .etl-button {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            padding: 13px 18px;
            border: 0;
            border-radius: 8px;
            background: #3976C5;
            color: white;
            font: inherit;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
        }

        .etl-button:hover {
            background: #285EA8;
        }

        .etl-button.danger {
            background: #B93838;
        }

        .etl-button.danger:hover {
            background: #922D2D;
        }

        /* SECONDARY BUTTON */
        .etl-button-secondary {
            border: 1px solid #3976C5;
            background: #FFFFFF;
            color: #285EA8;
        }

        .etl-button-secondary:hover {
            background: #285EA8;
            color: #FFFFFF;
        }

        .etl-alert {
            padding: 15px 18px;
            margin-bottom: 18px;
            border-radius: 8px;
            font-size: 14px;
            line-height: 1.7;
        }

        .etl-alert.error {
            background: #FDEEEE;
            color: #B42318;
        }

        .etl-alert.success {
            background: #EAF7EF;
            color: #16834A;
        }

        .etl-result {
            margin-top: 20px;
            padding: 20px;
            background: #F6F8FC;
            border: 1px solid #DCE3ED;
            border-radius: 10px;
        }

        .etl-result h3 {
            margin: 0 0 12px;
            color: #172B4D;
            font-size: 17px;
        }

        .etl-result-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
        }

        .etl-result-item {
            padding: 13px;
            background: white;
            border-radius: 8px;
        }

        .etl-result-item span {
            display: block;
            color: #64748B;
            font-size: 12px;
            margin-bottom: 6px;
        }

        .etl-result-item strong {
            color: #172B4D;
            font-size: 20px;
        }

        .etl-note {
            margin-top: 15px;
            color: #64748B;
            font-size: 13px;
            line-height: 1.7;
        }

        @media(max-width: 750px) {
            .etl-grid {
                grid-template-columns: 1fr;
            }

            .etl-result-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media(max-width: 580px) {
            .etl-top-actions {
                align-items: flex-start;
            }

            .etl-top-actions .etl-button,
            .etl-view-data {
                padding: 9px 11px;
                font-size: 12px;
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
            <small>DATA IMPORT</small>
            <h1>Import Data CSV</h1>
            <p>Pilih metode import sesuai kebutuhan pengelolaan dataset Superstore.</p>
        </header>

        @if ($errors->getBag('replace')->any())
            <div class="etl-alert error">
                <strong>Import Replace gagal:</strong>
                @foreach ($errors->getBag('replace')->all() as $message)
                    <div>{{ $message }}</div>
                @endforeach
            </div>
        @endif

        @if ($errors->getBag('append')->any())
            <div class="etl-alert error">
                <strong>Import Append gagal:</strong>
                @foreach ($errors->getBag('append')->all() as $message)
                    <div>{{ $message }}</div>
                @endforeach
            </div>
        @endif

        @if ($result)
            <div class="etl-alert success">
                <strong>Import berhasil diproses.</strong>
                {{ number_format(($result['loaded'] ?? 0), 0, ',', '.') }} baris data berhasil dimuat.
            </div>

            <div class="etl-result">
                <h3>Hasil Proses Import</h3>
                <div class="etl-result-grid">
                    <div class="etl-result-item">
                        <span>Baris Dibaca</span>
                        <strong>{{ number_format(($result['read'] ?? ($result['loaded'] ?? 0)), 0, ',', '.') }}</strong>
                    </div>
                    <div class="etl-result-item">
                        <span>Duplikat CSV</span>
                        <strong>{{ number_format(($result['duplicates'] ?? 0), 0, ',', '.') }}</strong>
                    </div>
                    <div class="etl-result-item">
                        <span>Baris Tidak Valid</span>
                        <strong>{{ number_format(($result['invalid'] ?? 0), 0, ',', '.') }}</strong>
                    </div>
                    <div class="etl-result-item">
                        <span>Baris Dimuat</span>
                        <strong>{{ number_format(($result['loaded'] ?? 0), 0, ',', '.') }}</strong>
                    </div>
                </div>

                @if (!empty($result['errors']))
                    <p class="etl-note"><strong>Catatan hasil:</strong></p>
                    <ul class="etl-note">
                        @foreach ($result['errors'] as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endif

        <div class="etl-grid" style="margin-top: 22px;">
            <section class="etl-card replace">
                <h2>01. Replace Data</h2>
                <p>
                    Menghapus seluruh data yang tersimpan saat ini, kemudian menggantinya
                    dengan isi file CSV yang dipilih.
                </p>

                <form action="{{ route('etl.replace') }}" method="POST" enctype="multipart/form-data"
                    onsubmit="return confirm('Seluruh data lama akan dihapus dan diganti. Apakah kamu yakin?');">
                    @csrf

                    <label for="csv_replace"><strong>Pilih file CSV</strong></label>
                    <input class="etl-file" type="file" id="csv_replace" name="csv" accept=".csv,.txt" required>

                    <label class="etl-check">
                        <input type="checkbox" name="confirm" value="1" required>
                        <span>Saya memahami bahwa seluruh data lama akan dihapus dan diganti dengan data dari file
                            ini.</span>
                    </label>

                    <button type="submit" class="etl-button danger">Ganti Seluruh Data</button>
                </form>

                <p class="etl-note">
                    Gunakan pilihan ini jika ingin memulai ulang dataset menggunakan file CSV baru.
                    Proses menggunakan transaksi agar data lama tetap aman jika import gagal.
                </p>
            </section>

            <section class="etl-card append">
                <h2>02. Append Data</h2>
                <p>
                    Menambahkan baris baru dari file CSV tanpa menghapus data yang sudah tersimpan.
                </p>

                <form action="{{ route('etl.append') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <label for="csv_append"><strong>Pilih file CSV</strong></label>
                    <input class="etl-file" type="file" id="csv_append" name="csv" accept=".csv,.txt" required>

                    <button type="submit" class="etl-button">Tambahkan Data</button>
                </form>

                <p class="etl-note">
                    Row ID wajib diisi dan tidak boleh bertabrakan dengan Row ID yang sudah ada.
                    Data lama tidak akan ditimpa.
                </p>
            </section>
        </div>

        <p class="etl-note">
            Format file harus sesuai dengan header dataset Sample - Superstore.csv.
            Ukuran maksimal file adalah 20 MB.
        </p>
    </div>
@endsection
