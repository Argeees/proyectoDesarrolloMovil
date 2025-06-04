<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $pet_id
 * @property int $vet_id
 * @property \Illuminate\Support\Carbon $appointment_datetime
 * @property string $motivo_consulta
 * @property string $estado
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\MedicalNote|null $medicalNote
 * @property-read \App\Models\Pet $pet
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @property-read \App\Models\User $veterinarian
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereAppointmentDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereMotivoConsulta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment wherePetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereVetId($value)
 */
	class Appointment extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $appointment_id
 * @property int $product_id
 * @property int $cantidad_vendida
 * @property string $precio_al_momento_venta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Appointment $appointment
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppointmentProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppointmentProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppointmentProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppointmentProduct whereAppointmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppointmentProduct whereCantidadVendida($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppointmentProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppointmentProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppointmentProduct wherePrecioAlMomentoVenta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppointmentProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppointmentProduct whereUpdatedAt($value)
 */
	class AppointmentProduct extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $appointment_id
 * @property string $diagnostico
 * @property string $tratamiento_sugerido
 * @property string|null $observaciones
 * @property string|null $archivo_url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Appointment $appointment
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalNote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalNote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalNote query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalNote whereAppointmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalNote whereArchivoUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalNote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalNote whereDiagnostico($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalNote whereObservaciones($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalNote whereTratamientoSugerido($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalNote whereUpdatedAt($value)
 */
	class MedicalNote extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $owner_id
 * @property string $nombre_mascota
 * @property string $especie
 * @property string|null $raza
 * @property \Illuminate\Support\Carbon|null $fecha_nacimiento
 * @property string|null $foto_url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Appointment> $appointments
 * @property-read int|null $appointments_count
 * @property-read \App\Models\User $owner
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pet query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pet whereEspecie($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pet whereFechaNacimiento($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pet whereFotoUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pet whereNombreMascota($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pet whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pet whereRaza($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pet whereUpdatedAt($value)
 */
	class Pet extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $nombre_producto
 * @property string|null $descripcion
 * @property numeric $precio_unitario
 * @property int $stock
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Appointment> $appointments
 * @property-read int|null $appointments_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereNombreProducto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product wherePrecioUnitario($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUpdatedAt($value)
 */
	class Product extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $nombre_rol
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereNombreRol($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereUpdatedAt($value)
 */
	class Role extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $role_id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Appointment> $appointmentsAsVet
 * @property-read int|null $appointments_as_vet_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pet> $petsOwned
 * @property-read int|null $pets_owned_count
 * @property-read \App\Models\Role $role
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \App\Models\UserProfile|null $userProfile
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent implements \Filament\Models\Contracts\FilamentUser {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $user_id
 * @property string $nombre_completo
 * @property string|null $telefono
 * @property string|null $direccion
 * @property string|null $foto_url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereDireccion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereFotoUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereNombreCompleto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereTelefono($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereUserId($value)
 */
	class UserProfile extends \Eloquent {}
}

