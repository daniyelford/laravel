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
        Schema::create('instagram_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('full_name')->nullable();
            $table->integer('followers')->nullable();
            $table->text('bio')->nullable();
            $table->string('profile_pic_url')->nullable();
            $table->text('error')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instagram_profiles');
    }
};
