{{-- resources/views/clientes/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de clientes
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Mensaje de éxito --}}
            @if (session('success'))
                <div class="mb-4 p-4 rounded-md bg-green-50 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 rounded-md bg-red-50 text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    {{-- Cabecera de la tarjeta: Título y Botón Crear --}}
                    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <h3 class="text-xl font-bold text-gray-900">
                            Lista de clientes
                        </h3>
                        
                        <a href="{{ route('clientes.create') }}"
                           class="inline-flex items-center mt-3 sm:mt-0 px-4 py-2 bg-blue-600 ...">
                            <svg class="w-4 h-4 mr-2 -ml-1" fill="none" ...></svg>
                            Crear cliente
                        </a>
                    </div>

                    
                    {{-- Este bloque de 'nav' es nuevo --}}
                    <div class="mb-4 border-b border-gray-200">
                        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                            
                            {{-- Tab de Activos --}}
                            <a href="{{ route('clientes.index') }}" @class([
                                'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm',
                                'border-blue-500 text-blue-600' => $estatusFilter == 'activo',
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' => $estatusFilter != 'activo'
                            ])>
                                Activos
                            </a>
                            
                            {{-- Tab de Inactivos --}}
                            <a href="{{ route('clientes.index', ['estatus' => 'inactivo']) }}" @class([
                                'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm',
                                'border-blue-500 text-blue-600' => $estatusFilter == 'inactivo',
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' => $estatusFilter != 'inactivo'
                            ])>
                                Inactivos
                            </a>
                        </nav>
                    </div>


                    <div class="mb-4">
                        <input type="text" 
                               id="search" 
                               name="search"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                               {{-- El placeholder ahora es dinámico --}}
                               placeholder="Buscar en {{ $estatusFilter == 'activo' ? 'activos' : 'inactivos' }}...">
                    </div>

                    {{-- Contenido: Tabla o Estado Vacío --}}
                    {{-- La condición @if ahora es más compleja --}}
                    @if ($clientes->isEmpty() && !request()->has('query') && $estatusFilter == 'activo') 
                        
                        {{-- Estado Vacío Mejorado --}}
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" ...></svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay clientes</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Comienza creando un nuevo cliente.
                            </p>
                            <div class="mt-6">
                                <a href="{{ route('clientes.create') }}"
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 ...">
                                    <svg class="w-4 h-4 mr-2 -ml-1" ...></svg>
                                    Crear cliente
                                </a>
                            </div>
                        </div>

                    @elseif ($clientes->isEmpty() && ($estatusFilter == 'inactivo' || request()->has('query')))
                        {{-- Mensaje de vacío para 'Inactivos' o si la búsqueda no arrojó nada --}}
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" ...></svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">
                                No se encontraron clientes
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">
                                {{ $estatusFilter == 'inactivo' ? 'No hay clientes marcados como inactivos.' : 'Intenta con otro término de búsqueda.' }}
                            </p>
                        </div>

                    @else
                        
                        {{-- Tabla Mejorada (esto se queda igual) --}}
                        <div class="overflow-x-auto border rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    {{--
                                        FUNCIÓN AUXILIAR PARA LOS ENLACES DE ORDENACIÓN
                                        Esta función crea un enlace para una cabecera de tabla.
                                        - $column: El nombre de la columna en la BD.
                                        - $label: El texto que se mostrará en la cabecera.
                                    --}}
                                    @php
                                    function render_sortable_header($column, $label, $estatusFilter, $sortBy, $sortDir) {
                                        // Determina la dirección de ordenación para el enlace
                                        $linkSortDir = ($sortBy == $column && $sortDir == 'asc') ? 'desc' : 'asc';
                                        
                                        // Construye la URL con los parámetros correctos
                                        $url = route('clientes.index', [
                                            'estatus' => $estatusFilter,
                                            'sort_by' => $column,
                                            'sort_dir' => $linkSortDir,
                                        ]);

                                        // Determina si esta es la columna activa para mostrar el icono
                                        $isCurrentSort = $sortBy == $column;
                                        $arrow = $sortDir == 'asc' ? '▲' : '▼';

                                        echo '<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">';
                                        echo '<a href="' . $url . '" class="flex items-center gap-2 hover:text-gray-900">';
                                        echo $label;
                                        if ($isCurrentSort) {
                                            echo '<span class="text-xs">' . $arrow . '</span>';
                                        }
                                        echo '</a>';
                                        echo '</th>';
                                    }
                                    @endphp

                                    <tr>
                                        {{-- Usamos la función para crear las cabeceras ordenables --}}
                                        @php render_sortable_header('nombre', 'Nombre', $estatusFilter, $sortBy, $sortDir) @endphp
                                        @php render_sortable_header('telefono', 'Teléfono', $estatusFilter, $sortBy, $sortDir) @endphp
                                        @php render_sortable_header('empresa', 'Empresa', $estatusFilter, $sortBy, $sortDir) @endphp
                                        @php render_sortable_header('giro_sector', 'Giro/Sector', $estatusFilter, $sortBy, $sortDir) @endphp
                                        @php render_sortable_header('fecha_registro', 'Fecha Registro', $estatusFilter, $sortBy, $sortDir) @endphp
                                        
                                        {{-- Cabecera condicional para la fecha de baja --}}
                                        @if($estatusFilter === 'inactivo')
                                            {{-- Ahora esta columna también es ordenable --}}
                                            @php render_sortable_header('fecha_baja', 'Fecha Baja', $estatusFilter, $sortBy, $sortDir) @endphp
                                        @endif

                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estatus</th>
                                        <th scope="col" class="relative px-6 py-3"><span class="sr-only">Acciones</span></th>
                                    </tr>
                                </thead>
                                <tbody id="lista-clientes" class="bg-white divide-y divide-gray-200">
                                    {{-- Ahora el parcial renderizará las nuevas columnas --}}
                                    @include('clientes._tabla_clientes', ['clientes' => $clientes])
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // --- 1. LÓGICA DE BÚSQUEDA (MODIFICADA) ---
            const searchInput = document.getElementById('search');
            const resultsContainer = document.getElementById('lista-clientes');
            
            if (resultsContainer) {
                const searchUrl = {!! json_encode(route('clientes.search')) !!};
                
                // ¡NUEVO! Leemos el estatus actual de la variable que pasó el controlador
                const currentEstatus = {!! json_encode($estatusFilter) !!};

                searchInput.addEventListener('keyup', function () {
                    let query = this.value;

                    // ¡MODIFICADO! Ahora la URL incluye el estatus
                    let url = `${searchUrl}?query=${query}&estatus=${currentEstatus}`;

                    fetch(url)
                        .then(response => response.text()) 
                        .then(html => {
                            resultsContainer.innerHTML = html;
                        })
                        .catch(error => {
                            console.error('Error en la búsqueda:', error);
                            // El colspan es dinámico: 7 para activos, 8 para inactivos
                            const colspan = currentEstatus === 'inactivo' ? 8 : 7;
                            resultsContainer.innerHTML = `<tr><td colspan="${colspan}" class="text-center py-4">Error al cargar resultados.</td></tr>`;
                        });
                });
            }

            // --- 2. LÓGICA DE FILAS CLICKEABLES (MODIFICADO) ---
            const tableBody = document.getElementById('lista-clientes');
            // ¡NUEVO! Leemos el estatus actual de nuevo para esta lógica
            const estatusParaClick = {!! json_encode($estatusFilter) !!};

            if (tableBody) {
                tableBody.addEventListener('click', (e) => {
                    const row = e.target.closest('.clickable-row');
                    
                    if (!row) return; 

                    if (e.target.closest('button') || e.target.closest('form') || e.target.closest('a')) {
                        return;
                    }
                    
                    // ¡MODIFICADO! Solo redirige a 'edit' si estamos en la pestaña 'activo'
                    if (estatusParaClick === 'activo') {
                        window.location.href = row.dataset.href;
                    }
                });
            }

        });
    </script>
    @endpush
</x-app-layout>