<?php

namespace App\Http\Controllers\Airports;

use App\Http\Controllers\Controller;
use App\Models\Airport;
use Predis\Client;

class AirportsController extends Controller
{
    public function index()
    {
        $redis = $this->getRedisConnection();
        $allAirportsKey = "allAirorts";

        if ($redis->exists($allAirportsKey)) {
            $airport =  json_decode($redis->get($allAirportsKey), true);
        } else {
            $airport = Airport::all();

            $redis->setex($allAirportsKey, 3600, json_encode($airport));
        }

        return response()
            ->json([
                'code'      =>  200,
                'airport'   =>  $airport,
            ], 200);
    }

    public function show($id)
    {
        try {

            $redis = new Client([
                'scheme' => 'tcp',
                'host'   => env('REDIS_HOST', '127.0.0.1'),
                'port'   => env('REDIS_PORT', 6379),
            ]);

            $airportKey = "airport:" . $id;

            if ($redis->exists($airportKey)) {
                $airport =  json_decode($redis->get($airportKey), true);
            } else {
                $airport = Airport::find($id);

                $redis->setex($airportKey, 3600, json_encode($airport));
            }

            return response()
                ->json([
                    'code'      =>  200,
                    'airport'   =>  $airport,
                ], 200);

        } catch (\Throwable $e) {

            return response()
                ->json([
                    'code'      =>  500,
                    'message'   =>  $e->getMessage(),
                ], 500);
        }
    }
}
