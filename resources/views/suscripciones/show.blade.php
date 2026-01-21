<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle de Suscripción') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $suscripcion->nombre_servicio }}</h3>
                            <p class="text-sm text-gray-500 mt-1">{{ $suscripcion->categoria->nombre ?? 'Sin categoría' }}</p>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            @php
                                $diasRestantes = $suscripcion->dias_restantes;
                                $estadoBadge = $suscripcion->estado_badge;
                            @endphp
                            
                            @if($estadoBadge === 'vencida')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Vencida ({{ abs($diasRestantes) }} días)
                                </span>
                            @elseif($estadoBadge === 'por_vencer')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Por vencer ({{ $diasRestantes }} días)
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    OK ({{ $diasRestantes }} días)
                                </span>
                            @endif

                            @if($suscripcion->estatus === 'activo')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Activo
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Inactivo
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Fecha de Inicio</p>
                            <p class="mt-1 text-lg text-gray-900">{{ $suscripcion->fecha_inicio?->format('d/m/Y') ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Fecha de Vencimiento</p>
                            <p class="mt-1 text-lg text-gray-900">{{ $suscripcion->fecha_vencimiento?->format('d/m/Y') ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Costo</p>
                            <p class="mt-1 text-lg text-gray-900">${{ number_format($suscripcion->costo, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Periodicidad</p>
                            <p class="mt-1 text-lg text-gray-900 capitalize">{{ $suscripcion->periodicidad }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Nivel de Uso</p>
                            <p class="mt-1 text-lg text-gray-900 capitalize">{{ $suscripcion->nivel_uso }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Días de Recordatorio</p>
                            <p class="mt-1 text-lg text-gray-900">
                                @if(is_array($suscripcion->dias_recordatorio) && count($suscripcion->dias_recordatorio) > 0)
                                    {{ implode(', ', $suscripcion->dias_recordatorio) }}
                                @else
                                    No configurados
                                @endif
                            </p>
                        </div>
                    </div>

                    @if($suscripcion->observaciones)
                        <div class="mt-6">
                            <p class="text-sm font-medium text-gray-500">Observaciones</p>
                            <p class="mt-1 text-gray-900">{{ $suscripcion->observaciones }}</p>
                        </div>
                    @endif

                    <div class="flex items-center gap-3 mt-6 pt-6 border-t border-gray-200">
                        <a href="{{ route('suscripciones.edit', $suscripcion->idSuscripcion ?? $suscripcion) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Editar
                        </a>

                        @if($suscripcion->estatus === 'activo')
                            <a href="{{ route('suscripciones.renovar.form', $suscripcion->idSuscripcion ?? $suscripcion) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Renovar
                            </a>

                            <form method="POST" action="{{ route('suscripciones.destroy', $suscripcion->idSuscripcion ?? $suscripcion) }}" class="inline" onsubmit="return confirm('¿Está seguro de dar de baja esta suscripción?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Dar de Baja
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('suscripciones.reactivar', $suscripcion->idSuscripcion ?? $suscripcion) }}" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Reactivar
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('suscripciones.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Volver
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Historial de Renovaciones</h3>

                    @php
                        $renovaciones = $suscripcion->renovaciones ?? collect();
                    @endphp

                    @if($renovaciones->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Fecha Renovación
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Costo Ciclo
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Vencimiento Anterior
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Vencimiento Nuevo
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Observaciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($renovaciones as $renovacion)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $renovacion->fecha_renovacion?->format('d/m/Y') ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                ${{ number_format($renovacion->costo_ciclo, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $renovacion->fecha_vencimiento_anterior?->format('d/m/Y') ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $renovacion->fecha_vencimiento_nueva?->format('d/m/Y') ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                {{ $renovacion->observaciones ?? '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">Aún no hay renovaciones registradas</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
