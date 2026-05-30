<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('volunteers', function (Blueprint $table) {
            if (!Schema::hasColumn('volunteers', 'organization')) {
                $table->string('organization')->nullable()->after('volunteer_type');
            }

            if (!Schema::hasColumn('volunteers', 'contact_number')) {
                $table->string('contact_number', 20)->nullable()->after('organization');
            }

            if (!Schema::hasColumn('volunteers', 'address')) {
                $table->text('address')->nullable()->after('contact_number');
            }

            if (!Schema::hasColumn('volunteers', 'latitude')) {
                $table->decimal('latitude', 10, 7)->nullable()->after('address');
            }

            if (!Schema::hasColumn('volunteers', 'longitude')) {
                $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            }

            if (!Schema::hasColumn('volunteers', 'pin_code')) {
                $table->string('pin_code', 10)->nullable()->after('longitude');
            }

            if (!Schema::hasColumn('volunteers', 'registration_number')) {
                $table->string('registration_number')->nullable()->after('pin_code');
            }

            if (!Schema::hasColumn('volunteers', 'group_quantity')) {
                $table->unsignedInteger('group_quantity')->nullable()->after('registration_number');
            }

            if (!Schema::hasColumn('volunteers', 'president_name')) {
                $table->string('president_name')->nullable()->after('group_quantity');
            }

            if (!Schema::hasColumn('volunteers', 'president_number')) {
                $table->string('president_number', 20)->nullable()->after('president_name');
            }

            if (!Schema::hasColumn('volunteers', 'secretary_name')) {
                $table->string('secretary_name')->nullable()->after('president_number');
            }

            if (!Schema::hasColumn('volunteers', 'secretary_number')) {
                $table->string('secretary_number', 20)->nullable()->after('secretary_name');
            }

            if (!Schema::hasColumn('volunteers', 'account_name')) {
                $table->string('account_name')->nullable()->after('secretary_number');
            }

            if (!Schema::hasColumn('volunteers', 'account_number')) {
                $table->string('account_number', 50)->nullable()->after('account_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('volunteers', function (Blueprint $table) {
            foreach ([
                'account_number',
                'account_name',
                'secretary_number',
                'secretary_name',
                'president_number',
                'president_name',
                'group_quantity',
                'registration_number',
                'pin_code',
                'longitude',
                'latitude',
                'address',
                'contact_number',
                'organization',
            ] as $column) {
                if (Schema::hasColumn('volunteers', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
