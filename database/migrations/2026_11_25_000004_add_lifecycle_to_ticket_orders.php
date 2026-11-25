<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ticket_orders', function (Blueprint $table) {
            $table->timestamp('expires_at')->nullable()->after('status_pembayaran');
            $table->timestamp('paid_at')->nullable()->after('expires_at');
            $table->timestamp('cancelled_at')->nullable()->after('paid_at');
            $table->timestamp('inventory_released_at')->nullable()->after('cancelled_at');
            $table->index(['status_pembayaran', 'expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_orders', function (Blueprint $table) {
            $table->dropIndex(['status_pembayaran', 'expires_at']);
            $table->dropColumn([
                'expires_at',
                'paid_at',
                'cancelled_at',
                'inventory_released_at',
            ]);
        });
    }
};
