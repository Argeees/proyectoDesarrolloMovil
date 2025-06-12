<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Http\Request;//representa la petición HTTP que llega desde3 la app
use Illuminate\Support\Facades\Auth;//ayuda a saber que usuario hizo la petición
use Illuminate\Support\Facades\Validator;//para vlidar los datos qe llegan
//este controladsor maneja las citas a traves de la api
class AppointmentController extends Controller
{//muestra una lista de citas , segun el rol
    public function index()
    {//obtengo el usuario autenticado
        $user = Auth::user();
        $query = Appointment::query();
        //si es dueno de mascota, solo muestra las citas de sus mascotas
        if ($user->role->nombre_rol === 'Dueño de Mascota') {
            //obtengo los ids de las mascotas del dueno
            $petIds = $user->petsOwned()->pluck('id')->all();
            $query->whereIn('pet_id', $petIds);
            //si es veterinario, solo muestra las citas que tiene asignadas
        } elseif ($user->role->nombre_rol === 'Veterinario') {
            $query->where('vet_id', $user->id);
        }
        //si el rol es admin no se aplican filtros
        //ejecutamso la consulta
        $appointments = $query->with(['pet:id,nombre_mascota', 'veterinarian:id,name'])
                                ->orderBy('appointment_datetime', 'desc')//se ordenan las citas por fecha descendente
                                ->get();

        return response()->json($appointments);
    }
    //guarda una nueva cita en la bd
    public function store(Request $request)
    {//obtengo el usuario autenticado
        $user = Auth::user();
        //se validan los datos que vienen en la peticin
        $validator = Validator::make($request->all(), [
            'pet_id' => 'required|exists:pets,id',
            'vet_id' => 'required|exists:users,id',
            'appointment_datetime' => 'required|date|after_or_equal:now',//debe de ser una fecha valida
            'motivo_consulta' => 'required|string|max:255',
        ]);
        // si la validacion falla, se devuelve un error
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        //si el rol es dueno de mascota y la mascota no pertenece al dueno//verifico permisos
        $pet = Pet::find($request->pet_id);
        if ($user->role->nombre_rol === 'Dueño de Mascota' && (!$pet || $pet->owner_id !== $user->id)) {
            return response()->json(['message' => 'Mascota no válida o no pertenece al usuario.'], 403);
        }

        //si rodo esta biej , creo la cita
        $appointment = Appointment::create([
            'pet_id' => $request->pet_id,
            'vet_id' => $request->vet_id,
            'appointment_datetime' => $request->appointment_datetime,
            'motivo_consulta' => $request->motivo_consulta,
            'estado' => 'Programada',
        ]);
        // se devuelve respuesta con exito
        return response()->json([
            'message' => 'Cita registrada exitosamente',
            'appointment' => $appointment
        ], 201);
    }
    // aqui se muestran los detalles de una cita en especifico

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
    // aqui se actualizan las citas
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
        //s e actualiza solo con los datos permitidos
        $appointment->update($request->only(['appointment_datetime', 'motivo_consulta', 'estado']));

        return response()->json([
            'message' => 'Cita actualizada exitosamente',
            'appointment' => $appointment
        ]);
    }
    //elimina una cita
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

    //relacion m,m de cita y producto , anañade un producto vendido a una cita especifica
    public function addProduct(Request $request, Appointment $appointment)
    {
        $user = Auth::user();

        // solo el vet asignado o un dueño de mascota puede agregar productos
        $isAssignedVet = $user->role->nombre_rol === 'Veterinario' && $appointment->vet_id === $user->id;
        $isPetOwner = $user->role->nombre_rol === 'Dueño de Mascota' && $appointment->pet->owner_id === $user->id;

        if (!$isAssignedVet && !$isPetOwner) {
            return response()->json(['message' => 'No autorizado para añadir productos a esta cita.'], 403);
        }
        //validamos los datos del producto a ana|adir
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'cantidad_vendida' => 'required|integer|min:1',
            'precio_al_momento_venta' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        // attach oara crear el registro en la tabla intermedia//pivote// 
        $appointment->products()->attach($request->product_id, [
            'cantidad_vendida' => $request->cantidad_vendida,
            'precio_al_momento_venta' => $request->precio_al_momento_venta,
        ]);

        return response()->json([
            'message' => 'Producto agregado a la cita exitosamente.',
            'appointment' => $appointment->load('products')
        ], 200);
    }
    //obtiene las citas programadas
        public function getScheduled()
    {
        $user = Auth::user();
        
        // consulta para obtener las citas programadas
        $query = Appointment::query()->where('estado', 'Programada'); 

        if ($user->role->nombre_rol === 'Dueño de Mascota') {
            $petIds = $user->petsOwned()->pluck('id')->all();
            $query->whereIn('pet_id', $petIds);
        } elseif ($user->role->nombre_rol === 'Veterinario') {
            $query->where('vet_id', $user->id);
        }

        $appointments = $query->with(['pet:id,nombre_mascota', 'veterinarian:id,name'])
                              ->orderBy('appointment_datetime', 'asc') // Las ordenamos de la más próxima a la más lejana.
                              ->get();

        return response()->json($appointments);
    }
}