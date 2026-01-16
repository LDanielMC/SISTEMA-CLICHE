<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventoParticipante extends Model
{
    protected $fillable = [
        'evento_id',
        'tipo',
        'referencia_id',
        'nombre',
        'correo',
        'confirmado',
    ];

    protected $casts = [
        'confirmado' => 'boolean',
    ];

    public function evento(): BelongsTo
    {
        return $this->belongsTo(Evento::class);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'referencia_id', 'id_cliente')
            ->where('tipo', 'cliente');
    }

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class, 'referencia_id', 'id_empleado')
            ->where('tipo', 'empleado');
    }

    public function getReferencia()
    {
        return match($this->tipo) {
            'cliente' => $this->cliente,
            'empleado' => $this->empleado,
            default => null,
        };
    }
}
