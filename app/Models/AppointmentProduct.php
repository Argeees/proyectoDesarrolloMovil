<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppointmentProduct extends Model 
{
    use HasFactory;

    protected $table = 'appointment_product';

    public $timestamps = true;

    protected $fillable = [
        'appointment_id',
        'product_id',
        'cantidad_vendida',
        'precio_al_momento_venta',
    ];

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}