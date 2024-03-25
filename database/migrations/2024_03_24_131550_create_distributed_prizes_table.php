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
        Schema::create('distributed_prizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prize_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('count');
            $table->unsignedInteger('remaining');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distributed_prizes');
    }
};
