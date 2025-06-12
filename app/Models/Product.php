<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
//modelo para los productos
class Product extends Model
{
    use HasFactory;

    //los campos que se pueden llenar
    protected $fillable = [
        'nombre_producto',
        'descripcion',
        'precio_unitario',
        'stock',
    ];

    //para que se trate de manera especial precio
    protected $casts = [
        'precio_unitario' => 'decimal:2',
    ];

    //un producto tiene muchas citas (calse pivote) para la relacion de muchos a muchos 
    public function appointments(): BelongsToMany
    {
        return $this->belongsToMany(Appointment::class, 'appointment_product') // Nombre de la tabla pivote
                   
                    ->withPivot('cantidad_vendida', 'precio_al_momento_venta') 
                    ->withTimestamps(); // Se le dice a laravel que la tabla de pivote tenga las columnas created_at y updated_at
    }
}