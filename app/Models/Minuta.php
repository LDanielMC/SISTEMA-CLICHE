<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Minuta extends Model
{
    protected $table = 'minutas';
    protected $primaryKey = 'id_minuta';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_cliente',
        'titulo',
        'fecha',
        'asistentes',
        'puntos_tratados',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    // Relación: Una minuta pertenece a un cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    // Relación: Una minuta tiene muchos acuerdos
    public function acuerdos()
    {
        return $this->hasMany(Acuerdo::class, 'id_minuta', 'id_minuta')->orderBy('orden', 'asc');
    }
}
