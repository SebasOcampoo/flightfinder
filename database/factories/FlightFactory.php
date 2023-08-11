<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Airport;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Flight>
 */
class FlightFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $airportCodes   = Airport::pluck('code')->toArray();
        $departureCode  = fake()->randomElement($airportCodes);
        $arrivalCode    = fake()->randomElement(array_diff($airportCodes, [$departureCode]));

        return [
            'code_departure'    => $departureCode,
            'code_arrival'      => $arrivalCode,
            'price'             => fake()->randomFloat(2, 50, 750),
        ];
    }

}
