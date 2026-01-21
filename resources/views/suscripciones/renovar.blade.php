<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Renovar Suscripción') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
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
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Información de la Suscripción</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Servicio</p>
                            <p class="mt-1 text-gray-900">{{ $suscripcion->nombre_servicio }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Categoría</p>
                            <p class="mt-1 text-gray-900">{{ $suscripcion->categoria->nombre ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Fecha de Vencimiento Actual</p>
                            <p class="mt-1 text-gray-900">{{ $suscripcion->fecha_vencimiento?->format('d/m/Y') ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Costo Actual</p>
                            <p class="mt-1 text-gray-900">${{ number_format($suscripcion->costo, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Periodicidad</p>
                            <p class="mt-1 text-gray-900 capitalize">{{ $suscripcion->periodicidad }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Días Restantes</p>
                            <p class="mt-1 text-gray-900">{{ $suscripcion->dias_restantes ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Datos de Renovación</h3>
                    
                    <form method="POST" action="{{ route('suscripciones.renovar', $suscripcion->idSuscripcion ?? $suscripcion) }}">
                        @csrf

                        <div class="space-y-6">
                            <div>
                                <x-input-label for="fecha_renovacion" :value="__('Fecha de Renovación')" />
                                <x-text-input id="fecha_renovacion" class="block mt-1 w-full" type="date" name="fecha_renovacion" :value="old('fecha_renovacion', date('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('fecha_renovacion')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="costo_ciclo" :value="__('Costo del Ciclo')" />
                                <x-text-input id="costo_ciclo" class="block mt-1 w-full" type="number" step="0.01" name="costo_ciclo" :value="old('costo_ciclo', $suscripcion->costo)" required />
                                <x-input-error :messages="$errors->get('costo_ciclo')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="observaciones" :value="__('Observaciones')" />
                                <textarea id="observaciones" name="observaciones" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('observaciones') }}</textarea>
                                <x-input-error :messages="$errors->get('observaciones')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">Importante</h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <p>Al confirmar la renovación:</p>
                                        <ul class="list-disc list-inside mt-1">
                                            <li>Se actualizará la fecha de vencimiento según la periodicidad ({{ $suscripcion->periodicidad }})</li>
                                            <li>Se creará un registro en el historial de renovaciones</li>
                                            <li>Esta acción no se puede deshacer</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6 gap-4">
                            <a href="{{ route('suscripciones.show', $suscripcion->idSuscripcion ?? $suscripcion) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancelar
                            </a>
                            <x-primary-button>
                                {{ __('Confirmar Renovación') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
