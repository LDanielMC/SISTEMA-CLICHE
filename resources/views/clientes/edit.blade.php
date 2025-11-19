<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Cliente: {{ $cliente->nombre }} {{ $cliente->apellido_paterno }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                {{-- El formulario apunta a la ruta de actualización y usa el método PUT --}}
                <form action="{{ route('clientes.update', $cliente->id_cliente) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Nombre --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" name="nombre" value="{{ old('nombre', $cliente->nombre) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                               required>
                        @error('nombre')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Apellido paterno --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Apellido paterno</label>
                        <input type="text" name="apellido_paterno" value="{{ old('apellido_paterno', $cliente->apellido_paterno) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                               required>
                        @error('apellido_paterno')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Apellido materno --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Apellido materno</label>
                        <input type="text" name="apellido_materno" value="{{ old('apellido_materno', $cliente->apellido_materno) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('apellido_materno')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Correo electrónico (solo lectura) --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">
                            Correo electrónico (login)
                        </label>
                        <input type="email" name="correo_contacto" value="{{ old('correo_contacto', $cliente->user->email) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100"
                               readonly>
                        @error('correo_contacto')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Teléfono --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                        <input type="text" name="telefono" value="{{ old('telefono', $cliente->telefono) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                               required>
                        @error('telefono')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Empresa --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Empresa</label>
                        <input type="text" name="empresa" value="{{ old('empresa', $cliente->empresa) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                               required>
                        @error('empresa')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Giro/Sector --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Giro / Sector</label>
                        <input type="text" name="giro_sector" value="{{ old('giro_sector', $cliente->giro_sector) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                               required>
                        @error('giro_sector')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('clientes.index') }}" 
                           class="px-4 py-2 border rounded-md text-sm text-gray-700">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
