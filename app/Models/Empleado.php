<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Empleado extends Model
{
   
    protected $table = 'empleados';
    protected $primaryKey = 'id_empleado';
    public $timestamps = false; // la tabla no tiene created_at / updated_at

    protected $fillable = [
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'telefono',
        'puesto',
        'fecha_ingreso',
        'estatus',
        'fecha_baja',
        'id_usuario',
    ];

    // 🔗 Empleado pertenece a un User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    /**
     * Relación con las tareas asignadas
     */
    public function asignaciones()
    {
        return $this->hasMany(AsignacionTarea::class, 'empleado_id', 'id_empleado');
    }

    protected $casts = [
        'fecha_ingreso' => 'datetime', // Esto convierte la fecha en un objeto Carbon
        'fecha_baja' => 'date',
    ];
}
