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
    ];

    /**
     * Relación con los Clientes asignados (Muchos a Muchos).
     */
    public function clientes()
    {
        return $this->belongsToMany(Cliente::class, 'brief_cliente', 'brief_id', 'cliente_id')
                    ->withPivot('estado', 'fecha_envio', 'fecha_ultimo_recordatorio')
                    ->withTimestamps();
    }
}
