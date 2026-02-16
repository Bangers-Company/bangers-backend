<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === "pgsql") {
            DB::statement("CREATE EXTENSION IF NOT EXISTS pg_trgm");
        }

        Schema::table("festivals", function (Blueprint $table) {
            $table->index("name", "festivals_name_index");
            $table->index("location", "festivals_location_index");
        });

        Schema::table("artists", function (Blueprint $table) {
            $table->index("name", "artists_name_index");
        });

        Schema::table("acts", function (Blueprint $table) {
            $table->index("name", "acts_name_index");
        });

        if ($driver === "pgsql") {
            DB::statement(
                "CREATE INDEX festivals_name_trgm ON festivals USING GIN (name gin_trgm_ops)",
            );
            DB::statement(
                "CREATE INDEX festivals_location_trgm ON festivals USING GIN (location gin_trgm_ops)",
            );
            DB::statement(
                "CREATE INDEX artists_name_trgm ON artists USING GIN (name gin_trgm_ops)",
            );
            DB::statement(
                "CREATE INDEX acts_name_trgm ON acts USING GIN (name gin_trgm_ops)",
            );
        }
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === "pgsql") {
            DB::statement("DROP INDEX IF EXISTS acts_name_trgm");
            DB::statement("DROP INDEX IF EXISTS artists_name_trgm");
            DB::statement("DROP INDEX IF EXISTS festivals_location_trgm");
            DB::statement("DROP INDEX IF EXISTS festivals_name_trgm");
        }

        Schema::table("festivals", function (Blueprint $table) {
            $table->dropIndex("festivals_name_index");
            $table->dropIndex("festivals_location_index");
        });

        Schema::table("artists", function (Blueprint $table) {
            $table->dropIndex("artists_name_index");
        });

        Schema::table("acts", function (Blueprint $table) {
            $table->dropIndex("acts_name_index");
        });
    }
};
