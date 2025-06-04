<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre_producto',
        'descripcion',
        'precio_unitario',
        'stock',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'precio_unitario' => 'decimal:2',
    ];

    /**
     * Define la relación "muchos a muchos" con el modelo Appointment.
     * Un producto puede estar en muchas citas (ventas).
     */
    public function appointments(): BelongsToMany
    {
        return $this->belongsToMany(Appointment::class, 'appointment_product') // Nombre de la tabla pivote
                    //->using(AppointmentProduct::class) // Opcional: si quieres usar tu modelo pivote personalizado
                    ->withPivot('cantidad_vendida', 'precio_al_momento_venta') // Columnas extra
                    ->withTimestamps(); // Si tu tabla pivote tiene timestamps
    }
}