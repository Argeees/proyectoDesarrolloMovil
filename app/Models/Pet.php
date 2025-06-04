<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pet extends Model
{
    use HasFactory;

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'owner_id',
        'nombre_mascota',
        'especie',
        'raza',
        'fecha_nacimiento',
        'foto_url',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    /**
     * Define la relación "pertenece a" con el modelo User (el dueño).
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Define la relación "uno a muchos" con el modelo Appointment.
     * Una mascota puede tener muchas citas.
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
}