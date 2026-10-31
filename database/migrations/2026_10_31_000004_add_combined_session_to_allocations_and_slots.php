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
        Schema::table('lesson_allocations', function (Blueprint $table) {
            $table->boolean('is_combined')->default(false)->after('block_size');
            $table->string('combined_group_id')->nullable()->index()->after('is_combined');
        });

        Schema::table('timetable_slots', function (Blueprint $table) {
            $table->boolean('is_combined')->default(false)->after('is_locked');
            $table->string('combined_group_id')->nullable()->index()->after('is_combined');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('timetable_slots', function (Blueprint $table) {
            $table->dropIndex(['combined_group_id']);
            $table->dropColumn(['is_combined', 'combined_group_id']);
        });

        Schema::table('lesson_allocations', function (Blueprint $table) {
            $table->dropIndex(['combined_group_id']);
            $table->dropColumn(['is_combined', 'combined_group_id']);
        });
    }
};
