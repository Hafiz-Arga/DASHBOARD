<?php

namespace App\Services;

use App\Models\EtlImportHistory;
use DateTimeImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RuntimeException;
use Throwable;

class SuperstoreEtl
{
    public const TABLE = 'sample_superstore';

    public const HEADERS = [
        'Row ID', 'Order ID', 'Order Date', 'Ship Date', 'Ship Mode',
        'Customer ID', 'Customer Name', 'Segment', 'Country', 'City',
        'State', 'Postal Code', 'Region', 'Product ID', 'Category',
        'Sub-Category', 'Product Name', 'Sales', 'Quantity', 'Discount',
        'Profit',
    ];

    private const REQUIRED_TEXT = [
        'Order ID', 'Ship Mode', 'Customer ID', 'Customer Name', 'Segment',
        'Country', 'City', 'State', 'Region', 'Product ID', 'Category',
        'Sub-Category', 'Product Name',
    ];

    private const CHUNK_SIZE = 500;
    private const MAX_ERROR_MESSAGES = 50;

    public const SHIP_MODES = [
        'Standard Class', 'Second Class', 'First Class', 'Same Day',
    ];

    public const SEGMENTS = [
        'Consumer', 'Corporate', 'Home Office',
    ];

    public const REGIONS = [
        'Central', 'East', 'South', 'West',
    ];

    public const CATEGORIES = [
        'Furniture', 'Office Supplies', 'Technology',
    ];

    private const MAX_LENGTH = [
        'Order ID' => 20,
        'Ship Mode' => 30,
        'Customer ID' => 20,
        'Customer Name' => 100,
        'Segment' => 30,
        'Country' => 50,
        'City' => 100,
        'State' => 100,
        'Postal Code' => 10,
        'Region' => 20,
        'Product ID' => 30,
        'Category' => 50,
        'Sub-Category' => 50,
        'Product Name' => 255,
    ];

