<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Tareas
        </h2>
    </x-slot>

    <div class="py-12">
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

            @if (session('error'))
                <div class="mb-4 p-4 rounded-md bg-red-50 text-red-700 border border-red-200">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <h3 class="text-xl font-bold text-gray-900">
                            Lista de tareas
                        </h3>
                        
                        <a href="{{ route('tareas.create') }}"
                           class="inline-flex items-center mt-3 sm:mt-0 px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Crear tarea
                        </a>
                    </div>

                    <div class="mb-4">
                        <input type="text" 
                               id="search" 
                               name="search"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                               placeholder="Buscar tareas...">
                    </div>

                    @if ($tareas->isEmpty() && !request()->has('query'))
                        
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay tareas</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Comienza creando una nueva tarea.
                            </p>
                            <div class="mt-6">
                                <a href="{{ route('tareas.create') }}"
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Crear tarea
                                </a>
                            </div>
                        </div>

                    @else
                        
                        <div class="overflow-x-auto border rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Título
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Cliente
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Categoría
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Fecha Creación
                                        </th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">Acciones</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="lista-tareas" class="bg-white divide-y divide-gray-200">
                                    @include('tareas._tabla_tareas', ['tareas' => $tareas])
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
                <h3 class="text-lg font-bold text-gray-900 text-center mb-2">¿Eliminar tarea?</h3>
                <p class="text-sm text-gray-600 text-center mb-6">
                    ¿Estás seguro de que deseas eliminar la tarea <strong id="tareaTitle"></strong>? 
                    Esta acción no se puede deshacer.
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
                            Sí, eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function openDeleteModal(tareaId, tareaTitle) {
            document.getElementById('tareaTitle').textContent = tareaTitle;
            document.getElementById('deleteForm').action = `/tareas/${tareaId}`;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        document.getElementById('deleteModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDeleteModal();
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('search');
            const resultsContainer = document.getElementById('lista-tareas');
            
            if (resultsContainer) {
                const searchUrl = {!! json_encode(route('tareas.search')) !!};

                searchInput.addEventListener('keyup', function () {
                    let query = this.value;
                    let url = `${searchUrl}?query=${query}`;

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

            const tableBody = document.getElementById('lista-tareas');

            if (tableBody) {
                tableBody.addEventListener('click', (e) => {
                    const row = e.target.closest('.clickable-row');
                    
                    if (!row) return; 

                    if (e.target.closest('button') || e.target.closest('form') || e.target.closest('a')) {
                        return;
                    }
                    
                    window.location.href = row.dataset.href;
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
