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
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ]);
        }

        // Check password
        if ($request->password == $admin->password_hash) {
            // Set session data
            Session::put('admin_logged_in', true);
            Session::put('admin_id', $admin->id);
            Session::put('admin_username', $admin->username);
            Session::put('admin_data', $admin); // Store entire admin object if needed
            
            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => $admin
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid password'
            ]);
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

        
            
        // Pass session data to view
        return view('Admin/admin_dashboard', [
            'total_users' => $totalUsers,
            'active_users' => $activeUsers,
            'orders' => $orders,
            'vendors' => $vendors,
            'active_vendors' => $activeVendors
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
        $vendorsBasic = DB::table('vendor_basic_info')
                        ->get()
                        ->toArray();
        return view('Admin/vendor_management', compact([
            'totalUsers',
            'activeUsers',
            'blocked',
            'pendding',
            'products',
            'vendors',
            'vendorsBasic'
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

        return view('Admin/product_management', compact([
            'total', 'pending', 'approved', 'rejected', 'out',
            'products'
        ]));
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