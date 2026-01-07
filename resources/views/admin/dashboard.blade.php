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


               <a href="{{ route('cotizaciones.index') }}" class="group block"> 
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-300 border-l-4 border-emerald-500">
                        <div class="p-6 text-gray-900 flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 group-hover:text-emerald-600 transition-colors">
                                    Cotizaciones
                                </h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    Crear, enviar y gestionar presupuestos.
                                </p>
                            </div>
                            
                            <div class="bg-emerald-100 p-3 rounded-full group-hover:bg-emerald-200 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                        </div>
                        
                        {{-- Footer de la tarjeta con resumen rápido (Opcional) --}}
                        <div class="bg-gray-50 px-6 py-2 border-t border-gray-100 text-xs text-gray-500 flex justify-between">
                            <span>Ver historial</span>
                            <span class="text-emerald-600 font-semibold group-hover:translate-x-1 transition-transform">Ir ahora &rarr;</span>
                        </div>
                    </div>
                </a>


            </div>
        </div>
    </div>
</x-app-layout>
