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
        Schema::table('friendships', function (Blueprint $table) {
            $table->dropPrimary(['user_id_1', 'user_id_2']);
            $table->uuid('id')->primary()->first();
            $table->unique(['user_id_1', 'user_id_2']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('friendships', function (Blueprint $table) {
            $table->dropUnique(['user_id_1', 'user_id_2']);
            $table->dropColumn('id');
            $table->primary(['user_id_1', 'user_id_2']);
        });
    }
};
