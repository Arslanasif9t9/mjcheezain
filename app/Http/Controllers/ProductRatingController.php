<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ProductRatingController extends Controller
{
    // Update rating to allow re-rating for replaced products
    public function rateProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:vendor_products,id',
            'order_id' => 'required|integer|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'is_replacement' => 'nullable|boolean'
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
            
            // Check if this is a replacement order
            $isReplacement = $request->has('is_replacement') ? $request->is_replacement : false;
            
            if (!$isReplacement) {
                // For original order, check if already rated
                $existingRating = DB::table('product_ratings')
                    ->where('product_id', $request->product_id)
                    ->where('customer_id', Auth::id())
                    ->where('order_id', $request->order_id)
                    ->where('is_replacement', false)
                    ->first();
                
                if ($existingRating) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You have already rated this product for this order.'
                    ], 400);
                }
            }
            
            // Insert rating (allow multiple ratings for replacements)
            DB::table('product_ratings')->insert([
                'product_id' => $request->product_id,
                'customer_id' => Auth::id(),
                'order_id' => $request->order_id,
                'rating' => $request->rating,
                'comment' => $request->comment,
                'is_replacement' => $isReplacement,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            // Calculate new average rating
            $ratings = DB::table('product_ratings')
                ->where('product_id', $request->product_id)
                ->where('is_replacement', false) // Only count original ratings for average
                ->select(DB::raw('AVG(rating) as avg_rating, COUNT(*) as total_ratings'))
                ->first();
            
            // Update vendor_products table with new average rating
            DB::table('vendor_products')
                ->where('id', $request->product_id)
                ->update([
                    'rating' => $ratings->avg_rating,
                    'updated_at' => now()
                ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Rating submitted successfully!',
                'newAverage' => $ratings->avg_rating,
                'totalRatings' => $ratings->total_ratings
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error submitting rating: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // Get rating (check for replacement)
    public function getRating($productId, $orderId)
    {
        // First check for replacement rating
        $replacementRating = DB::table('product_ratings')
            ->where('product_id', $productId)
            ->where('customer_id', Auth::id())
            ->where('order_id', $orderId)
            ->where('is_replacement', true)
            ->select('rating', 'comment')
            ->first();
        
        // If no replacement rating, check original rating
        if (!$replacementRating) {
            $originalRating = DB::table('product_ratings')
                ->where('product_id', $productId)
                ->where('customer_id', Auth::id())
                ->where('order_id', $orderId)
                ->where('is_replacement', false)
                ->select('rating', 'comment')
                ->first();
            
            if ($originalRating) {
                return response()->json([
                    'success' => true,
                    'rating' => $originalRating->rating,
                    'comment' => $originalRating->comment,
                    'is_replacement' => false
                ]);
            }
        } else {
            return response()->json([
                'success' => true,
                'rating' => $replacementRating->rating,
                'comment' => $replacementRating->comment,
                'is_replacement' => true
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'No rating found'
        ], 404);
    }
    
    // Submit replacement request
    // Submit replacement request (updated)
    public function submitReplaceRequest(Request $request)
     {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:vendor_products,id',
            'order_id' => 'required|integer|exists:orders,id',
            'reason' => 'required|string|max:50',
            'details' => 'nullable|string|max:1000'
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
            
            // Check if replacement already exists
            $existingRequest = DB::table('replacement_requests')
                ->where('product_id', $request->product_id)
                ->where('customer_id', Auth::id())
                ->where('order_id', $request->order_id)
                ->whereIn('status', ['pending', 'approved', 'processing'])
                ->first();
            
            if ($existingRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'A replacement request is already in progress for this product.'
                ], 400);
            }
            
            // Insert replacement request
            $replacementId = DB::table('replacement_requests')->insertGetId([
                'product_id' => $request->product_id,
                'customer_id' => Auth::id(),
                'order_id' => $request->order_id,
                'reason' => $request->reason,
                'details' => $request->details,
                'status' => 'pending',
                'current_step' => 'request_submitted',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            // Add first tracking step
            DB::table('replacement_tracking')->insert([
                'replacement_id' => $replacementId,
                'step' => 'request_submitted',
                'status' => 'completed',
                'description' => 'Replacement request submitted by customer',
                'created_at' => now()
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Replacement request submitted successfully!',
                'replacement_id' => $replacementId
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error submitting request: ' . $e->getMessage()
            ], 500);
        }
    }

    // Get replacement tracking information
    public function getReplacementTracking($replacementId)
    {
        try {
            // Get replacement details
            $replacement = DB::table('replacement_requests')
                ->where('id', $replacementId)
                ->where('customer_id', Auth::id())
                // ->orderByDesc('created_at')
                ->first();
            
            if (!$replacement) {
                return response()->json([
                    'success' => false,
                    'message' => 'Replacement request not found'
                ], 404);
            }
            
            // Get all tracking steps for this replacement
            $trackingStepsRaw = DB::table('replacement_tracking')
                ->where('replacement_id', $replacementId)
                ->orderBy('created_at', 'asc')
                // ->orderByDesc('created_at')
                ->get();
            // dd($trackingStepsRaw);
            
            // Organize tracking steps by step name
            $trackingSteps = [];
            foreach ($trackingStepsRaw as $step) {
                $trackingSteps[$step->step] = $step->created_at;
            }
            
            return response()->json([
                'success' => true,
                'replacement' => $replacement,
                'trackingSteps' => $trackingSteps
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading tracking: ' . $e->getMessage()
            ], 500);
        }
    }

     // Mark replacement as shipped by customer
    public function markReplacementShipped($replacementId)
    {
        try {
            DB::beginTransaction();
            
            // Verify customer owns this replacement
            $replacement = DB::table('replacement_requests')
                ->where('id', $replacementId)
                ->where('customer_id', Auth::id())
                ->first();
            
            if (!$replacement) {
                return response()->json([
                    'success' => false,
                    'message' => 'Replacement request not found'
                ], 404);
            }
            
            if ($replacement->current_step !== 'request_approved') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot mark as shipped at this stage'
                ], 400);
            }
            
            // Update replacement step
            DB::table('replacement_requests')
                ->where('id', $replacementId)
                ->update([
                    'current_step' => 'shipped_to_vendor',
                    'updated_at' => now()
                ]);
            
            // Add tracking step
            DB::table('replacement_tracking')->insert([
                'replacement_id' => $replacementId,
                'step' => 'shipped_to_vendor',
                'status' => 'completed',
                'description' => 'Customer marked original item as shipped',
                'created_at' => now()
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error updating status: ' . $e->getMessage()
            ], 500);
        }
    }

    // Cancel replacement request
    public function cancelReplacement($replacementId)
    {
        try {
            DB::beginTransaction();
            
            // Verify customer owns this replacement
            $replacement = DB::table('replacement_requests')
                ->where('id', $replacementId)
                ->where('customer_id', Auth::id())
                ->first();
            
            if (!$replacement) {
                return response()->json([
                    'success' => false,
                    'message' => 'Replacement request not found'
                ], 404);
            }
            
            // Check if cancellation is allowed
            if (!in_array($replacement->status, ['pending', 'approved'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot cancel request at this stage'
                ], 400);
            }
            
            // Update replacement status
            DB::table('replacement_requests')
                ->where('id', $replacementId)
                ->update([
                    'status' => 'cancelled',
                    'current_step' => 'cancelled',
                    'updated_at' => now()
                ]);
            
            // Add cancellation tracking step
            DB::table('replacement_tracking')->insert([
                'replacement_id' => $replacementId,
                'step' => 'cancelled',
                'status' => 'completed',
                'description' => 'Replacement request cancelled by customer',
                'created_at' => now()
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Replacement request cancelled successfully'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error cancelling request: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // Initiate return request
    public function initiateReturn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:vendor_products,id',
            'order_id' => 'required|integer|exists:orders,id',
            'return_type' => 'required|in:return,return_report'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            DB::table('return_requests')->insert([
                'product_id' => $request->product_id,
                'customer_id' => Auth::id(),
                'order_id' => $request->order_id,
                'return_type' => $request->return_type,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            // Here you can add email notification, etc.
            
            return response()->json([
                'success' => true,
                'message' => 'Return request submitted successfully!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error initiating return: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // Cancel order
    public function cancelOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|integer|exists:orders,id',
            'cart_id' => 'required|integer|exists:carts,id'
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
            
            // Update cart status
            DB::table('carts')
                ->where('id', $request->cart_id)
                ->where('order_id', $request->order_id)
                ->update([
                    'status' => 'cancelled',
                    'updated_at' => now()
                ]);
            
            // Check if all items in order are cancelled
            $activeItems = DB::table('carts')
                ->where('order_id', $request->order_id)
                ->where('status', '!=', 'cancelled')
                ->count();
            
            // If no active items, update order status
            if ($activeItems == 0) {
                DB::table('orders')
                    ->where('id', $request->order_id)
                    ->update([
                        'status' => 'cancelled',
                        'updated_at' => now()
                    ]);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Order cancelled successfully!'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error cancelling order: ' . $e->getMessage()
            ], 500);
        }
    }
}