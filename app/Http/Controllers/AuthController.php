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
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->email;
        
        // Generate 4-digit OTP
        $otp = rand(1000, 9999);
        
        // Store OTP in cache for 10 minutes
        Cache::put('otp_' . $email, $otp, 600);
        // dd($otp);
        
        try {
            // Send OTP via email
            Mail::send('emails.otp', ['otp' => $otp], function($message) use ($email) {
                $message->to($email)
                        ->subject('Your OTP Code');
            });
            
            return response()->json([
                'message' => 'OTP sent successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to send OTP'
            ], 500);
        }
    }

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
                'otp' => 'required|digits:4',
                'password' => 'required|min:6',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // ✅ Return JSON validation response correctly
            return response()->json([
                'errors' => $e->validator->errors(),
                'old_input' => $request->all(),
            ], 422);
        }

        // Verify OTP
        $cachedOtp = Cache::get('otp_' . $request->email);
        
        if (!$cachedOtp || $cachedOtp != $request->otp) {
            return response()->json([
                'message' => 'Invalid or expired OTP'
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
        // return response()->json($request);

        
        $user = User::where('email', $credentials['id'])
        ->where('type', $request->type) // Add type condition
        ->first();
        // ->orWhere('username', $credentials['id'])
        // dd($request->type, $user->type);
        // dd(Hash::check($credentials['password'], $user->password));

        if ($user && Hash::check($credentials['password'], $user->password)) {
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

        return response()->json([
            'errors' => [
                'loginError' => 'Invalid email or password.'
            ]
        ], 401);
    }









    
    public function sendPasswordResetOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $email = $request->email;
        
        // Generate 4-digit OTP
        $otp = rand(1000, 9999);
        
        // Store OTP in cache for 10 minutes
        Cache::put('password_reset_otp_' . $email, $otp, 600);
        
        try {
            // Send OTP via email
            Mail::send('emails.otp', ['otp' => $otp], function($message) use ($email) {
                $message->to($email)
                        ->subject('Password Reset OTP');
            });
            
            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP'
            ], 500);
        }
    }

    public function verifyPasswordResetOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:4'
        ]);

        $cachedOtp = Cache::get('password_reset_otp_' . $request->email);
        
        if (!$cachedOtp || $cachedOtp != $request->otp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP'
            ], 422);
        }

        // Store verification in cache for password reset
        Cache::put('password_reset_verified_' . $request->email, true, 600);

        return response()->json([
            'success' => true,
            'message' => 'OTP verified successfully'
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed'
        ]);

        // Check if OTP was verified
        if (!Cache::get('password_reset_verified_' . $request->email)) {
            return response()->json([
                'success' => false,
                'message' => 'OTP verification required'
            ], 422);
        }

        // Update password
        $user = User::where('email', $request->email)
        // ->where('type', $request->type) // Add type condition
        ->first();
        // return response()->json($request->type);
        $user->password = Hash::make($request->password);
        $user->save();

        // Clear cache
        Cache::forget('password_reset_otp_' . $request->email);
        Cache::forget('password_reset_verified_' . $request->email);

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully'
        ]);
    }





    public function logout()
    {
        Auth::logout();
        return redirect('/')->with('success', 'Logged out successfully.');
    }
}
