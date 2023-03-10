<?php

namespace App\Http\Controllers\Ports;

use App\Http\Controllers\Controller;
use App\Models\Port;
use Exception;
use Predis\Client;

class PortsController extends Controller
{
    public function index()
    {
        $redis = $this->getRedisConnection();
        $allPortsKey = "allPorts";

        if ($redis->exists($allPortsKey)) {
            $port =  json_decode($redis->get($allPortsKey), true);
        } else {
            $port = Port::all();

            $redis->setex($allPortsKey, 3600, json_encode($port));
        }

        return response()
            ->json([
                'code'      =>  200,
                'port'   =>  $port,
            ]);
    }
    public function show($id)
    {
        try {

            $redis = $this->getRedisConnection();
            $portKey = "port:" . $id;

            if ($redis->exists($portKey)) {
                $port =  json_decode($redis->get($portKey), true);
            } else {
                $port = Port::find($id) ?? throw new Exception();

                $redis->setex($portKey, 3600, json_encode($port));
            }

            return response()
                ->json([
                    'code'      =>  200,
                    'port'   =>  $port,
                ]);

        } catch (\Throwable $e) {

            return response()
                ->json([
                    'code'      =>  500,
                    'message'   =>  $e->getMessage(),
                ], 500);
        }
    }

    public function getRedisConnection()
    {
        return new Client([
            'scheme' => 'tcp',
            'host'   => env('REDIS_HOST', '127.0.0.1'),
            'port'   => env('REDIS_PORT', 6379),
        ]);
    }
}