    /**
     * TAHAP EXTRACT & TRANSFORM + VALIDATE (untuk Staging & Review).
     */
    public function extractTransformAndValidate(string $path, string $method = 'replace'): array
    {
        $this->assertTable();

        $handle = @fopen($path, 'rb');
        if ($handle === false) {
            return [
                'success' => false,
                'stage' => 'extract',
                'title' => 'Extract Gagal!',
                'message' => 'File CSV tidak dapat dibaca oleh sistem.',
                'details' => ['Pastikan file tidak rusak, tidak terkunci, dan memiliki izin baca yang valid.'],
            ];
        }

        $detectedColumns = 0;
        $totalRead = 0;

        try {
            $bom = fread($handle, 3);
            if ($bom !== "\xEF\xBB\xBF") {
                rewind($handle);
            }

            $header = fgetcsv($handle, 0, ',', '"', '');
            if ($header === false || $header === [null]) {
                return [
                    'success' => false,
                    'stage' => 'extract',
                    'title' => 'Extract Gagal!',
                    'message' => 'File CSV kosong atau tidak memiliki baris header.',
                    'details' => ['Unggah file CSV yang berisi header 21 kolom dan minimal 1 baris data transaksi.'],
                ];
            }

            $cleanHeader = array_map(function ($value) {
                $value = $this->toUtf8((string) $value);
                $value = preg_replace('/^\xEF\xBB\xBF/', '', $value);
                return trim($value);
            }, $header);

            $detectedColumns = count($cleanHeader);

            if ($cleanHeader !== self::HEADERS) {
                $missing = array_diff(self::HEADERS, $cleanHeader);
                $extra = array_diff($cleanHeader, self::HEADERS);
                $details = [
                    "Jumlah kolom terdeteksi: {$detectedColumns} kolom (wajib 21 kolom sesuai standar Superstore).",
                ];
                if (!empty($missing)) {
                    $details[] = 'Kolom wajib yang hilang / tidak sesuai: ' . implode(', ', $missing);
                }
                if (!empty($extra)) {
                    $details[] = 'Kolom tidak dikenal ditemukan: ' . implode(', ', array_slice($extra, 0, 8));
                }
                if (empty($missing) && empty($extra)) {
                    $details[] = 'Urutan kolom tidak sesuai dengan struktur standar Superstore.';
                }

                return [
                    'success' => false,
                    'stage' => 'extract',
                    'title' => 'Extract Gagal!',
                    'message' => 'Struktur kolom CSV tidak sesuai dengan skema Superstore EIS.',
                    'details' => $details,
                    'extract' => [
                        'total_rows' => 0,
                        'total_columns' => $detectedColumns,
                    ],
                ];
            }

            // Extract berhasil, lanjut Transform & Validate otomatis
            $validRows = [];
            $problemRows = []; // Detail per error untuk tabel Review
            $invalidRowNumbers = [];
            $seenRowIds = [];
            $duplicatesCount = 0;
            $missingValuesCount = 0;
            $typeErrorCount = 0;
            $warnings = [];
            $negativeProfitCount = 0;
            $highDiscountCount = 0;

            $recordNumber = 1;
            while (($raw = fgetcsv($handle, 0, ',', '"', '')) !== false) {
                $recordNumber++;
                if ($raw === [null] || $raw === []) {
                    continue;
                }

                $totalRead++;
                $rowErrors = [];
                $rowMissing = 0;
                $rowTypeErr = 0;

                $clean = $this->transformRowDetailed(
                    $raw,
                    $recordNumber,
                    $rowErrors,
                    $rowMissing,
                    $rowTypeErr
                );

                $missingValuesCount += $rowMissing;
                $typeErrorCount += $rowTypeErr;

                // Cek duplikasi Row ID di dalam file CSV
                $rawRowId = isset($raw[0]) ? trim((string) $raw[0]) : '';
                $orderIdVal = isset($raw[1]) && trim((string) $raw[1]) !== '' ? trim((string) $raw[1]) : '-';

                if ($rawRowId !== '' && ctype_digit($rawRowId) && (int) $rawRowId > 0) {
                    $rowIdKey = (string) ((int) $rawRowId);
                    if (isset($seenRowIds[$rowIdKey])) {
                        $duplicatesCount++;
                        $rowErrors[] = [
                            'row_number' => $recordNumber,
                            'order_id' => $orderIdVal,
                            'column' => 'Row ID',
                            'value' => $rawRowId,
                            'reason' => "Duplikat Row ID dengan baris ke-{$seenRowIds[$rowIdKey]}",
                        ];
                    } else {
                        $seenRowIds[$rowIdKey] = $recordNumber;
                    }
                }

                if (!empty($rowErrors)) {
                    $invalidRowNumbers[$recordNumber] = true;
                    foreach ($rowErrors as $err) {
                        if (count($problemRows) < self::MAX_ERROR_MESSAGES) {
                            $problemRows[] = $err;
                        }
                    }
                    continue;
                }

                if ($clean !== null) {
                    // Cek kondisi bisnis non-fatal (Warning)
                    if ((float) $clean['Profit'] < 0) {
                        $negativeProfitCount++;
                    }
                    if ((float) $clean['Discount'] >= 0.5) {
                        $highDiscountCount++;
                    }
                    $validRows[] = $clean;
                }
            }

            if ($totalRead === 0) {
                return [
                    'success' => false,
                    'stage' => 'extract',
                    'title' => 'Extract Gagal!',
                    'message' => 'File CSV hanya berisi baris header tanpa data transaksi.',
                    'details' => ['Tambahkan minimal 1 baris data transaksi sebelum melakukan import.'],
                    'extract' => [
                        'total_rows' => 0,
                        'total_columns' => $detectedColumns,
                    ],
                ];
            }

            // Jika metode Append, cek tabrakan Row ID dengan database
            if ($method === 'append' && !empty($validRows)) {
                $rowIds = array_column($validRows, 'Row ID');
                foreach (array_chunk($rowIds, self::CHUNK_SIZE) as $chunk) {
                    $existing = DB::table(self::TABLE)
                        ->whereIn('Row ID', $chunk)
                        ->pluck('Row ID')
                        ->all();

                    foreach ($existing as $existId) {
                        $line = $seenRowIds[(string) $existId] ?? 0;
                        $invalidRowNumbers[$line] = true;
                        $duplicatesCount++;
                        if (count($problemRows) < self::MAX_ERROR_MESSAGES) {
                            $problemRows[] = [
                                'row_number' => $line,
                                'order_id' => '-',
                                'column' => 'Row ID',
                                'value' => (string) $existId,
                                'reason' => "Row ID {$existId} sudah terdaftar di database (konflik mode Append)",
                            ];
                        }
                    }
                }
            }

            if ($negativeProfitCount > 0) {
                $warnings[] = "Ditemukan {$negativeProfitCount} transaksi dengan nilai Profit negatif (kerugian). Data tetap valid secara struktur dan dapat diimpor.";
            }
            if ($highDiscountCount > 0) {
                $warnings[] = "Terdapat {$highDiscountCount} transaksi dengan diskon tinggi (>= 50%).";
            }

            $invalidCount = count($invalidRowNumbers);
            $validCount = max(0, $totalRead - $invalidCount);
            $canLoad = ($invalidCount === 0 && $validCount > 0);

            return [
                'success' => true,
                'stage' => 'review',
                'can_load' => $canLoad,
                'extract' => [
                    'total_rows' => $totalRead,
                    'total_columns' => $detectedColumns,
                    'structure_valid' => true,
                ],
                'transform' => [
                    'total_checked' => $totalRead,
                    'valid_count' => $validCount,
                    'invalid_count' => $invalidCount,
                    'missing_values' => $missingValuesCount,
                    'duplicates' => $duplicatesCount,
                    'type_errors' => $typeErrorCount,
                    'structure_status' => 'Sesuai (21 Kolom)',
                    'type_status' => $typeErrorCount === 0 ? 'Sesuai' : "{$typeErrorCount} Kesalahan Tipe",
                    'date_format_status' => 'Sesuai (YYYY-MM-DD)',
                    'consistency_status' => $invalidCount === 0 ? 'Konsisten' : 'Perlu Perbaikan',
                ],
                'warnings' => $warnings,
                'problem_rows' => $problemRows,
                'preview_rows' => array_slice($validRows, 0, 5),
                'clean_rows' => $canLoad ? $validRows : [],
            ];
        } finally {
            fclose($handle);
        }
    }

