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
        Schema::create('user_resume_company_role_request', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_resume_id')->constrained('user_resume')->onDelete('cascade');
            $table->foreignId('company_role_request_id')->constrained('company_role_requests')->onDelete('cascade');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_resume_company_role_request');
    }
};
