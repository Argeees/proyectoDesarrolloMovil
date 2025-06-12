<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;//herramienta para restablecer contraseñas
//aqui manejamos la logica de autenticación para la api
class AuthController extends Controller
{
    public function register(Request $request)
    {//validfamos los datos que mando la api
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',//no email repetido
            'password' => 'required|string|min:8|confirmed',
            'role_name' => 'required|string|exists:roles,nombre_rol',
        ]);
        //si falla devolvemos error
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        // se busca el id del rol que nos mando x su nombre
        $role = Role::where('nombre_rol', $request->role_name)->first();
        // si el rol no se encuentra devolvemos error
        if (!$role) {
            return response()->json(['message' => 'Rol no válido'], 400);
        }
        //creamos el usuario en la bd
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $role->id,
        ]);
        //se crea un token de api para este usuario, este es el que guardara para que jale junto con la app d android
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Usuario registrado exitosamente',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ], 201);
    }
    //inicio de sesion con user existente
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        //hace una comparacion de credenciales , busca el usuario y compara la contraseña hasheada
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Credenciales incorrectas'
            ], 401);
        }
        //si fue correcto se crea el token
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

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => $status]);
        }

        return response()->json(['message' => $status], 400); 
    }//metodo para restablecer la contraseña
    public function resetPassword(Request $request)
    {//se validan los datos
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
            'email' => 'required|string|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        //funcion de laravel para verificar el token
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                //aqui se actualiza la contraseña
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();

            }
        );
        // se devuelve el mensaje ya se correcto o no
        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['message' => __($status)]);
        }

        return response()->json(['message' => __($status)], 400);
    }

}





