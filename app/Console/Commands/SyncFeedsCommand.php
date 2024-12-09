<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\SyncFeedJob;

class SyncFeedsCommand extends Command
{
    protected $signature = 'sync:feeds';

    protected $description = 'Sincroniza automáticamente los datos de los feeds de Adafruit';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $feeds = [
            'humedad-alimento' => \App\Models\Humedad::class,
            'dispensador-comida' => \App\Models\DispensadorComida::class,
            'nivel-agua' => \App\Models\NivelAgua::class,
            'nivel-agua-servida' => \App\Models\NivelAguaServida::class,
            'nivel-comida' => \App\Models\NivelComida::class,
            'nivel-comida-servida' => \App\Models\NivelComidaServida::class,
            'temperatura-agua' => \App\Models\Temperatura::class,
            'gases-comida' => \App\Models\GasesComida::class,
            'mascota-cerca' => \App\Models\MascotaCerca::class,
        ];

        foreach ($feeds as $feed => $modelClass) {
            SyncFeedJob::dispatch($feed, $modelClass);
            $this->info("Sincronizando feed: {$feed}");
        }

        return Command::SUCCESS;
    }
}
