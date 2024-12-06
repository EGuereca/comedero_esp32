<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AdafruitController extends Controller
{
    public function sendData(Request $request)
    {
        // Validar los datos recibidos
        $request->validate([
            'feed' => 'required|string',
            'value' => 'required    ',
        ]);

        // Obtener datos de la solicitud
        $feed = $request->input('feed');
        $value = $request->input('value');

        // Variables de entorno
        $username = env('ADAFRUIT_IO_USERNAME');
        $apiKey = env('ADAFRUIT_IO_KEY');

        // Construir la URL del feed
        $url = "https://io.adafruit.com/$username/feeds/$feed/data";
        
        //KEVIN
        // Enviar los datos a Adafruit IO
        $response = Http::withHeaders([
            'X-AIO-Key' => $apiKey,
        ])->post($url, [
            'value' => $value,
        ]);


        Log::info('Datos enviados a Adafruit:', [
            'url' => $url,
            'headers' => ['X-AIO-Key' => $apiKey],
            'body' => ['value' => $value],
        ]);
        
        Log::info('Respuesta de Adafruit IO:', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);
        

        if ($response->successful()) {
            return response()->json([
                'message' => 'Datos enviados con éxito a Adafruit IO.',
                'response' => $response->json(),
            ], 200);
        } else {
            return response()->json([
                'message' => 'Error al enviar datos a Adafruit IO.',
                'status' => $response->status(),
                'error' => $response->body(),
            ], $response->status());
        }
    }
}
