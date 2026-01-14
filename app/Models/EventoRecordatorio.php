<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventoRecordatorio extends Model
{
    protected $fillable = [
        'evento_id',
        'tipo_notificacion',
        'minutos_antes',
        'enviado',
        'fecha_envio',
    ];

    protected $casts = [
        'enviado' => 'boolean',
        'fecha_envio' => 'datetime',
    ];

    public function evento(): BelongsTo
    {
        return $this->belongsTo(Evento::class);
    }

    public function getFechaEnvioCalculadaAttribute()
    {
        if (!$this->evento) {
            return null;
        }

        $fechaHoraEvento = \Carbon\Carbon::parse($this->evento->fecha->format('Y-m-d') . ' ' . $this->evento->hora_inicio);
        return $fechaHoraEvento->subMinutes($this->minutos_antes);
    }

    public function scopePendientes($query)
    {
        return $query->where('enviado', false);
    }

    public function scopeParaEnviar($query)
    {
        return $query->where('enviado', false)
            ->whereHas('evento', function($q) {
                $q->where('fecha', '>=', now()->toDateString());
            });
    }
}
