<?php

namespace App\Http\Controllers;

use App\Models\Plataforma;
use App\Models\Formato;
use Illuminate\Http\Request;

class CalendarioConfigController extends Controller
{
    /**
     * Muestra la lista de Plataformas y Formatos con formularios para crear nuevos.
     */
    public function index()
    {
        // Obtenemos todos los registros para mostrarlos en tablas
        $plataformas = Plataforma::all();
        $formatos = Formato::all();

        return view('calendario.configuracion', compact('plataformas', 'formatos'));
    }

    // --- Lógica para PLATAFORMAS ---

    public function storePlataforma(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:plataformas,nombre',
            'icono'  => 'nullable|string|max:50', // opcional
        ]);

        Plataforma::create([
            'nombre' => $request->nombre,
            'icono' => $request->icono ?? 'default',
            'activo' => true
        ]);

        return back()->with('success', 'Plataforma agregada correctamente.');
    }

    public function destroyPlataforma($id)
    {
        // Nota: Si ya hay publicaciones usando esta plataforma, SQL podría bloquear el borrado.
        // Lo ideal sería usar SoftDeletes, pero por ahora haremos un borrado simple.
        try {
            $plataforma = Plataforma::findOrFail($id);
            $plataforma->delete();
            return back()->with('success', 'Plataforma eliminada.');
        } catch (\Exception $e) {
            return back()->withErrors('No se puede eliminar esta plataforma porque ya tiene publicaciones asociadas.');
        }
    }

    // --- Lógica para FORMATOS ---

    public function storeFormato(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:formatos,nombre',
            'especificaciones' => 'nullable|string',
        ]);

        Formato::create([
            'nombre' => $request->nombre,
            'especificaciones' => $request->especificaciones,
            'activo' => true
        ]);

        return back()->with('success', 'Formato agregado correctamente.');
    }

    public function destroyFormato($id)
    {
        try {
            $formato = Formato::findOrFail($id);
            $formato->delete();
            return back()->with('success', 'Formato eliminado.');
        } catch (\Exception $e) {
            return back()->withErrors('No se puede eliminar este formato porque ya tiene publicaciones asociadas.');
        }
    }
}