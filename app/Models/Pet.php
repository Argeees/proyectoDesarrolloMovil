<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
//modelo para las mascotas
class Pet extends Model
{
    use HasFactory;

    //los atributos que se pueden asignar en masa
    protected $fillable = [
        'owner_id',//la llave foranea que dice quien es el duenio
        'nombre_mascota',
        'especie',
        'raza',
        'fecha_nacimiento',
        'foto_url',
    ];

    //los atributos que deben ser convertidos en fechas
    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    //una mascota pertenece a un duenio
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    //una misma mascota puede tener muchas citas
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
}