<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('institution_settings', function (Blueprint $table) {
            $table->integer('saturday_periods')->default(6)->after('friday_periods');
        });
    }

    public function down(): void
    {
        Schema::table('institution_settings', function (Blueprint $table) {
            $table->dropColumn('saturday_periods');
        });
    }
};
