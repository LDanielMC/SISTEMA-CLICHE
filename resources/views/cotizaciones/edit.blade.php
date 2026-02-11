<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Cotización #{{ str_pad($cotizacion->id_cotizacion, 5, '0', STR_PAD_LEFT) }}
        </h2>
    </x-slot>

    <div class="py-8" x-data="cotizador()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('cotizaciones.update', $cotizacion->id_cotizacion) }}" method="POST" @submit.prevent="submitForm($event)">
                    @csrf
                    @method('PUT')

                    {{-- ================= SECCIÓN 1: CABECERA ================= --}}
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 border-b pb-6">

                        {{-- Título --}}
                        <div class="md:col-span-4">
                            <label class="block text-sm font-medium text-gray-700">Título del Proyecto / Cotización</label>
                            <input type="text" name="titulo_cotizacion"
                                   value="{{ old('titulo_cotizacion', $cotizacion->titulo_cotizacion) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm font-bold text-gray-800" required>
                            @error('titulo_cotizacion') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Cliente --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Cliente</label>
                            <select name="id_cliente" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="">-- Seleccionar Cliente --</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id_cliente }}"
                                        {{ old('id_cliente', $cotizacion->id_cliente) == $cliente->id_cliente ? 'selected' : '' }}>
                                        {{ $cliente->nombre }} {{ $cliente->apellido_paterno }} - {{ $cliente->empresa }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_cliente') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Fecha Emisión --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fecha Emisión</label>
                            <input type="date" name="fecha" value="{{ old('fecha', $cotizacion->fecha->format('Y-m-d')) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            @error('fecha') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Vencimiento --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Vence en (días)</label>
                            <input type="number" name="vencimiento_dias"
                                   value="{{ old('vencimiento_dias', $cotizacion->vencimiento_dias) }}" min="1"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            @error('vencimiento_dias') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Introducción --}}
                        <div class="md:col-span-4 mb-4">
                            <label class="block text-sm font-medium text-gray-700">Texto de Introducción (Opcional)</label>
                            <textarea name="texto_introduccion" rows="2"
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('texto_introduccion', $cotizacion->texto_introduccion) }}</textarea>
                        </div>
                    </div>

                    {{-- ================= SECCIÓN 2: PARTIDAS ================= --}}
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="text-lg font-medium text-gray-900">Conceptos de la Cotización</h3>
                            <button type="button" @click="addNewRow()" 
                                    class="text-sm text-emerald-600 hover:text-emerald-800 font-medium">
                                + Agregar al final
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mb-2">Usa las flechas para reordenar o el botón "+" para insertar filas intermedias.</p>

                        {{-- overflow-x-auto asegura scroll horizontal si no cabe --}}
                        <div class="overflow-x-auto border border-gray-200 rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider text-left">
                                    <tr>
                                        {{-- Orden (Fijo pequeño) --}}
                                        <th class="px-2 py-3 w-12 min-w-[50px] text-center">Orden</th>
                                        
                                        {{-- Título (Mínimo 200px) --}}
                                        <th class="px-4 py-3 min-w-[200px]">Título / Servicio</th>
                                        
                                        {{-- Descripción (Flexible pero mínimo 250px) --}}
                                        <th class="px-4 py-3 min-w-[250px]">Descripción Detallada</th>
                                        
                                        {{-- Cantidad (Mínimo 80px) --}}
                                        <th class="px-4 py-3 min-w-[90px] text-right">Cant.</th>
                                        
                                        {{-- Precio U. (Mínimo 130px) --}}
                                        <th class="px-4 py-3 min-w-[130px] text-right">Precio U.</th>
                                        
                                        {{-- IVA % y $ (Mínimo 180px) --}}
                                        <th class="px-4 py-3 min-w-[180px] text-right">IVA (% / $)</th>
                                        
                                        {{-- Total (Mínimo 120px) --}}
                                        <th class="px-4 py-3 min-w-[120px] text-right">Total</th>
                                        
                                        {{-- Acciones (Fijo) --}}
                                        <th class="px-4 py-3 w-20 min-w-[90px] text-center">Acciones</th>
                                    </tr>
                                </thead>

                                <tbody class="bg-white divide-y divide-gray-200">
                                    <template x-for="(row, index) in rows" :key="row.uid">
                                        <tr class="group bg-white hover:bg-gray-50 transition-colors">

                                            {{-- ORDEN --}}
                                            <td class="px-2 py-2 text-center align-top pt-3">
                                                <input type="hidden" :name="'partidas['+index+'][id_detalle]'" x-model="row.id_detalle">
                                                <input type="hidden" :name="'partidas['+index+'][folio]'" x-model="row.folio">

                                                <div class="flex flex-col items-center justify-center space-y-1">
                                                    <button type="button" @click="moveUp(index)" 
                                                            class="text-gray-400 hover:text-indigo-600 disabled:opacity-25 disabled:cursor-not-allowed"
                                                            :disabled="index === 0" title="Subir">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                                        </svg>
                                                    </button>
                                                    <button type="button" @click="moveDown(index)" 
                                                            class="text-gray-400 hover:text-indigo-600 disabled:opacity-25 disabled:cursor-not-allowed"
                                                            :disabled="index === rows.length - 1" title="Bajar">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>

                                            {{-- Título --}}
                                            <td class="px-4 py-2 align-top">
                                                <textarea :name="'partidas['+index+'][titulo]'" x-model="row.titulo" rows="2"
                                                       class="w-full text-sm rounded-md font-medium"
                                                       :class="isDuplicate(index) ? 'border-red-500 bg-red-50' : 'border-gray-300'"
                                                       required></textarea>
                                                <p x-show="isDuplicate(index)" style="color:#dc2626; font-size:12px; margin-top:2px;">⚠ Título duplicado</p>
                                            </td>

                                            {{-- Descripción --}}
                                            <td class="px-4 py-2 align-top">
                                                <textarea :name="'partidas['+index+'][descripcion]'" x-model="row.descripcion" rows="2"
                                                          class="w-full text-sm border-gray-300 rounded-md" required></textarea>
                                            </td>

                                            {{-- Cantidad --}}
                                            <td class="px-4 py-2 align-top">
                                                <input type="number" step="0.01" :name="'partidas['+index+'][cantidad]'" x-model="row.cantidad"
                                                       @input="row.iva = calculateIvaFromPercent(row)"
                                                       class="w-full text-sm text-right border-gray-300 rounded-md" required>
                                            </td>

                                            {{-- Precio U. --}}
                                            <td class="px-4 py-2 align-top">
                                                <input type="number" step="0.01" :name="'partidas['+index+'][precio_unitario]'" x-model="row.precio_unitario"
                                                       @input="row.iva = calculateIvaFromPercent(row)"
                                                       class="w-full text-sm text-right border-gray-300 rounded-md" required>
                                            </td>

                                            {{-- IVA % y $ --}}
                                            <td class="px-4 py-2 align-top">
                                                <div class="flex items-center gap-1">
                                                    <div class="flex items-center">
                                                        <input type="number" step="0.01" min="0" max="100"
                                                               x-model="row.iva_porcentaje" 
                                                               @input="row.iva = calculateIvaFromPercent(row)"
                                                               class="w-16 text-sm text-right border-gray-300 rounded-l-md focus:ring-emerald-500 focus:border-emerald-500"
                                                               placeholder="16">
                                                        <span class="px-1 py-1.5 bg-gray-100 border border-l-0 border-gray-300 text-gray-500 text-xs">%</span>
                                                    </div>
                                                    <input type="number" step="0.01" :name="'partidas['+index+'][iva]'" x-model="row.iva" 
                                                           @input="row.iva_porcentaje = calculatePercentFromIva(row)"
                                                           class="w-24 text-sm text-right border-gray-300 rounded-md text-gray-600"
                                                           placeholder="0.00">
                                                </div>
                                            </td>

                                            {{-- Total --}}
                                            <td class="px-4 py-2 text-right font-bold text-gray-700 align-top pt-3">
                                                <span x-text="formatMoney(calculateLineTotal(row))"></span>
                                            </td>

                                            {{-- Acciones --}}
                                            <td class="px-4 py-2 text-center align-top pt-3">
                                                <div class="flex items-center justify-center space-x-2">
                                                    {{-- Insertar Debajo --}}
                                                    <button type="button" @click="addRowAfter(index)" class="text-emerald-600 hover:text-emerald-800 bg-emerald-50 p-1 rounded-full hover:bg-emerald-100 transition-colors" title="Insertar fila debajo">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                                        </svg>
                                                    </button>

                                                    {{-- Eliminar --}}
                                                    <button type="button" @click="removeRow(index)" class="text-red-400 hover:text-red-600 p-1 rounded-full hover:bg-red-50 transition-colors" title="Eliminar fila">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>

                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-2 text-right">
                             @if($errors->has('partidas')) <p class="text-sm text-red-600 mt-2">Debes agregar al menos un servicio.</p> @endif
                        </div>
                    </div>

                    {{-- ================= SECCIÓN 3: TOTALES Y NOTAS ================= --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="md:col-span-2 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Notas</label>
                                <textarea name="notas" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('notas', $cotizacion->notas) }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Estatus</label>
                                <select name="estatus" class="mt-1 block w-40 border-gray-300 rounded-md shadow-sm">
                                    <option value="pendiente" {{ old('estatus', $cotizacion->estatus) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="aceptada" {{ old('estatus', $cotizacion->estatus) == 'aceptada' ? 'selected' : '' }}>Aceptada</option>
                                    <option value="rechazada" {{ old('estatus', $cotizacion->estatus) == 'rechazada' ? 'selected' : '' }}>Rechazada</option>
                                </select>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600">Subtotal:</span>
                                <span class="font-semibold text-gray-800" x-text="formatMoney(netSubtotal)"></span>
                            </div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600">IVA Total:</span>
                                <span class="font-semibold text-gray-800" x-text="formatMoney(netIva)"></span>
                            </div>
                            
                            {{-- NUEVO: Campo ISR --}}
                            <div class="flex justify-between items-center mb-2">
                                <label for="porcentaje_isr" class="text-gray-600 text-sm font-medium">ISR (+%):</label>
                                <div class="flex items-center gap-2">
                                    <input type="number" 
                                           name="porcentaje_isr" 
                                           id="porcentaje_isr"
                                           x-model="porcentaje_isr" 
                                           min="0" max="100" step="0.01" 
                                           placeholder="0"
                                           class="w-20 text-right p-1 text-sm border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                                    <span class="font-semibold text-gray-800 min-w-[80px] text-right" x-text="formatMoney(netIsr)"></span>
                                </div>
                            </div>

                            <div class="border-t border-gray-300 my-2"></div>
                            <div class="flex justify-between items-center text-lg">
                                <span class="font-bold text-gray-900">Total:</span>
                                <span class="font-bold text-emerald-600" x-text="formatMoney(netTotal)"></span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end gap-3">
                        <a href="{{ route('cotizaciones.index') }}" class="px-4 py-2 border rounded-md text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
                        <button type="submit" class="px-6 py-2 bg-emerald-600 text-white font-bold rounded-md hover:bg-emerald-700 shadow-md">
                            Actualizar Cotización
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        function cotizador() {
            const partidasOldRaw = @json(old('partidas'));
            const partidasDbRaw  = @json($cotizacion->detalles);

            // Función auxiliar para calcular porcentaje desde IVA
            function calcPercent(row) {
                let cant = parseFloat(row.cantidad) || 0;
                let prec = parseFloat(row.precio_unitario) || 0;
                let iva = parseFloat(row.iva) || 0;
                let subtotal = cant * prec;
                if (subtotal === 0) return 0;
                return Math.round((iva / subtotal) * 100 * 100) / 100;
            }

            // OLD: conservar folio si ya venía, si no asignar uno
            const partidasOld = (partidasOldRaw || []).map((r, i) => ({
                ...r,
                uid: r.uid || (r.id_detalle ? ('db_' + r.id_detalle) : ('tmp_' + i + '_' + Date.now())),
                folio: (r.folio !== undefined && r.folio !== null) ? r.folio : (i + 1),
                iva_porcentaje: r.iva_porcentaje || calcPercent(r),
            }));

            // DB: folio fijo por carga inicial
            const partidasDb = (partidasDbRaw || []).map((r, i) => ({
                ...r,
                uid: 'db_' + r.id_detalle,
                folio: i + 1,
                iva_porcentaje: r.iva_porcentaje || calcPercent(r),
            }));

            return {
                // ✅ Inicializamos con el valor de la BD (o 0 si no existe)
                porcentaje_isr: {{ old('porcentaje_isr', $cotizacion->porcentaje_isr ?? 0) }},

                rows: partidasOld.length ? partidasOld : (partidasDb.length ? partidasDb : []),

                // ✅ Función para crear una estructura de fila vacía
                createEmptyRow() {
                    const uid = (window.crypto && crypto.randomUUID) ? ('tmp_' + crypto.randomUUID()) : ('tmp_' + Date.now());
                    const maxFolio = this.rows.reduce((m, r) => Math.max(m, parseInt(r.folio || 0)), 0);
                    
                    return {
                        uid,
                        folio: maxFolio + 1,
                        id_detalle: null,
                        titulo: '',
                        descripcion: '',
                        cantidad: 1,
                        precio_unitario: 0,
                        iva: 0,
                        iva_porcentaje: 16
                    };
                },

                // Agregar al final
                addNewRow() {
                    this.rows.push(this.createEmptyRow());
                },

                // Insertar debajo
                addRowAfter(index) {
                    this.rows.splice(index + 1, 0, this.createEmptyRow());
                },

                // Mover Arriba
                moveUp(index) {
                    if (index > 0) {
                        const item = this.rows[index];
                        this.rows.splice(index, 1);
                        this.rows.splice(index - 1, 0, item);
                    }
                },

                // Mover Abajo
                moveDown(index) {
                    if (index < this.rows.length - 1) {
                        const item = this.rows[index];
                        this.rows.splice(index, 1);
                        this.rows.splice(index + 1, 0, item);
                    }
                },

                removeRow(index) {
                    if (this.rows.length > 1) {
                        this.rows.splice(index, 1);
                    } else {
                        alert('La cotización debe tener al menos un concepto.');
                    }
                },

                // Verificar si el título de una partida está duplicado
                isDuplicate(index) {
                    let titulo = (this.rows[index].titulo || '').trim().toLowerCase();
                    if (!titulo) return false;
                    return this.rows.some((r, i) => i !== index && (r.titulo || '').trim().toLowerCase() === titulo);
                },

                // Verificar si hay algún duplicado en todas las partidas
                hasDuplicates() {
                    let titulos = this.rows.map(r => (r.titulo || '').trim().toLowerCase()).filter(t => t !== '');
                    return titulos.length !== new Set(titulos).size;
                },

                // Enviar formulario solo si no hay duplicados
                submitForm(event) {
                    if (this.hasDuplicates()) {
                        alert('Hay títulos de servicio duplicados. Por favor corrige antes de guardar.');
                        return;
                    }
                    event.target.submit();
                },

                calculateLineTotal(row) {
                    let cant = parseFloat(row.cantidad) || 0;
                    let prec = parseFloat(row.precio_unitario) || 0;
                    let iva  = parseFloat(row.iva) || 0;
                    return (cant * prec) + iva;
                },

                // Calcular IVA en $ desde el porcentaje
                calculateIvaFromPercent(row) {
                    let cant = parseFloat(row.cantidad) || 0;
                    let prec = parseFloat(row.precio_unitario) || 0;
                    let pct = parseFloat(row.iva_porcentaje) || 0;
                    let subtotal = cant * prec;
                    return Math.round(subtotal * (pct / 100) * 100) / 100;
                },

                // Calcular porcentaje desde el IVA en $
                calculatePercentFromIva(row) {
                    let cant = parseFloat(row.cantidad) || 0;
                    let prec = parseFloat(row.precio_unitario) || 0;
                    let iva = parseFloat(row.iva) || 0;
                    let subtotal = cant * prec;
                    if (subtotal === 0) return 0;
                    return Math.round((iva / subtotal) * 100 * 100) / 100;
                },

                get netSubtotal() {
                    return this.rows.reduce((sum, row) => {
                        return sum + (parseFloat(row.cantidad || 0) * parseFloat(row.precio_unitario || 0));
                    }, 0);
                },

                get netIva() {
                    return this.rows.reduce((sum, row) => {
                        return sum + parseFloat(row.iva || 0);
                    }, 0);
                },

                // ✅ PROPIEDAD ISR
                get netIsr() {
                    let subtotal = this.netSubtotal;
                    let pct = parseFloat(this.porcentaje_isr) || 0;
                    return subtotal * (pct / 100);
                },

                get netTotal() {
                    // SUMAMOS: Subtotal + IVA + ISR
                    return this.netSubtotal + this.netIva + this.netIsr;
                },

                formatMoney(amount) {
                    return new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(amount);
                }
            }
        }
    </script>
</x-app-layout>