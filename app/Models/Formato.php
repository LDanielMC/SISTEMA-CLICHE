<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formato extends Model
{
    use HasFactory;

    protected $table = 'formatos';

    protected $fillable = [
        'nombre',
        'especificaciones',
        'activo'
    ];

    /**
     * Relación: Un formato se usa en muchas publicaciones.
     */
    public function publicaciones()
    {
        return $this->hasMany(Publicacion::class, 'formato_id');
    }
}
