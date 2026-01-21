<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Cotizaciones
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- BARRA DE HERRAMIENTAS --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                
                {{-- Filtros de Estatus (Pestañas visuales) --}}
                <div class="flex bg-white rounded-lg shadow-sm p-1">
                    @php
                        $claseBase = "px-4 py-2 text-sm font-medium rounded-md transition-colors ";
                        $claseActiva = "bg-emerald-100 text-emerald-700";
                        $claseInactiva = "text-gray-500 hover:text-gray-700 hover:bg-gray-50";
                        $current = request('estatus', 'todas');
                    @endphp

                    <a href="{{ route('cotizaciones.index', ['estatus' => 'todas']) }}" 
                       class="{{ $claseBase }} {{ $current == 'todas' ? $claseActiva : $claseInactiva }}">
                        Todas
                    </a>
                    <a href="{{ route('cotizaciones.index', ['estatus' => 'pendiente']) }}" 
                       class="{{ $claseBase }} {{ $current == 'pendiente' ? $claseActiva : $claseInactiva }}">
                        Pendientes
                    </a>
                    <a href="{{ route('cotizaciones.index', ['estatus' => 'aceptada']) }}" 
                       class="{{ $claseBase }} {{ $current == 'aceptada' ? $claseActiva : $claseInactiva }}">
                        Aceptadas
                    </a>
                    {{-- Nueva pestaña para Rechazadas --}}
                    <a href="{{ route('cotizaciones.index', ['estatus' => 'rechazada']) }}" 
                       class="{{ $claseBase }} {{ $current == 'rechazada' ? $claseActiva : $claseInactiva }}">
                        Rechazadas
                    </a>
                </div>

                {{-- Buscador y Botón Crear --}}
                <div class="flex gap-2 w-full md:w-auto">
                    <input type="text" id="search" placeholder="Buscar por titulo, cliente o folio..." 
                           class="w-full md:w-64 border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                    
                    <a href="{{ route('cotizaciones.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 transition ease-in-out duration-150">
                        + Nueva
                    </a>
                </div>
            </div>

            {{-- CONTENEDOR DE LA TABLA --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    {{-- Aquí cargamos el parcial --}}
                    <div id="tabla-container">
                        @include('cotizaciones._tabla_cotizaciones')
                    </div>
                </div>
            </div>
            
            {{-- Paginación (si no es búsqueda ajax) --}}
            <div class="mt-4">
                {{-- Verificamos si es una colección paginada antes de llamar a links() --}}
                @if(method_exists($cotizaciones, 'links'))
                    {{ $cotizaciones->appends(['estatus' => $current])->links() }}
                @endif
            </div>

        </div>
    </div>

    {{-- SCRIPT PARA BÚSQUEDA AJAX --}}
    <script>
        document.getElementById('search').addEventListener('keyup', function() {
            let query = this.value;
            let estatus = "{{ request('estatus', 'todas') }}";

            fetch(`{{ route('cotizaciones.search') }}?query=${query}&estatus=${estatus}`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('tabla-container').innerHTML = html;
                });
        });
    </script>
</x-app-layout>