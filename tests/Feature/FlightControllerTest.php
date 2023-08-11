<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FlightControllerTest extends TestCase
{

    public function it_can_get_lowest_price()
    {
        $response = $this->postJson('/search-flights', [
            'departure' => 'TRN',
            'arrival' => 'VCE',
        ]);

        $response->assertSuccessful();
        $response->assertJsonCount(3);

        $lowestPriceFlight = $response->json()[0];
        $this->assertLessThanOrEqual(100, $lowestPriceFlight['price']);
    }
}
