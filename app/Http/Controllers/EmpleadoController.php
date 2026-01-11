<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB; // <--- IMPORTANTE: Necesario para las transacciones
use Illuminate\Support\Facades\Auth; // <--- Agregado para Auth::user()
use Illuminate\Validation\ValidationException; // <--- Agregado para excepciones de validación

class EmpleadoController extends Controller
{
    public function index(Request $request)
    {
        // --- 1. OBTENER PARÁMETROS DE LA URL ---
        $estatusFilter = $request->query('estatus', 'activo');

        // Parámetros de ordenación con valores por defecto
        $sortBy = $request->query('sort_by', 'fecha_ingreso');
        $sortDir = $request->query('sort_dir', 'desc');

        // --- 2. VALIDAR COLUMNAS PERMITIDAS PARA ORDENAR ---
        $sortableColumns = ['nombre', 'telefono', 'puesto', 'fecha_ingreso', 'fecha_baja'];
        if (!in_array($sortBy, $sortableColumns)) {
            $sortBy = 'fecha_ingreso'; // Columna por defecto si se intenta una no permitida
        }

        // --- 3. CONSTRUIR LA CONSULTA ---
        $empleados = Empleado::with('user')
                        ->where('estatus', $estatusFilter)
                        // Aplicamos la ordenación dinámica
                        ->orderBy($sortBy, $sortDir)
                        ->get();

        // --- 4. PASAR DATOS A LA VISTA ---
        // Pasamos también los parámetros de ordenación para construir los enlaces
        return view('empleados.index', compact('empleados', 'estatusFilter', 'sortBy', 'sortDir'));
    }

    

    public function create()
    {
        // Usuarios que tienen rol empleado
        $usuarios = User::where('rol', 'empleado')->get();

        return view('empleados.create', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'            => 'required|string|max:120',
            'apellido_paterno'  => 'required|string|max:120',
            'apellido_materno'  => 'nullable|string|max:120',
            'correo_contacto'   => 'required|email|max:255|unique:users,email',
            'telefono'          => 'required|string|max:30',
            'puesto'            => 'required|string|max:120',
            'fecha_ingreso'     => 'required|date',
        ]);

        // 1) Crear el usuario asociado al empleado
        $user = User::create([
            'email'    => $request->correo_contacto,
            'rol'      => 'empleado',
            // contraseña temporal random (la va a cambiar con el link de invitación)
            'password' => Hash::make(Str::random(16)),
        ]);

        // 2) Crear el empleado vinculado al usuario
        Empleado::create([
            'nombre'            => $request->nombre,
            'apellido_paterno'  => $request->apellido_paterno,
            'apellido_materno'  => $request->apellido_materno,
            'telefono'          => $request->telefono,
            'puesto'            => $request->puesto,
            'fecha_ingreso'     => $request->fecha_ingreso,
            'estatus'           => 'activo', // Se asigna 'activo' por defecto
            'id_usuario'        => $user->id,
        ]);

        // 3) Enviar correo de "crear contraseña" (usa el sistema de reset de Laravel)
        Password::sendResetLink([
            'email' => $user->email,
        ]);

