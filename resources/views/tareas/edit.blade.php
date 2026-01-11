<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar tarea
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('tareas.update', $tarea->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Título de la tarea *</label>
                        <input type="text" name="titulo" value="{{ old('titulo', $tarea->titulo) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('titulo') border-red-500 @enderror"
                               required maxlength="200">
                        @error('titulo')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Cliente *</label>
                        <div class="relative" x-data="{ 
                            open: false, 
                            search: '{{ $tarea->cliente ? ($tarea->cliente->empresa ?: $tarea->cliente->nombre . ' ' . $tarea->cliente->apellido_paterno) : '' }}', 
                            clientes: [],
                            selectedCliente: {{ $tarea->cliente ? json_encode($tarea->cliente) : 'null' }},
                            loading: false,
                            async loadClientes() {
                                this.loading = true;
                                try {
                                    const response = await fetch(`{{ route('tareas.searchClientes') }}?query=${this.search}`);
                                    this.clientes = await response.json();
                                } catch (error) {
                                    console.error('Error:', error);
                                }
                                this.loading = false;
                            },
                            async searchClientes() {
                                await this.loadClientes();
                            },
                            selectCliente(cliente) {
                                this.selectedCliente = cliente;
                                this.search = cliente.empresa || `${cliente.nombre} ${cliente.apellido_paterno}`;
                                this.open = false;
                                document.getElementById('cliente_id').value = cliente.id_cliente;
                            }
                        }" @click.away="open = false">
                            <input type="text" 
                                   x-model="search"
                                   @input="searchClientes(); open = true"
                                   @focus="loadClientes(); open = true"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('cliente_id') border-red-500 @enderror"
                                   placeholder="Buscar cliente por nombre o empresa..."
                                   autocomplete="off">
                            <input type="hidden" name="cliente_id" id="cliente_id" value="{{ old('cliente_id', $tarea->cliente_id) }}">
                            
                            <div x-show="open && clientes.length > 0" 
                                 class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-auto">
                                <template x-for="cliente in clientes" :key="cliente.id_cliente">
                                    <div @click="selectCliente(cliente)"
                                         class="px-4 py-2 hover:bg-blue-50 cursor-pointer border-b border-gray-100 last:border-0">
                                        <div class="font-medium text-gray-900" x-text="cliente.empresa || `${cliente.nombre} ${cliente.apellido_paterno}`"></div>
                                        <div class="text-sm text-gray-500" x-text="cliente.correo_contacto"></div>
                                    </div>
                                </template>
                            </div>
                            
                            <div x-show="loading" class="absolute right-3 top-3">
                                <svg class="animate-spin h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                        </div>
                        @error('cliente_id')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Haz clic en el campo para ver todos los clientes disponibles.</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Categoría *</label>
                        <div class="relative" x-data="{ 
                            open: false, 
                            search: '{{ $tarea->categoria ? $tarea->categoria->nombre : '' }}', 
                            categorias: [],
                            selectedCategoria: {{ $tarea->categoria ? json_encode($tarea->categoria) : 'null' }},
                            loading: false,
                            async loadCategorias() {
                                this.loading = true;
                                try {
                                    const response = await fetch(`{{ route('tareas.searchCategorias') }}?query=${this.search}`);
                                    this.categorias = await response.json();
                                } catch (error) {
                                    console.error('Error:', error);
                                }
                                this.loading = false;
                            },
                            async searchCategorias() {
                                await this.loadCategorias();
                            },
                            selectCategoria(categoria) {
                                this.selectedCategoria = categoria;
                                this.search = categoria.nombre;
                                this.open = false;
                                document.getElementById('categoria_id').value = categoria.id;
                            }
                        }" @click.away="open = false">
                            <input type="text" 
                                   x-model="search"
                                   @input="searchCategorias(); open = true"
                                   @focus="loadCategorias(); open = true"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('categoria_id') border-red-500 @enderror"
                                   placeholder="Buscar categoría..."
                                   autocomplete="off">
                            <input type="hidden" name="categoria_id" id="categoria_id" value="{{ old('categoria_id', $tarea->categoria_id) }}">
                            
                            <div x-show="open && categorias.length > 0" 
                                 class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-auto">
                                <template x-for="categoria in categorias" :key="categoria.id">
                                    <div @click="selectCategoria(categoria)"
                                         class="px-4 py-2 hover:bg-purple-50 cursor-pointer border-b border-gray-100 last:border-0">
                                        <div class="font-medium text-gray-900" x-text="categoria.nombre"></div>
                                        <div class="text-sm text-gray-500" x-text="categoria.descripcion || 'Sin descripción'"></div>
                                    </div>
                                </template>
                            </div>
                            
                            <div x-show="loading" class="absolute right-3 top-3">
                                <svg class="animate-spin h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                        </div>
                        @error('categoria_id')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Haz clic en el campo para ver todas las categorías disponibles.</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Descripción *</label>
                        <textarea name="descripcion" rows="4"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('descripcion') border-red-500 @enderror"
                                  required>{{ old('descripcion', $tarea->descripcion) }}</textarea>
                        @error('descripcion')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Observaciones</label>
                        <textarea name="observaciones" rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                  placeholder="Observaciones adicionales (opcional)">{{ old('observaciones', $tarea->observaciones) }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Opcional.</p>
                    </div>

                    <div class="mb-4 p-4 bg-gray-50 rounded-md border border-gray-200">
                        <div class="text-sm text-gray-700">
                            <strong>Fecha de creación:</strong> {{ $tarea->created_at->format('d/m/Y H:i') }}
                        </div>
                        @if($tarea->updated_at != $tarea->created_at)
                            <div class="text-sm text-gray-700 mt-1">
                                <strong>Última actualización:</strong> {{ $tarea->updated_at->format('d/m/Y H:i') }}
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center justify-end gap-4 mt-6">
                        <a href="{{ route('tareas.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Cancelar
                        </a>
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Actualizar tarea
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
