<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfile extends Model
{
    use HasFactory;

    /**
     * La llave primaria asociada con la tabla.
     *
     * @var string
     */
    protected $primaryKey = 'user_id'; // Porque no usamos 'id'

    /**
     * Indica si la llave primaria es autoincremental.
     *
     * @var bool
     */
    public $incrementing = false; // Porque user_id viene de la tabla users

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id', // Necesario si lo asignas directamente
        'nombre_completo',
        'telefono',
        'direccion',
        'foto_url',
    ];

    /**
     * Define la relación "pertenece a" con el modelo User.
     * Un perfil pertenece a un usuario.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}