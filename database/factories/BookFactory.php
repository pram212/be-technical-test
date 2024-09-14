<?php

namespace Database\Factories;

use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $authors = Author::pluck('id');   
        return [
            'title' => $this->faker->realText(maxNbChars:10),
            'description' => $this->faker->realText(maxNbChars:225),
            'publish_date' =>$this->faker->date(),
            'author_id' => $authors->random()
        ];
    }
}
