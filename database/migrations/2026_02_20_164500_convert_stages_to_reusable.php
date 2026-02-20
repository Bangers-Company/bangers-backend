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
        // 1. Create the pivot table
        Schema::create('event_stages', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('event_id');
            $table->uuid('stage_id');
            $table->timestamps();

            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('stage_id')->references('id')->on('stages')->onDelete('cascade');

            $table->unique(['event_id', 'stage_id']);
        });

        // 2. Migrate existing data from stages table to event_stages
        DB::statement('
            INSERT INTO event_stages (event_id, stage_id, created_at, updated_at)
            SELECT event_id, id, NOW(), NOW()
            FROM stages
            WHERE event_id IS NOT NULL
        ');

        // 3. Drop the old foreign key and column from stages table
        Schema::table('stages', function (Blueprint $table) {
            // Get the foreign key name. In previous migrations it was created as:
            // $table->uuid('event_id')->nullable();
            // $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            // It appears to be stages_festival_id_foreign based on DB check
            $table->dropForeign('stages_festival_id_foreign');
            $table->dropColumn('event_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stages', function (Blueprint $table) {
            $table->uuid('event_id')->nullable();
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        });

        // Restore data (take the first event associated with a stage)
        DB::statement('
            UPDATE stages
            SET event_id = (
                SELECT event_id
                FROM event_stages
                WHERE event_stages.stage_id = stages.id
                LIMIT 1
            )
        ');

        Schema::dropIfExists('event_stages');
    }
};
