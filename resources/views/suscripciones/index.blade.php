<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-500 via-indigo-600 to-blue-500 flex items-center justify-center shadow-xl">
                    <svg class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div>
                    <h2 class="font-black text-3xl text-gray-900 leading-tight">
                        Suscripciones
                    </h2>
                    <p class="text-sm text-gray-500 font-medium mt-1">Administra las suscripciones de la empresa</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gradient-to-br from-gray-50 via-purple-50/20 to-blue-50/20 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Mensajes de éxito/error --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg">
                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                    <p class="text-red-700 font-medium">{{ session('error') }}</p>
                </div>
            @endif

            {{-- Botones de acción --}}
            <div class="mb-6 flex justify-end gap-3">
                <a href="{{ route('categorias-suscripcion.index') }}" 
                   class="inline-flex items-center space-x-2 px-6 py-3 bg-white border-2 border-gray-300 rounded-xl font-bold text-sm text-gray-700 shadow-lg hover:shadow-xl hover:scale-105 hover:border-purple-400 transition-all duration-300">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Configurar Categorías</span>
                </a>
                <a href="{{ route('suscripciones.create') }}" 
                   class="inline-flex items-center space-x-2 px-6 py-3 bg-gradient-to-r from-purple-500 to-indigo-600 border-2 border-purple-400/50 rounded-xl font-bold text-sm text-white shadow-xl hover:shadow-2xl hover:scale-105 transition-all duration-300">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Nueva Suscripción</span>
                </a>
            </div>

            {{-- Filtros --}}
            <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl p-6 border-2 border-gray-200/50 mb-6">
                <form method="GET" action="{{ route('suscripciones.index') }}">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        {{-- Búsqueda --}}
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">🔍 Buscar</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre del servicio..." 
                                   class="w-full border-2 border-gray-300 rounded-xl shadow-sm text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 py-3 transition-all">
                        </div>

                        {{-- Categoría --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">📂 Categoría</label>
                            <select name="categoria" class="w-full border-2 border-gray-300 rounded-xl shadow-sm text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 py-3 transition-all">
                                <option value="">Todas</option>
                                @foreach($categorias as $cat)
                                    <option value="{{ $cat->idCategoria }}" {{ request('categoria') == $cat->idCategoria ? 'selected' : '' }}>
                                        {{ $cat->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Periodicidad --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">📅 Periodicidad</label>
                            <select name="periodicidad" class="w-full border-2 border-gray-300 rounded-xl shadow-sm text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 py-3 transition-all">
                                <option value="">Todas</option>
                                <option value="mensual" {{ request('periodicidad') == 'mensual' ? 'selected' : '' }}>Mensual</option>
                                <option value="anual" {{ request('periodicidad') == 'anual' ? 'selected' : '' }}>Anual</option>
                            </select>
                        </div>

                        {{-- Estatus --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">📊 Estatus</label>
                            <select name="estatus" class="w-full border-2 border-gray-300 rounded-xl shadow-sm text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 py-3 transition-all">
                                <option value="">Todos</option>
                                <option value="activo" {{ request('estatus') == 'activo' ? 'selected' : '' }}>Activo</option>
                                <option value="inactivo" {{ request('estatus') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>

                        {{-- Nivel de Uso --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">⚡ Nivel de Uso</label>
                            <select name="nivel_uso" class="w-full border-2 border-gray-300 rounded-xl shadow-sm text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 py-3 transition-all">
                                <option value="">Todos</option>
                                <option value="bajo" {{ request('nivel_uso') == 'bajo' ? 'selected' : '' }}>Bajo</option>
                                <option value="medio" {{ request('nivel_uso') == 'medio' ? 'selected' : '' }}>Medio</option>
                                <option value="alto" {{ request('nivel_uso') == 'alto' ? 'selected' : '' }}>Alto</option>
                            </select>
                        </div>

                        {{-- Costo Mínimo --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">💰 Costo Mín</label>
                            <input type="number" name="costo_min" value="{{ request('costo_min') }}" step="0.01" placeholder="0.00" 
                                   class="w-full border-2 border-gray-300 rounded-xl shadow-sm text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 py-3 transition-all">
                        </div>

                        {{-- Costo Máximo --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">💰 Costo Máx</label>
                            <input type="number" name="costo_max" value="{{ request('costo_max') }}" step="0.01" placeholder="9999.99" 
                                   class="w-full border-2 border-gray-300 rounded-xl shadow-sm text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 py-3 transition-all">
                        </div>
                    </div>

                    {{-- Botones --}}
                    <div class="mt-4 flex justify-end space-x-3">
                        <a href="{{ route('suscripciones.index') }}" 
                           class="flex items-center space-x-2 px-4 py-2 text-sm font-semibold text-gray-600 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-all">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <span>Limpiar</span>
                        </a>
                        <button type="submit" 
                                class="flex items-center space-x-2 px-6 py-2 bg-gradient-to-r from-purple-500 to-indigo-600 text-white font-bold rounded-lg shadow-lg hover:shadow-xl transition-all">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <span>Filtrar</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Tabla de Suscripciones --}}
            <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl overflow-hidden border-2 border-gray-200/50">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-purple-500 to-indigo-600">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Servicio</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Categoría</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Periodicidad</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Costo</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Vencimiento</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Días Rest.</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Nivel Uso</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Estatus</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($suscripciones as $suscripcion)
                                <tr class="hover:bg-purple-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">{{ $suscripcion->nombre_servicio }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-600">{{ $suscripcion->categoria->nombre }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-bold rounded-full {{ $suscripcion->periodicidad == 'mensual' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                            {{ ucfirst($suscripcion->periodicidad) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">${{ number_format($suscripcion->costo, 2) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-600">{{ $suscripcion->fecha_vencimiento->format('d/m/Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold {{ $suscripcion->dias_restantes < 0 ? 'text-red-600' : ($suscripcion->dias_restantes <= 10 ? 'text-yellow-600' : 'text-green-600') }}">
                                            {{ $suscripcion->dias_restantes }} días
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-bold rounded-full 
                                            {{ $suscripcion->nivel_uso == 'bajo' ? 'bg-gray-100 text-gray-700' : '' }}
                                            {{ $suscripcion->nivel_uso == 'medio' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                            {{ $suscripcion->nivel_uso == 'alto' ? 'bg-red-100 text-red-700' : '' }}">
                                            {{ ucfirst($suscripcion->nivel_uso) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 text-xs font-bold rounded-full {{ $suscripcion->estado_badge['clase'] }}">
                                            {{ $suscripcion->estado_badge['texto'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-bold rounded-full {{ $suscripcion->estatus == 'activo' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                            {{ ucfirst($suscripcion->estatus) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('suscripciones.show', $suscripcion->idSuscripcion) }}" 
                                               class="text-indigo-600 hover:text-indigo-900" title="Ver detalles">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                            <a href="{{ route('suscripciones.edit', $suscripcion->idSuscripcion) }}" 
                                               class="text-yellow-600 hover:text-yellow-900" title="Editar">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            @if($suscripcion->estatus == 'activo')
                                                <a href="{{ route('suscripciones.renovar.form', $suscripcion->idSuscripcion) }}" 
                                                   class="text-green-600 hover:text-green-900" title="Renovar">
                                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                    </svg>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="px-6 py-12 text-center text-gray-500">
                                        No se encontraron suscripciones.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
