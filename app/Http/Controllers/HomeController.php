<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $user = null;
        $profile = null;
        $dashboardPage = null;
        $imgPath = 'img/default_profile.webp';

        if (Auth::check()) {
            $user = Auth::user();

            if ($user->type == 'vendor') {
                $profile = $user->vendorProfile;
                $imgPath = $profile && $profile->profile_picture 
                    ? "vendor/{$profile->profile_picture}" 
                    : "img/default_profile.webp";
                $dashboardPage = route('vendor.dashboard');
            } else {
                $profile = $user->customerProfile;
                $imgPath = $profile && $profile->profile_image 
                    ? "customer/{$profile->profile_image}" 
                    : "img/default_profile.webp";
                $dashboardPage = route('customer.dashboard');
            }
        }

        // 👉 Add this for products
        $products = Product::where('position', 'approved')
            ->whereColumn('mrp', '<', 'selling_price')
            ->with('primaryImage')
            ->select('*')
            ->selectRaw('(selling_price - mrp) AS discount_amount')
            ->orderByDesc('discount_amount')
            ->take(10)
            ->get();

        return view('home.index', compact('user', 'profile', 'dashboardPage', 'imgPath', 'products'));
    }
}
