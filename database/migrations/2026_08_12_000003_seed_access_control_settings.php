<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Seeds the customer/vendor access switches for the admin Controls page.
 *
 * Defaults deliberately FAIL-OPEN (login/register = '1', force logout = '0'):
 * if these rows are ever missing the site keeps behaving exactly as before,
 * rather than locking every user out.
 */
return new class extends Migration
{
    private array $defaults = [
        'customer_login_enabled'    => '1',
        'customer_register_enabled' => '1',
        'customer_force_logout'     => '0',
        'vendor_login_enabled'      => '1',
        'vendor_register_enabled'   => '1',
        'vendor_force_logout'       => '0',
    ];

    public function up(): void
    {
        if (!Schema::hasTable('site_settings')) {
            return; // site_settings migration hasn't run yet — nothing to seed
        }

        foreach ($this->defaults as $key => $value) {
            $exists = DB::table('site_settings')->where('key', $key)->exists();
            if (!$exists) {
                DB::table('site_settings')->insert([
                    'key'        => $key,
                    'value'      => $value,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('site_settings')) {
            return;
        }

        DB::table('site_settings')->whereIn('key', array_keys($this->defaults))->delete();
    }
};
