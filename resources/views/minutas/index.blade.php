<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Minutas
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- BARRA DE HERRAMIENTAS --}}
            <div class="mb-6 space-y-4">
                
                {{-- Fila 1: Filtros de Estatus --}}
                <div class="flex justify-between items-center">
                    <div class="flex bg-white rounded-lg shadow-sm p-1">
                        @php
                            $claseBase = "px-4 py-2 text-sm font-medium rounded-md transition-colors ";
                            $claseActiva = "bg-emerald-100 text-emerald-700";
                            $claseInactiva = "text-gray-500 hover:text-gray-700 hover:bg-gray-50";
                            $current = request('estatus', 'todas');
                        @endphp

                        <a href="{{ route('minutas.index', ['estatus' => 'todas']) }}" 
                           class="{{ $claseBase }} {{ $current == 'todas' ? $claseActiva : $claseInactiva }}">
                            Todas
                        </a>
                        <a href="{{ route('minutas.index', ['estatus' => 'pendiente']) }}" 
                           class="{{ $claseBase }} {{ $current == 'pendiente' ? $claseActiva : $claseInactiva }}">
                            Con Pendientes
                        </a>
                        <a href="{{ route('minutas.index', ['estatus' => 'completado']) }}" 
                           class="{{ $claseBase }} {{ $current == 'completado' ? $claseActiva : $claseInactiva }}">
                            Completadas
                        </a>
                    </div>

                    <a href="{{ route('minutas.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 transition ease-in-out duration-150">
                        + Nueva
                    </a>
                </div>

                {{-- Fila 2: Filtros y Búsqueda --}}
                <div class="bg-white rounded-lg shadow-sm p-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                        {{-- Búsqueda por texto --}}
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Buscar</label>
                            <input type="text" id="search" placeholder="Título, cliente o empresa..." 
                                   class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-emerald-500 focus:border-emerald-500">
                        </div>

                        {{-- Filtro por Folio --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Folio</label>
                            <input type="text" id="filter-folio" placeholder="Ej: 1" 
                                   class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-emerald-500 focus:border-emerald-500">
                        </div>

                        {{-- Filtro por Fecha --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Fecha</label>
                            <input type="date" id="filter-fecha" 
                                   class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                    </div>

                    {{-- Botón para limpiar filtros --}}
                    <div class="mt-3 flex justify-end">
                        <button type="button" id="clear-filters" 
                                class="text-xs text-gray-500 hover:text-gray-700 underline">
                            Limpiar filtros
                        </button>
                    </div>
                </div>
            </div>

            {{-- CONTENEDOR DE LA TABLA --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    {{-- Aquí cargamos el parcial --}}
                    <div id="tabla-container">
                        @include('minutas._tabla_minutas')
                    </div>
                </div>
            </div>
            
            {{-- Paginación --}}
            <div class="mt-4">
                @if(method_exists($minutas, 'links'))
                    {{ $minutas->appends(['estatus' => $current])->links() }}
                @endif
            </div>

        </div>
    </div>

    {{-- SCRIPT PARA BÚSQUEDA Y FILTROS AJAX --}}
    <script>
        function aplicarFiltros() {
            let query = document.getElementById('search').value;
            let folio = document.getElementById('filter-folio').value;
            let fecha = document.getElementById('filter-fecha').value;
            let estatus = "{{ request('estatus', 'todas') }}";

            // Construir URL con parámetros
            let url = `{{ route('minutas.search') }}?estatus=${estatus}`;
            if (query) url += `&query=${encodeURIComponent(query)}`;
            if (folio) url += `&folio=${encodeURIComponent(folio)}`;
            if (fecha) url += `&fecha=${encodeURIComponent(fecha)}`;

            fetch(url)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('tabla-container').innerHTML = html;
                });
        }

        // Event listeners para los filtros
        document.getElementById('search').addEventListener('keyup', aplicarFiltros);
        document.getElementById('filter-folio').addEventListener('keyup', aplicarFiltros);
        document.getElementById('filter-fecha').addEventListener('change', aplicarFiltros);

        // Limpiar filtros
        document.getElementById('clear-filters').addEventListener('click', function() {
            document.getElementById('search').value = '';
            document.getElementById('filter-folio').value = '';
            document.getElementById('filter-fecha').value = '';
            aplicarFiltros();
        });
    </script>
</x-app-layout>
