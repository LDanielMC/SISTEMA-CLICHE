<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Minuta #{{ str_pad($minuta->id_minuta, 5, '0', STR_PAD_LEFT) }}
        </h2>
    </x-slot>

    {{-- Quill.js CSS --}}
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    
    <style>
        /* Estilos para editores Quill */
        .ql-container {
            font-size: 14px;
            font-family: inherit;
        }
        
        .ql-editor {
            min-height: inherit;
            padding: 12px 15px;
        }
        
        .ql-toolbar {
            background-color: #f9fafb;
            border-top-left-radius: 0.375rem;
            border-top-right-radius: 0.375rem;
        }
        
        .ql-container {
            border-bottom-left-radius: 0.375rem;
            border-bottom-right-radius: 0.375rem;
        }
        
        /* Espaciado entre campos */
        .campo-editor {
            margin-bottom: 1.5rem;
        }
        
        /* Mejorar visualización de la tabla de acuerdos */
        .tabla-acuerdos-container {
            margin-top: 2rem;
        }
    </style>

    <div class="py-8" x-data="minutador()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('minutas.update', $minuta->id_minuta) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- ================= SECCIÓN 1: CABECERA ================= --}}
                    <div class="mb-8">
                        <h3 class="text-md font-semibold text-gray-700 mb-4">Información General</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            {{-- Cliente --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Cliente</label>
                                <select name="id_cliente" class="block w-full border-gray-300 rounded-md shadow-sm" required>
                                    <option value="">-- Selecciona un cliente --</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id_cliente }}" {{ old('id_cliente', $minuta->id_cliente) == $cliente->id_cliente ? 'selected' : '' }}>
                                            {{ $cliente->nombre }} - {{ $cliente->empresa }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_cliente') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Fecha --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Minuta</label>
                                <input type="date" name="fecha" value="{{ old('fecha', $minuta->fecha->format('Y-m-d')) }}" 
                                       class="block w-full border-gray-300 rounded-md shadow-sm" required>
                                @error('fecha') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Título --}}
                            <div class="md:col-span-3">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Título de la Minuta</label>
                                <input type="text" name="titulo" value="{{ old('titulo', $minuta->titulo) }}" 
                                       placeholder="Ej: Reunión de seguimiento proyecto X, Revisión de avances Q1, etc."
                                       class="block w-full border-gray-300 rounded-md shadow-sm">
                                <p class="text-xs text-gray-500 mt-1">Opcional: Agrega un título descriptivo para identificar fácilmente esta minuta</p>
                                @error('titulo') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- ================= SECCIÓN 2: CONTENIDO DE LA REUNIÓN ================= --}}
                    <div class="mb-8 pb-6 border-b">
                        <h3 class="text-md font-semibold text-gray-700 mb-4">Contenido de la Reunión</h3>
                        
                        {{-- Asistentes --}}
                        <div class="campo-editor">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Asistentes</label>
                            <div id="editor-asistentes" class="bg-white" style="min-height: 100px;"></div>
                            <input type="hidden" name="asistentes" id="asistentes-input">
                            @error('asistentes') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Puntos Tratados --}}
                        <div class="campo-editor">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Puntos Tratados</label>
                            <div id="editor-puntos" class="bg-white" style="min-height: 150px;"></div>
                            <input type="hidden" name="puntos_tratados" id="puntos-input">
                            @error('puntos_tratados') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Observaciones --}}
                        <div class="campo-editor">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Observaciones</label>
                            <div id="editor-observaciones" class="bg-white" style="min-height: 120px;"></div>
                            <input type="hidden" name="observaciones" id="observaciones-input">
                            @error('observaciones') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- ================= SECCIÓN 3: ACUERDOS ================= --}}
                    <div class="tabla-acuerdos-container mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="text-lg font-bold text-gray-800">Acuerdos / Compromisos</h3>
                            <button type="button" @click="addNewRow()" 
                                    class="px-3 py-1 bg-emerald-600 text-white text-sm rounded hover:bg-emerald-700">
                                + Agregar Acuerdo
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mb-2">Registra los acuerdos y compromisos derivados de la minuta.</p>

                        {{-- Tabla responsiva --}}
                        <div class="overflow-x-auto border border-gray-200 rounded-lg">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-2 py-2 text-center font-semibold text-gray-700 w-16">Orden</th>
                                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Acuerdo / Compromiso</th>
                                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Responsable</th>
                                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Estatus</th>
                                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Fecha Límite</th>
                                        <th class="px-4 py-2 text-center font-semibold text-gray-700 w-24">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(row, index) in rows" :key="index">
                                        <tr class="border-b border-gray-200 transition-all duration-300"
                                            :class="row.estatus === 'completado' ? '' : 'hover:bg-gray-50'"
                                            :style="row.estatus === 'completado' ? 'background: linear-gradient(135deg, rgba(219, 234, 254, 0.4) 0%, rgba(207, 250, 254, 0.4) 40%, rgba(204, 251, 241, 0.4) 70%, rgba(252, 231, 243, 0.4) 100%);' : ''">
                                            {{-- Orden (Flechas) --}}
                                            <td class="px-2 py-2 text-center align-top pt-3">
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

                                            {{-- Acuerdo --}}
                                            <td class="px-4 py-3">
                                                <input type="hidden" :name="'acuerdos[' + index + '][id_acuerdo]'" :value="row.id_acuerdo">
                                                <textarea :name="'acuerdos[' + index + '][acuerdo]'"
                                                          x-model="row.acuerdo"
                                                          class="w-full border-gray-300 rounded text-sm p-2"
                                                          rows="3" required></textarea>
                                            </td>

                                            {{-- Responsable --}}
                                            <td class="px-4 py-3">
                                                <input type="text" :name="'acuerdos[' + index + '][responsable]'" x-model="row.responsable"
                                                       class="w-full border-gray-300 rounded text-sm p-2"
                                                       placeholder="Nombre del responsable">
                                            </td>

                                            {{-- Estatus --}}
                                            <td class="px-4 py-3">
                                                <select :name="'acuerdos[' + index + '][estatus]'"
                                                        x-model="row.estatus"
                                                        class="border-gray-300 rounded text-sm w-full">
                                                    <option value="pendiente">Pendiente</option>
                                                    <option value="completado">Completado</option>
                                                </select>
                                            </td>

                                            {{-- Fecha Límite --}}
                                            <td class="px-4 py-3">
                                                <input type="date" :name="'acuerdos[' + index + '][fecha_limite]'"
                                                       x-model="row.fecha_limite"
                                                       class="border-gray-300 rounded text-sm w-full">
                                            </td>

                                            {{-- Acciones --}}
                                            <td class="px-4 py-3 text-center">
                                                <div class="flex items-center justify-center space-x-2">
                                                    {{-- Insertar Debajo --}}
                                                    <button type="button" @click="addRowAfter(index)" class="text-emerald-600 hover:text-emerald-800 bg-emerald-50 p-1 rounded-full hover:bg-emerald-100 transition-colors" title="Insertar fila debajo">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                    {{-- Eliminar --}}
                                                    <button type="button" @click="removeRow(index)"
                                                            class="text-red-600 hover:text-red-800 text-lg">
                                                        ✕
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-2 text-right">
                            <button type="button" @click="addNewRow()" 
                                    class="px-3 py-1 text-sm text-emerald-600 hover:bg-emerald-50 rounded">
                                + Agregar Acuerdo
                            </button>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end gap-3">
                        <a href="{{ route('minutas.index') }}" class="px-4 py-2 border rounded-md text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
                        <button type="submit" class="px-6 py-2 bg-emerald-600 text-white font-bold rounded-md hover:bg-emerald-700 shadow-md">
                            Guardar Cambios
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    {{-- Quill.js Script --}}
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

    <script>
        function minutador() {
            const acuerdosOldRaw = @json(old('acuerdos'));
            const acuerdosDbRaw  = @json($minuta->acuerdos);

            // OLD: conservar folio si ya venía
            const acuerdosOld = (acuerdosOldRaw || []).map((r, i) => ({
                ...r,
                id_acuerdo: r.id_acuerdo || null,
            }));

            // DB: datos de la BD
            const acuerdosDb = (acuerdosDbRaw || []).map((r, i) => ({
                id_acuerdo: r.id_acuerdo,
                acuerdo: r.acuerdo,
                responsable: r.responsable || '',
                estatus: r.estatus,
                fecha_limite: r.fecha_limite ? r.fecha_limite.split('T')[0] : '',
            }));

            return {
                rows: acuerdosOld.length ? acuerdosOld : (acuerdosDb.length ? acuerdosDb : []),

                createEmptyRow() {
                    return {
                        id_acuerdo: null,
                        acuerdo: '',
                        responsable: '',
                        estatus: 'pendiente',
                        fecha_limite: '',
                    };
                },

                addNewRow() {
                    this.rows.push(this.createEmptyRow());
                },

                // Insertar fila debajo
                addRowAfter(index) {
                    this.rows.splice(index + 1, 0, this.createEmptyRow());
                },

                // Mover fila arriba
                moveUp(index) {
                    if (index > 0) {
                        const item = this.rows[index];
                        this.rows.splice(index, 1);
                        this.rows.splice(index - 1, 0, item);
                    }
                },

                // Mover fila abajo
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
                        alert('La minuta debe tener al menos un acuerdo.');
                    }
                }
            }
        }

        // Datos iniciales desde el servidor
        const minutaData = {
            asistentes: @json(old('asistentes', $minuta->asistentes ?? '')),
            puntos_tratados: @json(old('puntos_tratados', $minuta->puntos_tratados ?? '')),
            observaciones: @json(old('observaciones', $minuta->observaciones ?? ''))
        };

        // Inicializar editores Quill
        document.addEventListener('DOMContentLoaded', function() {
            // Configuración de la toolbar
            const toolbarOptions = [
                ['bold', 'italic', 'underline'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'indent': '-1'}, { 'indent': '+1' }],
                ['clean']
            ];

            // Editor de Asistentes
            const quillAsistentes = new Quill('#editor-asistentes', {
                theme: 'snow',
                modules: {
                    toolbar: toolbarOptions
                },
                placeholder: 'Ej: Juan García, María López, Carlos Rodríguez...'
            });

            // Editor de Puntos Tratados
            const quillPuntos = new Quill('#editor-puntos', {
                theme: 'snow',
                modules: {
                    toolbar: toolbarOptions
                },
                placeholder: 'Describe los puntos tratados en la reunión...'
            });

            // Editor de Observaciones
            const quillObservaciones = new Quill('#editor-observaciones', {
                theme: 'snow',
                modules: {
                    toolbar: toolbarOptions
                },
                placeholder: 'Agrega observaciones adicionales...'
            });

            // Cargar contenido inicial de la base de datos
            if (minutaData.asistentes && minutaData.asistentes.trim() !== '') {
                quillAsistentes.clipboard.dangerouslyPasteHTML(minutaData.asistentes);
            }
            if (minutaData.puntos_tratados && minutaData.puntos_tratados.trim() !== '') {
                quillPuntos.clipboard.dangerouslyPasteHTML(minutaData.puntos_tratados);
            }
            if (minutaData.observaciones && minutaData.observaciones.trim() !== '') {
                quillObservaciones.clipboard.dangerouslyPasteHTML(minutaData.observaciones);
            }

            // Establecer valores iniciales en los inputs hidden
            document.getElementById('asistentes-input').value = quillAsistentes.root.innerHTML;
            document.getElementById('puntos-input').value = quillPuntos.root.innerHTML;
            document.getElementById('observaciones-input').value = quillObservaciones.root.innerHTML;

            // Actualizar inputs hidden cuando cambie el contenido
            quillAsistentes.on('text-change', function() {
                document.getElementById('asistentes-input').value = quillAsistentes.root.innerHTML;
            });
            quillPuntos.on('text-change', function() {
                document.getElementById('puntos-input').value = quillPuntos.root.innerHTML;
            });
            quillObservaciones.on('text-change', function() {
                document.getElementById('observaciones-input').value = quillObservaciones.root.innerHTML;
            });

            // Sincronizar contenido con inputs hidden antes de enviar el formulario
            const form = document.querySelector('form');
            form.addEventListener('submit', function() {
                document.getElementById('asistentes-input').value = quillAsistentes.root.innerHTML;
                document.getElementById('puntos-input').value = quillPuntos.root.innerHTML;
                document.getElementById('observaciones-input').value = quillObservaciones.root.innerHTML;
            });
        });
    </script>
</x-app-layout>
