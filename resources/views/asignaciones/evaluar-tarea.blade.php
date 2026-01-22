<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📋 Evaluar Tarea
            </h2>
            <a href="{{ route('asignaciones.index') }}" class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver a asignaciones
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 rounded-md bg-green-50 text-green-700 border border-green-200">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            <!-- Información de la Tarea -->
            <div class="bg-white shadow-sm sm:rounded-lg mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900">Información de la Tarea</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Título -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Título</label>
                            <p class="text-base font-semibold text-gray-900">{{ $asignacion->tarea->titulo }}</p>
                        </div>

                        <!-- Descripción -->
                        @if($asignacion->tarea->descripcion)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                            <p class="text-sm text-gray-600">{{ $asignacion->tarea->descripcion }}</p>
                        </div>
                        @endif

                        <!-- Empleado Asignado -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Empleado Asignado</label>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm font-medium text-gray-900">
                                    {{ $asignacion->empleado->nombre }} {{ $asignacion->empleado->apellido_paterno }}
                                </span>
                            </div>
                        </div>

                        <!-- Cliente -->
                        @if($asignacion->tarea->cliente)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cliente</label>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                </svg>
                                <span class="text-sm font-medium text-gray-900">
                                    {{ $asignacion->tarea->cliente->empresa ?: $asignacion->tarea->cliente->nombre . ' ' . $asignacion->tarea->cliente->apellido_paterno }}
                                </span>
                            </div>
                        </div>
                        @endif

                        <!-- Categoría -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $asignacion->tarea->categoria->nombre ?? 'Sin categoría' }}
                            </span>
                        </div>

                        <!-- Prioridad -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Prioridad</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $asignacion->prioridad_color }}">
                                {{ $asignacion->prioridad_texto }}
                            </span>
                        </div>

                        <!-- Fecha Límite -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Límite</label>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm {{ $asignacion->estaVencida() ? 'text-red-600 font-bold' : 'text-gray-900' }}">
                                    {{ $asignacion->fecha_limite->format('d/m/Y') }}
                                    @if($asignacion->estaVencida())
                                        <span class="ml-2 px-2 py-1 text-xs font-semibold rounded bg-red-100 text-red-800">⚠️ Vencida</span>
                                    @endif
                                </span>
                            </div>
                        </div>

                        <!-- Estado del Empleado -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Estado del Empleado</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                {{ $asignacion->estado_empleado === 'terminada' ? 'bg-green-100 text-green-800' :
                                   ($asignacion->estado_empleado === 'en_proceso' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ ucfirst(str_replace('_', ' ', $asignacion->estado_empleado)) }}
                            </span>
                        </div>

                        <!-- Fecha de Entrega -->
                        @if($asignacion->fecha_entrega)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Entrega</label>
                            <div class="flex items-center text-green-600">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm font-medium">{{ $asignacion->fecha_entrega->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                        @endif

                        <!-- Evidencia PDF -->
                        @if($asignacion->evidencia_path)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Evidencia</label>
                            <a href="{{ route('asignaciones.verEvidencia', $asignacion) }}" target="_blank"
                               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"/>
                                </svg>
                                📄 Ver Evidencia PDF
                            </a>
                        </div>
                        @else
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Evidencia</label>
                            <p class="text-sm text-gray-400 italic">Sin evidencia adjunta</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Formulario de Evaluación -->
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 bg-gradient-to-r from-indigo-50 to-purple-50 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900">Evaluación del Administrador</h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('asignaciones.actualizarEstadoAdmin', $asignacion) }}" method="POST">
                        @csrf
                        
                        <!-- Estado de Evaluación -->
                        <div class="mb-6">
                            <label for="estado_admin" class="block text-sm font-medium text-gray-700 mb-2">
                                Estado de Evaluación <span class="text-red-500">*</span>
                            </label>
                            <select name="estado_admin" 
                                    id="estado_admin" 
                                    required
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">-- Selecciona un estado --</option>
                                <option value="pendiente" {{ $asignacion->estado_admin === 'pendiente' ? 'selected' : '' }}>
                                    ⏳ Pendiente de revisión
                                </option>
                                <option value="completa" {{ $asignacion->estado_admin === 'completa' ? 'selected' : '' }}>
                                    ✅ Completa
                                </option>
                                <option value="parcialmente_completa" {{ $asignacion->estado_admin === 'parcialmente_completa' ? 'selected' : '' }}>
                                    🟡 Parcialmente completa
                                </option>
                                <option value="incompleta" {{ $asignacion->estado_admin === 'incompleta' ? 'selected' : '' }}>
                                    ❌ Incompleta
                                </option>
                            </select>
                            @error('estado_admin')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notas del Administrador -->
                        <div class="mb-6">
                            <label for="notas_admin" class="block text-sm font-medium text-gray-700 mb-2">
                                Notas y Comentarios
                            </label>
                            <textarea name="notas_admin" 
                                      id="notas_admin" 
                                      rows="4"
                                      placeholder="Escribe observaciones, comentarios o instrucciones adicionales para el empleado..."
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('notas_admin', $asignacion->notas_admin) }}</textarea>
                            @error('notas_admin')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notas anteriores (si existen) -->
                        @if($asignacion->notas_admin)
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Evaluación Anterior</h4>
                            <div class="flex items-start">
                                <svg class="w-5 h-5 mr-2 text-gray-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-600">{{ $asignacion->notas_admin }}</p>
                                    @if($asignacion->estado_admin)
                                    <p class="text-xs text-gray-500 mt-1">
                                        Estado: 
                                        <span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $asignacion->estado_admin)) }}</span>
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Botones de Acción -->
                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('asignaciones.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancelar
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Guardar Evaluación
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
