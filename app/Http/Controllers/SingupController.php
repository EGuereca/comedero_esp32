<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Mascota;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use App\Mail\ConfirmarCuenta;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SingupController extends Controller
{
    public function register(Request $request)
{
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $user = User::create([
        'name' => $validatedData['name'],
        'email' => $validatedData['email'],
        'password' => bcrypt($validatedData['password']),
    ]);

    return response()->json(['message' => 'Usuario registrado exitosamente'], 201);
}


    public function login(Request $request)
    {
        $data = $request->all();

        $validate = Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'max:40'],
        ]);

        if ($validate->fails()) {
            return response()->json(["validator" => $validate->errors()], 422);
        }

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json([
                'message' => 'Credenciales inválidas.',
            ], 401);
        }

        if ($user->rol === 'invitado') {
            return response()->json([
                'message' => 'Acceso denegado. Por favor, contacte con un administrador para obtener acceso.',
            ], 403);
        }

        if (!$user->estado) {
            return response()->json([
                'message' => 'Aún no activa su cuenta.',
            ], 403);
        }

        $token = $user->createToken('Access Token')->plainTextToken;

        return response()->json([
            'message' => 'Login exitoso.',
            'token' => $token,
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente.',
        ]);
    }
}