    /**
     * TAHAP LOAD: Menyimpan data staging yang sudah lolos validasi ke database secara transaksional.
     */
    public function loadValidatedRows(array $rows, string $method, string $fileName, ?int $userId = null): array
    {
        $startTime = microtime(true);

        if (empty($rows)) {
            throw new RuntimeException('Tidak ada baris data valid untuk dimuat ke database.');
        }

        if (!in_array($method, ['replace', 'append'], true)) {
            throw new RuntimeException('Metode import tidak dikenali.');
        }

        try {
            $this->assertTable();

            DB::transaction(function () use ($rows, $method) {
                if ($method === 'replace') {
                    DB::table(self::TABLE)->delete();
                } else {
                    $rowIds = array_column($rows, 'Row ID');
                    foreach (array_chunk($rowIds, self::CHUNK_SIZE) as $chunk) {
                        $exists = DB::table(self::TABLE)
                            ->whereIn('Row ID', $chunk)
                            ->lockForUpdate()
                            ->exists();
                        if ($exists) {
                            throw new RuntimeException('Terdeteksi konflik Row ID di database saat proses penyimpanan Append berlangsung.');
                        }
                    }
                }

                $this->insertChunks($rows);
            });

            $duration = round(max(0.01, microtime(true) - $startTime), 2);
            $loadedCount = count($rows);

            $this->recordHistory([
                'file_name' => $fileName,
                'import_method' => $method,
                'total_rows' => $loadedCount,
                'valid_rows' => $loadedCount,
                'invalid_rows' => 0,
                'loaded_rows' => $loadedCount,
                'status' => 'success',
                'failed_stage' => null,
                'error_message' => null,
                'duration_seconds' => $duration,
                'user_id' => $userId,
            ]);

            return [
                'loaded' => $loadedCount,
                'method' => $method,
                'duration' => $duration,
                'imported_at' => now()->format('d M Y, H:i:s'),
                'summary' => $this->summary(),
            ];
        } catch (Throwable $e) {
            $duration = round(max(0.01, microtime(true) - $startTime), 2);

            $this->recordHistory([
                'file_name' => $fileName,
                'import_method' => $method,
                'total_rows' => count($rows),
                'valid_rows' => count($rows),
                'invalid_rows' => 0,
                'loaded_rows' => 0,
                'status' => 'failed',
                'failed_stage' => 'load',
                'error_message' => mb_substr($e->getMessage(), 0, 2000),
                'duration_seconds' => $duration,
                'user_id' => $userId,
            ]);

            throw $e;
        }
    }

