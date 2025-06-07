<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
// Controlador para obtener veterinarios en base a l rol del usuario
{
    public function getVeterinarians()
    {
        $veterinarians = User::whereHas('role', function ($query) {
            $query->where('nombre_rol', 'Veterinario');
        })->select('id', 'name')->get(); 

        return response()->json($veterinarians);
    }
}
