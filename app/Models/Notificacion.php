<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    protected $table = 'notificaciones';

    protected $fillable = [
        'user_id',
        'asignacion_tarea_id',
        'tipo',
        'titulo',
        'mensaje',
        'leida',
    ];

    protected $casts = [
        'leida' => 'boolean',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function asignacionTarea()
    {
        return $this->belongsTo(AsignacionTarea::class);
    }

    public function marcarComoLeida()
    {
        $this->update(['leida' => true]);
    }

    public function getIconoAttribute()
    {
        return match($this->tipo) {
            'tarea_asignada' => '📋',
            'tarea_en_proceso' => '⚙️',
            'tarea_terminada' => '✅',
            'tarea_evaluada_completa' => '🎉',
            'tarea_evaluada_parcial' => '⚠️',
            'tarea_evaluada_incompleta' => '❌',
            'tarea_evaluada' => '📊',
            'recordatorio_evento' => '🔔',
            default => '🔔',
        };
    }

    public function getColorAttribute()
    {
        return match($this->tipo) {
            'tarea_asignada' => 'blue',
            'tarea_en_proceso' => 'yellow',
            'tarea_terminada' => 'green',
            'tarea_evaluada_completa' => 'green',
            'tarea_evaluada_parcial' => 'yellow',
            'tarea_evaluada_incompleta' => 'red',
            'tarea_evaluada' => 'blue',
            'recordatorio_evento' => 'purple',
            default => 'gray',
        };
    }
}
