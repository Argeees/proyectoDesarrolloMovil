<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
// Si vas a usar PetResource más adelante, descomenta la siguiente línea:
// use App\Http\Resources\PetResource; 
// Y también: use Illuminate\Support\Facades\Log; // Si usas la línea de Log

class PetController extends Controller
{
    /**
     * Muestra una lista de las mascotas del usuario autenticado.
     */
    public function index()
    {
        /** @var \App\Models\User|null $user */ // <--- AÑADE ESTA LÍNEA
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Usuario no autenticado.'], 401);
        }

        if (!method_exists($user, 'petsOwned')) {
            // Si quieres más detalles en tu log para depurar (opcional):
            // Log::error('El método petsOwned no existe en User.', ['user_class' => get_class($user)]);
            return response()->json(['message' => 'Error: La relación de mascotas no está configurada correctamente en el modelo User.'], 500);
        }

        $pets = $user->petsOwned()->get();

        return response()->json($pets);
        // return PetResource::collection($pets); // Para usar con API Resources
    }

    /**
     * Almacena una nueva mascota para el usuario autenticado.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'nombre_mascota' => 'required|string|max:255',
            'especie' => 'required|string|max:255',
            'raza' => 'nullable|string|max:255',
            'fecha_nacimiento' => 'nullable|date',
            'foto_url' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $pet = new Pet();
        $pet->owner_id = $user->id;
        $pet->nombre_mascota = $request->nombre_mascota;
        $pet->especie = $request->especie;
        $pet->raza = $request->raza;
        $pet->fecha_nacimiento = $request->fecha_nacimiento;
        $pet->foto_url = $request->foto_url;
        $pet->save();

        return response()->json([
            'message' => 'Mascota registrada exitosamente',
            'pet' => $pet
        ], 201);
        // return new PetResource($pet); // Para usar con API Resources
    }

    /**
     * Muestra la mascota especificada (que pertenezca al usuario autenticado).
     */
    public function show(Pet $pet)
    {
        if (Auth::id() !== $pet->owner_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }
        return response()->json($pet);
        // return new PetResource($pet); // Para usar con API Resources
    }

    /**
     * Actualiza la mascota especificada en la base de datos (que pertenezca al usuario autenticado).
     */
    public function update(Request $request, Pet $pet)
    {
        if (Auth::id() !== $pet->owner_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'nombre_mascota' => 'sometimes|required|string|max:255',
            'especie' => 'sometimes|required|string|max:255',
            'raza' => 'nullable|string|max:255',
            'fecha_nacimiento' => 'nullable|date',
            'foto_url' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        $pet->update($request->all());

        return response()->json([
            'message' => 'Mascota actualizada exitosamente',
            'pet' => $pet
        ]);
        // return new PetResource($pet); // Para usar con API Resources
    }

    /**
     * Elimina la mascota especificada de la base de datos (que pertenezca al usuario autenticado).
     */
    public function destroy(Pet $pet)
    {
        if (Auth::id() !== $pet->owner_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $pet->delete();

        return response()->json(['message' => 'Mascota eliminada exitosamente']);
    }
}