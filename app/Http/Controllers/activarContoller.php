<?php

namespace App\Http\Controllers;

Use App\Models\User;
Use Illuminate\Support\Facades\Mail;
Use Illuminate\Support\Facades\Validator;
Use Illuminate\Support\Facades\URL;
Use App\Mail\AdminConfirmacion;
Use App\Mail\ConfirmarCuenta;
use Illuminate\Http\Request;

class activarContoller extends Controller
{
    public function activar(int $userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        if ($user->is_active) {
            return response()->json(['message' => 'Su cuenta ya se encuentra activada'], 200);
        }

        $user->estado = true;
        $user->rol = 'user';
        $user->save();

        $admin = User::where('rol', 'admin')->first();

        if (!$admin) {
            return response()->json(['message' => 'No hay administradores registrados'], 404);
        }

        Mail::to($admin->email)->send(new AdminConfirmacion($user));

        return response()->json(['message' => 'Cuenta activada'], 200);
    }

    public function reactivar(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|string|email|max:255',
        ]);

        $user = User::where('email', $validatedData['email'])->first();

        if (!$user) {
            return response()->json(['message' => 'No hay ningún usuario con este correo'], 404);
        }

        if ($user->estado) {
            return response()->json(['message' => 'La cuenta ya está activada'], 409);
        }

        $url = URL::temporarySignedRoute('activar', now()->addMinutes(5), ['user' => $user->id]);
        Mail::to($user->email)->send(new ConfirmarCuenta($user, $url));

        return response()->json([
        'message' => 'Se envió un correo a su cuenta para completar el registro. Tiene 5 minutos para activar su cuenta.',
        ], 200);
    }

    public function prueba(){
        return response()->json([
            'message' => 'Si jala padrino',
        ]);
    }
}
