<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestor de Calendarios') }}
            </h2>
            <a href="{{ route('calendario.general') }}" class="text-gray-500 hover:text-gray-700 font-medium text-sm">
                ← Volver al Calendario General
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- SELECTOR -->
            <div class="bg-white shadow-sm sm:rounded-lg mb-6 border-l-4 border-blue-600 p-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Cliente Seleccionado:</label>

                <form method="GET" action="{{ route('calendario.gestion') }}">
                    <select
                        id="cliente_selector"
                        name="cliente_id"
                        onchange="this.form.submit()"
                        class="block w-full md:w-1/2 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 cursor-pointer"
                    >
                        <option value="">-- Selecciona un cliente --</option>

                        @foreach($clientesActivos as $cliente)
                            <option
                                value="{{ $cliente->id_cliente }}"
                                {{ (string)request('cliente_id') === (string)$cliente->id_cliente
                                    ? 'selected'
                                    : ((isset($clienteSeleccionado) && (string)$clienteSeleccionado->id_cliente === (string)$cliente->id_cliente) ? 'selected' : '')
                                }}
                            >
                                <!-- Lógica: Empresa primero, sino Nombre + Apellido Paterno -->
                                {{ $cliente->empresa ?: $cliente->nombre . ' ' . $cliente->apellido_paterno }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="hidden">Ir</button>
                </form>
            </div>

            @if($clienteSeleccionado)

                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <div id="app-container">

                    <!-- DASHBOARD -->
                    <div id="view-dashboard" class="block">

                        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-6 flex flex-col sm:flex-row justify-between items-center gap-4">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">
                                    Calendarios de {{ $clienteSeleccionado->empresa ?: $clienteSeleccionado->nombre . ' ' . $clienteSeleccionado->apellido_paterno }}
                                </h3>
                                <p class="text-sm text-gray-500">Cada tarjeta es un calendario (rango de fechas).</p>
                            </div>

                            <button type="button" onclick="toggleView('editor')"
                                class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded shadow flex items-center justify-center gap-2 transition-transform transform hover:scale-105">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Crear Nuevo Calendario
                            </button>
                        </div>

                        @if($calendariosPorAnio->count() > 0)

                            @foreach($calendariosPorAnio as $anio => $tarjetas)

                                @if((int)$anio !== 1900)
                                    <div class="mb-8">
                                        <div class="flex items-center justify-between mb-3">
                                            <h4 class="text-lg font-extrabold text-gray-800">Año {{ $anio }}</h4>
                                            <span class="text-sm text-gray-500">{{ $tarjetas->count() }} calendarios</span>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                            @foreach($tarjetas as $t)
                                                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200 hover:shadow-md transition-shadow">
                                                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                                                        <div>
                                                            <h5 class="font-bold text-gray-800 capitalize">
                                                                {{ $t['titulo'] }}
                                                            </h5>
                                                            <p class="text-xs text-gray-500 mt-1">
                                                                Última actualización: {{ $t['updated_at']->format('d/m/Y') }}
                                                            </p>
                                                        </div>
                                                        
                                                        <div class="flex items-center gap-3">
                                                            <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full font-semibold">
                                                                {{ $t['total'] }} posts
                                                            </span>

                                                            <!-- ✅ BOTÓN ELIMINAR CALENDARIO -->
                                                            <form action="{{ route('calendario.destroyLote') }}" method="POST" 
                                                                onsubmit="return confirm('⚠️ ¿Estás seguro de eliminar TODO este calendario?\n\nSe borrarán permanentemente {{ $t['total'] }} publicaciones.\n\nEsta acción NO se puede deshacer.');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <input type="hidden" name="lote_calendario" value="{{ $t['lote'] }}">
                                                                <input type="hidden" name="cliente_id" value="{{ $clienteSeleccionado->id_cliente }}">
                                                                <button type="submit" class="text-gray-400 hover:text-red-600 p-1 hover:bg-red-50 rounded transition-colors" title="Eliminar Calendario">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                    </svg>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>

                                                    <div class="p-6">
                                                        @php
                                                            $cPend = $t['publicaciones']->where('estatus','Pendiente')->count();
                                                            $cPub  = $t['publicaciones']->where('estatus','Publicado')->count();
                                                            $cRep  = $t['publicaciones']->where('estatus','Reprogramar')->count();
                                                        @endphp

                                                        <div class="flex gap-2 text-xs mb-4">
                                                            <span class="px-2 py-1 rounded-full bg-yellow-100 text-yellow-800 font-semibold">Pend: {{ $cPend }}</span>
                                                            <span class="px-2 py-1 rounded-full bg-green-100 text-green-800 font-semibold">Pub: {{ $cPub }}</span>
                                                            <span class="px-2 py-1 rounded-full bg-red-100 text-red-800 font-semibold">Rep: {{ $cRep }}</span>
                                                        </div>

                                                        <div class="space-y-2 mb-4">
                                                            @foreach($t['publicaciones']->take(3) as $pub)
                                                                <div class="flex items-center text-sm text-gray-600">
                                                                    <span class="w-2 h-2 rounded-full mr-2
                                                                        {{ $pub->estatus == 'Publicado' ? 'bg-green-500' : ($pub->estatus == 'Reprogramar' ? 'bg-red-500' : 'bg-yellow-400') }}"></span>
                                                                    <span class="font-medium mr-1">{{ $pub->fecha->format('d/m') }}:</span>
                                                                    <!-- CAMBIO: Muestra el Formato en lugar de la Plataforma -->
                                                                    <span class="truncate">{{ $pub->formato->nombre }}</span>
                                                                </div>
                                                            @endforeach
                                                            @if($t['total'] > 3)
                                                                <div class="text-xs text-gray-400 pl-4">+ {{ $t['total'] - 3 }} más...</div>
                                                            @endif
                                                        </div>

                                                        <button
                                                            type="button"
                                                            onclick="openModal('{{ $t['lote'] }}', @js($t['titulo']))"
                                                            class="w-full text-center bg-white border border-gray-200 hover:bg-gray-50 text-gray-800 font-semibold py-2 rounded-lg"
                                                        >
                                                            Ver / Editar publicaciones
                                                        </button>
                                                    </div>
                                                </div>

                                                <!-- ✅ CONTENIDO OCULTO PARA EL MODAL (Formulario Masivo) -->
                                                <div id="modal-content-{{ $t['lote'] }}" class="hidden">
                                                    
                                                    <!-- Formulario Masivo Invisible -->
                                                    <form id="form-masivo-{{ $t['lote'] }}" action="{{ route('publicaciones.updateMasivo') }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <!-- CAMPOS NECESARIOS PARA CREAR NUEVAS FILAS -->
                                                        <input type="hidden" name="cliente_id" value="{{ $clienteSeleccionado->id_cliente }}">
                                                        <input type="hidden" name="lote_calendario" value="{{ $t['lote'] }}">
                                                    </form>

                                                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                                                        <div class="text-sm text-gray-600">
                                                            <span class="font-semibold">Rango:</span>
                                                            {{ $t['inicio']->isoFormat('D [de] MMMM YYYY') }} → {{ $t['fin']->isoFormat('D [de] MMMM YYYY') }}
                                                        </div>
                                                        
                                                        <button type="submit" form="form-masivo-{{ $t['lote'] }}"
                                                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow-md transition-transform transform hover:scale-105 flex items-center gap-2">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                            </svg>
                                                            Guardar todos los cambios
                                                        </button>
                                                    </div>

                                                    <div class="overflow-x-auto border rounded-lg mb-4">
                                                        <table class="min-w-full text-sm">
                                                            <thead class="bg-gray-50">
                                                                <tr class="text-left text-xs text-gray-500 uppercase">
                                                                    <th class="py-3 px-3 w-32">Fecha</th>
                                                                    <th class="py-3 px-3 w-32">Plataforma</th>
                                                                    <th class="py-3 px-3 w-32">Formato</th>
                                                                    <th class="py-3 px-3 min-w-[200px]">Copy</th>
                                                                    <th class="py-3 px-3 min-w-[200px]">Arte</th>
                                                                    <th class="py-3 px-3 w-32">Estatus</th>
                                                                    <th class="py-3 px-3 text-right">Acciones</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="divide-y bg-white">
                                                                @foreach($t['publicaciones'] as $pub)
                                                                    @php
                                                                        $search = strtolower(
                                                                            $pub->fecha->format('d/m/Y').' '.
                                                                            $pub->plataforma->nombre.' '.
                                                                            $pub->formato->nombre.' '.
                                                                            ($pub->copy ?? '').' '.
                                                                            ($pub->arte ?? '').' '.
                                                                            $pub->estatus
                                                                        );
                                                                        $formId = 'form-masivo-' . $t['lote'];
                                                                        $prefix = "publicaciones[{$pub->idPublicacion}]";
                                                                    @endphp

                                                                    <tr class="modal-row align-top hover:bg-gray-50 transition-colors"
                                                                        data-estatus="{{ $pub->estatus }}"
                                                                        data-plataforma-id="{{ $pub->plataforma_id }}"
                                                                        data-search="{{ $search }}"
                                                                    >
                                                                        <td class="p-2">
                                                                            <!-- ✅ FECHA: Validada con min y max -->
                                                                            <input type="date" name="{{ $prefix }}[fecha]" form="{{ $formId }}"
                                                                                value="{{ $pub->fecha->format('Y-m-d') }}"
                                                                                min="{{ $t['inicio']->format('Y-m-d') }}"
                                                                                max="{{ $t['fin']->format('Y-m-d') }}"
                                                                                class="w-full text-xs border-gray-300 rounded focus:ring-blue-500">
                                                                        </td>
                                                                        <td class="p-2">
                                                                            <select name="{{ $prefix }}[plataforma_id]" form="{{ $formId }}"
                                                                                class="w-full text-xs border-gray-300 rounded focus:ring-blue-500">
                                                                                @foreach($plataformas as $p)
                                                                                    <option value="{{ $p->id }}" {{ $pub->plataforma_id == $p->id ? 'selected' : '' }}>
                                                                                        {{ $p->nombre }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </td>
                                                                        <td class="p-2">
                                                                            <select name="{{ $prefix }}[formato_id]" form="{{ $formId }}"
                                                                                class="w-full text-xs border-gray-300 rounded focus:ring-blue-500">
                                                                                @foreach($formatos as $f)
                                                                                    <option value="{{ $f->id }}" {{ $pub->formato_id == $f->id ? 'selected' : '' }}>
                                                                                        {{ $f->nombre }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </td>
                                                                        <td class="p-2">
                                                                            <textarea name="{{ $prefix }}[copy]" form="{{ $formId }}" rows="4"
                                                                                class="w-full text-xs border-gray-300 rounded focus:ring-blue-500 resize-y"
                                                                                placeholder="Escribe el copy...">{{ $pub->copy }}</textarea>
                                                                        </td>
                                                                        <td class="p-2">
                                                                            <textarea name="{{ $prefix }}[arte]" form="{{ $formId }}" rows="4"
                                                                                class="w-full text-xs border-gray-300 rounded focus:ring-blue-500 resize-y"
                                                                                placeholder="Indicaciones de arte...">{{ $pub->arte }}</textarea>
                                                                        </td>
                                                                        <td class="p-2">
                                                                            <select name="{{ $prefix }}[estatus]" form="{{ $formId }}"
                                                                                class="w-full text-xs border-gray-300 rounded focus:ring-blue-500
                                                                                {{ $pub->estatus == 'Publicado' ? 'bg-green-50 text-green-700' : '' }}
                                                                                {{ $pub->estatus == 'Reprogramar' ? 'bg-red-50 text-red-700' : '' }}">
                                                                                <option value="Pendiente" {{ $pub->estatus=='Pendiente'?'selected':'' }}>Pendiente</option>
                                                                                <option value="Publicado" {{ $pub->estatus=='Publicado'?'selected':'' }}>Publicado</option>
                                                                                <option value="Reprogramar" {{ $pub->estatus=='Reprogramar'?'selected':'' }}>Reprogramar</option>
                                                                            </select>
                                                                        </td>
                                                                        <td class="p-2 text-right align-middle">
                                                                            <div class="flex items-center justify-end gap-1">
                                                                                <!-- Botón Agregar Fila Aquí -->
                                                                                <button type="button" onclick="agregarFilaModal('{{ $t['lote'] }}', '{{ $t['inicio']->format('Y-m-d') }}', '{{ $t['fin']->format('Y-m-d') }}', this)"
                                                                                    class="text-blue-600 hover:text-blue-800 font-bold p-1 bg-blue-50 hover:bg-blue-100 rounded border border-blue-200" title="Insertar fila abajo">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                                                    </svg>
                                                                                </button>

                                                                                <!-- Botón Borrar -->
                                                                                <form action="{{ route('publicaciones.destroy', $pub->idPublicacion) }}" method="POST"
                                                                                    onsubmit="return confirm('¿Borrar publicación permanentemente?');" class="inline">
                                                                                    @csrf
                                                                                    @method('DELETE')
                                                                                    <button class="text-red-400 hover:text-red-600 font-bold p-1 hover:bg-red-50 rounded" title="Eliminar">
                                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                                        </svg>
                                                                                    </button>
                                                                                </form>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <!-- ✅ BOTÓN AGREGAR: Pasamos las fechas min y max -->
                                                    <button type="button" onclick="agregarFilaModal('{{ $t['lote'] }}', '{{ $t['inicio']->format('Y-m-d') }}', '{{ $t['fin']->format('Y-m-d') }}')"
                                                        class="text-blue-600 hover:text-blue-800 font-bold text-sm flex items-center gap-1 px-4 py-2 hover:bg-blue-50 rounded w-full justify-center border border-dashed border-blue-300">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                                        </svg>
                                                        Agregar Fila a este Calendario
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                            @endforeach

                            {{-- Si existe SIN_LOTE --}}
                            @if($calendariosPorAnio->has(1900))
                                <div class="mt-10">
                                    <h4 class="text-lg font-extrabold text-gray-800 mb-3">Otros</h4>
                                    <div class="bg-white p-6 rounded-xl border shadow-sm">
                                        <p class="text-gray-600 text-sm mb-3">
                                            Estas publicaciones no fueron guardadas como un “calendario” (rango). Se muestran aquí.
                                        </p>
                                        <button
                                            type="button"
                                            onclick="openModal('SIN_LOTE', 'Publicaciones (sin calendario guardado)')"
                                            class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded"
                                        >
                                            Ver / Editar publicaciones
                                        </button>

                                        <div id="modal-content-SIN_LOTE" class="hidden">
                                            
                                            <form id="form-masivo-SIN_LOTE" action="{{ route('publicaciones.updateMasivo') }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="cliente_id" value="{{ $clienteSeleccionado->id_cliente }}">
                                                <input type="hidden" name="lote_calendario" value="SIN_LOTE">
                                            </form>

                                            <div class="flex justify-between items-center mb-4">
                                                <div class="text-sm text-gray-600">
                                                    Estas publicaciones no pertenecen a un calendario guardado.
                                                </div>
                                                <button type="submit" form="form-masivo-SIN_LOTE"
                                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow-md transition-transform transform hover:scale-105">
                                                    Guardar todo
                                                </button>
                                            </div>

                                            <div class="overflow-x-auto border rounded-lg mb-4">
                                                <table class="min-w-full text-sm">
                                                    <thead class="bg-gray-50">
                                                        <tr class="text-left text-xs text-gray-500 uppercase">
                                                            <th class="py-3 px-3 w-32">Fecha</th>
                                                            <th class="py-3 px-3 w-32">Plataforma</th>
                                                            <th class="py-3 px-3 w-32">Formato</th>
                                                            <th class="py-3 px-3 min-w-[200px]">Copy</th>
                                                            <th class="py-3 px-3 min-w-[200px]">Arte</th>
                                                            <th class="py-3 px-3 w-32">Estatus</th>
                                                            <th class="py-3 px-3 text-right">Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y bg-white">
                                                        @foreach($calendariosPorAnio[1900]->first()['publicaciones'] as $pub)
                                                            @php
                                                                $search = strtolower(
                                                                    $pub->fecha->format('d/m/Y').' '.
                                                                    $pub->plataforma->nombre.' '.
                                                                    $pub->formato->nombre.' '.
                                                                    ($pub->copy ?? '').' '.
                                                                    ($pub->arte ?? '').' '.
                                                                    $pub->estatus
                                                                );
                                                                $formId = 'form-masivo-SIN_LOTE';
                                                                $prefix = "publicaciones[{$pub->idPublicacion}]";
                                                            @endphp

                                                            <tr class="modal-row align-top hover:bg-gray-50 transition-colors"
                                                                data-estatus="{{ $pub->estatus }}"
                                                                data-plataforma-id="{{ $pub->plataforma_id }}"
                                                                data-search="{{ $search }}"
                                                            >
                                                                <td class="p-2">
                                                                    <input type="date" name="{{ $prefix }}[fecha]" form="{{ $formId }}"
                                                                        value="{{ $pub->fecha->format('Y-m-d') }}"
                                                                        class="w-full text-xs border-gray-300 rounded focus:ring-blue-500">
                                                                </td>
                                                                <td class="p-2">
                                                                    <select name="{{ $prefix }}[plataforma_id]" form="{{ $formId }}"
                                                                        class="w-full text-xs border-gray-300 rounded focus:ring-blue-500">
                                                                        @foreach($plataformas as $p)
                                                                            <option value="{{ $p->id }}" {{ $pub->plataforma_id == $p->id ? 'selected' : '' }}>
                                                                                {{ $p->nombre }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td class="p-2">
                                                                    <select name="{{ $prefix }}[formato_id]" form="{{ $formId }}"
                                                                        class="w-full text-xs border-gray-300 rounded focus:ring-blue-500">
                                                                        @foreach($formatos as $f)
                                                                            <option value="{{ $f->id }}" {{ $pub->formato_id == $f->id ? 'selected' : '' }}>
                                                                                {{ $f->nombre }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td class="p-2">
                                                                    <textarea name="{{ $prefix }}[copy]" form="{{ $formId }}" rows="4"
                                                                        class="w-full text-xs border-gray-300 rounded focus:ring-blue-500 resize-y"
                                                                        placeholder="Copy...">{{ $pub->copy }}</textarea>
                                                                </td>
                                                                <td class="p-2">
                                                                    <textarea name="{{ $prefix }}[arte]" form="{{ $formId }}" rows="4"
                                                                        class="w-full text-xs border-gray-300 rounded focus:ring-blue-500 resize-y"
                                                                        placeholder="Arte...">{{ $pub->arte }}</textarea>
                                                                </td>
                                                                <td class="p-2">
                                                                    <select name="{{ $prefix }}[estatus]" form="{{ $formId }}"
                                                                        class="w-full text-xs border-gray-300 rounded focus:ring-blue-500">
                                                                        <option value="Pendiente" {{ $pub->estatus=='Pendiente'?'selected':'' }}>Pendiente</option>
                                                                        <option value="Publicado" {{ $pub->estatus=='Publicado'?'selected':'' }}>Publicado</option>
                                                                        <option value="Reprogramar" {{ $pub->estatus=='Reprogramar'?'selected':'' }}>Reprogramar</option>
                                                                    </select>
                                                                </td>
                                                                <td class="p-2 text-right align-middle">
                                                                    <div class="flex items-center justify-end gap-1">
                                                                        <!-- Botón Agregar Fila Aquí -->
                                                                        <button type="button" onclick="agregarFilaModal('SIN_LOTE', '', '', this)"
                                                                            class="text-blue-600 hover:text-blue-800 font-bold p-1 bg-blue-50 hover:bg-blue-100 rounded border border-blue-200" title="Insertar fila abajo">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                                            </svg>
                                                                        </button>

                                                                        <form action="{{ route('publicaciones.destroy', $pub->idPublicacion) }}" method="POST"
                                                                            onsubmit="return confirm('¿Borrar publicación?');" class="inline">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button class="text-red-400 hover:text-red-600 font-bold p-1 hover:bg-red-50 rounded" title="Eliminar">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                                </svg>
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>

                                            <button type="button" onclick="agregarFilaModal('SIN_LOTE', '', '')"
                                                class="text-blue-600 hover:text-blue-800 font-bold text-sm flex items-center gap-1 px-4 py-2 hover:bg-blue-50 rounded w-full justify-center border border-dashed border-blue-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                                </svg>
                                                Agregar Fila a este Calendario
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        @else
                            <div class="text-center py-16 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300 flex flex-col items-center justify-center mt-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-1">No hay calendarios creados</h3>
                                <p class="text-gray-500 mb-6 max-w-sm">Este cliente aún no tiene contenido. Usa el botón verde de arriba para empezar.</p>
                            </div>
                        @endif
                    </div>

                    <!-- EDITOR -->
                    <div id="view-editor" class="hidden">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-bold text-gray-800">Nuevo Calendario</h3>
                            <button type="button" onclick="toggleView('dashboard')"
                                class="text-gray-500 hover:text-gray-700 font-medium px-4 py-2 border rounded bg-white shadow-sm hover:bg-gray-50">
                                Cancelar y Volver
                            </button>
                        </div>

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 p-4 bg-blue-50 rounded-lg border border-blue-100">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Fecha Inicio</label>
                                    <input type="date" id="rango_inicio" onchange="resetGenerarTabla()" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Fecha Fin</label>
                                    <input type="date" id="rango_fin" onchange="resetGenerarTabla()" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div class="flex items-end">
                                    <button type="button" id="btn-generar-tabla" onclick="inicializarTabla()"
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                        Generar Tabla
                                    </button>
                                </div>
                            </div>

                            <form action="{{ route('publicaciones.storeMasivo') }}" method="POST" id="form-masivo">
                                @csrf
                                <input type="hidden" name="cliente_id" value="{{ $clienteSeleccionado->id_cliente }}">

                                <div class="overflow-x-auto mb-6">
                                    <table class="min-w-full divide-y divide-gray-200" id="tabla-editor">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-32">Red Social</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-32">Fecha</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-32">Formato</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Copy</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Arte</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-28">Estatus</th>
                                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase w-10">X</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200" id="cuerpo-tabla"></tbody>
                                    </table>
                                </div>

                                <div class="flex justify-between items-center pt-4 border-t">
                                    <button type="button" onclick="agregarFila()"
                                        class="text-blue-600 hover:text-blue-800 font-bold text-sm flex items-center gap-1 px-4 py-2 hover:bg-blue-50 rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                        </svg>
                                        Agregar Fila Manualmente
                                    </button>

                                    <button type="submit"
                                        class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded shadow-lg transform transition hover:scale-105">
                                        Guardar Calendario
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

                <!-- ✅ MODAL -->
                <div id="modal-overlay" class="hidden fixed inset-0 z-50">
                    <div class="absolute inset-0 bg-black/50" onclick="closeModal()"></div>

                    <!-- Aumenté el ancho máximo a max-w-7xl para tener más espacio -->
                    <div class="relative mx-auto mt-5 md:mt-10 w-[95%] max-w-7xl bg-white rounded-2xl shadow-xl overflow-hidden flex flex-col max-h-[90vh]">
                        <div class="p-5 border-b flex items-start justify-between gap-4 shrink-0">
                            <div>
                                <h3 id="modal-title" class="text-lg font-extrabold text-gray-900">Calendario</h3>
                                <p class="text-sm text-gray-500">Edita directamente los campos y haz clic en "Guardar todos los cambios".</p>
                            </div>

                            <button type="button" onclick="closeModal()"
                                class="px-3 py-2 rounded-lg border bg-white hover:bg-gray-50 text-gray-700 font-semibold">
                                Cerrar ✕
                            </button>
                        </div>

                        <div class="p-5 bg-gray-50 border-b shrink-0">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 mb-1">Filtrar por estatus</label>
                                    <select id="filtro-estatus" class="w-full rounded-md border-gray-300 text-sm">
                                        <option value="">Todos</option>
                                        <option value="Pendiente">Pendiente</option>
                                        <option value="Publicado">Publicado</option>
                                        <option value="Reprogramar">Reprogramar</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-600 mb-1">Filtrar por plataforma</label>
                                    <select id="filtro-plataforma" class="w-full rounded-md border-gray-300 text-sm">
                                        <option value="">Todas</option>
                                        @foreach($plataformas as $p)
                                            <option value="{{ $p->id }}">{{ $p->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-600 mb-1">Buscar</label>
                                    <input id="filtro-busqueda" type="text" placeholder="fecha, plataforma, copy, arte..."
                                        class="w-full rounded-md border-gray-300 text-sm">
                                </div>
                            </div>
                        </div>

                        <div class="p-5 overflow-auto flex-grow">
                            <div id="modal-body"></div>
                        </div>
                    </div>
                </div>

            @else
                <div class="text-center py-20 bg-white rounded-lg border shadow-sm mt-6">
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Selecciona un cliente arriba</h3>
                    <p class="mt-1 text-gray-500">Para ver sus calendarios o crear uno nuevo.</p>
                </div>
            @endif

        </div>
    </div>

    @push('scripts')
    <script>
        function toggleView(view) {
            const dashboard = document.getElementById('view-dashboard');
            const editor = document.getElementById('view-editor');
            if (!dashboard || !editor) return;

            if (view === 'editor') {
                dashboard.classList.add('hidden');
                editor.classList.remove('hidden');
                
                // Resetear el botón al abrir el editor
                const btn = document.getElementById('btn-generar-tabla');
                if (btn) {
                    btn.disabled = false;
                    btn.textContent = 'Generar Tabla';
                }
            } else {
                editor.classList.add('hidden');
                dashboard.classList.remove('hidden');
            }
        }

        // --- MODAL ---
        let currentModalRows = [];
        let modalRowIndex = 0; // Índice para filas nuevas en el modal

        function openModal(lote, titulo) {
            const overlay = document.getElementById('modal-overlay');
            const title = document.getElementById('modal-title');
            const body = document.getElementById('modal-body');

            const content = document.getElementById('modal-content-' + lote);
            if (!overlay || !title || !body || !content) return;

            title.textContent = titulo || 'Calendario';
            body.innerHTML = content.innerHTML;

            // --- FIX PARA IDS DUPLICADOS ---
            // IMPORTANTE: Al copiar el HTML, el ID del form se duplica.
            // Aquí lo renombramos a "modal-form-masivo" y re-vinculamos los inputs.
            const form = body.querySelector('form[id^="form-masivo-"]');
            if (form) {
                const oldId = form.id;
                const newId = 'modal-' + oldId;
                form.id = newId;

                // Re-vincular botón de guardar y los inputs
                const linkedElements = body.querySelectorAll(`[form="${oldId}"]`);
                linkedElements.forEach(el => {
                    el.setAttribute('form', newId);
                });
            }

            // cache rows
            currentModalRows = Array.from(body.querySelectorAll('.modal-row'));

            // reset filtros
            document.getElementById('filtro-estatus').value = '';
            document.getElementById('filtro-plataforma').value = '';
            document.getElementById('filtro-busqueda').value = '';

            overlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Evitar scroll en el body

            setTimeout(() => {
                document.getElementById('filtro-busqueda')?.focus();
            }, 50);
        }

        function closeModal() {
            const overlay = document.getElementById('modal-overlay');
            const body = document.getElementById('modal-body');
            if (!overlay || !body) return;

            overlay.classList.add('hidden');
            body.innerHTML = '';
            currentModalRows = [];
            document.body.style.overflow = ''; // Restaurar scroll
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeModal();
        });

        function applyFilters() {
            const estatus = document.getElementById('filtro-estatus')?.value || '';
            const plataforma = document.getElementById('filtro-plataforma')?.value || '';
            const q = (document.getElementById('filtro-busqueda')?.value || '').trim().toLowerCase();

            currentModalRows.forEach(row => {
                const rowEstatus = row.getAttribute('data-estatus') || '';
                const rowPlat = row.getAttribute('data-plataforma-id') || '';
                const rowSearch = row.getAttribute('data-search') || '';

                let ok = true;

                if (estatus && rowEstatus !== estatus) ok = false;
                if (plataforma && rowPlat !== plataforma) ok = false;
                if (q && !rowSearch.includes(q)) ok = false;

                row.style.display = ok ? '' : 'none';
            });
        }

        document.getElementById('filtro-estatus')?.addEventListener('change', applyFilters);
        document.getElementById('filtro-plataforma')?.addEventListener('change', applyFilters);
        document.getElementById('filtro-busqueda')?.addEventListener('input', applyFilters);

        // --- EDITOR (tabla dinámica) ---
        let rowIndex = 0;
        const plataformas = @json($plataformas ?? []);
        const formatos = @json($formatos ?? []);

        function generarOpciones(items) {
            return (items || []).map(i => `<option value="${i.id}">${i.nombre}</option>`).join('');
        }

        function agregarFila(fechaPredefinida = '', btnReferencia = null) {
            const tbody = document.getElementById('cuerpo-tabla');
            if (!tbody) return;

            const minDate = document.getElementById('rango_inicio')?.value || '';
            const maxDate = document.getElementById('rango_fin')?.value || '';
            let dateAttr = '';
            if (minDate && maxDate) dateAttr = `min="${minDate}" max="${maxDate}"`;

            const tr = document.createElement('tr');

            tr.innerHTML = `
                <td class="px-2 py-2">
                    <select name="items[${rowIndex}][plataforma_id]" class="w-full text-sm border-gray-300 rounded focus:ring-blue-500" required>
                        ${generarOpciones(plataformas)}
                    </select>
                </td>
                <td class="px-2 py-2">
                    <input type="date" name="items[${rowIndex}][fecha]" value="${fechaPredefinida}" ${dateAttr}
                        class="w-full text-sm border-gray-300 rounded focus:ring-blue-500" required>
                </td>
                <td class="px-2 py-2">
                    <select name="items[${rowIndex}][formato_id]" class="w-full text-sm border-gray-300 rounded focus:ring-blue-500" required>
                        ${generarOpciones(formatos)}
                    </select>
                </td>
                <td class="px-2 py-2">
                    <textarea name="items[${rowIndex}][copy]" rows="3"
                        class="w-full text-sm border-gray-300 rounded focus:ring-blue-500" placeholder="#Hashtags..."></textarea>
                </td>
                <td class="px-2 py-2">
                    <textarea name="items[${rowIndex}][arte]" rows="3"
                        class="w-full text-sm border-gray-300 rounded focus:ring-blue-500" placeholder="Visual..."></textarea>
                </td>
                <td class="px-2 py-2">
                    <select name="items[${rowIndex}][estatus]" class="w-full text-sm border-gray-300 rounded focus:ring-blue-500">
                        <option value="Pendiente">Pendiente</option>
                        <option value="Publicado">Publicado</option>
                        <option value="Reprogramar">Reprogramar</option>
                    </select>
                </td>
                <td class="px-2 py-2 text-center align-middle">
                    <div class="flex items-center justify-center gap-1">
                        <button type="button" onclick="agregarFila('', this)" 
                            class="text-blue-600 hover:text-blue-800 font-bold p-1 bg-blue-50 hover:bg-blue-100 rounded border border-blue-200" title="Insertar fila abajo">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        <button type="button" onclick="this.closest('tr').remove()" class="text-red-500 hover:text-red-700 font-bold p-1 hover:bg-red-50 rounded" title="Eliminar fila">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </td>
            `;

            if (btnReferencia) {
                const currentRow = btnReferencia.closest('tr');
                if (currentRow && currentRow.nextSibling) {
                    tbody.insertBefore(tr, currentRow.nextSibling);
                } else {
                    tbody.appendChild(tr);
                }
            } else {
                tbody.appendChild(tr);
            }
            rowIndex++;
        }

        // --- AGREGAR FILA DENTRO DEL MODAL ---
        function agregarFilaModal(lote, minDate = '', maxDate = '', btnReferencia = null) {
            const modalBody = document.getElementById('modal-body');
            const tbody = modalBody.querySelector('tbody');
            if (!tbody) return;

            // ID del formulario activo en el modal (renombrado por openModal)
            const formId = 'modal-form-masivo-' + lote;
            
            const tr = document.createElement('tr');
            tr.className = 'modal-row align-top hover:bg-gray-50 transition-colors bg-blue-50';
            
            const index = 'new_' + modalRowIndex++;
            const prefix = `nuevas[${index}]`;

            // Construir atributos min y max si existen
            const minAttr = minDate ? `min="${minDate}"` : '';
            const maxAttr = maxDate ? `max="${maxDate}"` : '';

            tr.innerHTML = `
                <td class="p-2">
                    <input type="date" name="${prefix}[fecha]" form="${formId}" 
                        class="w-full text-xs border-gray-300 rounded focus:ring-blue-500" 
                        ${minAttr} ${maxAttr} required>
                </td>
                <td class="p-2">
                    <select name="${prefix}[plataforma_id]" form="${formId}" class="w-full text-xs border-gray-300 rounded focus:ring-blue-500" required>
                        ${generarOpciones(plataformas)}
                    </select>
                </td>
                <td class="p-2">
                    <select name="${prefix}[formato_id]" form="${formId}" class="w-full text-xs border-gray-300 rounded focus:ring-blue-500" required>
                        ${generarOpciones(formatos)}
                    </select>
                </td>
                <td class="p-2">
                    <textarea name="${prefix}[copy]" form="${formId}" rows="4" class="w-full text-xs border-gray-300 rounded focus:ring-blue-500 resize-y" placeholder="Nuevo copy..."></textarea>
                </td>
                <td class="p-2">
                    <textarea name="${prefix}[arte]" form="${formId}" rows="4" class="w-full text-xs border-gray-300 rounded focus:ring-blue-500 resize-y" placeholder="Nuevo arte..."></textarea>
                </td>
                <td class="p-2">
                    <select name="${prefix}[estatus]" form="${formId}" class="w-full text-xs border-gray-300 rounded focus:ring-blue-500">
                        <option value="Pendiente">Pendiente</option>
                        <option value="Publicado">Publicado</option>
                        <option value="Reprogramar">Reprogramar</option>
                    </select>
                </td>
                <td class="p-2 text-right align-middle">
                    <div class="flex items-center justify-end gap-1">
                        <button type="button" onclick="agregarFilaModal('${lote}', '${minDate}', '${maxDate}', this)" 
                            class="text-blue-600 hover:text-blue-800 font-bold p-1 bg-blue-50 hover:bg-blue-100 rounded border border-blue-200" title="Insertar fila abajo">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        <button type="button" onclick="this.closest('tr').remove()" class="text-red-500 hover:text-red-700 font-bold p-1 hover:bg-red-50 rounded" title="Cancelar fila">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </td>
            `;
            
            if (btnReferencia) {
                const currentRow = btnReferencia.closest('tr');
                if (currentRow && currentRow.nextSibling) {
                    tbody.insertBefore(tr, currentRow.nextSibling);
                } else {
                    tbody.appendChild(tr);
                }
            } else {
                tbody.appendChild(tr);
            }
        }

        function resetGenerarTabla() {
            const btn = document.getElementById('btn-generar-tabla');
            if (btn) {
                btn.disabled = false;
                btn.textContent = 'Generar Tabla';
            }
        }

        function inicializarTabla() {
            const inicio = document.getElementById('rango_inicio')?.value || '';
            const fin = document.getElementById('rango_fin')?.value || '';

            if (!inicio || !fin) {
                alert('Por favor selecciona ambas fechas primero.');
                return;
            }
            if (inicio > fin) {
                alert('La fecha de inicio no puede ser mayor a la fecha fin.');
                return;
            }

            const tbody = document.getElementById('cuerpo-tabla');
            if (tbody) tbody.innerHTML = '';
            rowIndex = 0;

            agregarFila(inicio);
            agregarFila(fin);
            
            // Deshabilitar botón
            const btn = document.getElementById('btn-generar-tabla');
            if (btn) {
                btn.disabled = true;
                btn.textContent = 'Tabla Generada';
            }
        }
    </script>
    @endpush
</x-app-layout>