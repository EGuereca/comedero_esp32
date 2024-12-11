<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AdafruitService;
use GuzzleHttp\Client;

class ComederoController extends Controller
{
    protected $adafruitService;

    public function __construct(AdafruitService $adafruitService)
    {
        $this->adafruitService = $adafruitService;
    }

    public function syncComederoData()
    {
        $username = env('ADAFRUIT_IO_USERNAME');
        
        $this->adafruitService->updateComederoData($username);
        

        return response()->json(['message' => 'Datos de comedero actualizados correctamente.'], 200);

    }

    public function store(Request $request)
    {

        $username = env('ADAFRUIT_IO_USERNAME');
        $apikey = env('ADAFRUIT_IO_KEY');
        
        $request->validate([
            'estado' => 'required|in:1,2,3'
        ]);

        $estado = $request->input('estado');
        
        
        $client = new Client();
        $feedKey = 'dispensador-comida';
        $adafruitUrl = "https://io.adafruit.com/api/v2/$username/feeds/$feedKey/data";

        try {
            $response = $client->post($adafruitUrl, [
                'headers' => [
                    'X-AIO-Key' => $apikey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'value' => $estado,
                ],
            ]);

            return response()->json([
                'message' => 'Comedor creado y datos enviados a Adafruit IO',
                'data' => $response->getBody()->getContents(),
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No se pudo enviar los datos a Adafruit IO',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
