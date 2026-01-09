<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[#0149a8] leading-tight">
            {{ __('Mi Portal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-[#0149a8]">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-2">
                        Bienvenido, 
                        @if(Auth::user()->cliente && Auth::user()->cliente->empresa)
                            {{ Auth::user()->cliente->empresa }}
                        @else
                            {{ Auth::user()->name }}
                        @endif
                    </h3>
                    <p class="text-gray-600 mb-6">Este es tu espacio exclusivo en Cliché Marketing Digital.</p>
                    
                    <div class="p-5 bg-[#f4f8fb] rounded-lg border border-[#e7eef6]">
                        <h4 class="font-bold text-[#0149a8] mb-2">Mis Proyectos</h4>
                        <p class="text-sm text-gray-600">Próximamente podrás ver aquí el estado de tus cotizaciones.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>