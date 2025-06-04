<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// controladores
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PetController;

// Rutas publicas para autenticacion
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);

// Rutas protegidas ocupan el token
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('pets', PetController::class);
    //crea auto las rutas get,post,put,delete
    Route::apiResource('appointments', App\Http\Controllers\Api\AppointmentController::class);
    //se crean las rutas get,post,put,delete para las notas
        Route::post('/appointments/{appointment}/medical-notes', [App\Http\Controllers\Api\MedicalNoteController::class, 'storeForAppointment']); // <--- RUTA AÑADIDA
    Route::get('/pets/{pet}/medical-notes', [App\Http\Controllers\Api\MedicalNoteController::class, 'indexForPet']);             
    Route::get('/medical-notes/{medical_note}', [App\Http\Controllers\Api\MedicalNoteController::class, 'show']);
    Route::put('/medical-notes/{medical_note}', [App\Http\Controllers\Api\MedicalNoteController::class, 'update']);
    Route::delete('/medical-notes/{medical_note}', [App\Http\Controllers\Api\MedicalNoteController::class, 'destroy']);
    //para listar el catalogo
    Route::get('/products', [App\Http\Controllers\Api\ProductController::class, 'index']);
    //para anadir un producto a una cita
    Route::post('/appointments/{appointment}/products', [App\Http\Controllers\Api\AppointmentController::class, 'addProduct']);
    //para ver el perfil
    Route::get('/profile', [App\Http\Controllers\Api\UserProfileController::class, 'show']);
    Route::put('/profile', [App\Http\Controllers\Api\UserProfileController::class, 'update']);

});
   

