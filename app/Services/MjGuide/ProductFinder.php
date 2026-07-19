<?php

namespace App\Services\MjGuide;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * DB product search for MJGuider recommendations.
 * Only ever exposes public storefront fields (approved + in-stock products) —
 * never vendor ids, contact info, moderation fields or timestamps.
 */
class ProductFinder
{
    /** Same customer-price maths as product-card.js / product.blade.php (selling price + 17% GST). */
    private const GST = 1.17;

    private const LIMIT = 12;
    private const SNIPPET_LEN = 140;

    /** Words that carry no search signal (English + Roman Urdu fillers). */
    private const STOPWORDS = [
        'the', 'a', 'an', 'for', 'i', 'me', 'my', 'need', 'want', 'best', 'good', 'some', 'any',
        'and', 'or', 'of', 'to', 'in', 'with', 'is', 'are',
        'chahiye', 'chaiye', 'chahye', 'wala', 'wali', 'walay', 'wale', 'koi', 'mujhe', 'mujhy',
        'ka', 'ke', 'ki', 'ko', 'hai', 'hain', 'ho', 'se', 'sab', 'zyada', 'acha', 'achi',
        'aur', 'bhi', 'ek', 'liye', 'lie', 'kya', 'kar', 'do', 'dikhao', 'batao', 'karo',
    ];

    /**
     * @return array{rows: Collection, salesReliable: bool}
     */
    public function search(string $q, string $sort): array
    {
        $tokens = $this->tokenize($q);
        $rows = $tokens === [] ? collect() : $this->runQuery($tokens, $sort);

        // "Highest rated" / "best selling" asks often carry no product keyword
        // ("sab se zyada bikne wala kya hai?") — those must still return the ranking.
        if ($rows->isEmpty() && in_array($sort, ['rating', 'sales'], true)) {
            $rows = $this->runQuery([], $sort);
        }

        $this->attachImages($rows);

        return [
            'rows' => $rows,
            'salesReliable' => $rows->contains(fn ($r) => (int) $r->sold_count > 0),
        ];
    }

    /**
     * Fallback line-up when the user's exact ask has no match: best-rated
     * in-stock products so MJGuider can always offer something instead of a dead end.
     *
     * @return array{rows: Collection, salesReliable: bool}
     */
    public function topPicks(): array
    {
        $rows = $this->runQuery([], 'rating');
        $this->attachImages($rows);

        return [
            'rows' => $rows,
            'salesReliable' => $rows->contains(fn ($r) => (int) $r->sold_count > 0),
        ];
    }

    /** @param array<int,string> $tokens empty = browse mode (no keyword filter, sort only) */
    private function runQuery(array $tokens, string $sort): Collection
    {
        $soldSub = DB::table('carts')
            ->select('product_id', DB::raw('COUNT(*) as sold_count'))
            ->where('status', 'delivered')
            ->groupBy('product_id');

        // Per-token relevance score (name prefix > name > brand > category > model > description)
        $scorePieces = [];
        $scoreBindings = [];
        foreach ($tokens as $t) {
            $like = '%'.$this->escapeLike($t).'%';
            $prefix = $this->escapeLike($t).'%';
            $scorePieces[] = '(CASE WHEN vp.name LIKE ? THEN 6 WHEN vp.name LIKE ? THEN 4 ELSE 0 END'
                .' + CASE WHEN vp.brand LIKE ? THEN 3 ELSE 0 END'
                .' + CASE WHEN vp.category LIKE ? OR vp.subcategory LIKE ? THEN 3 ELSE 0 END'
                .' + CASE WHEN vp.model LIKE ? THEN 2 ELSE 0 END'
                .' + CASE WHEN vp.description LIKE ? THEN 1 ELSE 0 END)';
            array_push($scoreBindings, $prefix, $like, $like, $like, $like, $like, $like);
        }
        $scoreExpr = $scorePieces === [] ? '0' : implode(' + ', $scorePieces);

        $query = DB::table('vendor_products as vp')
            ->leftJoinSub($soldSub, 'sold', fn ($j) => $j->on('sold.product_id', '=', 'vp.id'))
            ->where('vp.position', 'approved')
            ->where('vp.quantity', '>', 0)
            ->when($tokens !== [], fn ($q) => $q->where(function ($w) use ($tokens) {
                foreach ($tokens as $t) {
                    $like = '%'.$this->escapeLike($t).'%';
                    $w->orWhere('vp.name', 'like', $like)
                        ->orWhere('vp.category', 'like', $like)
                        ->orWhere('vp.subcategory', 'like', $like)
                        ->orWhere('vp.brand', 'like', $like)
                        ->orWhere('vp.model', 'like', $like)
                        ->orWhere('vp.description', 'like', $like);
                }
            }))
            ->select([
                'vp.id', 'vp.name', 'vp.category', 'vp.subcategory', 'vp.brand', 'vp.model',
                'vp.selling_price', 'vp.mrp', 'vp.original_price', 'vp.rating', 'vp.quantity',
                'vp.description',
                DB::raw('COALESCE(sold.sold_count, 0) as sold_count'),
            ])
            ->selectRaw("({$scoreExpr}) as score", $scoreBindings);

        match ($sort) {
            'rating' => $query->orderByDesc('vp.rating')->orderByDesc('score')->orderByDesc('sold_count'),
            'sales' => $query->orderByDesc('sold_count')->orderByDesc('score')->orderByDesc('vp.rating'),
            default => $query->orderByDesc('score')->orderByDesc('vp.rating')->orderByDesc('sold_count')->orderByDesc('vp.created_at'),
        };

        return $query->limit(self::LIMIT)->get();
    }

