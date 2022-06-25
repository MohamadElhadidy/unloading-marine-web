<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class VesselFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
        'name' => $this->faker->name(),
        'vessel_id' => mt_rand(1000, 2000),
        'quantity' => mt_rand(1000, 2000),
        ];
    }
}