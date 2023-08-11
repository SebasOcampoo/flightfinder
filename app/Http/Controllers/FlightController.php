<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Flight;

class FlightController extends Controller
{

    private const MAX_RESULT_BY_QUERY = 50;

    /**
     * Search for flights based on the given request parameters.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchFlights(Request $request)
    {
        $departureCode      = $request->input('departure');
        $arrivalCode        = $request->input('arrival');
        $maxStops           = $request->input('stops', 0);

        if($maxStops) {
            $flights = $this->searchStopOverFlights($departureCode, $arrivalCode, $maxStops);
        }else{
            $flights = $this->searchDirectFlights($departureCode, $arrivalCode);
        }

        return response()->json($flights);
    }

     /**
     * Search for direct flights between two airports.
     *
     * @param  string  $departureCode
     * @param  string  $arrivalCode
     * @return array
     */
    public function searchDirectFlights(string $departureCode, string $arrivalCode) {

        $flights = Flight::with('departureAirport', 'arrivalAirport')
                        ->where('code_departure', $departureCode)
                        ->where('code_arrival', $arrivalCode)
                        ->orderBy('price')
                        ->limit(self::MAX_RESULT_BY_QUERY)
                        ->get();

        $formattedFlights = $flights->map(function ($flight) {
            return $this->formatFlights([$flight]);
        });

        return $formattedFlights;
    }

    /**
     * Search for flights with stopovers between airports.
     *
     * @param  string  $departureCode
     * @param  string  $arrivalCode
     * @param  int  $maxStops
     * @return array
     */
    public function searchStopOverFlights(string $departureCode, string $arrivalCode, int $maxStops){

        $connections = [];
        $departures   = Flight::where('code_departure', $departureCode)->get();
        $flights      = Flight::all();

        // Iterate through possible flight combinations for stop connections
        foreach ($departures as $flight1) {
            foreach ($flights as $flight2) {
                if ($flight2->code_departure == $flight1->code_arrival) {
                    if ($flight2->code_arrival == $arrivalCode) {
                        $connections[] = $this->formatFlights([$flight1, $flight2]);
                    } elseif ($maxStops > 1) {
                        foreach ($flights as $flight3) {
                            if ($flight3->code_departure == $flight2->code_arrival && $flight3->code_arrival == $arrivalCode) {
                                $connections[] = $this->formatFlights([$flight1, $flight2, $flight3]);
                            }
                        }
                    }
                }
            }
        }

        // Sort connections by price
        $sortedConnections = collect($connections)->sortBy('price');
        return $sortedConnections->values()->all();
   }

   /**
     * Format flight data for response.
     *
     * @param  array  $flights
     * @return array
     */
   private function formatFlights($flights){

        $totalPrice = 0;
        $stopovers  =[];
        $numFlights = count($flights);
        // Calculate total price and collect stopover information
        foreach($flights as $index => $flight){
            $totalPrice += $flight->price;
            if($index > 0){
                $stopovers[] = $flight->departureAirport->name .' ('.$flight->code_departure .') -> ' .
                            $flight->arrivalAirport->name .' ('.$flight->code_arrival .')';
            }
        }
        // Prepare formatted flight data
        return [
            'departure_code'    => $flights[0]->code_departure,
            'departure_airport' => $flights[0]->departureAirport->name,
            'arrival_code'      => $flights[$numFlights-1]->code_arrival,
            'arrival_airport'   => $flights[$numFlights-1]->arrivalAirport->name,
            'price'             => round($totalPrice,2),
            'stopovers'         => $stopovers
        ];
    }
}
