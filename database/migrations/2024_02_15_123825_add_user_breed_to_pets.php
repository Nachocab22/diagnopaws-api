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
        Schema::table('pets', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained(table: 'users')->onDelete('cascade');
            $table->foreignId('breed_id')->constrained(table: 'breeds')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('breed_id');
        });
    }
};
