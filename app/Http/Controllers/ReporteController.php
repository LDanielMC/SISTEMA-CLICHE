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

    /**
     * Reporte de costos y proyección de suscripciones.
     */
    public function suscripciones(Request $request)
    {
        // Filtros
        $idCategoria = $request->input('id_categoria');
        $periodicidad = $request->input('periodicidad');
        $nivelUso = $request->input('nivel_uso');
        $orden = $request->input('orden', 'dias_restantes'); // Default: próximas a vencer
        $direccion = $request->input('direccion', 'asc');

        $query = \App\Models\Suscripcion::with('categoria');

        // Aplicar filtros
        if ($idCategoria) {
            $query->where('idCategoria', $idCategoria);
        }
        if ($periodicidad) {
            $query->where('periodicidad', $periodicidad);
        }
        if ($nivelUso) {
            $query->where('nivel_uso', $nivelUso);
        }

        // Obtener datos
        $suscripciones = $query->get();

        // Ordenamiento (en colección porque dias_restantes es un accessor)
        if ($orden === 'dias_restantes') {
            $suscripciones = $direccion === 'asc' 
                ? $suscripciones->sortBy('dias_restantes') 
                : $suscripciones->sortByDesc('dias_restantes');
        } elseif ($orden === 'costo') {
            $suscripciones = $direccion === 'asc' 
                ? $suscripciones->sortBy('costo') 
                : $suscripciones->sortByDesc('costo');
        } else {
            // Orden por defecto DB
            $suscripciones = $direccion === 'asc' 
                ? $suscripciones->sortBy($orden) 
                : $suscripciones->sortByDesc($orden);
        }

        // === MÉTRICAS AVANZADAS PARA TOMA DE DECISIONES ===
        
        // 1. Totales por periodicidad
        $totalGastoMensual = $suscripciones->where('periodicidad', 'mensual')->sum('costo');
        $totalGastoAnual = $suscripciones->where('periodicidad', 'anual')->sum('costo');
        
        // 2. Proyección anual (mensualizar todo para comparar)
        $proyeccionAnual = $suscripciones->sum(function ($sub) {
            return $sub->periodicidad === 'mensual' ? $sub->costo * 12 : $sub->costo;
        });
        
        // 3. Promedio de costo por suscripción
        $promedioCosto = $suscripciones->avg('costo');
        
        // 4. Alertas de vencimiento
        $vencenEn7Dias = $suscripciones->filter(function ($sub) {
            return $sub->dias_restantes !== null && $sub->dias_restantes >= 0 && $sub->dias_restantes <= 7;
        })->count();
        
        $vencenEn30Dias = $suscripciones->filter(function ($sub) {
            return $sub->dias_restantes !== null && $sub->dias_restantes >= 0 && $sub->dias_restantes <= 30;
        })->count();
        
        $vencidas = $suscripciones->filter(function ($sub) {
            return $sub->dias_restantes !== null && $sub->dias_restantes < 0;
        })->count();
        
        // 5. Distribución por nivel de uso
        $distribucionUso = [
            'Alto' => $suscripciones->where('nivel_uso', 'alto')->count(),
            'Medio' => $suscripciones->where('nivel_uso', 'medio')->count(),
            'Bajo' => $suscripciones->where('nivel_uso', 'bajo')->count(),
        ];
        
        // 6. Distribución por estado
        $distribucionEstado = [
            'OK' => $suscripciones->filter(fn($s) => $s->dias_restantes === null || $s->dias_restantes > 30)->count(),
            'Por Vencer' => $vencenEn30Dias - $vencenEn7Dias,
            'Crítico' => $vencenEn7Dias,
            'Vencidas' => $vencidas,
        ];
        
        // 7. Suscripciones a optimizar (bajo uso con costo alto)
        $costoAlto = $suscripciones->avg('costo') * 1.2; // 20% por encima del promedio
        $aOptimizar = $suscripciones->filter(function ($sub) use ($costoAlto) {
            return $sub->nivel_uso === 'bajo' && $sub->costo >= $costoAlto;
        })->count();
        
        // 8. Gráfica principal: Gasto por Categoría (mensualizado)
        $datosGrafica = $suscripciones->groupBy('categoria.nombre')->map(function ($grupo) {
            return $grupo->sum(function ($sub) {
                return $sub->periodicidad === 'mensual' ? $sub->costo : $sub->costo / 12;
            });
        });
        
        // 9. Análisis de eficiencia: Costo promedio por nivel de uso
        $costoPromedioUso = [
            'Alto' => $suscripciones->where('nivel_uso', 'alto')->avg('costo') ?? 0,
            'Medio' => $suscripciones->where('nivel_uso', 'medio')->avg('costo') ?? 0,
            'Bajo' => $suscripciones->where('nivel_uso', 'bajo')->avg('costo') ?? 0,
        ];
        
        // Exportación PDF
        if ($request->has('export') && $request->export === 'pdf') {
            $pdf = \PDF::loadView('admin.reportes.pdf_suscripciones', compact(
                'suscripciones', 
                'totalGastoMensual', 
                'totalGastoAnual',
                'proyeccionAnual',
                'promedioCosto',
                'vencenEn7Dias',
                'vencenEn30Dias',
                'vencidas',
                'distribucionUso',
                'distribucionEstado',
                'aOptimizar',
                'datosGrafica'
            ));
            return $pdf->download('reporte-suscripciones-' . date('Y-m-d') . '.pdf');
        }

        // Listas para filtros
        $categorias = \App\Models\CategoriaSuscripcion::orderBy('nombre')->get();

        return view('admin.reportes.suscripciones', compact(
            'suscripciones', 
            'categorias', 
            'datosGrafica', 
            'totalGastoMensual', 
            'totalGastoAnual',
            'proyeccionAnual',
            'promedioCosto',
            'vencenEn7Dias',
            'vencenEn30Dias',
            'vencidas',
            'distribucionUso',
            'distribucionEstado',
            'aOptimizar',
            'costoPromedioUso'
        ));
    }

    /**
     * Reporte de acuerdos por cliente.
     */
    public function acuerdosCliente(Request $request)
    {
        $idCliente = $request->input('id_cliente');

        // Query base: obtener clientes con sus minutas y acuerdos
        $query = \App\Models\Cliente::with(['minutas.acuerdos'])
            ->where('estatus', 'activo');

        if ($idCliente) {
            $query->where('id_cliente', $idCliente);
        }

        $clientes = $query->get();

        // Calcular estadísticas por cliente
        $datosClientes = $clientes->map(function ($cliente) {
            $acuerdos = $cliente->minutas->flatMap(fn($m) => $m->acuerdos);
            $totalAcuerdos = $acuerdos->count();
            $concluidos = $acuerdos->where('estatus', 'concluido')->count();
            $pendientes = $acuerdos->where('estatus', 'pendiente')->count();
            $porcentajeConcluido = $totalAcuerdos > 0 ? round(($concluidos / $totalAcuerdos) * 100, 1) : 0;

            return [
                'cliente' => $cliente,
                'total_acuerdos' => $totalAcuerdos,
                'concluidos' => $concluidos,
                'pendientes' => $pendientes,
                'porcentaje_concluido' => $porcentajeConcluido,
                'acuerdos_pendientes' => $acuerdos->where('estatus', 'pendiente')->values()
            ];
        })->filter(fn($d) => $d['total_acuerdos'] > 0); // Solo clientes con acuerdos

        // Datos para gráfica de pastel (si hay un cliente seleccionado)
        $datosGrafica = null;
        if ($idCliente && $datosClientes->isNotEmpty()) {
            $data = $datosClientes->first();
            $datosGrafica = [
                'Concluidos' => $data['concluidos'],
                'Pendientes' => $data['pendientes']
            ];
        }

        // Totales generales
        $totalAcuerdosGeneral = $datosClientes->sum('total_acuerdos');
        $totalConcluidosGeneral = $datosClientes->sum('concluidos');
        $totalPendientesGeneral = $datosClientes->sum('pendientes');
        $porcentajeGeneral = $totalAcuerdosGeneral > 0 
            ? round(($totalConcluidosGeneral / $totalAcuerdosGeneral) * 100, 1) 
            : 0;

        // Lista de clientes para filtro
        $clientesLista = \App\Models\Cliente::where('estatus', 'activo')
            ->orderBy('empresa')
            ->get();

        return view('admin.reportes.acuerdos_cliente', compact(
            'datosClientes',
            'datosGrafica',
            'clientesLista',
            'idCliente',
            'totalAcuerdosGeneral',
            'totalConcluidosGeneral',
            'totalPendientesGeneral',
            'porcentajeGeneral'
        ));
    }

    /**
     * Reporte de crecimiento de clientes.
     */
    public function crecimientoClientes(Request $request)
    {
        $anio = $request->input('anio', date('Y'));

        // Clientes al inicio del año
        $clientesInicioAnio = \App\Models\Cliente::where(function($q) use ($anio) {
            $q->where('fecha_registro', '<', "$anio-01-01")
              ->where(function($q2) use ($anio) {
                  $q2->whereNull('fecha_baja')
                     ->orWhere('fecha_baja', '>=', "$anio-01-01");
              });
        })->count();

        // Datos mensuales
        $meses = [];
        $datosGrafica = [
            'labels' => [],
            'altas' => [],
            'bajas' => [],
            'total' => []
        ];

        $totalAcumulado = $clientesInicioAnio;
        $totalAltasAnio = 0;
        $totalBajasAnio = 0;

        for ($mes = 1; $mes <= 12; $mes++) {
            $nombreMes = \Carbon\Carbon::create($anio, $mes, 1)->locale('es')->monthName;
            $nombreMesCorto = \Carbon\Carbon::create($anio, $mes, 1)->locale('es')->shortMonthName;
            
            // Altas en el mes
            $altas = \App\Models\Cliente::whereYear('fecha_registro', $anio)
                ->whereMonth('fecha_registro', $mes)
                ->count();

            // Bajas en el mes
            $bajas = \App\Models\Cliente::whereYear('fecha_baja', $anio)
                ->whereMonth('fecha_baja', $mes)
                ->count();

            $totalAcumulado = $totalAcumulado + $altas - $bajas;
            $totalAltasAnio += $altas;
            $totalBajasAnio += $bajas;

            $meses[] = [
                'mes' => ucfirst($nombreMes),
                'altas' => $altas,
                'bajas' => $bajas,
                'total' => $totalAcumulado
            ];

            $datosGrafica['labels'][] = ucfirst($nombreMesCorto);
            $datosGrafica['altas'][] = $altas;
            $datosGrafica['bajas'][] = $bajas;
            $datosGrafica['total'][] = $totalAcumulado;
        }

        // Clientes al final del año
        $clientesFinalAnio = $totalAcumulado;

        // Crecimiento neto y porcentaje
        $crecimientoNeto = $clientesFinalAnio - $clientesInicioAnio;
        $porcentajeCrecimiento = $clientesInicioAnio > 0 
            ? round(($crecimientoNeto / $clientesInicioAnio) * 100, 2) 
            : 0;

        // Mejor y peor mes
        $mejorMes = collect($meses)->sortByDesc('altas')->first();
        $peorMes = collect($meses)->filter(fn($m) => $m['bajas'] > 0)->sortByDesc('bajas')->first();

        // Lista de años disponibles
        $aniosDisponibles = \App\Models\Cliente::selectRaw('DISTINCT YEAR(fecha_registro) as anio')
            ->whereNotNull('fecha_registro')
            ->orderBy('anio', 'desc')
            ->pluck('anio');

        if ($aniosDisponibles->isEmpty()) {
            $aniosDisponibles = collect([date('Y')]);
        }

        return view('admin.reportes.crecimiento_clientes', compact(
            'anio',
            'clientesInicioAnio',
            'clientesFinalAnio',
            'totalAltasAnio',
            'totalBajasAnio',
            'crecimientoNeto',
            'porcentajeCrecimiento',
            'meses',
            'datosGrafica',
            'mejorMes',
            'peorMes',
            'aniosDisponibles'
        ));
    }
}
