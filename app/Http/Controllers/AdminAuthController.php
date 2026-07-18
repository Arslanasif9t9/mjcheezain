<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AdminAuthController extends Controller
{
    function loginForm() {
        return view('Admin/login');
    }
    
    function login(Request $request) {
        $admin = DB::table('admin_users')
                    ->where('username', $request->username)
                    ->first();
        if (!$admin) {
            // Generic message — never reveal whether the username exists
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Check password: bcrypt hash preferred; legacy plaintext rows are
        // upgraded to a hash on first successful login.
        $given = (string) $request->password;
        $stored = (string) $admin->password_hash;
        $isHashed = str_starts_with($stored, '$2y$') || str_starts_with($stored, '$argon2');
        $ok = $isHashed
            ? \Illuminate\Support\Facades\Hash::check($given, $stored)
            : hash_equals($stored, $given);

        if ($ok && !$isHashed) {
            DB::table('admin_users')->where('id', $admin->id)
                ->update(['password_hash' => \Illuminate\Support\Facades\Hash::make($given)]);
        }

        if ($ok) {
            // Sanitised copy — never store or ship the password hash
            $safeAdmin = (object) collect((array) $admin)->except(['password_hash'])->all();

            // Regenerate the session id to prevent session fixation
            $request->session()->regenerate();

            Session::put('admin_logged_in', true);
            Session::put('admin_id', $admin->id);
            Session::put('admin_username', $admin->username);
            Session::put('admin_data', $safeAdmin);

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => $safeAdmin
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }
    }

    function logout() {
        // Clear all session data
        Session::forget('admin_logged_in');
        Session::forget('admin_id');
        Session::forget('admin_username');
        Session::forget('admin_data');
        
        // Alternatively, you can flush all session data
        // Session::flush();
        
        return redirect('/admin/login');
    }

    function dashboard() {
        // Check if admin is logged in
        if (!Session::get('admin_logged_in')) {
            return redirect('/admin/login');
        }

        $totalUsers = DB::table('users')->count();
        $activeUsers = DB::table('users')
                        ->where('verified', 1)
                        ->count();
        $orders = DB::table('orders')->count();
        $vendors = DB::table('users')
                        ->where('type', 'vendor')
                        ->count();
        $activeVendors = DB::table('users')
                        ->where('type', 'vendor')
                        ->where('verified', 1)
                        ->count();

        // Real operational stats (previously hardcoded zeros in the view)
        $totalReturns = DB::table('return_requests')->count();
        $totalReplacements = DB::table('replacement_requests')->count();
        $cancelledOrders = DB::table('carts')
                        ->whereIn('status', ['cancelled', 'cencelled']) // legacy rows used the misspelling
                        ->count();
        $totalSales = (float) DB::table('carts')
                        ->where('status', 'delivered')
                        ->sum(DB::raw('price * quantity'));
        $pendingWithdrawals = (float) DB::table('withdrawal_requests')
                        ->where('status', 'pending')
                        ->sum('amount');
        $pendingProducts = DB::table('vendor_products')
                        ->where('position', 'pending')
                        ->count();

        return view('Admin/admin_dashboard', [
            'total_users' => $totalUsers,
            'active_users' => $activeUsers,
            'orders' => $orders,
            'vendors' => $vendors,
            'active_vendors' => $activeVendors,
            'total_returns' => $totalReturns,
            'total_replacements' => $totalReplacements,
            'cancelled_orders' => $cancelledOrders,
            'total_sales' => $totalSales,
            'pending_withdrawals' => $pendingWithdrawals,
            'pending_products' => $pendingProducts,
        ]);
    }

    function vendors () {
        // Check if admin is logged in
        if (!Session::get('admin_logged_in')) {
            return redirect('/admin/login');
        }

        $totalUsers = DB::table('users')
                        ->where('type', 'vendor')
                        ->count();
        $activeUsers = DB::table('users')
                        ->where('type', 'vendor')
                        ->where('verified', 1)
                        ->count();
        $blocked = DB::table('users')
                        ->where('type', 'vendor')
                        ->where('status', 'blocked')
                        ->count();
        $pendding = DB::table('users')
                        ->where('type', 'vendor')
                        ->where('verified', 0)
                        ->count();
        $products = DB::table('vendor_products')
                        ->count();

        $vendors = DB::table('users')
                        ->where('type', 'vendor')
                        ->get()
                        ->toArray();

        // Prefetch per-vendor data with grouped queries (avoids N+1 in the view),
        // each keyed by user_id.
        $vendorsBasic = DB::table('vendor_basic_info')
                        ->get()
                        ->keyBy('user_id');
        $productCounts = DB::table('vendor_products')
                        ->select('user_id', DB::raw('COUNT(*) as total'))
                        ->groupBy('user_id')
                        ->pluck('total', 'user_id')
                        ->toArray();
        $orderCounts = DB::table('orders')
                        ->select('user_id', DB::raw('COUNT(*) as total'))
                        ->groupBy('user_id')
                        ->pluck('total', 'user_id')
                        ->toArray();
        $vendorEarnings = DB::table('orders')
                        ->select('vendor_id', DB::raw('SUM(total_amount) as total'))
                        ->groupBy('vendor_id')
                        ->pluck('total', 'vendor_id')
                        ->toArray();

        return view('Admin/vendor_management', compact([
            'totalUsers',
            'activeUsers',
            'blocked',
            'pendding',
            'products',
            'vendors',
            'vendorsBasic',
            'productCounts',
            'orderCounts',
            'vendorEarnings'
        ]));
    }

    function products() {
        // Check if admin is logged in
        if (!Session::get('admin_logged_in')) {
            return redirect('/admin/login');
        }

        $total = DB::table('vendor_products')
                        ->count();
        $pending = DB::table('vendor_products')
                        ->where('position', 'pending')
                        ->count();
        $approved = DB::table('vendor_products')
                        ->where('position', 'approved')
                        ->count();
        $rejected = DB::table('vendor_products')
                        ->where('position', 'rejected')
                        ->count();
        $out = DB::table('vendor_products')
                        ->where('quantity', 0)
                        ->count();

        $products = DB::table('vendor_products')
                ->orderBy('created_at', 'desc')
                ->get()
                ->toArray();
                
        $autoProducts = DB::table('auto_parts_products')
                ->orderBy('created_at', 'desc')
                ->get()
                ->toArray();

        // Prefetch store names once (avoids one query per table row in the view).
        $storeNames = DB::table('vendor_basic_info')
                ->pluck('store_name', 'user_id')
                ->toArray();

        return view('Admin/product_management', compact([
            'total', 'pending', 'approved', 'rejected', 'out',
            'products', 'autoProducts', 'storeNames'
        ]));
    }

    function categorySuggestions() {
        // Check if admin is logged in
        if (!Session::get('admin_logged_in')) {
            return redirect('/admin/login');
        }

        $suggestions = DB::table('category_suggestions as cs')
            ->leftJoin('users as u', 'cs.user_id', '=', 'u.user_id')
            ->leftJoin('vendor_products as p', 'cs.product_id', '=', 'p.id')
            ->select('cs.*', 'u.full_name as vendor_name', 'u.email as vendor_email', 'p.name as product_name')
            ->orderByDesc('cs.created_at')
            ->get();

        $pendingCount = $suggestions->where('status', 'pending')->count();

        return view('Admin/category_suggestions', compact('suggestions', 'pendingCount'));
    }


    function orders() {
        // Check if admin is logged in
        if (!Session::get('admin_logged_in')) {
            return redirect('/admin/login');
        }

        $total = DB::table('carts')
                        ->count();
        $pending = DB::table('carts')
                        ->where('status', null)
                        ->count();
        $shipped = DB::table('carts')
                        ->where('status', 'shipped')
                        ->count();
        $approved = DB::table('carts')
                        ->where('status', 'delivered')
                        ->count();
        $rejected = DB::table('carts')
                        ->where('status', 'cencelled')
                        ->count();
        // $out = DB::table('carts')
        //                 ->where('quantity', 0)
        //                 ->count();

        $carts = DB::table('carts')
                ->orderBy('created_at', 'desc')
                ->get()
                ->toArray();

        return view('Admin/order_management', compact([
            'total', 'pending', 'shipped', 'approved', 'rejected',
            'carts'
        ]));
    }
}