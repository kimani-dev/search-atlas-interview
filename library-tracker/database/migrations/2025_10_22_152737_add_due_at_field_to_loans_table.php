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
        Schema::table('loans', function (Blueprint $table) {
            $table->timestamp('due_at')->nullable();
        });

        DB::table('loans')->whereNull('due_at')->update([
            'due_at' => DB::raw("DATE_ADD(loaned_at, INTERVAL 14 DAY)")
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn('due_at');
        });
    }
};
