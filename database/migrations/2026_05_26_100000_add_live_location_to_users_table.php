<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Adds live-location columns used by the in-app "track donor"
     * feature. These are kept separate from `latitude` / `longitude`
     * (which represent the user's registered address) so that the
     * registered location is never overwritten by transient GPS pings.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'current_latitude')) {
                $table->decimal('current_latitude', 10, 7)->nullable()->after('longitude');
            }
            if (!Schema::hasColumn('users', 'current_longitude')) {
                $table->decimal('current_longitude', 10, 7)->nullable()->after('current_latitude');
            }
            if (!Schema::hasColumn('users', 'location_updated_at')) {
                $table->timestamp('location_updated_at')->nullable()->after('current_longitude');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'location_updated_at')) {
                $table->dropColumn('location_updated_at');
            }
            if (Schema::hasColumn('users', 'current_longitude')) {
                $table->dropColumn('current_longitude');
            }
            if (Schema::hasColumn('users', 'current_latitude')) {
                $table->dropColumn('current_latitude');
            }
        });
    }
};
