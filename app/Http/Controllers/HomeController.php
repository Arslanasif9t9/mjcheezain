<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Product;
use App\Models\VendorBasicInfo;
use App\Models\VendorProductImage;
use App\Models\Subscription;

class HomeController extends Controller
{
    public function index()
    {
        $user = null;
        $profile = null;
        $dashboardPage = null;
        $imgPath = 'img/default_profile.webp';
        
        if (Auth::check()) {
            $user = Auth::user();
            
            if ($user->type == 'vendor') {
                $profile = $user->vendorProfile;
                $imgPath = $profile && $profile->profile_picture 
                ? "vendor/profile/{$profile->profile_picture}" 
                : "img/default_profile.webp";
                $dashboardPage = route('vendor.dashboard');
            } else {
                $profile = $user->customerProfile;
                $imgPath = $profile && $profile->profile_image 
                ? "customer/profile/{$profile->profile_image}" 
                : "img/default_profile.webp";
                // dd($profile->profile_image);  
                $dashboardPage = route('customer.dashboard');
            }
        }

        // 👉 Add this for products
        $products = [];
        // $products = Product::where('position', 'approved')
        //     ->whereColumn('mrp', '<', 'selling_price')
        //     ->with('primaryImage')
        //     ->select('*')
        //     ->selectRaw('(selling_price - mrp) AS discount_amount')
        //     ->orderByDesc('discount_amount')
        //     ->take(10)
        //     ->get();

        // return view('home.index');
        return view('home.index', compact('user', 'profile', 'dashboardPage', 'imgPath', 'products'));
    }

    public function product($id)
    {
        $user = null;
        $profile = null;
        $dashboardPage = null;
        $imgPath = 'img/default_profile.webp';
        
        if (Auth::check()) {
            $user = Auth::user();
            
            if ($user->type == 'vendor') {
                $profile = $user->vendorProfile;
                $imgPath = $profile && $profile->profile_picture 
                ? "vendor/profile/{$profile->profile_picture}" 
                : "img/default_profile.webp";
                $dashboardPage = route('vendor.dashboard');
            } else {
                $profile = $user->customerProfile;
                $imgPath = $profile && $profile->profile_image 
                ? "customer/profile/{$profile->profile_image}" 
                : "img/default_profile.webp";
                // dd($profile->profile_image);  
                $dashboardPage = route('customer.dashboard');
            }
        }

        // 👉 Add this for products
        $product = Product::where('id', $id)->first();
        if($product->position != 'approved' && $product->user_id != $user->user_id) {
            return 'blocked';
        }
        $vendor = VendorBasicInfo::where('user_id', $product->user_id)->first();
        $vendorUser = DB::table('users')
                        ->where('user_id', $product->user_id)
                        ->first();
        $imageMain = VendorProductImage::where('product_id', $product->id)
                    ->where('is_primary', 1)
                    ->first();
        $images = VendorProductImage::where('product_id', $product->id)->pluck('image_path');
            // ->whereColumn('mrp', '<', 'selling_price')
            // ->with('VendorProductImage')
            // ->select('*')
            // ->selectRaw('(selling_price - mrp) AS discount_amount')
            // ->orderByDesc('discount_amount')
            // ->take(10)

            // dd($images);
        // return view('home.index');
        return view('product', compact(
            'user', 'profile', 'dashboardPage', 'imgPath', 
            'product', 'vendor', 'imageMain', 'images', 'vendorUser'
        ));
    }

    public function productList() {
        $user = null;
        $profile = null;
        $dashboardPage = null;
        $imgPath = 'img/default_profile.webp';
        
        if (Auth::check()) {
            $user = Auth::user();
            
            if ($user->type == 'vendor') {
                $profile = $user->vendorProfile;
                $imgPath = $profile && $profile->profile_picture 
                ? "vendor/profile/{$profile->profile_picture}" 
                : "img/default_profile.webp";
                $dashboardPage = route('vendor.dashboard');
            } else {
                $profile = $user->customerProfile;
                $imgPath = $profile && $profile->profile_image 
                ? "customer/profile/{$profile->profile_image}" 
                : "img/default_profile.webp";
                // dd($profile->profile_image);  
                $dashboardPage = route('customer.dashboard');
            }
        }

        return view('brands/product-listing', compact([
            'user', 'profile', 'dashboardPage', 'imgPath'
        ]));
    }

