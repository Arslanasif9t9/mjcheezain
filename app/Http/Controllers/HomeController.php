<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function signup(Request $request)
    {
        $request->validate([
            'type' => 'required|in:vendor,customer',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|min:6|confirmed',
        ]);

        try {
            DB::beginTransaction();

            $user = DB::table('users')->insertGetId([
                'type' => $request->type,
                'full_name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Generate username
            $firstName = strtolower(preg_replace('/[^a-zA-Z]/', '', explode(' ', trim($request->name))[0]));
            $username = $firstName . $user;

            DB::table('users')->where('user_id', $user)->update(['username' => $username]);

            if ($request->type == "vendor") {
                DB::table('vendor_basic_info')->insert([
                    'user_id' => $user,
                    'full_name' => $request->name,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                DB::table('vendor_balance')->insert([
                    'user_id' => $user,
                    'total_balance' => 0.00,
                    'created_at' => now(),
                ]);
                
                return redirect('/vendor/edit-profile');
            } else {
                $nameParts = explode(' ', $request->name, 2);
                $firstName = $nameParts[0];
                $lastName = isset($nameParts[1]) ? $nameParts[1] : '';

                DB::table('customer_profile')->insert([
                    'user_id' => $user,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                return redirect('/customer/edit-profile');
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Database error: ' . $e->getMessage());
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'password' => 'required',
        ]);

        $user = DB::table('users')
            ->where('username', $request->id)
            ->orWhere('email', $request->id)
            ->first();

        if ($user && Hash::check($request->password, $user->password)) {
            session([
                'user_id' => $user->user_id,
                'username' => $user->username,
                'email' => $user->email,
                'type' => $user->type,
            ]);

            if ($user->type == "vendor") {
                return redirect('/vendor/dashboard');
            } else {
                return redirect('/customer/dashboard');
            }
        }

        return back()->with('loginError', 'Invalid credentials');
    }
}