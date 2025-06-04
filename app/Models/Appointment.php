<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Appointment extends Model
{
    use HasFactory;

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pet_id',
        'vet_id',
        'appointment_datetime',
        'motivo_consulta',
        'estado',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'appointment_datetime' => 'datetime',
    ];

    /**
     * Define la relación "pertenece a" con el modelo Pet.
     */
    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }

    /**
     * Define la relación "pertenece a" con el modelo User (el veterinario).
     */
    public function veterinarian(): BelongsTo // Renombramos la relación para claridad
    {
        return $this->belongsTo(User::class, 'vet_id');
    }

    /**
     * Define la relación "uno a uno" con el modelo MedicalNote.
     * Una cita tiene una nota médica.
     */
    public function medicalNote(): HasOne
    {
        return $this->hasOne(MedicalNote::class);
    }

    /**
     * Define la relación "muchos a muchos" con el modelo Product.
     * Una cita puede tener muchos productos vendidos.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'appointment_product') 
                    ->withPivot('cantidad_vendida', 'precio_al_momento_venta') 
                    ->withTimestamps(); 
    }
}