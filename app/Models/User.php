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

    /**
     * Atributos asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    /**
     * Atributos que deben ocultarse durante la serialización.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Atributos que deben ser convertidos a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Obtiene el rol del usuario.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Obtiene el perfil del usuario.
     */
    public function userProfile(): HasOne
    {
        return $this->hasOne(UserProfile::class, 'user_id');
    }

    /**
     * Obtiene las mascotas que le pertenecen al usuario.
     */
    public function petsOwned(): HasMany
    {
        return $this->hasMany(Pet::class, 'owner_id');
    }

    /**
     * Obtiene las citas del usuario (si es veterinario).
     */
    public function appointmentsAsVet(): HasMany
    {
        return $this->hasMany(Appointment::class, 'vet_id');
    }

    /**
     * Determina si el usuario puede acceder al panel de Filament.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // TODO: Implementar la lógica de acceso real basada en el rol del usuario.
        // Por ejemplo: if ($this->role) { return in_array($this->role->nombre_rol, ['Administrador', 'Veterinario']); } return false;
        return true; // Temporal: permite el acceso a todos los usuarios autenticados.
    }
}