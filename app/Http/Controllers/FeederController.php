<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Mascota;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Comedero;
use Illuminate\Database\Eloquent\SoftDeletes;
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

        // m falta
        return response()->json([
            'message' => 'Mascotas del usuario autenticado',
            'data' => $mascotas,
        ]);
    }

    public function eliminarMascota($id)
    {
        $registro = Mascota::find($id);

        $comedero = Comedero::where('mascota_id', $id)->exists();

        if ($comedero) {
            return response()->json(["message" => "La mascota se encuentra en un comedero. "], 400);
        }

        if (!$registro) {
            return response()->json(["message" => "Mascota no encontrada."], 404);
        }

        $registro->delete();

        return response()->json(["message" => "Mascota eliminada correctamente"], 200);
    }

    public function crearComedero(Request $request)
    {
        $data = $request->all();

        $validatedData = Validator::make($data, [
            'mascota_id' => 'required|exists:mascotas,id',
        ]);

        if ($validatedData->fails()) {
            return response()->json(["validator" => $validatedData->errors()], 422);
        }


        $comedero = Comedero::create([
            'usuario_id' => Auth::id(),
            'mascota_id' => $request->mascota_id,
            'estado' => 'ACTIVO',
        ]);


        $adafruitApiKey = env('ADAFRUIT_IO_KEY');
        $username = env('ADAFRUIT_IO_USERNAME');
        
        $response = Http::withHeaders([
            'X-AIO-Key' => $adafruitApiKey,
        ])->post("https://io.adafruit.com/api/v2/{$username}/groups", [
            'name' => "$comedero->id",
            'description' => 'Grupo asociado al comedero',
        ]);

        if ($response->failed()) {
            return response()->json([
                'message' => 'Error al crear el grupo en Adafruit',
                'error' => $response->json(),
                'credenciales' => $adafruitApiKey . $username
            ], 500);
        }

        return response()->json([
            'message' => 'Comedero creado exitosamente',
            'group' => $response->json(),
        ], 201);
    }

    public function eliminarComedero($id){

        $registro = Comedero::find($id);

        $adafruitApiKey = env('ADAFRUIT_IO_KEY');
        $username = env('ADAFRUIT_IO_USERNAME');

        $response = Http::withHeaders([
            'X-AIO-Key' => $adafruitApiKey,
        ])->delete("https://io.adafruit.com/api/v2/{$username}/groups/{$id}");

        if ($response->failed()) {
            return response()->json([
                'message' => 'Error al eliminar el grupo en Adafruit',
                'error' => $response->json(),
                'credenciales' => $adafruitApiKey . $username
            ], 500);
        }

        
        if (!$registro) {
            return response()->json(["message" => "Comedero no encontrado."], 404);
        }

        $registro->delete();

        return response()->json(["message" => "Comedero eliminado correctamente"], 200);
    }

    public function verComederos()
    {
        $comederos = Comedero::with('mascota')
            ->where('usuario_id', Auth::id())
            ->get();

        if (!$comederos) {
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
