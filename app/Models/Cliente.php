<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Cliente extends Model
{
   
    protected $table = 'clientes';
    protected $primaryKey = 'id_cliente';
    public $timestamps = false; // la tabla no tiene created_at / updated_at

    protected $fillable = [
        'empresa',
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'giro_sector',
        'telefono',
        'estatus',
        'fecha_registro',
        'fecha_baja',
        'id_usuario',
    ];

    // 🔗 Cliente pertenece a un User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    protected $casts = [
        'fecha_registro' => 'date', // Esto convierte la fecha en un objeto Carbon
        'fecha_baja' => 'date',
    ];

    
    // Relación 1-M: Un cliente puede tener múltiples datos fiscales
    public function infosFiscales()
    {
        return $this->hasMany(InfoFiscal::class, 'id_cliente', 'id_cliente');
    }

    // Método helper: Obtiene la primera info fiscal (para compatibilidad)
    public function infoFiscal()
    {
        return $this->hasOne(InfoFiscal::class, 'id_cliente', 'id_cliente');
    }

    public function cotizaciones()
    {
        return $this->hasMany(Cotizacion::class, 'id_cliente', 'id_cliente');
    }

    public function minutas()
    {
        return $this->hasMany(Minuta::class, 'id_cliente', 'id_cliente');
    }

    public function briefs()
    {
        return $this->belongsToMany(Brief::class, 'brief_cliente', 'cliente_id', 'brief_id')
                    ->withPivot('estado', 'fecha_envio', 'fecha_ultimo_recordatorio', 'google_response_id')
                    ->withTimestamps();
    }

}
