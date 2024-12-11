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
            // Renderiza la vista 'CuentaActivada'
            return view('CuentaVerificada');
        }

        $user->estado = true;
        $user->rol = 'user';
        $user->save();

        $admin = User::where('rol', 'admin')->first();

        if (!$admin) {
            return response()->json(['message' => 'No hay administradores registrados'], 404);
        }

        Mail::to($admin->email)->send(new AdminConfirmacion($user));

        return view('CuentaActivada');
    }

    public function reactivar(Request $request)
    {

        $data = $request->all();

        $validate = Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);

        if ($validate->fails()) {
            return response()->json(["validator" => $validate->errors()], 422);
        }

        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            return response()->json(['message' => 'No hay ningún usuario con este correo'], 404);
        }

        if ($user->is_active) {
            // Renderiza la vista 'CuentaActivada'
            $view = view('CuentaVerificada')->render(); // Asegúrate de que la vista exista en 'resources/views'
    
            // Retorna JSON con el mensaje y la vista
            return response()->json([
                'message' => 'Su cuenta ya se encuentra activada',
                'html' => $view, // Contenido HTML de la vista
            ], 200);
        }

        $url = URL::temporarySignedRoute('activar', now()->addMinutes(5), ['user' => $user->id]);
        Mail::to($user->email)->send(new ConfirmarCuenta($user, $url));

        $view = view('CuentaActivada')->render(); // Crea esta vista si aún no la tienes
    
        // Retorna JSON con el mensaje de éxito y la vista
        return response()->json([
            'message' => 'Cuenta activada con éxito',
            'html' => $view, // Contenido HTML de la vista
        ], 200);
    }

    public function prueba(){
        return response()->json([
            'message' => 'Si jala padrino',
        ]);
    }
}
