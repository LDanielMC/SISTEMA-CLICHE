<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Acuerdo extends Model
{
    protected $table = 'acuerdos';
    protected $primaryKey = 'id_acuerdo';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_minuta',
        'acuerdo',
        'responsable',
        'orden',
        'estatus',
        'fecha_limite',
    ];

    protected $casts = [
        'fecha_limite' => 'date',
    ];

    // Relación: Un acuerdo pertenece a una minuta
    public function minuta()
    {
        return $this->belongsTo(Minuta::class, 'id_minuta', 'id_minuta');
    }
}
