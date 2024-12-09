<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FeederController extends Controller
{
    public function obtenerComederos(Request $request)
    {
        $usuario = $request->user();
        $nombreMascota = $request->query('nombre_mascota'); 

        if (!$nombreMascota) {
            return response()->json(['error' => 'Debe proporcionar el nombre de la mascota.'], 400);
        }

        $mascota = $usuario->mascotas()->where('nombre', $nombreMascota)->first();

        if (!$mascota) {
            return response()->json(['error' => 'Mascota no encontrada.'], 404);
        }

        $comederos = $mascota->comederos()->get();

        return response()->json($comederos, 200);
    }

    public function crearComedero(Request $request)
    {
        $usuario = $request->user(); 
        $data = $request->all();

        $validatedData = Validator::make($data, [
            'nombre' => 'required|string|max:255',
            'animal' => 'required|in:perro,gato,otro',
        ]);

        if ($validatedData->fails()) {
            return response()->json(["validator" => $validatedData->errors()], 422);
        }

        $mascota = $usuario->mascotas()->create($validatedData);

        $comedero = $mascota->comederos()->create([
            'usuario_id' => $usuario->id,
            'estado' => 'ACTIVO',
        ]);

        return response()->json([
            'mascota' => $mascota,
            'comedero' => $comedero,
        ], 201);
    }
}
