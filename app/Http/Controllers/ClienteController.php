<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\User;
// IMPORTANTE: Agregamos el modelo de la bitácora
use App\Models\BitacoraCliente; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException; 

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        // --- 1. OBTENER PARÁMETROS DE LA URL ---
        $estatusFilter = $request->query('estatus', 'activo');
        
        // Parámetros de ordenación con valores por defecto
        $sortBy = $request->query('sort_by', 'fecha_registro');
        $sortDir = $request->query('sort_dir', 'desc');

        // --- 2. VALIDAR COLUMNAS PERMITIDAS PARA ORDENAR ---
        $sortableColumns = ['nombre', 'telefono', 'empresa', 'giro_sector', 'fecha_registro', 'fecha_baja'];
        if (!in_array($sortBy, $sortableColumns)) {
            $sortBy = 'fecha_registro'; 
        }

        // --- 3. CONSTRUIR LA CONSULTA ---
        $clientes = Cliente::with('user')
                        ->where('estatus', $estatusFilter)
                        ->orderBy($sortBy, $sortDir)
                        ->get();

        // --- 4. PASAR DATOS A LA VISTA ---
        return view('clientes.index', compact('clientes', 'estatusFilter', 'sortBy', 'sortDir'));
    }

    public function create()
    {
        // Usuarios que tienen rol empleado (si los necesitas para algo visual, aunque en store creas uno nuevo para el cliente)
        $usuarios = User::where('rol', 'empleado')->get();

        return view('clientes.create', compact('usuarios'));
    }

    public function store(Request $request)
    {
        // 1. VALIDACIÓN INTEGRADA (Cliente + Fiscal)
        $request->validate([
            // Datos Cliente
            'empresa'           => 'nullable|string|max:180',
            'nombre'            => 'required|string|max:120',
            'apellido_paterno'  => 'required|string|max:120',
            'apellido_materno'  => 'nullable|string|max:120',
            'giro_sector'       => 'required|string|max:150',
            'correo'            => 'required|email|max:255|unique:users,email',
            'telefono'          => 'required|string|max:30',
            'fecha_registro'    => 'required|date', // Asegúrate de enviar esto desde el form

            // Datos Fiscales (Validación condicional)
            'fiscal.rfc'             => 'nullable|required_with:fiscal.razon_social|max:20',
            'fiscal.razon_social'    => 'nullable|required_with:fiscal.rfc|max:255',
            'fiscal.regimen'         => 'nullable|max:120',
            'fiscal.telefono_fiscal' => 'nullable|max:30',
            'fiscal.correo_fiscal'   => 'nullable|email|max:255',
            'fiscal.direccion_fiscal'=> 'nullable|max:400',
        ]);

        // USAMOS UNA TRANSACCIÓN PARA QUE TODO SE GUARDE O NADA SE GUARDE
        DB::transaction(function () use ($request) {
            
            // 2) Crear el usuario asociado al cliente (Login)
            $user = User::create([
                'email'    => $request->correo,
                'rol'      => 'cliente',
                'password' => Hash::make(Str::random(16)), // Contraseña temporal
            ]);

            // 3) Crear el cliente vinculado al usuario
            // ¡IMPORTANTE! Asignamos el resultado a $cliente para usar su ID abajo
            $cliente = Cliente::create([
                'empresa'           => $request->empresa,
                'nombre'            => $request->nombre,
                'apellido_paterno'  => $request->apellido_paterno,
                'apellido_materno'  => $request->apellido_materno,
                'giro_sector'       => $request->giro_sector,
                'telefono'          => $request->telefono,
                'estatus'           => 'activo',
                'fecha_registro'    => $request->fecha_registro,
                'id_usuario'        => $user->id,
            ]);

            // 4) Guardar Info Fiscal (Solo si se llenó el RFC)
            if ($request->filled('fiscal.rfc')) {
                // Creamos la info fiscal vinculada a este cliente
                $cliente->infoFiscal()->create($request->input('fiscal'));
            }

            // 5) Enviar correo de invitación
            Password::sendResetLink(['email' => $user->email]);
        });

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente registrado y datos fiscales guardados. Se envió invitación.');
    }

    public function edit(Cliente $cliente)
    {
        // Laravel carga la relación automáticamente si la llamas en la vista, 
        // pero es buena práctica precargarla aquí.
        // $cliente->load('infoFiscal'); 
        
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        // Obtenemos el ID del usuario para la validación (ignorar su propio correo actual)
        $userId = $cliente->user->id;

        // 1) Validar los datos (Cliente + Fiscal)
        $request->validate([
            'empresa'           => 'nullable|string|max:180',
            'nombre'            => 'required|string|max:120',
            'apellido_paterno'  => 'required|string|max:120',
            'apellido_materno'  => 'nullable|string|max:120',
            'giro_sector'       => 'required|string|max:150',
            'telefono'          => 'required|string|max:30',
            
            // "unique:users,email,ID" le dice a Laravel: "Revisa que sea único, pero sáltate este ID"
            'correo'            => 'required|email|max:255|unique:users,email,'.$userId,

            // Validación fiscal en edición
            'fiscal.rfc'             => 'nullable|required_with:fiscal.razon_social|max:20',
            'fiscal.razon_social'    => 'nullable|required_with:fiscal.rfc|max:255',
            'fiscal.regimen'         => 'nullable|max:120',
            'fiscal.telefono_fiscal' => 'nullable|max:30',
            'fiscal.correo_fiscal'   => 'nullable|email|max:255',
            'fiscal.direccion_fiscal'=> 'nullable|max:400',
        ]);

        DB::transaction(function () use ($request, $cliente) {
            // 2) Actualizar datos del Cliente
            $cliente->update([
                'empresa'           => $request->empresa,
                'nombre'            => $request->nombre,
                'apellido_paterno'  => $request->apellido_paterno,
                'apellido_materno'  => $request->apellido_materno,
                'giro_sector'       => $request->giro_sector,
                'telefono'          => $request->telefono,
            ]);
            
            // 3) Actualizar el Correo (Login) y ENVIAR EMAIL SI CAMBIÓ
            $user = $cliente->user;
            
            // Detectamos si el correo del formulario ($request->correo) es diferente al de la BD
            if ($user->email !== $request->correo) {
                
                // VALIDACIÓN DE SEGURIDAD:
                // Si la vista está enviando la contraseña del admin (password_admin_confirmation),
                // la verificamos antes de permitir el cambio sensible.
                if ($request->filled('password_admin_confirmation')) {
                    if (! Hash::check($request->password_admin_confirmation, Auth::user()->password)) {
                        throw ValidationException::withMessages([
                            'correo' => 'La contraseña de administrador es incorrecta. No se pudo actualizar el correo.',
                        ]);
                    }
                }

                // Actualizamos el correo
                $user->email = $request->correo;
                $user->email_verified_at = null; // Reseteamos la verificación por seguridad
                $user->save();

                // ---> AQUÍ ESTÁ LA MAGIA: ENVÍO AUTOMÁTICO <---
                // Enviamos el link de "Restablecer contraseña" al NUEVO correo
                Password::sendResetLink(['email' => $user->email]);
            }
            
            // 4) Actualizar o Crear Info Fiscal
            if ($request->filled('fiscal.rfc')) {
                $cliente->infoFiscal()->updateOrCreate(
                    ['id_cliente' => $cliente->id_cliente], // Busca por cliente
                    $request->input('fiscal')               // Guarda los datos
                );
            }
        });

        // Verificamos si hubo cambio de correo para mandar un mensaje más específico
        $mensajeExito = 'Cliente actualizado correctamente.';
        if ($cliente->user->wasChanged('email')) { // Esto podría no funcionar fuera de la transacción si se recarga, pero el flujo lógico ya pasó.
             // Mejor usamos una bandera simple o asumimos que si llegamos aquí todo está bien.
        }

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente actualizado correctamente. Si cambiaste el correo, se envió un enlace de acceso al nuevo email.');
    }

    public function destroy(string $id)
    {
        $cliente = \App\Models\Cliente::findOrFail($id);

        // MODIFICACIÓN: Usamos una transacción para asegurar baja + bitácora
        DB::transaction(function () use ($cliente) {
            // 1. Dar de baja
            $cliente->estatus = 'inactivo'; 
            $cliente->fecha_baja = now(); 
            $cliente->save();

            // 2. GUARDAR EN BITÁCORA
            BitacoraCliente::create([
                'id_cliente' => $cliente->id_cliente,
                'accion' => 'baja', // <--- Se guarda la acción
                'id_usuario_responsable' => Auth::id(), // <--- Se guarda quién lo hizo
                'fecha_movimiento' => now(), // <--- Fecha exacta
            ]);
        });

        return redirect()->route('clientes.index')
                        ->with('success', 'Cliente dado de baja correctamente (Bitácora actualizada).');
    }

    public function search(Request $request)
    {
        $query = $request->get('query');
        $estatusFilter = $request->query('estatus', 'activo'); 

        $clientesQuery = Cliente::with('user')
                            ->where('estatus', $estatusFilter);

        if (!empty($query)) {
            $clientesQuery->where(function($q) use ($query) {
                $q->where('nombre', 'LIKE', '%' . $query . '%')
                ->orWhere('apellido_paterno', 'LIKE', '%' . $query . '%')
                ->orWhere('empresa', 'LIKE', '%' . $query . '%') 
                ->orWhere('giro_sector', 'LIKE', '%' . $query . '%')
                ->orWhere('telefono', 'LIKE', '%' . $query . '%') 
                ->orWhereHas('user', function ($userQuery) use ($query) {
                    $userQuery->where('email', 'LIKE', '%' . $query . '%');
                })
                // OPCIONAL: Si quieres buscar también por RFC o Razón Social
                ->orWhereHas('infoFiscal', function ($fiscalQuery) use ($query) {
                     $fiscalQuery->where('rfc', 'LIKE', '%' . $query . '%')
                                 ->orWhere('razon_social', 'LIKE', '%' . $query . '%');
                });
            });
        }

        $clientes = $clientesQuery->orderBy('id_cliente', 'desc')->get();

        if ($clientes->count() > 0) {
            return view('clientes._tabla_clientes', compact('clientes'))->render();
        } else {
            $colspan = $estatusFilter === 'inactivo' ? 8 : 7;
            return '<tr><td colspan="' . $colspan . '" class="px-6 py-12 text-center text-gray-500">No se encontraron clientes con ese criterio de búsqueda.</td></tr>';
        }
    }

    public function reactivar(Cliente $cliente)
    {
        if ($cliente->estatus !== 'inactivo') {
            return redirect()->route('clientes.index')
                ->with('error', 'Solo se pueden reactivar clientes con estatus de inactivo.');
            
        } else {
            // MODIFICACIÓN: Transacción para reactivación + bitácora
            DB::transaction(function () use ($cliente) {
                // 1. Reactivar
                $cliente->estatus = 'activo';
                $cliente->fecha_registro = now(); 
                $cliente->fecha_baja = null;
                $cliente->save();

                // 2. GUARDAR EN BITÁCORA
                BitacoraCliente::create([
                    'id_cliente' => $cliente->id_cliente,
                    'accion' => 'reactivacion', // <--- Se guarda la acción
                    'id_usuario_responsable' => Auth::id(), // <--- Quién lo reactivó
                    'fecha_movimiento' => now(),
                ]);
            });

            return redirect()->route('clientes.index')
                ->with('success', 'Cliente reactivado correctamente (Bitácora actualizada).');
        }
    }

    public function destroyFiscal(Request $request, Cliente $cliente)
    {
        // 1. Validar que escribieron una contraseña
        $request->validate([
            'password_confirm' => 'required|string',
        ]);

        // 2. Verificar si la contraseña del ADMIN es correcta
        if (!Hash::check($request->password_confirm, Auth::user()->password)) {
            // Si falla, regresamos con un error específico
            return back()
                ->withInput() // Mantiene los inputs abiertos
                ->withErrors(['password_confirm' => 'La contraseña es incorrecta. No se eliminó nada.']);
        }

        // 3. Si la contraseña es correcta, borramos la info fiscal
        if ($cliente->infoFiscal) {
            $cliente->infoFiscal()->delete();
        }

        return redirect()->route('clientes.edit', $cliente->id_cliente)
            ->with('success', 'Información fiscal eliminada correctamente.');
    }

    public function verificarPassword(Request $request)
    {
        $request->validate(['password' => 'required']);

        if (Hash::check($request->password, Auth::user()->password)) {
            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'error'], 401);
    }
}