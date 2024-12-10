<?php

namespace App\Services;

use GuzzleHttp\Client;
use App\Models\Comedero;
use Illuminate\Support\Facades\Log;

class AdafruitService
{
    protected $client;
    protected $apiUrl = 'https://io.adafruit.com/api/v2/';
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('ADAFRUIT_IO_KEY'); // Tu clave API desde el archivo .env
    }

    /**
     * Método genérico para obtener datos de cualquier feed.
     */
    public function getFeedData($username, $feedKey)
    {
        $url =  'https://io.adafruit.com/api/v2/'. "{$username}/feeds/{$feedKey}/data";
        Log::info("URL de la solicitud: " . $url);

        $response = $this->client->get($url, [
            'headers' => [
                'X-AIO-Key' => $this->apiKey,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }


    public function updateComederoData($username)
    {
        $feeds = [
            'temperatua_agua' => 'temperatura',
            'humedad' => 'humedad-alimento',
            'cantidad_comida' => 'nivel-comida',
            'cantidad_agua' => 'nivel-agua',
            'cantidad_agua_servida' => 'nivel-agua-servida',
            'cantidad_comida_servida' => 'nivel-comida-servida',
            'gases' => 'gases-comida',
            'mascota_cerca' => 'mascota-cerca',

        ];

        $comedor = Comedero::first();
        Log::info("No hay comederos.");

        foreach ($feeds as $column => $feedKey) {
            $data = $this->getFeedData($username, $feedKey);
            Log::info("{$username}   {$feedKey}");

            if (!empty($data)) {
                
                $lastValue = $data[0]['value'];

            
                $comedor->{$column} = $lastValue;
            }
        }

        $comedor->save();
    }
    /**
     * Método específico para obtener datos del feed de temperatura.
     */
    public function getTemperaturaData($username)
    {
        return $this->getFeedData($username, 'temperatura'); // Reemplaza 'temperatura' con tu feed key real
    }

    /**
     * Método específico para obtener datos del feed de humedad.
     */
    public function getHumedadData($username)
    {
        return $this->getFeedData($username, 'humedad-alimento'); // Reemplaza 'humedad' con tu feed key real
    }

    // Agrega más métodos específicos según los feeds que tengas:
    // public function getOtroFeedData($username)
    // {
    //     return $this->getFeedData($username, 'otro_feed');
    // }
}
