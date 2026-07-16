<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function searchProductsOld(Request $request)
    {
        $searchTerm = $request->input('search', '');
        $category = $request->input('category', 'All Categories');
        $page = $request->input('page', 1);
        $perPage = 50;

        $query = DB::table('vendor_products as vp')
            ->leftJoin('vendor_product_images as vpi', function ($join) {
                $join->on('vp.id', '=', 'vpi.product_id')
                     ->where('vpi.is_primary', '=', 1);
            })
            ->where('vp.position', '=', 'approved');

        if (!empty($searchTerm)) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('vp.name', 'like', "%{$searchTerm}%")
                  ->orWhere('vp.description', 'like', "%{$searchTerm}%")
                  ->orWhere('vp.brand', 'like', "%{$searchTerm}%");
            });
        }

        if ($category !== 'All Categories') {
            $query->where('vp.category', '=', $category);
        }

        $products = $query
            ->select('vp.id', 'vp.name', 'vp.mrp', 'vp.selling_price', 'vp.updated_at', DB::raw('COALESCE(vpi.image_path, "img/default_img.png") as image'))
            ->orderByDesc('vp.updated_at')
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        return response()->json([
            'products' => $products,
            'page' => $page,
            'hasMore' => count($products) === $perPage,
        ]);
    }

    public function searchProducts(Request $request)
    {
        $request->validate([
            'q' => 'nullable|string|min:2|max:100',
        ]);
        
        $searchTerm = $request->input('q', '');
        $searchPattern = '%' . $searchTerm . '%';
        
        // return response()->json([
        //         'error' => 'Database error'
        // ]);
        try {
            $products = DB::table('vendor_products as vp')
                ->select([
                    'vp.id',
                    'vp.name',
                    'vp.category',
                    'vp.subcategory',
                    'vp.quantity',
                    'vp.brand',
                    'vp.model',
                    'vp.pcondition',
                    'vp.original_price',
                    'vp.delivery_charges',
                    'vp.selling_price',
                    'vp.mrp',
                    'vp.shipping_method',
                    'vp.shipping_time',
                    'vp.description',
                    'vp.location',
                    'vp.made_in',
                    'vp.return_policy',
                    'vp.status',
                    'vp.rating',
                    'vp.video',
                    'vp.created_at',
                    // Join with vendor_product_images to get primary image
                    'vpi.image_path as primary_image',
                    'vpi.is_primary'
                ])
                ->leftJoin('vendor_product_images as vpi', function($join) {
                    $join->on('vp.id', '=', 'vpi.product_id')
                        ->where('vpi.is_primary', '=', 1);
                })
                // Delivered-cart count per product (units sold) for quality ordering
                ->leftJoinSub(
                    DB::table('carts')
                        ->select('product_id', DB::raw('COUNT(*) as sold_count'))
                        ->where('status', 'delivered')
                        ->groupBy('product_id'),
                    'sold',
                    function ($join) {
                        $join->on('sold.product_id', '=', 'vp.id');
                    }
                )
                ->where('vp.position', 'approved')
                ->where(function ($query) use ($searchPattern) {
                    $query->where('vp.name', 'like', $searchPattern)
                        ->orWhere('vp.category', 'like', $searchPattern)
                        ->orWhere('vp.subcategory', 'like', $searchPattern)
                        ->orWhere('vp.brand', 'like', $searchPattern)
                        ->orWhere('vp.model', 'like', $searchPattern)
                        ->orWhere('vp.description', 'like', $searchPattern);
                })
                ->orderByRaw("
                    CASE 
                        WHEN vp.name LIKE ? THEN 1
                        WHEN vp.name LIKE ? THEN 2
                        WHEN vp.category LIKE ? THEN 3
                        ELSE 4
                    END
                ", [
                    $searchTerm . '%',
                    $searchPattern,
                    $searchPattern
                ])
                ->orderBy('vp.rating', 'desc')
                ->orderByRaw('COALESCE(sold.sold_count, 0) DESC')
                ->orderBy('vp.created_at', 'desc')
                ->limit(50)
                ->get();

            return response()->json($products);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Database error',
                'message' => \App\Support\ErrorReason::friendly($e, 'Search failed')
            ], 500);
        }
        
    }
}
