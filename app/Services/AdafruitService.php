<?php

namespace App\Services;

use GuzzleHttp\Client;

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
        $url = "{$this->apiUrl}{$username}/feeds/{$feedKey}/data";

        $response = $this->client->get($url, [
            'headers' => [
                'X-AIO-Key' => $this->apiKey,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * Método específico para obtener datos del feed de temperatura.
     */
    public function getTemperaturaData($username)
    {
        return $this->getFeedData($username, 'temperatura-agua'); // Reemplaza 'temperatura' con tu feed key real
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
