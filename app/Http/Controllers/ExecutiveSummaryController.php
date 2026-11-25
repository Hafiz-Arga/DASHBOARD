<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExecutiveSummaryController extends Controller
{
    public function index(Request $request)
    {
        // ==========================================
        // 1. PILIHAN GLOBAL FILTER
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

        // Default tahun adalah tahun terakhir dalam dataset
        $requestedYear = $request->query('year');
        $selectedYear = $requestedYear === null
            ? ($years->last() ?? '')
            : ($requestedYear === 'all' ? '' : $requestedYear);

        $selectedRegion = $request->query('region', '');
        $selectedCategory = $request->query('category', '');


        // ==========================================
        // 2. QUERY DASAR BERDASARKAN FILTER
        // ==========================================

        $query = DB::table('sample_superstore');

        if ($selectedYear !== '') {
            $query->whereYear('Order Date', $selectedYear);
        }

        if ($selectedRegion !== '') {
            $query->where('Region', $selectedRegion);
        }

        if ($selectedCategory !== '') {
            $query->where('Category', $selectedCategory);
        }


        // ==========================================
        // 3. KPI OVERVIEW
        // ==========================================

        $totalSales = (clone $query)->sum('Sales');

        $totalProfit = (clone $query)->sum('Profit');

        $profitMargin = $totalSales != 0
            ? ($totalProfit / $totalSales) * 100
            : 0;


        // Menghitung profit berdasarkan Order ID
        $orderProfits = (clone $query)
            ->select('Order ID')
            ->selectRaw('SUM(Profit) AS order_profit')
            ->groupBy('Order ID');

        $orderProfitData = (clone $orderProfits)->get();

        $totalOrders = $orderProfitData->count();

        $negativeOrders = $orderProfitData
            ->where('order_profit', '<', 0)
            ->count();

        $negativeProfitPercentage = $totalOrders > 0
            ? ($negativeOrders / $totalOrders) * 100
            : 0;


        // ==========================================
        // 4. BUSINESS PERFORMANCE OVERVIEW
        // ==========================================

        $categoryPerformance = (clone $query)
            ->select('Category')
            ->selectRaw('SUM(Sales) AS total_sales')
            ->selectRaw('SUM(Profit) AS total_profit')
            ->groupBy('Category')
            ->orderBy('Category')
            ->get();

        // Total Sales dan Profit untuk menghitung kontribusi
        $overallSales = $categoryPerformance->sum('total_sales');
        $overallProfit = $categoryPerformance->sum('total_profit');

        $categoryPerformance = $categoryPerformance->map(function ($item) use (
            $overallSales,
            $overallProfit
        ) {
            $item->sales_contribution = $overallSales != 0
                ? ($item->total_sales / $overallSales) * 100
                : 0;

            // Jika total profit keseluruhan nol atau negatif,
            // kontribusi profit tidak ditampilkan sebagai persentase.
            $item->profit_contribution = $overallProfit > 0
                ? ($item->total_profit / $overallProfit) * 100
                : null;

            return $item;
        });


        // ==========================================
        // 5. BOTTOM 5 PRODUCTS BY PROFIT
        // ==========================================

        $bottomProducts = (clone $query)
            ->select('Product Name')
            ->selectRaw('SUM(Profit) AS total_profit')
            ->selectRaw('SUM(Sales) AS total_sales')
            ->groupBy('Product Name')
            ->orderBy('total_profit', 'asc')
            ->limit(5)
            ->get();

        // ==========================================
        // 6. KEY FINDINGS
        // ==========================================

        $keyFindings = [];

        $categoryCount = $categoryPerformance->count();

        // Finding 1: Kategori dengan kontribusi Sales terbesar
        $highestSalesCategory = $categoryPerformance
            ->sortByDesc('total_sales')
            ->first();

        if ($highestSalesCategory && $categoryCount > 1) {
            $keyFindings[] =
                'Kategori ' . $highestSalesCategory->Category .
                ' memiliki kontribusi penjualan terbesar sebesar ' .
                number_format($highestSalesCategory->sales_contribution, 1) .
                '% dari total Sales.';
        }

        // Finding 2: Kategori dengan Profit tertinggi
        if ($categoryCount > 1) {
            $highestProfitCategory = $categoryPerformance
                ->sortByDesc('total_profit')
                ->first();

            $keyFindings[] =
                'Kategori ' . $highestProfitCategory->Category .
                ' menghasilkan total Profit tertinggi sebesar $' .
                number_format($highestProfitCategory->total_profit, 2) . '.';
        }

        // Finding 3: Kategori dengan Profit terendah
        if ($categoryCount > 1) {
            $lowestProfitCategory = $categoryPerformance
                ->sortBy('total_profit')
                ->first();

            $keyFindings[] =
                'Kategori ' . $lowestProfitCategory->Category .
                ' memiliki total Profit terendah sebesar $' .
                number_format($lowestProfitCategory->total_profit, 2) . '.';
        }

        // Finding 4: Proporsi pesanan dengan profit negatif
        if ($totalOrders > 0) {
            $keyFindings[] =
                number_format($negativeProfitPercentage, 1) .
                '% dari seluruh pesanan dalam cakupan filter menghasilkan total Profit negatif.';
        }

        // ==========================================
        // 7. KIRIM DATA KE VIEW
        // ==========================================

        return view('executive-summary', compact(
            'years',
            'regions',
            'categories',

            'selectedYear',
            'selectedRegion',
            'selectedCategory',

            'totalSales',
            'totalProfit',
            'profitMargin',
            'negativeProfitPercentage',

            'categoryPerformance',
            'bottomProducts',
            'keyFindings'
        ));
    }
}
