<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mis tareas asignadas
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

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

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                {{-- Columna: Asignadas --}}
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-gray-700 flex items-center">
                            <span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span>
                            Asignadas
                        </h3>
                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded-full">{{ $asignadas->count() }}</span>
                    </div>
                    
                    <div class="space-y-3 kanban-column" data-estado="asignada">
                        @forelse($asignadas as $asignacion)
                            @include('asignaciones._tarjeta-tarea', ['asignacion' => $asignacion, 'esEmpleado' => true])
                        @empty
                            <p class="text-gray-400 text-sm text-center py-8">No hay tareas asignadas</p>
                        @endforelse
                    </div>
                </div>

                {{-- Columna: En Proceso --}}
                <div class="bg-yellow-50 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-gray-700 flex items-center">
                            <span class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></span>
                            En Proceso
                        </h3>
                        <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2 py-1 rounded-full">{{ $enProceso->count() }}</span>
                    </div>
                    
                    <div class="space-y-3 kanban-column" data-estado="en_proceso">
                        @forelse($enProceso as $asignacion)
                            @include('asignaciones._tarjeta-tarea', ['asignacion' => $asignacion, 'esEmpleado' => true])
                        @empty
                            <p class="text-gray-400 text-sm text-center py-8">No hay tareas en proceso</p>
                        @endforelse
                    </div>
                </div>

                {{-- Columna: Terminadas --}}
                <div class="bg-green-50 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-gray-700 flex items-center">
                            <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                            Terminadas
                        </h3>
                        <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded-full">{{ $terminadas->count() }}</span>
                    </div>
                    
                    <div class="space-y-3 kanban-column" data-estado="terminada">
                        @forelse($terminadas as $asignacion)
                            @include('asignaciones._tarjeta-tarea', ['asignacion' => $asignacion, 'esEmpleado' => true])
                        @empty
                            <p class="text-gray-400 text-sm text-center py-8">No hay tareas terminadas</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
    <style>
        .kanban-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease, opacity 0.2s ease;
        }
        
        .kanban-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .kanban-card.dragging {
            opacity: 0.5;
            transform: rotate(3deg) scale(1.05);
            cursor: grabbing;
        }
        
        .kanban-column {
            transition: all 0.3s ease;
            min-height: 200px;
        }
        
        .kanban-column.drag-over {
            background-color: rgba(59, 130, 246, 0.1) !important;
            border: 2px dashed #3b82f6;
            transform: scale(1.02);
        }
        
        .kanban-card-placeholder {
            border: 2px dashed #9ca3af;
            background-color: #f3f4f6;
            border-radius: 0.5rem;
            height: 120px;
            margin-bottom: 0.75rem;
            opacity: 0.5;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .kanban-card.inserted {
            animation: slideIn 0.3s ease;
        }
        
        .toast {
            animation: slideInRight 0.3s ease;
        }
        
        @keyframes slideInRight {
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
            const columns = document.querySelectorAll('.kanban-column');
            let draggedElement = null;
            let placeholder = null;
            let sourceColumn = null;

            // Crear placeholder
            function createPlaceholder() {
                const div = document.createElement('div');
                div.className = 'kanban-card-placeholder';
                return div;
            }

            // Mostrar toast mejorado
            function showToast(message, type = 'success') {
                const toast = document.createElement('div');
                const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
                const icon = type === 'success'
                    ? '<svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>'
                    : '<svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>';

                toast.className = `toast fixed right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-xl flex items-center`;
                toast.innerHTML = `${icon}<span>${message}</span>`;

                // ✅ Debajo del nav (h-24 = 96px) + margen
                toast.style.top = '7.5rem'; // 120px aprox

                // ✅ Z-index real (sin depender de Tailwind)
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
                    const estadoAnterior = sourceColumn.dataset.estado;

                    // Remover placeholder
                    if (placeholder && placeholder.parentNode) {
                        placeholder.remove();
                    }

                    // Mostrar loading
                    draggedElement.style.opacity = '0.6';
                    draggedElement.style.pointerEvents = 'none';

                    try {
                        const response = await fetch(`/mis-tareas/${asignacionId}/actualizar-estado`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ estado_empleado: nuevoEstado })
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
                                'asignada': '📋 Tarea movida a Asignadas',
                                'en_proceso': '⚙️ Tarea en proceso',
                                'terminada': '✅ Tarea marcada como terminada'
                            };
                            
                            showToast(mensajes[nuevoEstado] || 'Estado actualizado correctamente', 'success');
                            
                            setTimeout(() => {
                                draggedElement.classList.remove('inserted');
                            }, 300);
                        } else if (response.status === 422) {
                            // Error de validación (sin evidencia)
                            const data = await response.json();
                            sourceColumn.appendChild(draggedElement);
                            draggedElement.style.opacity = '1';
                            draggedElement.style.pointerEvents = 'auto';
                            showToast(data.message || '⚠️ Debes subir la evidencia primero', 'error');
                        } else {
                            // Revertir si falla
                            sourceColumn.appendChild(draggedElement);
                            draggedElement.style.opacity = '1';
                            draggedElement.style.pointerEvents = 'auto';
                            showToast('Error al actualizar el estado', 'error');
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
            document.querySelectorAll('.kanban-card').forEach(card => {
                card.setAttribute('draggable', 'true');
                card.style.cursor = 'grab';
                
                card.addEventListener('dragstart', (e) => {
                    draggedElement = card;
                    sourceColumn = card.closest('.kanban-column');
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
                const draggableElements = [...column.querySelectorAll('.kanban-card:not(.dragging)')];

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
                    const count = column.querySelectorAll('.kanban-card').length;
                    const badge = column.closest('.bg-gray-50, .bg-yellow-50, .bg-green-50')
                        ?.querySelector('.bg-blue-100, .bg-yellow-100, .bg-green-100');
                    if (badge) {
                        badge.textContent = count;
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
