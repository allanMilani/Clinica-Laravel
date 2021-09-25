<?php

namespace Database\Seeders;

use App\Models\Phisician;
use Illuminate\Database\Seeder;

class PhisicianTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Phisician::factory()->count(10)->create();
    }
}
