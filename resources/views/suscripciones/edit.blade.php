<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Suscripción') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('suscripciones.update', $suscripcion->idSuscripcion ?? $suscripcion) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="idCategoria" :value="__('Categoría')" />
                                <select id="idCategoria" name="idCategoria" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Seleccione una categoría</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->idCategoria }}" {{ (old('idCategoria', $suscripcion->idCategoria) == $categoria->idCategoria) ? 'selected' : '' }}>
                                            {{ $categoria->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('idCategoria')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="nombre_servicio" :value="__('Nombre del Servicio')" />
                                <x-text-input id="nombre_servicio" class="block mt-1 w-full" type="text" name="nombre_servicio" :value="old('nombre_servicio', $suscripcion->nombre_servicio)" required />
                                <x-input-error :messages="$errors->get('nombre_servicio')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="fecha_inicio" :value="__('Fecha de Inicio')" />
                                <x-text-input id="fecha_inicio" class="block mt-1 w-full" type="date" name="fecha_inicio" :value="old('fecha_inicio', $suscripcion->fecha_inicio?->format('Y-m-d') ?? $suscripcion->fecha_inicio)" required />
                                <x-input-error :messages="$errors->get('fecha_inicio')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="costo" :value="__('Costo')" />
                                <x-text-input id="costo" class="block mt-1 w-full" type="number" step="0.01" name="costo" :value="old('costo', $suscripcion->costo)" required />
                                <x-input-error :messages="$errors->get('costo')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="periodicidad" :value="__('Periodicidad')" />
                                <select id="periodicidad" name="periodicidad" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Seleccione periodicidad</option>
                                    <option value="mensual" {{ old('periodicidad', $suscripcion->periodicidad) == 'mensual' ? 'selected' : '' }}>Mensual</option>
                                    <option value="anual" {{ old('periodicidad', $suscripcion->periodicidad) == 'anual' ? 'selected' : '' }}>Anual</option>
                                </select>
                                <x-input-error :messages="$errors->get('periodicidad')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="nivel_uso" :value="__('Nivel de Uso')" />
                                <select id="nivel_uso" name="nivel_uso" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Seleccione nivel</option>
                                    <option value="bajo" {{ old('nivel_uso', $suscripcion->nivel_uso) == 'bajo' ? 'selected' : '' }}>Bajo</option>
                                    <option value="medio" {{ old('nivel_uso', $suscripcion->nivel_uso) == 'medio' ? 'selected' : '' }}>Medio</option>
                                    <option value="alto" {{ old('nivel_uso', $suscripcion->nivel_uso) == 'alto' ? 'selected' : '' }}>Alto</option>
                                </select>
                                <x-input-error :messages="$errors->get('nivel_uso')" class="mt-2" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label :value="__('Días de Recordatorio')" />
                                <p class="mt-1 text-sm text-gray-500 mb-3">Selecciona cuántos días antes del vencimiento deseas recibir recordatorios</p>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                    @php
                                        $diasComunes = [30, 15, 10, 7, 5, 3, 2, 1];
                                        $diasSeleccionados = old('dias_recordatorio', $suscripcion->dias_recordatorio ?? []);
                                        if (!is_array($diasSeleccionados)) {
                                            $diasSeleccionados = [];
                                        }
                                    @endphp
                                    @foreach($diasComunes as $dia)
                                        <label class="flex items-center space-x-2 cursor-pointer">
                                            <input type="checkbox" 
                                                   name="dias_recordatorio[]" 
                                                   value="{{ $dia }}"
                                                   {{ in_array($dia, $diasSeleccionados) ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                            <span class="text-sm text-gray-700">{{ $dia }} días</span>
                                        </label>
                                    @endforeach
                                </div>
                                <x-input-error :messages="$errors->get('dias_recordatorio')" class="mt-2" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="observaciones" :value="__('Observaciones')" />
                                <textarea id="observaciones" name="observaciones" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('observaciones', $suscripcion->observaciones) }}</textarea>
                                <x-input-error :messages="$errors->get('observaciones')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="estatus" :value="__('Estatus')" />
                                <select id="estatus" name="estatus" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="activo" {{ old('estatus', $suscripcion->estatus) == 'activo' ? 'selected' : '' }}>Activo</option>
                                    <option value="inactivo" {{ old('estatus', $suscripcion->estatus) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                                </select>
                                <x-input-error :messages="$errors->get('estatus')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6 gap-4">
                            <a href="{{ route('suscripciones.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancelar
                            </a>
                            <x-primary-button>
                                {{ __('Guardar Cambios') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
