<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('donor_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->boolean('available_to_donate')->default(true);
            $table->unsignedSmallInteger('search_radius_km')->default(15);
            $table->enum('auto_respond', ['urgent_city', 'hospitals_only', 'any'])->default('any');
            $table->timestamps();
            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donor_preferences');
    }
};
