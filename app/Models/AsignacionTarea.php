<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AsignacionTarea extends Model
{
    protected $table = 'asignaciones_tareas';

    protected $fillable = [
        'tarea_id',
        'empleado_id',
        'prioridad',
        'fecha_limite',
        'estado_empleado',
        'estado_admin',
        'evidencia_path',
        'fecha_entrega',
        'notas_admin',
    ];

    protected $casts = [
        'fecha_limite' => 'date',
        'fecha_entrega' => 'datetime',
    ];

    public function tarea()
    {
        return $this->belongsTo(Tarea::class, 'tarea_id');
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id', 'id_empleado');
    }

    public function estaVencida()
    {
        return Carbon::now()->isAfter($this->fecha_limite) && 
               $this->estado_empleado !== 'terminada' && 
               $this->estado_admin !== 'completa';
    }

    public function getPrioridadColorAttribute()
    {
        return match($this->prioridad) {
            'baja' => 'bg-gray-100 text-gray-800',
            'media' => 'bg-blue-100 text-blue-800',
            'alta' => 'bg-orange-100 text-orange-800',
            'urgente' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getPrioridadTextoAttribute()
    {
        return match($this->prioridad) {
            'baja' => 'Baja',
            'media' => 'Media',
            'alta' => 'Alta',
            'urgente' => 'Urgente',
            default => 'Media',
        };
    }
}
