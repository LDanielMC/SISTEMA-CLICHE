<?php
use App\Models\Cliente;

$clientes = Cliente::all();

echo "ID | Empresa | Nombre | Estatus | Fecha Baja\n";
echo "------------------------------------------------\n";
foreach ($clientes as $c) {
    echo "{$c->id_cliente} | {$c->empresa} | {$c->nombre} {$c->apellido_paterno} | {$c->estatus} | {$c->fecha_baja}\n";
}