    /**
     * Mencatat riwayat ETL ke database (aman jika tabel belum dimigrasi).
     */
    public function recordHistory(array $data): void
    {
        if (!Schema::hasTable('etl_import_histories')) {
            return;
        }

        try {
            EtlImportHistory::create($data);
        } catch (Throwable $e) {
            // Jangan gagalkan proses utama hanya karena pencatatan log
        }
    }

    /**
     * MODE 1 (Legacy direct replace).
     */
    public function replace(string $path): array
    {
        $res = $this->extractTransformAndValidate($path, 'replace');
        if (!$res['success'] || !$res['can_load']) {
            $details = !empty($res['problem_rows'])
                ? array_map(fn ($p) => "Baris {$p['row_number']} ({$p['column']}): {$p['reason']}", array_slice($res['problem_rows'], 0, 10))
                : ($res['details'] ?? []);
            $msg = ($res['message'] ?? 'Validasi gagal.') . (!empty($details) ? ' Detail: ' . implode(' | ', $details) : '');
            throw new RuntimeException($msg);
        }
        $load = $this->loadValidatedRows($res['clean_rows'], 'replace', basename($path));

        return array_merge($load, [
            'read' => $res['transform']['total_checked'] ?? $load['loaded'],
            'duplicates' => $res['transform']['duplicates'] ?? 0,
            'invalid' => $res['transform']['invalid_count'] ?? 0,
            'errors' => $res['warnings'] ?? [],
        ]);
    }

    /**
     * MODE 2 (Legacy direct append).
     */
    public function append(string $path): array
    {
        $res = $this->extractTransformAndValidate($path, 'append');
        if (!$res['success'] || !$res['can_load']) {
            $details = !empty($res['problem_rows'])
                ? array_map(fn ($p) => "Baris {$p['row_number']} ({$p['column']}): {$p['reason']}", array_slice($res['problem_rows'], 0, 10))
                : ($res['details'] ?? []);
            $msg = ($res['message'] ?? 'Validasi gagal.') . (!empty($details) ? ' Detail: ' . implode(' | ', $details) : '');
            throw new RuntimeException($msg);
        }
        $load = $this->loadValidatedRows($res['clean_rows'], 'append', basename($path));

        return array_merge($load, [
            'read' => $res['transform']['total_checked'] ?? $load['loaded'],
            'duplicates' => $res['transform']['duplicates'] ?? 0,
            'invalid' => $res['transform']['invalid_count'] ?? 0,
            'errors' => $res['warnings'] ?? [],
        ]);
    }

