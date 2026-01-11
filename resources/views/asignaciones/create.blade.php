<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Asignar tarea a empleado
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('asignaciones.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Tarea *</label>
                        <div class="relative" x-data="{ 
                            open: false, 
                            search: '', 
                            tareas: [],
                            selectedTarea: null,
                            loading: false,
                            async loadTareas() {
                                this.loading = true;
                                try {
                                    const response = await fetch(`{{ route('asignaciones.searchTareas') }}?query=${this.search}`);
                                    this.tareas = await response.json();
                                } catch (error) {
                                    console.error('Error:', error);
                                }
                                this.loading = false;
                            },
                            selectTarea(tarea) {
                                this.selectedTarea = tarea;
                                this.search = tarea.titulo;
                                this.open = false;
                                document.getElementById('tarea_id').value = tarea.id;
                            }
                        }" @click.away="open = false">
                            <input type="text" 
                                   x-model="search"
                                   @input="loadTareas(); open = true"
                                   @focus="loadTareas(); open = true"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('tarea_id') border-red-500 @enderror"
                                   placeholder="Buscar tarea..."
                                   autocomplete="off">
                            <input type="hidden" name="tarea_id" id="tarea_id" value="{{ old('tarea_id') }}">
                            
                            <div x-show="open && tareas.length > 0" 
                                 class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-auto">
                                <template x-for="tarea in tareas" :key="tarea.id">
                                    <div @click="selectTarea(tarea)"
                                         class="px-4 py-2 hover:bg-blue-50 cursor-pointer border-b border-gray-100 last:border-0">
                                        <div class="font-medium text-gray-900" x-text="tarea.titulo"></div>
                                        <div class="text-sm text-gray-500">
                                            <span x-text="tarea.cliente?.empresa || (tarea.cliente?.nombre + ' ' + tarea.cliente?.apellido_paterno)"></span>
                                            <span class="mx-1">•</span>
                                            <span x-text="tarea.categoria?.nombre"></span>
                                        </div>
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
                        @error('tarea_id')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Haz clic para ver todas las tareas disponibles.</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Empleado responsable *</label>
                        <div class="relative" x-data="{ 
                            open: false, 
                            search: '', 
                            empleados: [],
                            selectedEmpleado: null,
                            loading: false,
                            async loadEmpleados() {
                                this.loading = true;
                                try {
                                    const response = await fetch(`{{ route('asignaciones.searchEmpleados') }}?query=${this.search}`);
                                    this.empleados = await response.json();
                                } catch (error) {
                                    console.error('Error:', error);
                                }
                                this.loading = false;
                            },
                            selectEmpleado(empleado) {
                                this.selectedEmpleado = empleado;
                                this.search = `${empleado.nombre} ${empleado.apellido_paterno}`;
                                this.open = false;
                                document.getElementById('empleado_id').value = empleado.id_empleado;
                            }
                        }" @click.away="open = false">
                            <input type="text" 
                                   x-model="search"
                                   @input="loadEmpleados(); open = true"
                                   @focus="loadEmpleados(); open = true"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('empleado_id') border-red-500 @enderror"
                                   placeholder="Buscar empleado..."
                                   autocomplete="off">
                            <input type="hidden" name="empleado_id" id="empleado_id" value="{{ old('empleado_id') }}">
                            
                            <div x-show="open && empleados.length > 0" 
                                 class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-auto">
                                <template x-for="empleado in empleados" :key="empleado.id_empleado">
                                    <div @click="selectEmpleado(empleado)"
                                         class="px-4 py-2 hover:bg-green-50 cursor-pointer border-b border-gray-100 last:border-0">
                                        <div class="font-medium text-gray-900" x-text="`${empleado.nombre} ${empleado.apellido_paterno} ${empleado.apellido_materno || ''}`"></div>
                                        <div class="text-sm text-gray-500" x-text="empleado.puesto"></div>
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
                        @error('empleado_id')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Haz clic para ver todos los empleados activos.</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Prioridad *</label>
                        <select name="prioridad" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('prioridad') border-red-500 @enderror"
                                required>
                            <option value="">Seleccionar prioridad</option>
                            <option value="baja" {{ old('prioridad') == 'baja' ? 'selected' : '' }}>🟢 Baja</option>
                            <option value="media" {{ old('prioridad') == 'media' ? 'selected' : '' }}>🔵 Media</option>
                            <option value="alta" {{ old('prioridad') == 'alta' ? 'selected' : '' }}>🟠 Alta</option>
                            <option value="urgente" {{ old('prioridad') == 'urgente' ? 'selected' : '' }}>🔴 Urgente</option>
                        </select>
                        @error('prioridad')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Fecha límite *</label>
                        <input type="date" name="fecha_limite" value="{{ old('fecha_limite') }}"
                               min="{{ date('Y-m-d') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('fecha_limite') border-red-500 @enderror"
                               required>
                        @error('fecha_limite')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end gap-4 mt-6">
                        <a href="{{ route('asignaciones.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Cancelar
                        </a>
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Asignar tarea
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
