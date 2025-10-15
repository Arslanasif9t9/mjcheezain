<?php

namespace App\Http\Controllers;

use App\Models\Product;
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
    public function byCategory($category)
    {
        $products = Product::where('category', $category)
            ->where('position', 'approved')
            ->with('primaryImage')
            ->orderByDesc('updated_at')
            ->get();

        return view('products.category', compact('products', 'category'));
    }
}
