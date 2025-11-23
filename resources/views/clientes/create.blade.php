<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Registrar cliente
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                
                {{-- INICIO DEL FORMULARIO --}}
                {{-- Usamos x-data de Alpine.js para controlar la visibilidad de la sección fiscal --}}
                {{-- Si hay errores en 'fiscal' o hay datos antiguos (old), se inicia como true (abierto) --}}
                <form action="{{ route('clientes.store') }}" method="POST" 
                      x-data="{ showFiscal: {{ $errors->has('fiscal.*') || old('fiscal.rfc') ? 'true' : 'false' }} }">
                    @csrf

                    {{-- ================= SECCIÓN 1: DATOS GENERALES ================= --}}
                    <div class="grid grid-cols-1 gap-y-4">
                        
                        {{-- Nombre --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input type="text" name="nombre" value="{{ old('nombre') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            @error('nombre') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Apellidos (Grid de 2 columnas para ahorrar espacio visual) --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Apellido paterno</label>
                                <input type="text" name="apellido_paterno" value="{{ old('apellido_paterno') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                @error('apellido_paterno') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Apellido materno</label>
                                <input type="text" name="apellido_materno" value="{{ old('apellido_materno') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @error('apellido_materno') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Correo y Teléfono --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Correo electrónico (login)</label>
                                <input type="email" name="correo" value="{{ old('correo') }}"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                @error('correo') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                                <input type="text" name="telefono" value="{{ old('telefono') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                @error('telefono') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Empresa y Giro --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Empresa</label>
                                <input type="text" name="empresa" value="{{ old('empresa') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                @error('empresa') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Giro / Sector</label>
                                <input type="text" name="giro_sector" value="{{ old('giro_sector') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                @error('giro_sector') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Fecha ingreso --}}
                        <div>
                            <label for="fecha_registro" class="block text-sm font-medium text-gray-700">Fecha de ingreso</label>
                            <input type="date" id="fecha_registro" name="fecha_registro" value="{{ old('fecha_registro', now()->format('Y-m-d')) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100" readonly required>
                            @error('fecha_registro') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- ================= SEPARADOR Y TOGGLE ================= --}}
                    <div class="my-8 border-t border-gray-200 pt-4">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" x-model="showFiscal" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                            <span class="ml-2 text-gray-800 font-semibold">¿Agregar información fiscal ahora?</span>
                        </label>
                    </div>

                    {{-- ================= SECCIÓN 2: DATOS FISCALES ================= --}}
                    {{-- x-show controla la visibilidad. x-transition hace que se vea suave --}}
                    <div x-show="showFiscal" x-transition class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Datos de Facturación</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- RFC --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">RFC</label>
                                <input type="text" name="fiscal[rfc]" value="{{ old('fiscal.rfc') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm uppercase">
                                @error('fiscal.rfc') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Razón Social --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Razón Social</label>
                                <input type="text" name="fiscal[razon_social]" value="{{ old('fiscal.razon_social') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm uppercase">
                                @error('fiscal.razon_social') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Régimen Fiscal --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Régimen Fiscal</label>
                                <input type="text" name="fiscal[regimen]" value="{{ old('fiscal.regimen') }}"
                                       placeholder="Ej: 601 - General de Ley Personas Morales"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @error('fiscal.regimen') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Correo Fiscal --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Correo para Facturación</label>
                                <input type="email" name="fiscal[correo_fiscal]" value="{{ old('fiscal.correo_fiscal') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @error('fiscal.correo_fiscal') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Teléfono Fiscal --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Teléfono Fiscal</label>
                                <input type="text" name="fiscal[telefono_fiscal]" value="{{ old('fiscal.telefono_fiscal') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @error('fiscal.telefono_fiscal') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Dirección Fiscal --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Dirección Fiscal Completa</label>
                                <input type="text" name="fiscal[direccion_fiscal]" value="{{ old('fiscal.direccion_fiscal') }}"
                                       placeholder="Calle, Número, Colonia, CP, Municipio, Estado"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @error('fiscal.direccion_fiscal') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- BOTONES DE ACCIÓN --}}
                    <div class="flex justify-end gap-3 mt-6">
                        <a href="{{ route('clientes.index') }}" 
                           class="px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Guardar cliente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>