<?php

namespace App\Http\Controllers;

use App\Models\EtlImportHistory;
use App\Services\SuperstoreEtl;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class EtlController extends Controller
{
    private const MANUAL_LABELS = [
        'order_id' => 'Order ID',
        'order_date' => 'Order Date',
        'ship_date' => 'Ship Date',
        'ship_mode' => 'Ship Mode',
        'customer_id' => 'Customer ID',
        'customer_name' => 'Customer Name',
        'segment' => 'Segment',
        'country' => 'Country',
        'city' => 'City',
        'state' => 'State',
        'postal_code' => 'Postal Code',
        'region' => 'Region',
        'product_id' => 'Product ID',
        'category' => 'Category',
        'sub_category' => 'Sub-Category',
        'product_name' => 'Product Name',
        'sales' => 'Sales',
        'quantity' => 'Quantity',
        'discount' => 'Discount',
        'profit' => 'Profit',
    ];

    /**
     * Halaman utama ETL Data Management (5-Stage Pipeline + Import History).
     */
    public function index(Request $request, SuperstoreEtl $etl)
    {
        $historySearch = trim((string) $request->query('history_search', ''));
        $historyStatus = trim((string) $request->query('history_status', ''));

        if (Schema::hasTable('etl_import_histories')) {
            $historyQuery = EtlImportHistory::query()->with('user')->latest();

            if ($historySearch !== '') {
                $historyQuery->where(function ($q) use ($historySearch) {
                    $q->where('file_name', 'LIKE', '%' . $historySearch . '%')
                      ->orWhere('import_method', 'LIKE', '%' . $historySearch . '%')
                      ->orWhere('failed_stage', 'LIKE', '%' . $historySearch . '%');
                });
            }

            if (in_array($historyStatus, ['success', 'failed'], true)) {
                $historyQuery->where('status', $historyStatus);
            }

            $histories = $historyQuery->paginate(10, ['*'], 'history_page')->withQueryString();
        } else {
            $histories = new LengthAwarePaginator([], 0, 10, 1, [
                'path' => $request->url(),
                'query' => $request->query(),
            ]);
        }

        return view('etl.index', [
            'summary' => $etl->summary(),
            'histories' => $histories,
            'historySearch' => $historySearch,
            'historyStatus' => $historyStatus,
        ]);
    }

    /**
     * Tahap 1 -> 2 (Extract) -> 3 (Transform & Validate) -> 4 (Review).
     * Dipanggil via AJAX setelah admin mengunggah CSV & memilih metode.
     */
    public function process(Request $request, SuperstoreEtl $etl): JsonResponse
    {
        set_time_limit(120);

        $validated = $request->validate([
            'csv' => ['required', 'file', 'mimes:csv,txt', 'max:20480'],
            'method' => ['required', Rule::in(['replace', 'append'])],
        ], [
            'csv.required' => 'Pilih file CSV terlebih dahulu.',
            'csv.file' => 'File yang diunggah tidak valid.',
            'csv.mimes' => 'Format file harus berupa CSV (.csv).',
            'csv.max' => 'Ukuran file maksimal 20 MB.',
            'method.required' => 'Pilih metode import (Replace atau Append).',
            'method.in' => 'Metode import tidak valid.',
        ]);

        $file = $request->file('csv');
        $fileName = $file->getClientOriginalName();
        $method = $validated['method'];
        $startedAt = now()->format('d M Y, H:i:s');
        $startTime = microtime(true);

        try {
            $result = $etl->extractTransformAndValidate($file->getRealPath(), $method);
            $duration = round(max(0.01, microtime(true) - $startTime), 2);

            if (!$result['success']) {
                $etl->recordHistory([
                    'file_name' => $fileName,
                    'import_method' => $method,
                    'total_rows' => $result['extract']['total_rows'] ?? 0,
                    'valid_rows' => 0,
                    'invalid_rows' => 0,
                    'loaded_rows' => 0,
                    'status' => 'failed',
                    'failed_stage' => $result['stage'] ?? 'extract',
                    'error_message' => $result['message'] ?? 'Extract gagal.',
                    'duration_seconds' => $duration,
                    'user_id' => Auth::id(),
                ]);

                return response()->json([
                    'success' => false,
                    'stage' => $result['stage'] ?? 'extract',
                    'title' => $result['title'] ?? 'Extract Gagal!',
                    'message' => $result['message'] ?? 'Proses Extract gagal.',
                    'details' => $result['details'] ?? [],
                    'meta' => [
                        'file_name' => $fileName,
                        'method' => $method,
                        'started_at' => $startedAt,
                        'total_rows' => $result['extract']['total_rows'] ?? 0,
                        'total_columns' => $result['extract']['total_columns'] ?? 0,
                    ],
                ], 422);
            }

            // Jika ditemukan error validasi di tahap Transform (can_load === false), catat juga atau siapkan review
            $stagingToken = null;
            if ($result['can_load'] && !empty($result['clean_rows'])) {
                $this->cleanupOldStaging();
                $stagingToken = (string) Str::uuid();
                Storage::disk('local')->put(
                    "etl_staging/{$stagingToken}.json",
                    json_encode([
                        'file_name' => $fileName,
                        'method' => $method,
                        'rows' => $result['clean_rows'],
                        'created_at' => now()->toIso8601String(),
                    ])
                );
            } else {
                // Catat kegagalan validasi Transform ke history agar terlacak
                $etl->recordHistory([
                    'file_name' => $fileName,
                    'import_method' => $method,
                    'total_rows' => $result['transform']['total_checked'] ?? 0,
                    'valid_rows' => $result['transform']['valid_count'] ?? 0,
                    'invalid_rows' => $result['transform']['invalid_count'] ?? 0,
                    'loaded_rows' => 0,
                    'status' => 'failed',
                    'failed_stage' => 'transform',
                    'error_message' => 'Ditemukan ' . ($result['transform']['invalid_count'] ?? 0) . ' baris data tidak valid pada tahap Transform & Validate.',
                    'duration_seconds' => $duration,
                    'user_id' => Auth::id(),
                ]);
            }

            return response()->json([
                'success' => true,
                'stage' => 'review',
                'can_load' => $result['can_load'],
                'staging_token' => $stagingToken,
                'meta' => [
                    'file_name' => $fileName,
                    'method' => $method,
                    'started_at' => $startedAt,
                ],
                'extract' => $result['extract'],
                'transform' => $result['transform'],
                'warnings' => $result['warnings'],
                'problem_rows' => $result['problem_rows'],
                'preview_rows' => $result['preview_rows'],
            ]);
        } catch (Throwable $e) {
            Log::error('ETL process error: ' . $e->getMessage(), ['exception' => $e]);
            $duration = round(max(0.01, microtime(true) - $startTime), 2);

            $etl->recordHistory([
                'file_name' => $fileName,
                'import_method' => $method,
                'total_rows' => 0,
                'valid_rows' => 0,
                'invalid_rows' => 0,
                'loaded_rows' => 0,
                'status' => 'failed',
                'failed_stage' => 'extract',
                'error_message' => mb_substr($e->getMessage(), 0, 1000),
                'duration_seconds' => $duration,
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'success' => false,
                'stage' => 'extract',
                'title' => 'Extract Gagal!',
                'message' => 'Terjadi kesalahan saat memproses file CSV.',
                'details' => [$e->getMessage()],
                'meta' => [
                    'file_name' => $fileName,
                    'method' => $method,
                    'started_at' => $startedAt,
                ],
            ], 500);
        }
    }

    /**
     * Tahap 4 (Review) -> Tahap 5 (Load ke Database).
     */
    public function load(Request $request, SuperstoreEtl $etl): JsonResponse
    {
        set_time_limit(120);

        $validated = $request->validate([
            'staging_token' => ['required', 'uuid'],
        ]);

        $token = $validated['staging_token'];
        $stagingPath = "etl_staging/{$token}.json";

        if (!Storage::disk('local')->exists($stagingPath)) {
            return response()->json([
                'success' => false,
                'stage' => 'load',
                'title' => 'Load Gagal!',
                'message' => 'Sesi staging data tidak ditemukan atau sudah kedaluwarsa.',
                'details' => ['Silakan unggah ulang file CSV untuk memulai proses validasi dari awal.'],
            ], 404);
        }

        $payload = json_decode(Storage::disk('local')->get($stagingPath), true);
        $fileName = $payload['file_name'] ?? 'unknown.csv';
        $method = $payload['method'] ?? 'replace';
        $rows = $payload['rows'] ?? [];

        try {
            $loadResult = $etl->loadValidatedRows($rows, $method, $fileName, Auth::id());
            Storage::disk('local')->delete($stagingPath);

            return response()->json([
                'success' => true,
                'stage' => 'load',
                'result' => $loadResult,
            ]);
        } catch (Throwable $e) {
            Log::error('ETL load error: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json([
                'success' => false,
                'stage' => 'load',
                'title' => 'Load Gagal!',
                'message' => 'Gagal menyimpan data ke database. Seluruh perubahan telah dibatalkan (Rollback).',
                'details' => [$e->getMessage()],
            ], 500);
        }
    }

    /**
     * Hapus file staging sementara.
     */
    public function reset(Request $request): JsonResponse
    {
        $token = $request->input('staging_token');
        if (is_string($token) && Str::isUuid($token)) {
            Storage::disk('local')->delete("etl_staging/{$token}.json");
        }
        return response()->json(['success' => true]);
    }

    private function cleanupOldStaging(): void
    {
        try {
            $files = Storage::disk('local')->files('etl_staging');
            $cutoff = now()->subHours(2)->getTimestamp();
            foreach ($files as $file) {
                if (Storage::disk('local')->lastModified($file) < $cutoff) {
                    Storage::disk('local')->delete($file);
                }
            }
        } catch (Throwable $e) {
            // Abaikan error pembersihan file lama
        }
    }

    /**
     * Halaman import CSV (Legacy).
     */
    public function importPage()
    {
        return view('etl.import', [
            'result' => session('etl_result'),
        ]);
    }

    /**
     * Halaman input manual.
     */
    public function manualPage()
    {
        $subCategories = Schema::hasTable(SuperstoreEtl::TABLE)
            ? DB::table(SuperstoreEtl::TABLE)
                ->distinct()
                ->orderBy('Sub-Category')
                ->pluck('Sub-Category')
            : collect();

        return view('etl.manual', [
            'subCategories' => $subCategories,
            'shipModes' => SuperstoreEtl::SHIP_MODES,
            'segments' => SuperstoreEtl::SEGMENTS,
            'regions' => SuperstoreEtl::REGIONS,
            'categories' => SuperstoreEtl::CATEGORIES,
        ]);
    }

    /**
     * Halaman kondisi data, validasi, dan tabel seluruh penjualan.
     */
    public function condition(Request $request, SuperstoreEtl $etl)
    {
        $summary = $etl->summary();
        $search = trim((string) $request->query('search', ''));
        $perPageOptions = [15, 25, 50, 100];
        $perPage = (int) $request->query('per_page', 15);
        if (!in_array($perPage, $perPageOptions, true)) {
            $perPage = 15;
        }

        $sortableColumns = SuperstoreEtl::HEADERS;
        $sort = (string) $request->query('sort', 'Row ID');
        if (!in_array($sort, $sortableColumns, true)) {
            $sort = 'Row ID';
        }

        $direction = strtolower((string) $request->query('direction', 'asc'));
        if (!in_array($direction, ['asc', 'desc'], true)) {
            $direction = 'asc';
        }

        $searchableColumns = [
            'Order ID', 'Customer ID', 'Customer Name', 'Product ID',
            'Product Name', 'City', 'State', 'Region', 'Category',
            'Sub-Category', 'Segment', 'Ship Mode',
        ];

        if (Schema::hasTable(SuperstoreEtl::TABLE)) {
            $query = DB::table(SuperstoreEtl::TABLE);
            if ($search !== '') {
                $query->where(function ($subQuery) use ($search, $searchableColumns) {
                    foreach ($searchableColumns as $column) {
                        $subQuery->orWhere($column, 'LIKE', '%' . $search . '%');
                    }
                });
            }
            $salesData = $query->orderBy($sort, $direction)->paginate($perPage)->withQueryString();
        } else {
            $salesData = new LengthAwarePaginator([], 0, $perPage, 1, [
                'path' => $request->url(),
                'query' => $request->query(),
            ]);
        }

        return view('etl.condition', [
            'summary' => $summary,
            'salesData' => $salesData,
            'search' => $search,
            'perPage' => $perPage,
            'perPageOptions' => $perPageOptions,
            'sort' => $sort,
            'direction' => $direction,
        ]);
    }

    public function replace(Request $request, SuperstoreEtl $etl): RedirectResponse
    {
        $request->validateWithBag(
            'replace',
            $this->csvRules() + ['confirm' => ['accepted']],
            $this->csvMessages() + ['confirm.accepted' => 'Centang kotak konfirmasi sebelum mengganti seluruh data.']
        );

        return $this->runImport('replace', fn () => $etl->replace($request->file('csv')->getRealPath()));
    }

    public function append(Request $request, SuperstoreEtl $etl): RedirectResponse
    {
        $request->validateWithBag('append', $this->csvRules(), $this->csvMessages());
        return $this->runImport('append', fn () => $etl->append($request->file('csv')->getRealPath()));
    }

    public function manual(Request $request, SuperstoreEtl $etl): RedirectResponse
    {
        $data = $request->validateWithBag(
            'manual',
            [
                'order_id' => ['required', 'string', 'max:20'],
                'order_date' => ['required', 'date_format:Y-m-d'],
                'ship_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:order_date'],
                'ship_mode' => ['required', Rule::in(SuperstoreEtl::SHIP_MODES)],
                'customer_id' => ['required', 'string', 'max:20'],
                'customer_name' => ['required', 'string', 'max:100'],
                'segment' => ['required', Rule::in(SuperstoreEtl::SEGMENTS)],
                'country' => ['required', 'string', 'max:50'],
                'city' => ['required', 'string', 'max:100'],
                'state' => ['required', 'string', 'max:100'],
                'postal_code' => ['required', 'string', 'max:10'],
                'region' => ['required', Rule::in(SuperstoreEtl::REGIONS)],
                'product_id' => ['required', 'string', 'max:30'],
                'category' => ['required', Rule::in(SuperstoreEtl::CATEGORIES)],
                'sub_category' => ['required', 'string', 'max:50'],
                'product_name' => ['required', 'string', 'max:255'],
                'sales' => ['required', 'numeric', 'min:0', 'max:99999999'],
                'quantity' => ['required', 'integer', 'min:1', 'max:65535'],
                'discount' => ['required', 'numeric', 'min:0', 'max:100'],
                'profit' => ['required', 'numeric', 'min:-99999999', 'max:99999999'],
            ],
            [
                'required' => 'Kolom :attribute wajib diisi.',
                'after_or_equal' => 'Ship Date tidak boleh lebih awal dari Order Date.',
                'in' => 'Pilihan :attribute tidak valid.',
                'numeric' => ':attribute harus berupa angka (gunakan titik untuk desimal).',
                'integer' => ':attribute harus berupa bilangan bulat.',
                'discount.min' => 'Discount tidak boleh kurang dari 0 persen.',
                'discount.max' => 'Discount tidak boleh lebih dari 100 persen.',
                'date_format' => ':attribute harus menggunakan format tanggal YYYY-MM-DD.',
            ],
            self::MANUAL_LABELS
        );

        $data['discount'] = (float) $data['discount'] / 100;
        $data = array_combine(
            array_map(fn ($key) => self::MANUAL_LABELS[$key], array_keys($data)),
            array_values($data)
        );

        try {
            $rowId = $etl->addOne($data);
        } catch (Throwable $e) {
            Log::error('Tambah data satuan gagal: ' . $e->getMessage());
            return back()->withInput()->withErrors([
                'form' => 'Gagal menyimpan data. Periksa kembali input atau coba lagi.',
            ], 'manual');
        }

        return redirect()
            ->route('etl.condition', ['sort' => 'Row ID', 'direction' => 'desc', 'per_page' => 15])
            ->with('etl_saved', $rowId);
    }

    private function runImport(string $bag, callable $job): RedirectResponse
    {
        set_time_limit(120);
        try {
            $result = $job();
        } catch (Throwable $e) {
            Log::error("ETL {$bag} gagal: " . $e->getMessage(), ['exception' => $e]);
            return back()->withInput()->withErrors(['csv' => mb_substr($e->getMessage(), 0, 10000)], $bag);
        }
        return redirect()->route('etl.import.page')->with('etl_result', $result);
    }

    private function csvRules(): array
    {
        return ['csv' => ['required', 'file', 'mimes:csv,txt', 'max:20480']];
    }

    private function csvMessages(): array
    {
        return [
            'csv.required' => 'Pilih file CSV terlebih dahulu.',
            'csv.file' => 'File yang diunggah tidak valid.',
            'csv.mimes' => 'File harus berformat CSV.',
            'csv.max' => 'Ukuran file maksimal 20 MB.',
        ];
    }

    public function export(): StreamedResponse
    {
        $columns = SuperstoreEtl::HEADERS;
        return response()->streamDownload(
            function () use ($columns) {
                $output = fopen('php://output', 'wb');
                if ($output === false) {
                    throw new \RuntimeException('Tidak dapat membuka output CSV.');
                }
                try {
                    fwrite($output, "\xEF\xBB\xBF");
                    fputcsv($output, $columns, ',', '"', '');
                    $rows = DB::table(SuperstoreEtl::TABLE)->orderBy('Row ID')->cursor();
                    foreach ($rows as $row) {
                        $values = [];
                        foreach ($columns as $column) {
                            $values[] = $row->{$column} ?? '';
                        }
                        fputcsv($output, $values, ',', '"', '');
                    }
                } finally {
                    fclose($output);
                }
            },
            'superstore_data_' . now()->format('Ymd_His') . '.csv',
            [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Cache-Control' => 'no-store, no-cache, must-revalidate',
            ]
        );
    }
}
