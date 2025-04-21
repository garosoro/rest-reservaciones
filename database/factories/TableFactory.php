<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Table>
 */
class TableFactory extends Factory
{


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $counter = 1; // Static counter to increment numbers
        static $table_capacity = [2, 4, 6, 8, 10]; // Table capacity options:
        static $status = ['available', 'reserved']; // Table capacity options:
        return [
            //
            "number" => sprintf("M%02d", $counter++),
            "capacity" => fake()->randomElement($table_capacity),
            "status" => fake()->randomElement($status),
        ];
    }
}
