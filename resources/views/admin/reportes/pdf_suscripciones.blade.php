<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Suscripciones</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1f2937;
            line-height: 1.4;
            margin: 20px 25px;
        }
        @page {
            margin: 20px 25px;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #4f46e5;
        }
        .header h1 {
            font-size: 22px;
            color: #4f46e5;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 10px;
            color: #6b7280;
        }
        
        /* KPIs Grid */
        .kpi-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .kpi-row {
            display: table-row;
        }
        .kpi-card {
            display: table-cell;
            width: 33%;
            padding: 12px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            text-align: center;
            vertical-align: middle;
        }
        .kpi-label {
            font-size: 9px;
            text-transform: uppercase;
            color: #6b7280;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        .kpi-value {
            font-size: 20px;
            font-weight: bold;
            color: #111827;
            margin-top: 5px;
        }
        .kpi-subtext {
            font-size: 8px;
            color: #9ca3af;
            margin-top: 3px;
        }
        
        /* Alertas */
        .alerts {
            margin-bottom: 20px;
            padding: 12px;
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
        }
        .alerts h3 {
            font-size: 12px;
            margin-bottom: 8px;
            color: #92400e;
        }
        .alert-item {
            font-size: 10px;
            margin: 4px 0;
            color: #78350f;
        }
        
        /* Sección de gráficas */
        .charts-section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        .chart-title {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 12px;
            color: #1f2937;
            background: #f3f4f6;
            padding: 8px 12px;
            border-left: 4px solid #4f46e5;
        }
        .chart-bar {
            margin: 6px 0;
            padding-left: 10px;
        }
        .chart-bar-label {
            font-size: 9px;
            margin-bottom: 4px;
            display: block;
            color: #374151;
            font-weight: 600;
        }
        .chart-bar-container {
            width: 85%;
            height: 18px;
            background: #e5e7eb;
            border-radius: 2px;
            position: relative;
            overflow: hidden;
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
        }
        .chart-bar-fill {
            height: 100%;
            background: #4f46e5;
            border-radius: 2px;
            position: relative;
            min-width: 2px;
        }
        .chart-bar-value {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 8px;
            color: white;
            font-weight: bold;
            text-shadow: 0 1px 1px rgba(0,0,0,0.3);
        }
        .chart-legend {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 2px;
            margin-right: 5px;
            vertical-align: middle;
        }
        
        /* Colores para barras */
        .bar-indigo { background: #4f46e5; }
        .bar-green { background: #10b981; }
        .bar-amber { background: #f59e0b; }
        .bar-red { background: #ef4444; }
        .bar-purple { background: #8b5cf6; }
        .bar-pink { background: #ec4899; }
        .bar-blue { background: #3b82f6; }
        .bar-teal { background: #14b8a6; }
        .bar-gray { background: #6b7280; }
        
        /* Tabla */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 9px;
        }
        th {
            background: #f3f4f6;
            padding: 8px 6px;
            text-align: left;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8px;
            border-bottom: 2px solid #d1d5db;
        }
        td {
            padding: 8px 6px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }
        tbody tr:nth-child(even) {
            background: #f9fafb;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }
        .badge-green { background: #d1fae5; color: #065f46; }
        .badge-blue { background: #dbeafe; color: #1e40af; }
        .badge-gray { background: #f3f4f6; color: #374151; }
        .badge-red { background: #fee2e2; color: #991b1b; }
        .badge-yellow { background: #fef3c7; color: #92400e; }
        
        .footer {
            position: fixed;
            bottom: 15px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 8px;
        }
        
        .page-break {
            page-break-after: always;
        }
        
        h2 {
            font-size: 14px;
            margin: 20px 0 15px;
            color: #1f2937;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 5px;
        }
    </style>
</head>
<body>
    <!-- ENCABEZADO -->
    <div class="header">
        <h1>📊 Reporte de Suscripciones y Costos</h1>
        <p>Generado el: {{ date('d/m/Y H:i') }}</p>
    </div>

    <!-- RESUMEN EJECUTIVO - KPIs -->
    <div class="kpi-grid">
        <div class="kpi-row">
            <div class="kpi-card">
                <div class="kpi-label">Proyección Anual</div>
                <div class="kpi-value">${{ number_format($proyeccionAnual, 0) }}</div>
                <div class="kpi-subtext">Gasto total anualizado</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-label">Total Suscripciones</div>
                <div class="kpi-value">{{ $suscripciones->count() }}</div>
                <div class="kpi-subtext">Servicios activos</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-label">Costo Promedio</div>
                <div class="kpi-value">${{ number_format($promedioCosto, 0) }}</div>
                <div class="kpi-subtext">Por suscripción</div>
            </div>
        </div>
    </div>

    <div class="kpi-grid" style="margin-top: 10px;">
        <div class="kpi-row">
            <div class="kpi-card">
                <div class="kpi-label">Gasto Mensual</div>
                <div class="kpi-value">${{ number_format($totalGastoMensual, 2) }}</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-label">Gasto Anual</div>
                <div class="kpi-value">${{ number_format($totalGastoAnual, 2) }}</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-label">A Optimizar</div>
                <div class="kpi-value" style="color: #f59e0b;">{{ $aOptimizar }}</div>
                <div class="kpi-subtext">Bajo uso / Alto costo</div>
            </div>
        </div>
    </div>

    <!-- ALERTAS -->
    @if($vencenEn7Dias > 0 || $vencidas > 0)
    <div class="alerts">
        <h3>⚠️ Alertas de Renovación</h3>
        @if($vencidas > 0)
        <div class="alert-item">🔴 <strong>{{ $vencidas }}</strong> suscripción(es) VENCIDA(S)</div>
        @endif
        @if($vencenEn7Dias > 0)
        <div class="alert-item">🟡 <strong>{{ $vencenEn7Dias }}</strong> vence(n) en los próximos 7 días</div>
        @endif
        @if($vencenEn30Dias > 0)
        <div class="alert-item">🟢 <strong>{{ $vencenEn30Dias }}</strong> vence(n) en los próximos 30 días</div>
        @endif
    </div>
    @endif

    <!-- GRÁFICA 1: Gasto Mensual por Categoría -->
    <div class="charts-section">
        <div class="chart-title">💰 Distribución de Gasto Mensual por Categoría</div>
        @php
            $totalGrafica = array_sum($datosGrafica->toArray());
            $colores = ['bar-indigo', 'bar-green', 'bar-amber', 'bar-red', 'bar-purple', 'bar-pink', 'bar-blue', 'bar-teal'];
            $colorIndex = 0;
            $sortedGrafica = $datosGrafica->sortByDesc(function($value) { return $value; })->take(5);
        @endphp
        @foreach($sortedGrafica as $categoria => $monto)
        @php 
            $porcentaje = $totalGrafica > 0 ? ($monto / $totalGrafica) * 100 : 0;
        @endphp
        <div class="chart-bar">
            <div style="display: table; width: 100%;">
                <div style="display: table-cell; width: 35%; vertical-align: middle;">
                    <span class="chart-bar-label">{{ $categoria }}</span>
                </div>
                <div style="display: table-cell; width: 50%; vertical-align: middle;">
                    <div class="chart-bar-container" style="width: 100%;">
                        <div class="chart-bar-fill {{ $colores[$colorIndex % count($colores)] }}" style="width: {{ $porcentaje }}%;"></div>
                    </div>
                </div>
                <div style="display: table-cell; width: 15%; vertical-align: middle; text-align: right; padding-left: 8px;">
                    <span style="font-size: 9px; font-weight: bold; color: #374151;">${{ number_format($monto, 0) }}</span>
                    <span style="font-size: 8px; color: #6b7280;"> ({{ number_format($porcentaje, 1) }}%)</span>
                </div>
            </div>
        </div>
        @php $colorIndex++; @endphp
        @endforeach
    </div>

    <!-- GRÁFICA 2: Distribución por Nivel de Uso -->
    <div class="charts-section">
        <div class="chart-title">📊 Distribución por Nivel de Uso</div>
        @php
            $totalUso = array_sum($distribucionUso);
        @endphp
        @foreach($distribucionUso as $nivel => $cantidad)
        @php
            $porcentajeUso = $totalUso > 0 ? ($cantidad / $totalUso) * 100 : 0;
        @endphp
        <div class="chart-bar">
            <div style="display: table; width: 100%;">
                <div style="display: table-cell; width: 35%; vertical-align: middle;">
                    <span class="chart-bar-label">{{ $nivel }}</span>
                </div>
                <div style="display: table-cell; width: 50%; vertical-align: middle;">
                    <div class="chart-bar-container" style="width: 100%;">
                        <div class="chart-bar-fill {{ $nivel === 'Alto' ? 'bar-green' : ($nivel === 'Medio' ? 'bar-blue' : 'bar-gray') }}" style="width: {{ $porcentajeUso }}%;"></div>
                    </div>
                </div>
                <div style="display: table-cell; width: 15%; vertical-align: middle; text-align: right; padding-left: 8px;">
                    <span style="font-size: 9px; font-weight: bold; color: #374151;">{{ $cantidad }}</span>
                    <span style="font-size: 8px; color: #6b7280;"> ({{ number_format($porcentajeUso, 1) }}%)</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- GRÁFICA 3: Estado de Renovación -->
    <div class="charts-section">
        <div class="chart-title">🔔 Estado de Renovación</div>
        @php
            $maxEstado = max($distribucionEstado);
            $totalEstado = array_sum($distribucionEstado);
        @endphp
        @foreach($distribucionEstado as $estado => $cantidad)
        @php
            $porcentajeEstado = $totalEstado > 0 ? ($cantidad / $totalEstado) * 100 : 0;
        @endphp
        <div class="chart-bar">
            <div style="display: table; width: 100%;">
                <div style="display: table-cell; width: 35%; vertical-align: middle;">
                    <span class="chart-bar-label">{{ $estado }}</span>
                </div>
                <div style="display: table-cell; width: 50%; vertical-align: middle;">
                    <div class="chart-bar-container" style="width: 100%;">
                        <div class="chart-bar-fill {{ $estado === 'OK' ? 'bar-green' : ($estado === 'Por Vencer' ? 'bar-amber' : ($estado === 'Crítico' ? 'bar-red' : 'bar-red')) }}" style="width: {{ $maxEstado > 0 ? ($cantidad / $maxEstado) * 100 : 0 }}%;"></div>
                    </div>
                </div>
                <div style="display: table-cell; width: 15%; vertical-align: middle; text-align: right; padding-left: 8px;">
                    <span style="font-size: 9px; font-weight: bold; color: #374151;">{{ $cantidad }}</span>
                    <span style="font-size: 8px; color: #6b7280;"> ({{ number_format($porcentajeEstado, 1) }}%)</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="page-break"></div>

    <!-- TABLA DETALLADA -->
    <h2>📋 Detalle de Suscripciones</h2>
    <table>
        <thead>
            <tr>
                <th>Servicio / Categoría</th>
                <th class="text-right">Costo</th>
                <th>Period.</th>
                <th>Vencimiento</th>
                <th class="text-center">Estado</th>
                <th class="text-center">Uso</th>
            </tr>
        </thead>
        <tbody>
            @foreach($suscripciones as $sub)
            <tr>
                <td>
                    <strong>{{ $sub->nombre_servicio }}</strong><br>
                    <span style="color: #9ca3af; font-size: 8px;">{{ $sub->categoria->nombre ?? 'Sin categoría' }}</span>
                </td>
                <td class="text-right">
                    <strong>${{ number_format($sub->costo, 2) }}</strong>
                </td>
                <td style="text-transform: capitalize; font-size: 9px;">
                    {{ $sub->periodicidad }}
                </td>
                <td>
                    {{ $sub->fecha_vencimiento ? $sub->fecha_vencimiento->format('d/m/Y') : 'N/A' }}
                    @if($sub->dias_restantes !== null)
                        <br>
                        <span style="font-size: 8px; color: {{ $sub->dias_restantes < 0 ? '#991b1b' : ($sub->dias_restantes <= 7 ? '#92400e' : '#6b7280') }};">
                            @if($sub->dias_restantes < 0)
                                Vencida hace {{ abs($sub->dias_restantes) }}d
                            @elseif($sub->dias_restantes == 0)
                                Vence HOY
                            @else
                                En {{ $sub->dias_restantes }} días
                            @endif
                        </span>
                    @endif
                </td>
                <td class="text-center">
                    @php 
                        $diasRest = $sub->dias_restantes;
                        if ($diasRest === null || $diasRest > 30) {
                            $badgeClass = 'badge-green';
                            $badgeText = 'OK';
                        } elseif ($diasRest < 0) {
                            $badgeClass = 'badge-red';
                            $badgeText = 'Vencida';
                        } elseif ($diasRest <= 7) {
                            $badgeClass = 'badge-red';
                            $badgeText = 'Crítico';
                        } else {
                            $badgeClass = 'badge-yellow';
                            $badgeText = 'Por Vencer';
                        }
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ $badgeText }}</span>
                </td>
                <td class="text-center">
                    <span class="badge {{ $sub->nivel_uso === 'alto' ? 'badge-green' : ($sub->nivel_uso === 'medio' ? 'badge-blue' : 'badge-gray') }}">
                        {{ ucfirst($sub->nivel_uso) }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Sistema Cliche - Reporte Administrativo de Suscripciones | Página {PAGE_NUM} de {PAGE_COUNT}
    </div>
</body>
</html>
