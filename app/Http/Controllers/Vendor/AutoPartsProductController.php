<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AutoPartsProductController extends Controller
{
    /**
     * Show the form for creating a new auto parts product
     */
    public function create()
    {
        // Get all categories for dropdown
        $categories = DB::table('auto_parts_categories')
            ->orderBy('category_name')
            ->get();
        
        // Get all subcategories (will be loaded via AJAX based on category)
        $subcategories = DB::table('auto_parts_subcategories')
            ->orderBy('subcategory_name')
            ->get();
            
            
            
            
            
            
            
        $user = Auth::user();
        $vendor_id = $user->user_id;

        // Get vendor basic info
        $vendorBasicInfo = DB::table('vendor_basic_info')
            ->where('user_id', $vendor_id)
            ->first();

        // Get store info
        $storeInfo = DB::table('vendor_store_details')
            ->where('user_id', $vendor_id)
            ->first();

        // Get address info
        $vendorAddress = DB::table('vendor_address')
            ->where('user_id', $vendor_id)
            ->first();

        return view('vendor.create-auto', [
            'user' => $user,
            'profile_picture' => $vendorBasicInfo->profile_picture ?? 'default_profile.webp',
            'full_name' => $user->full_name ?? $user->username,
            'user_email' => $user->email,
            'phone' => $user->phone ?? $vendorBasicInfo->phone ?? null,
            'store_logo' => $storeInfo->store_logo ?? 'default_profile.webp',
            'store_name' => $storeInfo->store_name ?? 'My Store',
            'rating' => $storeInfo->rating ?? 0,
            'verified' => $storeInfo->verified ?? false,
            'city' => $vendorAddress->city ?? 'Not specified',
            'country' => $vendorAddress->country ?? 'Not specified',
            'store_banner' => $storeInfo->store_banner ?? asset('img/default-banner.jpg'),
            'pickup_address' => $vendorAddress->pickup_address ?? 'Not specified',
            'business_type' => $storeInfo->business_type ?? 'Not specified',
            'store_category' => $storeInfo->store_category ?? 'Not specified',
            'return_policy' => $storeInfo->return_policy ?? 'Not avaliable',
            'return_policy_file' => $storeInfo->return_policy_file ?? 'Not avaliable',
            'shipping_policy' => $storeInfo->shipping_policy ?? 'Not avaliable',
            'shipping_policy_file' => $storeInfo->shipping_policy_file ?? 'Not avaliable',
            'store_description' => $storeInfo->store_description ?? 'No description provided.',
            'area' => $vendorAddress->area ?? 'Not specified',
            'postal_code' => $vendorAddress->postal_code ?? 'Not specified',
            'vendorBasicInfo' => $vendorBasicInfo,
            'categories' => $categories, 
            'subcategories' => $subcategories
        ]);
        
        return view('vendor.create-auto', compact('categories', 'subcategories'));
    }

    /**
     * Get subcategories by category ID (AJAX)
     */
    public function getSubcategories($categoryId)
    {
        $subcategories = DB::table('auto_parts_subcategories')
            ->where('category_id', $categoryId)
            ->select('id', 'subcategory_name', 'dropdown_type')
            ->orderBy('subcategory_name')
            ->get();
        
        return response()->json($subcategories);
    }

    /**
     * Store a newly created auto parts product
     */
    // public function store($req)
    // {
        
    //     // return response()->json([
    //             // 'success' => $request->all()
    //             // 'message' => 'Product created successfully and is pending approval.',
    //             // 'redirect' => route('vendor.products')
    //         // ]);
    //     // Validate the request
    //     // return $req->product_name;
    //     $validated = [
    //         'product_name' => $req->product_name,
    //         'category_id' => $req->category,
    //         'subcategory_id' => $req->subcategory,
    //         'part_type' => $req->part_type,
    //         'brand' => $req->brand,
    //         'model' => $req->model,
    //         'made_in' => $req->made_in,
    //         'condition' => $req->condition,
    //         'selling_price' => $req->selling_price,
    //         'mrp' => $req->mrp,
    //         'quantity' => $req->quantity,
    //         'description' => $req->description,
    //         'shipping_method' => $req->shipping_method,
    //         'shipping_time' => $req->shipping_time,
    //         'return_policy' => $req->return_policy,
    //         'location' => $req->location,
    //         'product_images' => $req->product_images,
    //         'product_images.*' => $req->product_images,
    //         'product_video' => $req->product_video
    //     ];

    //     try {
    //         DB::beginTransaction();

    //         // Get vendor ID from session or auth
    //         $vendorId = session('vendor_id'); // Adjust based on your auth system
    //         if (!$vendorId) {
    //             $user = Auth::user();
    //             $vendorId = $user->user_id;
    //         }

    //         // Insert main product data
    //         $productId = DB::table('auto_parts_products')->insertGetId([
    //             'vendor_id' => $vendorId,
    //             'product_name' => $request->product_name,
    //             'category_id' => $request->category_id,
    //             'subcategory_id' => $request->subcategory_id,
    //             'part_type' => $request->part_type,
    //             'brand' => $request->brand,
    //             'model' => $request->model,
    //             'made_in' => $request->made_in,
    //             'conditionp' => $request->condition,
    //             'selling_price' => $request->selling_price,
    //             'mrp' => $request->mrp ?? 0,
    //             'delivery_charges' => 250.00,
    //             'quantity' => $request->quantity,
    //             'description' => $request->description,
    //             'shipping_method' => $request->shipping_method,
    //             'shipping_time' => $request->shipping_time,
    //             'return_policy' => $request->return_policy,
    //             'location' => $request->location,
    //             'status' => 'pending',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ]);

    //         // Handle image uploads
    //         if ($request->hasFile('product_images')) {
    //             $sortOrder = 0;
    //             foreach ($request->file('product_images') as $image) {
    //                 // Generate unique filename
    //                 $filename = 'product_' . $productId . '_' . time() . '_' . $sortOrder . '.' . $image->getClientOriginalExtension();
                    
    //                 // Store in public disk
    //                 $path = $image->storeAs('storage/vendor/products/images', $filename, 'public');
    //                 $destinationPath = public_path('storage/vendor/products/images');
    //                 $image->move($destinationPath, $filename);
                    
    //                 // Insert into database
    //                 DB::table('auto_parts_product_images')->insert([
    //                     'product_id' => $productId,
    //                     'image_path' => $path,
    //                     'is_primary' => $sortOrder == 0 ? 1 : 0,
    //                     'sort_order' => $sortOrder,
    //                     'created_at' => now(),
    //                 ]);
                    
    //                 $sortOrder++;
    //             }
    //         }

    //         // Handle video upload
    //         if ($request->hasFile('product_video')) {
    //             $video = $request->file('product_video');
    //             $videoFilename = 'product_' . $productId . '_' . time() . '.' . $video->getClientOriginalExtension();
    //             $videoPath = $video->storeAs('vendor/products/autoparts/videos', $videoFilename, 'public');
                
    //             DB::table('auto_parts_product_videos')->insert([
    //                 'product_id' => $productId,
    //                 'video_path' => $videoPath,
    //                 'created_at' => now(),
    //             ]);
    //         }

    //         // Handle faults if any
    //         if ($request->has('faults') && is_array($request->faults)) {
    //             foreach ($request->file('faults', []) as $index => $faultImage) {
    //                 if ($faultImage && isset($request->fault_descriptions[$index])) {
    //                     // Upload fault image
    //                     $faultFilename = 'fault_' . $productId . '_' . time() . '_' . $index . '.' . $faultImage->getClientOriginalExtension();
    //                     $faultPath = $faultImage->storeAs('vendor/products/autoparts/faults', $faultFilename, 'public');
                        
    //                     // Insert fault
    //                     DB::table('auto_parts_product_faults')->insert([
    //                         'product_id' => $productId,
    //                         'fault_image' => $faultPath,
    //                         'fault_description' => $request->fault_descriptions[$index],
    //                         'created_at' => now(),
    //                     ]);
    //                 }
    //             }
    //         }

    //         DB::commit();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Auto parts product submitted successfully!',
    //             'redirect' => route('vendor.products')
    //         ]);

    //     } catch (\Exception $e) {
    //         DB::rollBack();
            
    //         return response()->json([
    //             'success' => false,
    //             'message' => \App\Support\ErrorReason::friendly($e, 'Failed to save product'),
    //             'errors' => ['general' => [$e->getMessage()]]
    //         ], 500);
    //     }
    // }
    
    
    
    // claude 
    public function store($req, $vendorProductId = null)
    {
        try {
            $user = Auth::user();
            $vendorId = $user->user_id;

            // category name se category_id nikalo
            $category = DB::table('auto_parts_categories')
                ->where('category_name', $req['category'])
                ->first();

            // subcategory name se subcategory_id nikalo
            $subcategory = DB::table('auto_parts_subcategories')
                ->where('subcategory_name', $req['subcategory'])
                ->first();

            $categoryId = $category ? $category->id : 1;
            $subcategoryId = $subcategory ? $subcategory->id : 1;

            // auto_parts_products mein insert karo
            $autoProductId = DB::table('auto_parts_products')->insertGetId([
                'vendor_id'          => $vendorId,
                'vendor_product_id'  => $vendorProductId,
                'product_name'       => $req['product_name'],
                'category_id'        => $categoryId,
                'subcategory_id'     => $subcategoryId,
                'part_type'          => $req['part_type'] ?? null,
                'brand'              => $req['brand'],
                'model'              => $req['model'] ?? '',
                'made_in'            => $req['made_in'] ?? '',
                'conditionp'         => $req['condition'],
                'selling_price'      => $req['selling_price'],
                'mrp'                => $req['mrp'] ?? null,
                'delivery_charges'   => $req['delivery_charges'] ?? 250,
                'quantity'           => $req['quantity'],
                'description'        => $req['description'],
                'shipping_method'    => $req['shipping_method'],
                'shipping_time'      => $req['shipping_time'],
                'return_policy'      => $req['return_policy'] ?? null,
                'location'           => $req['location'],
                'status'             => 'pending',
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);

            // vendor_product_images se same images auto_parts_product_images mein copy karo
            if ($vendorProductId) {
                $images = DB::table('vendor_product_images')
                    ->where('product_id', $vendorProductId)
                    ->get();

                $sortOrder = 0;
                foreach ($images as $img) {
                    DB::table('auto_parts_product_images')->insert([
                        'product_id' => $autoProductId,
                        'image_path' => $img->image_path,
                        'is_primary' => $img->is_primary ? 1 : 0,
                        'sort_order' => $sortOrder,
                        'created_at' => now(),
                    ]);
                    $sortOrder++;
                }
            }

            return true;

        } catch (\Exception $e) {
            return false;
        }
    }
    
    
    

    /**
     * Display listing of auto parts products
     */
    public function index()
    {
        $vendorId = session('vendor_id') ?? auth()->user()->id;
        
        $products = DB::table('auto_parts_products as p')
            ->join('auto_parts_categories as c', 'p.category_id', '=', 'c.id')
            ->join('auto_parts_subcategories as s', 'p.subcategory_id', '=', 's.id')
            ->leftJoin('auto_parts_product_images as i', function($join) {
                $join->on('p.id', '=', 'i.product_id')
                     ->where('i.is_primary', '=', 1);
            })
            ->where('p.vendor_id', $vendorId)
            ->select(
                'p.*',
                'c.category_name',
                's.subcategory_name',
                'i.image_path as primary_image'
            )
            ->orderBy('p.created_at', 'desc')
            ->paginate(10);
        
        return view('vendor.products.autoparts.index', compact('products'));
    }

    /**
     * Show form for editing an auto parts product
     */
    public function edit($id)
    {
        $vendorId = session('vendor_id') ?? auth()->user()->id;
        
        // Get product with its details
        $product = DB::table('auto_parts_products')
            ->where('id', $id)
            ->where('vendor_id', $vendorId)
            ->first();
        
        if (!$product) {
            return redirect()->route('vendor.products.autoparts.index')
                ->with('error', 'Product not found or unauthorized.');
        }
        
        // Get product images
        $productImages = DB::table('auto_parts_product_images')
            ->where('product_id', $id)
            ->orderBy('sort_order')
            ->get();
        
        // Get product video
        $productVideo = DB::table('auto_parts_product_videos')
            ->where('product_id', $id)
            ->first();
        
        // Get product faults
        $productFaults = DB::table('auto_parts_product_faults')
            ->where('product_id', $id)
            ->get();
        
        // Get categories
        $categories = DB::table('auto_parts_categories')
            ->orderBy('category_name')
            ->get();
        
        // Get subcategories for this product's category
        $subcategories = DB::table('auto_parts_subcategories')
            ->where('category_id', $product->category_id)
            ->orderBy('subcategory_name')
            ->get();
        
        return view('vendor.products.autoparts.edit', compact(
            'product', 
            'productImages', 
            'productVideo', 
            'productFaults',
            'categories', 
            'subcategories'
        ));
    }

    /**
     * Update an existing auto parts product
     */
    public function update(Request $request, $id)
    {
        $vendorId = session('vendor_id') ?? auth()->user()->id;
        
        // Verify product exists and belongs to vendor
        $product = DB::table('auto_parts_products')
            ->where('id', $id)
            ->where('vendor_id', $vendorId)
            ->first();
        
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found or unauthorized.'
            ], 404);
        }

        // Validate the request
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'category_id' => 'required|integer|exists:auto_parts_categories,id',
            'subcategory_id' => 'required|integer|exists:auto_parts_subcategories,id',
            'part_type' => 'nullable|string|max:100',
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'made_in' => 'required|string|max:100',
            'condition' => 'required|in:New,Used,Refurbished',
            'selling_price' => 'required|numeric|min:0',
            'mrp' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'description' => 'required|string|min:100',
            'shipping_method' => 'required|string|max:50',
            'shipping_time' => 'required|string|max:100',
            'return_policy' => 'nullable|string',
            'location' => 'required|string|max:255',
            'product_images' => 'nullable|array|max:10',
            'product_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
            'product_video' => 'nullable|mimes:mp4,mov,avi,webm,ogg|max:51200',
        ]);

        try {
            DB::beginTransaction();

            // Update main product
            DB::table('auto_parts_products')
                ->where('id', $id)
                ->update([
                    'product_name' => $request->product_name,
                    'category_id' => $request->category_id,
                    'subcategory_id' => $request->subcategory_id,
                    'part_type' => $request->part_type,
                    'brand' => $request->brand,
                    'model' => $request->model,
                    'made_in' => $request->made_in,
                    'condition' => $request->condition,
                    'selling_price' => $request->selling_price,
                    'mrp' => $request->mrp ?? 0,
                    'quantity' => $request->quantity,
                    'description' => $request->description,
                    'shipping_method' => $request->shipping_method,
                    'shipping_time' => $request->shipping_time,
                    'return_policy' => $request->return_policy,
                    'location' => $request->location,
                    'updated_at' => now(),
                ]);

            // Handle deleted images
            if ($request->has('deleted_images')) {
                foreach ($request->deleted_images as $imageId) {
                    // Get image path before deleting
                    $image = DB::table('auto_parts_product_images')
                        ->where('id', $imageId)
                        ->first();
                    
                    if ($image) {
                        // Delete file from storage
                        Storage::disk('public')->delete($image->image_path);
                        
                        // Delete from database
                        DB::table('auto_parts_product_images')
                            ->where('id', $imageId)
                            ->delete();
                    }
                }
            }

            // Handle new image uploads
            if ($request->hasFile('product_images')) {
                // Get current max sort order
                $maxSort = DB::table('auto_parts_product_images')
                    ->where('product_id', $id)
                    ->max('sort_order') ?? -1;
                
                $sortOrder = $maxSort + 1;
                
                foreach ($request->file('product_images') as $image) {
                    $filename = 'product_' . $id . '_' . time() . '_' . $sortOrder . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('vendor/products/autoparts/images', $filename, 'public');
                    
                    DB::table('auto_parts_product_images')->insert([
                        'product_id' => $id,
                        'image_path' => $path,
                        'is_primary' => 0,
                        'sort_order' => $sortOrder,
                        'created_at' => now(),
                    ]);
                    
                    $sortOrder++;
                }
            }

            // Handle video deletion
            if ($request->has('delete_video')) {
                $existingVideo = DB::table('auto_parts_product_videos')
                    ->where('product_id', $id)
                    ->first();
                
                if ($existingVideo) {
                    Storage::disk('public')->delete($existingVideo->video_path);
                    DB::table('auto_parts_product_videos')
                        ->where('product_id', $id)
                        ->delete();
                }
            }

            // Handle new video upload
            if ($request->hasFile('product_video')) {
                // Delete existing video first
                $existingVideo = DB::table('auto_parts_product_videos')
                    ->where('product_id', $id)
                    ->first();
                
                if ($existingVideo) {
                    Storage::disk('public')->delete($existingVideo->video_path);
                    DB::table('auto_parts_product_videos')
                        ->where('product_id', $id)
                        ->delete();
                }
                
                // Upload new video
                $video = $request->file('product_video');
                $videoFilename = 'product_' . $id . '_' . time() . '.' . $video->getClientOriginalExtension();
                $videoPath = $video->storeAs('vendor/products/autoparts/videos', $videoFilename, 'public');
                
                DB::table('auto_parts_product_videos')->insert([
                    'product_id' => $id,
                    'video_path' => $videoPath,
                    'created_at' => now(),
                ]);
            }

            // Handle faults
            if ($request->has('faults') && is_array($request->faults)) {
                // Delete existing faults
                $existingFaults = DB::table('auto_parts_product_faults')
                    ->where('product_id', $id)
                    ->get();
                
                foreach ($existingFaults as $fault) {
                    if ($fault->fault_image) {
                        Storage::disk('public')->delete($fault->fault_image);
                    }
                }
                
                DB::table('auto_parts_product_faults')
                    ->where('product_id', $id)
                    ->delete();
                
                // Add new faults
                foreach ($request->file('faults', []) as $index => $faultImage) {
                    if ($faultImage && isset($request->fault_descriptions[$index])) {
                        $faultFilename = 'fault_' . $id . '_' . time() . '_' . $index . '.' . $faultImage->getClientOriginalExtension();
                        $faultPath = $faultImage->storeAs('vendor/products/autoparts/faults', $faultFilename, 'public');
                        
                        DB::table('auto_parts_product_faults')->insert([
                            'product_id' => $id,
                            'fault_image' => $faultPath,
                            'fault_description' => $request->fault_descriptions[$index],
                            'created_at' => now(),
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Auto parts product updated successfully!',
                'redirect' => route('vendor.products.autoparts.index')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => \App\Support\ErrorReason::friendly($e, 'Failed to update product'),
                'errors' => ['general' => [$e->getMessage()]]
            ], 500);
        }
    }

    /**
     * Delete an auto parts product
     */
    public function destroy($id)
    {
        $vendorId = session('vendor_id') ?? auth()->user()->id;
        
        try {
            DB::beginTransaction();
            
            // Get product to verify ownership
            $product = DB::table('auto_parts_products')
                ->where('id', $id)
                ->where('vendor_id', $vendorId)
                ->first();
            
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found or unauthorized.'
                ], 404);
            }
            
            // Delete images files
            $images = DB::table('auto_parts_product_images')
                ->where('product_id', $id)
                ->get();
            
            foreach ($images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }
            
            // Delete video file
            $video = DB::table('auto_parts_product_videos')
                ->where('product_id', $id)
                ->first();
            
            if ($video) {
                Storage::disk('public')->delete($video->video_path);
            }
            
            // Delete fault images
            $faults = DB::table('auto_parts_product_faults')
                ->where('product_id', $id)
                ->get();
            
            foreach ($faults as $fault) {
                if ($fault->fault_image) {
                    Storage::disk('public')->delete($fault->fault_image);
                }
            }
            
            // Delete product (cascades will delete related records)
            DB::table('auto_parts_products')
                ->where('id', $id)
                ->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully!'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => \App\Support\ErrorReason::friendly($e, 'Failed to delete product')
            ], 500);
        }
    }
}