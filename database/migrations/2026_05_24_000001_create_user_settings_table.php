<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->json('notifications')->nullable();
            $table->json('privacy')->nullable();
            $table->json('security')->nullable();
            $table->json('appearance')->nullable();
            $table->string('language', 5)->default('en');
            $table->text('medical_info')->nullable();
            $table->boolean('quiet_hours')->default(false);
            $table->timestamps();
            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_settings');
    }
};
