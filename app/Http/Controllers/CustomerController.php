<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;

class CustomerController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $basic_info = DB::table('customer_profile')
                        ->where('user_id', $user->user_id)
                        ->first();
        // Example: Fetch customer orders
        // $orders = Order::where('user_id', $user->id)->latest()->get();

        return view('customer.dashboard', compact([
            'basic_info'
        ]));
    }
}