    /**
     * MODE 3: Menambahkan satu baris dari form manual.
     */
    public function addOne(array $input): int
    {
        $this->assertTable();

        $raw = ['0'];
        foreach (array_slice(self::HEADERS, 1) as $column) {
            $value = (string) ($input[$column] ?? '');
            if ($column === 'Order Date' || $column === 'Ship Date') {
                $date = DateTimeImmutable::createFromFormat('!Y-m-d', $value);
                $value = $date ? $date->format('n/j/Y') : $value;
            }
            $raw[] = $value;
        }

        $errors = [];
        $missing = 0;
        $typeErr = 0;
        $row = $this->transformRowDetailed($raw, 1, $errors, $missing, $typeErr, true);

        if ($row === null) {
            $msg = !empty($errors) ? implode(' | ', array_column($errors, 'reason')) : 'Data tidak valid.';
            throw new RuntimeException($msg);
        }

        return DB::transaction(function () use ($row) {
            $max = DB::table(self::TABLE)->lockForUpdate()->max('Row ID');
            $row['Row ID'] = ((int) $max) + 1;
            DB::table(self::TABLE)->insert($row);
            return $row['Row ID'];
        });
    }

    private function assertTable(): void
    {
        if (!Schema::hasTable(self::TABLE)) {
            throw new RuntimeException(
                'Tabel ' . self::TABLE . ' belum ada. Jalankan perintah: php artisan migrate'
            );
        }
    }

    public function summary(): ?array
    {
        if (!Schema::hasTable(self::TABLE)) {
            return null;
        }

        $row = DB::table(self::TABLE)
            ->selectRaw('COUNT(*) AS total_rows')
            ->selectRaw('COUNT(DISTINCT `Order ID`) AS total_orders')
            ->selectRaw('COALESCE(SUM(Sales), 0) AS total_sales')
            ->selectRaw('COALESCE(SUM(Profit), 0) AS total_profit')
            ->selectRaw('COALESCE(MAX(`Row ID`), 0) AS max_row_id')
            ->selectRaw('MIN(`Order Date`) AS first_date')
            ->selectRaw('MAX(`Order Date`) AS last_date')
            ->first();

        $sales = (float) $row->total_sales;
        $profit = (float) $row->total_profit;

        return [
            'total_rows' => (int) $row->total_rows,
            'total_orders' => (int) $row->total_orders,
            'total_sales' => $sales,
            'total_profit' => $profit,
            'margin' => $sales != 0.0 ? ($profit / $sales) * 100 : 0.0,
            'max_row_id' => (int) $row->max_row_id,
            'first_date' => $row->first_date,
            'last_date' => $row->last_date,
        ];
    }

