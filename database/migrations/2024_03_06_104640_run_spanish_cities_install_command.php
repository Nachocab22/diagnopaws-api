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
        // Poblar la base de datos con towns y provinces de España
        exec('php artisan spanish-cities:install');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
