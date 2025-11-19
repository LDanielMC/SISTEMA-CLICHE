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
}
