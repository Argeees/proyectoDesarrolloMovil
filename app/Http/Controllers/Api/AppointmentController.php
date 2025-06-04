<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Pet;
use App\Models\User; // Necesario para la verificación del veterinario si se implementa
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = Appointment::query();

        if ($user->role->nombre_rol === 'Dueño de Mascota') {
            $petIds = $user->petsOwned()->pluck('id')->all();
            $query->whereIn('pet_id', $petIds);
        } elseif ($user->role->nombre_rol === 'Veterinario') {
            $query->where('vet_id', $user->id);
        }
        
        $appointments = $query->with(['pet:id,nombre_mascota', 'veterinarian:id,name'])
                                ->orderBy('appointment_datetime', 'desc')
                                ->get();

        return response()->json($appointments);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'pet_id' => 'required|exists:pets,id',
            'vet_id' => 'required|exists:users,id',
            'appointment_datetime' => 'required|date|after_or_equal:now',
            'motivo_consulta' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $pet = Pet::find($request->pet_id);
        if ($user->role->nombre_rol === 'Dueño de Mascota' && (!$pet || $pet->owner_id !== $user->id)) {
            return response()->json(['message' => 'Mascota no válida o no pertenece al usuario.'], 403);
        }

        $appointment = Appointment::create([
            'pet_id' => $request->pet_id,
            'vet_id' => $request->vet_id,
            'appointment_datetime' => $request->appointment_datetime,
            'motivo_consulta' => $request->motivo_consulta,
            'estado' => 'Programada',
        ]);

        return response()->json([
            'message' => 'Cita registrada exitosamente',
            'appointment' => $appointment
        ], 201);
    }

    public function show(Appointment $appointment)
    {
        $user = Auth::user();
        $appointment->load(['pet:id,nombre_mascota', 'veterinarian:id,name']);

        if ($user->role->nombre_rol === 'Dueño de Mascota' && $appointment->pet->owner_id !== $user->id) {
            return response()->json(['message' => 'No autorizado'], 403);
        } elseif ($user->role->nombre_rol === 'Veterinario' && $appointment->vet_id !== $user->id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return response()->json($appointment);
    }

    public function update(Request $request, Appointment $appointment)
    {
        $user = Auth::user();

        if ($user->role->nombre_rol === 'Dueño de Mascota' && $appointment->pet->owner_id !== $user->id) {
            return response()->json(['message' => 'No autorizado'], 403);
        } elseif ($user->role->nombre_rol === 'Veterinario' && $appointment->vet_id !== $user->id) {
             return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'appointment_datetime' => 'sometimes|required|date|after_or_equal:now',
            'motivo_consulta' => 'sometimes|required|string|max:255',
            'estado' => 'sometimes|required|string|in:Programada,Completada,Cancelada',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $appointment->update($request->only(['appointment_datetime', 'motivo_consulta', 'estado']));

        return response()->json([
            'message' => 'Cita actualizada exitosamente',
            'appointment' => $appointment
        ]);
    }

    public function destroy(Appointment $appointment)
    {
        $user = Auth::user();

        if ($user->role->nombre_rol === 'Dueño de Mascota' && $appointment->pet->owner_id !== $user->id) {
            return response()->json(['message' => 'No autorizado'], 403);
        } elseif ($user->role->nombre_rol === 'Veterinario' && $appointment->vet_id !== $user->id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }
        
        $appointment->delete();

        return response()->json(['message' => 'Cita eliminada exitosamente']);
    }
}