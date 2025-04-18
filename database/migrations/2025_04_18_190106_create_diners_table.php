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
        Schema::create('diners', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Name of the diner
            $table->string('email')->unique(); // Unique email for the diner
            $table->string('telephone')->nullable(); // Telephone number of the diner
            $table->string('address')->nullable(); //address of the dinner
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diners');
    }
};
