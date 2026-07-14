<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\VendorBasicInfo;

class AutoPartsProductShowController extends Controller
{
    /**
     * Display public auto parts listing page
     */
    public function publicIndex()
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

        // return view('home.index', compact('user', 'profile', 'dashboardPage', 'imgPath', 'products'));
        
        
        // Get all categories for filters
        $categories = DB::table('auto_parts_categories')
            ->orderBy('category_name')
            ->get();
        
        // Get all brands (distinct from products)
        $brands = DB::table('auto_parts_products')
            ->select('brand')
            ->distinct()
            ->orderBy('brand')
            ->get();
        
        return view('brands.autoparts', compact('user', 'profile', 'dashboardPage', 'imgPath', 'categories', 'brands'));
    }

    /**
     * API endpoint to get filtered products
     */
   public function apiProducts(Request $request)
{
    try {
        // Base query
        $query = DB::table('auto_parts_products as p')
            ->join('auto_parts_categories as c', 'p.category_id', '=', 'c.id')
            ->join('auto_parts_subcategories as s', 'p.subcategory_id', '=', 's.id')
            ->leftJoin('auto_parts_product_images as i', function($join) {
                $join->on('p.id', '=', 'i.product_id')
                     ->on('i.sort_order', '=', DB::raw('0'));
            })
            ->where('p.status', 'approved')
            ->select(
                'p.id',
                'p.vendor_product_id',
                'p.product_name as name',
                'p.brand',
                'p.model',
                'p.part_type',
                'p.selling_price as price',
                'p.mrp as original_price',
                'p.conditionp as condition',
                'p.quantity',
                'p.description',
                'p.location',
                'p.shipping_method',
                'p.shipping_time',
                'p.return_policy',
                'p.made_in',
                'c.id as category_id',
                'c.category_name as category',
                'c.category_slug',
                's.id as subcategory_id',
                's.subcategory_name as subcategory',
                'i.image_path as primary_image'
            );

        // Check if we need all products (for frontend filtering)
        if ($request->has('all') && $request->all == 1) {
            $products = $query->get();

            foreach ($products as $product) {
                if ($product->primary_image) {
                    $product->image = asset('storage/vendor/products/images/' . $product->primary_image);
                } else {
                    $product->image = asset('images/default-product.jpg');
                }
                $product->price = (float)$product->price;
                $product->original_price = $product->original_price ? (float)$product->original_price : null;
                unset($product->primary_image);
            }

            return response()->json([
                'success' => true,
                'data' => $products
            ]);
        }

        // Apply filters
        if ($request->has('category') && !empty($request->category)) {
            $categories = is_array($request->category)
                ? $request->category
                : explode(',', $request->category);
            $query->whereIn('c.category_slug', $categories);
        }

        if ($request->has('subcategory') && !empty($request->subcategory)) {
            $subcategories = is_array($request->subcategory)
                ? $request->subcategory
                : explode(',', $request->subcategory);
            $query->whereIn('s.id', $subcategories);
        }

        if ($request->has('brand') && !empty($request->brand)) {
            $brands = is_array($request->brand)
                ? $request->brand
                : explode(',', $request->brand);
            $query->whereIn('p.brand', $brands);
        }

        if ($request->has('condition') && !empty($request->condition)) {
            $conditions = is_array($request->condition)
                ? $request->condition
                : explode(',', $request->condition);
            $query->whereIn('p.conditionp', $conditions);
        }

        if ($request->has('price_min') && $request->price_min !== '') {
            $query->where('p.selling_price', '>=', $request->price_min);
        }
        if ($request->has('price_max') && $request->price_max !== '') {
            $query->where('p.selling_price', '<=', $request->price_max);
        }

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('p.product_name', 'LIKE', "%{$search}%")
                  ->orWhere('p.brand', 'LIKE', "%{$search}%")
                  ->orWhere('p.model', 'LIKE', "%{$search}%")
                  ->orWhere('p.part_type', 'LIKE', "%{$search}%")
                  ->orWhere('c.category_name', 'LIKE', "%{$search}%")
                  ->orWhere('s.subcategory_name', 'LIKE', "%{$search}%");
            });
        }

        $sort = $request->get('sort', 'default');
        switch($sort) {
            case 'price-low':
                $query->orderBy('p.selling_price', 'asc');
                break;
            case 'price-high':
                $query->orderBy('p.selling_price', 'desc');
                break;
            case 'name-asc':
                $query->orderBy('p.product_name', 'asc');
                break;
            case 'name-desc':
                $query->orderBy('p.product_name', 'desc');
                break;
            default:
                $query->orderBy('p.created_at', 'desc');
        }

        $perPage = $request->get('per_page', 12);
        $products = $query->paginate($perPage);

        foreach ($products as $product) {
            if ($product->primary_image) {
                $product->image = asset('storage/vendor/products/images/' . $product->primary_image);
            } else {
                $product->image = asset('images/default-product.jpg');
            }
            $product->price = (float)$product->price;
            $product->original_price = $product->original_price ? (float)$product->original_price : null;
            unset($product->primary_image);
        }

        return response()->json([
            'success' => true,
            'data' => $products->items(),
            'total' => $products->total(),
            'current_page' => $products->currentPage(),
            'last_page' => $products->lastPage(),
            'per_page' => $products->perPage()
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => \App\Support\ErrorReason::friendly($e, 'Error fetching products')
        ], 500);
    }
}

    /**
     * API endpoint to get filter options
     */
    public function apiFilters(Request $request)
    {
        try {
            // Get categories with counts
            $categories = DB::table('auto_parts_categories as c')
                ->join('auto_parts_products as p', 'c.id', '=', 'p.category_id')
                ->where('p.status', 'approved')
                ->select(
                    'c.id',
                    'c.category_name as name',
                    'c.category_slug as slug',
                    DB::raw('COUNT(p.id) as product_count')
                )
                ->groupBy('c.id', 'c.category_name', 'c.category_slug')
                ->orderBy('c.category_name')
                ->get();

            // Get subcategories with counts
            $subcategoriesQuery = DB::table('auto_parts_subcategories as s')
                ->join('auto_parts_products as p', 's.id', '=', 'p.subcategory_id')
                ->where('p.status', 'approved')
                ->select(
                    's.id',
                    's.subcategory_name as name',
                    's.category_id',
                    DB::raw('COUNT(p.id) as product_count')
                )
                ->groupBy('s.id', 's.subcategory_name', 's.category_id')
                ->orderBy('s.subcategory_name');

            // If category filter is applied, filter subcategories
            if ($request->has('category') && !empty($request->category)) {
                $categories = is_array($request->category) 
                    ? $request->category 
                    : explode(',', $request->category);
                
                $categoryIds = DB::table('auto_parts_categories')
                    ->whereIn('category_slug', $categories)
                    ->pluck('id');
                
                $subcategoriesQuery->whereIn('s.category_id', $categoryIds);
            }

            $subcategories = $subcategoriesQuery->get();

            // Get brands with counts
            $brands = DB::table('auto_parts_products')
                ->where('status', 'approved')
                ->select('brand', DB::raw('COUNT(*) as product_count'))
                ->whereNotNull('brand')
                ->groupBy('brand')
                ->orderBy('brand')
                ->get();

            // Get conditions with counts (using conditionp column)
            $conditions = DB::table('auto_parts_products')
                ->where('status', 'approved')
                ->select('conditionp as condition', DB::raw('COUNT(*) as product_count')) // Changed from condition to conditionp
                ->whereNotNull('conditionp')
                ->groupBy('conditionp')
                ->orderBy('conditionp')
                ->get();

            // Get price ranges
            $priceStats = DB::table('auto_parts_products')
                ->where('status', 'approved')
                ->select(
                    DB::raw('MIN(selling_price) as min_price'),
                    DB::raw('MAX(selling_price) as max_price')
                )
                ->first();

            return response()->json([
                'success' => true,
                'data' => [
                    'categories' => $categories,
                    'subcategories' => $subcategories,
                    'brands' => $brands,
                    'conditions' => $conditions,
                    'price_range' => [
                        'min' => floor(($priceStats->min_price ?? 0) / 100) * 100,
                        'max' => ceil(($priceStats->max_price ?? 100000) / 100) * 100
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => \App\Support\ErrorReason::friendly($e, 'Error fetching filters')
            ], 500);
        }
    }

    /**
     * Get single product details
     */
    public function publicShow($id)
    {
        try {
            // Get product details
            $product = DB::table('auto_parts_products as p')
                ->join('auto_parts_categories as c', 'p.category_id', '=', 'c.id')
                ->join('auto_parts_subcategories as s', 'p.subcategory_id', '=', 's.id')
                ->where('p.id', $id)
                ->where('p.status', 'approved')
                ->select(
                    'p.*',
                    'c.category_name',
                    'c.category_slug',
                    's.subcategory_name',
                    's.dropdown_type'
                )
                ->first();

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }

            // Get all images
            $images = DB::table('auto_parts_product_images')
                ->where('product_id', $id)
                ->orderBy('sort_order')
                ->get()
                ->map(function($image) {
                    return [
                        'id' => $image->id,
                        'url' => asset('storage/' . $image->image_path),
                        'is_primary' => $image->is_primary
                    ];
                });

            // Get video
            $video = DB::table('auto_parts_product_videos')
                ->where('product_id', $id)
                ->first();
            
            $videoUrl = $video ? asset('storage/' . $video->video_path) : null;

            // Get faults if any
            $faults = DB::table('auto_parts_product_faults')
                ->where('product_id', $id)
                ->get()
                ->map(function($fault) {
                    return [
                        'description' => $fault->fault_description,
                        'image' => $fault->fault_image ? asset('storage/' . $fault->fault_image) : null
                    ];
                });

            // Format response
            $productData = [
                'id' => $product->id,
                'name' => $product->product_name,
                'category' => $product->category_name,
                'category_id' => $product->category_id,
                'subcategory' => $product->subcategory_name,
                'subcategory_id' => $product->subcategory_id,
                'part_type' => $product->part_type,
                'brand' => $product->brand,
                'model' => $product->model,
                'made_in' => $product->made_in,
                'condition' => $product->conditionp, // Changed from condition to conditionp
                'price' => (float)$product->selling_price,
                'original_price' => $product->mrp ? (float)$product->mrp : null,
                'quantity' => $product->quantity,
                'description' => $product->description,
                'shipping_method' => $product->shipping_method,
                'shipping_time' => $product->shipping_time,
                'return_policy' => $product->return_policy,
                'location' => $product->location,
                'images' => $images,
                'video' => $videoUrl,
                'faults' => $faults
            ];

            return response()->json([
                'success' => true,
                'data' => $productData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => \App\Support\ErrorReason::friendly($e, 'Error fetching product')
            ], 500);
        }
    }
    
    
    
    public function singleShow($id) {
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
                // dd($profile->profile_image);  
                $dashboardPage = route('customer.dashboard');
            }
        }
            // Get product details
            $product = DB::table('auto_parts_products as p')
                ->join('auto_parts_categories as c', 'p.category_id', '=', 'c.id')
                ->join('auto_parts_subcategories as s', 'p.subcategory_id', '=', 's.id')
                ->where('p.id', $id)
                ->where('p.status', 'approved')
                ->select(
                    'p.*',
                    'c.category_name',
                    'c.category_slug',
                    's.subcategory_name',
                    's.dropdown_type'
                )
                ->first();

            if (!$product) {
                abort(404);
            }

            // Get all images
            $images = DB::table('auto_parts_product_images')
                ->where('product_id', $id)
                ->orderBy('sort_order')
                ->get()
                ->map(function($image) {
                    return [
                        'id' => $image->id,
                        'url' => asset($image->image_path),
                        'is_primary' => $image->is_primary
                    ];
                });

            // Get video
            $video = DB::table('auto_parts_product_videos')
                ->where('product_id', $id)
                ->first();
            
            $videoUrl = $video ? asset('storage/' . $video->video_path) : null;

            // Get faults if any
            $faults = DB::table('auto_parts_product_faults')
                ->where('product_id', $id)
                ->get()
                ->map(function($fault) {
                    return [
                        'description' => $fault->fault_description,
                        'image' => $fault->fault_image ? asset($fault->fault_image) : null
                    ];
                });

            // Format response
            $productData = [
                'id' => $product->id,
                'vendor_id' => $product->vendor_id,
                'name' => $product->product_name,
                'category' => $product->category_name,
                'category_id' => $product->category_id,
                'subcategory' => $product->subcategory_name,
                'subcategory_id' => $product->subcategory_id,
                'part_type' => $product->part_type,
                'brand' => $product->brand,
                'model' => $product->model,
                'made_in' => $product->made_in,
                'condition' => $product->conditionp, // Changed from condition to conditionp
                'price' => (float)$product->selling_price,
                'original_price' => $product->mrp ? (float)$product->mrp : null,
                'quantity' => $product->quantity,
                'description' => $product->description,
                'shipping_method' => $product->shipping_method,
                'shipping_time' => $product->shipping_time,
                'return_policy' => $product->return_policy,
                'location' => $product->location,
                'images' => $images,
                'video' => $videoUrl,
                'faults' => $faults
            ];
            // dd($product->vendor_id);
            
            $vendor = VendorBasicInfo::where('user_id', $product->vendor_id)->first();
            $vendorUser = DB::table('users')
                            ->where('user_id', $product->vendor_id)
                            ->first();
            
            return view('product-auto', compact(
                'user', 'profile', 'dashboardPage', 'imgPath', 
                'productData', 'vendor', 'vendorUser'
            ));

            // return response()->json([
            //     'success' => true,
            //     'data' => $productData
            // ]);

        } catch (\Exception $e) {
            // Full page route: let the global handler render the friendly error page
            throw $e;
        }
    }

    /**
     * Get related products
     */
    public function relatedProducts($id, Request $request)
    {
        try {
            // Get current product's category and brand
            $product = DB::table('auto_parts_products')
                ->where('id', $id)
                ->select('category_id', 'brand')
                ->first();

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }

            // Get related products (same category or brand)
            $related = DB::table('auto_parts_products as p')
                ->join('auto_parts_categories as c', 'p.category_id', '=', 'c.id')
                ->leftJoin('auto_parts_product_images as i', function($join) {
                    $join->on('p.id', '=', 'i.product_id')
                         ->where('i.is_primary', '=', 1);
                })
                ->where('p.status', 'approved')
                ->where('p.id', '!=', $id)
                ->where(function($q) use ($product) {
                    $q->where('p.category_id', $product->category_id)
                      ->orWhere('p.brand', $product->brand);
                })
                ->select(
                    'p.id',
                    'p.product_name as name',
                    'p.brand',
                    'p.selling_price as price',
                    'p.mrp as original_price',
                    'c.category_name as category',
                    'i.image_path as primary_image'
                )
                ->limit($request->get('limit', 4))
                ->get();

            // Format image URLs
            foreach ($related as $item) {
                if ($item->primary_image) {
                    $item->image = asset('storage/' . $item->primary_image);
                } else {
                    $item->image = asset('images/default-product.jpg');
                }
                $item->price = (float)$item->price;
                $item->original_price = $item->original_price ? (float)$item->original_price : null;
                
                // Remove primary_image from response
                unset($item->primary_image);
            }

            return response()->json([
                'success' => true,
                'data' => $related
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => \App\Support\ErrorReason::friendly($e, 'Error fetching related products')
            ], 500);
        }
    }
}