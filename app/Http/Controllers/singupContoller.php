<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use App\Mail\ConfirmarCuenta;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class singupContoller extends Controller
{
    public function register(Request $request)
    {
        $data = $request->all();

        $validate = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'max:40'],
        ]);

        if ($validate->fails()) {
            return response()->json(["validator" => $validate->errors()], 422);
        }

        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->rol = "invitado";
        $user->save();

        $url = URL::temporarySignedRoute('activar', now()->addMinutes(5), ['user' => $user->id]);

        Mail::to($user->email)->send(new ConfirmarCuenta($user, $url));

        return response()->json([
            'message' => 'Se envio un correo a su cuenta para completar el registro, tiene 5 minutos para activar su cuenta',
        ]);
    }

    public function login(Request $request)
    {
        $data = $request->all();

        $validate = Validator::make($data, [
            'email' => ['required','string' ,'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'max:40'],
        ]);

        
        if ($validate->fails()) {
            return response()->json(["validator" => $validate->errors()], 422);
        }

        $user = User::where('email', $data['email'])->first();
        
        if (!$user) {
            return response()->json([
                'message' => 'Credenciales inválidas.'
            ], 401);
        }
        
        if (!Hash::check($data['password'], $user->password)) {
            return response()->json([
                'message' => 'Credenciales inválidas.'
            ], 401);
        }

        

        if ($user->rol === 'invitado') {
            return response()->json([
                'message' => 'Acceso denegado. Por favor, contacte con un administrador para obtener acceso.'
            ], 403);
        }

        if ($user->estado === false) {
            return response()->json([
                'message' => 'Aún no activa su cuenta',
            ], 403);
        }

       

        $token = $user->createToken('Access Token')->plainTextToken;

        return response()->json([
            'message' => 'Login exitoso',
            'token' => $token
        ], 200);
    }
}
