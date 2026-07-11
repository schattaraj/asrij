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
        Schema::create('donation_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('mobile');
            $table->string('email');
            $table->string('pan_no');
            $table->text('address');
            $table->decimal('amount', 12, 2);
            $table->string('screenshot'); // path to the uploaded transaction screenshot
            $table->string('status')->default('pending'); // pending | verified | rejected
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donation_payments');
    }
};
