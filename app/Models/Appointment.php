<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
//modelo para las citas
class Appointment extends Model
{
    use HasFactory;

    //atributos que se pueden rellenar

    protected $fillable = [
        'pet_id',
        'vet_id',
        'appointment_datetime',
        'motivo_consulta',
        'estado',
    ];

    //formato de la fecha
    protected $casts = [
        'appointment_datetime' => 'datetime',
    ];

    //una cita pertenece a una mascota
    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }

    //una cita pertenece a un usuario en este cao el veterinario
    public function veterinarian(): BelongsTo // Renombramos la relación para claridad
    {
        return $this->belongsTo(User::class, 'vet_id');
    }

    //relacion uno a uno , una cita tiene una nota medica
    public function medicalNote(): HasOne
    {
        return $this->hasOne(MedicalNote::class);
    }

    //relacion muchos a muchos , una cita puede tener muchos productos
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'appointment_product') 
                    ->withPivot('cantidad_vendida', 'precio_al_momento_venta') 
                    ->withTimestamps(); 
    }
}