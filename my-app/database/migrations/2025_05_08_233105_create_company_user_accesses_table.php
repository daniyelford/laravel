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
        Schema::create('company_user_accesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_user_id')->constrained('company_users')->onDelete('cascade');
            $table->foreignId('role_id')->constrained('company_roles')->onDelete('cascade');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_user_accesses');
    }
};
