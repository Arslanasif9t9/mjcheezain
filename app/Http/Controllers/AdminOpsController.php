<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Admin operations: orders oversight, withdraw requests, customers,
 * returns & replacements. All routes wrapped in admin.auth middleware.
 */
class AdminOpsController extends Controller
{
    /** Line-item fulfillment vocabulary (matches what the vendor panel writes to carts.status). */
    const ORDER_STATUSES = ['order placed', 'processing', 'shipping', 'delivered', 'cancelled'];

    private function notify($userId, $title, $message, $type = 'order')
    {
        try {
            DB::table('notifications')->insert([
                'user_id' => $userId,
                'title' => $title,
                'message' => $message,
                'type' => $type,
                'icon_class' => 'fas fa-bell',
                'icon_color' => 'bg-pink-100 text-[#E85D85]',
                'dot_color' => 'bg-[#FF7DA0]',
                'is_read' => 0,
                'created_at' => now(),
            ]);
        } catch (\Throwable $e) {
            \Log::warning('admin notify failed: ' . $e->getMessage());
        }
    }

    public function orders()
    {
        $rows = DB::table('carts as c')
            ->whereNotNull('c.order_id')
            ->leftJoin('users as u', 'c.user_id', '=', 'u.user_id')
            ->leftJoin('vendor_products as p', 'c.product_id', '=', 'p.id')
            ->leftJoin('users as v', 'p.user_id', '=', 'v.user_id')
            ->leftJoin('vendor_product_images as pi', function ($join) {
                $join->on('pi.product_id', '=', 'p.id')->where('pi.is_primary', '=', 1);
            })
            ->select(
                'c.id', 'c.order_id', 'c.quantity', 'c.price', 'c.status', 'c.created_at',
                'u.full_name as customer_name', 'u.email as customer_email',
                'p.id as product_id', 'p.name as product_name', 'pi.image_path as product_image',
                'v.full_name as vendor_name'
            )
            ->orderByDesc('c.created_at')
            ->limit(300)
            ->get();

        $stats = [
            'total' => DB::table('carts')->whereNotNull('order_id')->count(),
            'placed' => DB::table('carts')->whereNotNull('order_id')->where(function ($q) {
                $q->whereNull('status')->orWhere('status', 'order placed');
            })->count(),
            'processing' => DB::table('carts')->whereNotNull('order_id')->where('status', 'processing')->count(),
            'shipping' => DB::table('carts')->whereNotNull('order_id')->where('status', 'shipping')->count(),
            'delivered' => DB::table('carts')->whereNotNull('order_id')->where('status', 'delivered')->count(),
            'cancelled' => DB::table('carts')->whereNotNull('order_id')->where('status', 'cancelled')->count(),
        ];

        return view('Admin.order_management', ['orders' => $rows, 'stats' => $stats, 'statuses' => self::ORDER_STATUSES]);
    }

