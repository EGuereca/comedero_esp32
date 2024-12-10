<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Mascota;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Comedero;
use Illuminate\Support\Facades\Http;

class FeederController extends Controller
{
    public function crearMascota(Request $request)
    {
        $data = $request->all();

        $validatedData = Validator::make($data, [
            'nombre' => 'required|string|max:255',
            'animal' => 'required|in:perro,gato,otro',
            'img' => 'nullable|string|max:255',
            'comidas_diarias' => 'nullable|integer|min:0',
        ]);

        if ($validatedData->fails()) {
            return response()->json(["validator" => $validatedData->errors()], 422);
        }

        Mascota::create([
            'usuario_id' => Auth::id(),
            'nombre' => $request->nombre,
            'animal' => $request->animal,
            'comidas_diarias' => $request->comidas_diarias ?? null,
        ]);

        return response()->json([
            'message' => 'Mascota creada exitosamente',
        ], 201);
    }

    public function verMascotas()
    {
        $mascotas = Mascota::where('usuario_id', Auth::id())->get();

        return response()->json([
            'message' => 'Mascotas del usuario autenticado',
            'data' => $mascotas,
        ]);
    }

    public function crearComedero(Request $request)
    {
        $data = $request->all();

        // Validación de los datos de entrada
        $validatedData = Validator::make($data, [
            'mascota_id' => 'required|exists:mascotas,id',
            'numero_serie' => 'required|string',
        ]);

        if ($validatedData->fails()) {
            return response()->json(["validator" => $validatedData->errors()], 422);
        }

        // Verificar el número de serie
        $numeroSerie = DB::table('numero_serie')->where('numero_serie', $request->numero_serie)->first();

        if (!$numeroSerie) {
            return response()->json([
                'message' => 'El número de serie no existe.',
            ], 400);
        }

        if ($numeroSerie->estado === 'inactivo') {
            return response()->json([
                'message' => 'El número de serie existe, pero está inactivo.',
            ], 400);
        }

        // Crear el comedero en la base de datos
        $comedero = Comedero::create([
            'usuario_id' => Auth::id(),
            'mascota_id' => $request->mascota_id,
            'numero_serie' => $request->numero_serie,
            'estado' => 'ACTIVO',
        ]);

        // Actualizar el estado del número de serie
        DB::table('numero_serie')
            ->where('numero_serie', $request->numero_serie)
            ->update(['estado' => 'inactivo']);

        // Crear un grupo de feeds en Adafruit
        $adafruitApiKey = env('ADAFRUIT_IO_KEY');
        $username = env('ADAFRUIT_IO_USERNAME');
        $groupName = 'comedero_' . $request->numero_serie;

        $groupResponse = Http::withHeaders([
            'X-AIO-Key' => $adafruitApiKey,
        ])->post("https://io.adafruit.com/api/v2/{$username}/groups", [
            'name' => $groupName,
            'description' => 'Grupo de feeds para el comedero asociado al número de serie ' . $request->numero_serie,
        ]);

        if ($groupResponse->failed()) {
            return response()->json([
                'message' => 'Comedero creado en la base de datos, pero hubo un error al crear el grupo en Adafruit.',
                'error' => $groupResponse->json(),
            ], 500);
        }

        // Crear feeds dentro del grupo
        $feeds = ['temperatura', 'humedad', 'nivel_comida', 'nivel_agua', 'dispensador'];
        foreach ($feeds as $feed) {
            $feedResponse = Http::withHeaders([
                'X-AIO-Key' => $adafruitApiKey,
            ])->post("https://io.adafruit.com/api/v2/{$username}/feeds", [
                'name' => $groupName . '_' . $feed,
                'group_key' => $groupName,
                'description' => ucfirst($feed) . ' del comedero.',
            ]);

            if ($feedResponse->failed()) {
                return response()->json([
                    'message' => 'Grupo creado en Adafruit, pero hubo un error al crear el feed ' . $feed . '.',
                    'error' => $feedResponse->json(),
                ], 500);
            }
        }

        return response()->json([
            'message' => 'Comedero creado exitosamente con grupo y feeds en Adafruit.',
            'comedero' => $comedero,
        ], 201);
    }
    public function verComederos()
    {
        $comederos = Comedero::with('mascota')
        ->where('usuario_id', Auth::id())
        ->get();
        
        if(!$comederos){
            return response()->json(['message' => 'no existen comederos aun'], 404);
        }
        return response()->json([
            'data' => $comederos
        ]);
    }

    public function verComedero(Request $request, $id)
    {

        $comedero = Comedero::with('mascota')
        ->where('id', $id)
        ->get();

        return response()->json([
            'data' => $comedero
        ]);
    }


    
}
