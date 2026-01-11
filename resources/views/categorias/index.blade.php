<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de categorías
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

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

                    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <h3 class="text-xl font-bold text-gray-900">
                            Catálogo de categorías
                        </h3>
                        
                        <a href="{{ route('categorias.create') }}"
                           class="inline-flex items-center mt-3 sm:mt-0 px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Crear categoría
                        </a>
                    </div>

                    <div class="mb-4 border-b border-gray-200">
                        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                            
                            <a href="{{ route('categorias.index') }}" @class([
                                'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm',
                                'border-blue-500 text-blue-600' => $estatusFilter == 'activo',
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' => $estatusFilter != 'activo'
                            ])>
                                Activas
                            </a>
                            
                            <a href="{{ route('categorias.index', ['estatus' => 'inactivo']) }}" @class([
                                'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm',
                                'border-blue-500 text-blue-600' => $estatusFilter == 'inactivo',
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' => $estatusFilter != 'inactivo'
                            ])>
                                Inactivas
                            </a>
                        </nav>
                    </div>

                    <div class="mb-4">
                        <input type="text" 
                               id="search" 
                               name="search"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                               placeholder="Buscar en {{ $estatusFilter == 'activo' ? 'activas' : 'inactivas' }}...">
                    </div>

                    @if ($categorias->isEmpty() && !request()->has('query') && $estatusFilter == 'activo') 
                        
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay categorías</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Comienza creando una nueva categoría.
                            </p>
                            <div class="mt-6">
                                <a href="{{ route('categorias.create') }}"
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Crear categoría
                                </a>
                            </div>
                        </div>

                    @elseif ($categorias->isEmpty() && ($estatusFilter == 'inactivo' || request()->has('query')))
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">
                                No se encontraron categorías
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">
                                {{ $estatusFilter == 'inactivo' ? 'No hay categorías inactivas.' : 'Intenta con otro término de búsqueda.' }}
                            </p>
                        </div>

                    @else
                        
                        <div class="overflow-x-auto border rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    @php
                                    function render_sortable_header_categorias($column, $label, $estatusFilter, $sortBy, $sortDir) {
                                        $linkSortDir = ($sortBy == $column && $sortDir == 'asc') ? 'desc' : 'asc';
                                        
                                        $url = route('categorias.index', [
                                            'estatus' => $estatusFilter,
                                            'sort_by' => $column,
                                            'sort_dir' => $linkSortDir,
                                        ]);

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
                                        @php render_sortable_header_categorias('nombre', 'Nombre', $estatusFilter, $sortBy, $sortDir) @endphp
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Descripción
                                        </th>
                                        @php render_sortable_header_categorias('created_at', 'Fecha Creación', $estatusFilter, $sortBy, $sortDir) @endphp
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Estado
                                        </th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">Acciones</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="lista-categorias" class="bg-white divide-y divide-gray-200">
                                    @include('categorias._tabla_categorias', ['categorias' => $categorias])
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    {{-- Modal de confirmación para eliminar --}}
    <div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 text-center mb-2">¿Desactivar categoría?</h3>
                <p class="text-sm text-gray-600 text-center mb-6">
                    ¿Estás seguro de que deseas desactivar la categoría <strong id="categoryName"></strong>? 
                    Esta acción cambiará su estado a inactiva.
                </p>
                <div class="flex gap-3 justify-end">
                    <button onclick="closeDeleteModal()" 
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-semibold text-sm transition">
                        Cancelar
                    </button>
                    <form id="deleteForm" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold text-sm transition">
                            Sí, desactivar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal de confirmación para reactivar --}}
    <div id="reactivateModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-green-100 rounded-full mb-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 text-center mb-2">¿Reactivar categoría?</h3>
                <p class="text-sm text-gray-600 text-center mb-6">
                    ¿Estás seguro de que deseas reactivar la categoría <strong id="categoryNameReactivate"></strong>? 
                    Esta acción cambiará su estado a activa.
                </p>
                <div class="flex gap-3 justify-end">
                    <button onclick="closeReactivateModal()" 
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-semibold text-sm transition">
                        Cancelar
                    </button>
                    <form id="reactivateForm" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold text-sm transition">
                            Sí, reactivar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function openDeleteModal(categoriaId, categoriaNombre) {
            document.getElementById('categoryName').textContent = categoriaNombre;
            document.getElementById('deleteForm').action = `/categorias/${categoriaId}`;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        function openReactivateModal(categoriaId, categoriaNombre) {
            document.getElementById('categoryNameReactivate').textContent = categoriaNombre;
            document.getElementById('reactivateForm').action = `/categorias/${categoriaId}/reactivar`;
            document.getElementById('reactivateModal').classList.remove('hidden');
        }

        function closeReactivateModal() {
            document.getElementById('reactivateModal').classList.add('hidden');
        }

        // Cerrar modal de eliminar al hacer clic fuera
        document.getElementById('deleteModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

        // Cerrar modal de reactivar al hacer clic fuera
        document.getElementById('reactivateModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeReactivateModal();
            }
        });

        // Cerrar modales con ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDeleteModal();
                closeReactivateModal();
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            
            const searchInput = document.getElementById('search');
            const resultsContainer = document.getElementById('lista-categorias');
            
            if (resultsContainer) {
                const searchUrl = {!! json_encode(route('categorias.search')) !!};
                const currentEstatus = {!! json_encode($estatusFilter) !!};

                searchInput.addEventListener('keyup', function () {
                    let query = this.value;
                    let url = `${searchUrl}?query=${query}&estatus=${currentEstatus}`;

                    fetch(url)
                        .then(response => response.text()) 
                        .then(html => {
                            resultsContainer.innerHTML = html;
                        })
                        .catch(error => {
                            console.error('Error en la búsqueda:', error);
                            resultsContainer.innerHTML = `<tr><td colspan="5" class="text-center py-4">Error al cargar resultados.</td></tr>`;
                        });
                });
            }

            const tableBody = document.getElementById('lista-categorias');
            const estatusParaClick = {!! json_encode($estatusFilter) !!};

            if (tableBody) {
                tableBody.addEventListener('click', (e) => {
                    const row = e.target.closest('.clickable-row');
                    
                    if (!row) return; 

                    if (e.target.closest('button') || e.target.closest('form') || e.target.closest('a')) {
                        return;
                    }
                    
                    if (estatusParaClick === 'activo') {
                        window.location.href = row.dataset.href;
                    }
                });
            }

        });
    </script>
    @endpush
</x-app-layout>
