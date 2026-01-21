<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Suscripcion;
use App\Models\CategoriaSuscripcion;
use App\Models\SuscripcionRenovacion;
use App\Http\Requests\SuscripcionRequest;
use App\Http\Requests\RenovacionRequest;
use Carbon\Carbon;

class SuscripcionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Suscripcion::with('categoria');

        // Filtro por categoría
        if ($request->filled('categoria')) {
            $query->where('idCategoria', $request->categoria);
        }

        // Filtro por periodicidad
        if ($request->filled('periodicidad')) {
            $query->where('periodicidad', $request->periodicidad);
        }

        // Filtro por estatus
        if ($request->filled('estatus')) {
            $query->where('estatus', $request->estatus);
        }

        // Filtro por nivel de uso
        if ($request->filled('nivel_uso')) {
            $query->where('nivel_uso', $request->nivel_uso);
        }

        // Búsqueda por nombre
        if ($request->filled('search')) {
            $query->where('nombre_servicio', 'like', '%' . $request->search . '%');
        }

        // Filtro por rango de costo
        if ($request->filled('costo_min')) {
            $query->where('costo', '>=', $request->costo_min);
        }
        if ($request->filled('costo_max')) {
            $query->where('costo', '<=', $request->costo_max);
        }

        $suscripciones = $query->orderBy('fecha_vencimiento', 'asc')->get();
        $categorias = CategoriaSuscripcion::where('estatus', 'activo')->get();

        return view('suscripciones.index', compact('suscripciones', 'categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = CategoriaSuscripcion::where('estatus', 'activo')->get();
        return view('suscripciones.create', compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SuscripcionRequest $request)
    {
        $data = $request->validated();
        
        // Calcular fecha de vencimiento automáticamente
        $data['fecha_vencimiento'] = Suscripcion::calcularFechaVencimiento(
            $data['fecha_inicio'],
            $data['periodicidad']
        );

        Suscripcion::create($data);

        return redirect()->route('suscripciones.index')
            ->with('success', 'Suscripción creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Suscripcion $suscripcion)
    {
        $suscripcion->load(['categoria', 'renovaciones']);
        return view('suscripciones.show', compact('suscripcion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Suscripcion $suscripcion)
    {
        $categorias = CategoriaSuscripcion::where('estatus', 'activo')->get();
        return view('suscripciones.edit', compact('suscripcion', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SuscripcionRequest $request, Suscripcion $suscripcion)
    {
        $data = $request->validated();
        
        // Recalcular fecha de vencimiento si cambió fecha_inicio o periodicidad
        if ($request->filled('fecha_inicio') || $request->filled('periodicidad')) {
            $data['fecha_vencimiento'] = Suscripcion::calcularFechaVencimiento(
                $data['fecha_inicio'],
                $data['periodicidad']
            );
        }

        $suscripcion->update($data);

        return redirect()->route('suscripciones.index')
            ->with('success', 'Suscripción actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage (baja lógica).
     */
    public function destroy(Suscripcion $suscripcion)
    {
        // Baja lógica: cambiar estatus a inactivo
        $suscripcion->update(['estatus' => 'inactivo']);

        return redirect()->route('suscripciones.index')
            ->with('success', 'Suscripción dada de baja exitosamente.');
    }

    /**
     * Reactivar una suscripción.
     */
    public function reactivar(Suscripcion $suscripcion)
    {
        $suscripcion->update(['estatus' => 'activo']);

        return redirect()->route('suscripciones.index')
            ->with('success', 'Suscripción reactivada exitosamente.');
    }

    /**
     * Mostrar formulario de renovación.
     */
    public function renovarForm(Suscripcion $suscripcion)
    {
        return view('suscripciones.renovar', compact('suscripcion'));
    }

    /**
     * Procesar renovación de suscripción.
     */
    public function renovar(RenovacionRequest $request, Suscripcion $suscripcion)
    {
        $data = $request->validated();
        
        // Guardar fecha de vencimiento anterior
        $fechaVencimientoAnterior = $suscripcion->fecha_vencimiento;
        
        // Calcular nueva fecha de vencimiento desde la fecha actual de vencimiento
        $nuevaFechaVencimiento = Carbon::parse($fechaVencimientoAnterior);
        if ($suscripcion->periodicidad === 'mensual') {
            $nuevaFechaVencimiento->addMonth();
        } else {
            $nuevaFechaVencimiento->addYear();
        }
        
        // Crear registro de renovación
        SuscripcionRenovacion::create([
            'idSuscripcion' => $suscripcion->idSuscripcion,
            'fecha_renovacion' => $data['fecha_renovacion'],
            'costo_ciclo' => $data['costo_ciclo'],
            'fecha_vencimiento_anterior' => $fechaVencimientoAnterior,
            'fecha_vencimiento_nueva' => $nuevaFechaVencimiento->format('Y-m-d'),
            'observaciones' => $data['observaciones'] ?? null,
        ]);
        
        // Actualizar fecha de vencimiento en la suscripción
        $suscripcion->update([
            'fecha_vencimiento' => $nuevaFechaVencimiento->format('Y-m-d'),
        ]);
        
        return redirect()->route('suscripciones.show', $suscripcion->idSuscripcion)
            ->with('success', 'Suscripción renovada exitosamente.');
    }
}
