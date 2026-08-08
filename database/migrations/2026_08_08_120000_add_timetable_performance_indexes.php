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
        // Index for getGroupTimetableWithAttendance: queried by (group_timetable_id, timetable_entry_id)
        Schema::table('group_timetable_attendance', function (Blueprint $table) {
            $table->index('timetable_entry_id', 'gta_entry_id_idx');
            $table->index('user_id', 'gta_user_id_idx');
        });

        // Index for group_timetable_entries: queried by group_timetable_id (already indexed) + timetable_entry_id
        Schema::table('group_timetable_entries', function (Blueprint $table) {
            $table->index('timetable_entry_id', 'gte_entry_id_idx');
        });

        // Index for user_timetable_favorites: subquery uses (user_id, timetable_entry_id) — primary key covers this
        // But add reverse index for entry-based lookups
        Schema::table('user_timetable_favorites', function (Blueprint $table) {
            $table->index('timetable_entry_id', 'utf_entry_id_idx');
        });

        // Index for timetable_entries: queried by timetable_id + start_time ordering
        Schema::table('timetable_entries', function (Blueprint $table) {
            $table->index(['timetable_id', 'start_time'], 'te_timetable_start_idx');
        });

        // Index for group_timetables: queried by (group_id, event_id)
        Schema::table('group_timetables', function (Blueprint $table) {
            $table->index(['group_id', 'event_id'], 'gt_group_event_idx');
        });

        // Index for event_timetables: queried by (event_id, is_official, is_public)
        if (Schema::hasTable('event_timetables')) {
            Schema::table('event_timetables', function (Blueprint $table) {
                $table->index(['event_id', 'is_official', 'is_public'], 'et_event_official_public_idx');
            });
        }

        // Index for group_members: queried by user_id + invitation_status
        Schema::table('group_members', function (Blueprint $table) {
            $table->index(['user_id', 'invitation_status'], 'gm_user_status_idx');
        });

        // Index for groups: queried by event_id
        Schema::table('groups', function (Blueprint $table) {
            $table->index('event_id', 'groups_event_id_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('group_timetable_attendance', function (Blueprint $table) {
            $table->dropIndex('gta_entry_id_idx');
            $table->dropIndex('gta_user_id_idx');
        });

        Schema::table('group_timetable_entries', function (Blueprint $table) {
            $table->dropIndex('gte_entry_id_idx');
        });

        Schema::table('user_timetable_favorites', function (Blueprint $table) {
            $table->dropIndex('utf_entry_id_idx');
        });

        Schema::table('timetable_entries', function (Blueprint $table) {
            $table->dropIndex('te_timetable_start_idx');
        });

        Schema::table('group_timetables', function (Blueprint $table) {
            $table->dropIndex('gt_group_event_idx');
        });

        if (Schema::hasTable('event_timetables')) {
            Schema::table('event_timetables', function (Blueprint $table) {
                $table->dropIndex('et_event_official_public_idx');
            });
        }

        Schema::table('group_members', function (Blueprint $table) {
            $table->dropIndex('gm_user_status_idx');
        });

        Schema::table('groups', function (Blueprint $table) {
            $table->dropIndex('groups_event_id_idx');
        });
    }
};
