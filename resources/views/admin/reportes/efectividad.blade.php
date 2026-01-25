<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📊 {{ __('Reporte de Efectividad e Ingresos por Cotizaciones') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Filtros -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <form method="GET" action="{{ route('reportes.efectividad') }}" class="flex flex-col md:flex-row items-end gap-4">
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
                        <div class="flex-1 min-w-[200px]">
                            <label for="id_cliente" class="block text-sm font-medium text-gray-700">Cliente (Opcional)</label>
                            <select name="id_cliente" id="id_cliente" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Todos los clientes</option>
                                @foreach($listaClientes as $c)
                                    <option value="{{ $c->id_cliente }}" {{ $idCliente == $c->id_cliente ? 'selected' : '' }}>
                                        {{ $c->nombre }} {{ $c->apellido_paterno }} {{ $c->empresa ? '('.$c->empresa.')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-shrink-0 flex gap-2">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                                Filtrar
                            </button>
                            
                            <button type="submit" name="export" value="excel" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                Exportar Excel
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabla de Resultados -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Efectividad por Cliente</h3>
                        <span class="text-sm text-gray-500 block">
                            Rango: {{ $fechaInicio->format('d/m/Y') }} - {{ $fechaFin->format('d/m/Y') }}
                        </span>
                    </div>
                </div>

                @if($reporte->isEmpty())
                    <div class="text-center py-8 text-gray-500 bg-gray-50 rounded-lg">
                        No se encontraron cotizaciones para este periodo.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Cliente
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Cotizaciones Emitidas
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Cotizaciones Aceptadas
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Efectividad
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Monto Emitido
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Monto Aceptado
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($reporte as $row)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $row['cliente'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                            {{ $row['emitidas'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                            {{ $row['aceptadas'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="flex items-center justify-center">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $row['efectividad'] >= 50 ? 'bg-green-100 text-green-800' : ($row['efectividad'] > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                                    {{ $row['efectividad'] }}%
                                                </span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2">
                                                <div class="bg-indigo-600 h-1.5 rounded-full" style="width: {{ $row['efectividad'] }}%"></div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                                            ${{ number_format($row['monto_emitido'], 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-green-600">
                                            ${{ number_format($row['monto_aceptado'], 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <th scope="row" class="px-6 py-3 text-left text-xs font-bold text-gray-900 uppercase tracking-wider">
                                        Totales
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-900">
                                        {{ $reporte->sum('emitidas') }}
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-900">
                                        {{ $reporte->sum('aceptadas') }}
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-900">
                                        {{ $reporte->sum('emitidas') > 0 ? round(($reporte->sum('aceptadas') / $reporte->sum('emitidas')) * 100, 1) : 0 }}%
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-900">
                                        ${{ number_format($reporte->sum('monto_emitido'), 2) }}
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-bold text-green-600">
                                        ${{ number_format($reporte->sum('monto_aceptado'), 2) }}
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
