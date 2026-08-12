<?php

namespace App\Support;

/**
 * Admin-controlled customer/vendor account access (Controls page).
 *
 * Single source of truth for "can this role sign in / sign up right now".
 * Every default is FAIL-OPEN so a missing site_settings table or row leaves
 * the site working exactly as it did before this feature existed.
 *
 * Note: this covers storefront customer/vendor accounts only — the /admin
 * and /japanadmin panels have their own session and are never affected.
 */
class AccessControl
{
    public const ROLES = ['customer', 'vendor'];

    public static function loginAllowed(string $role): bool
    {
        return SiteSettings::get("{$role}_login_enabled", '1') === '1';
    }

    public static function registerAllowed(string $role): bool
    {
        return SiteSettings::get("{$role}_register_enabled", '1') === '1';
    }

    /** Should ANY auth UI be shown for this role? */
    public static function anyAllowed(string $role): bool
    {
        return self::loginAllowed($role) || self::registerAllowed($role);
    }

    /** Admin also wants existing sessions of this role terminated. */
    public static function forceLogout(string $role): bool
    {
        return SiteSettings::get("{$role}_force_logout", '0') === '1';
    }

    /** Nothing available to anyone — the login page has no reason to exist. */
    public static function allBlocked(): bool
    {
        foreach (self::ROLES as $role) {
            if (self::anyAllowed($role)) {
                return false;
            }
        }
        return true;
    }

    /** Compact flags for views (shared as $siteAccess by AppServiceProvider). */
    public static function flags(): array
    {
        return [
            'customer_login'    => self::loginAllowed('customer'),
            'customer_register' => self::registerAllowed('customer'),
            'customer_any'      => self::anyAllowed('customer'),
            'vendor_login'      => self::loginAllowed('vendor'),
            'vendor_register'   => self::registerAllowed('vendor'),
            'vendor_any'        => self::anyAllowed('vendor'),
            'any'               => !self::allBlocked(),
        ];
    }
}
