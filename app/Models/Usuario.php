<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    // ⭐ Nombre real de tu tabla
    protected $table = 'usuarios';

    // ⭐ Llave primaria real de tu tabla
    protected $primaryKey = 'id_usuario';

    // ⭐ Si tu tabla NO tiene created_at y updated_at
    public $timestamps = false;

    // ⭐ Campos que se pueden llenar masivamente
    protected $fillable = [
        'email',
        'password_hash',
        'rol',
        'creado_en',
    ];

    // ⭐ Campos que quieres ocultar cuando se convierta a JSON
    protected $hidden = [
        'password_hash',
    ];

    // ⭐ Casts de tipos
    protected $casts = [
        'creado_en' => 'datetime',
    ];

    // ⭐ MUY IMPORTANTE: decirle a Laravel cuál es el campo de password
    public function getAuthPassword()
    {
        return $this->password_hash;
    }
}
