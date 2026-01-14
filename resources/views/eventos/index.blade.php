<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📅 {{ __('Calendario de Eventos') }}
        </h2>
    </x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">📅 Calendario de Eventos</h2>
                    <p class="text-gray-600 mt-1">Gestiona reuniones, sesiones fotográficas y compromisos</p>
                </div>
                <button onclick="abrirModalNuevoEvento()" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-lg transition duration-150">
                    ➕ Nuevo Evento
                </button>
            </div>
        </div>

        <!-- Calendario -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div id="calendar"></div>
            </div>
        </div>

        <!-- Próximos Eventos -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">📋 Próximos Eventos</h3>
                <div id="proximosEventos" class="space-y-3">
                    @forelse($eventos->take(5) as $evento)
                        <div class="border-l-4 pl-4 py-2" style="border-color: {{ $evento->color }}">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-semibold text-gray-800">{{ $evento->titulo }}</h4>
                                    <p class="text-sm text-gray-600">
                                        📅 {{ $evento->fecha->format('d/m/Y') }} • 
                                        🕐 {{ date('H:i', strtotime($evento->hora_inicio)) }} - {{ date('H:i', strtotime($evento->hora_fin)) }}
                                    </p>
                                    @if($evento->cliente)
                                        <p class="text-sm text-gray-500">👤 {{ $evento->cliente->nombre }}</p>
                                    @endif
                                    @if($evento->lugar)
                                        <p class="text-sm text-gray-500">📍 {{ $evento->lugar }}</p>
                                    @endif
                                </div>
                                <div class="flex gap-2">
                                    <button onclick="verEvento({{ $evento->id }})" 
                                            class="text-blue-600 hover:text-blue-800">
                                        👁️
                                    </button>
                                    <button onclick="editarEvento({{ $evento->id }})" 
                                            class="text-yellow-600 hover:text-yellow-800">
                                        ✏️
                                    </button>
                                    <button onclick="eliminarEvento({{ $evento->id }})" 
                                            class="text-red-600 hover:text-red-800">
                                        🗑️
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">No hay eventos próximos</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Crear/Editar Evento -->
<div id="eventoModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center pb-3 border-b">
            <h3 id="modalTitulo" class="text-2xl font-bold text-gray-800">Nuevo Evento</h3>
            <button onclick="cerrarModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>
        
        <form id="eventoForm" class="mt-4">
            @csrf
            <input type="hidden" id="eventoId" name="evento_id">
            
            <!-- Información Básica -->
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Título *</label>
                    <input type="text" id="titulo" name="titulo" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cliente</label>
                        <select id="cliente_id" name="cliente_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Sin cliente</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id_cliente }}">{{ $cliente->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                        <input type="color" id="color" name="color" value="#3B82F6"
                               class="w-full h-10 px-1 py-1 border border-gray-300 rounded-lg">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha *</label>
                        <input type="date" id="fecha" name="fecha" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Hora Inicio *</label>
                        <input type="time" id="hora_inicio" name="hora_inicio" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Hora Fin *</label>
                        <input type="time" id="hora_fin" name="hora_fin" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lugar</label>
                    <input type="text" id="lugar" name="lugar"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="Ej: Oficina principal, Casa del cliente, etc.">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notas u Observaciones</label>
                    <textarea id="notas" name="notas" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                              placeholder="Detalles adicionales del evento..."></textarea>
                </div>

                <!-- Recurrencia -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Recurrencia</label>
                    <select id="recurrencia" name="recurrencia" onchange="toggleRecurrenciaHasta()"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="ninguna">Sin recurrencia</option>
                        <option value="diaria">Diaria</option>
                        <option value="semanal">Semanal</option>
                        <option value="mensual">Mensual</option>
                        <option value="anual">Anual</option>
                    </select>
                </div>

                <div id="recurrenciaHastaDiv" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Repetir hasta</label>
                    <input type="date" id="recurrencia_hasta" name="recurrencia_hasta"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- Participantes -->
            <div class="mt-6 border-t pt-4">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="font-semibold text-gray-800">👥 Participantes</h4>
                    <button type="button" onclick="agregarParticipante()" 
                            class="text-sm bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded">
                        + Agregar
                    </button>
                </div>
                <div id="participantesContainer" class="space-y-2"></div>
            </div>

            <!-- Recordatorios -->
            <div class="mt-6 border-t pt-4">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="font-semibold text-gray-800">🔔 Recordatorios</h4>
                    <button type="button" onclick="agregarRecordatorio()" 
                            class="text-sm bg-purple-500 hover:bg-purple-600 text-white px-3 py-1 rounded">
                        + Agregar
                    </button>
                </div>
                <div id="recordatoriosContainer" class="space-y-2"></div>
            </div>

            <!-- Botones -->
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="cerrarModal()" 
                        class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    Cancelar
                </button>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Guardar Evento
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para Ver Detalles del Evento -->
<div id="detallesModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center pb-3 border-b">
            <h3 class="text-2xl font-bold text-gray-800">📋 Detalles del Evento</h3>
            <button onclick="cerrarModalDetalles()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>
        
        <div id="detallesContenido" class="mt-6 space-y-4">
            <!-- Título -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h4 id="detalle_titulo" class="text-2xl font-bold text-gray-800"></h4>
            </div>

            <!-- Fecha y Hora -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center gap-3 bg-blue-50 p-4 rounded-lg">
                    <span class="text-3xl">📅</span>
                    <div>
                        <p class="text-xs text-gray-600 font-semibold">Fecha</p>
                        <p id="detalle_fecha" class="text-lg font-semibold text-gray-800"></p>
                    </div>
                </div>
                <div class="flex items-center gap-3 bg-green-50 p-4 rounded-lg">
                    <span class="text-3xl">🕐</span>
                    <div>
                        <p class="text-xs text-gray-600 font-semibold">Horario</p>
                        <p id="detalle_horario" class="text-lg font-semibold text-gray-800"></p>
                    </div>
                </div>
            </div>

            <!-- Cliente y Lugar -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div id="detalle_cliente_container" class="hidden">
                    <div class="flex items-center gap-3 bg-purple-50 p-4 rounded-lg">
                        <span class="text-3xl">👤</span>
                        <div>
                            <p class="text-xs text-gray-600 font-semibold">Cliente</p>
                            <p id="detalle_cliente" class="text-lg font-semibold text-gray-800"></p>
                        </div>
                    </div>
                </div>
                <div id="detalle_lugar_container" class="hidden">
                    <div class="flex items-center gap-3 bg-yellow-50 p-4 rounded-lg">
                        <span class="text-3xl">📍</span>
                        <div>
                            <p class="text-xs text-gray-600 font-semibold">Lugar</p>
                            <p id="detalle_lugar" class="text-lg font-semibold text-gray-800"></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recurrencia -->
            <div id="detalle_recurrencia_container" class="hidden">
                <div class="flex items-center gap-3 bg-indigo-50 p-4 rounded-lg">
                    <span class="text-3xl">🔁</span>
                    <div>
                        <p class="text-xs text-gray-600 font-semibold">Recurrencia</p>
                        <p id="detalle_recurrencia" class="text-lg font-semibold text-gray-800"></p>
                    </div>
                </div>
            </div>

            <!-- Notas -->
            <div id="detalle_notas_container" class="hidden">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-xs text-gray-600 font-semibold mb-2">📝 Notas</p>
                    <p id="detalle_notas" class="text-gray-700 whitespace-pre-wrap"></p>
                </div>
            </div>

            <!-- Participantes -->
            <div id="detalle_participantes_container" class="hidden">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-xs text-gray-600 font-semibold mb-3">👥 Participantes</p>
                    <div id="detalle_participantes" class="space-y-2"></div>
                </div>
            </div>

            <!-- Recordatorios -->
            <div id="detalle_recordatorios_container" class="hidden">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-xs text-gray-600 font-semibold mb-3">🔔 Recordatorios</p>
                    <div id="detalle_recordatorios" class="space-y-2"></div>
                </div>
            </div>

            <!-- Estado de sincronización -->
            <div id="detalle_google_container" class="hidden">
                <div class="flex items-center gap-3 bg-green-50 p-4 rounded-lg border-l-4 border-green-500">
                    <span class="text-3xl">✓</span>
                    <div>
                        <p class="text-sm font-semibold text-green-800">Sincronizado con Google Calendar</p>
                        <p class="text-xs text-green-600">Este evento está disponible en tu Google Calendar</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="mt-6 flex justify-end gap-3">
            <button type="button" onclick="cerrarModalDetalles()" 
                    class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                Cerrar
            </button>
            <button type="button" onclick="editarDesdeDetalles()" 
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                ✏️ Editar Evento
            </button>
        </div>
    </div>
</div>

<!-- Modal de Confirmación para Eliminar -->
<div id="eliminarModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full z-50">
    <div class="relative top-1/3 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <span class="text-3xl">⚠️</span>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Eliminar Evento</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    ¿Estás seguro de que deseas eliminar este evento?
                </p>
                <p id="eliminar_evento_titulo" class="text-base font-semibold text-gray-800 mt-2"></p>
                <p class="text-xs text-red-600 mt-2">
                    Esta acción no se puede deshacer y también se eliminará de Google Calendar si está sincronizado.
                </p>
            </div>
            <div class="flex gap-4 px-4 py-3">
                <button onclick="cerrarModalEliminar()" 
                        class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    Cancelar
                </button>
                <button onclick="confirmarEliminar()" 
                        class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Eliminar
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
<style>
    .fc {
        max-width: 100%;
    }
    .fc-event {
        cursor: pointer;
    }
    .fc-daygrid-event {
        white-space: normal !important;
    }
</style>
@endpush

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/locales/es.global.min.js'></script>
<script>
    let calendar;
    let eventoActual = null;
    let participanteIndex = 0;
    let recordatorioIndex = 0;

    document.addEventListener('DOMContentLoaded', function() {
        inicializarCalendario();
    });

    function inicializarCalendario() {
        const calendarEl = document.getElementById('calendar');
        
        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
            },
            buttonText: {
                today: 'Hoy',
                month: 'Mes',
                week: 'Semana',
                day: 'Día',
                list: 'Lista'
            },
            events: '/calendario/eventos',
            eventClick: function(info) {
                // Si es evento recurrente, extraer el ID original
                const eventoId = info.event.id.toString().includes('_') 
                    ? info.event.id.split('_')[0] 
                    : info.event.id;
                verEvento(eventoId);
            },
            dateClick: function(info) {
                abrirModalNuevoEvento(info.dateStr);
            },
            editable: true,
            eventDrop: function(info) {
                actualizarFechaEvento(info.event.id, info.event.startStr);
            }
        });
        
        calendar.render();
    }

    function abrirModalNuevoEvento(fecha = null) {
        eventoActual = null;
        document.getElementById('modalTitulo').textContent = 'Nuevo Evento';
        document.getElementById('eventoForm').reset();
        document.getElementById('eventoId').value = '';
        
        if (fecha) {
            document.getElementById('fecha').value = fecha;
        }
        
        document.getElementById('participantesContainer').innerHTML = '';
        document.getElementById('recordatoriosContainer').innerHTML = '';
        participanteIndex = 0;
        recordatorioIndex = 0;
        
        document.getElementById('eventoModal').classList.remove('hidden');
    }

    function cerrarModal() {
        document.getElementById('eventoModal').classList.add('hidden');
    }

    function toggleRecurrenciaHasta() {
        const recurrencia = document.getElementById('recurrencia').value;
        const div = document.getElementById('recurrenciaHastaDiv');
        
        if (recurrencia !== 'ninguna') {
            div.classList.remove('hidden');
        } else {
            div.classList.add('hidden');
        }
    }

    function agregarParticipante() {
        const container = document.getElementById('participantesContainer');
        const index = participanteIndex++;
        
        const html = `
            <div class="participante-item flex gap-2" data-index="${index}">
                <select name="participantes[${index}][tipo]" class="flex-1 px-2 py-1 border rounded" onchange="cambiarTipoParticipante(${index})">
                    <option value="externo">Externo</option>
                    <option value="empleado">Empleado</option>
                    <option value="cliente">Cliente</option>
                </select>
                <input type="text" name="participantes[${index}][nombre]" placeholder="Nombre" required
                       class="flex-1 px-2 py-1 border rounded participante-nombre-${index}">
                <input type="email" name="participantes[${index}][correo]" placeholder="Correo" required
                       class="flex-1 px-2 py-1 border rounded participante-correo-${index}">
                <input type="hidden" name="participantes[${index}][referencia_id]" class="participante-ref-${index}">
                <button type="button" onclick="eliminarParticipante(${index})" 
                        class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                    ✕
                </button>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', html);
    }

    function eliminarParticipante(index) {
        document.querySelector(`.participante-item[data-index="${index}"]`).remove();
    }

    function agregarRecordatorio() {
        const container = document.getElementById('recordatoriosContainer');
        const index = recordatorioIndex++;
        
        const html = `
            <div class="recordatorio-item flex gap-2" data-index="${index}">
                <select name="recordatorios[${index}][tipo_notificacion]" class="flex-1 px-2 py-1 border rounded">
                    <option value="ambos">Correo + Sistema</option>
                    <option value="correo">Solo Correo</option>
                    <option value="sistema">Solo Sistema</option>
                </select>
                <select name="recordatorios[${index}][minutos_antes]" class="flex-1 px-2 py-1 border rounded">
                    <option value="10">10 minutos antes</option>
                    <option value="30">30 minutos antes</option>
                    <option value="60">1 hora antes</option>
                    <option value="120">2 horas antes</option>
                    <option value="1440">1 día antes</option>
                </select>
                <button type="button" onclick="eliminarRecordatorio(${index})" 
                        class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                    ✕
                </button>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', html);
    }

    function eliminarRecordatorio(index) {
        document.querySelector(`.recordatorio-item[data-index="${index}"]`).remove();
    }

    document.getElementById('eventoForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = {};
        
        // Convertir FormData a objeto
        formData.forEach((value, key) => {
            if (key.includes('[')) {
                // Manejar arrays (participantes y recordatorios)
                const match = key.match(/(\w+)\[(\d+)\]\[(\w+)\]/);
                if (match) {
                    const [, arrayName, index, field] = match;
                    if (!data[arrayName]) data[arrayName] = [];
                    if (!data[arrayName][index]) data[arrayName][index] = {};
                    data[arrayName][index][field] = value;
                }
            } else {
                data[key] = value;
            }
        });
        
        // Limpiar arrays
        if (data.participantes) {
            data.participantes = data.participantes.filter(p => p);
        }
        if (data.recordatorios) {
            data.recordatorios = data.recordatorios.filter(r => r);
        }
        
        try {
            const eventoId = document.getElementById('eventoId').value;
            const url = eventoId ? `/eventos/${eventoId}` : '/eventos';
            const method = eventoId ? 'PUT' : 'POST';
            
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                cerrarModal();
                calendar.refetchEvents();
                mostrarToast('Evento guardado exitosamente', 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                mostrarToast(result.error || 'Error al guardar el evento', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            mostrarToast('Error de conexión', 'error');
        }
    });

    async function verEvento(id) {
        try {
            const response = await fetch(`/eventos/${id}`);
            const evento = await response.json();
            
            // Guardar evento actual para edición
            eventoActual = evento;
            
            // Llenar datos básicos
            document.getElementById('detalle_titulo').textContent = evento.titulo;
            document.getElementById('detalle_fecha').textContent = formatearFecha(evento.fecha);
            document.getElementById('detalle_horario').textContent = 
                `${formatearHora(evento.hora_inicio)} - ${formatearHora(evento.hora_fin)}`;
            
            // Cliente
            const clienteContainer = document.getElementById('detalle_cliente_container');
            if (evento.cliente) {
                document.getElementById('detalle_cliente').textContent = evento.cliente.nombre;
                clienteContainer.classList.remove('hidden');
            } else {
                clienteContainer.classList.add('hidden');
            }
            
            // Lugar
            const lugarContainer = document.getElementById('detalle_lugar_container');
            if (evento.lugar) {
                document.getElementById('detalle_lugar').textContent = evento.lugar;
                lugarContainer.classList.remove('hidden');
            } else {
                lugarContainer.classList.add('hidden');
            }
            
            // Recurrencia
            const recurrenciaContainer = document.getElementById('detalle_recurrencia_container');
            if (evento.recurrencia && evento.recurrencia !== 'ninguna') {
                let textoRecurrencia = evento.recurrencia.charAt(0).toUpperCase() + evento.recurrencia.slice(1);
                if (evento.recurrencia_hasta) {
                    textoRecurrencia += ` hasta ${formatearFecha(evento.recurrencia_hasta)}`;
                }
                document.getElementById('detalle_recurrencia').textContent = textoRecurrencia;
                recurrenciaContainer.classList.remove('hidden');
            } else {
                recurrenciaContainer.classList.add('hidden');
            }
            
            // Notas
            const notasContainer = document.getElementById('detalle_notas_container');
            if (evento.notas) {
                document.getElementById('detalle_notas').textContent = evento.notas;
                notasContainer.classList.remove('hidden');
            } else {
                notasContainer.classList.add('hidden');
            }
            
            // Participantes
            const participantesContainer = document.getElementById('detalle_participantes_container');
            const participantesDiv = document.getElementById('detalle_participantes');
            if (evento.participantes && evento.participantes.length > 0) {
                participantesDiv.innerHTML = evento.participantes.map(p => `
                    <div class="flex items-center gap-2 text-gray-700">
                        <span class="text-blue-600">•</span>
                        <span class="font-medium">${p.nombre}</span>
                        <span class="text-gray-500 text-sm">(${p.correo})</span>
                        <span class="text-xs bg-gray-200 px-2 py-1 rounded">${p.tipo}</span>
                    </div>
                `).join('');
                participantesContainer.classList.remove('hidden');
            } else {
                participantesContainer.classList.add('hidden');
            }
            
            // Recordatorios
            const recordatoriosContainer = document.getElementById('detalle_recordatorios_container');
            const recordatoriosDiv = document.getElementById('detalle_recordatorios');
            if (evento.recordatorios && evento.recordatorios.length > 0) {
                recordatoriosDiv.innerHTML = evento.recordatorios.map(r => {
                    const tipoIcono = r.tipo_notificacion === 'correo' ? '📧' : 
                                     r.tipo_notificacion === 'sistema' ? '🔔' : '📧🔔';
                    return `
                        <div class="flex items-center gap-2 text-gray-700">
                            <span class="text-lg">${tipoIcono}</span>
                            <span>${r.minutos_antes} minutos antes - ${r.tipo_notificacion}</span>
                        </div>
                    `;
                }).join('');
                recordatoriosContainer.classList.remove('hidden');
            } else {
                recordatoriosContainer.classList.add('hidden');
            }
            
            // Estado de Google Calendar
            const googleContainer = document.getElementById('detalle_google_container');
            if (evento.sincronizado_google && evento.google_event_id) {
                googleContainer.classList.remove('hidden');
            } else {
                googleContainer.classList.add('hidden');
            }
            
            // Abrir modal
            document.getElementById('detallesModal').classList.remove('hidden');
            
        } catch (error) {
            console.error('Error:', error);
            mostrarToast('Error al cargar detalles del evento', 'error');
        }
    }
    
    function cerrarModalDetalles() {
        document.getElementById('detallesModal').classList.add('hidden');
        eventoActual = null;
    }
    
    function editarDesdeDetalles() {
        if (eventoActual) {
            cerrarModalDetalles();
            editarEvento(eventoActual.id);
        }
    }
    
    function formatearFecha(fecha) {
        if (!fecha) return '';
        
        // Si ya viene con hora, extraer solo la fecha
        const fechaSolo = fecha.split('T')[0].split(' ')[0];
        
        // Crear fecha en formato ISO
        const date = new Date(fechaSolo + 'T12:00:00Z');
        
        if (isNaN(date.getTime())) {
            return fecha; // Retornar fecha original si no se puede parsear
        }
        
        return date.toLocaleDateString('es-MX', { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            timeZone: 'America/Mexico_City'
        });
    }
    
    function formatearHora(hora) {
        // Si es timestamp completo, extraer solo la hora
        if (hora.includes(' ')) {
            hora = hora.split(' ')[1];
        }
        const [h, m] = hora.split(':');
        return `${h}:${m}`;
    }

    async function editarEvento(id) {
        try {
            const response = await fetch(`/eventos/${id}`);
            const evento = await response.json();
            
            // Guardar evento actual
            eventoActual = evento;
            
            // Cambiar título del modal
            document.getElementById('modalTitulo').textContent = 'Editar Evento';
            document.getElementById('eventoId').value = evento.id;
            
            // Llenar campos básicos
            document.getElementById('titulo').value = evento.titulo;
            document.getElementById('cliente_id').value = evento.cliente_id || '';
            document.getElementById('fecha').value = evento.fecha.split('T')[0];
            document.getElementById('hora_inicio').value = formatearHoraInput(evento.hora_inicio);
            document.getElementById('hora_fin').value = formatearHoraInput(evento.hora_fin);
            document.getElementById('lugar').value = evento.lugar || '';
            document.getElementById('notas').value = evento.notas || '';
            document.getElementById('color').value = evento.color || '#3B82F6';
            document.getElementById('recurrencia').value = evento.recurrencia || 'ninguna';
            
            // Mostrar/ocultar campo de recurrencia hasta
            const recurrenciaHastaDiv = document.getElementById('recurrenciaHastaDiv');
            if (evento.recurrencia && evento.recurrencia !== 'ninguna') {
                recurrenciaHastaDiv.classList.remove('hidden');
                document.getElementById('recurrencia_hasta').value = evento.recurrencia_hasta 
                    ? evento.recurrencia_hasta.split('T')[0] 
                    : '';
            } else {
                recurrenciaHastaDiv.classList.add('hidden');
            }
            
            // Limpiar y cargar participantes
            const participantesContainer = document.getElementById('participantesContainer');
            participantesContainer.innerHTML = '';
            participanteIndex = 0;
            
            if (evento.participantes && evento.participantes.length > 0) {
                evento.participantes.forEach(participante => {
                    agregarParticipante(participante);
                });
            }
            
            // Limpiar y cargar recordatorios
            const recordatoriosContainer = document.getElementById('recordatoriosContainer');
            recordatoriosContainer.innerHTML = '';
            recordatorioIndex = 0;
            
            if (evento.recordatorios && evento.recordatorios.length > 0) {
                evento.recordatorios.forEach(recordatorio => {
                    agregarRecordatorio(recordatorio);
                });
            }
            
            // Abrir modal
            document.getElementById('eventoModal').classList.remove('hidden');
            
        } catch (error) {
            console.error('Error:', error);
            mostrarToast('Error al cargar evento', 'error');
        }
    }
    
    function formatearHoraInput(hora) {
        // Si es timestamp completo, extraer solo HH:MM
        if (hora.includes(' ')) {
            hora = hora.split(' ')[1];
        }
        const [h, m] = hora.split(':');
        return `${h}:${m}`;
    }

    let eventoIdEliminar = null;

    function eliminarEvento(id) {
        // Cargar datos del evento para mostrar en el modal
        fetch(`/eventos/${id}`)
            .then(response => response.json())
            .then(evento => {
                eventoIdEliminar = id;
                document.getElementById('eliminar_evento_titulo').textContent = evento.titulo;
                document.getElementById('eliminarModal').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarToast('Error al cargar evento', 'error');
            });
    }
    
    function cerrarModalEliminar() {
        document.getElementById('eliminarModal').classList.add('hidden');
        eventoIdEliminar = null;
    }
    
    async function confirmarEliminar() {
        if (!eventoIdEliminar) return;
        
        try {
            const response = await fetch(`/eventos/${eventoIdEliminar}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                cerrarModalEliminar();
                calendar.refetchEvents();
                mostrarToast('Evento eliminado exitosamente', 'success');
                
                // Recargar página para actualizar lista de próximos eventos
                setTimeout(() => location.reload(), 1000);
            } else {
                mostrarToast('Error al eliminar evento', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            mostrarToast('Error al eliminar evento', 'error');
        }
    }

    function mostrarToast(mensaje, tipo = 'success') {
        const toast = document.createElement('div');
        const bgColor = tipo === 'success' ? 'bg-green-500' : 'bg-red-500';
        toast.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-xl z-50`;
        toast.textContent = mensaje;
        document.body.appendChild(toast);
        
        setTimeout(() => toast.remove(), 3000);
    }
</script>
@endpush
</x-app-layout>
