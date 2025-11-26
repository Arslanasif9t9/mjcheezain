<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function toggleFavorite(Request $request)
    {
        try {
            $user_id = Auth::id();
            $product_id = $request->product_id;

            if (!$user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            if (!$product_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product ID is required'
                ]);
            }

            // Check if already favorited
            $existingFavorite = DB::table('favorites')
                ->where('user_id', $user_id)
                ->where('product_id', $product_id)
                ->first();

            if ($existingFavorite) {
                // Remove from favorites
                DB::table('favorites')
                    ->where('user_id', $user_id)
                    ->where('product_id', $product_id)
                    ->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Product removed from favorites',
                    'is_favorite' => false
                ]);
            } else {
                // Add to favorites
                DB::table('favorites')->insert([
                    'user_id' => $user_id,
                    'product_id' => $product_id,
                    'created_at' => now()
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Product added to favorites',
                    'is_favorite' => true
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating favorites: ' . $e->getMessage()
            ], 500);
        }
    }

    public function checkFavorite($product_id)
    {
        try {
            $user_id = Auth::id();

            if (!$user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $isFavorite = DB::table('favorites')
                ->where('user_id', $user_id)
                ->where('product_id', $product_id)
                ->exists();

            return response()->json([
                'success' => true,
                'is_favorite' => $isFavorite
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error checking favorite status'
            ], 500);
        }
    }

    public function getWishlist(Request $request)
    {
        try {
            $user_id = Auth::id();

            if (!$user_id) {
                return response()->json([]);
            }

            // Get query parameters
            $sort = $request->get('sort', 'recent');
            $filter = $request->get('filter', 'all');

            // Build the query
            $query = DB::table('favorites as f')
                ->select(
                    'vp.*',
                    'f.id as fav_id',
                    'f.created_at as favorited_at',
                    'i.image_path'
                )
                ->join('vendor_products as vp', 'f.product_id', '=', 'vp.id')
                ->leftJoin('vendor_product_images as i', function($join) {
                    $join->on('vp.id', '=', 'i.product_id')
                         ->where('i.is_primary', 1);
                })
                ->where('f.user_id', $user_id);

            // Apply stock filter
            if ($filter === 'in_stock') {
                $query->where('vp.quantity', '>', 10);
            } elseif ($filter === 'out_of_stock') {
                $query->where('vp.quantity', '=', 0);
            } elseif ($filter === 'limited') {
                $query->whereBetween('vp.quantity', [1, 10]);
            }

            // Apply sorting
            switch ($sort) {
                case 'low_high':
                    $query->orderBy('vp.selling_price', 'asc');
                    break;
                case 'high_low':
                    $query->orderBy('vp.selling_price', 'desc');
                    break;
                case 'name_asc':
                    $query->orderBy('vp.name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('vp.name', 'desc');
                    break;
                default: // recent
                    $query->orderBy('f.created_at', 'desc');
                    break;
            }

            $wishlist = $query->get();

            return response()->json($wishlist);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching wishlist: ' . $e->getMessage()
            ], 500);
        }
    }
}