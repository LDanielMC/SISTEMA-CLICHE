<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🆕 {{ __('Registrar Nuevo Formulario (Brief)') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ mode: 'create' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Mensajes de Error -->
            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6 flex justify-between items-center">
                        <a href="{{ route('briefs.index') }}" class="text-blue-500 hover:text-blue-700">
                            ← Volver al listado
                        </a>
                    </div>

                    <!-- Selector de Modo -->
                    <div class="flex justify-center mb-8 border-b">
                        <button @click="mode = 'create'" 
                                :class="mode === 'create' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-500 hover:text-gray-700'"
                                class="px-6 py-2 font-semibold transition duration-150">
                            ✨ Crear Nuevo Formulario
                        </button>
                        <button @click="mode = 'link'" 
                                :class="mode === 'link' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-500 hover:text-gray-700'"
                                class="px-6 py-2 font-semibold transition duration-150">
                            🔗 Vincular Existente
                        </button>
                    </div>

                    <!-- MODO CREAR NUEVO -->
                    <div x-show="mode === 'create'" x-transition>
                        <form action="{{ route('briefs.store') }}" method="POST" class="max-w-4xl mx-auto">
                            @csrf
                            <input type="hidden" name="action_type" value="create">
                            
                            <!-- Datos Básicos -->
                            <div class="bg-gray-50 p-4 rounded-lg mb-6 border border-gray-200">
                                <h4 class="font-bold text-gray-700 mb-4 border-b pb-2">1. Información del Formulario</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="mb-4">
                                        <label for="titulo" class="block text-gray-700 text-sm font-bold mb-2">
                                            Título del Formulario <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="titulo" id="titulo" 
                                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                               placeholder="Ej: Brief de Diseño Web"
                                               value="{{ old('titulo') }}">
                                    </div>

                                    <div class="mb-4">
                                        <label for="id_cliente_create" class="block text-gray-700 text-sm font-bold mb-2">
                                            Asignar a Cliente (Opcional)
                                        </label>
                                        <select name="id_cliente" id="id_cliente_create" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                            <option value="">-- Sin asignar por ahora --</option>
                                            @foreach($clientes as $cliente)
                                                <option value="{{ $cliente->id_cliente }}" {{ old('id_cliente') == $cliente->id_cliente ? 'selected' : '' }}>
                                                    {{ $cliente->empresa ? $cliente->empresa . ' (' . $cliente->nombre . ' ' . $cliente->apellido_paterno . ')' : $cliente->nombre . ' ' . $cliente->apellido_paterno }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <p class="text-xs text-gray-500 mt-1">Si seleccionas un cliente, se le enviará un correo con el link inmediatamente.</p>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="descripcion" class="block text-gray-700 text-sm font-bold mb-2">
                                        Descripción / Instrucciones
                                    </label>
                                    <textarea name="descripcion" id="descripcion" rows="2"
                                              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                              placeholder="Instrucciones para el cliente...">{{ old('descripcion') }}</textarea>
                                </div>
                            </div>

                            <!-- Constructor de Preguntas -->
                            <div class="bg-white p-4 rounded-lg mb-6 border-2 border-dashed border-gray-300" x-data="questionBuilder()">
                                <h4 class="font-bold text-gray-700 mb-4 flex justify-between items-center">
                                    <span>2. Preguntas del Cuestionario</span>
                                    <button type="button" @click="addQuestion()" class="text-sm bg-blue-100 text-blue-700 px-3 py-1 rounded hover:bg-blue-200">
                                        + Agregar Pregunta
                                    </button>
                                </h4>

                                <template x-if="questions.length === 0">
                                    <div class="text-center py-8 text-gray-400">
                                        No has agregado preguntas. Se creará un formulario vacío.
                                    </div>
                                </template>

                                <div class="space-y-4">
                                    <template x-for="(q, index) in questions" :key="q.id">
                                        <div class="bg-gray-50 p-4 rounded border border-gray-200 relative">
                                            <!-- Botón Eliminar -->
                                            <button type="button" @click="removeQuestion(index)" class="absolute top-2 right-2 text-red-400 hover:text-red-600">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>

                                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                                <!-- Título de la pregunta -->
                                                <div class="md:col-span-8">
                                                    <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Pregunta</label>
                                                    <input type="text" :name="`questions[${index}][title]`" x-model="q.title" required
                                                           class="w-full text-sm border-gray-300 rounded shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                                           placeholder="¿Qué colores prefiere?">
                                                </div>

                                                <!-- Tipo de respuesta -->
                                                <div class="md:col-span-4">
                                                    <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Tipo de Respuesta</label>
                                                    <select :name="`questions[${index}][type]`" x-model="q.type" 
                                                            class="w-full text-sm border-gray-300 rounded shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                        <option value="text">Texto Corto</option>
                                                        <option value="paragraph">Párrafo Largo</option>
                                                        <option value="choice">Opción Múltiple</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Opciones (solo para Choice) -->
                                            <div x-show="q.type === 'choice'" class="mt-3 pl-4 border-l-2 border-blue-200">
                                                <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Opciones</label>
                                                <template x-for="(opt, optIndex) in q.options" :key="optIndex">
                                                    <div class="flex items-center mb-2">
                                                        <span class="text-gray-400 mr-2">○</span>
                                                        <input type="text" :name="`questions[${index}][options][]`" x-model="q.options[optIndex]" 
                                                               class="flex-1 text-sm border-gray-300 rounded-sm shadow-sm p-1" placeholder="Opción">
                                                        <button type="button" @click="q.options.splice(optIndex, 1)" class="ml-2 text-gray-400 hover:text-red-500">×</button>
                                                    </div>
                                                </template>
                                                <button type="button" @click="q.options.push('')" class="text-xs text-blue-600 hover:underline mt-1">+ Añadir opción</button>
                                            </div>

                                            <!-- Obligatorio -->
                                            <div class="mt-3 flex items-center">
                                                <input type="checkbox" :name="`questions[${index}][required]`" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                <span class="ml-2 text-sm text-gray-600">Respuesta obligatoria</span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <div class="flex items-center justify-end">
                                <button type="submit" class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-6 rounded shadow-lg transform hover:scale-105 transition duration-150">
                                    🚀 Crear y Asignar Formulario
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- MODO VINCULAR EXISTENTE -->
                    <div x-show="mode === 'link'" x-transition style="display: none;">
                        <form action="{{ route('briefs.store') }}" method="POST" class="max-w-2xl mx-auto">
                            @csrf
                            <input type="hidden" name="action_type" value="link">
                            
                            <div class="mb-6">
                                <label for="google_form_url" class="block text-gray-700 text-sm font-bold mb-2">
                                    Enlace del Google Form (Edición o Vista)
                                </label>
                                <input type="url" name="google_form_url" id="google_form_url" 
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                       placeholder="Ej: https://docs.google.com/forms/d/1FAIpQLSc.../viewform"
                                       value="{{ old('google_form_url') }}">
                                <p class="text-gray-600 text-xs italic mt-2">
                                    Pega el enlace completo de tu formulario de Google. El sistema extraerá automáticamente el ID.
                                </p>
                            </div>

                            <div class="mb-6">
                                <label for="id_cliente_link" class="block text-gray-700 text-sm font-bold mb-2">
                                    Asignar a Cliente (Opcional)
                                </label>
                                <select name="id_cliente" id="id_cliente_link" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <option value="">-- Sin asignar por ahora --</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id_cliente }}" {{ old('id_cliente') == $cliente->id_cliente ? 'selected' : '' }}>
                                            {{ $cliente->empresa ? $cliente->empresa . ' (' . $cliente->nombre . ' ' . $cliente->apellido_paterno . ')' : $cliente->nombre . ' ' . $cliente->apellido_paterno }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex items-center justify-end">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                                    🔗 Sincronizar y Registrar
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function questionBuilder() {
            return {
                questions: [],
                
                addQuestion() {
                    this.questions.push({
                        id: Date.now(),
                        title: '',
                        type: 'text',
                        required: false,
                        options: ['Opción 1']
                    });
                },

                removeQuestion(index) {
                    this.questions.splice(index, 1);
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
