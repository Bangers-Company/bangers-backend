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
        Schema::create('festival_acts', function (Blueprint $table) {
            $table->foreignUuid('festival_id')->constrained('festivals')->onDelete('cascade');
            $table->foreignUuid('act_id')->constrained('acts')->onDelete('cascade');
            $table->timestamp('announcement_date')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->primary(['festival_id', 'act_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('festival_acts');
    }
};
