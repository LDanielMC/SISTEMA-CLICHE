<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use App\Models\Cliente;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TareaController extends Controller
{
    public function index(Request $request)
    {
        $query = Tarea::with(['cliente', 'categoria']);

        $sortBy = $request->get('sort_by', 'created_at');
        $sortDir = $request->get('sort_dir', 'desc');

        $query->orderBy($sortBy, $sortDir);

        $tareas = $query->get();

        return view('tareas.index', compact('tareas', 'sortBy', 'sortDir'));
    }

    public function create()
    {
        return view('tareas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => [
                'required',
                'string',
                'max:200',
                Rule::unique('tareas')->where(function ($query) use ($request) {
                    return $query->where('cliente_id', $request->cliente_id);
                }),
            ],
            'cliente_id' => 'required|exists:clientes,id_cliente',
            'categoria_id' => 'required|exists:categorias,id',
            'descripcion' => 'required|string',
            'observaciones' => 'nullable|string',
        ], [
            'titulo.required' => 'El título es obligatorio.',
            'titulo.max' => 'El título no puede exceder 200 caracteres.',
            'titulo.unique' => 'Ya existe una tarea con este título para el cliente seleccionado.',
            'cliente_id.required' => 'Debe seleccionar un cliente.',
            'cliente_id.exists' => 'El cliente seleccionado no existe.',
            'categoria_id.required' => 'Debe seleccionar una categoría.',
            'categoria_id.exists' => 'La categoría seleccionada no existe.',
            'descripcion.required' => 'La descripción es obligatoria.',
        ]);

        Tarea::create($validated);

        return redirect()->route('tareas.index')->with('success', 'Tarea creada exitosamente.');
    }

    public function edit(Tarea $tarea)
    {
        return view('tareas.edit', compact('tarea'));
    }

    public function update(Request $request, Tarea $tarea)
    {
        $validated = $request->validate([
            'titulo' => [
                'required',
                'string',
                'max:200',
                Rule::unique('tareas')->where(function ($query) use ($request) {
                    return $query->where('cliente_id', $request->cliente_id);
                })->ignore($tarea->id),
            ],
            'cliente_id' => 'required|exists:clientes,id_cliente',
            'categoria_id' => 'required|exists:categorias,id',
            'descripcion' => 'required|string',
            'observaciones' => 'nullable|string',
        ], [
            'titulo.required' => 'El título es obligatorio.',
            'titulo.max' => 'El título no puede exceder 200 caracteres.',
            'titulo.unique' => 'Ya existe otra tarea con este título para el cliente seleccionado.',
            'cliente_id.required' => 'Debe seleccionar un cliente.',
            'cliente_id.exists' => 'El cliente seleccionado no existe.',
            'categoria_id.required' => 'Debe seleccionar una categoría.',
            'categoria_id.exists' => 'La categoría seleccionada no existe.',
            'descripcion.required' => 'La descripción es obligatoria.',
        ]);

        $tarea->update($validated);

        return redirect()->route('tareas.index')->with('success', 'Tarea actualizada exitosamente.');
    }

    public function destroy(Tarea $tarea)
    {
        $tarea->delete();

        return redirect()->route('tareas.index')->with('success', 'Tarea eliminada exitosamente.');
    }

    public function search(Request $request)
    {
        $query = $request->get('query', '');

        $tareas = Tarea::with(['cliente', 'categoria'])
            ->where('titulo', 'like', "%{$query}%")
            ->orWhereHas('cliente', function($q) use ($query) {
                $q->where('nombre', 'like', "%{$query}%")
                  ->orWhere('apellido_paterno', 'like', "%{$query}%");
            })
            ->orWhereHas('categoria', function($q) use ($query) {
                $q->where('nombre', 'like', "%{$query}%");
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tareas._tabla_tareas', compact('tareas'));
    }

    public function searchClientes(Request $request)
    {
        $query = $request->get('query', '');

        $clientesQuery = Cliente::where('estatus', 'activo');

        if (!empty($query)) {
            $clientesQuery->where(function($q) use ($query) {
                $q->where('nombre', 'like', "%{$query}%")
                  ->orWhere('apellido_paterno', 'like', "%{$query}%")
                  ->orWhere('apellido_materno', 'like', "%{$query}%")
                  ->orWhere('empresa', 'like', "%{$query}%");
            });
        }

        $clientes = $clientesQuery->limit(20)->get();

        return response()->json($clientes);
    }

    public function searchCategorias(Request $request)
    {
        $query = $request->get('query', '');

        $categoriasQuery = Categoria::where('activo', true);

        if (!empty($query)) {
            $categoriasQuery->where('nombre', 'like', "%{$query}%");
        }

        $categorias = $categoriasQuery->limit(20)->get();

        return response()->json($categorias);
    }
}
