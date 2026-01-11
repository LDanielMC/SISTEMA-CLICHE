<div class="kanban-card bg-white rounded-lg shadow-sm border border-gray-200 p-4 cursor-move hover:shadow-md transition"
     draggable="true"
     data-asignacion-id="{{ $asignacion->id }}">
    
    {{-- Prioridad --}}
    <div class="flex items-center justify-between mb-2">
        <span class="px-2 py-1 text-xs font-semibold rounded {{ $asignacion->prioridad_color }}">
            {{ $asignacion->prioridad_texto }}
        </span>
        @if($asignacion->estaVencida())
            <span class="px-2 py-1 text-xs font-semibold rounded bg-red-100 text-red-800">
                ⚠️ Vencida
            </span>
        @endif
    </div>

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
    <div class="flex items-center text-xs text-gray-500 mb-3">
        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
        </svg>
        Límite: {{ $asignacion->fecha_limite->format('d/m/Y') }}
    </div>

    @if(isset($esEmpleado) && $esEmpleado)
        {{-- Botón para subir evidencia --}}
        @if($asignacion->estado_empleado !== 'terminada')
            <button onclick="openEvidenciaModal({{ $asignacion->id }})"
                    class="w-full text-xs bg-blue-600 text-white px-3 py-2 rounded hover:bg-blue-700 transition">
                📎 Subir evidencia
            </button>
        @else
            <div class="text-xs text-green-600 font-semibold flex items-center justify-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                Evidencia entregada
            </div>
        @endif
    @else
        {{-- Info para admin --}}
        @if($asignacion->evidencia_path)
            <a href="{{ Storage::url($asignacion->evidencia_path) }}" target="_blank"
               class="text-xs text-blue-600 hover:underline flex items-center">
                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"/>
                </svg>
                Ver evidencia PDF
            </a>
        @else
            <p class="text-xs text-gray-400">Sin evidencia</p>
        @endif
    @endif
</div>

{{-- Modal para subir evidencia --}}
@if(isset($esEmpleado) && $esEmpleado)
    @once
    <div id="evidenciaModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Subir evidencia</h3>
                <form id="evidenciaForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Archivo PDF *</label>
                        <input type="file" name="evidencia" accept=".pdf" required
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="text-xs text-gray-500 mt-1">Máximo 10MB. Solo archivos PDF.</p>
                    </div>
                    <div class="flex gap-3 justify-end">
                        <button type="button" onclick="closeEvidenciaModal()" 
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-semibold text-sm transition">
                            Cancelar
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold text-sm transition">
                            Subir evidencia
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function openEvidenciaModal(asignacionId) {
            const modal = document.getElementById('evidenciaModal');
            const form = document.getElementById('evidenciaForm');
            form.action = `/mis-tareas/${asignacionId}/subir-evidencia`;
            modal.classList.remove('hidden');
        }

        function closeEvidenciaModal() {
            document.getElementById('evidenciaModal').classList.add('hidden');
        }

        document.getElementById('evidenciaModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeEvidenciaModal();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeEvidenciaModal();
            }
        });
    </script>
    @endpush
    @endonce
@endif
