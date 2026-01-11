<div class="kanban-card-admin bg-white rounded-lg shadow-sm border border-gray-200 p-4 cursor-move hover:shadow-md transition"
     draggable="true"
     data-asignacion-id="{{ $asignacion->id }}">
    
    {{-- Prioridad y Estado del Empleado --}}
    <div class="flex items-center justify-between mb-2">
        <span class="px-2 py-1 text-xs font-semibold rounded {{ $asignacion->prioridad_color }}">
            {{ $asignacion->prioridad_texto }}
        </span>
        <span class="px-2 py-1 text-xs font-semibold rounded 
            {{ $asignacion->estado_empleado === 'terminada' ? 'bg-green-100 text-green-800' : 
               ($asignacion->estado_empleado === 'en_proceso' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
            {{ ucfirst(str_replace('_', ' ', $asignacion->estado_empleado)) }}
        </span>
    </div>

    @if($asignacion->estaVencida())
        <div class="mb-2">
            <span class="px-2 py-1 text-xs font-semibold rounded bg-red-100 text-red-800">
                ⚠️ Vencida
            </span>
        </div>
    @endif

    {{-- Título de la tarea --}}
    <h4 class="font-bold text-gray-900 mb-2">{{ $asignacion->tarea->titulo }}</h4>

    {{-- Cliente y Categoría --}}
    <div class="text-xs text-gray-600 mb-2">
        <div class="flex items-center mb-1">
            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
            </svg>
            @if($asignacion->tarea->cliente)
                {{ $asignacion->tarea->cliente->empresa ?: $asignacion->tarea->cliente->nombre . ' ' . $asignacion->tarea->cliente->apellido_paterno }}
            @endif
        </div>
        <div class="flex items-center">
            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
            </svg>
            {{ $asignacion->tarea->categoria->nombre ?? 'Sin categoría' }}
        </div>
    </div>

    {{-- Fecha límite --}}
    <div class="flex items-center text-xs text-gray-500 mb-2">
        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
        </svg>
        Límite: {{ $asignacion->fecha_limite->format('d/m/Y') }}
    </div>

    {{-- Fecha de entrega --}}
    @if($asignacion->fecha_entrega)
        <div class="flex items-center text-xs text-green-600 mb-2">
            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            Entregada: {{ $asignacion->fecha_entrega->format('d/m/Y H:i') }}
        </div>
    @endif

    {{-- Evidencia --}}
    @if($asignacion->evidencia_path)
        <a href="{{ Storage::url($asignacion->evidencia_path) }}" target="_blank"
           class="block text-xs text-blue-600 hover:underline mb-2 flex items-center">
            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"/>
            </svg>
            📄 Ver evidencia PDF
        </a>
    @else
        <p class="text-xs text-gray-400 mb-2">Sin evidencia</p>
    @endif

    {{-- Notas del admin --}}
    @if($asignacion->notas_admin)
        <div class="mt-2 p-2 bg-gray-50 rounded text-xs text-gray-600">
            <strong>Notas:</strong> {{ $asignacion->notas_admin }}
        </div>
    @endif
</div>
