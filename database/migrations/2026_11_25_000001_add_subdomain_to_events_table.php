<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('subdomain')->nullable()->unique()->after('nama_event');
            $table->string('timezone')->default('UTC')->after('tanggal_selesai');
            $table->softDeletes();
        });

        DB::table('events')
            ->whereNull('subdomain')
            ->orderBy('id')
            ->chunkById(100, function ($events): void {
                foreach ($events as $event) {
                    $base = Str::limit(Str::slug((string) $event->nama_event), 40, '') ?: 'event';
                    $suffix = substr(str_replace('-', '', (string) $event->id), 0, 8);
                    $candidate = $base.'-'.$suffix;
                    $attempt = 1;

                    while (DB::table('events')->where('subdomain', $candidate)->exists()) {
                        $candidate = $base.'-'.$suffix.'-'.$attempt;
                        $attempt++;
                    }

                    DB::table('events')->where('id', $event->id)->update([
                        'subdomain' => $candidate,
                    ]);
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropUnique(['subdomain']);
            $table->dropSoftDeletes();
            $table->dropColumn(['subdomain', 'timezone']);
        });
    }
};
