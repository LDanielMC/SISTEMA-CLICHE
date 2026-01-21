<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Models\AsignacionTarea;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    /**
     * Reporte de cumplimiento y puntualidad de empleados.
     */
    public function cumplimiento(Request $request)
    {
        // 1. Definir rango de fechas (Default: mes actual)
        $fechaInicio = $request->input('fecha_inicio') 
            ? Carbon::parse($request->input('fecha_inicio'))->startOfDay() 
            : Carbon::now()->startOfMonth();
            
        $fechaFin = $request->input('fecha_fin') 
            ? Carbon::parse($request->input('fecha_fin'))->endOfDay() 
            : Carbon::now()->endOfDay();

        // 2. Obtener empleados con sus tareas en el rango
        // Filtramos las tareas por fecha_limite dentro del rango seleccionado
        $empleados = Empleado::where('estatus', 'activo')
            ->with(['asignaciones' => function ($query) use ($fechaInicio, $fechaFin) {
                $query->whereBetween('fecha_limite', [$fechaInicio, $fechaFin]);
            }])
            ->get();

        // 3. Procesar datos para el reporte
        $reporte = $empleados->map(function ($empleado) {
            $totalTareas = $empleado->asignaciones->count();
            
            if ($totalTareas === 0) {
                return [
                    'id' => $empleado->id_empleado,
                    'nombre' => $empleado->nombre . ' ' . $empleado->apellido_paterno,
                    'puesto' => $empleado->puesto,
                    'total' => 0,
                    'entregadas' => 0,
                    'a_tiempo' => 0,
                    'tardias' => 0,
                    'pendientes' => 0,
                    'porcentaje_cumplimiento' => 0,
                    'porcentaje_puntualidad' => 0,
                ];
            }

            $entregadas = $empleado->asignaciones->filter(function ($tarea) {
                return $tarea->estado_empleado === 'terminada' || $tarea->estado_admin === 'completa';
            });

            $aTiempo = $entregadas->filter(function ($tarea) {
                if (!$tarea->fecha_entrega) return false;
                // Comparamos solo fecha (ignorar hora si se desea, o usar precisión completa)
                // Asumimos que fecha_limite es Y-m-d (00:00:00) y fecha_entrega es datetime.
                // Si entregó el mismo día de la fecha límite (cualquier hora), ¿cuenta?
                // Generalmente sí.
                return $tarea->fecha_entrega->startOfDay()->lte($tarea->fecha_limite->startOfDay());
            })->count();

            $entregadasCount = $entregadas->count();
            $tardias = $entregadasCount - $aTiempo;
            $pendientes = $totalTareas - $entregadasCount;

            $porcentajeCumplimiento = ($entregadasCount / $totalTareas) * 100;
            
            // Puntualidad: Se calcula sobre las entregadas o sobre el total?
            // "Puntualidad de empleados": % de tareas entregadas a tiempo respecto al total de tareas asignadas (o entregadas).
            // Si calculamos sobre entregadas: De las que hizo, cuántas fueron a tiempo.
            // Si calculamos sobre total: Cuántas hizo a tiempo del total esperado.
            // Usualmente para evaluación de desempeño es sobre el TOTAL asignado.
            $porcentajePuntualidad = ($aTiempo / $totalTareas) * 100;

            return [
                'id' => $empleado->id_empleado,
                'nombre' => $empleado->nombre . ' ' . $empleado->apellido_paterno,
                'puesto' => $empleado->puesto,
                'total' => $totalTareas,
                'entregadas' => $entregadasCount,
                'a_tiempo' => $aTiempo,
                'tardias' => $tardias,
                'pendientes' => $pendientes,
                'porcentaje_cumplimiento' => round($porcentajeCumplimiento, 1),
                'porcentaje_puntualidad' => round($porcentajePuntualidad, 1),
            ];
        });

        // Ordenar por cumplimiento descendente
        $reporte = $reporte->sortByDesc('porcentaje_cumplimiento');

        return view('admin.reportes.cumplimiento', compact('reporte', 'fechaInicio', 'fechaFin'));
    }

    /**
     * Reporte de efectividad e ingresos por cotizaciones.
     */
    public function efectividad(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio') 
            ? Carbon::parse($request->input('fecha_inicio'))->startOfDay() 
            : Carbon::now()->startOfMonth();
            
        $fechaFin = $request->input('fecha_fin') 
            ? Carbon::parse($request->input('fecha_fin'))->endOfDay() 
            : Carbon::now()->endOfDay();
            
        $idCliente = $request->input('id_cliente');

        // Construir consulta base
        $query = \App\Models\Cliente::where('estatus', 'activo')
            ->withCount('cotizaciones') // Historial total de cotizaciones (sin filtro de fecha)
            ->with(['cotizaciones' => function ($q) use ($fechaInicio, $fechaFin) {
                $q->whereBetween('fecha', [$fechaInicio, $fechaFin]);
            }]);

        if ($idCliente) {
            $query->where('id_cliente', $idCliente);
        }

        $clientes = $query->get();

        // Procesar datos
        $reporte = $clientes->map(function ($cliente) {
            $todas = $cliente->cotizaciones;
            $aceptadas = $todas->where('estatus', 'aceptada');
            $rechazadas = $todas->where('estatus', 'rechazada');
            $pendientes = $todas->where('estatus', 'pendiente');

            $countEmitidas = $todas->count();
            $countAceptadas = $aceptadas->count();
            $countRechazadas = $rechazadas->count();
            $countPendientes = $pendientes->count();
            
            // Totales Generales (Emitido)
            $montoEmitido = $todas->sum('total');
            $montoRechazado = $rechazadas->sum('total');
            $montoPendiente = $pendientes->sum('total');

            // Desglose de Ganancias (Solo Aceptadas)
            $montoAceptadoTotal = $aceptadas->sum('total');
            $montoAceptadoSubtotal = $aceptadas->sum('subtotal');
            $montoAceptadoIVA = $aceptadas->sum('iva_total');
            $montoAceptadoISR = $aceptadas->sum('retencion_isr');

            // Efectividad por cantidad (Conversión)
            $efectividad = $countEmitidas > 0 ? ($countAceptadas / $countEmitidas) * 100 : 0;
            
            // Efectividad Monetaria
            $efectividadMonetaria = $montoEmitido > 0 ? ($montoAceptadoTotal / $montoEmitido) * 100 : 0;

            // Ticket Promedio
            $ticketPromedio = $countAceptadas > 0 ? ($montoAceptadoTotal / $countAceptadas) : 0;

            return [
                'cliente' => $cliente->nombre . ' ' . $cliente->apellido_paterno . ($cliente->empresa ? ' (' . $cliente->empresa . ')' : ''),
                'historial_total' => $cliente->cotizaciones_count, // Dato histórico
                'emitidas' => $countEmitidas,
                'aceptadas' => $countAceptadas,
                'rechazadas' => $countRechazadas,
                'pendientes' => $countPendientes,
                'efectividad' => round($efectividad, 1),
                'efectividad_monetaria' => round($efectividadMonetaria, 1),
                'monto_emitido' => $montoEmitido,
                'monto_aceptado' => $montoAceptadoTotal,
                'ganancia_subtotal' => $montoAceptadoSubtotal,
                'ganancia_iva' => $montoAceptadoIVA,
                'ganancia_isr' => $montoAceptadoISR,
                'monto_rechazado' => $montoRechazado,
                'monto_pendiente' => $montoPendiente,
                'ticket_promedio' => $ticketPromedio,
            ];
        })->filter(function ($row) {
            // Opcional: Filtrar clientes sin cotizaciones si se desea limpiar el reporte
            return $row['emitidas'] > 0;
        })->values(); // Reindexar

        // Exportación a Excel (HTML para formato profesional)
        if ($request->has('export') && $request->export === 'excel') {
            $filename = "reporte-efectividad-" . date('Y-m-d') . ".xls";
            
            return response(view('admin.reportes.excel_efectividad', compact('reporte', 'fechaInicio', 'fechaFin')))
                ->header('Content-Type', 'application/vnd.ms-excel; charset=utf-8')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
        }

        // Obtener lista de clientes para el filtro
        $listaClientes = \App\Models\Cliente::where('estatus', 'activo')->orderBy('nombre')->get();

        return view('admin.reportes.efectividad', compact('reporte', 'fechaInicio', 'fechaFin', 'listaClientes', 'idCliente'));
    }

    /**
     * Reporte de carga de trabajo por empleado y cliente.
     */
    public function cargaTrabajo(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio') 
            ? Carbon::parse($request->input('fecha_inicio'))->startOfDay() 
            : Carbon::now()->startOfMonth();
            
        $fechaFin = $request->input('fecha_fin') 
            ? Carbon::parse($request->input('fecha_fin'))->endOfDay() 
            : Carbon::now()->endOfDay();

        $empleados = Empleado::where('estatus', 'activo')
            ->with(['asignaciones' => function ($q) use ($fechaInicio, $fechaFin) {
                // Consideramos fecha_limite o fecha_entrega para la carga de trabajo?
                // Generalmente es lo que tienen asignado con fecha limite en ese rango.
                $q->whereBetween('fecha_limite', [$fechaInicio, $fechaFin])
                  ->with(['tarea.cliente']);
            }])
            ->get();

        $reporte = $empleados->map(function ($empleado) {
            // Filtrar asignaciones que tengan tarea y cliente validos
            $asignacionesValidas = $empleado->asignaciones->filter(function ($a) {
                return $a->tarea && $a->tarea->cliente;
            });

            // Desglose de estatus
            $terminadas = $asignacionesValidas->filter(function ($a) {
                return $a->estado_empleado === 'terminada' || $a->estado_admin === 'completa';
            })->count();
            $pendientes = $asignacionesValidas->count() - $terminadas;

            // Agrupar por ID de cliente
            $porCliente = $asignacionesValidas->groupBy(function ($a) {
                return $a->tarea->cliente->id_cliente;
            });

            $clientesData = $porCliente->map(function ($grupo) {
                $cliente = $grupo->first()->tarea->cliente;
                return [
                    'cliente_id' => $cliente->id_cliente,
                    'cliente_nombre' => $cliente->nombre . ' ' . $cliente->apellido_paterno . ($cliente->empresa ? ' (' . $cliente->empresa . ')' : ''),
                    'cantidad_tareas' => $grupo->count(),
                    'tareas' => $grupo // Para detalle si se requiere
                ];
            })->sortByDesc('cantidad_tareas')->values();

            return [
                'empleado_nombre' => $empleado->nombre . ' ' . $empleado->apellido_paterno,
                'puesto' => $empleado->puesto,
                'total_tareas' => $asignacionesValidas->count(),
                'terminadas' => $terminadas,
                'pendientes' => $pendientes,
                'clientes_detalle' => $clientesData
            ];
        })->filter(function ($row) {
            return $row['total_tareas'] > 0;
        })->values();

        // Ordenar empleados por mayor carga total
        $reporte = $reporte->sortByDesc('total_tareas')->values();

        // --- KPIs Globales ---
        $totalTareasGlobal = $reporte->sum('total_tareas');
        $promedioTareas = $reporte->count() > 0 ? round($totalTareasGlobal / $reporte->count(), 1) : 0;
        $topEmpleado = $reporte->first();

        // Calcular Top Cliente
        $todosClientes = $reporte->pluck('clientes_detalle')->flatten(1);
        $topClienteData = $todosClientes->groupBy('cliente_id')->map(function ($grupo) {
             return [
                 'nombre' => $grupo->first()['cliente_nombre'],
                 'total' => $grupo->sum('cantidad_tareas')
             ];
        })->sortByDesc('total')->first();
        
        $topCliente = $topClienteData ? $topClienteData : null;

        return view('admin.reportes.carga_trabajo', compact('reporte', 'fechaInicio', 'fechaFin', 'totalTareasGlobal', 'promedioTareas', 'topEmpleado', 'topCliente'));
    }
}
