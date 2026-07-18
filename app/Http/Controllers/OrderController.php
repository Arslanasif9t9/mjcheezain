<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function getOrders(Request $request)
    {
        $status = $request->get('status', 'all');
        $user_id = Auth::id() ?? 1; // Use authenticated user ID

        $query = DB::table('orders')
            ->select([
                'orders.id AS order_id',
                'orders.order_date',
                'orders.quantity',
                'orders.total_amount',
                'orders.status',
                'orders.fulfillment'
            ])
            ->where('orders.user_id', $user_id);

        // Add fulfillment-state filter if not 'all' (tabs are order states, not payment status)
        if ($status !== 'all') {
            $query->where('orders.fulfillment', $status);
        }

        $orders = $query->get();

        return response()->json($orders);
    }
}