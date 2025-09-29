<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Ensure manual_eligibility allows NULL
            $table->boolean('manual_eligibility')->nullable()->default(null)->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Rollback: set back to NOT NULL with default 0
            $table->boolean('manual_eligibility')->default(0)->change();
        });
    }
};
