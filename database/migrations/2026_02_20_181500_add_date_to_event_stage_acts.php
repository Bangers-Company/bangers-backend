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
        Schema::table('event_stage_acts', function (Blueprint $table) {
            $table->date('date')->nullable()->after('stage_id');
            // created_at was added in a previous migration, so we only need updated_at
            if (!Schema::hasColumn('event_stage_acts', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }

            $table->unique(['event_id', 'stage_id', 'act_id', 'date'], 'event_stage_act_date_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_stage_acts', function (Blueprint $table) {
            $table->dropUnique('event_stage_act_date_unique');
            if (Schema::hasColumn('event_stage_acts', 'updated_at')) {
                $table->dropColumn('updated_at');
            }
            $table->dropColumn('date');
        });
    }
};
