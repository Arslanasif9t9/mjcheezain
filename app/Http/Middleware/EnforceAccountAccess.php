<?php

namespace App\Http\Middleware;

use App\Support\AccessControl;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Ends the session of any storefront user whose role the admin has switched
 * off AND chosen to force out (Controls page).
 *
 * Only the SESSION is terminated — no account row is ever modified or deleted.
 * The /admin and /japanadmin panels use their own session keys, so they are
 * untouched by this.
 */
class EnforceAccountAccess
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $role = (string) (Auth::user()->type ?? '');

            if ($role !== '' && !AccessControl::loginAllowed($role) && AccessControl::forceLogout($role)) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Your session has ended.',
                    ], 403);
                }

                return redirect('/');
            }
        }

        return $next($request);
    }
}
