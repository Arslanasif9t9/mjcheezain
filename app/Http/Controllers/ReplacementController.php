<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * Customer-facing "Request Replacement" flow.
 *
 * Owner policy (implemented exactly, see project instructions):
 * - Replacement is NOT a refund mechanism — there is no cash-back path here, full stop.
 * - The replacement product must come from the SAME vendor/store as the original order.
 * - The replacement product's price must be the same as or higher than the original
 *   product's price — no "downgrade and pocket the difference".
 * - If the replacement costs more, the customer pays the price difference
 *   (Additional Amount Payable) before/as part of the replacement being processed.
 * - Courier cost attribution mirrors the return flow: derived from the replacement
 *   reason (vendor's fault -> vendor pays courier; customer's own choice -> customer
 *   pays). When the vendor pays, delivery is excluded from the customer's total.
 *
 * All money math here is authoritative — the create-form's live preview is UX only.
 */
class ReplacementController extends Controller
{
    // Reasons that indicate the ORIGINAL issue was the vendor's fault -> vendor pays
    // the replacement courier cost. Reasons that are the customer's own choice (or not
    // clearly the vendor's fault) -> customer pays. Mirrors ReturnController::store().
    const VENDOR_FAULT_REASONS = [
        'wrong_product',
        'damaged_product',
        'defective_product',
        'different_from_description',
        'missing_part',
    ];

    const REASONS = [
        'wrong_product',
        'damaged_product',
        'defective_product',
        'different_from_description',
        'missing_part',
        'size_color_issue',
        'other',
    ];

    // Flat delivery fee — same flat-per-order rule used across checkout (Rs 300 unless
    // the item is vendor-marked free_delivery). See CheckoutController::checkout().
    const FLAT_DELIVERY_FEE = 300;

    // Show the replacement request form for one purchased line item.
    public function create($orderId, $cartId)
    {
        $customerId = Auth::id();

        $order = DB::table('orders')
            ->where('id', $orderId)
            ->where('user_id', $customerId)
            ->first();

        if (!$order) {
            abort(404, 'Order not found');
        }

        $cart = DB::table('carts as c')
            ->join('vendor_products as vp', 'c.product_id', '=', 'vp.id')
            ->leftJoin('vendor_product_images as vpi', function ($join) {
                $join->on('vp.id', '=', 'vpi.product_id')
                    ->where('vpi.is_primary', 1);
            })
            ->where('c.id', $cartId)
            ->where('c.order_id', $orderId)
            ->where('c.user_id', $customerId)
            ->select('c.*', 'vp.name as product_name', 'vp.user_id as vendor_id', 'vpi.image_path as product_image')
            ->first();

        if (!$cart) {
            abort(404, 'Item not found');
        }

        // Block a duplicate active request for the same purchased item.
        $existing = DB::table('replacement_requests')
            ->where('cart_id', $cartId)
            ->where('customer_id', $customerId)
            ->whereIn('status', ['pending', 'approved', 'processing'])
            ->first();

        if ($existing) {
            return redirect()->route('customer.orders')->with('error', 'A replacement request is already in progress for this item');
        }

        // Same-store candidates, priced at or above what the customer paid for the
        // original item — the picker itself is filtered, store() re-validates too.
        $replacementCandidates = DB::table('vendor_products as vp')
            ->leftJoin('vendor_product_images as vpi', function ($join) {
                $join->on('vp.id', '=', 'vpi.product_id')
                    ->where('vpi.is_primary', 1);
            })
            ->where('vp.user_id', $cart->vendor_id)
            ->where('vp.status', 'approved')
            ->where('vp.selling_price', '>=', $cart->price)
            ->select('vp.id', 'vp.name', 'vp.selling_price', 'vp.free_delivery', 'vpi.image_path')
            ->orderBy('vp.selling_price')
            ->get();

        return view('customer.replacements.create', compact('order', 'cart', 'replacementCandidates'));
    }

    // Submit replacement request.
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|integer|exists:orders,id',
            'cart_id' => 'required|integer|exists:carts,id',
            'product_id' => 'required|integer|exists:vendor_products,id',
            'replacement_product_id' => 'required|integer|exists:vendor_products,id',
            'reason' => 'required|in:' . implode(',', self::REASONS),
            'details' => 'nullable|string|max:1000',
            'images.*' => 'nullable|image|max:5120',
            'video' => 'nullable|mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/webm|max:51200',
            'customer_confirmed' => 'required|accepted',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            $customerId = Auth::id();

