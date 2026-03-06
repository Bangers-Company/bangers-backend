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
        // Ensure btree_gist extension is available for exclusion constraint
        DB::statement('CREATE EXTENSION IF NOT EXISTS btree_gist');

        Schema::create('personal_timetable_entries', function (Blueprint $table) {
            $table->uuid('timetable_id');
            $table->uuid('timetable_entry_id');
            $table->rawIndex('tsrange(NULL, NULL)', 'idx_personal_timetable_entries_range'); // Placeholder for index

            $table->primary(['timetable_id', 'timetable_entry_id']);
            $table->foreign('timetable_id')->references('id')->on('personal_timetables')->onDelete('cascade');
            $table->foreign('timetable_entry_id')->references('id')->on('timetable_entries')->onDelete('cascade');
        });

        // Add the tsrange column and the exclusion constraint manually
        DB::statement('ALTER TABLE personal_timetable_entries ADD COLUMN time_range tsrange NOT NULL');
        DB::statement('ALTER TABLE personal_timetable_entries ADD CONSTRAINT no_overlap_personal EXCLUDE USING GIST (timetable_id WITH =, time_range WITH &&)');
    }

    public function down(): void
    {
        Schema::dropIfExists('personal_timetable_entries');
    }
};
