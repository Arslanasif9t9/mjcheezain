<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Turns replacement_requests into a genuine "Request Replacement" flow, matching
     * the owner's policy:
     * - Replacement is NOT a refund mechanism. The replacement product must come from
     *   the SAME vendor/store and cost the same or more than the original product.
     * - cart_id / replacement_product_id / original_price / replacement_price let us
     *   recompute (server-side, never trust the client) additional_amount_payable
     *   and total_amount_payable.
     * - courier_paid_by mirrors the return flow: derived from the replacement reason
     *   (vendor's fault -> vendor pays courier; customer's own choice -> customer pays).
     *   When the vendor pays, delivery_charge is excluded from total_amount_payable.
     * - vendor_comment / vendor_commented_at: the vendor can only comment — only Admin
     *   (AdminOpsController::updateReplacementStatus) sets the final approved/rejected
     *   decision, same lockdown as the return flow.
     * - customer_confirmed: the required "I agree to the Replacement Policy" checkbox.
     * - reference_number: human-readable REP-{YYYYMMDD}-#### reference.
     */
    public function up(): void
    {
        Schema::table('replacement_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('replacement_requests', 'cart_id')) {
                $table->unsignedBigInteger('cart_id')->nullable()->after('order_id');
            }
            if (!Schema::hasColumn('replacement_requests', 'video_path')) {
                $table->string('video_path')->nullable()->after('details');
            }
            if (!Schema::hasColumn('replacement_requests', 'replacement_product_id')) {
                $table->unsignedBigInteger('replacement_product_id')->nullable()->after('video_path');
            }
            if (!Schema::hasColumn('replacement_requests', 'original_price')) {
                $table->decimal('original_price', 10, 2)->nullable()->after('replacement_product_id');
            }
            if (!Schema::hasColumn('replacement_requests', 'replacement_price')) {
                $table->decimal('replacement_price', 10, 2)->nullable()->after('original_price');
            }
            if (!Schema::hasColumn('replacement_requests', 'additional_amount_payable')) {
                $table->decimal('additional_amount_payable', 10, 2)->default(0)->after('replacement_price');
            }
            if (!Schema::hasColumn('replacement_requests', 'delivery_charge')) {
                $table->decimal('delivery_charge', 10, 2)->default(0)->after('additional_amount_payable');
            }
            if (!Schema::hasColumn('replacement_requests', 'total_amount_payable')) {
                $table->decimal('total_amount_payable', 10, 2)->default(0)->after('delivery_charge');
            }
            if (!Schema::hasColumn('replacement_requests', 'courier_paid_by')) {
                $table->enum('courier_paid_by', ['vendor', 'customer'])->nullable()->after('total_amount_payable');
            }
            if (!Schema::hasColumn('replacement_requests', 'customer_confirmed')) {
                $table->boolean('customer_confirmed')->default(false)->after('courier_paid_by');
            }
            if (!Schema::hasColumn('replacement_requests', 'vendor_comment')) {
                $table->text('vendor_comment')->nullable()->after('customer_confirmed');
            }
            if (!Schema::hasColumn('replacement_requests', 'vendor_commented_at')) {
                $table->timestamp('vendor_commented_at')->nullable()->after('vendor_comment');
            }
            if (!Schema::hasColumn('replacement_requests', 'reference_number')) {
                $table->string('reference_number', 40)->nullable()->unique()->after('vendor_commented_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('replacement_requests', function (Blueprint $table) {
            foreach ([
                'reference_number', 'vendor_commented_at', 'vendor_comment', 'customer_confirmed',
                'courier_paid_by', 'total_amount_payable', 'delivery_charge', 'additional_amount_payable',
                'replacement_price', 'original_price', 'replacement_product_id', 'video_path', 'cart_id',
            ] as $column) {
                if (Schema::hasColumn('replacement_requests', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
