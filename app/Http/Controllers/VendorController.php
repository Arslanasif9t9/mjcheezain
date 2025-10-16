<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $vendor_id = $user->user_id;

        $vendorBasicInfo = DB::table('vendor_basic_info')
            ->where('user_id', $vendor_id)
            ->first();
        // dd($vendorBasicInfo);

        // ✅ Orders (last 6 months)
        $orders = DB::table('orders')
            ->selectRaw("DATE_FORMAT(order_date, '%Y-%m') as month, COUNT(*) as total_orders")
            ->where('vendor_id', $vendor_id)
            ->whereRaw("order_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Prepare 6-month chart data
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $m = date('Y-m', strtotime("-$i month"));
            $months[$m] = 0;
        }
        foreach ($orders as $o) {
            $months[$o->month] = $o->total_orders;
        }

        $chartLabels = array_map(fn($m) => date('M', strtotime("$m-01")), array_keys($months));
        $chartData = array_values($months);
        // dd($chartLabels, $chartData);

        // ✅ Vendor balance
        $balanceRow = DB::table('vendor_balance')
            ->where('user_id', $vendor_id)
            ->select('total_balance')
            ->first();
        $balance = $balanceRow->total_balance ?? 0.00;

        // ✅ Products (from vendor_products)
        $products = DB::table('vendor_products')
            ->where('user_id', $vendor_id)
            // ->select('id', 'name', 'selling_price', 'quantity', 'position', 'image_path')
            ->select('id', 'name', 'selling_price', 'quantity', 'position')
            ->orderByDesc('id')
            ->limit(8)
            ->get();

         // ✅ Stats Cards Data
        $totalProducts = DB::table('vendor_products')
            ->where('user_id', $vendor_id)
            ->count();

        $totalSales = DB::table('orders')
            ->where('vendor_id', $vendor_id)
            ->where('fulfillment', 'delivered')
            ->count();

        $newOrders = DB::table('orders')
            ->where('vendor_id', $vendor_id)
            ->count();

        // ✅ Recent Sold Orders (Last 3 orders)
        $recentOrders = DB::table('orders as o')
            ->select(
                'o.id as order_id',
                'p.name as product_name',
                'p.category as product_category',
                'o.total_amount',
                'o.order_date',
                'c.first_name as customer_name',
                'o.fulfillment',
                'pi.image_path'
            )
            ->join('vendor_products as p', 'o.product_id', '=', 'p.id')
            ->join('users as u', 'o.user_id', '=', 'u.user_id')
            ->leftJoin('customer_profile as c', 'o.user_id', '=', 'c.user_id')
            ->leftJoin('vendor_product_images as pi', function($join) {
                $join->on('pi.product_id', '=', 'p.id')
                     ->where('pi.is_primary', '=', true);
            })
            ->where('o.vendor_id', $vendor_id)
            ->orderBy('o.order_date', 'desc')
            ->limit(3)
            ->get()
            ->map(function($order) {
                return [
                    'order_id' => $order->order_id,
                    'product_name' => $order->product_name,
                    'product_category' => $order->product_category,
                    'total_amount' => $order->total_amount,
                    'order_date' => $order->order_date,
                    'customer_name' => $order->customer_name ?: 'N/A',
                    'fulfillment' => $order->fulfillment,
                    'image_path' => $order->image_path ? asset('uploads/' . $order->image_path) : asset('img/default_product.webp')
                ];
            })
            ->toArray();

        // ✅ Top Categories
        $topCategories = DB::table('vendor_products as p')
            ->select('p.name', DB::raw('COUNT(o.id) as order_count'))
            ->leftJoin('orders as o', function($join) use ($vendor_id) {
                $join->on('p.id', '=', 'o.product_id')
                     ->where('o.vendor_id', '=', $vendor_id);
            })
            ->where('p.user_id', $vendor_id)
            ->groupBy('p.id', 'p.name')
            ->orderBy('order_count', 'desc')
            ->limit(5)
            ->get()
            ->map(function($category) {
                return [
                    'name' => $category->name,
                    'order_count' => $category->order_count
                ];
            })
            ->toArray();

        return view('vendor.dashboard', compact(
            'user', 'balance', 'products', 
            'chartLabels', 'chartData',
            'vendorBasicInfo',
            'totalProducts',
            'totalSales',
            'newOrders',
            'recentOrders',
            'topCategories'
        ));
    }
}
