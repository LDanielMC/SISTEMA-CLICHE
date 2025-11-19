<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Registrar cliente
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('clientes.store') }}" method="POST">
                    @csrf

                    {{-- Nombre --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                               required>
                        @error('nombre')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Apellido paterno --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Apellido paterno</label>
                        <input type="text" name="apellido_paterno" value="{{ old('apellido_paterno') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                               required>
                        @error('apellido_paterno')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Apellido materno --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Apellido materno</label>
                        <input type="text" name="apellido_materno" value="{{ old('apellido_materno') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('apellido_materno')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Correo contacto (login e invitación) --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">
                            Correo electrónico (login e invitación)
                        </label>
                        {{-- CAMBIO 1: El 'name' ahora es 'correo' --}}
                        <input type="email" name="correo" value="{{ old('correo') }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                            required>
                        {{-- CAMBIO 2: El @error ahora escucha 'correo' --}}
                        @error('correo')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Teléfono --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                        <input type="text" name="telefono" value="{{ old('telefono') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                               required>
                        @error('telefono')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Empresa --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Empresa</label>
                        <input type="text" name="empresa" value="{{ old('empresa') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                               required>
                        @error('empresa')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Giro/Sector --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Giro / Sector</label>
                        <input type="text" name="giro_sector" value="{{ old('giro_sector') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                               required>
                        @error('giro_sector')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Fecha ingreso --}}
                    <div class="mb-4">
                        <label for="fecha_registro" class="block text-sm font-medium text-gray-700">Fecha de ingreso</label>
                        <input type="date" id="fecha_registro" name="fecha_registro" value="{{ old('fecha_registro', now()->format('Y-m-d')) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100"
                               readonly
                               required>
                        @error('fecha_registro')
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
                            Guardar y enviar invitación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
