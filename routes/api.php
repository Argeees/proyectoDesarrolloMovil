<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// Controladores
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PetController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\MedicalNoteController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserProfileController;

// Rutas públicas para autenticación
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset'); // <-- MOVIDA AQUÍ, FUERA DEL GRUPO PROTEGIDO

// Rutas protegidas (necesitan token)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('pets', PetController::class);

    
    // ruta  para obtener solo las citas programadas 
    Route::get('/appointments/scheduled', [AppointmentController::class, 'getScheduled']);
    
    
    
    Route::apiResource('appointments', AppointmentController::class);

    // Rutas para Notas Médicas
    Route::post('/appointments/{appointment}/medical-notes', [MedicalNoteController::class, 'storeForAppointment']);
    Route::get('/pets/{pet}/medical-notes', [MedicalNoteController::class, 'indexForPet']);
    Route::get('/medical-notes/{medical_note}', [MedicalNoteController::class, 'show']);
    Route::put('/medical-notes/{medical_note}', [MedicalNoteController::class, 'update']);
    Route::delete('/medical-notes/{medical_note}', [MedicalNoteController::class, 'destroy']);

    // Rutas para Productos y Ventas
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/appointments/{appointment}/products', [AppointmentController::class, 'addProduct']);
    
    // Rutas para Perfil de Usuario
    Route::get('/profile', [UserProfileController::class, 'show']);
    Route::put('/profile', [UserProfileController::class, 'update']);

    //ruta para obtener la lista de veterinarios
    Route::get('/veterinarians', [App\Http\Controllers\Api\UserController::class, 'getVeterinarians']);

});
   

