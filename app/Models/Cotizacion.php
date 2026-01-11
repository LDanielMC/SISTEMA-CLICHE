<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    protected $table = 'cotizaciones';
    protected $primaryKey = 'id_cotizacion';

    protected $fillable = [
        'id_cliente',
        'titulo_cotizacion',   
        'texto_introduccion',
        'fecha',
        'vencimiento_dias',
        'subtotal',
        'iva_total',
        'porcentaje_isr', 
        'retencion_isr', 
        'total',
        'notas',
        'estatus'
    ];

    // Castear fecha a objeto Carbon para poder formatearla fácil después
    protected $casts = [
        'fecha' => 'date',
        'subtotal' => 'decimal:2',
        'iva_total' => 'decimal:2',
        'total' => 'decimal:2',
        'porcentaje_isr' => 'decimal:2',
        'retencion_isr' => 'decimal:2',
    ];

    // Relación: Una cotización pertenece a un cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    // Relación: Una cotización tiene muchos detalles (partidas)
    public function detalles()
    {
        return $this->hasMany(CotizacionDetalle::class, 'id_cotizacion', 'id_cotizacion')->orderBy('orden', 'asc');
    }
}