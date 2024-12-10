<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\SyncFeedJob;
use App\Models\Temperatura;
use App\Models\Humedad;
use App\Models\DispensadorComida;
use App\Models\GasesComida;
use App\Models\MascotaCerca;
use App\Models\NivelAgua;
use App\Models\NivelAguaServida;
use App\Models\NivelComida;
use App\Models\NivelComidaServida;
use Illuminate\Support\Facades\Log;

class AdafruitController extends Controller
{
    protected $username;
    protected $apiKey;

    public function __construct()
    {
        $this->username = env('ADAFRUIT_IO_USERNAME');
        $this->apiKey = env('ADAFRUIT_IO_KEY');
    }

    /**
     * Sincronizar datos de un feed específico con su tabla correspondiente.
     */
    public function syncFeed(Request $request, $feed)
    {
        // Mapeo de feeds a sus modelos correspondientes
        $modelMapping = [
            'temperatura' => Temperatura::class,
            'humedad-alimento' => Humedad::class,
            'dispensador-comida' => DispensadorComida::class,
            'gases-comida' => GasesComida::class,
            'mascota-cerca' => MascotaCerca::class,
            'nivel-agua' => NivelAgua::class,
            'nivel-agua-servida' => NivelAguaServida::class,
            'nivel-comida' => NivelComida::class,
            'nivel-comida-servida' => NivelComidaServida::class,
        ];

        if (!array_key_exists($feed, $modelMapping)) {
            return response()->json([
                'message' => "El feed '{$feed}' no está configurado para sincronizarse.",
            ], 400);
        }


        SyncFeedJob::dispatch($feed, $modelMapping[$feed]);

        
        return response()->json([
            'message' => "Sincronización del feed '{$feed}' en proceso.",
        ], 200);
    }
}
