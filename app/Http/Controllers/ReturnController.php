<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ReturnController extends Controller
{
    // Show return form
    public function create($orderId, $cartId)
    {
        $customerId = Auth::id();
        
        // Get order and cart details
        $order = DB::table('orders')
            ->where('id', $orderId)
            ->where('user_id', $customerId)
            ->first();
        
        if (!$order) {
            abort(404, 'Order not found');
        }
        
        $cart = DB::table('carts as c')
            ->join('vendor_products as vp', 'c.product_id', '=', 'vp.id')
            ->leftJoin('vendor_product_images as vpi', function($join) {
                $join->on('vp.id', '=', 'vpi.product_id')
                    ->where('vpi.is_primary', 1);
            })
            ->where('c.id', $cartId)
            ->where('c.order_id', $orderId)
            ->where('c.user_id', $customerId)
            ->select('c.*', 'vp.name as product_name', 'vpi.image_path as product_image')
            ->first();
        
        if (!$cart) {
            abort(404, 'Item not found');
        }
        
        // Check if already returned
        $existingReturn = DB::table('return_requests')
            ->where('cart_id', $cartId)
            ->where('customer_id', $customerId)
            ->whereIn('status', ['pending', 'approved', 'processing'])
            ->first();
        
        if ($existingReturn) {
            return redirect()->route('customer.orders')->with('error', 'Return already requested for this item');
        }
        
        return view('customer.returns.create', compact('order', 'cart'));
    }
    
    // Submit return request
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|integer|exists:orders,id',
            'cart_id' => 'required|integer|exists:carts,id',
            'product_id' => 'required|integer|exists:vendor_products,id',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|in:damaged,wrong_item,defective,size_issue,color_issue,not_as_described,other',
            'details' => 'nullable|string|max:1000',
            'images.*' => 'nullable|image|max:2048'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            DB::beginTransaction();
            
            $customerId = Auth::id();
            
            // Verify ownership
            $order = DB::table('orders')
                ->where('id', $request->order_id)
                ->where('user_id', $customerId)
                ->first();
            
            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found'
                ], 404);
            }
            
            // Check if already returned
            $existingReturn = DB::table('return_requests')
                ->where('cart_id', $request->cart_id)
                ->where('customer_id', $customerId)
                ->whereIn('status', ['pending', 'approved', 'processing'])
                ->first();
            
            if ($existingReturn) {
                return response()->json([
                    'success' => false,
                    'message' => 'Return already requested for this item'
                ], 400);
            }
            
            // Calculate refund amount
            $cart = DB::table('carts')
                ->where('id', $request->cart_id)
                ->where('order_id', $request->order_id)
                ->first();
            
            $refundAmount = $cart->price * $request->quantity;
            
            // Create return request
            $returnId = DB::table('return_requests')->insertGetId([
                'order_id' => $request->order_id,
                'customer_id' => $customerId,
                'product_id' => $request->product_id,
                'cart_id' => $request->cart_id,
                'quantity' => $request->quantity,
                'reason' => $request->reason,
                'details' => $request->details,
                'refund_amount' => $refundAmount,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            // Add initial tracking
            DB::table('return_tracking')->insert([
                'return_id' => $returnId,
                'step' => 'request_submitted',
                'status' => 'completed',
                'description' => 'Return request submitted by customer',
                'created_at' => now()
            ]);
            
            // Handle image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('returns/images', 'public');
                    
                    DB::table('return_images')->insert([
                        'return_id' => $returnId,
                        'image_path' => $path,
                        'created_at' => now()
                    ]);
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Return request submitted successfully!',
                'return_id' => $returnId,
                'redirect' => route('customer.returns.track', $returnId)
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => \App\Support\ErrorReason::friendly($e, 'Error submitting return request')
            ], 500);
        }
    }
    
    // Track return
    public function track($returnId)
    {
        $customerId = Auth::id();
        
        $return = DB::table('return_requests as rr')
            ->join('vendor_products as vp', 'rr.product_id', '=', 'vp.id')
            ->join('orders as o', 'rr.order_id', '=', 'o.id')
            ->where('rr.id', $returnId)
            ->where('rr.customer_id', $customerId)
            ->select('rr.*', 'vp.name as product_name', 'o.order_date')
            ->first();
        
        if (!$return) {
            abort(404, 'Return request not found');
        }
        
        $tracking = DB::table('return_tracking')
            ->where('return_id', $returnId)
            ->orderBy('created_at', 'asc')
            ->get();
        
        $images = DB::table('return_images')
            ->where('return_id', $returnId)
            ->get();
        
        return view('customer.returns.track', compact('return', 'tracking', 'images'));
    }
    
    // List all returns for customer
    public function index()
    {
        $customerId = Auth::id();
        
        $returns = DB::table('return_requests as rr')
            ->join('vendor_products as vp', 'rr.product_id', '=', 'vp.id')
            ->leftJoin('vendor_product_images as vpi', function($join) {
                $join->on('vp.id', '=', 'vpi.product_id')
                    ->where('vpi.is_primary', 1);
            })
            ->where('rr.customer_id', $customerId)
            ->select(
                'rr.*',
                'vp.name as product_name',
                'vpi.image_path as product_image'
            )
            ->orderBy('rr.created_at', 'desc')
            ->paginate(10);
        
        return view('customer.returns.index', compact('returns'));
    }
    
    // Cancel return request
    public function cancel($returnId)
    {
        try {
            DB::beginTransaction();
            
            $customerId = Auth::id();
            
            $return = DB::table('return_requests')
                ->where('id', $returnId)
                ->where('customer_id', $customerId)
                ->where('status', 'pending')
                ->first();
            
            if (!$return) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot cancel this return request'
                ], 400);
            }
            
            // Update status
            DB::table('return_requests')
                ->where('id', $returnId)
                ->update([
                    'status' => 'cancelled',
                    'updated_at' => now()
                ]);
            
            // Add tracking
            DB::table('return_tracking')->insert([
                'return_id' => $returnId,
                'step' => 'cancelled',
                'status' => 'completed',
                'description' => 'Return request cancelled by customer',
                'created_at' => now()
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Return request cancelled successfully'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => \App\Support\ErrorReason::friendly($e, 'Error cancelling return')
            ], 500);
        }
    }
}