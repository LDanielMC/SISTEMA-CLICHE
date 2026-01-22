<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📈 {{ __('Reporte de Crecimiento de Clientes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Acciones -->
            <div class="flex justify-end items-center mb-6">
                <button onclick="exportarPNG()" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Exportar PNG
                </button>
            </div>

            <!-- VISTA WEB (sin gráfica) -->
            <div id="web-view">

                <!-- Tarjetas de Resumen - KPIs -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 overflow-hidden shadow-lg sm:rounded-lg p-5 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs font-medium uppercase opacity-90">Inicio {{ $anio }}</div>
                                <div class="mt-2 text-2xl font-bold">{{ $clientesInicioAnio }}</div>
                                <div class="text-xs opacity-80 mt-1">Clientes activos</div>
                            </div>
                            <div class="text-3xl opacity-80">👥</div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-green-500 to-green-600 overflow-hidden shadow-lg sm:rounded-lg p-5 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs font-medium uppercase opacity-90">Final {{ $anio }}</div>
                                <div class="mt-2 text-2xl font-bold">{{ $clientesFinalAnio }}</div>
                                <div class="text-xs opacity-80 mt-1">Clientes activos</div>
                            </div>
                            <div class="text-3xl opacity-80">🎯</div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 border-l-4 {{ $crecimientoNeto >= 0 ? 'border-green-500' : 'border-red-500' }}">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs font-medium uppercase text-gray-500">Crecimiento Neto</div>
                                <div class="mt-2 text-2xl font-bold {{ $crecimientoNeto >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $crecimientoNeto >= 0 ? '+' : '' }}{{ $crecimientoNeto }}
                                </div>
                                <div class="text-xs text-gray-500 mt-1">{{ $porcentajeCrecimiento >= 0 ? '+' : '' }}{{ $porcentajeCrecimiento }}%</div>
                            </div>
                            <div class="text-3xl">{{ $crecimientoNeto >= 0 ? '📈' : '📉' }}</div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 border-l-4 border-indigo-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs font-medium uppercase text-gray-500">Movimientos</div>
                                <div class="mt-2 flex items-baseline gap-2">
                                    <span class="text-lg font-bold text-green-600">+{{ $totalAltasAnio }}</span>
                                    <span class="text-lg font-bold text-red-600">-{{ $totalBajasAnio }}</span>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">Altas / Bajas</div>
                            </div>
                            <div class="text-3xl">🔄</div>
                        </div>
                    </div>
                </div>

                <!-- Insights -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    @if($mejorMes)
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 text-2xl mr-3">🏆</div>
                            <div>
                                <h4 class="text-sm font-bold text-green-800">Mejor Mes de Captación</h4>
                                <p class="text-sm text-green-700 mt-1">
                                    <strong>{{ $mejorMes['mes'] }}</strong> con <strong>{{ $mejorMes['altas'] }}</strong> nuevos clientes
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($peorMes)
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 text-2xl mr-3">⚠️</div>
                            <div>
                                <h4 class="text-sm font-bold text-red-800">Mayor Pérdida de Clientes</h4>
                                <p class="text-sm text-red-700 mt-1">
                                    <strong>{{ $peorMes['mes'] }}</strong> con <strong>{{ $peorMes['bajas'] }}</strong> bajas registradas
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    
                    <!-- Columna Izquierda: Filtro -->
                    <div class="lg:col-span-1">
                        <div class="bg-white shadow sm:rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Filtro</h3>
                            <form action="{{ route('reportes.crecimiento_clientes') }}" method="GET">
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Año</label>
                                    <select name="anio" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @foreach($aniosDisponibles as $a)
                                            <option value="{{ $a }}" {{ $anio == $a ? 'selected' : '' }}>
                                                {{ $a }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="flex justify-end">
                                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm w-full">Filtrar</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Columna Derecha: Tabla Mensual -->
                    <div class="lg:col-span-3">
                        <div class="bg-white shadow sm:rounded-lg overflow-hidden">
                            <div class="px-6 py-4 bg-gray-50 border-b">
                                <h3 class="text-lg font-medium text-gray-900">Evolución Mensual - {{ $anio }}</h3>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mes</th>
                                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Altas</th>
                                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Bajas</th>
                                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Neto</th>
                                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total Acumulado</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($meses as $mes)
                                            @php
                                                $neto = $mes['altas'] - $mes['bajas'];
                                            @endphp
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ $mes['mes'] }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        +{{ $mes['altas'] }}
                                                    </span>
                                                    @if($mes['reactivaciones'] > 0)
                                                        <div class="text-[10px] text-gray-500 mt-1">
                                                            (N:{{ $mes['nuevos'] }} / R:{{ $mes['reactivaciones'] }})
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        -{{ $mes['bajas'] }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <span class="text-sm font-bold {{ $neto >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                        {{ $neto >= 0 ? '+' : '' }}{{ $neto }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <span class="text-sm font-bold text-indigo-600">{{ $mes['total'] }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- VISTA PARA EXPORTACIÓN PNG (oculta, solo se muestra al exportar) -->
            <div id="export-view" style="display: none; position: fixed; left: -9999px; width: 1200px; background: white; padding: 40px;">
                <!-- Encabezado profesional -->
                <div style="text-align: center; margin-bottom: 30px; border-bottom: 3px solid #4f46e5; padding-bottom: 20px;">
                    <h1 style="font-size: 28px; color: #4f46e5; font-weight: bold; margin: 0;">📈 REPORTE DE CRECIMIENTO DE CLIENTES</h1>
                    <p style="font-size: 14px; color: #6b7280; margin-top: 8px;">Análisis de evolución anual {{ $anio }}</p>
                    <p style="font-size: 12px; color: #9ca3af; margin-top: 4px;">Generado el {{ date('d/m/Y H:i') }}</p>
                </div>

                <!-- KPIs en Grid -->
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px;">
                    <div style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); padding: 20px; border-radius: 8px; color: white; text-align: center;">
                        <div style="font-size: 11px; text-transform: uppercase; opacity: 0.9; margin-bottom: 8px;">Inicio {{ $anio }}</div>
                        <div style="font-size: 32px; font-weight: bold;">{{ $clientesInicioAnio }}</div>
                        <div style="font-size: 10px; opacity: 0.8; margin-top: 5px;">Clientes activos</div>
                    </div>
                    <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 20px; border-radius: 8px; color: white; text-align: center;">
                        <div style="font-size: 11px; text-transform: uppercase; opacity: 0.9; margin-bottom: 8px;">Final {{ $anio }}</div>
                        <div style="font-size: 32px; font-weight: bold;">{{ $clientesFinalAnio }}</div>
                        <div style="font-size: 10px; opacity: 0.8; margin-top: 5px;">Clientes activos</div>
                    </div>
                    <div style="background: {{ $crecimientoNeto >= 0 ? '#10b981' : '#ef4444' }}; padding: 20px; border-radius: 8px; color: white; text-align: center;">
                        <div style="font-size: 11px; text-transform: uppercase; opacity: 0.9; margin-bottom: 8px;">Crecimiento Neto</div>
                        <div style="font-size: 32px; font-weight: bold;">{{ $crecimientoNeto >= 0 ? '+' : '' }}{{ $crecimientoNeto }}</div>
                        <div style="font-size: 10px; opacity: 0.8; margin-top: 5px;">{{ $porcentajeCrecimiento >= 0 ? '+' : '' }}{{ $porcentajeCrecimiento }}%</div>
                    </div>
                    <div style="background: #6366f1; padding: 20px; border-radius: 8px; color: white; text-align: center;">
                        <div style="font-size: 11px; text-transform: uppercase; opacity: 0.9; margin-bottom: 8px;">Movimientos</div>
                        <div style="font-size: 24px; font-weight: bold; margin-top: 8px;">
                            <span style="color: #d1fae5;">+{{ $totalAltasAnio }}</span> / 
                            <span style="color: #fecaca;">-{{ $totalBajasAnio }}</span>
                        </div>
                        <div style="font-size: 10px; opacity: 0.8; margin-top: 5px;">Altas / Bajas</div>
                    </div>
                </div>

                <!-- Gráfica de Línea -->
                <div style="background: white; padding: 25px; border: 2px solid #e5e7eb; border-radius: 8px; margin-bottom: 30px;">
                    <h3 style="font-size: 18px; font-weight: bold; color: #374151; margin-bottom: 20px; text-align: center;">Evolución de Clientes durante {{ $anio }}</h3>
                    <div style="height: 350px;">
                        <canvas id="chartExport"></canvas>
                    </div>
                </div>

                <!-- Insights -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
                    @if($mejorMes)
                    <div style="background: #d1fae5; border-left: 4px solid #10b981; padding: 15px; border-radius: 8px;">
                        <div style="display: flex; align-items: center;">
                            <div style="font-size: 24px; margin-right: 10px;">🏆</div>
                            <div>
                                <h4 style="font-size: 13px; font-weight: bold; color: #065f46; margin-bottom: 5px;">Mejor Mes de Captación</h4>
                                <p style="font-size: 12px; color: #047857;">
                                    <strong>{{ $mejorMes['mes'] }}</strong> con <strong>{{ $mejorMes['altas'] }}</strong> nuevos clientes
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($peorMes)
                    <div style="background: #fecaca; border-left: 4px solid #ef4444; padding: 15px; border-radius: 8px;">
                        <div style="display: flex; align-items: center;">
                            <div style="font-size: 24px; margin-right: 10px;">⚠️</div>
                            <div>
                                <h4 style="font-size: 13px; font-weight: bold; color: #991b1b; margin-bottom: 5px;">Mayor Pérdida de Clientes</h4>
                                <p style="font-size: 12px; color: #b91c1c;">
                                    <strong>{{ $peorMes['mes'] }}</strong> con <strong>{{ $peorMes['bajas'] }}</strong> bajas registradas
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Tabla Mensual Compacta -->
                <div style="margin-top: 30px;">
                    <h3 style="font-size: 16px; font-weight: bold; color: #374151; margin-bottom: 15px; padding-left: 10px; border-left: 4px solid #4f46e5;">Detalle Mensual</h3>
                    <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                        <thead>
                            <tr style="background: #f3f4f6;">
                                <th style="padding: 10px; text-align: left; border: 1px solid #e5e7eb; font-weight: 600; color: #374151;">Mes</th>
                                <th style="padding: 10px; text-align: center; border: 1px solid #e5e7eb; font-weight: 600; color: #374151; width: 100px;">Altas</th>
                                <th style="padding: 10px; text-align: center; border: 1px solid #e5e7eb; font-weight: 600; color: #374151; width: 100px;">Bajas</th>
                                <th style="padding: 10px; text-align: center; border: 1px solid #e5e7eb; font-weight: 600; color: #374151; width: 100px;">Neto</th>
                                <th style="padding: 10px; text-align: center; border: 1px solid #e5e7eb; font-weight: 600; color: #374151; width: 120px;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($meses as $mes)
                            @php $neto = $mes['altas'] - $mes['bajas']; @endphp
                            <tr>
                                <td style="padding: 8px; border: 1px solid #e5e7eb; font-weight: 600; color: #1f2937;">{{ $mes['mes'] }}</td>
                                <td style="padding: 8px; border: 1px solid #e5e7eb; text-align: center;">
                                    <span style="background: #d1fae5; color: #065f46; padding: 3px 10px; border-radius: 10px; font-weight: 600; font-size: 11px;">+{{ $mes['altas'] }}</span>
                                    @if($mes['reactivaciones'] > 0)
                                        <div style="font-size: 9px; color: #6b7280; margin-top: 2px;">
                                            (N:{{ $mes['nuevos'] }} / R:{{ $mes['reactivaciones'] }})
                                        </div>
                                    @endif
                                </td>
                                <td style="padding: 8px; border: 1px solid #e5e7eb; text-align: center;">
                                    <span style="background: #fecaca; color: #991b1b; padding: 3px 10px; border-radius: 10px; font-weight: 600; font-size: 11px;">-{{ $mes['bajas'] }}</span>
                                </td>
                                <td style="padding: 8px; border: 1px solid #e5e7eb; text-align: center; font-weight: bold; color: {{ $neto >= 0 ? '#059669' : '#dc2626' }}; font-size: 13px;">
                                    {{ $neto >= 0 ? '+' : '' }}{{ $neto }}
                                </td>
                                <td style="padding: 8px; border: 1px solid #e5e7eb; text-align: center; font-weight: bold; color: #4f46e5; font-size: 13px;">{{ $mes['total'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Footer -->
                <div style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #e5e7eb; text-align: center; color: #9ca3af; font-size: 12px;">
                    <p>Sistema de Gestión - Análisis de Crecimiento de Clientes</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- html2canvas para exportar PNG -->
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>

    <script>
        let chartExportInstance = null;

        // Función para crear la gráfica de exportación
        function crearGraficaExportacion() {
            const ctx = document.getElementById('chartExport');
            
            if (ctx && !chartExportInstance) {
                const datosGrafica = @json($datosGrafica);
                
                chartExportInstance = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: datosGrafica.labels,
                        datasets: [
                            {
                                label: 'Total Clientes',
                                data: datosGrafica.total,
                                borderColor: '#4f46e5',
                                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4,
                                pointRadius: 5,
                                pointHoverRadius: 7,
                                pointBackgroundColor: '#4f46e5',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2
                            },
                            {
                                label: 'Altas',
                                data: datosGrafica.altas,
                                borderColor: '#10b981',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                borderWidth: 2,
                                borderDash: [5, 5],
                                fill: false,
                                tension: 0.4,
                                pointRadius: 4,
                                pointHoverRadius: 6,
                                pointBackgroundColor: '#10b981'
                            },
                            {
                                label: 'Bajas',
                                data: datosGrafica.bajas,
                                borderColor: '#ef4444',
                                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                                borderWidth: 2,
                                borderDash: [5, 5],
                                fill: false,
                                tension: 0.4,
                                pointRadius: 4,
                                pointHoverRadius: 6,
                                pointBackgroundColor: '#ef4444'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    boxWidth: 15,
                                    padding: 15,
                                    font: { size: 13, weight: 'bold' }
                                }
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) label += ': ';
                                        label += context.parsed.y;
                                        return label;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1,
                                    font: { size: 11 }
                                },
                                grid: {
                                    color: '#e5e7eb'
                                }
                            },
                            x: {
                                ticks: {
                                    font: { size: 11 }
                                },
                                grid: {
                                    display: false
                                }
                            }
                        },
                        interaction: {
                            mode: 'nearest',
                            axis: 'x',
                            intersect: false
                        }
                    }
                });
            }
        }

        // Función para exportar a PNG
        function exportarPNG() {
            const exportView = document.getElementById('export-view');
            
            // Mostrar mensaje de carga
            const btn = event.target.closest('button');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Generando...';
            btn.disabled = true;
            
            // Mostrar la vista de exportación de manera visible pero fuera de pantalla
            exportView.style.display = 'block';
            exportView.style.position = 'fixed';
            exportView.style.left = '0';
            exportView.style.top = '-10000px';
            exportView.style.visibility = 'visible';
            exportView.style.opacity = '1';
            
            // Destruir gráfica anterior si existe
            if (chartExportInstance) {
                chartExportInstance.destroy();
                chartExportInstance = null;
            }
            
            // Pequeña pausa para que el DOM se actualice
            setTimeout(() => {
                // Crear la gráfica
                crearGraficaExportacion();
                
                // Esperar más tiempo para que la gráfica se renderice completamente
                setTimeout(() => {
                    html2canvas(exportView, {
                        scale: 2,
                        backgroundColor: '#ffffff',
                        logging: false,
                        useCORS: true,
                        width: 1200,
                        height: exportView.scrollHeight,
                        windowWidth: 1200,
                        allowTaint: false
                    }).then(canvas => {
                        // Crear enlace de descarga
                        const link = document.createElement('a');
                        const filename = 'reporte-crecimiento-clientes-{{ $anio }}-' + new Date().toISOString().split('T')[0] + '.png';
                        link.download = filename;
                        link.href = canvas.toDataURL('image/png');
                        link.click();
                        
                        // Ocultar la vista de exportación
                        exportView.style.display = 'none';
                        
                        // Restaurar botón
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    }).catch(error => {
                        console.error('Error al generar PNG:', error);
                        alert('Error al generar la imagen. Intenta nuevamente.');
                        exportView.style.display = 'none';
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    });
                }, 1000);
            }, 100);
        }
    </script>
</x-app-layout>
