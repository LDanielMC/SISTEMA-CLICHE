<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Tareas de {{ $empleado->nombre }} {{ $empleado->apellido_paterno }}
            </h2>
            <a href="{{ route('asignaciones.index') }}" class="text-sm text-blue-600 hover:underline">
                ← Volver a asignaciones
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                
                {{-- Columna: Pendientes de Evaluación --}}
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-gray-700 flex items-center">
                            <span class="w-3 h-3 bg-gray-500 rounded-full mr-2"></span>
                            Pendientes
                        </h3>
                        <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2 py-1 rounded-full">{{ $pendientes->count() }}</span>
                    </div>
                    
                    <div class="space-y-3 kanban-column-admin" data-estado="pendiente">
                        @forelse($pendientes as $asignacion)
                            @include('asignaciones._tarjeta-evaluacion', ['asignacion' => $asignacion])
                        @empty
                            <p class="text-gray-400 text-sm text-center py-8">No hay tareas pendientes</p>
                        @endforelse
                    </div>
                </div>

                {{-- Columna: Completas --}}
                <div class="bg-green-50 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-gray-700 flex items-center">
                            <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                            Completas
                        </h3>
                        <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded-full">{{ $completas->count() }}</span>
                    </div>
                    
                    <div class="space-y-3 kanban-column-admin" data-estado="completa">
                        @forelse($completas as $asignacion)
                            @include('asignaciones._tarjeta-evaluacion', ['asignacion' => $asignacion])
                        @empty
                            <p class="text-gray-400 text-sm text-center py-8">No hay tareas completas</p>
                        @endforelse
                    </div>
                </div>

                {{-- Columna: Parcialmente Completas --}}
                <div class="bg-yellow-50 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-gray-700 flex items-center">
                            <span class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></span>
                            Parcialmente
                        </h3>
                        <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2 py-1 rounded-full">{{ $parcialmenteCompletas->count() }}</span>
                    </div>
                    
                    <div class="space-y-3 kanban-column-admin" data-estado="parcialmente_completa">
                        @forelse($parcialmenteCompletas as $asignacion)
                            @include('asignaciones._tarjeta-evaluacion', ['asignacion' => $asignacion])
                        @empty
                            <p class="text-gray-400 text-sm text-center py-8">No hay tareas parcialmente completas</p>
                        @endforelse
                    </div>
                </div>

                {{-- Columna: Incompletas --}}
                <div class="bg-red-50 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-gray-700 flex items-center">
                            <span class="w-3 h-3 bg-red-500 rounded-full mr-2"></span>
                            Incompletas
                        </h3>
                        <span class="bg-red-100 text-red-800 text-xs font-semibold px-2 py-1 rounded-full">{{ $incompletas->count() }}</span>
                    </div>
                    
                    <div class="space-y-3 kanban-column-admin" data-estado="incompleta">
                        @forelse($incompletas as $asignacion)
                            @include('asignaciones._tarjeta-evaluacion', ['asignacion' => $asignacion])
                        @empty
                            <p class="text-gray-400 text-sm text-center py-8">No hay tareas incompletas</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
    <style>
        .kanban-card-admin {
            transition: transform 0.2s ease, box-shadow 0.2s ease, opacity 0.2s ease;
        }
        
        .kanban-card-admin:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .kanban-card-admin.dragging {
            opacity: 0.5;
            transform: rotate(3deg) scale(1.05);
            cursor: grabbing;
        }
        
        .kanban-column-admin {
            transition: all 0.3s ease;
            min-height: 200px;
        }
        
        .kanban-column-admin.drag-over {
            background-color: rgba(59, 130, 246, 0.1) !important;
            border: 2px dashed #3b82f6;
            transform: scale(1.02);
        }
        
        .kanban-card-placeholder-admin {
            border: 2px dashed #9ca3af;
            background-color: #f3f4f6;
            border-radius: 0.5rem;
            height: 120px;
            margin-bottom: 0.75rem;
            opacity: 0.5;
        }
        
        @keyframes slideInAdmin {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .kanban-card-admin.inserted {
            animation: slideInAdmin 0.3s ease;
        }
        
        .toast-admin {
            animation: slideInRightAdmin 0.3s ease;
        }
        
        @keyframes slideInRightAdmin {
            from {
                opacity: 0;
                transform: translateX(100px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const columns = document.querySelectorAll('.kanban-column-admin');
            let draggedElement = null;
            let placeholder = null;
            let sourceColumn = null;

            // Crear placeholder
            function createPlaceholder() {
                const div = document.createElement('div');
                div.className = 'kanban-card-placeholder-admin';
                return div;
            }

            // Mostrar toast mejorado
            function showToast(message, type = 'success') {
                const toast = document.createElement('div');
                const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
                const icon = type === 'success'
                    ? '<svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>'
                    : '<svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>';

                toast.className = `toast-admin fixed right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-xl flex items-center`;
                toast.innerHTML = `${icon}<span>${message}</span>`;

                // ✅ Debajo del nav
                toast.style.top = '7.5rem';

                // ✅ Z-index real
                toast.style.zIndex = '2147483647';

                document.body.appendChild(toast);

                setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateX(100px)';
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            }


            // Configurar columnas
            columns.forEach(column => {
                column.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    
                    if (!draggedElement) return;
                    
                    column.classList.add('drag-over');
                    
                    // Insertar placeholder
                    const afterElement = getDragAfterElement(column, e.clientY);
                    if (!placeholder) {
                        placeholder = createPlaceholder();
                    }
                    
                    if (afterElement == null) {
                        column.appendChild(placeholder);
                    } else {
                        column.insertBefore(placeholder, afterElement);
                    }
                });

                column.addEventListener('dragleave', (e) => {
                    if (e.target === column) {
                        column.classList.remove('drag-over');
                    }
                });

                column.addEventListener('drop', async (e) => {
                    e.preventDefault();
                    column.classList.remove('drag-over');

                    if (!draggedElement) return;

                    const asignacionId = draggedElement.dataset.asignacionId;
                    const nuevoEstado = column.dataset.estado;

                    // Remover placeholder
                    if (placeholder && placeholder.parentNode) {
                        placeholder.remove();
                    }

                    // Mostrar loading
                    draggedElement.style.opacity = '0.6';
                    draggedElement.style.pointerEvents = 'none';

                    try {
                        const response = await fetch(`/asignaciones/${asignacionId}/actualizar-estado-admin`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ estado_admin: nuevoEstado })
                        });

                        if (response.ok) {
                            // Insertar en la nueva posición con animación
                            const afterElement = getDragAfterElement(column, e.clientY);
                            draggedElement.classList.add('inserted');
                            
                            if (afterElement == null) {
                                column.appendChild(draggedElement);
                            } else {
                                column.insertBefore(draggedElement, afterElement);
                            }
                            
                            draggedElement.style.opacity = '1';
                            draggedElement.style.pointerEvents = 'auto';
                            
                            // Actualizar contadores
                            updateCounters();
                            
                            // Mensaje personalizado según el estado
                            const mensajes = {
                                'pendiente': '⏳ Tarea marcada como pendiente',
                                'completa': '✅ Tarea evaluada como completa',
                                'parcialmente_completa': '⚠️ Tarea parcialmente completa',
                                'incompleta': '❌ Tarea marcada como incompleta'
                            };
                            
                            showToast(mensajes[nuevoEstado] || 'Evaluación actualizada correctamente', 'success');
                            
                            setTimeout(() => {
                                draggedElement.classList.remove('inserted');
                            }, 300);
                        } else {
                            // Revertir si falla
                            sourceColumn.appendChild(draggedElement);
                            draggedElement.style.opacity = '1';
                            draggedElement.style.pointerEvents = 'auto';
                            showToast('Error al actualizar la evaluación', 'error');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        sourceColumn.appendChild(draggedElement);
                        draggedElement.style.opacity = '1';
                        draggedElement.style.pointerEvents = 'auto';
                        showToast('Error de conexión', 'error');
                    }
                });
            });

            // Configurar tarjetas
            document.querySelectorAll('.kanban-card-admin').forEach(card => {
                card.setAttribute('draggable', 'true');
                card.style.cursor = 'grab';
                
                card.addEventListener('dragstart', (e) => {
                    draggedElement = card;
                    sourceColumn = card.closest('.kanban-column-admin');
                    card.classList.add('dragging');
                    card.style.cursor = 'grabbing';
                    
                    // Efecto de elevación
                    setTimeout(() => {
                        card.style.opacity = '0.5';
                    }, 0);
                });

                card.addEventListener('dragend', () => {
                    card.classList.remove('dragging');
                    card.style.cursor = 'grab';
                    card.style.opacity = '1';
                    
                    // Limpiar placeholder
                    if (placeholder && placeholder.parentNode) {
                        placeholder.remove();
                    }
                    placeholder = null;
                    
                    // Remover clase drag-over de todas las columnas
                    columns.forEach(col => col.classList.remove('drag-over'));
                });
            });

            // Función para determinar después de qué elemento insertar
            function getDragAfterElement(column, y) {
                const draggableElements = [...column.querySelectorAll('.kanban-card-admin:not(.dragging)')];

                return draggableElements.reduce((closest, child) => {
                    const box = child.getBoundingClientRect();
                    const offset = y - box.top - box.height / 2;

                    if (offset < 0 && offset > closest.offset) {
                        return { offset: offset, element: child };
                    } else {
                        return closest;
                    }
                }, { offset: Number.NEGATIVE_INFINITY }).element;
            }

            // Actualizar contadores de tareas
            function updateCounters() {
                columns.forEach(column => {
                    const count = column.querySelectorAll('.kanban-card-admin').length;
                    const badge = column.closest('.bg-gray-50, .bg-green-50, .bg-yellow-50, .bg-red-50')
                        ?.querySelector('.bg-gray-100, .bg-green-100, .bg-yellow-100, .bg-red-100');
                    if (badge) {
                        badge.textContent = count;
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
