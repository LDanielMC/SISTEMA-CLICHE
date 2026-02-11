<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[#0149a8] leading-tight">
            {{ __('Mis Briefs y Encuestas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- SECCIÓN DE NOTIFICACIONES / PENDIENTES -->
            @if(isset($briefsPendientes) && $briefsPendientes->count() > 0)
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 shadow sm:rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <!-- Icono Alerta -->
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3 w-full">
                            <h3 class="text-sm leading-5 font-medium text-yellow-800">
                                Tienes {{ $briefsPendientes->count() }} formulario(s) pendiente(s) de respuesta
                            </h3>
                            <div class="mt-4 grid gap-4">
                                @foreach($briefsPendientes as $brief)
                                    <div class="bg-white p-4 rounded-lg border border-yellow-200 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center">
                                        <div>
                                            <h4 class="font-bold text-gray-800">{{ $brief->titulo }}</h4>
                                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($brief->descripcion, 100) }}</p>
                                            <p class="text-xs text-gray-500 mt-2">
                                                Enviado el: {{ $brief->pivot->fecha_envio ? \Carbon\Carbon::parse($brief->pivot->fecha_envio)->format('d/m/Y') : 'Fecha no disponible' }}
                                            </p>
                                        </div>
                                        <div class="mt-4 md:mt-0">
                                            <a href="{{ $brief->form_url }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 active:bg-yellow-700 focus:outline-none focus:border-yellow-700 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150">
                                                📝 Contestar Ahora
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-[#0149a8]">
                <div class="p-6 text-gray-900">
                    <div class="mb-8">
                        <h4 class="font-bold text-[#0149a8] text-lg mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            Mis Encuestas
                        </h4>
                        
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            @if($briefsPendientes->isEmpty() && $briefsCompletados->isEmpty())
                                <div class="p-6 text-center text-gray-500">
                                    No tienes encuestas asignadas en este momento.
                                </div>
                            @else
                                <div class="grid md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-gray-100">
                                    <!-- Pendientes -->
                                    <div class="p-6">
                                        <h5 class="text-sm font-bold text-gray-700 mb-4 flex items-center">
                                            <span class="w-2 h-2 bg-yellow-400 rounded-full mr-2"></span>
                                            Pendientes ({{ $briefsPendientes->count() }})
                                        </h5>
                                        @if($briefsPendientes->count() > 0)
                                            <div class="space-y-3">
                                                @foreach($briefsPendientes as $brief)
                                                    <div class="group flex flex-col sm:flex-row justify-between items-start sm:items-center p-3 bg-yellow-50/50 rounded-lg border border-yellow-100 hover:border-yellow-300 transition-colors">
                                                        <div class="mb-2 sm:mb-0">
                                                            <span class="block font-bold text-gray-800 text-sm">{{ $brief->titulo }}</span>
                                                            <span class="text-xs text-gray-500">Asignada: {{ $brief->fecha_envio ? $brief->fecha_envio->format('d/m/Y') : 'N/A' }}</span>
                                                        </div>
                                                        <a href="{{ $brief->form_url }}" target="_blank" class="text-xs bg-yellow-400 hover:bg-yellow-500 text-white font-bold py-1.5 px-3 rounded shadow-sm transition">
                                                            Responder
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-sm text-gray-400 italic">Todo al día.</p>
                                        @endif
                                    </div>

                                    <!-- Completadas -->
                                    <div class="p-6 bg-gray-50/50">
                                        <h5 class="text-sm font-bold text-gray-700 mb-4 flex items-center">
                                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                            Historial de Encuestas
                                        </h5>
                                        @if($briefsCompletados->count() > 0)
                                            <div class="space-y-3">
                                                @foreach($briefsCompletados as $brief)
                                                    <div class="flex justify-between items-center p-3 bg-white rounded-lg border border-gray-100 opacity-80 hover:opacity-100 transition">
                                                        <div>
                                                            <span class="block font-medium text-gray-600 text-sm line-through">{{ $brief->titulo }}</span>
                                                            <span class="text-xs text-green-600 font-semibold flex items-center">
                                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                                Completado
                                                            </span>
                                                        </div>
                                                        <a href="{{ $brief->form_url }}" target="_blank" class="text-gray-400 hover:text-blue-600 transition" title="Ver formulario original">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-sm text-gray-400 italic">No hay historial reciente.</p>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
