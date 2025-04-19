<?php

namespace Database\Seeders;

use App\Models\Diner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DinerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Diner::factory()->count(10)->create();
    }
}
