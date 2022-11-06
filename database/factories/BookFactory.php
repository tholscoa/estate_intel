<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string,
     *  mixed>
     */
    public function definition()
    {
        return [
            'name'=>fake()->name(),
            'isbn'=>fake()->numberBetween(9999, 9999999999),
             'country'=>fake()->country(),
             'number_of_pages'=>fake()->numberBetween(1-3000),
              'authors'=>[fake()->name()],
             'publisher'=>fake()->name(),
             'release_date'=>date('Y-m-d')
        ];
    }
}
