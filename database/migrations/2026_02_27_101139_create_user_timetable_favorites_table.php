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
        Schema::create('user_timetable_favorites', function (Blueprint $table) {
            $table->uuid('user_id');
            $table->uuid('timetable_entry_id');
            $table->timestamp('created_at')->useCurrent();

            $table->primary(['user_id', 'timetable_entry_id']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('timetable_entry_id')->references('id')->on('timetable_entries')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_timetable_favorites');
    }
};
