{{-- resources/views/admin/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Panel de administración
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Tarjeta Gestión de Usuarios (Empleados) --}}
                <a href="{{ route('empleados.index') }}" 
                   class="block bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 hover:shadow-md transition">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-2">
                            Gestión de usuarios (empleados)
                        </h3>
                        <p class="text-gray-600 text-sm">
                            Ver, registrar, actualizar y eliminar la información de los empleados 
                            que colaboran en la empresa.
                        </p>
                    </div>
                </a>

                {{-- Tarjeta Gestión de Clientes --}}
                <a href="{{ route('clientes.index') }}" 
                   class="block bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 hover:shadow-md transition">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-2">
                            Gestión de clientes
                        </h3>
                        <p class="text-gray-600 text-sm">
                            Ver, registrar, actualizar y eliminar la información de los clientes de Cliché.
                        </p>
                    </div>
                </a>


                <a href="{{ route('clientes.index') }}" 
                   class="block bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 hover:shadow-md transition">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-2">
                            Gestión de clientes
                        </h3>
                        <p class="text-gray-600 text-sm">
                            Ver, registrar, actualizar y eliminar la información de los clientes de Cliché.
                        </p>
                    </div>
                </a>


            </div>
        </div>
    </div>
</x-app-layout>
