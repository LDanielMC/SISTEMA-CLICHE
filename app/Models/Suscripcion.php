<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class Suscripcion extends Model
{
    protected $table = 'suscripciones';
    protected $primaryKey = 'idSuscripcion';
    public $incrementing = true;
    protected $keyType = 'int';

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'idSuscripcion';
    }

    protected $fillable = [
        'idCategoria',
        'nombre_servicio',
        'fecha_inicio',
        'costo',
        'periodicidad',
        'fecha_vencimiento',
        'dias_recordatorio',
        'nivel_uso',
        'observaciones',
        'estatus',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_vencimiento' => 'date',
        'dias_recordatorio' => 'array',
        'costo' => 'decimal:2',
    ];

    // Relación: Una suscripción pertenece a una categoría
    public function categoria()
    {
        return $this->belongsTo(CategoriaSuscripcion::class, 'idCategoria', 'idCategoria');
    }

    // Relación: Una suscripción tiene muchas renovaciones
    public function renovaciones()
    {
        return $this->hasMany(SuscripcionRenovacion::class, 'idSuscripcion', 'idSuscripcion')
                    ->orderBy('fecha_renovacion', 'desc');
    }

    // Accessor: Calcular días restantes dinámicamente
    protected function diasRestantes(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->fecha_vencimiento) {
                    return null;
                }
                $hoy = Carbon::today();
                $vencimiento = Carbon::parse($this->fecha_vencimiento);
                return $hoy->diffInDays($vencimiento, false); // false para obtener número negativo si está vencida
            }
        );
    }

    // Accessor: Badge de estado (Por vencer / Vencida / OK)
    protected function estadoBadge(): Attribute
    {
        return Attribute::make(
            get: function () {
                $dias = $this->dias_restantes;
                
                if ($dias === null) {
                    return ['texto' => 'Sin fecha', 'clase' => 'bg-gray-100 text-gray-600'];
                }
                
                if ($dias < 0) {
                    return ['texto' => 'Vencida', 'clase' => 'bg-red-100 text-red-700'];
                }
                
                if ($dias <= 10) {
                    return ['texto' => 'Por vencer', 'clase' => 'bg-yellow-100 text-yellow-700'];
                }
                
                return ['texto' => 'OK', 'clase' => 'bg-green-100 text-green-700'];
            }
        );
    }

    // Método helper: Calcular fecha de vencimiento según periodicidad
    public static function calcularFechaVencimiento($fechaInicio, $periodicidad)
    {
        $fecha = Carbon::parse($fechaInicio);
        
        if ($periodicidad === 'mensual') {
            return $fecha->addMonth()->format('Y-m-d');
        }
        
        if ($periodicidad === 'anual') {
            return $fecha->addYear()->format('Y-m-d');
        }
        
        return $fechaInicio;
    }
}
