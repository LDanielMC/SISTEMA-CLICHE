<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index(Request $request)
    {
        $estatusFilter = $request->query('estatus', 'activo');

        $sortBy = $request->query('sort_by', 'created_at');
        $sortDir = $request->query('sort_dir', 'desc');

        $sortableColumns = ['nombre', 'created_at'];
        if (!in_array($sortBy, $sortableColumns)) {
            $sortBy = 'created_at';
        }

        if ($estatusFilter === 'activo') {
            $categorias = Categoria::activas()
                            ->orderBy($sortBy, $sortDir)
                            ->get();
        } else {
            $categorias = Categoria::inactivas()
                            ->orderBy($sortBy, $sortDir)
                            ->get();
        }

        return view('categorias.index', compact('categorias', 'estatusFilter', 'sortBy', 'sortDir'));
    }

    public function create()
    {
        return view('categorias.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:categorias,nombre',
            'descripcion' => 'nullable|string|max:500',
        ]);

        Categoria::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'activo' => true,
        ]);

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría registrada correctamente.');
    }

    public function edit(Categoria $categoria)
    {
        return view('categorias.edit', compact('categoria'));
    }

    public function update(Request $request, Categoria $categoria)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:categorias,nombre,' . $categoria->id,
            'descripcion' => 'nullable|string|max:500',
        ]);

        $categoria->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
        ]);

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(Categoria $categoria)
    {
        $categoria->activo = false;
        $categoria->save();

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría desactivada correctamente.');
    }

    public function search(Request $request)
    {
        $query = $request->get('query');
        $estatusFilter = $request->query('estatus', 'activo');

        $categoriasQuery = Categoria::where('activo', $estatusFilter === 'activo');

        if (!empty($query)) {
            $categoriasQuery->where(function($q) use ($query) {
                $q->where('nombre', 'LIKE', '%' . $query . '%')
                  ->orWhere('descripcion', 'LIKE', '%' . $query . '%');
            });
        }

        $categorias = $categoriasQuery->orderBy('nombre', 'asc')->get();

        if ($categorias->count() > 0) {
            return view('categorias._tabla_categorias', compact('categorias'))->render();
        } else {
            return '<tr><td colspan="4" class="text-center py-8 text-gray-500">No se encontraron categorías.</td></tr>';
        }
    }

    public function reactivar(Categoria $categoria)
    {
        if ($categoria->activo) {
            return redirect()->route('categorias.index')
                ->with('error', 'Esta categoría ya está activa.');
        }

        $categoria->activo = true;
        $categoria->save();

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría reactivada correctamente.');
    }
}
