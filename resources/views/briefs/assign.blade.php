<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[#0149a8] leading-tight">
            {{ __('Asignar Brief') }}: {{ $brief->titulo }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Mensajes de Estado -->
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="flex gap-6 flex-col md:flex-row">
                
                <!-- Columna Izquierda: Formulario de Asignación -->
                <div class="w-full md:w-1/3">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="font-bold text-lg mb-4 text-gray-700">Nueva Asignación</h3>
                            
                            <form action="{{ route('briefs.storeAssignment', $brief->id) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="id_cliente" class="block text-sm font-medium text-gray-700 mb-1">Seleccionar Cliente</label>
                                    <select name="id_cliente" id="id_cliente" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">-- Seleccione --</option>
                                        @foreach($clientes as $cliente)
                                            <option value="{{ $cliente->id_cliente }}">
                                                {{ $cliente->empresa ?: $cliente->nombre . ' ' . $cliente->apellido_paterno }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_cliente')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="flex justify-end">
                                    <button type="submit" class="bg-[#0149a8] hover:bg-blue-800 text-white font-bold py-2 px-4 rounded transition">
                                        Asignar y Notificar
                                    </button>
                                </div>
                            </form>
                            
                            <p class="text-xs text-gray-500 mt-4">
                                * Al asignar, se enviará automáticamente un correo electrónico y una notificación al cliente.
                            </p>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('briefs.index') }}" class="text-gray-600 hover:text-gray-900 underline text-sm">
                            &larr; Volver a Briefs
                        </a>
                    </div>
                </div>

                <!-- Columna Derecha: Lista de Asignados -->
                <div class="w-full md:w-2/3">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-[#0149a8]">
                        <div class="p-6 text-gray-900">
                            <h3 class="font-bold text-lg mb-4 text-gray-700 flex items-center justify-between">
                                Clientes Asignados
                                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                    Total: {{ $brief->clientes->count() }}
                                </span>
                            </h3>

                            @if($brief->clientes->isEmpty())
                                <div class="text-center py-8 text-gray-400 bg-gray-50 rounded-lg border border-dashed border-gray-200">
                                    Este brief aún no ha sido asignado a ningún cliente.
                                </div>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Asignación</th>
                                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($brief->clientes as $cliente)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                        {{ $cliente->empresa ?: $cliente->nombre . ' ' . $cliente->apellido_paterno }}
                                                        <div class="text-xs text-gray-500 font-normal">{{ $cliente->correo }}</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                        @if($cliente->pivot->estado == 'recibido')
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                                Recibido
                                                            </span>
                                                        @else
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                                Pendiente
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ \Carbon\Carbon::parse($cliente->pivot->fecha_envio)->format('d/m/Y H:i') }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                        <form action="{{ route('briefs.unassign', [$brief->id, $cliente->id_cliente]) }}" method="POST" onsubmit="return confirm('¿Estás seguro de quitar la asignación? Esto no borra las respuestas en Google, pero dejará de mostrarse en el portal del cliente.');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900">Desasignar</button>
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
        </div>
    </div>
</x-app-layout>
