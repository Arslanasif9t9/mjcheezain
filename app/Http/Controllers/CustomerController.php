<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class CustomerController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        // Example: Fetch customer orders
        // $orders = Order::where('user_id', $user->id)->latest()->get();

        return view('customer.dashboard');
    }
}
