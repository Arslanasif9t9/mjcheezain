<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VendorReturnController extends Controller
{
    // List all return requests for vendor
    public function index()
    {
        $vendorId = Auth::id();
        
        $returns = DB::table('return_requests as rr')
            ->join('vendor_products as vp', 'rr.product_id', '=', 'vp.id')
            ->join('orders as o', 'rr.order_id', '=', 'o.id')
            ->leftJoin('users as u', 'rr.customer_id', '=', 'u.user_id')
            ->leftJoin('customer_profile as cp', 'rr.customer_id', '=', 'cp.user_id')
            ->where('vp.user_id', $vendorId)
            ->select(
                'rr.*',
                'vp.name as product_name',
                'o.order_date',
                'u.email as customer_email',
                'cp.first_name as customer_first_name',
                'cp.last_name as customer_last_name'
            )
            ->orderBy('rr.created_at', 'desc')
            ->paginate(20);
        
        // Statistics
        $stats = [
            'total' => DB::table('return_requests as rr')
                ->join('vendor_products as vp', 'rr.product_id', '=', 'vp.id')
                ->where('vp.user_id', $vendorId)
                ->count(),
            
            'pending' => DB::table('return_requests as rr')
                ->join('vendor_products as vp', 'rr.product_id', '=', 'vp.id')
                ->where('vp.user_id', $vendorId)
                ->where('rr.status', 'pending')
                ->count(),
            
            'approved' => DB::table('return_requests as rr')
                ->join('vendor_products as vp', 'rr.product_id', '=', 'vp.id')
                ->where('vp.user_id', $vendorId)
                ->where('rr.status', 'approved')
                ->count(),
            
            'processing' => DB::table('return_requests as rr')
                ->join('vendor_products as vp', 'rr.product_id', '=', 'vp.id')
                ->where('vp.user_id', $vendorId)
                ->where('rr.status', 'processing')
                ->count(),
            
            'refunded' => DB::table('return_requests as rr')
                ->join('vendor_products as vp', 'rr.product_id', '=', 'vp.id')
                ->where('vp.user_id', $vendorId)
                ->where('rr.status', 'refunded')
                ->count(),
            
            'completed' => DB::table('return_requests as rr')
                ->join('vendor_products as vp', 'rr.product_id', '=', 'vp.id')
                ->where('vp.user_id', $vendorId)
                ->where('rr.status', 'completed')
                ->count(),
            
            'rejected' => DB::table('return_requests as rr')
                ->join('vendor_products as vp', 'rr.product_id', '=', 'vp.id')
                ->where('vp.user_id', $vendorId)
                ->where('rr.status', 'rejected')
                ->count(),
        ];
        
        // $user = (object) ['user_id' => $vendorId];
        $user = (object) ['user_id' => $vendorId];
        return view('vendor.returns.index', compact('returns', 'stats', 'user'));
    }
    
    // View return details
    public function show($id)
    {
        $vendorId = Auth::id();
        
        $return = DB::table('return_requests as rr')
            ->join('vendor_products as vp', 'rr.product_id', '=', 'vp.id')
            ->join('orders as o', 'rr.order_id', '=', 'o.id')
            ->leftJoin('users as u', 'rr.customer_id', '=', 'u.user_id')
            ->leftJoin('customer_profile as cp', 'rr.customer_id', '=', 'cp.user_id')
            ->where('vp.user_id', $vendorId)
            ->where('rr.id', $id)
            ->select(
                'rr.*',
                'vp.name as product_name',
                'o.order_date',
                'u.email as customer_email',
                'cp.first_name as customer_first_name',
                'cp.last_name as customer_last_name',
                'cp.phone as customer_phone'
                // 'cp.address as customer_address'
            )
            ->first();
        
        if (!$return) {
            abort(404, 'Return request not found');
        }
        
        $tracking = DB::table('return_tracking')
            ->where('return_id', $id)
            ->orderBy('created_at', 'asc')
            ->get();
        
        $images = DB::table('return_images')
            ->where('return_id', $id)
            ->get();
        $user = (object) ['user_id' => $vendorId];
        return view('vendor.returns.show', compact('user', 'return', 'tracking', 'images'));
    }
    
    // Vendor comment on a return request.
    //
    // Policy: the vendor is NOT allowed to unilaterally approve/reject/refund/complete a
    // return — only Admin (AdminOpsController::updateReturnStatus) can set that final
    // decision. The vendor's role here is limited to giving their opinion on the request
    // (e.g. "this is not our fault, customer opened the seal"). Submitting a comment moves
    // a still-pending request to 'vendor_reviewed' so Admin knows the vendor has responded;
    // it never sets approved/rejected/processing/refunded/completed.
    public function updateStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'return_id' => 'required|integer|exists:return_requests,id',
            'comment' => 'required|string|max:1000',
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
            $return = DB::table('return_requests as rr')
                ->join('vendor_products as vp', 'rr.product_id', '=', 'vp.id')
                ->where('rr.id', $request->return_id)
                ->where('vp.user_id', $vendorId)
                ->first();

            if (!$return) {
                return response()->json([
                    'success' => false,
                    'message' => 'Return request not found or access denied'
                ], 404);
            }

            $updateData = [
                'vendor_comment' => $request->comment,
                'vendor_commented_at' => now(),
                'updated_at' => now(),
            ];

            // Only nudge the status forward if Admin hasn't already made a final decision.
            // The vendor can comment again later without overriding Admin's approved/rejected call.
            if ($return->status === 'pending') {
                $updateData['status'] = 'vendor_reviewed';
            }

            DB::table('return_requests')
                ->where('id', $request->return_id)
                ->update($updateData);

            DB::table('return_tracking')->insert([
                'return_id' => $request->return_id,
                'step' => 'vendor_commented',
                'status' => 'completed',
                'description' => 'Vendor commented: ' . $request->comment,
                'created_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Comment submitted. Admin will make the final decision.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => \App\Support\ErrorReason::friendly($e, 'Error submitting comment')
            ], 500);
        }
    }
    
    private function getStepDescription($step, $notes)
    {
        $descriptions = [
            'request_submitted' => 'Return request submitted by customer',
            'under_review' => 'Return request under review',
            'approved' => 'Return request approved',
            'rejected' => 'Return request rejected',
            'pickup_scheduled' => 'Pickup scheduled for return item',
            'pickup_completed' => 'Item picked up for return',
            'item_received' => 'Returned item received by vendor',
            'quality_check' => 'Item undergoing quality inspection',
            'refund_processing' => 'Refund processing initiated',
            'refunded' => 'Refund completed',
            'completed' => 'Return process completed'
        ];
        
        $description = $descriptions[$step] ?? 'Status updated';
        
        if ($notes) {
            $description .= '. Notes: ' . $notes;
        }
        
        return $description;
    }
    
    // Filter returns by status
    public function filter($status)
    {
        $vendorId = Auth::id();
        
        $returns = DB::table('return_requests as rr')
            ->join('vendor_products as vp', 'rr.product_id', '=', 'vp.id')
            ->join('orders as o', 'rr.order_id', '=', 'o.id')
            ->leftJoin('users as u', 'rr.customer_id', '=', 'u.user_id')
            ->leftJoin('customer_profile as cp', 'rr.customer_id', '=', 'cp.user_id')
            ->where('vp.user_id', $vendorId)
            ->when($status !== 'all', function($query) use ($status) {
                return $query->where('rr.status', $status);
            })
            ->select(
                'rr.*',
                'vp.name as product_name',
                'o.order_date',
                'u.email as customer_email',
                'cp.first_name as customer_first_name',
                'cp.last_name as customer_last_name'
            )
            ->orderBy('rr.created_at', 'desc')
            ->paginate(20);
        
        return view('vendor.returns.partials.table', compact('returns'))->render();
    }
}