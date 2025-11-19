<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

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
        // Para evitar errores o inyecciones, solo permitimos ordenar por columnas conocidas.
        $sortableColumns = ['nombre', 'telefono', 'empresa', 'giro_sector', 'fecha_registro', 'fecha_baja'];
        if (!in_array($sortBy, $sortableColumns)) {
            $sortBy = 'fecha_registro'; // Columna por defecto si se intenta una no permitida
        }

        // --- 3. CONSTRUIR LA CONSULTA ---
        $clientes = Cliente::with('user')
                        ->where('estatus', $estatusFilter)
                        // Aplicamos la ordenación dinámica
                        ->orderBy($sortBy, $sortDir)
                        ->get();

        // --- 4. PASAR DATOS A LA VISTA ---
        // Pasamos también los parámetros de ordenación para construir los enlaces
        return view('clientes.index', compact('clientes', 'estatusFilter', 'sortBy', 'sortDir'));
    }

    

    public function create()
    {
        // Usuarios que tienen rol empleado
        $usuarios = User::where('rol', 'empleado')->get();

        return view('clientes.create', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'empresa'           => 'nullable|string|max:180',
            'nombre'            => 'required|string|max:120',
            'apellido_paterno'  => 'required|string|max:120',
            'apellido_materno'  => 'nullable|string|max:120',
            'giro_sector'       => 'required|string|max:150',
            'correo'            => 'required|email|max:255|unique:users,email', // Valida contra la tabla Users
            'telefono'          => 'required|string|max:30',
        ]);

        // 1) Crear el usuario asociado al empleado
        $user = User::create([
            'email'    => $request->correo,
            'rol'      => 'cliente',
            // contraseña temporal random (la va a cambiar con el link de invitación)
            'password' => Hash::make(Str::random(16)),
        ]);

        // 2) Crear el empleado vinculado al usuario
        Cliente::create([
            'empresa'           => $request->empresa,
            'nombre'            => $request->nombre,
            'apellido_paterno'  => $request->apellido_paterno,
            'apellido_materno'  => $request->apellido_materno,
            'giro_sector'       => $request->giro_sector,
            'telefono'          => $request->telefono,
            'estatus'           => 'activo',
            'fecha_registro'     => $request->fecha_registro,
            'id_usuario'        => $user->id,
        ]);

        // 3) Enviar correo de "crear contraseña" (usa el sistema de reset de Laravel)
        Password::sendResetLink([
            'email' => $user->email,
        ]);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente registrado. Se envió una invitación al correo para crear su contraseña.');
    }

    public function edit(Cliente $cliente)
    {
        // Carga la vista de edición y le pasa el empleado que se quiere modificar.
        // La vista 'empleados.edit' usará los datos de $empleado para rellenar el formulario.
        // Nota: Tu archivo se llama 'edit.balde.php', Laravel buscará 'edit.blade.php'. 
        // Asegúrate de que el nombre del archivo sea correcto.
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        // 1) Validar los datos del formulario de edición
        $request->validate([
            'empresa'           => 'nullable|string|max:180',
            'nombre'            => 'required|string|max:120',
            'apellido_paterno'  => 'required|string|max:120',
            'apellido_materno'  => 'nullable|string|max:120',
            'giro_sector'       => 'required|string|max:150',
            'telefono'          => 'required|string|max:30',
           
        ]);

        // 2) Actualizar los datos del modelo Empleado
        $cliente->update([
            'empresa'           => $request->empresa,
            'nombre'            => $request->nombre,
            'apellido_paterno'  => $request->apellido_paterno,
            'apellido_materno'  => $request->apellido_materno,
            'giro_sector'       => $request->giro_sector,
            'telefono'          => $request->telefono,
        ]);


        // 4) Redirigir a la lista con un mensaje de éxito
        return redirect()->route('clientes.index')
            ->with('success', 'Cliente actualizado correctamente.');
    }

    
    public function destroy(string $id)
    {
        // 1. Buscamos al empleado
        $cliente = \App\Models\Cliente::findOrFail($id);

        // 2. CAMBIAMOS EL ESTATUS (Soft Delete)
        $cliente->estatus = 'inactivo'; 
        
        // 3. Registramos la fecha de baja
        $cliente->fecha_baja = now(); 

        // 4. Guardamos los cambios
        $cliente->save();

        // 5. Redirigimos con un mensaje de éxito
        return redirect()->route('clientes.index')
                        ->with('success', 'Cliente dado de baja correctamente.');
    }

    /**
     * Maneja la búsqueda AJAX de empleados.
     */
    public function search(Request $request)
    {
        // 1. Obtiene el término de búsqueda de la petición
        $query = $request->get('query');
        $estatusFilter = $request->query('estatus', 'activo'); // Default 'activo'

        // 2. Inicia la consulta, usando la misma lógica del'index'
        $clientesQuery = Cliente::with('user')
                            ->where('estatus', $estatusFilter);

        // 3. Si hay un término de búsqueda (no está vacío), aplica los filtros
        if (!empty($query)) {
            $clientesQuery->where(function($q) use ($query) {
                $q->where('nombre', 'LIKE', '%' . $query . '%')
                ->orWhere('apellido_paterno', 'LIKE', '%' . $query . '%')
                ->orWhere('empresa', 'LIKE', '%' . $query . '%') 
                ->orWhere('giro_sector', 'LIKE', '%' . $query . '%')
                ->orWhere('telefono', 'LIKE', '%' . $query . '%') 
                ->orWhereHas('user', function ($userQuery) use ($query) {
                    $userQuery->where('email', 'LIKE', '%' . $query . '%');
                });
            });
        }

        // 4. Ejecuta la consulta (ya sea filtrada o no)
        $clientes = $clientesQuery->orderBy('id_cliente', 'desc')->get();

        // 5. Devuelve la vista parcial con los resultados
        if ($clientes->count() > 0) {
            // Devuelve el HTML renderizado del archivo parcial
            return view('clientes._tabla_clientes', compact('clientes'))->render();
        } else {
            // El colspan ahora depende de si estamos en activos (7) o inactivos (8)
            $colspan = $estatusFilter === 'inactivo' ? 8 : 7;
            // Devuelve un mensaje de "no encontrado"
            return '<tr><td colspan="' . $colspan . '" class="px-6 py-12 text-center text-gray-500">No se encontraron clientes con ese criterio de búsqueda.</td></tr>';
        }
    }

    public function reactivar(Cliente $cliente)
    {
        if ($cliente->estatus !== 'inactivo') {
            return redirect()->route('clientes.index')
                ->with('error', 'Solo se pueden reactivar clientes con estatus de inactivo.');
            
        }else{
        // Cambia el estatus a 'activo' y limpia la fecha de baja
        $cliente->estatus = 'activo';
        $cliente->fecha_registro = now();
        $cliente->fecha_baja = null;
        $cliente->save();

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente reactivado correctamente.');
        }
    }



}
