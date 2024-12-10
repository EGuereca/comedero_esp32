<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AdafruitService;

class ComederoController extends Controller
{
    protected $adafruitService;

    public function __construct(AdafruitService $adafruitService)
    {
        $this->adafruitService = $adafruitService;
    }

    public function syncComederoData()
    {
        $username =  env('ADAFRUIT_IO_USERNAME');
        
        $this->adafruitService->updateComederoData($username);

        return response()->json(['message' => 'Datos de comedero actualizados correctamente.']);
    }
}
