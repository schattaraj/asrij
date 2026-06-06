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
        Schema::create('volunteer_organizations', function (Blueprint $table) {
            $table->id();

            $table->string('type');
            $table->string('organization_name');
            $table->string('registration_number')->unique();
            $table->string('contact_number');
            $table->string('email')->nullable();
            $table->text('address')->nullable();

            $table->string('president_name')->nullable();
            $table->string('president_number')->nullable();
            $table->string('secretary_name')->nullable();
            $table->string('secretary_number')->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->foreignId('created_by');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('volunteer_organizations');
    }
};
