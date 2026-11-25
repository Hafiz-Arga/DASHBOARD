<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExecutiveDashboardController extends Controller
{
    public function index(Request $request)
    {
        // ==========================================
        // 1. GLOBAL FILTER
        // ==========================================

        $years = DB::table('sample_superstore')
            ->selectRaw('DISTINCT YEAR(`Order Date`) AS year')
            ->orderBy('year')
            ->pluck('year');

        $regions = DB::table('sample_superstore')
            ->distinct()
            ->orderBy('Region')
            ->pluck('Region');

        $categories = DB::table('sample_superstore')
            ->distinct()
            ->orderBy('Category')
            ->pluck('Category');

        $requestedYear = $request->query('year');
        $selectedYear = $requestedYear === null
            ? ($years->last() ?? '')
            : ($requestedYear === 'all' ? '' : $requestedYear);
        $selectedRegion = $request->query('region', '');
        $selectedCategory = $request->query('category', '');

        // ==========================================
        // 2. DRILL-DOWN CONTEXT
        // ==========================================

        $geoLevel = $request->query('geo_level', 'region');
        $geoRegion = $request->query('geo_region', '');
        $geoState = $request->query('geo_state', '');

        $productLevel = $request->query('product_level', 'category');
        $productCategory = $request->query('product_category', '');
        $productSubcategory = $request->query('product_subcategory', '');

        if (!in_array($geoLevel, ['region', 'state', 'city'])) {
            $geoLevel = 'region';
        }

        if (!in_array($productLevel, ['category', 'sub-category', 'product'])) {
            $productLevel = 'category';
        }

        // Kompatibilitas parameter lama
        if ($geoRegion === '' && $geoState === '') {
            $oldGeoValue = $request->query('geo_value', '');

            if ($geoLevel === 'state') {
                $geoRegion = $oldGeoValue;
            } elseif ($geoLevel === 'city') {
                $geoState = $oldGeoValue;
            }
        }

        if ($productCategory === '' && $productSubcategory === '') {
            $oldProductValue = $request->query('product_value', '');

            if ($productLevel === 'sub-category') {
                $productCategory = $oldProductValue;
            } elseif ($productLevel === 'product') {
                $productSubcategory = $oldProductValue;
            }
        }

        // ==========================================
        // 3. GLOBAL QUERY
        // Hanya menggunakan filter utama
        // ==========================================

        $buildGlobalQuery = function ($year = null) use (
            $selectedRegion,
            $selectedCategory
        ) {
            $query = DB::table('sample_superstore');

            if ($year !== null && $year !== '') {
                $query->whereYear('Order Date', $year);
            }

            if ($selectedRegion !== '') {
                $query->where('Region', $selectedRegion);
            }

            if ($selectedCategory !== '') {
                $query->where('Category', $selectedCategory);
            }

            return $query;
        };

        // ==========================================
        // 4. KPI CALCULATION
        // KPI hanya mengikuti global filter
        // ==========================================

        $calculateKPI = function ($year) use ($buildGlobalQuery) {

            $query = $buildGlobalQuery($year);

            $sales = (clone $query)->sum('Sales');
            $profit = (clone $query)->sum('Profit');

            $margin = $sales != 0
                ? ($profit / $sales) * 100
                : 0;

            $orders = (clone $query)
                ->select('Order ID')
                ->selectRaw('SUM(Profit) AS order_profit')
                ->groupBy('Order ID')
                ->get();

            $totalOrders = $orders->count();

            $negativeOrders = $orders
                ->where('order_profit', '<', 0)
                ->count();

            $negativePercentage = $totalOrders > 0
                ? ($negativeOrders / $totalOrders) * 100
                : 0;

            return [
                'sales' => $sales,
                'profit' => $profit,
                'margin' => $margin,
                'negative' => $negativePercentage,
            ];
        };

        $current = $calculateKPI(
            $selectedYear !== '' ? $selectedYear : null
        );

        // ==========================================
        // 5. YEAR-OVER-YEAR COMPARISON
        // ==========================================

        $previous = null;

        $previousYear = (string) ((int) $selectedYear - 1);

        if (
            $selectedYear !== '' &&
            $years->map(fn($year) => (string) $year)->contains($previousYear)
        ) {
            $previous = $calculateKPI($previousYear);
        }

        $calculateYoY = function ($currentValue, $previousValue) {

            if ($previousValue === null || $previousValue == 0) {
                return null;
            }

            return (
                ($currentValue - $previousValue)
                / abs($previousValue)
            ) * 100;
        };

        $kpis = [];

        foreach (['sales', 'profit', 'margin', 'negative'] as $metric) {

            $kpis[$metric] = [
                'value' => $current[$metric],

                'yoy' => $previous !== null
                    ? $calculateYoY(
                        $current[$metric],
                        $previous[$metric]
                    )
                    : null,
            ];
        }

        // ==========================================
        // 6. BASE QUERY UNTUK GRAFIK
        // ==========================================

        $chartQuery = $buildGlobalQuery(
            $selectedYear !== '' ? $selectedYear : null
        );

        // ==========================================
        // 7. PROFIT MARGIN TREND
        // Hanya mengikuti global filter
        // ==========================================

        $trend = (clone $chartQuery)
            ->selectRaw("
                CONCAT(
                    YEAR(`Order Date`),
                    '-Q',
                    QUARTER(`Order Date`)
                ) AS period
            ")
            ->selectRaw("
                SUM(Profit) / NULLIF(SUM(Sales), 0) * 100 AS margin
            ")
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        // ==========================================
        // 8. GEOGRAPHICAL DRILL-DOWN
        // Hanya memengaruhi grafik geografis
        // ==========================================

        $geoColumn = match ($geoLevel) {
            'region' => 'Region',
            'state' => 'State',
            'city' => 'City',
        };

        $geoQuery = clone $chartQuery;

        // Region -> State
        if ($geoLevel === 'state' && $geoRegion !== '') {
            $geoQuery->where('Region', $geoRegion);
        }

        // State -> City
        if ($geoLevel === 'city') {

            if ($geoRegion !== '') {
                $geoQuery->where('Region', $geoRegion);
            }

            if ($geoState !== '') {
                $geoQuery->where('State', $geoState);
            }
        }

        $profitByState = $geoQuery
            ->select($geoColumn . ' as label')
            ->selectRaw('SUM(Profit) AS profit')
            ->groupBy($geoColumn)
            ->orderBy('profit')
            ->when($geoLevel === 'city', function ($query) {
                $query->limit(9);
            })
            ->get();

        // ==========================================
        // 9. PRODUCT DRILL-DOWN
        // Hanya memengaruhi grafik produk
        // ==========================================

        $productColumn = match ($productLevel) {
            'category' => '`Category`',
            'sub-category' => '`Sub-Category`',
            'product' => '`Product Name`',
        };

        $productQuery = clone $chartQuery;

        // Category -> Sub-Category
        if (
            $productLevel === 'sub-category' &&
            $productCategory !== ''
        ) {
            $productQuery->where('Category', $productCategory);
        }

        // Sub-Category -> Product
        if ($productLevel === 'product') {

            if ($productCategory !== '') {
                $productQuery->where('Category', $productCategory);
            }

            if ($productSubcategory !== '') {
                $productQuery->where(
                    'Sub-Category',
                    $productSubcategory
                );
            }
        }

        $productQuery = $productQuery
            ->selectRaw("$productColumn AS label")
            ->selectRaw('SUM(Profit) AS profit')
            ->groupByRaw($productColumn)
            ->orderBy('profit');

        if ($productLevel === 'product') {
            $productQuery->limit(10);
        }

        $profitByCategory = $productQuery->get();

        // ==========================================
        // 10. PROFIT MARGIN BY DISCOUNT GROUP
        // Hanya mengikuti global filter
        // ==========================================

        $discountGroups = (clone $chartQuery)
            ->selectRaw("
                CASE
                    WHEN Discount = 0 THEN '0%'
                    WHEN Discount <= 0.10 THEN '1-10%'
                    WHEN Discount <= 0.20 THEN '11-20%'
                    WHEN Discount <= 0.30 THEN '21-30%'
                    WHEN Discount <= 0.40 THEN '31-40%'
                    WHEN Discount <= 0.50 THEN '41-50%'
                    WHEN Discount <= 0.60 THEN '51-60%'
                    WHEN Discount <= 0.70 THEN '61-70%'
                    WHEN Discount <= 0.80 THEN '71-80%'
                    ELSE '>80%'
                END AS discount_group
            ")
            ->selectRaw("
                SUM(Profit) / NULLIF(SUM(Sales), 0) * 100 AS margin
            ")
            ->groupBy('discount_group')
            ->get()
            ->sortBy(function ($item) {
                return match ($item->discount_group) {
                    '0%' => 1,
                    '1-10%' => 2,
                    '11-20%' => 3,
                    '21-30%' => 4,
                    '31-40%' => 5,
                    '41-50%' => 6,
                    '51-60%' => 7,
                    '61-70%' => 8,
                    '71-80%' => 9,
                    '>80%' => 10,
                    default => 11,
                };
            })
            ->values();

        // ==========================================
        // 11. BREADCRUMB
        // ==========================================

        $geoBreadcrumb = [
            'region' => 'Region',
            'state' => 'State',
            'city' => 'City',
        ];

        $productBreadcrumb = [
            'category' => 'Category',
            'sub-category' => 'Sub-Category',
            'product' => 'Product',
        ];

        $geoValue = match ($geoLevel) {
            'state' => $geoRegion,
            'city' => $geoState,
            default => '',
        };

        $productValue = match ($productLevel) {
            'sub-category' => $productCategory,
            'product' => $productSubcategory,
            default => '',
        };

        // ==========================================
        // 12. DATA RESPONSE
        // ==========================================

        $data = [
            'kpis' => $kpis,

            'trend' => $trend,

            'profitByState' => $profitByState,

            'profitByCategory' => $profitByCategory,

            'discountGroups' => $discountGroups,

            'geoLevel' => $geoLevel,
            'geoRegion' => $geoRegion,
            'geoState' => $geoState,
            'geoValue' => $geoValue,
            'geoBreadcrumb' => $geoBreadcrumb,

            'productLevel' => $productLevel,
            'productCategory' => $productCategory,
            'productSubcategory' => $productSubcategory,
            'productValue' => $productValue,
            'productBreadcrumb' => $productBreadcrumb,
        ];

        if ($request->expectsJson()) {
            return response()->json($data);
        }

        return view('executive-dashboard', array_merge($data, [
            'years' => $years,
            'regions' => $regions,
            'categories' => $categories,

            'selectedYear' => $selectedYear,
            'selectedRegion' => $selectedRegion,
            'selectedCategory' => $selectedCategory,
        ]));
    }
}