            $order = DB::table('orders')->where('id', $request->order_id)->where('user_id', $customerId)->first();
            if (!$order) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Order not found'], 404);
            }

            $cart = DB::table('carts')
                ->where('id', $request->cart_id)
                ->where('order_id', $request->order_id)
                ->where('user_id', $customerId)
                ->first();
            if (!$cart || (int) $cart->product_id !== (int) $request->product_id) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Item not found in this order'], 404);
            }

            $existing = DB::table('replacement_requests')
                ->where('cart_id', $request->cart_id)
                ->where('customer_id', $customerId)
                ->whereIn('status', ['pending', 'approved', 'processing'])
                ->first();
            if ($existing) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'A replacement request is already in progress for this item'], 400);
            }

            $originalProduct = DB::table('vendor_products')->where('id', $request->product_id)->first();
            $replacementProduct = DB::table('vendor_products')->where('id', $request->replacement_product_id)->first();

            if (!$originalProduct || !$replacementProduct) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Product not found'], 404);
            }

            // Policy: same store only.
            if ((int) $replacementProduct->user_id !== (int) $originalProduct->user_id) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'You can only replace with a product from the same store.',
                ], 422);
            }

            // Server-side source of truth for pricing: what the customer actually paid
            // (cart.price) vs. the replacement product's current selling price.
            $originalPrice = (float) $cart->price;
            $replacementPrice = (float) $replacementProduct->selling_price;

            // Policy: no downgrade — replacement must cost the same or more.
            if ($replacementPrice < $originalPrice) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'You can only replace with a product of the same or higher price from the same store.',
                ], 422);
            }

            $additionalAmountPayable = max(0, round($replacementPrice - $originalPrice, 2));

            // Courier cost attribution, derived from the replacement reason (owner policy):
            // reasons pointing at the vendor's fault -> vendor pays the replacement courier;
            // reasons that are the customer's own choice ("size/color issue", "other") ->
            // customer pays. Mirrors ReturnController::store().
            $courierPaidBy = in_array($request->reason, self::VENDOR_FAULT_REASONS, true) ? 'vendor' : 'customer';

            // Flat delivery fee, waived if the replacement product is vendor-marked
            // free_delivery — same rule as checkout. If the vendor is paying the courier
            // (their fault), it is not added to what the customer owes.
            $deliveryCharge = $replacementProduct->free_delivery ? 0 : self::FLAT_DELIVERY_FEE;
            $totalAmountPayable = $additionalAmountPayable + ($courierPaidBy === 'customer' ? $deliveryCharge : 0);

            $referenceNumber = 'REP-' . now()->format('Ymd') . '-' . str_pad((string) (DB::table('replacement_requests')->count() + 1), 4, '0', STR_PAD_LEFT);
            // Guarantee uniqueness even under a race — extremely unlikely but cheap to guard.
            while (DB::table('replacement_requests')->where('reference_number', $referenceNumber)->exists()) {
                $referenceNumber = 'REP-' . now()->format('Ymd') . '-' . str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
            }

            $replacementId = DB::table('replacement_requests')->insertGetId([
                'product_id' => $request->product_id,
                'customer_id' => $customerId,
                'order_id' => $request->order_id,
                'cart_id' => $request->cart_id,
                'reason' => $request->reason,
                'details' => $request->details,
                'replacement_product_id' => $request->replacement_product_id,
                'original_price' => $originalPrice,
                'replacement_price' => $replacementPrice,
                'additional_amount_payable' => $additionalAmountPayable,
                'delivery_charge' => $deliveryCharge,
                'total_amount_payable' => $totalAmountPayable,
                'courier_paid_by' => $courierPaidBy,
                'customer_confirmed' => true,
                'reference_number' => $referenceNumber,
                'status' => 'pending',
                'current_step' => 'request_submitted',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('replacement_tracking')->insert([
                'replacement_id' => $replacementId,
                'step' => 'request_submitted',
                'status' => 'completed',
                'description' => 'Replacement request submitted by customer',
                'created_at' => now(),
            ]);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('replacements/images', 'public');
                    DB::table('replacement_images')->insert([
                        'replacement_id' => $replacementId,
                        'image_path' => $path,
                        'created_at' => now(),
                    ]);
                }
            }

            if ($request->hasFile('video')) {
                $videoPath = $request->file('video')->store('replacements/videos', 'public');
                DB::table('replacement_requests')->where('id', $replacementId)->update(['video_path' => $videoPath]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Replacement request submitted successfully!',
                'replacement_id' => $replacementId,
                'reference_number' => $referenceNumber,
                'redirect' => route('customer.orders'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => \App\Support\ErrorReason::friendly($e, 'Error submitting replacement request'),
            ], 500);
        }
    }

    // JSON list of same-store replacement candidates (price >= original), used by the
    // create-form's picker. Server-side validation in store() is the real gate — this
    // is just a convenience/UX filter.
    public function products($orderId, $cartId)
    {
        $customerId = Auth::id();

        $cart = DB::table('carts as c')
            ->join('vendor_products as vp', 'c.product_id', '=', 'vp.id')
            ->where('c.id', $cartId)
            ->where('c.order_id', $orderId)
            ->where('c.user_id', $customerId)
            ->select('c.price', 'vp.user_id as vendor_id', 'vp.id as product_id')
            ->first();

        if (!$cart) {
            return response()->json(['success' => false, 'message' => 'Item not found'], 404);
        }

        $products = DB::table('vendor_products as vp')
            ->leftJoin('vendor_product_images as vpi', function ($join) {
                $join->on('vp.id', '=', 'vpi.product_id')->where('vpi.is_primary', 1);
            })
            ->where('vp.user_id', $cart->vendor_id)
            ->where('vp.status', 'approved')
            ->where('vp.selling_price', '>=', $cart->price)
            ->select('vp.id', 'vp.name', 'vp.selling_price', 'vp.free_delivery', 'vpi.image_path')
            ->orderBy('vp.selling_price')
            ->get();

        return response()->json(['success' => true, 'products' => $products, 'original_price' => (float) $cart->price]);
    }
}
