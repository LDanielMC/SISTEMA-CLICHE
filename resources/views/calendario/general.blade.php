<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Calendario General') }}
            </h2>
            
            <div class="flex gap-2 items-center">
                <!-- Botón Configuración -->
                <a href="{{ route('calendario.config') }}" class="bg-gray-100 text-gray-700 hover:bg-gray-200 font-medium text-sm flex items-center gap-1 transition-colors px-3 py-2 rounded-md border border-gray-300">
                    <span>⚙️</span> Configuración
                </a>

                <!-- Botón de Gestión (AHORA DIRECTO A LA TABLA DE GESTIÓN) -->
                <a href="{{ route('calendario.gestion') }}" class="bg-blue-600 text-white hover:bg-blue-700 font-bold text-sm px-4 py-2 rounded-md shadow-sm transition-colors flex items-center">
                    <span>+ Gestionar Contenido</span>
                </a>
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
                    alert('Cliente: ' + info.event.title + '\n' +
                          'Formato: ' + info.event.extendedProps.formato + '\n' +
                          'Estatus: ' + info.event.extendedProps.estatus);
                }
            });
            calendar.render();
        });
    </script>
    @endpush
</x-app-layout>