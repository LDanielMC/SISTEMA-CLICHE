<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CotizacionDetalle extends Model
{
    protected $table = 'cotizacion_detalle';
    protected $primaryKey = 'id_detalle';

    protected $fillable = [
        'id_cotizacion',
        'titulo',
        'cantidad',
        'descripcion',
        'precio_unitario',
        'iva',
        'precio_total',
        'orden'
    ];

    // Relación inversa: Un detalle pertenece a una cotización
    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class, 'id_cotizacion', 'id_cotizacion');
    }
}
