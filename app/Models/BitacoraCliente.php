<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BitacoraCliente extends Model
{
    use HasFactory;

    protected $table = 'bitacora_clientes';

    protected $fillable = [
        'id_cliente',
        'accion',
        'id_usuario_responsable',
        'fecha_movimiento',
    ];

    protected $casts = [
        'fecha_movimiento' => 'datetime',
    ];
}