@forelse ($categorias as $categoria)
    <tr @class([
        'hover:bg-gray-50',
        'cursor-pointer clickable-row' => $categoria->activo
    ]) 
        @if($categoria->activo)
        data-href="{{ route('categorias.edit', $categoria->id) }}"
        @endif
    >
        
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm font-medium text-gray-900">
                {{ $categoria->nombre }}
            </div>
        </td>

        <td class="px-6 py-4">
            <div class="text-sm text-gray-500">
                {{ $categoria->descripcion ?? 'Sin descripción' }}
            </div>
        </td>

        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
            {{ $categoria->created_at->format('d/m/Y') }}
        </td>
        
        <td class="px-6 py-4 whitespace-nowrap">
            <span @class([
                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                'bg-green-100 text-green-800' => $categoria->activo,
                'bg-red-100 text-red-800' => !$categoria->activo,
            ])>
                {{ $categoria->activo ? 'Activa' : 'Inactiva' }}
            </span>
        </td>
        
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
            @if(auth()->user()->rol == 'admin')

                @if(!$categoria->activo)
                    
                    <button type="button" 
                            onclick="openReactivateModal({{ $categoria->id }}, '{{ addslashes($categoria->nombre) }}')"
                            class="text-green-600 hover:text-green-900" 
                            title="Reactivar">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </button>

                @else

                    <button type="button" 
                            onclick="openDeleteModal({{ $categoria->id }}, '{{ addslashes($categoria->nombre) }}')"
                            class="text-red-600 hover:text-red-900" 
                            title="Desactivar">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </button>
                @endif
                
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
            No se encontraron categorías con ese criterio de búsqueda.
        </td>
    </tr>
@endforelse
