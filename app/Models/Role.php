<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
//modelo para los roles
class Role extends Model
{
    use HasFactory;


    protected $fillable = [
        'nombre_rol',
    ];

// relacion de un rol tiene muchos users
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
