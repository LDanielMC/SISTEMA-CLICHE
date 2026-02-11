<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    
    @forelse($cotizaciones as $cot)
        @php
            // Lógica de colores según estatus
            $estilos = match($cot->estatus) {
                'pendiente' => [
                    'borde' => 'border-yellow-400',
                    'badge' => 'bg-yellow-100 text-yellow-800 ring-1 ring-yellow-600/20'
                ],
                'aceptada' => [
                    'borde' => 'border-emerald-500',
                    'badge' => 'bg-emerald-100 text-emerald-800 ring-1 ring-emerald-600/20'
                ],
                'rechazada' => [
                    'borde' => 'border-red-500',
                    'badge' => 'bg-red-100 text-red-800 ring-1 ring-red-600/20'
                ],
            };

            $esVencida = $cot->estatus == 'pendiente' && $cot->fecha->addDays($cot->vencimiento_dias)->isPast();
        @endphp

        {{-- TARJETA --}}
        <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden border border-gray-100 group relative flex flex-col h-full">
            
            {{-- Borde izquierdo de color --}}
            <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $estilos['borde'] }}"></div>

            <div class="p-5 pl-7 flex-1"> 
                
                {{-- TOP: Folio y Estatus --}}
                <div class="flex justify-between items-start mb-2">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">
                        Folio #{{ str_pad($cot->id_cotizacion, 5, '0', STR_PAD_LEFT) }}
                    </span>
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold uppercase {{ $estilos['badge'] }}">
                        {{ $cot->estatus }}
                    </span>
                </div>

                {{-- TÍTULO DEL PROYECTO (Nuevo Protagonista) --}}
                <h3 class="text-lg font-bold text-gray-900 leading-tight mb-3 line-clamp-2 h-14" title="{{ $cot->titulo_cotizacion }}">
                    {{ $cot->titulo_cotizacion ?? 'Sin Título Asignado' }}
                </h3>

                {{-- INFO CLIENTE (Con Iconos) --}}
                <div class="space-y-1 mb-4">
                    <div class="flex items-center text-sm text-gray-600">
                        {{-- Icono Usuario --}}
                        <svg class="h-4 w-4 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="truncate font-medium">{{ $cot->cliente->nombre }} {{ $cot->cliente->apellido_paterno }}</span>
                    </div>
                    <div class="flex items-center text-xs text-gray-500">
                        {{-- Icono Edificio --}}
                        <svg class="h-4 w-4 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span class="truncate">{{ $cot->cliente->empresa }}</span>
                    </div>
                </div>
            </div>

            {{-- FOOTER: Totales y Fechas --}}
            <div class="bg-gray-50 px-5 py-3 border-t border-gray-100 pl-7 mt-auto">
                <div class="flex justify-between items-end mb-3">
                    <div>
                        <p class="text-xs text-gray-400 uppercase">Total</p>
                        <p class="text-xl font-extrabold text-gray-800">
                            ${{ number_format($cot->total, 2) }}
                        </p>
                    </div>
                    <div class="text-right">
                        @if($esVencida)
                            <p class="text-xs font-bold text-red-500">¡Vencida!</p>
                        @else
                            <p class="text-xs text-gray-400">Vencimiento</p>
                        @endif
                        <p class="text-xs font-medium {{ $esVencida ? 'text-red-600' : 'text-gray-600' }}">
                            {{ $cot->fecha->addDays($cot->vencimiento_dias)->format('d M, Y') }}
                        </p>
                    </div>
                </div>

                {{-- BOTONES DE ACCIÓN --}}
                <div class="flex justify-between items-center pt-2 border-t border-gray-200 border-dashed">
                    <div class="flex space-x-2">
                        {{-- PDF --}}
                        <a href="{{ route('cotizaciones.pdf', $cot->id_cotizacion) }}" target="_blank" 
                           class="text-gray-400 hover:text-red-600 transition-colors" title="Descargar PDF">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </a>
                        {{-- Enviar PDF por correo --}}
                        <form action="{{ route('cotizaciones.sendPdf', $cot->id_cotizacion) }}" method="POST" class="inline"
                              onsubmit="return confirm('¿Enviar cotización #{{ str_pad($cot->id_cotizacion, 5, '0', STR_PAD_LEFT) }} al correo de {{ $cot->cliente->nombre }}?\n\nSe enviará a: {{ $cot->cliente->user->email }}');">
                            @csrf
                            <button type="submit" class="text-gray-400 hover:text-emerald-600 transition-colors" title="Enviar PDF por correo a {{ $cot->cliente->user->email }}">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </button>
                        </form>
                        {{-- Editar --}}
                        <a href="{{ route('cotizaciones.edit', $cot->id_cotizacion) }}" 
                           class="text-gray-400 hover:text-indigo-600 transition-colors" title="Editar">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>
                    </div>

                    {{-- Eliminar --}}
                    <form action="{{ route('cotizaciones.destroy', $cot->id_cotizacion) }}" method="POST" onsubmit="return confirm('¿Eliminar cotización?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs text-gray-400 hover:text-red-600 font-medium transition-colors">
                            Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>

    @empty
        <div class="col-span-1 md:col-span-2 lg:col-span-3 flex flex-col items-center justify-center py-12 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300">
            <svg class="h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="text-lg font-medium text-gray-500">No hay cotizaciones</p>
            <p class="text-sm text-gray-400">Intenta cambiar los filtros o crea una nueva.</p>
        </div>
    @endforelse

</div>

<div class="mt-6">
    {{-- FIX: Solo mostrar paginación si $cotizaciones es un Paginador, no una Colección --}}
    @if(method_exists($cotizaciones, 'links'))
        {{ $cotizaciones->links() }}
    @endif
</div>