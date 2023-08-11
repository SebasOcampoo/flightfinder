<?php

namespace Database\Seeders;

use App\Models\Airport;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AirportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $airports = [
            [
                'name' => 'Leonardo da Vinci-Fiumicino Airport',
                'code' => 'FCO',
                'lat' => 41.8002778,
                'lng' => 12.2388889,
            ],
            [
                'name' => 'Malpensa Airport',
                'code' => 'MXP',
                'lat' => 45.6306,
                'lng' => 8.7239,
            ],
            [
                'name' => 'Orio al Serio International Airport',
                'code' => 'BGY',
                'lat' => 45.6726,
                'lng' => 9.7042,
            ],
            [
                'name' => 'Venice Marco Polo Airport',
                'code' => 'VCE',
                'lat' => 45.5053,
                'lng' => 12.3519,
            ],
            [
                'name' => 'Naples International Airport',
                'code' => 'NAP',
                'lat' => 40.8861,
                'lng' => 14.2908,
            ],
            [
                'name' => 'Florence Airport',
                'code' => 'FLR',
                'lat' => 43.8098,
                'lng' => 11.2051,
            ],
            [
                'name' => 'Bologna Guglielmo Marconi Airport',
                'code' => 'BLQ',
                'lat' => 44.5308,
                'lng' => 11.2969,
            ],
            [
                'name' => 'Milan Linate Airport',
                'code' => 'LIN',
                'lat' => 45.4451,
                'lng' => 9.2767,
            ],
            [
                'name' => 'Turin Airport',
                'code' => 'TRN',
                'lat' => 45.1995,
                'lng' => 7.6496,
            ],
            [
                'name' => 'Catania Fontanarossa Airport',
                'code' => 'CTA',
                'lat' => 37.4668,
                'lng' => 15.0664,
            ],
        ];

        Airport::insert($airports);
    }
}
