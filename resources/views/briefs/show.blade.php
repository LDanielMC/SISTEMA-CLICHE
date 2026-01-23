<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📊 {{ __('Detalles del Brief') }}: {{ $brief->titulo }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="{{ route('briefs.index') }}" class="text-blue-500 hover:text-blue-700">
                    ← Volver al listado
                </a>
            </div>

            @if($error)
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4" role="alert">
                    <p class="font-bold">Aviso de Sincronización</p>
                    <p>{{ $error }}</p>
                    <p class="text-sm mt-2">Mostrando información local guardada.</p>
                </div>
            @endif

            <!-- Información General -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-2xl font-bold mb-2">{{ $brief->titulo }}</h3>
                            <p class="text-gray-600 mb-4">{{ $brief->descripcion }}</p>
                            @if($brief->form_url)
                                <a href="{{ $brief->form_url }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Ir al Formulario en Google ↗
                                </a>
                            @endif
                        </div>
                        <div class="text-right text-sm text-gray-500">
                            <p>ID: {{ $brief->google_form_id }}</p>
                            <p>Registrado: {{ $brief->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Respuestas -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4 border-b pb-2">Respuestas Recibidas ({{ is_countable($responses) ? count($responses) : 0 }})</h3>

                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    Para ver las respuestas identificadas por cliente, por favor diríjase a la sección de 
                                    <a href="{{ route('briefs.assign', $brief->id) }}" class="font-bold underline hover:text-blue-900">Asignar Brief</a> 
                                    y haga clic en "Ver Respuesta" junto al cliente correspondiente.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <p class="text-xs text-gray-500 italic">
                        Nota: Aquí solo se muestra el conteo total. La visualización detallada se ha movido para evitar confusiones sobre la autoría de las respuestas.
                    </p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
