<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class VendorController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        return view('vendor.dashboard');
    }
}
