<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuscripcionRenovacion extends Model
{
    protected $table = 'suscripcion_renovaciones';
    protected $primaryKey = 'idRenovacion';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'idSuscripcion',
        'fecha_renovacion',
        'costo_ciclo',
        'fecha_vencimiento_anterior',
        'fecha_vencimiento_nueva',
        'observaciones',
    ];

    protected $casts = [
        'fecha_renovacion' => 'date',
        'fecha_vencimiento_anterior' => 'date',
        'fecha_vencimiento_nueva' => 'date',
        'costo_ciclo' => 'decimal:2',
    ];

    // Relación: Una renovación pertenece a una suscripción
    public function suscripcion()
    {
        return $this->belongsTo(Suscripcion::class, 'idSuscripcion', 'idSuscripcion');
    }
}
