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
        // Table for users
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('family');
            $table->string('code_mely')->unique()->nullable();
            $table->timestamps();
        });

        // Table for users_mobile
        Schema::create('users_mobile', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('mobile')->unique();
            $table->timestamps();
        });

        // Table for users_account
        Schema::create('users_account', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_mobile_id')->constrained('users_mobile');
            $table->decimal('mojodi_account', 15, 2)->default(0);
            $table->timestamps();
        });

        // Table for users_address
        Schema::create('users_address', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_account_id')->constrained('users_account');
            $table->string('address');
            $table->string('code_posty');
            $table->decimal('lat', 10, 7);
            $table->decimal('long', 10, 7);
            $table->timestamps();
        });

        // Table for users_cart
        Schema::create('users_cart', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('shoamre_shaba');
            $table->string('shomare_cart');
            $table->timestamps();
        });

        // Table for bardasht az account
        Schema::create('bardasht_az_account', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_account_id')->constrained('users_account');
            $table->foreignId('user_cart_id')->constrained('users_cart');
            $table->decimal('meghdar', 15, 2);
            $table->timestamp('time');
            $table->enum('vaziate_entghal_b_hesab_karbar', ['pending', 'completed', 'failed'])->default('pending');
            $table->timestamps();
        });

        // Table for variz
        Schema::create('variz', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_account_id')->constrained('users_account');
            $table->decimal('meghdar', 15, 2);
            $table->string('factor_pardakht');
            $table->timestamp('time');
            $table->timestamps();
        });

        Schema::create('verifications', function (Blueprint $table) {
            $table->id();
            $table->string('mobile')->unique();
            $table->string('code');
            $table->timestamps();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variz');
        Schema::dropIfExists('bardasht_az_account');
        Schema::dropIfExists('users_cart');
        Schema::dropIfExists('users_address');
        Schema::dropIfExists('users_account');
        Schema::dropIfExists('users_mobile');
        Schema::dropIfExists('users');
        Schema::dropIfExists('verifications');
        Schema::dropIfExists('sessions');
    }
};