    /** Compact JSON-lines block injected into the model's second call. */
    public function candidatesForLlm(Collection $rows): string
    {
        return $rows->map(function ($r) {
            $price = $this->customerPrice($r);
            $mrp = $this->displayMrp($r, $price);

            return json_encode([
                'id' => (int) $r->id,
                'name' => $this->cleanText($r->name, 80),
                'cat' => $this->cleanText($r->category, 40),
                'sub' => $this->cleanText($r->subcategory, 40),
                'brand' => $this->cleanText($r->brand, 40),
                'price' => $price,
                'mrp' => $mrp,
                'rating' => (float) $r->rating > 0 ? round((float) $r->rating, 1) : null,
                'sold' => (int) $r->sold_count,
                'desc' => $this->cleanText($r->description, self::SNIPPET_LEN),
            ], JSON_UNESCAPED_UNICODE);
        })->implode("\n");
    }

    /**
     * Card payloads for the frontend, built ONLY from already-fetched rows
     * (the model contributes ids alone — it can never fabricate a product).
     * selling_price/mrp go RAW so buildProductCard applies its own ×1.17,
     * identical to every other card on the site.
     */
    public function cards(array $pickedIds, Collection $rows): array
    {
        $byId = $rows->keyBy('id');
        $cards = [];

        foreach ($pickedIds as $id) {
            $r = $byId->get($id);
            if (! $r) {
                continue;
            }
            $price = $this->customerPrice($r);
            $mrp = $this->displayMrp($r, $price);

            $cards[] = [
                'id' => (int) $r->id,
                'name' => (string) $r->name,
                'image' => $r->image_path
                    ? asset('storage/vendor/products/images/'.$r->image_path)
                    : asset('img/default_img.png'),
                'selling_price' => (float) $r->selling_price,
                'mrp' => $mrp,
                'rating' => (float) $r->rating > 0 ? round((float) $r->rating, 1) : null,
                'category' => (string) $r->category,
            ];
        }

        return $cards;
    }

    /** Final customer price (Rs.), matching what every product card displays. */
    private function customerPrice(object $r): int
    {
        return (int) round((float) $r->selling_price * self::GST);
    }

    /** Struck-through MRP — only when the discount rounds to at least 1% (never a "-0%" ribbon). */
    private function displayMrp(object $r, int $customerPrice): ?int
    {
        $mrp = (float) ($r->mrp ?: $r->original_price ?: 0);
        $price = (float) $r->selling_price * self::GST;
        if ($mrp <= $price) {
            return null;
        }
        $pct = (int) round(($mrp - $price) / $mrp * 100); // same maths as product-card.js

        return $pct >= 1 ? (int) round($mrp) : null;
    }

    private function tokenize(string $q): array
    {
        $words = preg_split('/[^\p{L}\p{N}]+/u', mb_strtolower(trim($q)), -1, PREG_SPLIT_NO_EMPTY) ?: [];

        $tokens = [];
        foreach ($words as $w) {
            if (mb_strlen($w) < 2 || in_array($w, self::STOPWORDS, true) || in_array($w, $tokens, true)) {
                continue;
            }
            $tokens[] = $w;
            if (count($tokens) >= 6) {
                break;
            }
        }

        return $tokens;
    }

    private function escapeLike(string $value): string
    {
        return addcslashes($value, '\\%_');
    }

    /** One image per product (primary first), attached as ->image_path. */
    private function attachImages(Collection $rows): void
    {
        if ($rows->isEmpty()) {
            return;
        }

        $images = DB::table('vendor_product_images')
            ->whereIn('product_id', $rows->pluck('id'))
            ->orderByDesc('is_primary')
            ->orderBy('id')
            ->get(['product_id', 'image_path'])
            ->groupBy('product_id');

        foreach ($rows as $r) {
            $r->image_path = $images->get($r->id)?->first()?->image_path;
        }
    }

    /** Vendor-written text sanitized before it reaches the model or reply text: no tags, no < > (directive forging), capped. */
    public function cleanText(?string $text, int $max): string
    {
        $text = strip_tags((string) $text);
        $text = str_replace(['<', '>'], '', $text);
        $text = preg_replace('/\s+/u', ' ', $text);

        return mb_substr(trim($text), 0, $max);
    }
}
