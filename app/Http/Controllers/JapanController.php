<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class JapanController extends Controller
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