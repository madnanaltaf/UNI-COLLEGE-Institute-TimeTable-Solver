<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('institution_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Government Higher Secondary School & College');
            $table->string('academic_year')->default('2026-2027');
            $table->string('term')->default('Annual Term');
            $table->string('motto')->nullable()->default('Excellence in Education & Character');
            $table->json('working_days')->nullable();
            $table->integer('periods_per_day')->default(7);
            $table->integer('friday_periods')->default(4);
            $table->json('day_periods')->nullable();
            $table->integer('break_after_period')->default(4);
            $table->integer('break_duration_mins')->default(30);
            $table->integer('period_duration_mins')->default(45);
            $table->integer('start_hour')->default(8);
            $table->integer('start_min')->default(0);
            $table->timestamps();
        });

        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->index();
            $table->string('subject_type')->default('Theory'); // Theory, Practical Lab, Elective, P.E
            $table->boolean('requires_lab')->default(false);
            $table->string('default_room_type')->default('General Classroom');
            $table->string('color')->default('#3498db');
            $table->boolean('is_difficult')->default(false);
            $table->boolean('allowed_double_period')->default(false);
            $table->timestamps();
        });

        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->foreignId('primary_subject_id')->nullable()->constrained('subjects')->nullOnDelete();
            $table->json('qualified_subjects')->nullable();
            $table->integer('max_periods_per_week')->default(24);
            $table->integer('max_periods_per_day')->default(5);
            $table->json('unavailable_slots')->nullable(); // [[day, period], ...]
            $table->timestamps();
        });

        Schema::create('grade_classes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Class 6, Class 7, etc.
            $table->integer('order_num')->default(0);
            $table->timestamps();
        });

        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('room_type')->default('General Classroom');
            $table->integer('capacity')->default(45);
            $table->string('building')->default('Main Academic Block');
            $table->timestamps();
        });

        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_class_id')->constrained('grade_classes')->cascadeOnDelete();
            $table->string('name'); // 6A, 6B, etc.
            $table->integer('student_count')->default(35);
            $table->foreignId('home_room_id')->nullable()->constrained('classrooms')->nullOnDelete();
            $table->foreignId('class_teacher_id')->nullable()->constrained('teachers')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('lesson_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete();
            $table->foreignId('room_id')->nullable()->constrained('classrooms')->nullOnDelete();
            $table->integer('periods_per_week')->default(4);
            $table->integer('block_size')->default(1); // 1 = single, 2 = double
            $table->string('sync_group_id')->nullable()->index(); // for parallel macro-tasks
            $table->timestamps();
        });

        Schema::create('timetable_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_allocation_id')->nullable()->constrained('lesson_allocations')->nullOnDelete();
            $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('room_id')->nullable()->constrained('classrooms')->nullOnDelete();
            $table->string('day'); // Monday..Friday
            $table->integer('period'); // 1..8
            $table->boolean('is_locked')->default(false);
            $table->timestamps();

            $table->index(['day', 'period']);
            $table->index(['teacher_id', 'day', 'period']);
            $table->index(['section_id', 'day', 'period']);
            $table->index(['room_id', 'day', 'period']);
        });

        Schema::create('duty_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('color')->default('#6c757d');
            $table->timestamps();
        });

        Schema::create('duty_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('duty_type_id')->constrained('duty_types')->cascadeOnDelete();
            $table->string('day');
            $table->integer('period')->default(0); // 0 = Morning Assembly, -1 = Gate Duty, 99 = Recess
            $table->string('location')->default('Campus Courtyard');
            $table->integer('required_teachers')->default(2);
            $table->timestamps();
        });

        Schema::create('duty_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('duty_slot_id')->constrained('duty_slots')->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('duty_assignments');
        Schema::dropIfExists('duty_slots');
        Schema::dropIfExists('duty_types');
        Schema::dropIfExists('timetable_slots');
        Schema::dropIfExists('lesson_allocations');
        Schema::dropIfExists('sections');
        Schema::dropIfExists('classrooms');
        Schema::dropIfExists('grade_classes');
        Schema::dropIfExists('teachers');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('institution_settings');
    }
};
