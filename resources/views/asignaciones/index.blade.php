<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Asignación de Tareas
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
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-.75-4.75a.75.75 0 011.5 0v.5a.75.75 0 01-1.5 0v-.5zM10 5.5a.75.75 0 01.75.75v6a.75.75 0 01-1.5 0v-6A.75.75 0 0110 5.5z" clip-rule="evenodd"/>
                        </svg>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <h3 class="text-xl font-bold text-gray-900">
                            Tareas asignadas a empleados
                        </h3>
                        
                        <a href="{{ route('asignaciones.create') }}"
                           class="inline-flex items-center mt-3 sm:mt-0 px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Asignar tarea
                        </a>
                    </div>

                    {{-- Filtros y búsqueda --}}
                    <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <form method="GET" action="{{ route('asignaciones.index') }}" class="space-y-4">
                            
                            {{-- Buscador general --}}
                            <div>
                                <label for="buscar" class="block text-sm font-medium text-gray-700 mb-1">
                                    Buscar
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </div>
                                    <input type="text" 
                                           name="buscar" 
                                           id="buscar"
                                           value="{{ request('buscar') }}"
                                           placeholder="Buscar por tarea, empleado o cliente..."
                                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                </div>
                            </div>

                            {{-- Filtros en grid --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                                
                                {{-- Filtro por empleado --}}
                                <div>
                                    <label for="empleado" class="block text-sm font-medium text-gray-700 mb-1">
                                        Empleado
                                    </label>
                                    <select name="empleado" 
                                            id="empleado"
                                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        <option value="">Todos</option>
                                        @foreach($empleados as $empleado)
                                            <option value="{{ $empleado->id_empleado }}" {{ request('empleado') == $empleado->id_empleado ? 'selected' : '' }}>
                                                {{ $empleado->nombre }} {{ $empleado->apellido_paterno }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Filtro por prioridad --}}
                                <div>
                                    <label for="prioridad" class="block text-sm font-medium text-gray-700 mb-1">
                                        Prioridad
                                    </label>
                                    <select name="prioridad" 
                                            id="prioridad"
                                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        <option value="">Todas</option>
                                        <option value="baja" {{ request('prioridad') == 'baja' ? 'selected' : '' }}>🟢 Baja</option>
                                        <option value="media" {{ request('prioridad') == 'media' ? 'selected' : '' }}>🔵 Media</option>
                                        <option value="alta" {{ request('prioridad') == 'alta' ? 'selected' : '' }}>🟠 Alta</option>
                                        <option value="urgente" {{ request('prioridad') == 'urgente' ? 'selected' : '' }}>🔴 Urgente</option>
                                    </select>
                                </div>

                                {{-- Filtro por categoría --}}
                                <div>
                                    <label for="categoria" class="block text-sm font-medium text-gray-700 mb-1">
                                        Categoría
                                    </label>
                                    <select name="categoria" 
                                            id="categoria"
                                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        <option value="">Todas</option>
                                        @foreach($categorias as $categoria)
                                            <option value="{{ $categoria->id }}" {{ request('categoria') == $categoria->id ? 'selected' : '' }}>
                                                {{ $categoria->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Filtro por estado empleado --}}
                                <div>
                                    <label for="estado_empleado" class="block text-sm font-medium text-gray-700 mb-1">
                                        Estado Empleado
                                    </label>
                                    <select name="estado_empleado" 
                                            id="estado_empleado"
                                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        <option value="">Todos</option>
                                        <option value="asignada" {{ request('estado_empleado') == 'asignada' ? 'selected' : '' }}>Asignada</option>
                                        <option value="en_proceso" {{ request('estado_empleado') == 'en_proceso' ? 'selected' : '' }}>En Proceso</option>
                                        <option value="terminada" {{ request('estado_empleado') == 'terminada' ? 'selected' : '' }}>Terminada</option>
                                    </select>
                                </div>

                                {{-- Filtro por estado admin --}}
                                <div>
                                    <label for="estado_admin" class="block text-sm font-medium text-gray-700 mb-1">
                                        Evaluación Admin
                                    </label>
                                    <select name="estado_admin" 
                                            id="estado_admin"
                                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        <option value="">Todas</option>
                                        <option value="pendiente" {{ request('estado_admin') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="completa" {{ request('estado_admin') == 'completa' ? 'selected' : '' }}>Completa</option>
                                        <option value="parcialmente_completa" {{ request('estado_admin') == 'parcialmente_completa' ? 'selected' : '' }}>Parcialmente Completa</option>
                                        <option value="incompleta" {{ request('estado_admin') == 'incompleta' ? 'selected' : '' }}>Incompleta</option>
                                    </select>
                                </div>

                            </div>

                            {{-- Botones de acción --}}
                            <div class="flex items-center gap-3">
                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                    </svg>
                                    Filtrar
                                </button>
                                
                                <a href="{{ route('asignaciones.index') }}"
                                   class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Limpiar
                                </a>

                                @if(request()->hasAny(['buscar', 'empleado', 'prioridad', 'categoria', 'estado_empleado', 'estado_admin']))
                                    <span class="text-sm text-gray-600">
                                        <strong>{{ $asignaciones->count() }}</strong> resultado(s) encontrado(s)
                                    </span>
                                @endif
                            </div>

                        </form>
                    </div>

                    @if ($asignaciones->isEmpty())
                        
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay asignaciones</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Comienza asignando una tarea a un empleado.
                            </p>
                            <div class="mt-6">
                                <a href="{{ route('asignaciones.create') }}"
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Asignar tarea
                                </a>
                            </div>
                        </div>

                    @else
                        
                        <div class="overflow-x-auto border rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tarea
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Empleado
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Prioridad
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Fecha Límite
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Estado
                                        </th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">Acciones</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($asignaciones as $asignacion)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $asignacion->tarea->titulo }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $asignacion->tarea->cliente->empresa ?? $asignacion->tarea->cliente->nombre }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    {{ $asignacion->empleado->nombre }} {{ $asignacion->empleado->apellido_paterno }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $asignacion->empleado->puesto }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded {{ $asignacion->prioridad_color }}">
                                                    {{ $asignacion->prioridad_texto }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $asignacion->fecha_limite->format('d/m/Y') }}
                                                @if($asignacion->estaVencida())
                                                    <span class="block text-red-600 font-semibold">⚠️ Vencida</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-xs">
                                                    <div class="mb-1">
                                                        <span class="font-semibold">Empleado:</span>
                                                        <span class="px-2 py-1 rounded {{ 
                                                            $asignacion->estado_empleado === 'terminada' ? 'bg-green-100 text-green-800' : 
                                                            ($asignacion->estado_empleado === 'en_proceso' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') 
                                                        }}">
                                                            {{ ucfirst(str_replace('_', ' ', $asignacion->estado_empleado)) }}
                                                        </span>
                                                    </div>
                                                    @if($asignacion->estado_admin)
                                                        <div>
                                                            <span class="font-semibold">Admin:</span>
                                                            <span class="px-2 py-1 rounded {{ 
                                                                $asignacion->estado_admin === 'completa' ? 'bg-green-100 text-green-800' : 
                                                                ($asignacion->estado_admin === 'parcialmente_completa' ? 'bg-yellow-100 text-yellow-800' : 
                                                                ($asignacion->estado_admin === 'incompleta' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) 
                                                            }}">
                                                                {{ ucfirst(str_replace('_', ' ', $asignacion->estado_admin)) }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('asignaciones.tareasEmpleado', $asignacion->empleado) }}"
                                                   class="text-blue-600 hover:text-blue-900 mr-3" 
                                                   title="Evaluar tareas">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                </a>
                                                <form action="{{ route('asignaciones.destroy', $asignacion) }}" 
                                                      method="POST" 
                                                      class="inline"
                                                      onsubmit="return confirm('¿Estás seguro de eliminar esta asignación?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Eliminar">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 h-5 inline" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
