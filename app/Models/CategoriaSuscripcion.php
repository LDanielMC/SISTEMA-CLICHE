<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaSuscripcion extends Model
{
    protected $table = 'categorias_suscripcion';
    protected $primaryKey = 'idCategoria';
    public $incrementing = true;
    protected $keyType = 'int';

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'idCategoria';
    }

    protected $fillable = [
        'nombre',
        'estatus',
    ];

    // Relación: Una categoría tiene muchas suscripciones
    public function suscripciones()
    {
        return $this->hasMany(Suscripcion::class, 'idCategoria', 'idCategoria');
    }
}
