<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\VendorBasicInfo;
use App\Models\CustomerProfile;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        try {
            $validated = $request->validate([
                'type' => 'required|in:vendor,customer',
                'name' => 'required|string|max:255',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('users')->where(function ($query) use ($request) {
                        return $query->where('type', $request->type);
                    }),
                ],
                'password' => 'required|min:6',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // ✅ Return JSON validation response correctly
            return response()->json([
                'errors' => $e->validator->errors(),
                'old_input' => $request->all(),
            ], 422);
        }

        // Generate unique username
        $firstName = strtolower(Str::before($request->name, ' '));
        $tempUsername = $this->generateUniqueUsername($firstName);

        // Create user
        $user = User::create([
            'type' => $request->type,
            'full_name' => $request->name,
            'username' => $tempUsername,
            'email' => $request->email,
            'phone' => null,
            'password' => Hash::make($request->password),
        ]);

        // Create related profile
        if ($request->type === 'vendor') {
            // VendorBasicInfo::create([
            //     'user_id' => $user->user_id,
            //     'full_name' => $request->name,
            //     'store_name' => $request->name,
            //     'phone' => null,
            //     'email' => $request->email,
            // ]);

            // DB::table('vendor_store_details')->insert([
            //     'user_id' => $user->user_id,
            // ]);

            // DB::table('vendor_address')->insert([
            //     'user_id' => $user->user_id,
            // ]);
        } else {
            $nameParts = explode(' ', $request->name, 2);
            CustomerProfile::create([
                'user_id' => $user->user_id,
                'first_name' => $nameParts[0],
                'last_name' => $nameParts[1] ?? '',
                'email' => $request->email,
                'phone' => null,
            ]);
        }

        // Auto login
        Auth::login($user);

        // Return success JSON response
        if ($user->type === 'vendor') {
            return response()->json([
                'success' => true,
                'type' => 'vendor',
                'message' => 'Vendor account created successfully!',
                'redirect' => url('/vendor/dashboard'),
            ]);
        } else {
            return response()->json([
                'success' => true,
                'type' => 'customer',
                'message' => 'Customer account created successfully!',
                'redirect' => url('/customer/dashboard'),
            ]);
        }
    }

    /**
     * Generate a truly unique username
     */
    private function generateUniqueUsername($base)
    {
        $username = $base . rand(1000, 9999);

        while (User::where('username', $username)->exists()) {
            $username = $base . rand(1000, 9999);
        }

        return $username;
    }


    public function login(Request $request)
    {
        $credentials = $request->validate([
            'id' => 'required',
            'password' => 'required',
        ]);

        
        $user = User::where('email', $credentials['id'])
        // ->orWhere('username', $credentials['id'])
        ->where('type', $request->type) // Add type condition
        ->first();
        // dd($request->type, $user->type);
        // dd(Hash::check($credentials['password'], $user->password));

        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user);

            if ($user->type === 'vendor') {
                return redirect('/vendor/dashboard');
            } else {
                return redirect('/customer/dashboard');
            }
        }

        return back()->withErrors(['loginError' => 'Invalid email or password.']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/')->with('success', 'Logged out successfully.');
    }
}
