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
        Schema::create('bardasht_az_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_account_id')->constrained('users_account')->onDelete('cascade');
            $table->foreignId('user_cart_id')->constrained('users_cart')->onDelete('cascade');
            $table->decimal('meghdar', 15, 2);
            $table->timestamp('time');
            $table->enum('vaziate_entghal_b_hesab_karbar', ['pending', 'completed', 'failed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bardasht_az_accounts');
    }
};
