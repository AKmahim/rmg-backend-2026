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
        // 'phone_number',// unique phone number
        // 'score',
        // 'ip_address',
        // 'user_agent',// mobile or desktop
        Schema::create('spinners', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number')->unique();
            $table->integer('score')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->integer('played_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spinners');
    }
};
