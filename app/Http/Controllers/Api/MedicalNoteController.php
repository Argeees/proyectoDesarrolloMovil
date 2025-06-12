<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MedicalNote;
use App\Models\Appointment;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;//manejar archivos
use Illuminate\Support\Facades\Validator;
//toda la logica de las notas medicas
class MedicalNoteController extends Controller
{//crea y guarda una  nueva nota medica para una cita en especifico
    public function storeForAppointment(Request $request, Appointment $appointment)
    {// se obtinen el user que hace la peticion
        $user = Auth::user();
        //solo un vet y que esta asignado puedo crear notas
        if (!$user || $user->role->nombre_rol !== 'Veterinario' || $appointment->vet_id !== $user->id) {
            return response()->json(['message' => 'No autorizado para añadir notas a esta cita.'], 403);
        }
        //solo una nota x cita
        if (MedicalNote::where('appointment_id', $appointment->id)->exists()) {
            return response()->json(['message' => 'Ya existe una nota médica para esta cita.'], 409);
        }
        //validaciones que nos manda la app
        $validator = Validator::make($request->all(), [
            'diagnostico' => 'required|string',
            'tratamiento_sugerido' => 'required|string',
            'observaciones' => 'nullable|string',
            'archivo' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        // se preparan los datos
        $medicalNoteData = [
            'appointment_id' => $appointment->id,
            'diagnostico' => $request->diagnostico,
            'tratamiento_sugerido' => $request->tratamiento_sugerido,
            'observaciones' => $request->observaciones,
            'archivo_url' => null,
        ];
        // si se subio un archivo (solo se puede desde el panel xd)
        if ($request->hasFile('archivo')) {
            $path = $request->file('archivo')->store('medical_notes_files', 'public');
            $medicalNoteData['archivo_url'] = $path;
        }
        //creamos la nota medica en la bd
        $medicalNote = MedicalNote::create($medicalNoteData);

        if ($medicalNote->archivo_url) {
            $medicalNote->archivo_url = Storage::disk('public')->url($medicalNote->archivo_url);
        }

        return response()->json([
            'message' => 'Nota médica creada exitosamente',
            'medical_note' => $medicalNote
        ], 201);
    }
    //muestra todo el historial de notas medicas de una mascota
    public function indexForPet(Pet $pet)
    {
        $user = Auth::user();

        if (!$user || ($user->role->nombre_rol === 'Dueño de Mascota' && $pet->owner_id !== $user->id)) {
            if ($user->role->nombre_rol !== 'Veterinario' && $user->role->nombre_rol !== 'Administrador') { 
                 return response()->json(['message' => 'No autorizado para ver este historial.'], 403);
            }
        }
        
        $appointmentIds = $pet->appointments()->pluck('id')->all();
        $medicalNotes = MedicalNote::whereIn('appointment_id', $appointmentIds)
                                    ->with('appointment:id,appointment_datetime')
                                    ->orderByDesc(
                                        Appointment::select('appointment_datetime')
                                            ->whereColumn('id', 'medical_notes.appointment_id')
                                    )
                                    ->get();
        
        $medicalNotes->each(function ($note) {
            if ($note->archivo_url) {
                $note->archivo_url = Storage::disk('public')->url($note->archivo_url);
            }
        });

        return response()->json($medicalNotes);
    }
    //muestra los detalle de una nota
    public function show(MedicalNote $medical_note)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['message' => 'No autenticado.'], 401);
        }

        $medical_note->load(['appointment.pet', 'appointment.veterinarian']);
        $appointment = $medical_note->appointment;

        if (!$appointment || !$appointment->pet) {
            return response()->json(['message' => 'Datos de cita o mascota no encontrados.'], 404);
        }
        
        $isOwner = $user->role->nombre_rol === 'Dueño de Mascota' && $appointment->pet->owner_id === $user->id;
        $isAssignedVet = $user->role->nombre_rol === 'Veterinario' && $appointment->vet_id === $user->id;
        $isAdmin = $user->role->nombre_rol === 'Administrador'; // Asumimos que Admin puede ver todo

        if (!$isOwner && !$isAssignedVet && !$isAdmin) {
            return response()->json(['message' => 'No autorizado para ver esta nota.'], 403);
        }
        
        if ($medical_note->archivo_url) {
            $medical_note->archivo_url = Storage::disk('public')->url($medical_note->archivo_url);
        }

        return response()->json($medical_note);
    }
    //actualizar medical note
    public function update(Request $request, MedicalNote $medical_note)
    {
        $user = Auth::user();

        if (!$user || !$medical_note->appointment || $user->role->nombre_rol !== 'Veterinario' || $medical_note->appointment->vet_id !== $user->id) {
            return response()->json(['message' => 'No autorizado para actualizar esta nota.'], 403);
        }
        //validaciones
        $validator = Validator::make($request->all(), [
            'diagnostico' => 'sometimes|required|string',
            'tratamiento_sugerido' => 'sometimes|required|string',
            'observaciones' => 'nullable|string',
            'archivo' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $updateData = $request->only(['diagnostico', 'tratamiento_sugerido', 'observaciones']);

        if ($request->hasFile('archivo')) {
            if ($medical_note->archivo_url) {
                Storage::disk('public')->delete($medical_note->archivo_url);
            }
            $path = $request->file('archivo')->store('medical_notes_files', 'public');
            $updateData['archivo_url'] = $path;
        }

        $medical_note->update($updateData);
        
        if ($medical_note->archivo_url) {
            $medical_note->archivo_url = Storage::disk('public')->url($medical_note->archivo_url);
        }
       
        return response()->json([
            'message' => 'Nota médica actualizada exitosamente',
            'medical_note' => $medical_note->load(['appointment:id,appointment_datetime', 'appointment.pet:id,nombre_mascota'])
        ]);
    }
    //eliminar medical note
    public function destroy(MedicalNote $medical_note)
    {
        $user = Auth::user();
        $isAssignedVet = $user && $medical_note->appointment && $user->role->nombre_rol === 'Veterinario' && $medical_note->appointment->vet_id === $user->id;
        $isAdmin = $user && $user->role->nombre_rol === 'Administrador';

        if (!$isAssignedVet && !$isAdmin) {
             return response()->json(['message' => 'No autorizado para eliminar esta nota.'], 403);
        }

        if ($medical_note->archivo_url) {
            Storage::disk('public')->delete($medical_note->archivo_url);
        }
        $medical_note->delete();

        return response()->json(['message' => 'Nota médica eliminada exitosamente']);
    }
}