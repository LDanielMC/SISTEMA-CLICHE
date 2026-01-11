@forelse ($tareas as $tarea)
    <tr class="hover:bg-gray-50 cursor-pointer clickable-row" 
        data-href="{{ route('tareas.edit', $tarea->id) }}">
        
        <td class="px-6 py-4">
            <div class="text-sm font-medium text-gray-900">
                {{ $tarea->titulo }}
            </div>
            @if($tarea->descripcion)
                <div class="text-sm text-gray-500 truncate max-w-md">
                    {{ Str::limit($tarea->descripcion, 60) }}
                </div>
            @endif
        </td>

        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900">
                @if($tarea->cliente)
                    @if($tarea->cliente->empresa)
                        {{ $tarea->cliente->empresa }}
                    @else
                        {{ $tarea->cliente->nombre }} {{ $tarea->cliente->apellido_paterno }}
                    @endif
                @else
                    <span class="text-gray-400">Sin cliente</span>
                @endif
            </div>
        </td>

        <td class="px-6 py-4 whitespace-nowrap">
            @if($tarea->categoria)
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                    {{ $tarea->categoria->nombre }}
                </span>
            @else
                <span class="text-gray-400 text-sm">Sin categoría</span>
            @endif
        </td>

        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
            {{ $tarea->created_at->format('d/m/Y H:i') }}
        </td>
        
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
            @if(auth()->user()->rol == 'admin')
                <button type="button" 
                        onclick="event.stopPropagation(); openDeleteModal({{ $tarea->id }}, '{{ addslashes($tarea->titulo) }}')"
                        class="text-red-600 hover:text-red-900" 
                        title="Eliminar">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </button>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
            No se encontraron tareas con ese criterio de búsqueda.
        </td>
    </tr>
@endforelse
