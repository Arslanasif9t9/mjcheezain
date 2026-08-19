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
        if (!$product) {
            abort(404);
        }
        // Non-approved (pending/draft/rejected) products: only the owner vendor
        // and a logged-in admin may preview them; everyone else gets a 404.
        $isOwner = $product->user_id == ($user->user_id ?? null);
        $isAdmin = (bool) session('admin_logged_in');
        if ($product->position != 'approved' && !$isOwner && !$isAdmin) {
            abort(404);
        }
        $isOwnerPreview = $product->position != 'approved';

        $vendor = VendorBasicInfo::where('user_id', $product->user_id)->first();
        $vendorUser = DB::table('users')
                        ->where('user_id', $product->user_id)
                        ->first();

        // Some vendors have no vendor_basic_info row yet — fall back to the user record
        if (!$vendor) {
            $vendor = (object) [
                'user_id' => $product->user_id,
                'full_name' => $vendorUser->full_name ?? 'MJ Cheezain Vendor',
                'profile_picture' => null,
            ];
        }
        // One query for both the gallery and the primary image (was two scans
        // of the same rows).
        $imageRows = VendorProductImage::where('product_id', $product->id)->get();
        $imageMain = $imageRows->firstWhere('is_primary', 1);
        $images = $imageRows->pluck('image_path');
            // ->whereColumn('mrp', '<', 'selling_price')
            // ->with('VendorProductImage')
            // ->select('*')
            // ->selectRaw('(selling_price - mrp) AS discount_amount')
            // ->orderByDesc('discount_amount')
            // ->take(10)

            // dd($images);
        // return view('home.index');
        $reviews = DB::table('product_ratings')
            ->join('users', 'product_ratings.customer_id', '=', 'users.user_id')
            ->where('product_ratings.product_id', $product->id)
            ->where('product_ratings.is_replacement', 0)
            ->select('product_ratings.rating', 'product_ratings.comment', 'product_ratings.created_at', 'users.full_name as customer_name')
            ->orderBy('product_ratings.created_at', 'desc')
            ->limit(5)
            ->get();

        // Count and average in one pass (was two identical WHERE clauses).
        $ratingStats = DB::table('product_ratings')
            ->where('product_id', $product->id)
            ->where('is_replacement', 0)
            ->selectRaw('COUNT(*) as review_count, AVG(rating) as avg_rating')
            ->first();

        $reviewCount = (int) ($ratingStats->review_count ?? 0);
        $avgRating = $ratingStats->avg_rating ?? null;

        return view('product', compact(
            'user', 'profile', 'dashboardPage', 'imgPath',
            'product', 'vendor', 'imageMain', 'images', 'vendorUser',
            'reviews', 'reviewCount', 'avgRating', 'isOwnerPreview'
        ));
    }

    public function loadMoreReviews(Request $request, $id)
    {
        $offset = $request->query('offset', 5);

        $reviews = DB::table('product_ratings')
            ->join('users', 'product_ratings.customer_id', '=', 'users.user_id')
            ->where('product_ratings.product_id', $id)
            ->where('product_ratings.is_replacement', 0)
            ->select('product_ratings.rating', 'product_ratings.comment', 'product_ratings.created_at', 'users.full_name as customer_name')
            ->orderBy('product_ratings.created_at', 'desc')
            ->limit(5)
            ->offset($offset)
            ->get();

        return response()->json($reviews);
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

    public function footerPage($page)
    {
        $views = [
            'about'           => 'footer/about',
            'future-vision'   => 'footer/future-vision',
            'contact-us'      => 'footer/contact-us',
            'FAQs'            => 'footer/FAQs',
            'vendor-zone'     => 'footer/vendor-zone',
            'legal-policies'  => 'footer/legal-policies',
            'privacy-policy'  => 'footer/privacy-policy',
            'cookie-policy'   => 'footer/privacy-policy',
            'disclaimer'      => 'footer/privacy-policy',
            'return-replacement' => 'footer/return-replacement',
        ];

        abort_unless(isset($views[$page]), 404);

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
                $dashboardPage = route('customer.dashboard');
            }
        }

        return view($views[$page], compact([
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

        // Primary images in ONE query keyed by product (was a query per
        // product — a vendor with 200 products cost 200 extra round-trips).
        $primaryImages = VendorProductImage::whereIn('product_id', $products->pluck('id'))
            ->where('is_primary', 1)
            ->pluck('image_path', 'product_id');

        foreach ($products as $product) {
            $product->primary_image = $primaryImages[$product->id] ?? null;
        }
        // $images = VendorProductImage::where('product_id', $product->id)->pluck('image_path');

        return view('vendor-products', compact(
            'user', 'profile', 'dashboardPage', 'imgPath', 
            'products', 'vendor',
        ));
    }

}
