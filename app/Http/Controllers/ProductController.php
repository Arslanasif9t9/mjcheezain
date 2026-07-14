<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\VendorProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    // Get all approved products (for the product-listing page)
    public function allProducts()
    {
        $products = Product::where('position', 'approved')
            ->orderByDesc('updated_at')
            ->get();

        $productIds = $products->pluck('id');
        $allImages = VendorProductImage::whereIn('product_id', $productIds)->where('is_primary', 1)->get();
        $imagesByProduct = $allImages->groupBy('product_id');

        return response()->json([
            'data' => $products,
            'images' => $imagesByProduct
        ]);
    }

    // Get products by category
    public function byCategory(Request $request)
    {
        // return response()->json([
        //     'data' => $request->name
        // ]);
        $products = Product::where('category', $request->name)
            ->where('position', 'approved')
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



    public function changeProductPosition(Request $request)
    {
        $productId = $request->input('product_id');
        $position = $request->input('position');
        $auto = $request->input('autopart');
        
        // Validate inputs
        if (!$productId || !$position) {
            return response()->json([
                'success' => false,
                'message' => 'Product ID and position are required'
            ]);
        }
        
        try {
            // Update product position
            $affected = null;
            if ($auto) {
                $affected = DB::table('auto_parts_products')
                ->where('id', $productId)
                ->update(['status' => $position]);
            }
            else {
                $affected = DB::table('vendor_products')
                ->where('id', $productId)
                ->update(['position' => $position]);
            }
            
            
            if ($affected) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product position updated successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found or no changes made'
                ]);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => \App\Support\ErrorReason::friendly($e, 'Error updating product')
            ]);
        }
    }
    
    public function deleteProduct(Request $request)
    {
        $productId = $request->input('product_id');
        $auto = $request->input('autopart');
        
        if (!$productId) {
            return response()->json([
                'success' => false,
                'message' => 'Product ID is required'
            ]);
        }
        
        try {
            // Delete product
            $deleted = null;
            if ($auto) {
                $deleted = DB::table('auto_parts_products')
                ->where('id', $productId)
                ->delete();
            }
            else {
                $deleted = DB::table('vendor_products')
                ->where('id', $productId)
                ->delete();
            }
            
            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product deleted successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ]);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => \App\Support\ErrorReason::friendly($e, 'Error deleting product')
            ]);
        }
    }
}
