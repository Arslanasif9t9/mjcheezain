<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Support\CategoryCatalog;
use Illuminate\Support\Facades\Cache;

/**
 * Public XML sitemap at /sitemap.xml — no auth, safe for Googlebot.
 *
 * Includes: the live homepage ("/" — currently the coming-soon page is what's
 * actually live at the root domain, see the note below), every APPROVED
 * vendor product, every active site category (linking to the filtered
 * listing page), and the static footer pages.
 */
class SitemapController extends Controller
{
    const CACHE_KEY = 'sitemap_xml_v1';

    public function index()
    {
        $xml = Cache::remember(self::CACHE_KEY, 3600, function () {
            return $this->build();
        });

        return response($xml, 200)->header('Content-Type', 'text/xml; charset=UTF-8');
    }

    private function build(): string
    {
        $urls = [];

        // Homepage. NOTE: "/" currently renders the coming-soon placeholder
        // (brands/japan-coming-soon) — that is what's genuinely live at the
        // root domain today, so it is what belongs in the sitemap. The real
        // storefront lives at /a456 but is not what Google should be sending
        // traffic to until it becomes the actual root route. Flagged for the
        // human to confirm/change once that switch happens.
        $urls[] = [
            'loc' => url('/'),
            'lastmod' => now()->toAtomString(),
            'changefreq' => 'daily',
            'priority' => '1.0',
        ];

        // Static footer pages.
        $footerPages = [
            'about', 'future-vision', 'contact-us', 'FAQs',
            'vendor-zone', 'legal-policies', 'privacy-policy', 'return-replacement',
        ];
        foreach ($footerPages as $page) {
            $urls[] = [
                'loc' => url('/' . $page),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.5',
            ];
        }

        // Categories — link to the filtered product-listing page.
        try {
            foreach (CategoryCatalog::active() as $cat) {
                $urls[] = [
                    'loc' => url('/products/all-page?category=' . urlencode($cat->name)),
                    'lastmod' => now()->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.6',
                ];
            }
        } catch (\Throwable $e) {
            // Category catalog unavailable — skip category URLs rather than fail the whole sitemap.
        }

        // Live products — approved only, capped to keep the sitemap light.
        Product::where('position', 'approved')
            ->select(['id', 'updated_at'])
            ->orderByDesc('updated_at')
            ->limit(5000)
            ->chunk(500, function ($products) use (&$urls) {
                foreach ($products as $product) {
                    $urls[] = [
                        'loc' => url('/product/' . $product->id),
                        'lastmod' => optional($product->updated_at)->toAtomString() ?? now()->toAtomString(),
                        'changefreq' => 'weekly',
                        'priority' => '0.8',
                    ];
                }
            });

        return $this->toXml($urls);
    }

    private function toXml(array $urls): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as $u) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>' . htmlspecialchars($u['loc'], ENT_XML1 | ENT_QUOTES, 'UTF-8') . "</loc>\n";
            $xml .= '    <lastmod>' . $u['lastmod'] . "</lastmod>\n";
            $xml .= '    <changefreq>' . $u['changefreq'] . "</changefreq>\n";
            $xml .= '    <priority>' . $u['priority'] . "</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return $xml;
    }
}
