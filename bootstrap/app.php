<?php

use App\Support\ErrorReason;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin.auth' => \App\Http\Middleware\AdminAuth::class,
            'japanadmin.auth' => \App\Http\Middleware\JapanAdminAuth::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Database errors (wrong credentials, missing DB, lost connection, ...)
        // In production these render a friendly page instead of the raw SQL error.
        $exceptions->render(function (\PDOException $e, Request $request) {
            if (config('app.debug')) {
                return null; // local development: show the full error page
            }

            $reference = strtoupper(Str::random(8));

            Log::error("[{$reference}] Database error: {$e->getMessage()}", [
                'url' => $request->fullUrl(),
                'exception' => get_class($e),
            ]);

            $reason = ErrorReason::database($e);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $reason,
                    'reference' => $reference,
                ], 500);
            }

            return response()->view('errors.500', [
                'reason' => $reason,
                'reference' => $reference,
            ], 500);
        });
    })->create();
