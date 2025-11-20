<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function getSalesData()
    {
        try {
            // Generate last 6 months in PHP instead of SQL
            $months = [];
            for ($i = 5; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $months[] = [
                    'month_key' => $month->format('Y-m'),
                    'month_name' => $month->format('F')
                ];
            }

            $labels = [];
            $data = [];

            foreach ($months as $month) {
                $count = DB::table('orders')
                    ->where(DB::raw('DATE_FORMAT(order_date, "%Y-%m")'), $month['month_key'])
                    ->count();

                $labels[] = $month['month_name'];
                $data[] = $count;
            }

            return response()->json([
                'success' => true,
                'labels' => $labels,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'labels' => [],
                'data' => []
            ], 500);
        }
    }
}