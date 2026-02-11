<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    
    @forelse($minutas as $minuta)
        @php
            // Lógica de colores según estatus de acuerdos
            $acuerdosPendientes = $minuta->acuerdos->where('estatus', 'pendiente')->count();
            $acuerdosCompletados = $minuta->acuerdos->where('estatus', 'completado')->count();
            $totalAcuerdos = $minuta->acuerdos->count();
            
            // Verificar si todos los acuerdos están completados
            $todosCompletados = $totalAcuerdos > 0 && $acuerdosCompletados === $totalAcuerdos;

            // Determinar color de borde y estilo de tarjeta
            if ($todosCompletados) {
                $borde = 'border-emerald-500';
                $badge = 'bg-emerald-100 text-emerald-800 ring-1 ring-emerald-600/20';
                $fondoTarjeta = 'background: linear-gradient(135deg, #dbeafe 0%, #cffafe 40%, #ccfbf1 70%, #fce7f3 100%);';
            } elseif ($acuerdosPendientes > 0) {
                $borde = 'border-yellow-400';
                $badge = 'bg-yellow-100 text-yellow-800 ring-1 ring-yellow-600/20';
                $fondoTarjeta = '';
            } else {
                $borde = 'border-gray-300';
                $badge = 'bg-gray-100 text-gray-800 ring-1 ring-gray-600/20';
                $fondoTarjeta = '';
            }
        @endphp

        {{-- TARJETA --}}
        <div class="rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden border border-gray-100 group relative flex flex-col h-full"
             style="{{ $fondoTarjeta ?: 'background: white;' }}">
            
            {{-- Borde izquierdo de color --}}
            <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $borde }}"></div>

            <div class="p-5 pl-7 flex-1"> 
                
                {{-- TOP: ID y Estatus de Acuerdos --}}
                <div class="flex justify-between items-start mb-2">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">
                        Minuta #{{ str_pad($minuta->id_minuta, 5, '0', STR_PAD_LEFT) }}
                    </span>
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold uppercase {{ $badge }}">
                        @if ($todosCompletados)
                            Completada
                        @elseif ($acuerdosPendientes > 0)
                            {{ $acuerdosPendientes }} {{ Str::plural('pendiente', $acuerdosPendientes) }}
                        @else
                            Sin acuerdos
                        @endif
                    </span>
                </div>

                {{-- TÍTULO DE LA MINUTA --}}
                @if($minuta->titulo)
                    <h3 class="text-base font-bold text-gray-900 leading-tight mb-1">
                        {{ $minuta->titulo }}
                    </h3>
                @endif

                {{-- FECHA --}}
                <p class="text-sm {{ $minuta->titulo ? 'text-gray-500' : 'text-lg font-bold text-gray-900' }} leading-tight mb-3">
                    {{ $minuta->fecha->format('d/m/Y') }}
                </p>

                {{-- INFO CLIENTE --}}
                <div class="space-y-1 mb-4">
                    <div class="flex items-center text-sm text-gray-600">
                        {{-- Icono Usuario --}}
                        <svg class="h-4 w-4 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="truncate font-medium">{{ $minuta->cliente->nombre }} {{ $minuta->cliente->apellido_paterno }}</span>
                    </div>
                    <div class="flex items-center text-xs text-gray-500">
                        {{-- Icono Empresa --}}
                        <svg class="h-4 w-4 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span class="truncate">{{ $minuta->cliente->empresa }}</span>
                    </div>
                </div>

                {{-- VISTA PREVIA DE PUNTOS TRATADOS --}}
                @if($minuta->puntos_tratados)
                    <div class="bg-gray-50 p-2 rounded text-xs text-gray-600 line-clamp-2">
                        {{ Str::limit(strip_tags($minuta->puntos_tratados), 100, '...') }}
                    </div>
                @endif
            </div>

            {{-- FOOTER: Resumen y Acciones --}}
            <div class="bg-gray-50 px-5 py-3 border-t border-gray-100 pl-7 mt-auto">
                <div class="flex justify-between items-end mb-3">
                    <div>
                        <p class="text-xs text-gray-400 uppercase">Acuerdos</p>
                        <p class="text-lg font-extrabold text-gray-800">
                            {{ $minuta->acuerdos->count() }}
                        </p>
                    </div>
                    <div class="text-right text-xs">
                        <p class="text-green-600">✓ {{ $acuerdosCompletados }}</p>
                        <p class="text-yellow-600">● {{ $acuerdosPendientes }}</p>
                    </div>
                </div>

                {{-- BOTONES DE ACCIÓN --}}
                <div class="flex justify-between items-center pt-2 border-t border-gray-200 border-dashed">
                    <div class="flex space-x-2">
                        {{-- Editar --}}
                        <a href="{{ route('minutas.edit', $minuta->id_minuta) }}" 
                           class="text-gray-400 hover:text-indigo-600 transition-colors" title="Editar minuta">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>
                    </div>

                    {{-- Eliminar --}}
                    <form action="{{ route('minutas.destroy', $minuta->id_minuta) }}" method="POST" onsubmit="return confirm('¿Eliminar minuta?');">
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
            <p class="text-lg font-medium text-gray-500">No hay minutas</p>
            <p class="text-sm text-gray-400">Intenta cambiar los filtros o crea una nueva.</p>
        </div>
    @endforelse

</div>

<div class="mt-6">
    {{-- Solo mostrar paginación si es un Paginador --}}
    @if(method_exists($minutas, 'links'))
        {{ $minutas->links() }}
    @endif
</div>
