<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plataforma extends Model
{
    use HasFactory;

    // Nombre explícito de la tabla (opcional si sigues convención, pero mejor asegurar)
    protected $table = 'plataformas';

    protected $fillable = [
        'nombre',
        'icono',
        'activo'
    ];

    /**
     * Relación: Una plataforma tiene muchas publicaciones.
     */
    public function publicaciones()
    {
        return $this->hasMany(Publicacion::class, 'plataforma_id');
    }
}