    private function transformRowDetailed(
        array $raw,
        int $recordNumber,
        array &$errors,
        int &$missingCount,
        int &$typeErrorCount,
        bool $skipRowId = false
    ): ?array {
        $errors = [];
        $missingCount = 0;
        $typeErrorCount = 0;

        $orderIdHint = isset($raw[1]) && trim((string) $raw[1]) !== '' ? trim((string) $raw[1]) : "Baris #{$recordNumber}";

        if (count($raw) !== count(self::HEADERS)) {
            $typeErrorCount++;
            $errors[] = [
                'row_number' => $recordNumber,
                'order_id' => $orderIdHint,
                'column' => 'Struktur Baris',
                'value' => count($raw) . ' kolom',
                'reason' => 'Jumlah kolom ' . count($raw) . ', seharusnya ' . count(self::HEADERS),
            ];
            return null;
        }

        $values = [];
        foreach ($raw as $value) {
            $value = $this->toUtf8((string) $value);
            $value = str_replace("\u{00A0}", ' ', $value);
            $values[] = trim($value);
        }

        $row = array_combine(self::HEADERS, $values);

        if (!$skipRowId) {
            if ($row['Row ID'] === '') {
                $missingCount++;
                $errors[] = [
                    'row_number' => $recordNumber,
                    'order_id' => $orderIdHint,
                    'column' => 'Row ID',
                    'value' => '(kosong)',
                    'reason' => 'Nilai kosong pada kolom wajib Row ID',
                ];
            } elseif (!ctype_digit($row['Row ID']) || (int) $row['Row ID'] < 1) {
                $typeErrorCount++;
                $errors[] = [
                    'row_number' => $recordNumber,
                    'order_id' => $orderIdHint,
                    'column' => 'Row ID',
                    'value' => $row['Row ID'],
                    'reason' => 'Row ID harus berupa bilangan bulat positif',
                ];
            }
        }

        foreach (self::REQUIRED_TEXT as $column) {
            if ($row[$column] === '') {
                $missingCount++;
                $errors[] = [
                    'row_number' => $recordNumber,
                    'order_id' => $orderIdHint,
                    'column' => $column,
                    'value' => '(kosong)',
                    'reason' => "Nilai kosong pada kolom wajib {$column}",
                ];
            }
        }

        foreach (self::MAX_LENGTH as $column => $max) {
            if (mb_strlen($row[$column]) > $max) {
                $typeErrorCount++;
                $errors[] = [
                    'row_number' => $recordNumber,
                    'order_id' => $orderIdHint,
                    'column' => $column,
                    'value' => mb_substr($row[$column], 0, 25) . '...',
                    'reason' => "Teks melebihi batas maksimal {$max} karakter",
                ];
            }
        }

        $allowedValues = [
            'Ship Mode' => self::SHIP_MODES,
            'Segment' => self::SEGMENTS,
            'Region' => self::REGIONS,
            'Category' => self::CATEGORIES,
        ];

        foreach ($allowedValues as $column => $allowed) {
            if ($row[$column] !== '' && !in_array($row[$column], $allowed, true)) {
                $typeErrorCount++;
                $errors[] = [
                    'row_number' => $recordNumber,
                    'order_id' => $orderIdHint,
                    'column' => $column,
                    'value' => $row[$column],
                    'reason' => "Kategori {$column} tidak valid (harus: " . implode(', ', $allowed) . ')',
                ];
            }
        }

        $parsedDates = [];
        foreach (['Order Date', 'Ship Date'] as $column) {
            if ($row[$column] === '') {
                $missingCount++;
                $errors[] = [
                    'row_number' => $recordNumber,
                    'order_id' => $orderIdHint,
                    'column' => $column,
                    'value' => '(kosong)',
                    'reason' => "Nilai kosong pada kolom tanggal {$column}",
                ];
                continue;
            }

            $date = $this->parseDate($row[$column]);
            if ($date === null) {
                $typeErrorCount++;
                $errors[] = [
                    'row_number' => $recordNumber,
                    'order_id' => $orderIdHint,
                    'column' => $column,
                    'value' => $row[$column],
                    'reason' => "Format tanggal tidak valid pada {$column}",
                ];
            } else {
                $parsedDates[$column] = $date;
                $row[$column] = $date;
            }
        }

        if (
            isset($parsedDates['Order Date'], $parsedDates['Ship Date'])
            && $parsedDates['Ship Date'] < $parsedDates['Order Date']
        ) {
            $typeErrorCount++;
            $errors[] = [
                'row_number' => $recordNumber,
                'order_id' => $orderIdHint,
                'column' => 'Ship Date',
                'value' => $row['Ship Date'],
                'reason' => 'Ship Date tidak boleh lebih awal dari Order Date (' . $row['Order Date'] . ')',
            ];
        }

        foreach (['Sales', 'Profit'] as $column) {
            if ($row[$column] === '') {
                $missingCount++;
                $errors[] = [
                    'row_number' => $recordNumber,
                    'order_id' => $orderIdHint,
                    'column' => $column,
                    'value' => '(kosong)',
                    'reason' => "Nilai kosong pada kolom numerik {$column}",
                ];
                continue;
            }

            if (!is_numeric($row[$column])) {
                $typeErrorCount++;
                $errors[] = [
                    'row_number' => $recordNumber,
                    'order_id' => $orderIdHint,
                    'column' => $column,
                    'value' => $row[$column],
                    'reason' => "Nilai pada kolom '{$column}' tidak dapat dikonversi menjadi angka",
                ];
                continue;
            }

            if (abs((float) $row[$column]) >= 1e8) {
                $typeErrorCount++;
                $errors[] = [
                    'row_number' => $recordNumber,
                    'order_id' => $orderIdHint,
                    'column' => $column,
                    'value' => $row[$column],
                    'reason' => "{$column} berada di luar batas kapasitas penyimpanan",
                ];
            }
        }

        if ($row['Quantity'] === '') {
            $missingCount++;
            $errors[] = [
                'row_number' => $recordNumber,
                'order_id' => $orderIdHint,
                'column' => 'Quantity',
                'value' => '(kosong)',
                'reason' => 'Nilai kosong pada kolom Quantity',
            ];
        } elseif (!ctype_digit($row['Quantity']) || (int) $row['Quantity'] < 1 || (int) $row['Quantity'] > 65535) {
            $typeErrorCount++;
            $errors[] = [
                'row_number' => $recordNumber,
                'order_id' => $orderIdHint,
                'column' => 'Quantity',
                'value' => $row['Quantity'],
                'reason' => 'Quantity harus berupa bilangan bulat >= 1',
            ];
        }

        if ($row['Discount'] === '') {
            $missingCount++;
            $errors[] = [
                'row_number' => $recordNumber,
                'order_id' => $orderIdHint,
                'column' => 'Discount',
                'value' => '(kosong)',
                'reason' => 'Nilai kosong pada kolom Discount',
            ];
        } elseif (!is_numeric($row['Discount']) || (float) $row['Discount'] < 0 || (float) $row['Discount'] > 1) {
            $typeErrorCount++;
            $errors[] = [
                'row_number' => $recordNumber,
                'order_id' => $orderIdHint,
                'column' => 'Discount',
                'value' => $row['Discount'],
                'reason' => 'Discount harus berupa angka desimal antara 0 sampai 1',
            ];
        }

        if (
            $row['Postal Code'] !== ''
            && ctype_digit($row['Postal Code'])
            && strlen($row['Postal Code']) < 5
        ) {
            $row['Postal Code'] = str_pad($row['Postal Code'], 5, '0', STR_PAD_LEFT);
        }

        if (!empty($errors)) {
            return null;
        }

        $row['Row ID'] = $skipRowId ? 0 : (int) $row['Row ID'];
        $row['Quantity'] = (int) $row['Quantity'];

        return $row;
    }

    private function parseDate(string $value): ?string
    {
        $value = trim($value);
        $formats = [
            '!Y-m-d' => 'Y-m-d',
            '!n/j/Y' => 'n/j/Y',
            '!m/d/Y' => 'm/d/Y',
        ];

        foreach ($formats as $format => $expectedFormat) {
            $date = DateTimeImmutable::createFromFormat($format, $value);
            if ($date === false) {
                continue;
            }
            $errors = DateTimeImmutable::getLastErrors();
            if ($errors !== false && ($errors['warning_count'] > 0 || $errors['error_count'] > 0)) {
                continue;
            }
            if ($date->format($expectedFormat) === $value) {
                return $date->format('Y-m-d');
            }
        }

        return null;
    }

    private function toUtf8(string $value): string
    {
        if (mb_check_encoding($value, 'UTF-8')) {
            return $value;
        }
        return mb_convert_encoding($value, 'UTF-8', 'Windows-1252');
    }

    private function insertChunks(array $rows): void
    {
        foreach (array_chunk($rows, self::CHUNK_SIZE) as $chunk) {
            DB::table(self::TABLE)->insert($chunk);
        }
    }
}
