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
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('last_login_at')->nullable()->after('password');
            $table->string('first_name')->nullable()->change();
            $table->string('last_name')->nullable()->change();
        });

        Schema::create('genres', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('genreables', function (Blueprint $table) {
            $table->uuid('genre_id');
            $table->uuid('genreable_id');
            $table->string('genreable_type');
            
            $table->primary(['genre_id', 'genreable_id', 'genreable_type']);
            
            $table->foreign('genre_id')
                  ->references('id')
                  ->on('genres')
                  ->onDelete('cascade');
                  
            $table->index(['genreable_id', 'genreable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('genreables');
        Schema::dropIfExists('genres');
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('last_login_at');
            $table->string('first_name')->nullable(false)->change();
            $table->string('last_name')->nullable(false)->change();
        });
    }
};
