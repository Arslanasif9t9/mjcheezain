<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Models\VendorBasicInfo;
use App\Models\VendorProduct;
use App\Models\VendorProductImage;
use App\Models\VendorProductFault;
use App\Http\Controllers\Vendor\AutoPartsProductController;

class VendorController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        // dd($user->full_name);
        $vendor_id = $user->user_id;

        $vendorBasicInfo = DB::table('vendor_basic_info')
            ->where('user_id', $vendor_id)
            ->first();
        // dd($vendorBasicInfo);

        // ✅ Orders (last 6 months)
        $driver = DB::connection()->getDriverName();
        if ($driver === 'sqlite') {
            $orders = DB::table('orders')
                ->selectRaw("strftime('%Y-%m', order_date) as month, COUNT(*) as total_orders")
                ->where('vendor_id', $vendor_id)
                ->whereRaw("order_date >= date('now', '-6 month')")
                ->groupBy('month')
                ->orderBy('month')
                ->get();
        } else {
            $orders = DB::table('orders')
                ->selectRaw("DATE_FORMAT(order_date, '%Y-%m') as month, COUNT(*) as total_orders")
                ->where('vendor_id', $vendor_id)
                ->whereRaw("order_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)")
                ->groupBy('month')
                ->orderBy('month')
                ->get();
        }

        // Prepare 6-month chart data
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $m = date('Y-m', strtotime("-$i month"));
            $months[$m] = 0;
        }
        foreach ($orders as $o) {
            $months[$o->month] = $o->total_orders;
        }

        $chartLabels = array_map(fn($m) => date('M', strtotime("$m-01")), array_keys($months));
        $chartData = array_values($months);
        // dd($chartLabels, $chartData);

        // ✅ Vendor balance
        // $balanceRow = DB::table('vendor_balance')
        //     ->where('user_id', $vendor_id)
        //     ->select('total_balance')
        //     ->first();
        // $balance = $balanceRow->total_balance ?? 0.00;
        $balance = 999;

        // ✅ Products (from vendor_products)
        $products = DB::table('vendor_products')
            ->where('user_id', $vendor_id)
            // ->select('id', 'name', 'selling_price', 'quantity', 'position', 'image_path')
            ->select('id', 'name', 'selling_price', 'quantity', 'position')
            ->orderByDesc('id')
            ->limit(8)
            ->get();

         // ✅ Stats Cards Data
        $totalProducts = DB::table('vendor_products')
            ->where('user_id', $vendor_id)
            ->count();

        $totalSales = DB::table('orders')
            ->where('vendor_id', $vendor_id)
            ->where('fulfillment', 'delivered')
            ->count();

        $newOrders = DB::table('orders')
            ->where('vendor_id', $vendor_id)
            ->count();

        // ✅ Recent Sold Orders (Last 3 orders)
        $recentOrders = DB::table('orders as o')
            ->select(
                'o.id as order_id',
                'p.name as product_name',
                'p.category as product_category',
                'o.total_amount',
                'o.order_date',
                'c.first_name as customer_name',
                'o.fulfillment',
                'pi.image_path'
            )
            ->join('vendor_products as p', 'o.product_id', '=', 'p.id')
            ->join('users as u', 'o.user_id', '=', 'u.user_id')
            ->leftJoin('customer_profile as c', 'o.user_id', '=', 'c.user_id')
            ->leftJoin('vendor_product_images as pi', function($join) {
                $join->on('pi.product_id', '=', 'p.id')
                     ->where('pi.is_primary', '=', true);
            })
            ->where('o.vendor_id', $vendor_id)
            ->orderBy('o.order_date', 'desc')
            ->limit(3)
            ->get()
            ->map(function($order) {
                return [
                    'order_id' => $order->order_id,
                    'product_name' => $order->product_name,
                    'product_category' => $order->product_category,
                    'total_amount' => $order->total_amount,
                    'order_date' => $order->order_date,
                    'customer_name' => $order->customer_name ?: 'N/A',
                    'fulfillment' => $order->fulfillment,
                    'image_path' => $order->image_path ? asset('uploads/' . $order->image_path) : asset('img/default_product.webp')
                ];
            })
            ->toArray();

        // ✅ Top Categories
        $topCategories = DB::table('vendor_products as p')
            ->select('p.name', DB::raw('COUNT(o.id) as order_count'))
            ->leftJoin('orders as o', function($join) use ($vendor_id) {
                $join->on('p.id', '=', 'o.product_id')
                     ->where('o.vendor_id', '=', $vendor_id);
            })
            ->where('p.user_id', $vendor_id)
            ->groupBy('p.id', 'p.name')
            ->orderBy('order_count', 'desc')
            ->limit(5)
            ->get()
            ->map(function($category) {
                return [
                    'name' => $category->name,
                    'order_count' => $category->order_count
                ];
            })
            ->toArray();

        return view('vendor.dashboard', compact(
            'user', 'balance', 'products', 
            'chartLabels', 'chartData',
            'vendorBasicInfo',
            'totalProducts',
            'totalSales',
            'newOrders',
            'recentOrders',
            'topCategories'
        ));
    }


    public function profile()
    {
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

        return view('vendor.profile', [
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
        ]);
    }

    public function profileEdit() {
        $user = Auth::user();
        $vendor_id = $user->user_id;

        $vendorBasicInfo = DB::table('vendor_basic_info')
            ->where('user_id', $vendor_id)
            ->first();
        $storeDetail = DB::table('vendor_store_details')
            ->where('user_id', $vendor_id)
            ->first();
        $address = DB::table('vendor_address')
            ->where('user_id', $vendor_id)
            ->first();

        // dd($vendorBasicInfo);


        return view('vendor.edit-profile', compact(
            "user",
            'vendorBasicInfo',
            'storeDetail',
            'address'
        ));
    }

    public function updateBasicInfo(Request $request)
    {
        try {
            $user = Auth::user();
            $vendor_id = $user->user_id;
            
            // Validate the request
            $validated = $request->validate([
                'full_name' => 'required|string|max:255',
                'store_name' => 'string|max:255',
                'phone' => 'max:20',
                'profile_visibility' => 'nullable|boolean',
                'profile_picture' => 'nullable'
            ]);

            // Find the vendor
            $vendor = db::table('vendor_basic_info')
            ->where('user_id', $vendor_id)->first();
            
            if (!$vendor) {
                // return response()->json([
                //     'success' => false,
                //     'message' => 'Vendor not found'
                // ], 404);
                VendorBasicInfo::create([
                    'user_id' => $vendor_id,
                    'full_name' => $request->full_name
                ]);
            }

            // Handle profile picture upload
            if ($request->hasFile('profile_picture')) {
                $profilePicture = $request->file('profile_picture');
                
                // Delete old profile picture if exists
                if ($vendor->profile_picture) {
                    Storage::delete('public/vendor/profile/' . $vendor->profile_picture);
                }
                
                // Generate unique filename
                $filename = 'vendor_' . $vendor_id . '_' . time() . '.' . $profilePicture->getClientOriginalExtension();
                
                // Store the image
                $path = $profilePicture->storeAs('vendor/profile', $filename, 'public');
                $destinationPath = public_path('storage/vendor/profile');
                $profilePicture->move($destinationPath, $filename);
                // $path = $profilePicture->move(public_path('storage/vendor/profile'));
                
                // Update filename in validated data
                $validated['profile_picture'] = $filename;
            } else {
                // Remove profile_picture from validated data if no new file uploaded
                unset($validated['profile_picture']);
            }
            
            // Convert profile_visibility to boolean
            $validated['profile_visibility'] = $request->boolean('profile_visibility');
            
            // Update vendor record
            DB::table('vendor_basic_info')
            ->where('user_id', $vendor_id)
            ->update($validated);
            // return response()->json(['success' => true]);
            
            
            return response()->json([
                'success' => true,
                'message' => 'Basic information updated successfully',
                'data' => $vendor
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating basic information: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateStoreDetail(Request $request)
    {
        try {
            $user = Auth::user();
            $vendor_id = $user->user_id;

            // Validate fields
            
            $validated = $request->validate([
                'business_type' => 'nullable|string|max:100',
                'store_category' => 'nullable|string|max:100',
                'store_description' => 'nullable|string',
                'return_policy' => 'nullable|string',
                'shipping_policy' => 'nullable|string',
                'store_logo' => 'nullable',
                'store_banner' => 'nullable',
                'return_policy_file' => 'nullable',
                'shipping_policy_file' => 'nullable',
            ]);

            // Find vendor store record
            $vendor = DB::table('vendor_store_details')->where('user_id', $vendor_id)->first();
            if (!$vendor) {
                // return response()->json([
                //     'success' => false,
                //     'message' => 'Vendor store details not found.'
                // ], 404);
                DB::table('vendor_store_details')->insert(['user_id'=>$vendor_id]);
            }

            // Define base storage paths
            $basePath = 'vendor/store';

            // Handle each file upload
            if ($request->hasFile('store_logo')) {
                $file = $request->file('store_logo');
                $filename = 'logo_' . $vendor_id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs($basePath, $filename);
                $destinationPath = public_path('storage/vendor/store');
                $file->move($destinationPath, $filename);
                $validated['store_logo'] = $filename;
            }

            if ($request->hasFile('store_banner')) {
                $file = $request->file('store_banner');
                $filename = 'banner_' . $vendor_id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs($basePath, $filename);
                $destinationPath = public_path('storage/vendor/store');
                $file->move($destinationPath, $filename);
                $validated['store_banner'] = $filename;
            }

            if ($request->hasFile('return_policy_file')) {
                $file = $request->file('return_policy_file');
                $filename = 'return_policy_' . $vendor_id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs($basePath, $filename);
                $destinationPath = public_path('storage/vendor/store');
                $file->move($destinationPath, $filename);
                $validated['return_policy_file'] = $filename;
            }

            if ($request->hasFile('shipping_policy_file')) {
                $file = $request->file('shipping_policy_file');
                $filename = 'shipping_policy_' . $vendor_id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs($basePath, $filename);
                $destinationPath = public_path('storage/vendor/store');
                $file->move($destinationPath, $filename);
                $validated['shipping_policy_file'] = $filename;
            }

            // Update record
            DB::table('vendor_store_details')
                ->where('user_id', $vendor_id)
                ->update($validated);

            if($request->NTN != null){
                DB::table('vendor_store_details')
                    ->where('user_id', $vendor_id)
                    ->update(['NTN' => $request->NTN]);
            }

            

            return response()->json([
                'success' => true,
                'message' => 'Store details updated successfully',
                'logo_url' => isset($validated['store_logo']) ? asset('storage/vendor/store/' . $validated['store_logo']) : null,
                'banner_url' => isset($validated['store_banner']) ? asset('storage/vendor/store/' . $validated['store_banner']) : null,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating store details: ' . $e->getMessage()
            ], 500);
        }
    }
    public function updateAddress(Request $request)
    {
        // return response()->json([
        //         'success' => true,
        //         'message' => 'Address updated successfully!',
        //     ]);
        try {
            $user = Auth::user();
            $vendor_id = $user->user_id;

            // Validate form inputs
            $validated = $request->validate([
                'pickup_address' => 'max:255',
                'city' => 'max:100',
                'area' => 'max:100',
                'country' => 'max:100',
                'postal_code' => 'max:20',
            ]);


            // Check if vendor record exists
            $vendorAddress = DB::table('vendor_address')->where('user_id', $vendor_id)->first();
            if (!$vendorAddress) {
                DB::table('vendor_address')->insert(['user_id'=>$vendor_id]);
            }

            if ($vendorAddress) {
                // Update existing record
                DB::table('vendor_address')->where('user_id', $vendor_id)->update($validated);
            } else {
                // Insert new record if not found
                $validated['user_id'] = $vendor_id;
                DB::table('vendor_address')->insert($validated);
            }

            return response()->json([
                'success' => true,
                'message' => 'Address updated successfully!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating address: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function products(Request $request) {
        $user = Auth::user();
        $vendor_id = $user->user_id;
        
        // Get vendor basic info
        $vendorBasicInfo = DB::table('vendor_basic_info')
            ->where('user_id', $vendor_id)
            ->first();
        
        // Default values if data doesn't exist
        // $profile_picture = $vendorBasicInfo->profile_picture ?? 'img/default_profile.webp';
        // $full_name = $vendorBasicInfo->full_name ?? 'Not specified';
        
        // Determine active tab from URL parameter
        // $active_tab = $request->get('tab', 'all');
        
        // Map tab names to position values
        // $tab_position_map = [
        //     'online' => 'online',
        //     'pending' => 'pending',
        //     'offline' => 'offline',
        //     'draft' => 'draft',
        //     'all' => 'all'
        // ];
        
        // Get products based on active tab
        // $query = DB::table('vendor_products')
        // ->leftJoin('vendor_product_images as primaryImage', 'vendor_products.id', '=', 'primaryImage.product_id')
        // ->where('vendor_products.user_id', $vendor_id)
        // ->select('vendor_products.*', 'primaryImage.image_path as primary_image');
        $products = VendorProduct::where('user_id', $vendor_id)->get();

        foreach ($products as $product) {
            $productImage = VendorProductImage::where('product_id', $product->id)
                                            ->where('is_primary', 1)
                                            ->first(); // use first() because only one primary image

            // Example: attach image path to product
            $product->primary_image = $productImage ? $productImage->image_path : null;
        }
        
        // if ($active_tab !== 'all') {
        //     $position = $tab_position_map[$active_tab] ?? 'all';
        //     $query->where('position', $position);
        // }
        
        // $products = $query->get();
        $total_products = $products->count();
        $pending_products = $products->where('position', 'pending')->count();
        
        // Calculate completion percentage
        $completion_percentage = $total_products > 0 
            ? round(100 - (($pending_products / $total_products) * 100))
            : 0;
        
        $active_tab = null;
        return view('vendor.products', compact(
            'user',
            'vendorBasicInfo',
            'products',
            'active_tab',
            'total_products',
            'pending_products',
            'completion_percentage'
        ));
    }

    public function productsCreate ($id = null) {
        $user = Auth::user();
        $vendor_id = $user->user_id;
        
        // Get vendor basic info
        $vendorBasicInfo = DB::table('vendor_basic_info')
            ->where('user_id', $vendor_id)
            ->first();

        $product = null;
        $productImages = [];
        $productFaults = [];

        // If editing, get product data
        if ($id) {
            $product = DB::table('vendor_products')
                ->where('id', $id)
                ->where('user_id', $vendor_id)
                ->first();

            if ($product) {
                // Get product images
                $productImages = DB::table('vendor_product_images')
                    ->where('product_id', $id)
                    ->get();

                // Get product faults
                $productFaults = DB::table('vendor_product_faults')
                    ->where('product_id', $id)
                    ->get();
            }
        }
        

        return view('vendor.new_product', compact(
            'user',
            'vendorBasicInfo',
            'product',
            'productImages',
            'productFaults'
        ));
    }

    public function storeProduct(Request $request)
    {
        // Validate the request
        // $validated = $request->validate([
        //     // 'productVideo' => 'required|string|max:255',
        //     'product_name' => 'required|string|max:255',
        //     'category' => 'required|string|max:255',
        //     'subcategory' => 'required|string|max:255',
        //     'quantity' => 'required|integer|min:1',
        //     'brand' => 'required|string|max:255',
        //     'model' => 'nullable|string|max:255',
        //     // 'model_value' => 'nullable|string|max:255',
        //     // 'model_unit' => 'nullable|string|max:255',
        //     'condition' => 'required|string|max:255',
        //     'original_price' => 'required|numeric|min:0',
        //     'delivery_charges' => 'required|numeric|min:0',
        //     'selling_price' => 'required|numeric|min:0',
        //     'mrp' => 'nullable|numeric|min:0',
        //     'shipping_method' => 'required|string|max:255',
        //     'shipping_time' => 'required|string|max:255',
        //     'description' => 'required|string|min:100',
        //     'location' => 'required|string|max:255',
        //     'made_in' => 'required|string|max:255',
        //     'return_policy' => 'nullable|string',
        //     'productImages' => 'required|array|min:5|max:10',
        //     'productImages.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        //     'productVideo' => 'nullable|file|mimes:mp4,mov,avi|max:51200',
        //     'faults' => 'nullable|array',
        //     'faults.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        //     'fault_descriptions' => 'nullable|array',
        // ]);
        
        $validated = $request->all();
        // return response()->json([
        //         'success' => $request->all()
        //     ]);
        $validated['product_name'] = ucwords(trim($validated['product_name']));
        $validated['brand'] = ucwords(trim($validated['brand']));
        $validated['model'] = ucwords(trim($validated['model']));
        $validated['made_in'] = ucwords(trim($validated['made_in']));
        // Handle model field based on category
        // $modelValue = $validated['model'] ?? '';
        // if (!empty($validated['model_value']) && !empty($validated['model_unit'])) {
        //     $modelValue = $validated['model_value'] . ' ' . $validated['model_unit'];
        // }

        // Create the product
        $product = VendorProduct::create([
            'user_id' => Auth::id(),
            'name' => $validated['product_name'],
            'category' => $validated['category'],
            'subcategory' => $validated['subcategory'],
            'quantity' => $validated['quantity'],
            'brand' => $validated['brand'],
            'model' => $validated['model'],
            'pcondition' => $validated['condition'],
            'original_price' => 0,
            'delivery_charges' => $validated['delivery_charges'],
            'selling_price' => $validated['selling_price'],
            'mrp' => $validated['mrp'] ?? null,
            'shipping_method' => $validated['shipping_method'],
            'shipping_time' => $validated['shipping_time'],
            'description' => $validated['description'],
            'location' => $validated['location'],
            'made_in' => $validated['made_in'],
            'return_policy' => $validated['return_policy'] ?? null,
            'status' => 'pending',
            'part_type' => $validated['part_type'] ?? null
        ]);

        // return $request->input('productImages');
        // Handle product images
        if ($request->hasFile('productImages')) {
            foreach ($request->file('productImages') as $index => $image) {
                $extension = $image->getClientOriginalExtension();
                $filename = uniqid() . '.' . $extension; // unique filename
                $imagePath = $image->storeAs('vendor/products/images', $filename, 'public');
                $destinationPath = public_path('storage/vendor/products/images');
                $image->move($destinationPath, $filename);
                
                VendorProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $filename,
                    'is_primary' => $index === 0, // First image is primary
                ]);
            }
        }

        // Handle product video
        if ($request->hasFile('productVideo')) {
            $extension = $image->getClientOriginalExtension();
            $filename = uniqid() . '.' . $extension; // unique filename
            $videoPath = $request->file('productVideo')->storeAs('vendor/products/videos', $filename, 'public');
            $destinationPath = public_path('storage/vendor/products/videos');
            $request->file('productVideo')->move($destinationPath, $filename);
            VendorProduct::where('id', $product->id)
                ->update([
                    'video' => $filename
                ]);
        }

        // Handle product faults
        if ($request->hasFile('faults')) {
            foreach ($request->file('faults') as $index => $faultImage) {
                $extension = $image->getClientOriginalExtension();
                $filename = uniqid() . '.' . $extension; // unique filename
                $faultImagePath = $faultImage->storeAs('vendor/products/faults', $filename, 'public');
                $destinationPath = public_path('storage/vendor/products/faults');
                $faultImage->move($destinationPath, $filename);
                
                VendorProductFault::create([
                    'product_id' => $product->id,
                    'fault_image' => $filename,
                    'fault_description' => $validated['fault_descriptions'][$index] ?? null,
                ]);
            }
        }
        
        if ($validated['part_type'] ?? null) {
            $AutoPartsProductController = new AutoPartsProductController();
            $result = $AutoPartsProductController->store($validated, $product->id);
            // return response()->json(['test'=>$result]);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product created successfully and is pending approval.',
                'redirect' => route('vendor.products')
            ]);
        }

        return redirect()->route('vendor.products')
            ->with('success', 'Product created successfully and is pending approval.');
    }

    public function deleteProduct(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'product_id' => 'required'
            ]);
            
            // Find the product
            $product = VendorProduct::findOrFail($request->product_id);
            
            // Check if the product belongs to the current vendor
            if ($product->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to delete this product.'
                ], 403);
            }
            
            // return response()->json(['success' => $product]);
            // Delete product images from storage
            if ($product->primary_image) {
                Storage::delete('vendor/products/images/' . $product->primary_image);
            }

            // Delete additional images if any
            if ($product->images) {
                foreach ($product->images as $image) {
                    Storage::delete('vendor/products/images/' . $image);
                }
            }

            // Delete the product
            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete product: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateProduct(Request $request, $id)
    {
        // Find the product
        $product = VendorProduct::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $validated = $request->all();

        // Update the product
        $product->update([
            'name' => $validated['product_name'],
            'category' => $validated['category'],
            'subcategory' => $validated['subcategory'],
            'quantity' => $validated['quantity'],
            'brand' => $validated['brand'],
            'model' => $validated['model'],
            'pcondition' => $validated['condition'],
            'original_price' => 0,
            'delivery_charges' => $validated['delivery_charges'],
            'selling_price' => $validated['selling_price'],
            'mrp' => $validated['mrp'] ?? null,
            'shipping_method' => $validated['shipping_method'],
            'shipping_time' => $validated['shipping_time'],
            'description' => $validated['description'],
            'location' => $validated['location'],
            'made_in' => $validated['made_in'],
            'return_policy' => $validated['return_policy'] ?? null,
            'status' => 'pending',
        ]);

        // Handle product images - ADD NEW IMAGES ONLY
        if ($request->hasFile('productImages')) {
            foreach ($request->file('productImages') as $index => $image) {
                $extension = $image->getClientOriginalExtension();
                $filename = uniqid() . '.' . $extension;
                $imagePath = $image->storeAs('vendor/products/images', $filename, 'public');
                $destinationPath = public_path('storage/vendor/products/images');
                $image->move($destinationPath, $filename);
                
                VendorProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $filename,
                    'is_primary' => $index === 0 && $product->images()->count() === 0, // Primary only if no images exist
                ]);
            }
        }

        // Handle product video - REPLACE EXISTING VIDEO
        if ($request->hasFile('productVideo')) {
            // Delete old video if exists
            if ($product->video) {
                if (Storage::disk('public')->exists('vendor/products/videos/' . $product->video)) {
                    Storage::disk('public')->delete('vendor/products/videos/' . $product->video);
                }
            }

            $video = $request->file('productVideo');
            $extension = $video->getClientOriginalExtension();
            $filename = uniqid() . '.' . $extension;
            $videoPath = $video->storeAs('vendor/products/videos', $filename, 'public');
            $destinationPath = public_path('storage/vendor/products/videos');
            $video->move($destinationPath, $filename);
            
            $product->update([
                'video' => $filename
            ]);
        }

        // Handle remove video request
        if ($request->has('remove_video') && $request->input('remove_video')) {
            if ($product->video) {
                if (Storage::disk('public')->exists('vendor/products/videos/' . $product->video)) {
                    Storage::disk('public')->delete('vendor/products/videos/' . $product->video);
                }
            }
            $product->update(['video' => null]);
        }

        // Handle product faults - DELETE OLD AND ADD NEW
        // First delete all existing faults
        $existingFaults = VendorProductFault::where('product_id', $product->id)->get();
        foreach ($existingFaults as $fault) {
            if ($fault->fault_image && Storage::disk('public')->exists('vendor/products/faults/' . $fault->fault_image)) {
                Storage::disk('public')->delete('vendor/products/faults/' . $fault->fault_image);
            }
            $fault->delete();
        }

        // Then add new faults
        if ($request->hasFile('faults')) {
            foreach ($request->file('faults') as $index => $faultImage) {
                $extension = $faultImage->getClientOriginalExtension();
                $filename = uniqid() . '.' . $extension;
                $faultImagePath = $faultImage->storeAs('vendor/products/faults', $filename, 'public');
                $destinationPath = public_path('storage/vendor/products/faults');
                $faultImage->move($destinationPath, $filename);
                
                VendorProductFault::create([
                    'product_id' => $product->id,
                    'fault_image' => $filename,
                    'fault_description' => $validated['fault_descriptions'][$index] ?? null,
                ]);
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully and is pending approval.',
                'redirect' => route('vendor.products')
            ]);
        }

        return redirect()->route('vendor.products')
            ->with('success', 'Product updated successfully and is pending approval.');
    }

    public function updateStatus(Request $request) {
        try {
            $request->validate([
                'user_id' => 'required|integer|exists:users,user_id',
                'status' => 'required|in:active,pending,blocked',
                'action' => 'required|in:approve,block,unblock,reject'
            ]);
            
            $userId = $request->user_id;
            $newStatus = $request->status;
            $action = $request->action;
            $verify = ($newStatus == 'active') ? 1 : 0;
            
            // Update user status
            DB::table('users')
                ->where('user_id', $userId)
                ->update(['status' => $newStatus, 'verified' => $verify]);
            
            return response()->json([
                'success' => true
            ]);
            // Log the action if needed
            // ActivityLog::create([...]);

            return response()->json([
                'success' => true,
                'message' => "Vendor {$action}d successfully!",
                'data' => [
                    'user_id' => $userId,
                    'new_status' => $newStatus,
                    'action' => $action
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update vendor status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getVendorDetails($user_id)
    {
        try {
            $user_id = intval($user_id);

            // Get vendor basic info
            $vendor = DB::table('users as u')
                ->leftJoin('vendor_basic_info as vbi', 'u.user_id', '=', 'vbi.user_id')
                ->leftJoin('vendor_store_details as vsd', 'u.user_id', '=', 'vsd.user_id')
                ->leftJoin('vendor_address as va', 'u.user_id', '=', 'va.user_id')
                ->where('u.user_id', $user_id)
                ->select('u.*', 'vbi.*', 'vsd.*', 'va.*')
                ->first();

            if (!$vendor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vendor not found'
                ], 404);
            }

            // Get vendor products
            $products = DB::table('vendor_products')
                ->where('user_id', $user_id)
                ->limit(5)
                ->get();

            // // Get vendor orders
            // $orders = DB::table('orders as o')
            //     ->join('vendor_products as p', 'o.product_id', '=', 'p.id')
            //     ->where('o.vendor_id', $user_id)
            //     ->select('o.*', 'p.name as product_name')
            //     ->orderBy('o.order_date', 'DESC')
            //     ->limit(5)
            //     ->get();

            // // Get vendor documents
            // $documents = DB::table('vendor_documents')
            //     ->where('user_id', $user_id)
            //     ->get();

            // // Get vendor payments
            // $payments = DB::table('vendor_payments')
            //     ->where('user_id', $user_id)
            //     ->orderBy('payment_date', 'DESC')
            //     ->limit(5)
            //     ->get();

            // // Calculate total earnings
            // $earnings = DB::table('orders')
            //     ->where('vendor_id', $user_id)
            //     ->select(DB::raw('SUM(total_amount) as total_earnings'))
            //     ->first();


            // return response()->json([
            //     'success' => true,
            //     'html' => [$user_id, $vendor, $products]
            // ]);

            // Generate HTML content
            $html = view('admin/vendor_details', compact([
                'vendor', 'products'
            ]))->render();

            return response()->json([
                'success' => true,
                'html' => $html
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading vendor details: ' . $e->getMessage()
            ], 500);
        }
    }

    public function pr ($pr = 1) {}

    public function orders() {
        $user = Auth::user();
        $vendor_id = $user->user_id;
        
        // Get all product IDs for this vendor
        $productIds = DB::table('vendor_products')
            ->where('user_id', $vendor_id)
            ->pluck('id')
            ->toArray();

        // Get all carts that have these product IDs
        $orders = DB::table('carts')
            ->whereIn('product_id', $productIds)
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate stats
        $totalOrders = $orders->count();
        $deliveredOrders = $orders->where('status', 'delivered')->count();
        $paidOrders = $orders->where('status', 'paid')->count();
        $activeOrders = $orders->whereNotIn('status', ['delivered', 'cancelled'])->count();

        // Get vendor basic info
        $vendorBasicInfo = DB::table('vendor_basic_info')
            ->where('user_id', $vendor_id)
            ->first();

        return view('vendor.orders', compact(
            'user',
            'orders',
            'totalOrders',
            'deliveredOrders',
            'paidOrders',
            'activeOrders',
            'vendorBasicInfo'
        ));
    }

    public function updateOrderStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer|exists:carts,id',
            'status' => 'required|in:order placed,processing,shipping,delivered,cancelled'
        ]);

        $vendor_id = Auth::id();
        
        // Check if the product belongs to this vendor
        $isValid = DB::table('carts')
            ->join('vendor_products', 'carts.product_id', '=', 'vendor_products.id')
            ->where('carts.id', $request->order_id)
            ->where('vendor_products.user_id', $vendor_id)
            ->exists();

        if (!$isValid) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        // Update status
        DB::table('carts')
            ->where('id', $request->order_id)
            ->update([
                'status' => $request->status,
                'updated_at' => now()
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'status' => $request->status
        ]);
    }





    public function notifications()
    {
        $user = Auth::user();
        // dd($user->full_name);
        $vendor_id = $user->user_id;

        $vendorBasicInfo = DB::table('vendor_basic_info')
            ->where('user_id', $vendor_id)
            ->first();
        
        // Get notifications grouped by date
        $notifications = DB::table('notifications')
            ->where('user_id', $user->user_id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function($notification) {
                return $this->formatDateGroup($notification->created_at);
            });
        
        return view('vendor.notifications', compact('user', 'vendor_id', 'vendorBasicInfo', 'notifications'));
    }
    
    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        
        DB::table('notifications')
            ->where('id', $id)
            ->where('user_id', $user->user_id)
            ->update([
                'is_read' => 1,
                'read_at' => now()
            ]);
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Format date for grouping
     */
    private function formatDateGroup($date)
    {
        $notificationDate = \Carbon\Carbon::parse($date);
        $today = \Carbon\Carbon::today();
        $yesterday = \Carbon\Carbon::yesterday();
        
        if ($notificationDate->isToday()) {
            return 'Today';
        } elseif ($notificationDate->isYesterday()) {
            return 'Yesterday';
        } elseif ($notificationDate->isCurrentWeek()) {
            return 'This Week';
        } else {
            return $notificationDate->format('F Y');
        }
    }

}
