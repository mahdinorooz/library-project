<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'author' => $this->faker->name,
            'description' => $this->faker->realText(200,2),
            'release_date' => $this->faker->dateTime,
            'number_of_pages' => rand(111,999),
        ];
    }
}
