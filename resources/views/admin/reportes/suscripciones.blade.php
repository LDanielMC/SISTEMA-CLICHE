<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📊 {{ __('Reporte de Suscripciones y Costos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Acciones -->
            <div class="flex justify-end items-center mb-6">
            
            <form action="{{ route('reportes.suscripciones') }}" method="GET" class="flex gap-2">
                <!-- Mantener filtros actuales al exportar -->
                <input type="hidden" name="id_categoria" value="{{ request('id_categoria') }}">
                <input type="hidden" name="periodicidad" value="{{ request('periodicidad') }}">
                <input type="hidden" name="nivel_uso" value="{{ request('nivel_uso') }}">
                <input type="hidden" name="orden" value="{{ request('orden') }}">
                <input type="hidden" name="direccion" value="{{ request('direccion') }}">
                <input type="hidden" name="export" value="pdf">
                
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Exportar PDF
                </button>
            </form>
        </div>

        <!-- Tarjetas de Resumen - KPIs Clave -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
            <!-- Proyección Anual -->
            <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 overflow-hidden shadow-lg sm:rounded-lg p-5 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs font-medium uppercase opacity-90">Proyección Anual</div>
                        <div class="mt-2 text-2xl font-bold">${{ number_format($proyeccionAnual, 0) }}</div>
                    </div>
                    <div class="text-3xl opacity-80">📊</div>
                </div>
            </div>
            
            <!-- Total Suscripciones -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs font-medium uppercase text-gray-500">Suscripciones</div>
                        <div class="mt-2 text-2xl font-bold text-gray-900">{{ $suscripciones->count() }}</div>
                    </div>
                    <div class="text-3xl">📦</div>
                </div>
            </div>

            <!-- Alertas Críticas -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 border-l-4 {{ $vencenEn7Dias > 0 ? 'border-red-500' : 'border-green-500' }}">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs font-medium uppercase text-gray-500">Crítico (7 días)</div>
                        <div class="mt-2 text-2xl font-bold {{ $vencenEn7Dias > 0 ? 'text-red-600' : 'text-green-600' }}">{{ $vencenEn7Dias }}</div>
                    </div>
                    <div class="text-3xl">{{ $vencenEn7Dias > 0 ? '⚠️' : '✅' }}</div>
                </div>
            </div>

            <!-- Por Vencer (30 días) -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 border-l-4 {{ $vencenEn30Dias > 0 ? 'border-yellow-500' : 'border-green-500' }}">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs font-medium uppercase text-gray-500">Por Vencer (30d)</div>
                        <div class="mt-2 text-2xl font-bold {{ $vencenEn30Dias > 0 ? 'text-yellow-600' : 'text-green-600' }}">{{ $vencenEn30Dias }}</div>
                    </div>
                    <div class="text-3xl">🔔</div>
                </div>
            </div>

            <!-- A Optimizar -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 border-l-4 {{ $aOptimizar > 0 ? 'border-orange-500' : 'border-gray-300' }}">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs font-medium uppercase text-gray-500">A Optimizar</div>
                        <div class="mt-2 text-2xl font-bold {{ $aOptimizar > 0 ? 'text-orange-600' : 'text-gray-600' }}">{{ $aOptimizar }}</div>
                    </div>
                    <div class="text-3xl">💡</div>
                </div>
                <div class="text-xs text-gray-400 mt-1">Bajo uso, costo alto</div>
            </div>

            <!-- Promedio Costo -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs font-medium uppercase text-gray-500">Costo Promedio</div>
                        <div class="mt-2 text-2xl font-bold text-gray-900">${{ number_format($promedioCosto, 0) }}</div>
                    </div>
                    <div class="text-3xl">💰</div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            
            <!-- Columna Izquierda: Filtros -->
            <div class="lg:col-span-1">
                <div class="bg-white shadow sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Filtros</h3>
                    <form action="{{ route('reportes.suscripciones') }}" method="GET">
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                            <select name="id_categoria" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Todas</option>
                                @foreach($categorias as $cat)
                                    <option value="{{ $cat->idCategoria }}" {{ request('id_categoria') == $cat->idCategoria ? 'selected' : '' }}>
                                        {{ $cat->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Periodicidad</label>
                            <select name="periodicidad" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Todas</option>
                                <option value="mensual" {{ request('periodicidad') == 'mensual' ? 'selected' : '' }}>Mensual</option>
                                <option value="anual" {{ request('periodicidad') == 'anual' ? 'selected' : '' }}>Anual</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nivel de Uso</label>
                            <select name="nivel_uso" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Todos</option>
                                <option value="alto" {{ request('nivel_uso') == 'alto' ? 'selected' : '' }}>Alto</option>
                                <option value="medio" {{ request('nivel_uso') == 'medio' ? 'selected' : '' }}>Medio</option>
                                <option value="bajo" {{ request('nivel_uso') == 'bajo' ? 'selected' : '' }}>Bajo</option>
                            </select>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ordenar por</label>
                            <div class="flex gap-2">
                                <select name="orden" class="w-2/3 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="dias_restantes" {{ request('orden') == 'dias_restantes' ? 'selected' : '' }}>Días Restantes</option>
                                    <option value="costo" {{ request('orden') == 'costo' ? 'selected' : '' }}>Costo</option>
                                    <option value="fecha_vencimiento" {{ request('orden') == 'fecha_vencimiento' ? 'selected' : '' }}>Vencimiento</option>
                                    <option value="nombre_servicio" {{ request('orden') == 'nombre_servicio' ? 'selected' : '' }}>Nombre</option>
                                </select>
                                <select name="direccion" class="w-1/3 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="asc" {{ request('direccion') == 'asc' ? 'selected' : '' }}>Asc</option>
                                    <option value="desc" {{ request('direccion') == 'desc' ? 'selected' : '' }}>Desc</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('reportes.suscripciones') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 text-sm">Limpiar</a>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm">Filtrar</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Columna Derecha: Tabla -->
            <div class="lg:col-span-3">
                <div class="bg-white shadow sm:rounded-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Servicio</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Costo</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vencimiento</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Uso</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($suscripciones as $sub)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $sub->nombre_servicio }}</div>
                                            <div class="text-sm text-gray-500">{{ $sub->categoria->nombre ?? 'Sin categoría' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">${{ number_format($sub->costo, 2) }}</div>
                                            <div class="text-xs text-gray-500 capitalize">{{ $sub->periodicidad }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $sub->fecha_vencimiento ? $sub->fecha_vencimiento->format('d/m/Y') : 'N/A' }}</div>
                                            <div class="text-xs {{ $sub->dias_restantes < 0 ? 'text-red-600 font-bold' : ($sub->dias_restantes <= 7 ? 'text-yellow-600 font-bold' : 'text-gray-500') }}">
                                                @if($sub->dias_restantes === null)
                                                    -
                                                @elseif($sub->dias_restantes < 0)
                                                    Venció hace {{ abs($sub->dias_restantes) }} días
                                                @elseif($sub->dias_restantes == 0)
                                                    Vence hoy
                                                @else
                                                    Vence en {{ $sub->dias_restantes }} días
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @php $badge = $sub->estado_badge; @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $badge['clase'] }}">
                                                {{ $badge['texto'] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $sub->nivel_uso === 'alto' ? 'bg-green-100 text-green-800' : 
                                                   ($sub->nivel_uso === 'medio' ? 'bg-blue-100 text-blue-800' : 
                                                   'bg-gray-100 text-gray-800') }}">
                                                {{ ucfirst($sub->nivel_uso) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                            No se encontraron suscripciones con los filtros seleccionados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

        </div>
    </div>
</x-app-layout>
