{{-- resources/views/asignaciones/_tarjeta-tarea.blade.php --}}

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
        @if($asignacion->estado_empleado === 'terminada' && $asignacion->fecha_entrega && $asignacion->fecha_limite && $asignacion->fecha_entrega->gt($asignacion->fecha_limite))
            <span class="px-2 py-1 text-xs font-semibold rounded bg-orange-100 text-orange-800">
                🕐 Entregada con retraso
            </span>
        @endif
    </div>

    {{-- Título de la tarea --}}
    <h4 class="font-bold text-gray-900 mb-2">{{ $asignacion->tarea->titulo }}</h4>
    
    {{-- Botón para ver detalles --}}
    <button type="button" 
            onclick="openDetallesTareaModal({{ $asignacion->id }})" 
            class="text-xs text-blue-600 hover:text-blue-800 underline mb-2">
        ℹ️ Ver detalles completos
    </button>

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

    {{-- =========================
         BLOQUE EMPLEADO
         ========================= --}}
    @if(isset($esEmpleado) && $esEmpleado)

        {{-- Botón para subir evidencia --}}
        @if($asignacion->estado_empleado !== 'terminada')
            <button type="button"
                    onclick="openEvidenciaModal({{ $asignacion->id }})"
                    class="w-full text-xs bg-blue-600 text-white px-3 py-2 rounded hover:bg-blue-700 transition">
                📎 Subir evidencia
            </button>
        @else
            <div class="space-y-2">
                <div class="text-xs text-green-600 font-semibold flex items-center justify-center">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Evidencia entregada
                </div>

                {{-- Botones para ver y eliminar evidencia --}}
                <div class="flex gap-2">
                    {{-- ✅ LINK SEGURO (NO Storage::url) --}}
                    <a href="{{ route('asignaciones.verEvidencia', $asignacion) }}" target="_blank"
                       class="flex-1 text-xs bg-blue-100 text-blue-700 px-3 py-2 rounded hover:bg-blue-200 transition text-center">
                        👁️ Ver PDF
                    </a>

                    <form id="form-eliminar-{{ $asignacion->id }}"
                          method="POST"
                          action="{{ route('asignaciones.eliminarEvidencia', $asignacion) }}"
                          style="display: inline; width: 50%;">
                        @csrf
                        @method('DELETE')
                        <button type="button"
                                onclick="confirmarEliminarEvidencia({{ $asignacion->id }})"
                                class="w-full text-xs bg-red-100 text-red-700 px-3 py-2 rounded hover:bg-red-200 transition">
                            🗑️ Eliminar
                        </button>
                    </form>
                </div>
            </div>
        @endif

    {{-- =========================
         BLOQUE ADMIN
         ========================= --}}
    @else
        @if($asignacion->evidencia_path)
            {{-- ✅ LINK SEGURO (NO Storage::url) --}}
            <a href="{{ route('asignaciones.verEvidencia', $asignacion) }}" target="_blank"
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
                        <input type="file" name="evidencia" id="evidenciaInput" accept=".pdf" required
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="text-xs text-gray-500 mt-1">Máximo 10MB. Solo archivos PDF.</p>
                    </div>
                    <div class="flex gap-3 justify-end">
                        <button type="button" onclick="closeEvidenciaModal()"
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-semibold text-sm transition">
                            Cancelar
                        </button>
                        <button type="button" onclick="mostrarConfirmacionSubir()"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold text-sm transition">
                            Subir evidencia
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal de confirmación para subir evidencia --}}
    <div id="confirmarSubirModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-75 overflow-y-auto h-full w-full z-[60]">
        <div class="relative top-1/2 -translate-y-1/2 mx-auto p-6 border w-96 shadow-2xl rounded-xl bg-white">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 mb-4">
                    <svg class="h-10 w-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">¿Confirmar subida de evidencia?</h3>
                <p class="text-sm text-gray-600 mb-2">
                    Archivo seleccionado: <span id="nombreArchivo" class="font-semibold text-gray-900"></span>
                </p>
                <p class="text-sm text-gray-500 mb-6">
                    Al subir la evidencia, la tarea se marcará automáticamente como <strong>Terminada</strong>.
                </p>
                <div class="flex gap-3 justify-center">
                    <button type="button" onclick="cerrarConfirmacionSubir()"
                            class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-semibold text-sm transition">
                        Cancelar
                    </button>
                    <button type="button" onclick="confirmarSubirEvidencia()"
                            class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold text-sm transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Confirmar y subir
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal de confirmación para eliminar evidencia --}}
    <div id="confirmarEliminarModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-75 overflow-y-auto h-full w-full z-[60]">
        <div class="relative top-1/2 -translate-y-1/2 mx-auto p-6 border w-96 shadow-2xl rounded-xl bg-white">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                    <svg class="h-10 w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">¿Eliminar evidencia?</h3>
                <p class="text-sm text-gray-600 mb-6">
                    Esta acción eliminará el archivo PDF y moverá la tarea a <strong class="text-yellow-600">"En Proceso"</strong>.<br>
                    Podrás subir una nueva evidencia después.
                </p>
                <div class="flex gap-3 justify-center">
                    <button type="button" onclick="cerrarConfirmacionEliminar()"
                            class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-semibold text-sm transition">
                        Cancelar
                    </button>
                    <button type="button" onclick="confirmarEliminarEvidenciaFinal()"
                            class="px-6 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold text-sm transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Sí, eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal de detalles de tarea --}}
    <div id="detallesTareaModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-75 overflow-y-auto h-full w-full z-[60]">
        <div class="relative top-20 mx-auto p-6 border w-full max-w-2xl shadow-2xl rounded-xl bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900">📋 Detalles de la tarea</h3>
                <button onclick="closeDetallesTareaModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div id="detallesTareaContent" class="space-y-4">
                {{-- Contenido dinámico cargado por JS --}}
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let currentAsignacionId = null;
        
        // Datos de tareas para el modal (inyectados desde Blade)
        const tareasData = {
            @foreach(collect([$asignadas ?? collect(), $enProceso ?? collect(), $terminadas ?? collect()])->flatten()->unique('id') as $a)
            {{ $a->id }}: {
                titulo: {!! json_encode($a->tarea->titulo) !!},
                descripcion: {!! json_encode($a->tarea->descripcion ?? 'Sin descripción') !!},
                observaciones: {!! json_encode($a->tarea->observaciones ?? 'Sin observaciones') !!},
                cliente: {!! json_encode($a->tarea->cliente ? ($a->tarea->cliente->empresa ?: $a->tarea->cliente->nombre . ' ' . $a->tarea->cliente->apellido_paterno) : 'Sin cliente') !!},
                categoria: {!! json_encode($a->tarea->categoria->nombre ?? 'Sin categoría') !!},
                prioridad: {!! json_encode($a->prioridad_texto) !!},
                prioridadColor: {!! json_encode($a->prioridad_color) !!},
                fechaLimite: {!! json_encode($a->fecha_limite->format('d/m/Y')) !!},
                fechaEntrega: {!! json_encode($a->fecha_entrega ? $a->fecha_entrega->format('d/m/Y H:i') : null) !!},
                estadoEmpleado: {!! json_encode($a->estado_empleado) !!},
                estadoAdmin: {!! json_encode($a->estado_admin ?? 'Pendiente') !!},
                tieneEvidencia: {{ $a->evidencia_path ? 'true' : 'false' }}
            },
            @endforeach
        };
        
        function openDetallesTareaModal(asignacionId) {
            const tarea = tareasData[asignacionId];
            if (!tarea) {
                mostrarToast('No se encontraron los detalles de la tarea', 'error');
                return;
            }
            
            const estadosTexto = {
                'asignada': 'Asignada',
                'en_proceso': 'En Proceso',
                'terminada': 'Terminada'
            };
            
            const estadosAdminTexto = {
                'pendiente': 'Pendiente de revisión',
                'completa': 'Completa ✅',
                'parcialmente_completa': 'Parcialmente completa ⚠️',
                'incompleta': 'Incompleta ❌',
                'Pendiente': 'Pendiente de revisión'
            };
            
            document.getElementById('detallesTareaContent').innerHTML = `
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-bold text-lg text-gray-900 mb-2">${tarea.titulo}</h4>
                    <div class="flex gap-2 mb-3">
                        <span class="px-2 py-1 text-xs font-semibold rounded ${tarea.prioridadColor}">
                            ${tarea.prioridad}
                        </span>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 font-semibold mb-1">Cliente</p>
                        <p class="text-sm text-gray-900">${tarea.cliente}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-semibold mb-1">Categoría</p>
                        <p class="text-sm text-gray-900">${tarea.categoria}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-semibold mb-1">Fecha límite</p>
                        <p class="text-sm text-gray-900">${tarea.fechaLimite}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-semibold mb-1">Estado</p>
                        <p class="text-sm text-gray-900">${estadosTexto[tarea.estadoEmpleado]}</p>
                    </div>
                </div>
                
                <div class="border-t pt-4">
                    <p class="text-xs text-gray-500 font-semibold mb-2">Descripción</p>
                    <p class="text-sm text-gray-700 whitespace-pre-wrap">${tarea.descripcion}</p>
                </div>
                
                <div class="border-t pt-4">
                    <p class="text-xs text-gray-500 font-semibold mb-2">Observaciones</p>
                    <p class="text-sm text-gray-700 whitespace-pre-wrap">${tarea.observaciones}</p>
                </div>
                
                ${tarea.fechaEntrega ? `
                <div class="border-t pt-4">
                    <p class="text-xs text-gray-500 font-semibold mb-2">Fecha de entrega</p>
                    <p class="text-sm text-gray-900">${tarea.fechaEntrega}</p>
                </div>
                ` : ''}
                
                ${tarea.tieneEvidencia ? `
                <div class="border-t pt-4">
                    <p class="text-xs text-gray-500 font-semibold mb-2">Evidencia</p>
                    <p class="text-sm text-green-600 font-semibold">✅ Evidencia subida</p>
                </div>
                ` : ''}
                
                <div class="border-t pt-4">
                    <p class="text-xs text-gray-500 font-semibold mb-2">Evaluación del administrador</p>
                    <p class="text-sm text-gray-900">${estadosAdminTexto[tarea.estadoAdmin]}</p>
                </div>
            `;
            
            document.getElementById('detallesTareaModal').classList.remove('hidden');
        }
        
        function closeDetallesTareaModal() {
            document.getElementById('detallesTareaModal').classList.add('hidden');
        }

        function openEvidenciaModal(asignacionId) {
            currentAsignacionId = asignacionId;
            const modal = document.getElementById('evidenciaModal');
            const form = document.getElementById('evidenciaForm');
            form.action = `/mis-tareas/${asignacionId}/subir-evidencia`;
            modal.classList.remove('hidden');
        }

        function closeEvidenciaModal() {
            document.getElementById('evidenciaModal').classList.add('hidden');
            document.getElementById('evidenciaInput').value = '';
        }

        function mostrarConfirmacionSubir() {
            const input = document.getElementById('evidenciaInput');

            if (!input.files || input.files.length === 0) {
                mostrarToast('Por favor selecciona un archivo PDF', 'error');
                return;
            }

            const file = input.files[0];

            // Validar que sea PDF
            if (file.type !== 'application/pdf') {
                mostrarToast('El archivo debe ser un PDF', 'error');
                return;
            }

            // Validar tamaño (10MB)
            if (file.size > 10 * 1024 * 1024) {
                mostrarToast('El archivo no debe superar 10MB', 'error');
                return;
            }

            document.getElementById('nombreArchivo').textContent = file.name;
            document.getElementById('confirmarSubirModal').classList.remove('hidden');
        }

        function cerrarConfirmacionSubir() {
            document.getElementById('confirmarSubirModal').classList.add('hidden');
        }

        function confirmarSubirEvidencia() {
            cerrarConfirmacionSubir();
            document.getElementById('evidenciaForm').submit();
        }

        function confirmarEliminarEvidencia(asignacionId) {
            if (!asignacionId) {
                console.error('ID de asignación no válido:', asignacionId);
                mostrarToast('Error: ID de asignación no válido', 'error');
                return;
            }
            currentAsignacionId = asignacionId;
            document.getElementById('confirmarEliminarModal').classList.remove('hidden');
        }

        function cerrarConfirmacionEliminar() {
            document.getElementById('confirmarEliminarModal').classList.add('hidden');
        }

        function confirmarEliminarEvidenciaFinal() {
            if (!currentAsignacionId) {
                mostrarToast('Error: No se pudo identificar la tarea', 'error');
                cerrarConfirmacionEliminar();
                return;
            }

            cerrarConfirmacionEliminar();

            const form = document.getElementById(`form-eliminar-${currentAsignacionId}`);
            if (form) {
                form.submit();
            } else {
                mostrarToast('Error: No se pudo enviar el formulario', 'error');
            }
        }

        function mostrarToast(mensaje, tipo = 'success') {
            const toast = document.createElement('div');

            const bgColor = tipo === 'success' ? 'bg-green-500' : 'bg-red-500';
            const icon = tipo === 'success'
                ? '<svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>'
                : '<svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>';


            toast.innerHTML = `${icon}<span>${mensaje}</span>`;

            // ✅ TOP seguro: debajo de tu nav (h-24 = 96px) + margen
            toast.style.top = '7.5rem'; // 120px aprox

            // ✅ Z-INDEX ultra alto (sin depender de Tailwind)
            toast.style.zIndex = '2147483647';

            document.body.appendChild(toast);

            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100px)';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        // Cerrar modales al hacer click fuera
        document.getElementById('evidenciaModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeEvidenciaModal();
        });

        document.getElementById('confirmarSubirModal')?.addEventListener('click', function(e) {
            if (e.target === this) cerrarConfirmacionSubir();
        });

        document.getElementById('confirmarEliminarModal')?.addEventListener('click', function(e) {
            if (e.target === this) cerrarConfirmacionEliminar();
        });

        // Cerrar modales con tecla Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeEvidenciaModal();
                cerrarConfirmacionSubir();
                cerrarConfirmacionEliminar();
            }
        });
    </script>
    @endpush

    @endonce
@endif
