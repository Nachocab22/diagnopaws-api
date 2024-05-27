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
        Schema::create('vaccination', function (Blueprint $table) {
            $table->id();
            $table->date('vaccination_date');
            $table->date('next_vaccination_date');
            $table->string('lot_number')->nullable();
            $table->foreignId('pet_id')->constrained(table: 'pets')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vaccination');
    }
};