    public function updateOrderStatus(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|integer',
            'status' => 'required|in:' . implode(',', self::ORDER_STATUSES),
        ]);

        $cart = DB::table('carts')->where('id', $request->cart_id)->whereNotNull('order_id')->first();
        if (!$cart) return response()->json(['success' => false, 'message' => 'Order item not found.'], 404);

        DB::table('carts')->where('id', $cart->id)->update(['status' => $request->status, 'updated_at' => now()]);

        $product = DB::table('vendor_products')->where('id', $cart->product_id)->first();
        $label = ucwords($request->status);
        $this->notify($cart->user_id, 'Order update', "Aapke order item \"" . ($product->name ?? 'Product') . "\" ka status ab: {$label} (admin update).");
        if ($product) {
            $this->notify($product->user_id, 'Order update by admin', "Order item \"{$product->name}\" ka status admin ne {$label} kar diya hai.");
        }

        return response()->json(['success' => true, 'message' => "Status updated to {$label}."]);
    }

    public function withdrawRequests()
    {
        $rows = DB::table('withdrawal_requests as w')
            ->leftJoin('users as u', 'w.vendor_id', '=', 'u.user_id')
            ->select('w.*', 'u.full_name as vendor_name', 'u.email as vendor_email')
            ->orderByDesc('w.requested_at')
            ->get();

        $stats = [
            'pending' => $rows->where('status', 'pending')->count(),
            'approved' => $rows->where('status', 'approved')->count(),
            'rejected' => $rows->where('status', 'rejected')->count(),
            'paid' => $rows->where('status', 'paid')->count(),
            'pending_amount' => $rows->where('status', 'pending')->sum('amount'),
        ];

        return view('Admin.withdraw_requests', compact('rows', 'stats'));
    }

    public function updateWithdrawStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,paid',
            'admin_notes' => 'nullable|string|max:500',
            'transaction_id' => 'nullable|string|max:120',
        ]);

        $w = DB::table('withdrawal_requests')->where('id', $id)->first();
        if (!$w) return response()->json(['success' => false, 'message' => 'Request not found.'], 404);

        DB::table('withdrawal_requests')->where('id', $id)->update([
            'status' => $request->status,
            'admin_notes' => $request->input('admin_notes', $w->admin_notes),
            'transaction_id' => $request->input('transaction_id', $w->transaction_id),
            'processed_at' => in_array($request->status, ['approved', 'rejected', 'paid']) ? now() : $w->processed_at,
        ]);

        $labels = [
            'pending' => 'dobara pending kar di gayi hai',
            'approved' => 'approve ho gayi hai — payment jald process hogi',
            'rejected' => 'reject kar di gayi hai',
            'paid' => 'pay kar di gayi hai',
        ];
        $this->notify($w->vendor_id, 'Withdraw request update', 'Aapki Rs. ' . number_format($w->amount) . ' ki withdraw request ' . $labels[$request->status] . '.', 'withdraw');

        return response()->json(['success' => true, 'message' => 'Withdraw request updated.']);
    }

    public function customers()
    {
        $rows = DB::table('users as u')
            ->where('u.type', 'customer')
            ->leftJoin('customer_profile as cp', 'u.user_id', '=', 'cp.user_id')
            ->leftJoin(DB::raw('(SELECT user_id, COUNT(*) as order_count FROM orders GROUP BY user_id) as o'), 'o.user_id', '=', 'u.user_id')
            ->select('u.user_id', 'u.full_name', 'u.email', 'u.phone', 'u.created_at', 'cp.profile_image', DB::raw('COALESCE(o.order_count, 0) as order_count'))
            ->orderByDesc('u.created_at')
            ->get();

        $stats = [
            'total' => $rows->count(),
            'with_orders' => $rows->where('order_count', '>', 0)->count(),
            'new_30d' => $rows->filter(fn ($r) => $r->created_at && strtotime($r->created_at) > strtotime('-30 days'))->count(),
        ];

        return view('Admin.customers', compact('rows', 'stats'));
    }

    public function returns()
    {
        $rows = DB::table('return_requests as r')
            ->leftJoin('users as u', 'r.customer_id', '=', 'u.user_id')
            ->leftJoin('vendor_products as p', 'r.product_id', '=', 'p.id')
            ->leftJoin('users as v', 'p.user_id', '=', 'v.user_id')
            ->select('r.*', 'u.full_name as customer_name', 'p.name as product_name', 'v.full_name as vendor_name')
            ->orderByDesc('r.created_at')
            ->get();

        $stats = [
            'total' => $rows->count(),
            'pending' => $rows->whereIn('status', ['pending', 'vendor_reviewed'])->count(),
            'approved' => $rows->where('status', 'approved')->count(),
            'rejected' => $rows->where('status', 'rejected')->count(),
            'refunded' => $rows->whereIn('status', ['refunded', 'completed'])->count(),
        ];

        return view('Admin.returns', compact('rows', 'stats'));
    }

    // Only Admin can set a final decision on a return (approve/reject) or move it through
    // the post-approval flow. The vendor can only leave a comment (see
    // VendorReturnController::updateStatus) — they never reach this endpoint.
    //
    // Full flow: pending/vendor_reviewed -> approved -> processing (pickup/courier in
    // progress) -> received (product physically back) -> Quality Check:
    //   Pass -> refunded (terminal, customer refunded)
    //   Fail -> qc_failed (terminal, no refund, product goes back to the customer)
    // rejected and qc_failed are both terminal "no refund" outcomes and can never be reopened
    // or slide into the refund pipeline.
    public function updateReturnStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:pending,vendor_reviewed,approved,rejected,processing,received,qc_failed,refunded,completed']);

        $r = DB::table('return_requests')->where('id', $id)->first();
        if (!$r) return response()->json(['success' => false, 'message' => 'Return request not found.'], 404);

        // Terminal "no refund" outcomes must never be reopened.
        $terminal = ['rejected', 'qc_failed'];
        if (in_array($r->status, $terminal, true) && $request->status !== $r->status) {
            return response()->json([
                'success' => false,
                'message' => 'This return was already ' . str_replace('_', ' ', $r->status) . ' and cannot be reopened.'
            ], 422);
        }

        // A rejected (or still undecided) return must never slide into the pickup/received/
        // refund/completed pipeline — Admin has to explicitly approve it first. This is what
        // keeps "reject = no refund, no return proceeds" true regardless of what gets posted here.
        $postApprovalStates = ['processing', 'received', 'refunded', 'completed', 'qc_failed'];
        if (in_array($request->status, $postApprovalStates, true) && !in_array($r->status, array_merge(['approved'], $postApprovalStates), true)) {
            return response()->json([
                'success' => false,
                'message' => 'This return must be approved before it can move to "' . $request->status . '".'
            ], 422);
        }

        // The Quality Check verdict (pass -> refunded, fail -> qc_failed) can only be recorded
        // once the product has been marked physically received back.
        if (in_array($request->status, ['refunded', 'qc_failed'], true) && !in_array($r->status, ['received', 'refunded', 'qc_failed', 'completed'], true)) {
            return response()->json([
                'success' => false,
                'message' => 'Mark this return "received" first, then record the quality-check result.'
            ], 422);
        }

        DB::table('return_requests')->where('id', $id)->update(['status' => $request->status, 'updated_at' => now()]);

        try {
            $stepMap = [
                'pending' => 'under_review',
                'vendor_reviewed' => 'under_review',
                'approved' => 'approved',
                'rejected' => 'rejected',
                'processing' => 'refund_processing',
                'received' => 'item_received',
                'qc_failed' => 'qc_failed',
                'refunded' => 'refunded',
                'completed' => 'completed',
            ];
            $descriptions = [
                'received' => 'Product received back — under quality check.',
                'qc_failed' => 'Quality check failed — return rejected, product is being sent back to the customer.',
                'refunded' => 'Quality check passed — refund processed.',
            ];
            DB::table('return_tracking')->insert([
                'return_id' => $id,
                'step' => $stepMap[$request->status] ?? 'under_review',
                'status' => 'completed',
                'description' => $descriptions[$request->status] ?? 'Updated by admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable $e) {
            \Log::warning('return_tracking insert (admin) failed: ' . $e->getMessage());
        }

        $notifyMessages = [
            'received' => 'Aapka return item humein mil gaya hai — ab quality check ho raha hai.',
            'qc_failed' => 'Quality check mein return item pass nahi hua — refund nahi hoga, item aapko wapas bheja ja raha hai.',
            'refunded' => 'Quality check pass ho gaya — aapka refund process kar diya gaya hai.',
        ];
        $this->notify(
            $r->customer_id,
            'Return update',
            $notifyMessages[$request->status] ?? ('Aapki return request ka status ab: ' . ucfirst($request->status) . ' (admin review).'),
            'return'
        );

        return response()->json(['success' => true, 'message' => 'Return status updated.']);
    }

    public function replacements()
    {
        $rows = DB::table('replacement_requests as r')
            ->leftJoin('users as u', 'r.customer_id', '=', 'u.user_id')
            ->leftJoin('vendor_products as p', 'r.product_id', '=', 'p.id')
            ->leftJoin('users as v', 'p.user_id', '=', 'v.user_id')
            ->select('r.*', 'u.full_name as customer_name', 'p.name as product_name', 'v.full_name as vendor_name')
            ->orderByDesc('r.created_at')
            ->get();

        $stats = [
            'total' => $rows->count(),
            'pending' => $rows->where('status', 'pending')->count(),
            'approved' => $rows->where('status', 'approved')->count(),
            'completed' => $rows->where('status', 'completed')->count(),
            'rejected' => $rows->where('status', 'rejected')->count(),
        ];

        return view('Admin.replacements', compact('rows', 'stats'));
    }

    public function updateReplacementStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:pending,approved,rejected,processing,completed,cancelled']);

        $r = DB::table('replacement_requests')->where('id', $id)->first();
        if (!$r) return response()->json(['success' => false, 'message' => 'Replacement request not found.'], 404);

        $stepMap = [
            'pending' => 'request_submitted',
            'approved' => 'request_approved',
            'rejected' => 'rejected',
            'processing' => 'replacement_processing',
            'completed' => 'replacement_delivered',
            'cancelled' => 'cancelled',
        ];

        DB::table('replacement_requests')->where('id', $id)->update([
            'status' => $request->status,
            'current_step' => $stepMap[$request->status],
            'updated_at' => now(),
        ]);

        try {
            DB::table('replacement_tracking')->insert([
                'replacement_id' => $id,
                'step' => $stepMap[$request->status],
                'status' => 'completed',
                'description' => 'Updated by admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable $e) {
            \Log::warning('replacement_tracking insert (admin) failed: ' . $e->getMessage());
        }

        $this->notify($r->customer_id, 'Replacement update', 'Aapki replacement request ka status ab: ' . ucfirst($request->status) . ' (admin review).', 'replacement');

        return response()->json(['success' => true, 'message' => 'Replacement status updated.']);
    }
}
