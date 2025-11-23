<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InfoFiscal extends Model
{
    protected $table = 'info_fiscal';
    protected $primaryKey = 'id_fiscal';
    
    // Importante: Mapeamos los timestamps de Laravel a tus columnas
    const CREATED_AT = 'fecha_registro';
    const UPDATED_AT = 'fecha_actualiza';

    protected $fillable = [
        'id_cliente',
        'rfc',
        'razon_social',
        'direccion_fiscal',
        'regimen',
        'telefono_fiscal',
        'correo_fiscal'
    ];

    // Relación inversa
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }
}