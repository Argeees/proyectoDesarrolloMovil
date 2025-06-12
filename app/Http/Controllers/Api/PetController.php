<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class PetController extends Controller
{
    //muestra una lista de mascotas del usuario autenticado
    public function index()
    {//anotacion phpdoc para que la IDE entienda que la variable $user es de tipo User
        /** @var \App\Models\User|null $user */ 
        //obtengo el usuario autenticado
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Usuario no autenticado.'], 401);
        }

        if (!method_exists($user, 'petsOwned')) {

            return response()->json(['message' => 'Error: La relación de mascotas no está configurada correctamente en el modelo User.'], 500);
        }

        $pets = $user->petsOwned()->get();

        return response()->json($pets);
  
    }

    //almacena una nueva mascota para el usuario autenticado
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
        //creo unja nueva instancia del modelo pet
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
        
    }

    //muestra una mascota especificada
    public function show(Pet $pet)
    {
        if (Auth::id() !== $pet->owner_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }
        return response()->json($pet);
        
    }

    //actualiza una mascota especificada
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
        
    }

    //elimina una mascota especificada de un user
    public function destroy(Pet $pet)
    {
        if (Auth::id() !== $pet->owner_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $pet->delete();
        //devolvemois un mensaje de exito
        return response()->json(['message' => 'Mascota eliminada exitosamente']);
    }
}