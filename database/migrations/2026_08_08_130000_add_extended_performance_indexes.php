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
        // Index for acts: queried by name and soft deletes
        if (Schema::hasTable('acts')) {
            Schema::table('acts', function (Blueprint $table) {
                $table->index('name', 'acts_name_idx');
                $table->index('deleted_at', 'acts_deleted_at_idx');
            });
        }

        // Index for act_artists: reverse lookup by artist_id
        if (Schema::hasTable('act_artists')) {
            Schema::table('act_artists', function (Blueprint $table) {
                $table->index('artist_id', 'act_artists_artist_idx');
            });
        }

        // Index for users: profile_media_id lookup
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $table->index('profile_media_id', 'users_profile_media_idx');
            });
        }

        // Index for friendships: queried by (user_id_1, status) and (user_id_2, status)
        if (Schema::hasTable('friendships')) {
            Schema::table('friendships', function (Blueprint $table) {
                $table->index(['user_id_1', 'status'], 'friendships_user1_status_idx');
                $table->index(['user_id_2', 'status'], 'friendships_user2_status_idx');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('acts')) {
            Schema::table('acts', function (Blueprint $table) {
                $table->dropIndex('acts_name_idx');
                $table->dropIndex('acts_deleted_at_idx');
            });
        }

        if (Schema::hasTable('act_artists')) {
            Schema::table('act_artists', function (Blueprint $table) {
                $table->dropIndex('act_artists_artist_idx');
            });
        }

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropIndex('users_profile_media_idx');
            });
        }

        if (Schema::hasTable('friendships')) {
            Schema::table('friendships', function (Blueprint $table) {
                $table->dropIndex('friendships_user1_status_idx');
                $table->dropIndex('friendships_user2_status_idx');
            });
        }
    }
};
