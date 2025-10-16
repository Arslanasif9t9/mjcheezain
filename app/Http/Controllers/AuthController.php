<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
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
                'phone' => [
                    'required',
                    'string',
                    Rule::unique('users')->where(function ($query) use ($request) {
                        return $query->where('type', $request->type);
                    }),
                ],
                'password' => 'required|min:6|confirmed',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // dd('Validation failed', $e->errors());
            return redirect('/')
            ->withErrors($e->validator)
            ->withInput();
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
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        // Optional: Update username again with ID (for prettier look)
        // $finalUsername = $firstName . $user->id;
        // $user->update(['username' => $finalUsername]);

        // Create related profile
        if ($request->type === 'vendor') {
            VendorBasicInfo::create([
                'user_id' => $user->user_id,
                'full_name' => $request->name,
                'store_name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
            ]);
        } else {
            $nameParts = explode(' ', $request->name, 2);
            $customer = CustomerProfile::create([
                'user_id' => $user->user_id,
                'first_name' => $nameParts[0],
                'last_name' => $nameParts[1] ?? '',
                'email' => $request->email,
                'phone' => $request->phone,
            ]);
            // dd($customer->email);
        }

        // Auto login
        Auth::login($user);

        // Redirect based on type
        if ($user->type === 'vendor') {
            return redirect('/vendor/dashboard')->with('success', 'Vendor account created successfully!');
        } else {
            return redirect('/customer/dashboard')->with('success', 'Customer account created successfully!');
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
