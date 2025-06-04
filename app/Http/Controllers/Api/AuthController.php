<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role; // Para asignar el rol
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth; // Para validar los datos

class AuthController extends Controller
{
    /**
     * Registra un nuevo usuario.
     */
    public function register(Request $request)
    {
        // 1. Validar los datos que envía la app móvil
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // 'confirmed' buscará un campo 'password_confirmation'
            'role_name' => 'required|string|exists:roles,nombre_rol', // Asumimos que la app envía el nombre del rol
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422); // 422 es error de validación
        }

        // 2. Buscar el ID del rol
        $role = Role::where('nombre_rol', $request->role_name)->first();
        if (!$role) {
            // Esto no debería pasar si 'exists:roles,nombre_rol' funciona bien, pero es una doble verificación
            return response()->json(['message' => 'Rol no válido'], 400);
        }

        // 3. Crear el usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $role->id,
        ]);

        // 4. Crear un token para el nuevo usuario (para que inicie sesión automáticamente)
        $token = $user->createToken('auth_token')->plainTextToken;

        // 5. Devolver la respuesta
        return response()->json([
            'message' => 'Usuario registrado exitosamente',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ], 201); // 201 significa "Creado"
    }


    /**
     * Inicia sesión para un usuario existente.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Credenciales incorrectas'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Inicio de sesión exitoso',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }
//cierra la sesion de un usuario existente
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada exitosamente'
        ]);
    }
//metodo para la recuperacion de la contraseña
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|exists:users,email',
        ]);

        if ($validator->fails()) {

            return response()->json($validator->errors(), 422);
        }



        return response()->json([
            'message' => 'Si tu correo electrónico está en nuestros registros, recibirás un enlace para restablecer tu contraseña en breve.'
        ]);
    }      

}

