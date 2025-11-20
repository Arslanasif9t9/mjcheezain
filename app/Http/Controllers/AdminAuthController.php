<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AdminAuthController extends Controller
{
    function loginForm() {
        return view('admin/login');
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
        return view('admin/admin_dashboard', [
            'total_users' => $totalUsers,
            'active_users' => $activeUsers,
            'orders' => $orders,
            'vendors' => $vendors,
            'active_vendors' => $activeVendors
        ]);
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
}