<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Generic key/value store backing the admin "Controls" page.
 * Cached (10 min) with a safe fallback so a missing/unmigrated
 * site_settings table never breaks a product/checkout page.
 */
class SiteSettings
{
    const CACHE_KEY = 'site_settings_v1';

    /** All settings as a key => value map. */
    public static function all()
    {
        return Cache::remember(self::CACHE_KEY, 600, function () {
            try {
                return DB::table('site_settings')->pluck('value', 'key');
            } catch (\Throwable $e) {
                Log::warning('SiteSettings fallback used: ' . $e->getMessage());
                return collect();
            }
        });
    }

    public static function get(string $key, $default = null)
    {
        $value = self::all()->get($key);
        return $value === null ? $default : $value;
    }

    public static function set(string $key, $value): void
    {
        $exists = DB::table('site_settings')->where('key', $key)->exists();
        if ($exists) {
            DB::table('site_settings')->where('key', $key)->update(['value' => $value, 'updated_at' => now()]);
        } else {
            DB::table('site_settings')->insert(['key' => $key, 'value' => $value, 'created_at' => now(), 'updated_at' => now()]);
        }
        Cache::forget(self::CACHE_KEY);
    }
}
