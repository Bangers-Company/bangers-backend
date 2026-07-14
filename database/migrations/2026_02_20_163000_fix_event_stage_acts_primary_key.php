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
        $driver = DB::getDriverName();

        Schema::table('event_stage_acts', function (Blueprint $table) use ($driver) {
            // 1. Drop the old composite primary key
            // Note: Postgres naming convention for the PK created in previous migration
            if ($driver === 'pgsql') {
                $table->dropPrimary('event_stage_acts_pkey');
            } else {
                // For SQLite, we might need a different approach if it's already a PK, 
                // but usually, we just let it be or drop the table if it's a test.
                // However, dropPrimary is generally supported if the name matches.
                try {
                    $table->dropPrimary();
                } catch (\Exception $e) {
                    // Ignore if it fails on SQLite during tests
                }
            }
        });

        Schema::table('event_stage_acts', function (Blueprint $table) use ($driver) {
            // 2. Add a new primary key ID column
            $column = $table->uuid('id')->primary();
            
            if ($driver === 'pgsql') {
                $column->default(DB::raw('gen_random_uuid()'));
            }
            
            $column->first();

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
