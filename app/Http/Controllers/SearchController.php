<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function searchProducts(Request $request)
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
}
