<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function updateUser(Request $request)
{
    $data = $request->all();
    $user = $request->user();

    $validatedData = Validator::make($data, [
        'name' => 'required|string|max:255',
        'old_password' => 'nullable|string|min:8|max:40',
        'new_password' => 'nullable|string|min:8|confirmed|max:40',
        'new_password_confirmation' => 'nullable|string|min:8|same:new_password|max:40'
    ]);

    if ($validatedData->fails()) {
        return response()->json(["validator" => $validatedData->errors()], 422);
    }

    if ($request->filled('old_password') && !$request->filled('new_password') && !$request->filled('new_password_confirmation') ){
        return response()->json(["validator" => "Escribe tu nueva contraseña y confirmala"], 422);
    }

    if ($request->filled('old_password') && !Hash::check($request->old_password, $user->password)) {
        return response()->json(["validator" => "La contraseña antigua no coincide"], 422);
    }

    if ($request->filled('new_password') && $request->new_password == $request->new_password_confirmation) {
        $user->password = Hash::make($request->new_password);
    }

    $user->name = $request->name;

    $user->save();

    return response()->json([
        'message' => 'Datos actualizados correctamente.'
    ], 200);
}


    
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        
        return response()->json(['message' => 'Sesión cerrada correctamente.'], 200);
    }

    public function me(){
        return response()->json(Auth::user());
    }
    
}
