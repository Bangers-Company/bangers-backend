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
        Schema::table('stage_acts', function (Blueprint $table) {
            // Drop old primary key first using its original name
            $table->dropPrimary('stage_acts_pkey');
        });

        Schema::rename('stage_acts', 'event_stage_acts');

        Schema::table('event_stage_acts', function (Blueprint $table) {
            $table->uuid('event_id')->after('stage_id')->nullable();
        });

        Schema::table('event_stage_acts', function (Blueprint $table) {
            $table->uuid('stage_id')->nullable()->change();
        });

        // Data migration: populate event_id from stages table using raw SQL for PostgreSQL compatibility
        DB::statement('
            UPDATE event_stage_acts
            SET event_id = stages.event_id
            FROM stages
            WHERE event_stage_acts.stage_id = stages.id
        ');

        Schema::table('event_stage_acts', function (Blueprint $table) {
            // Now that data is migrated, we can make event_id required and add foreign key
            $table->uuid('event_id')->nullable(false)->change();

            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->primary(['event_id', 'stage_id', 'act_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_stage_acts', function (Blueprint $table) {
            $table->dropForeign(['event_id']);
            $table->dropPrimary(['event_id', 'stage_id', 'act_id']);
        });

        Schema::table('event_stage_acts', function (Blueprint $table) {
            $table->uuid('stage_id')->nullable(false)->change();
        });

        Schema::rename('event_stage_acts', 'stage_acts');

        Schema::table('stage_acts', function (Blueprint $table) {
            $table->primary(['stage_id', 'act_id']);
        });

        Schema::table('stage_acts', function (Blueprint $table) {
            $table->dropColumn('event_id');
        });
    }
};
