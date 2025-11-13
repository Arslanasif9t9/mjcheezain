<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\VendorProductImage;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Get products with biggest savings
    public function biggestSavings()
    {
        $products = Product::where('position', 'approved')
            ->whereColumn('mrp', '<', 'selling_price')
            ->with('primaryImage')
            ->select('*')
            ->selectRaw('(selling_price - mrp) AS discount_amount')
            ->orderByDesc('discount_amount')
            ->take(10)
            ->get();

        return view('products.biggest-savings', compact('products'));
    }

    // Get products by category
    public function byCategory(Request $request)
    {
        // return response()->json([
        //     'data' => $request->name
        // ]);
        $products = Product::where('category', $request->name)
            ->where('position', 'pending')
            ->orderByDesc('updated_at')
            ->get();

        // Get all product IDs
        $productIds = $products->pluck('id');

        // Get all images for these products
        $allImages = VendorProductImage::whereIn('product_id', $productIds)->where('is_primary', 1)->get();

        // Group images by product_id for easy access
        $imagesByProduct = $allImages->groupBy('product_id');

        return response()->json([
            'data' => $products,
            'images' => $imagesByProduct
        ]);
        // return view('products.category', compact('products', 'category'));
    }
}
