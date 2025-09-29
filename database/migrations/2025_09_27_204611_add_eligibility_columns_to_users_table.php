<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::table('users', function (Blueprint $table) {
        if (!Schema::hasColumn('users', 'is_eligible')) {
            $table->boolean('is_eligible')->default(true);
        }
        if (!Schema::hasColumn('users', 'manual_eligibility')) {
            $table->boolean('manual_eligibility')->nullable();
        }
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        if (Schema::hasColumn('users', 'is_eligible')) {
            $table->dropColumn('is_eligible');
        }
        if (Schema::hasColumn('users', 'manual_eligibility')) {
            $table->dropColumn('manual_eligibility');
        }
    });
}

};
