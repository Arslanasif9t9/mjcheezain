<?php

namespace App\Http\Controllers;

use App\Support\AccessControl;
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
        $access = AccessControl::flags();
        $forceLogout = [
            'customer' => AccessControl::forceLogout('customer'),
            'vendor'   => AccessControl::forceLogout('vendor'),
        ];

        return view('Admin.controls', compact(
            'whatsappBuyNowEnabled', 'whatsappBuyNowNumber', 'access', 'forceLogout'
        ));
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

    /**
     * Customer/vendor account access switches. Saved as one block so the admin
     * never lands in a half-applied state (e.g. login off but force-logout
     * still pending).
     */
    public function saveAccess(Request $request)
    {
        $this->guard();

        $data = $request->validate([
            'role'         => 'required|in:customer,vendor',
            'login'        => 'required|boolean',
            'register'     => 'required|boolean',
            'force_logout' => 'required|boolean',
        ]);

        $role = $data['role'];
        SiteSettings::set("{$role}_login_enabled", $data['login'] ? '1' : '0');
        SiteSettings::set("{$role}_register_enabled", $data['register'] ? '1' : '0');
        SiteSettings::set("{$role}_force_logout", $data['force_logout'] ? '1' : '0');

        return response()->json([
            'success' => true,
            'message' => ucfirst($role) . ' access saved.',
        ]);
    }
}
