<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\UserProfile; 

class UserProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Usuario no autenticado.'], 401);
        }

        $profileData = null;
        if ($user->userProfile) { // Accede a la relación userProfile directamente
            $profileData = [
                'nombre_completo' => $user->userProfile->nombre_completo,
                'telefono' => $user->userProfile->telefono,
                'direccion' => $user->userProfile->direccion,
                'foto_url' => $user->userProfile->foto_url,
            ];
        }

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role ? $user->role->nombre_rol : null, // Accede a la relación role
            'profile' => $profileData,
            'email_verified_at' => $user->email_verified_at,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Usuario no autenticado.'], 401);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes',
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'nombre_completo' => 'sometimes|required|string|max:255',
            'telefono' => 'nullable|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'foto_url' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('email')) {
            $user->email = $request->email;
        }
        $user->save();

        $profileDataToUpdate = [];
        if ($request->has('nombre_completo')) {
            $profileDataToUpdate['nombre_completo'] = $request->nombre_completo;
        }
        if ($request->has('telefono')) {
            $profileDataToUpdate['telefono'] = $request->telefono;
        }
        if ($request->has('direccion')) {
            $profileDataToUpdate['direccion'] = $request->direccion;
        }
        if ($request->has('foto_url')) {
            $profileDataToUpdate['foto_url'] = $request->foto_url;
        }

        if (count($profileDataToUpdate) > 0) {
            $user->userProfile()->updateOrCreate(
                ['user_id' => $user->id],
                $profileDataToUpdate
            );
        }
        
        // Para devolver la respuesta, volvemos a construirla como en show()
        $updatedProfileData = null;
        if ($user->userProfile()->exists()) { // Verificamos si el perfil existe después de updateOrCreate
            $reloadedUserProfile = $user->userProfile()->first(); // Recargamos el perfil
             $updatedProfileData = [
                'nombre_completo' => $reloadedUserProfile->nombre_completo,
                'telefono' => $reloadedUserProfile->telefono,
                'direccion' => $reloadedUserProfile->direccion,
                'foto_url' => $reloadedUserProfile->foto_url,
            ];
        }


        return response()->json([
            'message' => 'Perfil actualizado exitosamente',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role ? $user->role->nombre_rol : null,
                'profile' => $updatedProfileData,
            ]
        ]);
    }
}