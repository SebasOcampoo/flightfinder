<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Airport>
 */
class AirportFactory extends Factory
{


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $city = fake()->city();
        $code = $this->generateCode($city);

        return [
            'name' => $city . ' Airport',
            'code' => $code,
            'lat'  => fake()->latitude(),
            'lng'  => fake()->longitude(),
        ];
    }

    public function generateCode($city)
    {
        $abbreviation = strtoupper(substr($city, 0, 3));
        return $abbreviation. strtoupper(fake()->randomLetter());
    }

}