    public function cosmetics() {
        $user = null;
        $profile = null;
        $dashboardPage = null;
        $imgPath = 'img/default_profile.webp';
        
        if (Auth::check()) {
            $user = Auth::user();
            
            if ($user->type == 'vendor') {
                $profile = $user->vendorProfile;
                $imgPath = $profile && $profile->profile_picture 
                ? "vendor/profile/{$profile->profile_picture}" 
                : "img/default_profile.webp";
                $dashboardPage = route('vendor.dashboard');
            } else {
                $profile = $user->customerProfile;
                $imgPath = $profile && $profile->profile_image 
                ? "customer/profile/{$profile->profile_image}" 
                : "img/default_profile.webp";
                // dd($profile->profile_image);  
                $dashboardPage = route('customer.dashboard');
            }
        }

        return view('brands/cosmetics', compact([
            'user', 'profile', 'dashboardPage', 'imgPath'
        ]));
    }

    public function subscribe(Request $request)
    {
        // Manual validation
        // $validator = Validator::make($request->all(), [
            //     'email' => 'required|email|max:255'
            // ]);

        // if ($validator->fails()) {
        //     return response()->json([
        //         'message' => 'Invalid email address',
        //         'errors' => $validator->errors(),
        //         'success' => false
        //     ], 422);
        // }
        
        try {
            $email = $request->email;
            
            // Check if email already exists
            if (Subscription::find($email)) {
                return response()->json([
                    'message' => 'This email is already subscribed!',
                    'success' => false
                ], 409);
            }
            
            // return response()->json([
            //     'success' => false
            // ]);
            // Create new subscription
            $subscription = Subscription::create([
                'email' => $email
            ]);

            return response()->json([
                'email' => $subscription->email,
                'message' => 'Subscription successful!',
                'success' => true
            ]);

        } catch (\Illuminate\Database\QueryException $e) {
            // Handle duplicate entry
            if (str_contains($e->getMessage(), 'Duplicate entry') || $e->getCode() == 23000) {
                return response()->json([
                    'message' => 'This email is already subscribed!',
                    'success' => false
                ], 409);
            }

            Log::error('Database error in subscription: ' . $e->getMessage());
            return response()->json([
                'message' => 'Subscription failed. Please try again.',
                'success' => false
            ], 500);

        } catch (\Exception $e) {
            Log::error('Subscription error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Subscription failed. Please try again.',
                'success' => false
            ], 500);
        }
    }

    public function vendorProducts($id) {
        $user = null;
        $profile = null;
        $dashboardPage = null;
        $imgPath = 'img/default_profile.webp';
        
        if (Auth::check()) {
            $user = Auth::user();
            
            if ($user->type == 'vendor') {
                $profile = $user->vendorProfile;
                $imgPath = $profile && $profile->profile_picture 
                ? "vendor/profile/{$profile->profile_picture}" 
                : "img/default_profile.webp";
                $dashboardPage = route('vendor.dashboard');
            } else {
                $profile = $user->customerProfile;
                $imgPath = $profile && $profile->profile_image 
                ? "customer/profile/{$profile->profile_image}" 
                : "img/default_profile.webp";
                // dd($profile->profile_image);  
                $dashboardPage = route('customer.dashboard');
            }
        }

         // 👉 Add this for products
        $products = Product::where('user_id', $id)->get();
        $vendor = VendorBasicInfo::where('user_id', $id)->first();
        foreach ($products as $product) {
            $productImage = VendorProductImage::where('product_id', $product->id)
                                            ->where('is_primary', 1)
                                            ->first();
            $product->primary_image = $productImage ? $productImage->image_path : null;
        }
        // $images = VendorProductImage::where('product_id', $product->id)->pluck('image_path');

        return view('vendor-products', compact(
            'user', 'profile', 'dashboardPage', 'imgPath', 
            'products', 'vendor',
        ));
    }

}
