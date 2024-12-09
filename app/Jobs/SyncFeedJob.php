<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class SyncFeedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $feed;
    protected $modelClass;

    /**
     * Crear una nueva instancia del Job.
     */
    public function __construct($feed, $modelClass)
    {
        $this->feed = $feed;
        $this->modelClass = $modelClass;
    }

    /**
     * Lógica que ejecutará el Job.
     */
    public function handle()
    {
        $username = env('ADAFRUIT_IO_USERNAME');
        $apiKey = env('ADAFRUIT_IO_KEY');
        $url = "https://io.adafruit.com/api/v2/{$username}/feeds/{$this->feed}/data";

        // Realizar la solicitud a Adafruit IO
        $response = Http::withHeaders([
            'X-AIO-Key' => $apiKey,
        ])->get($url);

        if ($response->successful()) {
            $data = $response->json();

            // Guardar los datos en la base de datos
            foreach ($data as $entry) {
                $this->modelClass::create([
                    'valor' => $entry['value'],
                    'fecha' => Carbon::parse($entry['created_at'])->format('Y-m-d H:i:s'),
                ]);
            }
        }
    }
}
