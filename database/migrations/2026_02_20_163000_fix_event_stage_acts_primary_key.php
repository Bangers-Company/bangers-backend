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
        Schema::table('event_stage_acts', function (Blueprint $table) {
            // 1. Drop the old composite primary key
            // Note: Postgres naming convention for the PK created in previous migration
            $table->dropPrimary('event_stage_acts_pkey');
        });

        Schema::table('event_stage_acts', function (Blueprint $table) {
            // 2. Add a new primary key ID column
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'))->first();

            // 3. Explicitly allow stage_id to be nullable
            $table->uuid('stage_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_stage_acts', function (Blueprint $table) {
            $table->dropPrimary('event_stage_acts_id_primary');
            $table->dropColumn('id');
        });

        Schema::table('event_stage_acts', function (Blueprint $table) {
            $table->uuid('stage_id')->nullable(false)->change();
            $table->primary(['event_id', 'stage_id', 'act_id']);
        });
    }
};
