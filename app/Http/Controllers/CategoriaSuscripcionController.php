<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoriaSuscripcion;
use App\Http\Requests\CategoriaSuscripcionRequest;

class CategoriaSuscripcionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorias = CategoriaSuscripcion::withCount('suscripciones')->get();
        return view('categorias_suscripcion.index', compact('categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categorias_suscripcion.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoriaSuscripcionRequest $request)
    {
        CategoriaSuscripcion::create($request->validated());

        return redirect()->route('categorias-suscripcion.index')
            ->with('success', 'Categoría creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CategoriaSuscripcion $categoriasSuscripcion)
    {
        $categoriasSuscripcion->load('suscripciones');
        return view('categorias_suscripcion.show', compact('categoriasSuscripcion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CategoriaSuscripcion $categoriasSuscripcion)
    {
        return view('categorias_suscripcion.edit', compact('categoriasSuscripcion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoriaSuscripcionRequest $request, CategoriaSuscripcion $categoriasSuscripcion)
    {
        $categoriasSuscripcion->update($request->validated());

        return redirect()->route('categorias-suscripcion.index')
            ->with('success', 'Categoría actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage (eliminación lógica).
     */
    public function destroy(CategoriaSuscripcion $categoriasSuscripcion)
    {
        // Verificar si tiene suscripciones activas asociadas
        $suscripcionesActivas = $categoriasSuscripcion->suscripciones()
            ->where('estatus', 'activo')
            ->count();

        if ($suscripcionesActivas > 0) {
            return redirect()->route('categorias-suscripcion.index')
                ->with('error', 'No se puede dar de baja la categoría porque tiene suscripciones activas asociadas.');
        }

        // Eliminación lógica: cambiar estatus a inactivo
        $categoriasSuscripcion->update(['estatus' => 'inactivo']);

        return redirect()->route('categorias-suscripcion.index')
            ->with('success', 'Categoría dada de baja exitosamente.');
    }

    /**
     * Reactivar una categoría inactiva.
     */
    public function reactivar(CategoriaSuscripcion $categoriasSuscripcion)
    {
        $categoriasSuscripcion->update(['estatus' => 'activo']);

        return redirect()->route('categorias-suscripcion.index')
            ->with('success', 'Categoría reactivada exitosamente.');
    }
}
