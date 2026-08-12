<?php

namespace App\Http\Controllers;

use App\Support\AccessControl;

/**
 * Gatekeeper for the public auth PAGES. When the admin has switched a flow off
 * (Controls page) these pages quietly redirect home, so a hidden button can't
 * be worked around by typing the URL.
 *
 * Previously these were plain `Route::view(...)` entries in routes/web.php.
 */
class AuthPageController extends Controller
{
    /** /login-user — the combined customer/vendor login + signup page. */
    public function loginUser()
    {
        if (AccessControl::allBlocked()) {
            return redirect('/');
        }

        return view('home/login');
    }

    /** /customer-forgot-password */
    public function customerForgot()
    {
        if (!AccessControl::loginAllowed('customer')) {
            return redirect('/');
        }

        return view('home/forgot');
    }

    /** /vendor-forgot-password */
    public function vendorForgot()
    {
        if (!AccessControl::loginAllowed('vendor')) {
            return redirect('/');
        }

        return view('home/forgot');
    }
}
