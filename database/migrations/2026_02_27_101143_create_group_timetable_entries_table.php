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
        Schema::create('group_timetable_entries', function (Blueprint $table) {
            $table->uuid('group_timetable_id');
            $table->uuid('timetable_entry_id');
            $table->uuid('added_by');
            $table->timestamps();

            $table->primary(['group_timetable_id', 'timetable_entry_id']);
            $table->foreign('group_timetable_id')->references('id')->on('group_timetables')->onDelete('cascade');
            $table->foreign('timetable_entry_id')->references('id')->on('timetable_entries')->onDelete('cascade');
            $table->foreign('added_by')->references('id')->on('users');

            $table->index('group_timetable_id');
            $table->index('added_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('group_timetable_entries');
    }
};
