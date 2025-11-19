<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Empleado: {{ $empleado->nombre }} {{ $empleado->apellido_paterno }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                {{-- El formulario apunta a la ruta de actualización y usa el método PUT --}}
                <form action="{{ route('empleados.update', $empleado->id_empleado) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Nombre --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" name="nombre" value="{{ old('nombre', $empleado->nombre) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                               required>
                        @error('nombre')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Apellido paterno --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Apellido paterno</label>
                        <input type="text" name="apellido_paterno" value="{{ old('apellido_paterno', $empleado->apellido_paterno) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                               required>
                        @error('apellido_paterno')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Apellido materno --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Apellido materno</label>
                        <input type="text" name="apellido_materno" value="{{ old('apellido_materno', $empleado->apellido_materno) }}"
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
                        <input type="email" name="correo_contacto" value="{{ old('correo_contacto', $empleado->user->email) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100"
                               readonly>
                        @error('correo_contacto')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Teléfono --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                        <input type="text" name="telefono" value="{{ old('telefono', $empleado->telefono) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                               required>
                        @error('telefono')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Puesto --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Puesto</label>
                        <input type="text" name="puesto" value="{{ old('puesto', $empleado->puesto) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                               required>
                        @error('puesto')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>


                    <div class="flex justify-end gap-3">
                        <a href="{{ route('empleados.index') }}" 
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
