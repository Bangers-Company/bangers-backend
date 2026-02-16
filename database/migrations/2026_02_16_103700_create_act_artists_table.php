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
        Schema::create('act_artists', function (Blueprint $table) {
            $table->foreignUuid('act_id')->constrained('acts')->onDelete('cascade');
            $table->foreignUuid('artist_id')->constrained('artists')->onDelete('cascade');
            $table->primary(['act_id', 'artist_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('act_artists');
    }
};
