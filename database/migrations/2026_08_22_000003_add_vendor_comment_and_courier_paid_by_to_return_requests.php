<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds the columns needed to enforce the return-request policy:
     * - vendor_comment / vendor_commented_at: the vendor's opinion on the request.
     *   The vendor can only comment; only Admin can set a final approved/rejected status.
     * - courier_paid_by: who pays the return courier cost, derived from the return reason
     *   (vendor's fault -> vendor pays, customer's own choice -> customer pays).
     */
    public function up(): void
    {
        Schema::table('return_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('return_requests', 'vendor_comment')) {
                $table->text('vendor_comment')->nullable()->after('status');
            }
            if (!Schema::hasColumn('return_requests', 'vendor_commented_at')) {
                $table->timestamp('vendor_commented_at')->nullable()->after('vendor_comment');
            }
            if (!Schema::hasColumn('return_requests', 'courier_paid_by')) {
                $table->enum('courier_paid_by', ['vendor', 'customer'])->nullable()->after('vendor_commented_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('return_requests', function (Blueprint $table) {
            if (Schema::hasColumn('return_requests', 'courier_paid_by')) {
                $table->dropColumn('courier_paid_by');
            }
            if (Schema::hasColumn('return_requests', 'vendor_commented_at')) {
                $table->dropColumn('vendor_commented_at');
            }
            if (Schema::hasColumn('return_requests', 'vendor_comment')) {
                $table->dropColumn('vendor_comment');
            }
        });
    }
};
