<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📊 {{ __('Reporte de Cumplimiento y Puntualidad') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Filtro de Fechas -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <form method="GET" action="{{ route('reportes.cumplimiento') }}" class="flex flex-col md:flex-row items-end gap-4">
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
                                Filtrar Reporte
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabla de Resultados -->
            <div id="reporte-container" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Desempeño por Empleado</h3>
                        <span class="text-sm text-gray-500 block">
                            Rango: {{ $fechaInicio->format('d/m/Y') }} - {{ $fechaFin->format('d/m/Y') }}
                        </span>
                    </div>
                    
                    <button onclick="descargarJPG()" data-html2canvas-ignore="true" class="inline-flex items-center px-3 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Descargar JPG
                    </button>
                </div>

                @if($reporte->isEmpty())
                    <div class="text-center py-8 text-gray-500 bg-gray-50 rounded-lg">
                        No se encontraron empleados activos o datos para este periodo.
                    </div>
                @else
                    <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Empleado
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Total Tareas
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Entregadas
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            A Tiempo
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tardías
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Pendientes
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Cumplimiento
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Puntualidad
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($reporte as $row)
                                        <tr class="{{ $row['porcentaje_cumplimiento'] < 70 ? 'bg-red-50' : '' }}">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <span class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-500 font-bold">
                                                            {{ substr($row['nombre'], 0, 1) }}
                                                        </span>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">{{ $row['nombre'] }}</div>
                                                        <div class="text-sm text-gray-500">{{ $row['puesto'] }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900 font-bold">
                                                {{ $row['total'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                                {{ $row['entregadas'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-green-600 font-semibold">
                                                {{ $row['a_tiempo'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-yellow-600">
                                                {{ $row['tardias'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-red-600">
                                                {{ $row['pendientes'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <div class="flex items-center justify-center">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $row['porcentaje_cumplimiento'] >= 90 ? 'bg-green-100 text-green-800' : ($row['porcentaje_cumplimiento'] >= 70 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                        {{ $row['porcentaje_cumplimiento'] }}%
                                                    </span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2">
                                                    <div class="bg-indigo-600 h-1.5 rounded-full" style="width: {{ $row['porcentaje_cumplimiento'] }}%"></div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <div class="flex items-center justify-center">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $row['porcentaje_puntualidad'] >= 90 ? 'bg-green-100 text-green-800' : ($row['porcentaje_puntualidad'] >= 70 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                        {{ $row['porcentaje_puntualidad'] }}%
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        // Definir la función globalmente de inmediato
        window.descargarJPG = function() {
            const element = document.getElementById('reporte-container');
            
            // Notificar al usuario que espere
            const btn = document.querySelector('button[onclick="descargarJPG()"]');
            const originalText = btn.innerHTML;
            
            btn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Generando...';
            btn.disabled = true;

            const options = {
                scale: 2,
                backgroundColor: '#ffffff',
                useCORS: true,
                logging: false,
                windowWidth: element.scrollWidth,
                windowHeight: element.scrollHeight
            };

            html2canvas(element, options).then(canvas => {
                const link = document.createElement('a');
                link.download = 'reporte-cumplimiento-' + new Date().toISOString().slice(0,10) + '.jpg';
                link.href = canvas.toDataURL('image/jpeg', 0.9);
                link.click();
                
                btn.innerHTML = originalText;
                btn.disabled = false;
            }).catch(err => {
                console.error('Error al generar la imagen:', err);
                alert('Hubo un error al generar la imagen. Por favor intenta de nuevo.');
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        };
    </script>
</x-app-layout>
