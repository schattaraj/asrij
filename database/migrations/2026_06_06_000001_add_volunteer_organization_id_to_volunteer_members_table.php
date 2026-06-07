<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('volunteer_members', function (Blueprint $table) {
            if (!Schema::hasColumn('volunteer_members', 'volunteer_organization_id')) {
                $table->foreignId('volunteer_organization_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('volunteer_organizations')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('volunteer_members', function (Blueprint $table) {
            if (Schema::hasColumn('volunteer_members', 'volunteer_organization_id')) {
                $table->dropConstrainedForeignId('volunteer_organization_id');
            }
        });
    }
};
