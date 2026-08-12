<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Indexes for the hot storefront queries.
 *
 * The live tables were created out-of-band and had drifted from the migrations:
 * verified with SHOW INDEX, `vendor_products`, `carts` and `japan_products`
 * carried nothing but their PRIMARY key. Every product listing filters
 * `position = 'approved'` and joins a `carts.status = 'delivered'` GROUP BY
 * subquery, so those were full scans + filesorts on every page view — and
 * `carts` grows a row on every guest add-to-cart.
 *
 * Each index is added only if the table exists and the index doesn't, so this
 * is safe to re-run and safe on a DB where some tables are missing.
 */
return new class extends Migration
{
    /** table => [indexName => [columns]] */
    private array $indexes = [
        'vendor_products' => [
            'vp_position_category_idx'  => ['position', 'category'],
            'vp_position_rating_idx'    => ['position', 'rating', 'updated_at'],
            'vp_user_position_idx'      => ['user_id', 'position'],
        ],
        'vendor_product_images' => [
            'vpi_product_primary_idx'   => ['product_id', 'is_primary'],
        ],
        'carts' => [
            'carts_status_product_idx'  => ['status', 'product_id'],
            'carts_session_order_idx'   => ['session_id', 'order_id'],
            'carts_user_order_idx'      => ['user_id', 'order_id'],
        ],
        'japan_products' => [
            'jp_status_id_idx'          => ['status', 'id'],
            'jp_status_brand_idx'       => ['status', 'brand'],
        ],
    ];

    public function up(): void
    {
        foreach ($this->indexes as $table => $defs) {
            if (!Schema::hasTable($table)) {
                continue;
            }

            foreach ($defs as $name => $columns) {
                if ($this->indexExists($table, $name)) {
                    continue;
                }
                // Skip if the live table is missing one of the columns.
                foreach ($columns as $col) {
                    if (!Schema::hasColumn($table, $col)) {
                        continue 2;
                    }
                }

                Schema::table($table, function ($t) use ($columns, $name) {
                    $t->index($columns, $name);
                });
            }
        }
    }

    public function down(): void
    {
        foreach ($this->indexes as $table => $defs) {
            if (!Schema::hasTable($table)) {
                continue;
            }
            foreach ($defs as $name => $columns) {
                if ($this->indexExists($table, $name)) {
                    Schema::table($table, function ($t) use ($name) {
                        $t->dropIndex($name);
                    });
                }
            }
        }
    }

    private function indexExists(string $table, string $index): bool
    {
        return count(DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$index])) > 0;
    }
};
