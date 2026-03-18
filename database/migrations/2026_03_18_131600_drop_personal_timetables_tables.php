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
        Schema::dropIfExists('personal_timetable_entries');
        Schema::dropIfExists('personal_timetables');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No going back
    }
};
