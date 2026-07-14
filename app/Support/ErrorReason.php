<?php

namespace App\Support;

use Illuminate\Support\Facades\Log;
use Throwable;

class ErrorReason
{
    /**
     * A safe, human-readable reason for a database failure.
     * Never exposes SQL, credentials, or stack traces.
     */
    public static function database(Throwable $e): string
    {
        $msg = $e->getMessage();

        return match (true) {
            str_contains($msg, 'Access denied for user')
                => 'Database connection failed: the database username or password configured on the server is incorrect. Please update DB_USERNAME and DB_PASSWORD in the server\'s .env file.',

            str_contains($msg, 'Unknown database')
                => 'Database connection failed: the database name configured on the server does not exist. Please check DB_DATABASE in the server\'s .env file.',

            str_contains($msg, 'Connection refused'),
            str_contains($msg, 'No connection could be made'),
            str_contains($msg, 'getaddrinfo'),
            str_contains($msg, 'Connection timed out'),
            str_contains($msg, 'No such file or directory')
                => 'Database server is not reachable right now. Please check DB_HOST in the server\'s .env file or try again in a few minutes.',

            str_contains($msg, 'Too many connections')
                => 'The database is receiving too many connections right now. Please try again in a few minutes.',

            str_contains($msg, 'Base table or view not found')
                => 'A required database table is missing. The database migrations may not have been run on this server.',

            str_contains($msg, 'server has gone away')
                => 'The database connection was lost. Please refresh the page and try again.',

            default
                => 'A database error occurred while loading this page. Please try again later.',
        };
    }

    /**
     * User-facing message for any exception. Full details are logged,
     * but the user only sees a safe reason.
     *
     * In debug mode (local development) the raw message is returned
     * so developers still see the real error.
     */
    public static function friendly(Throwable $e, string $context = 'Something went wrong'): string
    {
        Log::error($context.': '.$e->getMessage(), [
            'exception' => get_class($e),
            'file' => $e->getFile().':'.$e->getLine(),
        ]);

        if (config('app.debug')) {
            return $context.': '.$e->getMessage();
        }

        if ($e instanceof \PDOException) {
            return self::database($e);
        }

        return $context.'. Please try again later.';
    }
}
