<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FicheroFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'file' => $this->faker->file(public_path('storage/tmp'), public_path('storage'), false),
            'user_id' => $this->faker->numberBetween(1, 3),
        
        ];
    }
}
