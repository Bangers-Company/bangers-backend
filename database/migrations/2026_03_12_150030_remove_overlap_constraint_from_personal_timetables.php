<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE personal_timetable_entries DROP CONSTRAINT IF EXISTS no_overlap_personal');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE personal_timetable_entries ADD CONSTRAINT no_overlap_personal EXCLUDE USING GIST (timetable_id WITH =, time_range WITH &&)');
    }
};
