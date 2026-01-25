<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📊 {{ __('Reporte de Carga de Trabajo por Empleado y Cliente') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Filtro de Fechas -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <form method="GET" action="{{ route('reportes.carga_trabajo') }}" class="flex flex-col md:flex-row items-end gap-4">
                        <div>
                            <label for="fecha_inicio" class="block text-sm font-medium text-gray-700">Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio" 
                                   value="{{ $fechaInicio->format('Y-m-d') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="fecha_fin" class="block text-sm font-medium text-gray-700">Fecha Fin</label>
                            <input type="date" name="fecha_fin" id="fecha_fin" 
                                   value="{{ $fechaFin->format('Y-m-d') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div class="flex-shrink-0">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                                Filtrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Contenedor del Reporte (Vista en Pantalla) -->
            <div id="reporte-container" class="space-y-6">
                
                <!-- Encabezado -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Carga de Trabajo</h3>
                            <span class="text-sm text-gray-500 block">
                                Rango: {{ $fechaInicio->format('d/m/Y') }} - {{ $fechaFin->format('d/m/Y') }}
                            </span>
                        </div>
                        
                        <button onclick="descargarPNG()" data-html2canvas-ignore="true" class="inline-flex items-center px-3 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Descargar PNG
                        </button>
                    </div>
                </div>

                <!-- Detalle por Empleado -->
                @if($reporte->isEmpty())
                    <div class="text-center py-8 text-gray-500 bg-gray-50 rounded-lg">
                        No hay asignaciones de tareas registradas en este periodo.
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-6">
                        @foreach($reporte as $empleado)
                            <div class="border rounded-lg shadow-sm overflow-hidden">
                                <div class="bg-gray-50 px-4 py-3 border-b flex justify-between items-center">
                                    <div class="flex items-center w-full md:w-auto">
                                        <div class="flex-shrink-0 h-12 w-12 rounded-full bg-white border-2 border-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xl shadow-sm">
                                            {{ substr($empleado['empleado_nombre'], 0, 1) }}
                                        </div>
                                        <div class="ml-4">
                                            <h4 class="text-lg font-bold text-gray-900">{{ $empleado['empleado_nombre'] }}</h4>
                                            <p class="text-sm text-gray-500">{{ $empleado['puesto'] }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex gap-4 w-full md:w-auto justify-end">
                                        <div class="text-center px-4">
                                            <span class="block text-xl font-bold text-gray-800">{{ $empleado['total_tareas'] }}</span>
                                            <span class="text-xs text-gray-500 uppercase font-bold">Total</span>
                                        </div>
                                        <div class="text-center px-4 border-l border-gray-200">
                                            <span class="block text-xl font-bold text-green-600">{{ $empleado['terminadas'] }}</span>
                                            <span class="text-xs text-gray-500 uppercase font-bold">Terminadas</span>
                                        </div>
                                        <div class="text-center px-4 border-l border-gray-200">
                                            <span class="block text-xl font-bold text-orange-500">{{ $empleado['pendientes'] }}</span>
                                            <span class="text-xs text-gray-500 uppercase font-bold">Pendientes</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="p-6 bg-white">
                                    <h5 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b pb-2">Distribución por Cliente</h5>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                                        @foreach($empleado['clientes_detalle'] as $cliente)
                                            <div class="relative group">
                                                <div class="flex justify-between items-center mb-1 text-sm">
                                                    <span class="font-medium text-gray-700 truncate pr-2" title="{{ $cliente['cliente_nombre'] }}">{{ $cliente['cliente_nombre'] }}</span>
                                                    <span class="font-bold text-gray-900 flex-shrink-0">{{ $cliente['cantidad_tareas'] }} <span class="text-gray-400 text-xs font-normal">tareas</span></span>
                                                </div>
                                                <div class="overflow-hidden h-2.5 text-xs flex rounded-full bg-gray-100">
                                                    @php
                                                        $porcentaje = ($cliente['cantidad_tareas'] / $empleado['total_tareas']) * 100;
                                                        $colorBarra = $porcentaje > 50 ? 'bg-indigo-600' : ($porcentaje > 25 ? 'bg-indigo-400' : 'bg-indigo-300');
                                                    @endphp
                                                    <div style="width: {{ $porcentaje }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center {{ $colorBarra }} transition-all duration-500"></div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Datos para la gráfica
            const labels = @json($reporte->pluck('empleado_nombre'));
            const data = @json($reporte->pluck('total_tareas'));
            const colors = [
                '#4f46e5', '#2563eb', '#7c3aed', '#db2777', '#dc2626', 
                '#ea580c', '#d97706', '#65a30d', '#16a34a', '#059669'
            ];

            const ctx = document.getElementById('workloadChart').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: colors.slice(0, data.length),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    animation: false, // Desactivar animación para captura correcta
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                font: {
                                    size: 14
                                }
                            }
                        },
                        title: {
                            display: true,
                            text: 'Distribución de Tareas por Empleado',
                            font: {
                                size: 16
                            }
                        }
                    }
                }
            });
        });

        window.descargarPNG = function() {
            const element = document.getElementById('export-view');
            const btn = document.querySelector('button[onclick="descargarPNG()"]');
            const originalText = btn.innerHTML;
            
            btn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Generando...';
            btn.disabled = true;

            html2canvas(element, {
                scale: 2,
                backgroundColor: '#ffffff',
                useCORS: true,
                logging: false,
                onclone: (clonedDoc) => {
                    // Asegurar que el elemento clonado sea visible para la captura
                    const exportDiv = clonedDoc.getElementById('export-view');
                    exportDiv.style.display = 'block';
                    exportDiv.style.position = 'static';
                    exportDiv.style.top = 'auto';
                    exportDiv.style.left = 'auto';
                }
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = 'reporte-carga-trabajo-completo-' + new Date().toISOString().slice(0,10) + '.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
                
                btn.innerHTML = originalText;
                btn.disabled = false;
            }).catch(err => {
                console.error('Error:', err);
                alert('Error al generar la imagen.');
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        };
    </script>

    <!-- Contenedor Oculto para Exportación -->
    <div id="export-view" style="position: absolute; top: -9999px; left: -9999px; width: 1200px; padding: 40px; background-color: white;">
        <div class="mb-8 border-b pb-4">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Reporte de Carga de Trabajo</h1>
            <p class="text-gray-500">Generado el: {{ now()->format('d/m/Y H:i') }} | Rango: {{ $fechaInicio->format('d/m/Y') }} - {{ $fechaFin->format('d/m/Y') }}</p>
        </div>

        <!-- KPIs Resumen -->
        <div class="grid grid-cols-4 gap-6 mb-8">
            <div class="bg-indigo-50 p-6 rounded-xl border border-indigo-100 text-center flex flex-col justify-center">
                <h4 class="text-sm font-bold text-indigo-500 uppercase mb-2">Total Tareas</h4>
                <p class="text-4xl font-extrabold text-indigo-700 leading-tight">{{ $totalTareasGlobal }}</p>
            </div>
            <div class="bg-blue-50 p-6 rounded-xl border border-blue-100 text-center flex flex-col justify-center">
                <h4 class="text-sm font-bold text-blue-500 uppercase mb-2">Promedio / Empleado</h4>
                <p class="text-4xl font-extrabold text-blue-700 leading-tight">{{ $promedioTareas }}</p>
            </div>
            <div class="bg-green-50 p-6 rounded-xl border border-green-100 text-center flex flex-col justify-center">
                <h4 class="text-sm font-bold text-green-600 uppercase mb-2">Top Empleado</h4>
                @if($topEmpleado)
                    <p class="text-xl font-bold text-green-800 break-words leading-snug mb-1">{{ $topEmpleado['empleado_nombre'] }}</p>
                    <span class="text-sm font-semibold text-green-600 block">{{ $topEmpleado['total_tareas'] }} tareas</span>
                @else
                    <p class="text-xl font-bold text-gray-400">-</p>
                @endif
            </div>
            <div class="bg-purple-50 p-6 rounded-xl border border-purple-100 text-center flex flex-col justify-center">
                <h4 class="text-sm font-bold text-purple-600 uppercase mb-2">Top Cliente</h4>
                @if($topCliente)
                    <p class="text-xl font-bold text-purple-800 break-words leading-snug mb-1">{{ $topCliente['nombre'] }}</p>
                    <span class="text-sm font-semibold text-purple-600 block">{{ $topCliente['total'] }} tareas</span>
                @else
                    <p class="text-xl font-bold text-gray-400">-</p>
                @endif
            </div>
        </div>

        <!-- Sección Gráfica y Tabla Resumen -->
        <div class="flex gap-8 mb-8">
            <!-- Gráfica -->
            <div class="w-1/3 bg-white border rounded-xl p-6 shadow-sm flex items-center justify-center" style="height: 350px;">
                <canvas id="workloadChart" style="max-height: 100%; max-width: 100%;"></canvas>
            </div>
            
            <!-- Tabla Resumida -->
            <div class="w-2/3 bg-white border rounded-xl overflow-hidden shadow-sm">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Empleado</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Terminadas</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Pendientes</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente Principal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($reporte as $emp)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ $emp['empleado_nombre'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900 font-bold">{{ $emp['total_tareas'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-green-600">{{ $emp['terminadas'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-orange-500">{{ $emp['pendientes'] }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 break-words">
                                    @if(count($emp['clientes_detalle']) > 0)
                                        {{ $emp['clientes_detalle'][0]['cliente_nombre'] }} ({{ $emp['clientes_detalle'][0]['cantidad_tareas'] }})
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Detalle Extendido (Solo las primeras 5 filas de clientes por empleado para no hacer la imagen infinita) -->
        <div>
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-l-4 border-indigo-500 pl-3">Detalle de Distribución por Cliente</h3>
            <div class="grid grid-cols-2 gap-6">
                @foreach($reporte as $empleado)
                    <div class="border rounded-lg bg-gray-50 p-4 break-inside-avoid">
                        <div class="flex justify-between items-center mb-3">
                            <h4 class="font-bold text-gray-900">{{ $empleado['empleado_nombre'] }}</h4>
                            <span class="text-xs bg-indigo-100 text-indigo-800 px-2 py-1 rounded-full font-bold">{{ $empleado['total_tareas'] }} Tareas</span>
                        </div>
                        <ul class="space-y-2">
                            @foreach($empleado['clientes_detalle'] as $index => $cliente)
                                @if($index < 5)
                                    <li class="text-sm flex justify-between items-start">
                                        <span class="text-gray-600 break-words w-3/4">{{ $cliente['cliente_nombre'] }}</span>
                                        <span class="font-semibold text-gray-900">{{ $cliente['cantidad_tareas'] }}</span>
                                    </li>
                                @elseif($index == 5)
                                    <li class="text-xs text-gray-400 text-center mt-1">+ {{ count($empleado['clientes_detalle']) - 5 }} clientes más...</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
        
        <div class="mt-8 text-center text-xs text-gray-400 border-t pt-4">
            Reporte generado automáticamente por Sistema Cliché
        </div>
    </div>
</x-app-layout>
