<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ticket_types', function (Blueprint $table) {
            $table->unsignedInteger('capacity')->nullable()->after('kuota');
            $table->softDeletes();
        });

        DB::table('ticket_types')->whereNull('capacity')->update([
            'capacity' => DB::raw('kuota'),
        ]);

        Schema::table('booths', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('booth_bookings', function (Blueprint $table) {
            $table->timestamp('approved_at')->nullable()->after('status');
            $table->timestamp('cancelled_at')->nullable()->after('approved_at');
            $table->index(['booth_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booth_bookings', function (Blueprint $table) {
            $table->dropIndex(['booth_id', 'status']);
            $table->dropColumn(['approved_at', 'cancelled_at']);
        });

        Schema::table('booths', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('ticket_types', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn('capacity');
        });
    }
};
