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
        Schema::create('group_timetables', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('group_id');
            $table->uuid('event_id');
            $table->string('name');
            $table->integer('version')->default(1);
            $table->timestamps();

            $table->unique(['group_id', 'event_id']);
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');

            $table->index('group_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('group_timetables');
    }
};
