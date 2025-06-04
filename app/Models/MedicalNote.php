<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalNote extends Model
{
    use HasFactory;

    /**
     * La llave primaria asociada con la tabla.
     *
     * @var string
     */
    protected $primaryKey = 'appointment_id';
   

    /**
     * Indica si la llave primaria es autoincremental.
     *
     * @var bool
     */
    public $incrementing = false; 

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'appointment_id', // Necesario si lo asignas directamente
        'diagnostico',
        'tratamiento_sugerido',
        'observaciones',
        'archivo_url',
    ];

    /**
     * Define la relación "pertenece a" con el modelo Appointment.
     * Una nota médica pertenece a una cita.
     */
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }
}