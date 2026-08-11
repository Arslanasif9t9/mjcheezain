<?php

namespace App\Providers;

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
        // Makes $whatsappBuyNow available on every single-product page without
        // touching their controllers. When enabled (admin Controls page),
        // Buy Now opens WhatsApp instead of going through checkout.
        View::composer(['product', 'product-auto', 'product-auto-japan'], function ($view) {
            $view->with('whatsappBuyNow', [
                'enabled' => SiteSettings::get('whatsapp_buy_now_enabled', '0') === '1',
                'number'  => SiteSettings::get('whatsapp_buy_now_number', ''),
            ]);
        });
    }
}
