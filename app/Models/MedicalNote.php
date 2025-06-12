<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalNote extends Model
{
    use HasFactory;

    //llave primaria con otro nombre para que laravel no la incremente
    protected $primaryKey = 'appointment_id';
   

    //llave no incrementable por que el id de una nota médica es el id de la cita
    public $incrementing = false; 

    //atributos que se pueden asignar
    protected $fillable = [
        'appointment_id', // Necesario si lo asignas directamente
        'diagnostico',
        'tratamiento_sugerido',
        'observaciones',
        'archivo_url',
    ];

    //una nota médica pertenece a una cita
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }
}