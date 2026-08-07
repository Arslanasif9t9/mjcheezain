<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\VendorBasicInfo;

class JapanController extends Controller
{
    public function show($id)
    {
        try {
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

            $product = DB::table('japan_products')
                ->where('id', $id)
                ->where('status', 'approved')
                ->first();

            if (!$product) {
                abort(404);
            }

            $imageUrl = $product->image ? $product->image : asset('img/no-image.png');

            $productData = [
                'id' => $product->id,
                'vendor_id' => $product->vendor_id,
                'name' => $product->product_name,
                'category' => 'Japan',
                'category_id' => null,
                'subcategory' => '',
                'subcategory_id' => null,
                'part_type' => '',
                'brand' => $product->brand,
                'model' => $product->model,
                'made_in' => $product->made_in,
                'condition' => $product->conditionp,
                'price' => (float) $product->selling_price,
                'original_price' => $product->mrp ? (float) $product->mrp : null,
                'quantity' => $product->quantity,
                'description' => $product->description,
                'shipping_method' => '',
                'shipping_time' => '',
                'return_policy' => '',
                'location' => '',
                'images' => [['id' => 0, 'url' => $imageUrl, 'is_primary' => 1]],
                'video' => null,
                'faults' => [],
            ];

            $vendor = VendorBasicInfo::where('user_id', $product->vendor_id)->first();
            if (!$vendor) {
                $vendor = (object) [
                    'full_name' => 'MJ Cheezain Vendor',
                    'verified' => 0,
                    'profile_picture' => null,
                    'user_id' => $product->vendor_id,
                ];
            }
            $vendorUser = DB::table('users')->where('user_id', $product->vendor_id)->first();

            return view('product-auto-japan', compact(
                'user', 'profile', 'dashboardPage', 'imgPath',
                'productData', 'vendor', 'vendorUser'
            ));
        } catch (\Exception $e) {
            throw $e;
        }
    }

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
            }
        }

        return view('brands.japan', compact('user', 'profile', 'dashboardPage', 'imgPath'));
    }

    public function apiProducts(Request $request)
    {
        try {
            $query = DB::table('japan_products')
                ->where('status', 'approved')
                ->select(
                    'id',
                    'product_name as name',
                    'brand',
                    'model',
                    'selling_price as price',
                    'mrp',
                    'image',
                    'quantity',
                    'description'
                );

            if ($request->filled('brand')) {
                $query->where('brand', $request->brand);
            }

            $products = $query->orderBy('id', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $products,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function apiFilters()
    {
        try {
            $brands = DB::table('japan_products')
                ->where('status', 'approved')
                ->whereNotNull('brand')
                ->select('brand', DB::raw('COUNT(*) as product_count'))
                ->groupBy('brand')
                ->orderBy('brand')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'categories' => [],
                    'subcategories' => [],
                    'brands' => $brands,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}