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
        Schema::dropIfExists('festival_acts');

        Schema::create('stage_acts', function (Blueprint $table) {
            $table->uuid('stage_id');
            $table->uuid('act_id');
            $table->timestamp('created_at')->useCurrent();

            $table->primary(['stage_id', 'act_id']);

            $table->foreign('stage_id')->references('id')->on('stages')->onDelete('cascade');
            $table->foreign('act_id')->references('id')->on('acts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stage_acts');

        Schema::create('festival_acts', function (Blueprint $table) {
            $table->uuid('festival_id');
            $table->uuid('act_id');
            $table->timestamp('announcement_date')->nullable();
            $table->timestamps();

            $table->primary(['festival_id', 'act_id']);

            $table->foreign('festival_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('act_id')->references('id')->on('acts')->onDelete('cascade');
        });
    }
};
