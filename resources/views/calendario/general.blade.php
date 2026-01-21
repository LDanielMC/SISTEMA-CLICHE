<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ Auth::user()->rol === 'cliente' ? __('Mi Calendario') : __('Calendario de Publicaciones') }}
            </h2>
            
            <div class="flex gap-2 items-center">
                @if(Auth::user()->rol !== 'cliente')
                <!-- Botón Configuración -->
                <a href="{{ route('calendario.config') }}" class="bg-gray-100 text-gray-700 hover:bg-gray-200 font-medium text-sm flex items-center gap-1 transition-colors px-3 py-2 rounded-md border border-gray-300">
                    <span>⚙️</span> Configuración
                </a>

                <!-- Botón de Gestión (AHORA DIRECTO A LA TABLA DE GESTIÓN) -->
                <a href="{{ route('calendario.gestion') }}" class="bg-blue-600 text-white hover:bg-blue-700 font-bold text-sm px-4 py-2 rounded-md shadow-sm transition-colors flex items-center">
                    <span>+ Gestionar Contenido</span>
                </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Leyenda de Colores -->
                    <div class="flex gap-4 mb-4 text-sm flex-wrap">
                        <div class="flex items-center"><span class="w-3 h-3 rounded-full bg-green-500 mr-2"></span> Publicado</div>
                        <div class="flex items-center"><span class="w-3 h-3 rounded-full bg-yellow-400 mr-2"></span> Pendiente</div>
                        <div class="flex items-center"><span class="w-3 h-3 rounded-full bg-red-500 mr-2"></span> Reprogramar</div>
                    </div>

                    <!-- Contenedor del Calendario -->
                    <div id='calendar' class="min-h-[600px]"></div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Detalles de Publicación -->
    <div id="eventModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Overlay de fondo -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModal()"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modalTitle">Detalles de Publicación</h3>
                            
                            <div class="space-y-3 text-sm text-gray-700">
                                <p><strong>Cliente:</strong> <span id="modalCliente"></span></p>
                                <p><strong>Fecha:</strong> <span id="modalFecha"></span></p>
                                <p><strong>Plataforma:</strong> <span id="modalPlataforma"></span></p>
                                <p><strong>Formato:</strong> <span id="modalFormato"></span></p>
                                <p><strong>Estatus:</strong> <span id="modalEstatus" class="font-semibold"></span></p>
                                
                                <div class="border-t pt-2 mt-2">
                                    <p class="font-bold mb-1">Copy:</p>
                                    <div id="modalCopy" class="whitespace-pre-wrap bg-gray-50 p-2 rounded border text-gray-600 max-h-40 overflow-y-auto"></div>
                                </div>

                                <div class="border-t pt-2 mt-2">
                                    <p class="font-bold mb-1">Arte:</p>
                                    <div id="modalArte" class="whitespace-pre-wrap bg-gray-50 p-2 rounded border text-gray-600 max-h-40 overflow-y-auto"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="closeModal()">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    @push('scripts')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var eventos = @json($eventosFormateados);

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                events: eventos,
                eventClick: function(info) {
                    // Llenar datos del modal
                    var props = info.event.extendedProps;
                    
                    document.getElementById('modalCliente').textContent = props.cliente_nombre || 'N/A';
                    
                    // Formatear fecha
                    var fecha = info.event.start;
                    var opcionesFecha = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                    document.getElementById('modalFecha').textContent = fecha ? fecha.toLocaleDateString('es-ES', opcionesFecha) : 'N/A';

                    document.getElementById('modalPlataforma').textContent = props.plataforma || 'N/A';
                    document.getElementById('modalFormato').textContent = props.formato || 'N/A';
                    document.getElementById('modalEstatus').textContent = props.estatus || 'N/A';
                    document.getElementById('modalCopy').textContent = props.copy || 'Sin copy.';

                    document.getElementById('modalArte').textContent = props.arte || 'Sin descripción de arte.';

                    // Mostrar modal
                    document.getElementById('eventModal').classList.remove('hidden');
                }
            });
            calendar.render();

            // Función para cerrar modal globalmente
            window.closeModal = function() {
                document.getElementById('eventModal').classList.add('hidden');
            }
        });
    </script>
    @endpush
</x-app-layout>