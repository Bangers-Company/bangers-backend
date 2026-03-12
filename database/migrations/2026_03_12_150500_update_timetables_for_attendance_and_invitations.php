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
        // Add is_attending to personal_timetable_entries
        Schema::table('personal_timetable_entries', function (Blueprint $table) {
            $table->boolean('is_attending')->default(false);
        });

        // Add invitation_status to group_members
        Schema::table('group_members', function (Blueprint $table) {
            $table->string('invitation_status', 20)->default('accepted'); // pending, accepted, rejected
            // existing entries are 'accepted' by default
        });

        // Create group_timetable_attendance table
        Schema::create('group_timetable_attendance', function (Blueprint $table) {
            $table->uuid('group_timetable_id');
            $table->uuid('timetable_entry_id');
            $table->uuid('user_id');
            $table->timestamps();

            $table->primary(['group_timetable_id', 'timetable_entry_id', 'user_id'], 'group_tt_attendance_primary');
            $table->foreign('group_timetable_id')->references('id')->on('group_timetables')->onDelete('cascade');
            $table->foreign('timetable_entry_id')->references('id')->on('timetable_entries')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_timetable_attendance');

        Schema::table('group_members', function (Blueprint $table) {
            $table->dropColumn('invitation_status');
        });

        Schema::table('personal_timetable_entries', function (Blueprint $table) {
            $table->dropColumn('is_attending');
        });
    }
};
