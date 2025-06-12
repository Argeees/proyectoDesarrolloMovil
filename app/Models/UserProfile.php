<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
//clase modelo para el perfil de usuario , userProfile me refiero a los datos que este tiene como su telefono,direccion,etc
class UserProfile extends Model
{
    use HasFactory;

    //se le dice a laravel que esta llave primaria no se llama id , sino user_id //para la relacion 1,1
    protected $primaryKey = 'user_id'; // Porque no usamos 'id'

    //no incrementa la llave primaria 
    public $incrementing = false; // Porque user_id viene de la tabla users

    //medida de seguridad , solo se permite la creacion de los campos que se definen en el $fillable
    protected $fillable = [
        'user_id', //id del usr al que pertenece el perfil
        'nombre_completo',
        'telefono',
        'direccion',
        'foto_url',
    ];

    //aqui definimos la relacion uno a uno con el modelo user , osea un perfil pertenece a un user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}