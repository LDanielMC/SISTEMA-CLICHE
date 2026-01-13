<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publicacion extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'publicaciones';

    // Llave primaria personalizada
    protected $primaryKey = 'idPublicacion';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'cliente_id',
        'lote_calendario', 
        'plataforma_id',
        'formato_id',
        'fecha',
        'copy',
        'arte',
        'estatus',
    ];

    // Conversión de tipos de datos automática
    protected $casts = [
        'fecha' => 'date',
    ];

    /* |--------------------------------------------------------------------------
     | Relaciones
     |-------------------------------------------------------------------------- */

    /**
     * Una publicación pertenece a un Cliente.
     * IMPORTANTE: tu PK del cliente es id_cliente, por eso se especifica.
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id_cliente');
    }

    /**
     * Una publicación pertenece a una Plataforma.
     */
    public function plataforma()
    {
        return $this->belongsTo(Plataforma::class, 'plataforma_id');
    }

    /**
     * Una publicación pertenece a un Formato.
     */
    public function formato()
    {
        return $this->belongsTo(Formato::class, 'formato_id');
    }
}
