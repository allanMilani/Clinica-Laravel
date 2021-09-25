<?php

namespace Database\Factories;

use App\Models\Model;
use App\Models\Phisician;
use Illuminate\Database\Eloquent\Factories\Factory;

class PhisicianFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Phisician::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name
        ];
    }
}
