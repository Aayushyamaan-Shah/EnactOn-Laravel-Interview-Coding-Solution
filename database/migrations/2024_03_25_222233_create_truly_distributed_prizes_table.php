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
        Schema::create('truly_distributed_prizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prize_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('count');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('truly_distributed_prizes');
    }
};
