<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Evento extends Model
{
    protected $fillable = [
        'titulo',
        'cliente_id',
        'creado_por',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'lugar',
        'notas',
        'color',
        'recurrencia',
        'recurrencia_config',
        'recurrencia_hasta',
        'google_event_id',
        'sincronizado_google',
        'ultima_sincronizacion',
    ];

    protected $casts = [
        'fecha' => 'date',
        'hora_inicio' => 'datetime:H:i',
        'hora_fin' => 'datetime:H:i',
        'recurrencia_config' => 'array',
        'recurrencia_hasta' => 'date',
        'sincronizado_google' => 'boolean',
        'ultima_sincronizacion' => 'datetime',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id_cliente');
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    public function participantes(): HasMany
    {
        return $this->hasMany(EventoParticipante::class);
    }

    public function recordatorios(): HasMany
    {
        return $this->hasMany(EventoRecordatorio::class);
    }

    public function getFechaHoraInicioAttribute()
    {
        return $this->fecha->format('Y-m-d') . ' ' . $this->hora_inicio;
    }

    public function getFechaHoraFinAttribute()
    {
        return $this->fecha->format('Y-m-d') . ' ' . $this->hora_fin;
    }

    public function scopeProximos($query)
    {
        return $query->where('fecha', '>=', now()->toDateString())
            ->orderBy('fecha', 'asc')
            ->orderBy('hora_inicio', 'asc');
    }

    public function scopeEntreFechas($query, $inicio, $fin)
    {
        return $query->whereBetween('fecha', [$inicio, $fin])
            ->orderBy('fecha', 'asc')
            ->orderBy('hora_inicio', 'asc');
    }
}
