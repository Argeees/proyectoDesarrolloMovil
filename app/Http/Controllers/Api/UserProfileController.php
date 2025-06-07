<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage; 

class UserProfileController extends Controller
{
    public function show(Request $request)
    {

        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Usuario no autenticado.'], 401);
        }

        $profileData = null;
        if ($user->userProfile) {
            $profileData = [
                'nombre_completo' => $user->userProfile->nombre_completo,
                'telefono' => $user->userProfile->telefono,
                'direccion' => $user->userProfile->direccion,
                'foto_url' => $user->userProfile->foto_url ? Storage::disk('public')->url($user->userProfile->foto_url) : null,
            ];
        }

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role ? $user->role->nombre_rol : null,
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
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Campo para el archivo de foto
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $userDataToUpdate = [];
        if ($request->has('name')) {
            $userDataToUpdate['name'] = $request->name;
        }
        if ($request->has('email')) {
            $userDataToUpdate['email'] = $request->email;
        }
        if (count($userDataToUpdate) > 0) {
            $user->update($userDataToUpdate);
        }

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

        $userProfile = $user->userProfile()->firstOrNew(['user_id' => $user->id]);

        if ($request->hasFile('photo')) {
            if ($userProfile->foto_url) {
                Storage::disk('public')->delete($userProfile->foto_url);
            }
            $path = $request->file('photo')->store('profile-photos', 'public');
            $profileDataToUpdate['foto_url'] = $path;
        }

        // Asignar los datos y guardar el perfil
        
        $userProfile->user_id = $user->id; 
        foreach($profileDataToUpdate as $key => $value){
            $userProfile->{$key} = $value;
        }
        $userProfile->save();

        $user->refresh()->load('userProfile', 'role');

        $reloadedProfileData = null;
        if ($user->userProfile) {
             $reloadedProfileData = [
                'nombre_completo' => $user->userProfile->nombre_completo,
                'telefono' => $user->userProfile->telefono,
                'direccion' => $user->userProfile->direccion,
                'foto_url' => $user->userProfile->foto_url ? Storage::disk('public')->url($user->userProfile->foto_url) : null,
            ];
        }

        return response()->json([
            'message' => 'Perfil actualizado exitosamente',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role ? $user->role->nombre_rol : null,
                'profile' => $reloadedProfileData,
            ]
        ]);
    }
}