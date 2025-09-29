<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Make column nullable (if not already)
        Schema::table('users', function (Blueprint $table) {
            $table->integer('skipped_elections')->nullable()->change();
        });

        // 2. Set skipped_elections = NULL for all existing users
        DB::table('users')
            ->whereNotNull('skipped_elections')
            ->update(['skipped_elections' => null]);
    }

    public function down(): void
    {
        // Optional: revert column to default 0
        Schema::table('users', function (Blueprint $table) {
            $table->integer('skipped_elections')->default(0)->change();
        });

        // Optional: set NULLs back to 0
        DB::table('users')
            ->whereNull('skipped_elections')
            ->update(['skipped_elections' => 0]);
    }
};
