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

    /**
     * Método para crear un group feed en Adafruit.
     */
    public function createGroupFeed($username, $groupName, $description = '')
    {
        $url = "{$this->apiUrl}{$username}/groups";

        $response = $this->client->post($url, [
            'headers' => [
                'X-AIO-Key' => $this->apiKey,
            ],
            'json' => [
                'name' => $groupName,
                'description' => $description,
            ],
        ]);

        if ($response->getStatusCode() === 201) {
            return json_decode($response->getBody(), true);
        }

        return null;
    }

    /**
     * Método genérico para crear un feed dentro de un group feed.
     */
    public function createFeedInGroup($username, $groupName, $feedKey, $feedName)
    {
        $url = "{$this->apiUrl}{$username}/groups/{$groupName}/feeds";

        $response = $this->client->post($url, [
            'headers' => [
                'X-AIO-Key' => $this->apiKey,
            ],
            'json' => [
                'key' => $feedKey,
                'name' => $feedName,
            ],
        ]);

        if ($response->getStatusCode() === 201) {
            return json_decode($response->getBody(), true);
        }

        return null;
    }

    // Agrega más métodos específicos según tus necesidades.
}
