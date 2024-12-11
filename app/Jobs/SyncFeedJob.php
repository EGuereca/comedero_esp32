<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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
        Log::info("Iniciando sincronización para feed: {$this->feed}");

        $username = env('ADAFRUIT_IO_USERNAME');
        $apiKey = env('ADAFRUIT_IO_KEY');
        $url = "https://io.adafruit.com/api/v2/$username/feeds/{$this->feed}/data/last";

        $response = Http::withHeaders([
            'X-AIO-key' => $apiKey,
        ])->get($url);

        if ($response->successful()) {
            $data = $response->json();

            
            Log::info("Respuesta recibida para el feed {$this->feed}: " . json_encode($data));

            
            if (isset($data['value'])) {
                $lastValue = $data['value'];
                $createdAt = Carbon::parse($data['created_at'])->format('Y-m-d H:i:s');

                
                $this->modelClass::create([
                    'valor' => $lastValue,
                    'fecha' => $createdAt,
                ]);
                Log::info("Valor {$lastValue} guardado correctamente para el feed {$this->feed}.");
            } else {
                Log::error("El campo 'value' no se encuentra en la respuesta para el feed {$this->feed}. Respuesta: " . json_encode($data));
            }
        } else {
            Log::error("Error al sincronizar el feed {$this->feed}: " . $response->status());
        }
    }
}
