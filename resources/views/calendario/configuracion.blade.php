<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Configuración del Calendario') }}
            </h2>
            <a href="{{ route('calendario.general') }}" class="text-gray-500 hover:text-gray-700 font-medium text-sm">
                ← Volver al Calendario
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                    <ul>@foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <!-- COLUMNA 1: PLATAFORMAS -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Plataformas / Redes</h2>
                        
                        <form action="{{ route('plataformas.store') }}" method="POST" class="mb-6 flex gap-2">
                            @csrf
                            <input type="text" name="nombre" placeholder="Nueva Plataforma" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" required>
                            <button type="submit" class="bg-blue-600 text-white px-3 py-2 rounded-md text-sm hover:bg-blue-700">Agregar</button>
                        </form>

                        <ul class="divide-y divide-gray-200">
                            @foreach($plataformas as $p)
                                <li class="py-3 flex justify-between items-center">
                                    <span class="text-gray-700 font-medium">{{ $p->nombre }}</span>
                                    <form action="{{ route('plataformas.destroy', $p->id) }}" method="POST" onsubmit="return confirm('¿Eliminar?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 text-xs uppercase font-bold">Eliminar</button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- COLUMNA 2: FORMATOS -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Formatos de Contenido</h2>
                        
                        <form action="{{ route('formatos.store') }}" method="POST" class="mb-6 space-y-2">
                            @csrf
                            <div class="flex gap-2">
                                <input type="text" name="nombre" placeholder="Nuevo Formato" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" required>
                                <button type="submit" class="bg-green-600 text-white px-3 py-2 rounded-md text-sm hover:bg-green-700">Agregar</button>
                            </div>
                            <input type="text" name="especificaciones" placeholder="Especificaciones (opcional)" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-xs text-gray-600">
                        </form>

                        <ul class="divide-y divide-gray-200">
                            @foreach($formatos as $f)
                                <li class="py-3 flex justify-between items-center">
                                    <div>
                                        <span class="text-gray-700 font-medium block">{{ $f->nombre }}</span>
                                        <span class="text-gray-400 text-xs">{{ $f->especificaciones }}</span>
                                    </div>
                                    <form action="{{ route('formatos.destroy', $f->id) }}" method="POST" onsubmit="return confirm('¿Eliminar?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 text-xs uppercase font-bold">Eliminar</button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>