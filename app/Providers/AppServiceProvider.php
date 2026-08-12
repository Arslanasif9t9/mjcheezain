<?php

namespace App\Providers;

use App\Support\AccessControl;
use App\Support\SiteSettings;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Shared with EVERY view (header, footer, cart, product pages) so the
        // admin Controls switches work site-wide without touching controllers.
        //  - $whatsappBuyNow : Buy Now / cart checkout divert to WhatsApp
        //  - $siteAccess     : which customer/vendor auth UI may be shown
        // Both read the cached SiteSettings store, so this stays cheap.
        View::composer('*', function ($view) {
            $view->with('whatsappBuyNow', [
                'enabled' => SiteSettings::get('whatsapp_buy_now_enabled', '0') === '1',
                'number'  => SiteSettings::get('whatsapp_buy_now_number', ''),
            ])->with('siteAccess', AccessControl::flags());
        });
    }
}
