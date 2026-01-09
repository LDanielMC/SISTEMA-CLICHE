<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[#0149a8] leading-tight">
            {{ __('Panel de Trabajo | Team Cliché') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-[#0149a8]">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">
                        Hola, {{ Auth::user()->empleado ? Auth::user()->empleado->nombre : Auth::user()->name }} 👋
                    </h3>
                    <p class="mb-4 text-gray-600">Bienvenido a tu espacio de trabajo.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                        <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                            <div class="font-bold text-[#0149a8]">Puesto</div>
                            <div class="text-sm text-gray-500">
                                {{ Auth::user()->empleado ? Auth::user()->empleado->puesto : 'No definido' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>