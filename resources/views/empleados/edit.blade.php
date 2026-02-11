<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Empleado: {{ $empleado->nombre }}
        </h2>
    </x-slot>

    {{-- X-DATA: Controlamos el bloqueo del email y el modal --}}
    <div class="py-8" x-data="{ 
        emailLocked: true,
        showEmailModal: false,
        adminPassword: '',
        passwordError: '',

        async unlockEmail() {
            this.passwordError = '';
            try {
                // REUTILIZAMOS LA RUTA QUE YA CREASTE PARA CLIENTES
                let response = await fetch('{{ route('password.verify') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ password: this.adminPassword })
                });

                if (response.ok) {
                    this.emailLocked = false;
                    this.showEmailModal = false;
                    this.adminPassword = '';
                } else {
                    this.passwordError = 'Contraseña incorrecta.';
                }
            } catch (error) {
                this.passwordError = 'Error de conexión.';
            }
        }
    }">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                
                {{-- El formulario apunta a la ruta de actualización y usa el método PUT --}}
                <form action="{{ route('empleados.update', $empleado->id_empleado) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Nombre --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" 
                               name="nombre" 
                               value="{{ old('nombre', $empleado->nombre) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                               required>
                        @error('nombre')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Apellido paterno --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Apellido paterno</label>
                        <input type="text" 
                               name="apellido_paterno" 
                               value="{{ old('apellido_paterno', $empleado->apellido_paterno) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                               required>
                        @error('apellido_paterno')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Apellido materno --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Apellido materno</label>
                        <input type="text" 
                               name="apellido_materno" 
                               value="{{ old('apellido_materno', $empleado->apellido_materno) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('apellido_materno')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ================= CORREO (LOGIN) BLOQUEADO ================= --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">
                            Correo electrónico (Login)
                        </label>

                        <div class="relative rounded-md shadow-sm">
                            {{-- Notar que el name es 'correo_contacto' como en tu controlador --}}
                            <input type="email" 
                                   name="correo_contacto"
                                   value="{{ $errors->has('correo_contacto') ? ($empleado->user->email ?? '') : old('correo_contacto', $empleado->user->email ?? '') }}"
                                   :readonly="emailLocked"
                                   :class="emailLocked ? 'bg-gray-100 text-gray-500 cursor-not-allowed' : 'bg-white text-gray-900'"
                                   class="block w-full border-gray-300 rounded-md pr-10 focus:ring-indigo-500 focus:border-indigo-500"
                                   required>

                            {{-- Candado --}}
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer"
                                 x-show="emailLocked"
                                 @click="showEmailModal = true"
                                 title="Click para desbloquear (Requiere Admin)">
                                <svg class="h-5 w-5 text-gray-400 hover:text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                        </div>

                        <p x-show="emailLocked" class="text-xs text-gray-500 mt-1">
                            <span class="font-bold">Protegido.</span> Haz clic en el candado para cambiar el correo de acceso.
                        </p>
                        <p x-show="!emailLocked" class="text-xs text-green-600 mt-1 font-bold">
                            ¡Edición habilitada! Recuerda guardar los cambios abajo.
                        </p>
                        @error('correo_contacto')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Teléfono --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                        <input type="text" 
                               name="telefono" 
                               value="{{ old('telefono', $empleado->telefono) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                               inputmode="numeric" maxlength="10" pattern="[0-9]{10}"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                               placeholder="10 dígitos"
                               required>
                        @error('telefono')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Puesto (con autocompletado) --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Puesto</label>
                        <input type="text" 
                               name="puesto" 
                               value="{{ old('puesto', $empleado->puesto) }}"
                               list="lista-puestos"
                               placeholder="Escribe o selecciona un puesto..."
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                               required>
                        <datalist id="lista-puestos">
                            @foreach($puestos as $puesto)
                                <option value="{{ $puesto }}">
                            @endforeach
                        </datalist>
                        @error('puesto')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Botones de Acción --}}
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('empleados.index') }}" class="px-4 py-2 border rounded-md text-sm text-gray-700">
                            Cancelar
                        </a>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
            
            {{-- ================= MODAL DE SEGURIDAD ================= --}}
            <div x-show="showEmailModal" 
                 style="display: none;" 
                 class="fixed inset-0 z-50 overflow-y-auto" 
                 aria-labelledby="modal-title" 
                 role="dialog" 
                 aria-modal="true">
                 
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    
                    {{-- Fondo oscuro --}}
                    <div x-show="showEmailModal" 
                         x-transition.opacity 
                         class="fixed inset-0 bg-gray-500 bg-opacity-75" 
                         @click="showEmailModal = false">
                    </div>
                    
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                    {{-- Contenido del Modal --}}
                    <div x-show="showEmailModal" 
                         x-transition.scale 
                         class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                        
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Desbloquear edición de correo</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 mb-4">Ingresa tu contraseña de administrador para confirmar:</p>
                                
                                <input type="password" 
                                       x-model="adminPassword" 
                                       @keydown.enter.prevent="unlockEmail()" 
                                       class="w-full border-gray-300 rounded-md shadow-sm" 
                                       placeholder="Contraseña de Admin">
                                       
                                <p x-show="passwordError" x-text="passwordError" class="text-sm text-red-600 mt-2 font-bold"></p>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="button" 
                                    @click="unlockEmail()" 
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm">
                                Confirmar
                            </button>
                            <button type="button" 
                                    @click="showEmailModal = false; passwordError = ''" 
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>