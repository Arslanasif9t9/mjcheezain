<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

/** Protects admin routes: session must carry admin_logged_in. */
class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::get('admin_logged_in')) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Not authorized.'], 401);
            }
            return redirect('/admin/login');
        }

        return $next($request);
    }
}
