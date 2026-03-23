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
        Schema::create('timetable_entries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('timetable_id');
            $table->uuid('stage_id');
            $table->uuid('act_id');
            $table->timestamp('start_time');
            $table->timestamp('end_time');
            $table->integer('version')->default(1);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('timetable_id')->references('id')->on('event_timetables')->onDelete('cascade');
            $table->foreign('stage_id')->references('id')->on('stages')->onDelete('cascade');
            $table->foreign('act_id')->references('id')->on('acts');

            $table->index(['timetable_id', 'start_time']);
            $table->index(['stage_id', 'start_time']);
        });

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE timetable_entries ADD CONSTRAINT valid_time CHECK (end_time > start_time)');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('timetable_entries');
    }
};
