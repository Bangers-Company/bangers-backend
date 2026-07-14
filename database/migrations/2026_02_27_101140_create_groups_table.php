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
        Schema::create('groups', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->text('description')->nullable();
            $table->uuid('owner_id');
            $table->timestamps();

            $table->foreign('owner_id')->references('id')->on('users');
            $table->index('owner_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
