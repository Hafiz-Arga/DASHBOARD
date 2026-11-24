<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WhatIfController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 1. DISCOUNT POLICY SIMULATION
    |--------------------------------------------------------------------------
    */

    public function discount(Request $request)
    {
        // Global filters
        $filters = $this->getGlobalFilters($request);

        // Discount scenario
        $scenarios = [
            'status_quo' => [
                'label' => 'Status Quo',
                'cap' => null,
            ],
            'cap20' => [
                'label' => 'Cap 20%',
                'cap' => 0.20,
            ],
            'cap15' => [
                'label' => 'Cap 15%',
                'cap' => 0.15,
            ],
        ];

        $selectedScenario = $request->query('scenario', 'cap20');

        if (!array_key_exists($selectedScenario, $scenarios)) {
            $selectedScenario = 'cap20';
        }

        $scenarioLabel = $scenarios[$selectedScenario]['label'];
        $discountCap = $scenarios[$selectedScenario]['cap'];

        // Ambil data sesuai global filters
        $rows = $this->getFilteredRows($filters)->get();

        // Discount calculation
        $calculatedRows = $rows->map(function ($row) use ($discountCap) {

            $actualSales = (float) $row->Sales;
            $actualProfit = (float) $row->Profit;
            $actualDiscount = (float) $row->Discount;

            if ($discountCap === null) {
                $scenarioSales = $actualSales;
                $scenarioProfit = $actualProfit;
            } else {
                $grossSales = (1 - $actualDiscount) != 0
                    ? $actualSales / (1 - $actualDiscount)
                    : $actualSales;

                $newDiscount = min($actualDiscount, $discountCap);

                // Asumsi biaya dan kuantitas tetap
                $fixedCost = $actualSales - $actualProfit;

                $scenarioSales = $grossSales * (1 - $newDiscount);
                $scenarioProfit = $scenarioSales - $fixedCost;
            }

            // Discount grouping
            $actualDiscountPercent = round(
                $actualDiscount * 100,
                2
            );

            if ($actualDiscountPercent <= 0) {
                $discountGroup = '0%';
                $discountGroupOrder = 0;
            } else {
                $discountGroupOrder = (int) ceil(
                    $actualDiscountPercent / 10
                );

                $upperLimit = $discountGroupOrder * 10;
                $lowerLimit = $upperLimit - 9;

                $discountGroup = $lowerLimit . '-' . $upperLimit . '%';
            }

            return (object) [
                'order_id' => $row->{'Order ID'},
                'category' => $row->Category,
                'sub_category' => $row->{'Sub-Category'},

                'discount' => $actualDiscount,
                'discount_group' => $discountGroup,
                'discount_group_order' => $discountGroupOrder,

                'actual_sales' => $actualSales,
                'actual_profit' => $actualProfit,

                'scenario_sales' => $scenarioSales,
                'scenario_profit' => $scenarioProfit,
            ];
        });

        // Summary function
        $summarize = function ($items) {

            $salesBefore = $items->sum('actual_sales');
            $salesAfter = $items->sum('scenario_sales');

            $profitBefore = $items->sum('actual_profit');
            $profitAfter = $items->sum('scenario_profit');

            // Agregasi profit per Order ID
            $orders = $items->groupBy('order_id');

            $negativeBefore = $orders->filter(function ($orderRows) {
                return $orderRows->sum('actual_profit') < 0;
            })->count();

            $negativeAfter = $orders->filter(function ($orderRows) {
                return $orderRows->sum('scenario_profit') < 0;
            })->count();

            $totalOrders = $orders->count();

            $marginBefore = $salesBefore != 0
                ? ($profitBefore / $salesBefore) * 100
                : 0;

            $marginAfter = $salesAfter != 0
                ? ($profitAfter / $salesAfter) * 100
                : 0;

            return [
                'sales_before' => $salesBefore,
                'sales_after' => $salesAfter,
                'sales_delta' => $salesAfter - $salesBefore,

                'profit_before' => $profitBefore,
                'profit_after' => $profitAfter,
                'profit_delta' => $profitAfter - $profitBefore,

                'margin_before' => $marginBefore,
                'margin_after' => $marginAfter,

                'negative_orders_before' => $negativeBefore,
                'negative_orders_after' => $negativeAfter,

                'negative_percentage_before' => $totalOrders > 0
                    ? ($negativeBefore / $totalOrders) * 100
                    : 0,

                'negative_percentage_after' => $totalOrders > 0
                    ? ($negativeAfter / $totalOrders) * 100
                    : 0,

                'total_orders' => $totalOrders,
            ];
        };

        // Discount KPIs
        $kpis = $summarize($calculatedRows);

        // Hierarchy: Category -> Sub-Category -> Discount Group
        $categoryData = $calculatedRows
            ->groupBy('category')
            ->map(function ($categoryRows, $categoryName) use ($summarize) {

                $subcategories = $categoryRows
                    ->groupBy('sub_category')
                    ->map(function ($subRows, $subName) use ($summarize) {

                        $discountGroups = $subRows
                            ->groupBy('discount_group')
                            ->sortBy(function ($groupRows) {
                                return $groupRows
                                    ->first()
                                    ->discount_group_order;
                            })
                            ->map(function ($groupRows, $groupName) use ($summarize) {

                                return array_merge(
                                    ['label' => $groupName],
                                    $summarize($groupRows)
                                );
                            })
                            ->values();

                        return array_merge(
                            [
                                'label' => $subName,
                                'children' => $discountGroups,
                            ],
                            $summarize($subRows)
                        );
                    })
                    ->sortBy('profit_before')
                    ->values();

                return array_merge(
                    [
                        'label' => $categoryName,
                        'children' => $subcategories,
                    ],
                    $summarize($categoryRows)
                );
            })
            ->sortBy('profit_before')
            ->values();

        $data = array_merge($filters, [
            'kpis' => $kpis,
            'categoryData' => $categoryData,

            'scenarios' => $scenarios,
            'selectedScenario' => $selectedScenario,
            'scenarioLabel' => $scenarioLabel,
            'discountCap' => $discountCap,
        ]);

        if ($request->expectsJson()) {
            return response()->json($data);
        }

        return view('what-if.discount', $data);
    }


    /*
    |--------------------------------------------------------------------------
    | 2. REGIONAL GROWTH ALLOCATION
    |--------------------------------------------------------------------------
    */

    public function growth(Request $request)
    {
        // Global filters
        $filters = $this->getGlobalFilters($request);

        // Regional Growth Allocation selalu menggunakan seluruh wilayah
        // Filter Region hanya berlaku pada Discount Policy Simulation
        $filters['selectedRegion'] = '';

        // Baseline growth: 10% for all regions
        $baselineGrowthRate = 10;

        // Default alternative growth rates
        $defaultGrowthRates = [
            'West' => 15,
            'East' => 15,
            'Central' => 5,
            'South' => 5,
        ];

        $inputGrowthRates = $request->query('growth_rates', []);

        if (!is_array($inputGrowthRates)) {
            $inputGrowthRates = [];
        }

        $growthRates = [];

        foreach ($filters['regions'] as $region) {

            $defaultRate = $defaultGrowthRates[$region] ?? 10;

            $inputRate = $inputGrowthRates[$region] ?? $defaultRate;

            if (!is_numeric($inputRate)) {
                $inputRate = $defaultRate;
            }

            $growthRates[$region] = max(
                -100,
                min(500, (float) $inputRate)
            );
        }

        // Ambil data berdasarkan Year dan Category
        // Tanpa membatasi Region
        $rows = $this->getFilteredRows($filters)->get();

        // Regional growth calculation
        $growthRows = $rows->map(function ($row) use (
            $baselineGrowthRate,
            $growthRates
        ) {

            $actualSales = (float) $row->Sales;
            $actualProfit = (float) $row->Profit;

            $region = $row->Region;

            $baselineRate = $baselineGrowthRate / 100;
            $alternativeRate = ($growthRates[$region] ?? 10) / 100;

            // Baseline simulation
            $baselineSales = $actualSales * (1 + $baselineRate);
            $baselineProfit = $actualProfit * (1 + $baselineRate);

            // Alternative simulation
            $alternativeSales = $actualSales * (1 + $alternativeRate);
            $alternativeProfit = $actualProfit * (1 + $alternativeRate);

            return (object) [
                'region' => $region,
                'state' => $row->State,
                'city' => $row->City,

                'actual_sales' => $actualSales,
                'actual_profit' => $actualProfit,

                'baseline_sales' => $baselineSales,
                'baseline_profit' => $baselineProfit,

                'alternative_sales' => $alternativeSales,
                'alternative_profit' => $alternativeProfit,
            ];
        });

        // Growth summary function
        $summarizeGrowth = function ($items) {

            $actualSales = $items->sum('actual_sales');
            $actualProfit = $items->sum('actual_profit');

            $baselineSales = $items->sum('baseline_sales');
            $baselineProfit = $items->sum('baseline_profit');

            $alternativeSales = $items->sum('alternative_sales');
            $alternativeProfit = $items->sum('alternative_profit');

            $actualMargin = $actualSales != 0
                ? ($actualProfit / $actualSales) * 100
                : 0;

            $baselineMargin = $baselineSales != 0
                ? ($baselineProfit / $baselineSales) * 100
                : 0;

            $alternativeMargin = $alternativeSales != 0
                ? ($alternativeProfit / $alternativeSales) * 100
                : 0;

            return [
                'actual_sales' => $actualSales,
                'actual_profit' => $actualProfit,
                'actual_margin' => $actualMargin,

                'baseline_sales' => $baselineSales,
                'baseline_profit' => $baselineProfit,
                'baseline_margin' => $baselineMargin,

                'alternative_sales' => $alternativeSales,
                'alternative_profit' => $alternativeProfit,
                'alternative_margin' => $alternativeMargin,

                'baseline_sales_delta' => $baselineSales - $actualSales,
                'baseline_profit_delta' => $baselineProfit - $actualProfit,

                'alternative_sales_delta' => $alternativeSales - $actualSales,
                'alternative_profit_delta' => $alternativeProfit - $actualProfit,

                'profit_difference' => $alternativeProfit - $baselineProfit,
                'sales_difference' => $alternativeSales - $baselineSales,

                'margin_difference' => $alternativeMargin - $baselineMargin,
            ];
        };

        // Growth KPIs
        $growthKpis = $summarizeGrowth($growthRows);

        // Hierarchy: Region -> State -> City
        $growthData = $growthRows
            ->groupBy('region')
            ->map(function ($regionRows, $regionName) use ($summarizeGrowth) {

                $states = $regionRows
                    ->groupBy('state')
                    ->map(function ($stateRows, $stateName) use ($summarizeGrowth) {

                        $cities = $stateRows
                            ->groupBy('city')
                            ->map(function ($cityRows, $cityName) use ($summarizeGrowth) {

                                return array_merge(
                                    ['label' => $cityName],
                                    $summarizeGrowth($cityRows)
                                );
                            })
                            ->sortBy('alternative_profit')
                            ->values();

                        return array_merge(
                            [
                                'label' => $stateName,
                                'children' => $cities,
                            ],
                            $summarizeGrowth($stateRows)
                        );
                    })
                    ->sortBy('alternative_profit')
                    ->values();

                return array_merge(
                    [
                        'label' => $regionName,
                        'children' => $states,
                    ],
                    $summarizeGrowth($regionRows)
                );
            })
            ->sortBy('alternative_profit')
            ->values();

        $data = array_merge($filters, [
            'growthKpis' => $growthKpis,
            'growthData' => $growthData,
            'growthRates' => $growthRates,
            'baselineGrowthRate' => $baselineGrowthRate,
        ]);

        if ($request->expectsJson()) {
            return response()->json($data);
        }

        return view('what-if.growth', $data);
    }


    /*
    |--------------------------------------------------------------------------
    | 3. GLOBAL FILTERS
    |--------------------------------------------------------------------------
    */

    private function getGlobalFilters(Request $request)
    {
        $years = DB::table('sample_superstore')
            ->selectRaw(
                "DISTINCT CAST(LEFT(TRIM(`Order Date`), 4) AS UNSIGNED) AS year"
            )
            ->orderBy('year', 'asc')
            ->pluck('year');

        $regions = DB::table('sample_superstore')
            ->distinct()
            ->orderBy('Region')
            ->pluck('Region');

        $categories = DB::table('sample_superstore')
            ->distinct()
            ->orderBy('Category')
            ->pluck('Category');

        $selectedYear = $request->query('year', $years->last() ?? '');
        $selectedRegion = $request->query('region', '');
        $selectedCategory = $request->query('category', '');

        return [
            'years' => $years,
            'regions' => $regions,
            'categories' => $categories,

            'selectedYear' => $selectedYear,
            'selectedRegion' => $selectedRegion,
            'selectedCategory' => $selectedCategory,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | 4. DATA QUERY BASED ON GLOBAL FILTERS
    |--------------------------------------------------------------------------
    */

    private function getFilteredRows(array $filters)
    {
        $query = DB::table('sample_superstore')
            ->select([
                'Order ID',
                'Region',
                'State',
                'City',
                'Category',
                'Sub-Category',
                'Discount',
                'Sales',
                'Profit',
            ]);

        if ($filters['selectedYear'] !== '') {
            $query->whereRaw(
                "LEFT(TRIM(`Order Date`), 4) = ?",
                [(string) $filters['selectedYear']]
            );
        }

        if ($filters['selectedRegion'] !== '') {
            $query->where('Region', $filters['selectedRegion']);
        }

        if ($filters['selectedCategory'] !== '') {
            $query->where('Category', $filters['selectedCategory']);
        }

        return $query;
    }
}