        return redirect()->route('empleados.index')
            ->with('success', 'Empleado registrado. Se envió una invitación al correo para crear su contraseña.');
    }

    public function edit(Empleado $empleado)
    {
        // Carga la vista de edición y le pasa el empleado que se quiere modificar.
        // La vista 'empleados.edit' usará los datos de $empleado para rellenar el formulario.
        return view('empleados.edit', compact('empleado'));
    }

    public function update(Request $request, Empleado $empleado)
    {
        // Obtenemos el ID del usuario vinculado
        $userId = $empleado->user->id;

        // 1) Validar
        $request->validate([
            'nombre'            => 'required|string|max:120',
            'apellido_paterno'  => 'required|string|max:120',
            'apellido_materno'  => 'nullable|string|max:120',
            'telefono'          => 'required|string|max:30',
            'puesto'            => 'required|string|max:120',
            
            // VALIDACIÓN DE CORREO: Único en users, ignorando a este usuario
            'correo_contacto'   => 'required|email|max:255|unique:users,email,'.$userId,
        ]);

        DB::transaction(function () use ($request, $empleado) {
            // 2) Actualizar datos del Empleado
            $empleado->update([
                'nombre'            => $request->nombre,
                'apellido_paterno'  => $request->apellido_paterno,
                'apellido_materno'  => $request->apellido_materno,
                'telefono'          => $request->telefono,
                'puesto'            => $request->puesto,
            ]);

            // 3) Actualizar el LOGIN (Tabla Users) y enviar correo si cambió
            $user = $empleado->user;
            
            // Detectamos si el correo cambió
            if ($user->email !== $request->correo_contacto) {
                
                // VALIDACIÓN DE SEGURIDAD: Verificar contraseña de Admin si se proporciona
                if ($request->filled('password_admin_confirmation')) {
                    if (! Hash::check($request->password_admin_confirmation, Auth::user()->password)) {
                        throw ValidationException::withMessages([
                            'correo_contacto' => 'La contraseña de administrador es incorrecta. No se pudo actualizar el correo.',
                        ]);
                    }
                }

                // Actualizar correo
                $user->email = $request->correo_contacto;
                $user->email_verified_at = null; // Resetear verificación
                $user->save();

                // ---> ENVÍO AUTOMÁTICO DE RESET LINK <---
                Password::sendResetLink(['email' => $user->email]);
            }
        });

        return redirect()->route('empleados.index')
            ->with('success', 'Empleado actualizado correctamente. Si cambiaste el correo, se envió un enlace de acceso al nuevo email.');
    }

    
    public function destroy(string $id)
    {
        // 1. Buscamos al empleado
        $empleado = \App\Models\Empleado::findOrFail($id);

        // 2. CAMBIAMOS EL ESTATUS (Soft Delete)
        $empleado->estatus = 'baja'; 
        
        // 3. Registramos la fecha de baja
        $empleado->fecha_baja = now(); 

        // 4. Guardamos los cambios
        $empleado->save();

        // 5. Redirigimos con un mensaje de éxito
        return redirect()->route('empleados.index')
                        ->with('success', 'Empleado dado de baja correctamente.');
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
        $empleadosQuery = Empleado::with('user')
                            ->where('estatus', $estatusFilter);

        // 3. Si hay un término de búsqueda (no está vacío), aplica los filtros
        if (!empty($query)) {
            $empleadosQuery->where(function($q) use ($query) {
                $q->where('nombre', 'LIKE', '%' . $query . '%')
                  ->orWhere('apellido_paterno', 'LIKE', '%' . $query . '%')
                  ->orWhere('apellido_materno', 'LIKE', '%' . $query . '%')
                  ->orWhere('puesto', 'LIKE', '%' . $query . '%') // ¡Añadí 'puesto' a la búsqueda!
                  ->orWhere('telefono', 'LIKE', '%' . $query . '%')
                  ->orWhereHas('user', function ($userQuery) use ($query) {
                      // Busca en la relación 'user' por el email
                      $userQuery->where('email', 'LIKE', '%' . $query . '%');
                  });
            });
        }

        // 4. Ejecuta la consulta (ya sea filtrada o no)
        $empleados = $empleadosQuery->orderBy('nombre', 'asc')->get();

        // 5. Devuelve la vista parcial con los resultados
        if ($empleados->count() > 0) {
            // Devuelve el HTML renderizado del archivo parcial
            return view('empleados._tabla_empleados', compact('empleados'))->render();
        } else {
            // Devuelve un mensaje de "no encontrado"
            // IMPORTANTE: Cambia 'colspan="5"' al número de columnas de tu tabla
            return '<tr><td colspan="5" class="text-center">No se encontraron empleados.</td></tr>';
        }
    }

    public function reactivar(Empleado $empleado)
    {
        if ($empleado->estatus !== 'baja') {
            return redirect()->route('empleados.index')
                ->with('error', 'Solo se pueden reactivar empleados con estatus de baja.');
            
        }else{
        // Cambia el estatus a 'activo' y limpia la fecha de baja
        $empleado->estatus = 'activo';
        $empleado->fecha_ingreso = now();
        $empleado->fecha_baja = null;
        $empleado->save();

        return redirect()->route('empleados.index')
            ->with('success', 'Empleado reactivado correctamente.');
        }
    }
}