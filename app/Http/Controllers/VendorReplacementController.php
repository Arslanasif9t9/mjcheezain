<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VendorReplacementController extends Controller
{
    // Show all replacement requests for vendor
    public function index()
    {
        // Get vendor ID (assuming vendor is logged in)
        $vendorId = Auth::id();
        $user = Auth::user();
        // dd($user->full_name);
        $vendor_id = $user->user_id;

        $vendorBasicInfo = DB::table('vendor_basic_info')
            ->where('user_id', $vendor_id)
            ->first();
        
        // Get replacement requests for vendor's products
        $replacements = DB::table('replacement_requests as rr')
            ->join('vendor_products as vp', 'rr.product_id', '=', 'vp.id')
            ->leftJoin('users as u', 'rr.customer_id', '=', 'u.user_id')
            ->leftJoin('customer_profile as cp', 'rr.customer_id', '=', 'cp.user_id')
            ->where('vp.user_id', $vendorId)
            ->select(
                'rr.*',
                'vp.name as product_name',
                'vp.id as product_id',
                'u.email as customer_email',
                'cp.first_name as customer_first_name',
                'cp.last_name as customer_last_name'
            )
            ->orderBy('rr.created_at', 'desc')
            ->paginate(20);
        
        // Calculate statistics
        $stats = [
            'total' => DB::table('replacement_requests as rr')
                ->join('vendor_products as vp', 'rr.product_id', '=', 'vp.id')
                ->where('vp.user_id', $vendorId)
                ->count(),
            
            'pending' => DB::table('replacement_requests as rr')
                ->join('vendor_products as vp', 'rr.product_id', '=', 'vp.id')
                ->where('vp.user_id', $vendorId)
                ->where('rr.status', 'pending')
                ->count(),
            
            'approved' => DB::table('replacement_requests as rr')
                ->join('vendor_products as vp', 'rr.product_id', '=', 'vp.id')
                ->where('vp.user_id', $vendorId)
                ->where('rr.status', 'approved')
                ->count(),
            
            'processing' => DB::table('replacement_requests as rr')
                ->join('vendor_products as vp', 'rr.product_id', '=', 'vp.id')
                ->where('vp.user_id', $vendorId)
                ->where('rr.status', 'processing')
                ->count(),
            
            'completed' => DB::table('replacement_requests as rr')
                ->join('vendor_products as vp', 'rr.product_id', '=', 'vp.id')
                ->where('vp.user_id', $vendorId)
                ->where('rr.status', 'completed')
                ->count(),
            
            'cancelled' => DB::table('replacement_requests as rr')
                ->join('vendor_products as vp', 'rr.product_id', '=', 'vp.id')
                ->where('vp.user_id', $vendorId)
                ->where('rr.status', 'cancelled')
                ->count(),
        ];
        
        return view('vendor.replacements.index', compact('user', 'vendorBasicInfo', 'replacements', 'stats'));
    }
    
    // Show replacement details
    public function show($id)
    {
        $vendorId = Auth::id();
        
        $replacement = DB::table('replacement_requests as rr')
            ->join('vendor_products as vp', 'rr.product_id', '=', 'vp.id')
            ->leftJoin('users as u', 'rr.customer_id', '=', 'u.user_id')
            ->leftJoin('customer_profile as cp', 'rr.customer_id', '=', 'cp.user_id')
            ->where('vp.user_id', $vendorId)
            ->where('rr.id', $id)
            ->select(
                'rr.*',
                'vp.name as product_name',
                'vp.id as product_id',
                'u.email as customer_email',
                'cp.first_name as customer_first_name',
                'cp.last_name as customer_last_name',
                'cp.phone as customer_phone'
            )
            ->first();
        
        if (!$replacement) {
            abort(404, 'Replacement request not found');
        }
        
        // Get tracking history
        $tracking = DB::table('replacement_tracking')
            ->where('replacement_id', $id)
            ->orderBy('created_at', 'asc')
            ->get();
        
        // Get original order details
        $order = DB::table('orders as o')
            ->join('carts as c', 'o.id', '=', 'c.order_id')
            ->where('c.product_id', $replacement->product_id)
            ->where('c.user_id', $replacement->customer_id)
            ->select('o.*')
            ->first();
        
        return view('vendor.replacements.show', compact('replacement', 'tracking', 'order'));
    }
    
    // Update replacement status
    public function updateStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'replacement_id' => 'required|integer|exists:replacement_requests,id',
            'status' => 'required|in:pending,approved,rejected,processing,completed,cancelled',
            'current_step' => 'nullable|string|max:50',
            'tracking_number' => 'nullable|string|max:100',
            'estimated_delivery_date' => 'nullable|date',
            'notes' => 'nullable|string|max:500'
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
            
            $vendorId = Auth::id();
            
            // Verify vendor owns this product
            $replacement = DB::table('replacement_requests as rr')
                ->join('vendor_products as vp', 'rr.product_id', '=', 'vp.id')
                ->where('rr.id', $request->replacement_id)
                ->where('vp.user_id', $vendorId)
                ->first();
            
            if (!$replacement) {
                return response()->json([
                    'success' => false,
                    'message' => 'Replacement request not found or access denied'
                ], 404);
            }
            
            // Prepare update data
            $statusToStep = [
                'pending'    => 'request_submitted',
                // 'request_approved' matches the customer-side guard in
                // ProductRatingController::markReplacementShipped and the admin panel vocabulary.
                'approved'   => 'request_approved',
                'processing' => 'replacement_processing',
                'completed'  => 'replacement_delivered',
                'rejected'   => 'request_submitted',
                'cancelled'  => 'request_submitted',
            ];

            $autoStep = $request->filled('current_step')
                ? $request->current_step
                : ($statusToStep[$request->status] ?? 'request_submitted');

            $updateData = [
                'status'       => $request->status,
                'current_step' => $autoStep,
                'updated_at'   => now()
            ];
            
            if ($request->filled('tracking_number')) {
                $updateData['tracking_number'] = $request->tracking_number;
            }
            
            if ($request->filled('estimated_delivery_date')) {
                $updateData['estimated_delivery_date'] = $request->estimated_delivery_date;
            }
            
            // Update replacement
            DB::table('replacement_requests')
                ->where('id', $request->replacement_id)
                ->update($updateData);
            
            // Add to tracking history
            $stepDescription = $this->getStepDescription($request->status, $request->current_step);
            
            DB::table('replacement_tracking')->insert([
                'replacement_id' => $request->replacement_id,
                'step' => $autoStep,
                'status' => 'completed',
                'description' => $request->notes ?? $stepDescription,
                'created_at' => now()
            ]);

            // Notify the customer about the replacement status change (best-effort)
            try {
                $rr = DB::table('replacement_requests')->where('id', $request->replacement_id)->first();
                if ($rr && $rr->customer_id) {
                    $productName = DB::table('vendor_products')->where('id', $rr->product_id)->value('name') ?? 'Product';
                    DB::table('notifications')->insert([
                        'user_id' => $rr->customer_id,
                        'title' => 'Replacement update',
                        'message' => "Your replacement request for \"{$productName}\" is now: " . ucwords($request->status) . ".",
                        // notifications.type is an enum('shipping','payment','offer','loyalty','order','message','alert')
                        'type' => 'order',
                        'icon_class' => 'fas fa-bell',
                        'icon_color' => 'bg-pink-100 text-[#E85D85]',
                        'dot_color' => 'bg-[#FF7DA0]',
                        'is_read' => 0,
                        'created_at' => now(),
                    ]);
                }
            } catch (\Throwable $e) {
                \Log::warning('replacement status notify failed: ' . $e->getMessage());
            }

            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Replacement status updated successfully'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => \App\Support\ErrorReason::friendly($e, 'Error updating status')
            ], 500);
        }
    }
    
    // Get step description based on status
    private function getStepDescription($status, $step)
    {
        $descriptions = [
            'pending' => 'Replacement request is pending review',
            'approved' => 'Replacement request approved by vendor',
            'rejected' => 'Replacement request rejected by vendor',
            'processing' => 'Replacement is being processed',
            'completed' => 'Replacement completed successfully',
            'cancelled' => 'Replacement request cancelled',
            'request_submitted' => 'Request submitted by customer',
            'request_approved' => 'Request approved',
            'shipped_to_vendor' => 'Original item shipped to vendor',
            'received_by_vendor' => 'Original item received by vendor',
            'replacement_verified' => 'Issue verified, preparing replacement',
            'replacement_processing' => 'Replacement item being prepared',
            'replacement_shipped' => 'Replacement item shipped',
            'replacement_delivered' => 'Replacement item delivered'
        ];
        
        return $descriptions[$step] ?? $descriptions[$status] ?? 'Status updated';
    }
    
    // Filter replacements by status
    public function filter($status)
    {
        $vendorId = Auth::id();
        
        $replacements = DB::table('replacement_requests as rr')
            ->join('vendor_products as vp', 'rr.product_id', '=', 'vp.id')
            ->leftJoin('users as u', 'rr.customer_id', '=', 'u.user_id')
            ->leftJoin('customer_profile as cp', 'rr.customer_id', '=', 'cp.user_id')
            ->where('vp.user_id', $vendorId)
            ->where('rr.status', $status)
            ->select(
                'rr.*',
                'vp.name as product_name',
                'vp.id as product_id',
                'u.email as customer_email',
                'cp.first_name as customer_first_name',
                'cp.last_name as customer_last_name'
            )
            ->orderBy('rr.created_at', 'desc')
            ->paginate(20);
        
        $user = (object) ['user_id' => $vendorId];
        return view('vendor.replacements.partials.table', compact('user', 'replacements'))->render();
    }
}