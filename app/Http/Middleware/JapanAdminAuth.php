<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

/** Protects /japanadmin routes: session must carry japanadmin_logged_in. Isolated from the main /admin session. */
class JapanAdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::get('japanadmin_logged_in')) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Not authorized.'], 401);
            }
            return redirect('/japanadmin/login');
        }

        return $next($request);
    }
}
