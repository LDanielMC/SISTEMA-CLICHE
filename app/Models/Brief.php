<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cliente;

class Brief extends Model
{
    use HasFactory;

    protected $fillable = [
        'google_form_id',
        'titulo',
        'descripcion',
        'form_url',
        'id_cliente',
        'estado',
        'fecha_envio',
        'fecha_ultimo_recordatorio',
    ];

    protected $casts = [
        'fecha_envio' => 'datetime',
        'fecha_ultimo_recordatorio' => 'datetime',
    ];

    /**
     * Relación con el Cliente asignado.
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }
}
