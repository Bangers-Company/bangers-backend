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
        Schema::create('event_timetables', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('event_id')->unique();
            $table->string('name')->default('Official Timetable');
            $table->boolean('is_official')->default(false);
            $table->boolean('is_public')->default(false);
            $table->timestamps();

            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        });

        // Add partial unique index for official timetables
        DB::statement('CREATE UNIQUE INDEX idx_one_official_per_event ON event_timetables (event_id) WHERE is_official = TRUE');
    }

    public function down(): void
    {
        Schema::dropIfExists('event_timetables');
    }
};
