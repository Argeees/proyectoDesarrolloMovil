<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $with = ['role'];
    //lista blanca de atributos que se pueden asignar masivamente
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    //estos nunca se muestran , ya que son datos sensibles
    protected $hidden = [
        'password',
        'remember_token',
    ];


    // se le pide a laravel que trate esto atributos de manera especial , como hashear la password
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    //un user pertenece a un rol
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    //un user tiene un solo perfil de usuario
    public function userProfile(): HasOne
    {
        return $this->hasOne(UserProfile::class, 'user_id');
    }

    // el user como dueño puede tener muchas mascotas
    public function petsOwned(): HasMany
    {
        return $this->hasMany(Pet::class, 'owner_id');
    }

    //relacion de que el veterinario tenga varias citas 
    public function appointmentsAsVet(): HasMany
    {
        return $this->hasMany(Appointment::class, 'vet_id');
    }


    public function canAccessPanel(Panel $panel): bool
    {
    //permitimos que los 3 roles puedan acceder
    return $this->role && in_array($this->role->nombre_rol, ['Admin', 'Veterinario', 'Dueño de Mascota']);

    }
}