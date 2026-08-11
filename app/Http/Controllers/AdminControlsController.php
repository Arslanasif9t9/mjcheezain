<?php

namespace App\Http\Controllers;

use App\Support\SiteSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

/**
 * Site-wide feature toggles for the admin panel. Starts with a single
 * control — WhatsApp Buy Now — more can be added to the same page later.
 */
class AdminControlsController extends Controller
{
    private function guard()
    {
        if (!Session::get('admin_logged_in')) {
            abort(response()->json(['success' => false, 'message' => 'Not authorized'], 401));
        }
    }

    private function guardPage()
    {
        if (!Session::get('admin_logged_in')) {
            return redirect('/admin/login');
        }
        return null;
    }

    public function index()
    {
        if ($r = $this->guardPage()) return $r;

        $whatsappBuyNowEnabled = SiteSettings::get('whatsapp_buy_now_enabled', '0') === '1';
        $whatsappBuyNowNumber = SiteSettings::get('whatsapp_buy_now_number', '');

        return view('Admin.controls', compact('whatsappBuyNowEnabled', 'whatsappBuyNowNumber'));
    }

    public function save(Request $request)
    {
        $this->guard();

        $data = $request->validate([
            'enabled' => 'required|boolean',
            'number'  => 'nullable|string|max:20',
        ]);

        SiteSettings::set('whatsapp_buy_now_enabled', $data['enabled'] ? '1' : '0');
        SiteSettings::set('whatsapp_buy_now_number', trim((string) ($data['number'] ?? '')));

        return response()->json(['success' => true, 'message' => 'Controls saved.']);
    }
}
