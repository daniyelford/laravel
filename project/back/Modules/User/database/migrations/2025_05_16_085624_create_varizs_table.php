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
        Schema::create('varizs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_account_id')->constrained('users_account')->onDelete('cascade');
            $table->decimal('meghdar', 15, 2);
            $table->string('factor_pardakht');
            $table->timestamp('time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('varizs');
    }
};
