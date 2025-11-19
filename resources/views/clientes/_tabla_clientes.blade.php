{{-- resources/views/clientes/_tabla_clientes.blade.php --}}

@forelse ($clientes as $cliente)
    
    {{-- 
      La fila solo es clickeable y tiene cursor de puntero 
      si el estatus es 'activo'.
    --}}
    <tr @class([
        'hover:bg-gray-50',
        'cursor-pointer clickable-row' => $cliente->estatus == 'activo'
    ]) 
        @if($cliente->estatus == 'activo')
        data-href="{{ route('clientes.edit', $cliente->id_cliente) }}"
        @endif
    >
        
        {{-- Celda combinada de Nombre y Correo --}}
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
                <div>
                    <div class="text-sm font-medium text-gray-900">
                        {{ $cliente->nombre }} {{ $cliente->apellido_paterno }} {{ $cliente->apellido_materno }}
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ $cliente->user->email ?? 'Sin email' }}
                    </div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
            {{ $cliente->telefono }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
            {{ $cliente->empresa }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
            {{ $cliente->giro_sector }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
            {{ $cliente->fecha_registro ? $cliente->fecha_registro->format('d/m/Y') : 'N/A' }}
        </td>

        {{-- Mostramos la fecha de baja solo si estamos en la vista de inactivos --}}
        @if(request()->query('estatus') === 'inactivo')
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ $cliente->fecha_baja ? $cliente->fecha_baja->format('d/m/Y') : 'N/A' }}
            </td>
        @endif
        
        {{-- Celda de Estatus con Etiqueta (Badge) --}}
        <td class="px-6 py-4 whitespace-nowrap">
            {{-- 
              Ajuste: 'inactivo' se cambia por 'inactivo' para que 
              coincida con la lógica de tu controlador.
            --}}
            <span @class([
                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                'bg-green-100 text-green-800' => $cliente->estatus == 'activo',
                'bg-red-100 text-red-800' => $cliente->estatus == 'inactivo', // <-- Modificado
                'bg-gray-100 text-gray-800' => !in_array($cliente->estatus, ['activo', 'inactivo']), // <-- Modificado
            ])>
                {{ ucfirst($cliente->estatus) }}
            </span>
        </td>
        
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
            @if(auth()->user()->rol == 'admin')

                @if($cliente->estatus == 'inactivo')
                    
                    {{-- Si está INACTIVO, muestra el botón REACTIVAR --}}
                    <form action="{{ route('clientes.reactivar', $cliente->id_cliente) }}"
                          method="POST"
                          class="inline"
                          onsubmit="return confirm('¿Estás seguro de reactivar a este cliente?');">
                        @csrf
                        @method('PATCH') {{-- Usamos PATCH para actualizar --}}
                        <button type="submit" class="text-green-600 hover:text-green-900" title="Reactivar">
                            {{-- Icono de Reactivar (check-circle) --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </form>

                @else

                    {{-- Si está ACTIVO, muestra el botón MARCAR COMO INACTIVO (tu código original) --}}
                    <form action="{{ route('clientes.destroy', $cliente->id_cliente) }}"
                          method="POST"
                          class="inline"
                          onsubmit="return confirm('¿Estás seguro de que deseas marcar como inactivo a este cliente?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900" title="Marcar como inactivo">
                            {{-- Icono de bote de basura (tu código original) --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </form>
                @endif
                
            @endif
        </td>
    </tr>
@empty
    <tr>
        {{-- El colspan es dinámico: 7 para activos, 8 para inactivos --}}
        <td colspan="{{ request()->query('estatus') === 'inactivo' ? '8' : '7' }}" class="px-6 py-12 text-center text-gray-500">
            No se encontraron clientes con ese criterio de búsqueda.
        </td>
    </tr>
@endforelse