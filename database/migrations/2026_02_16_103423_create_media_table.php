<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("media", function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->uuid("owner_id")->nullable()->index();
            $table->string("type", 50);
            $table->text("storage_key");
            $table->text("url");
            $table->string("mime_type", 100)->nullable();
            $table->integer("size_bytes")->nullable();
            $table->integer("width")->nullable();
            $table->integer("height")->nullable();
            $table->jsonb("metadata")->nullable();
            $table->boolean("is_public")->default(true);
            $table->timestamp("created_at")->useCurrent();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("media");
    }
};
