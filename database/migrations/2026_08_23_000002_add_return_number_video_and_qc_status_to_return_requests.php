<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * - return_number: human-readable reference (RET-YYYYMMDD-0000) shown to the customer,
     *   generated in ReturnController::store() right when the row is created.
     * - return_video: path to the optional video the customer records explaining the return.
     * - status enum: widened to add the post-approval quality-check checkpoint
     *   (Approve -> processing -> received -> Quality Check Pass/Fail):
     *     - 'received'   : product physically arrived back with the vendor/admin.
     *     - 'qc_failed'  : quality check failed after inspection -> return rejected,
     *                      product goes back to the customer, no refund (terminal, like 'rejected').
     *   Also restores two values that application code already writes but which were missing
     *   from the live enum (found while widening this column) and would otherwise fail with a
     *   truncation/data error the moment they're hit:
     *     - 'vendor_reviewed' : set by VendorReturnController::updateStatus() when the vendor
     *                           leaves their first comment on a still-pending request.
     *     - 'cancelled'       : set by ReturnController::cancel() when the customer cancels
     *                           their own still-pending request.
     */
    public function up(): void
    {
        Schema::table('return_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('return_requests', 'return_number')) {
                $table->string('return_number', 30)->nullable()->unique()->after('id');
            }
            if (!Schema::hasColumn('return_requests', 'return_video')) {
                $table->string('return_video', 255)->nullable()->after('courier_paid_by');
            }
        });

        DB::statement("ALTER TABLE return_requests MODIFY status ENUM(" .
            "'pending','vendor_reviewed','approved','rejected','processing','received','qc_failed','refunded','completed','cancelled'" .
            ") NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Move any rows using the new-only enum values back to a safe value before narrowing
        // the column, so the down() migration itself doesn't fail on truncation.
        DB::table('return_requests')->whereIn('status', ['received', 'qc_failed'])->update(['status' => 'processing']);
        DB::table('return_requests')->where('status', 'cancelled')->update(['status' => 'rejected']);
        DB::table('return_requests')->where('status', 'vendor_reviewed')->update(['status' => 'pending']);

        DB::statement("ALTER TABLE return_requests MODIFY status ENUM(" .
            "'pending','approved','rejected','processing','refunded','completed'" .
            ") NULL DEFAULT 'pending'");

        Schema::table('return_requests', function (Blueprint $table) {
            if (Schema::hasColumn('return_requests', 'return_video')) {
                $table->dropColumn('return_video');
            }
            if (Schema::hasColumn('return_requests', 'return_number')) {
                $table->dropColumn('return_number');
            }
        });
    }
};
