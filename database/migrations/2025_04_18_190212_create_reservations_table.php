<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->date('date'); // Date of the reservation
            $table->time('time'); // Time of the reservation
            $table->smallInteger('number_of_people', false, true); // Number of people for the reservation
            $table->foreignId('diner_id')->constrained('diners')->onDelete('cascade'); // Foreign key to diners table
            $table->foreignId('table_id')->constrained('tables')->onDelete('cascade'); // Foreign key to tables table
            $table->enum('status', ['pending', 'cancelled', 'confirmed'])->default('pending'); // Status of the reservation
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
