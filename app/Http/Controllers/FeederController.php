<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Mascota;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Comedero;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        $mascotas = Mascota::with('usuario_id', Auth::id())->get();

        return response()->json([
            'message' => 'Mascotas del usuario autenticado',
            'data' => $mascotas,
        ]);
    }

    public function eliminarMascota($id){
        $registro = Mascota::find($id);

        $comedero = Comedero::where('mascota_id', $id)->get();

        if($comedero){
            return response()->json(["message" => "La mascota se encuentra en un comedero."], 400);
        }

        if(!$registro){
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
            'numero_serie' => 'required|string',
        ]);

        if ($validatedData->fails()) {
            return response()->json(["validator" => $validatedData->errors()], 422);
        }

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
        

        $comedero = Comedero::create([
            'usuario_id' => Auth::id(),
            'mascota_id' => $request->mascota_id,
            'numero_serie' => $request->numero_serie,
            'estado' => 'ACTIVO',
        ]);

        DB::table('numero_serie')
        ->where('numero_serie', $request->numero_serie)
        ->update(['estado' => 'inactivo']);

        return response()->json([
            'message' => 'Comedero creado exitosamente'
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
