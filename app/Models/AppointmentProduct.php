<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
//modelo pivot del producto de una cita//osea tabla intermedia
class AppointmentProduct extends Model 
{
    use HasFactory;
    //se le dice como se llama la tabla
    protected $table = 'appointment_product';
    //para saber cuando se vendio el producto
    public $timestamps = true;
    //incluye las llaves foraneas  y las columnas extras
    protected $fillable = [
        'appointment_id',
        'product_id',
        'cantidad_vendida',
        'precio_al_momento_venta',
    ];
    //cada registro de venta pertenece a una cita
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }
    //cada registro de venta pertenece a un producto
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}