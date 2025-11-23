<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Cliente: {{ $cliente->nombre }} {{ $cliente->apellido_paterno }}
        </h2>
    </x-slot>

    {{-- AUMENTAMOS EL ESTADO DE ALPINE: Ahora controlamos 'showFiscal' y 'showDeleteModal' --}}
    <div class="py-8" x-data="{ 
            showFiscal: {{ $errors->has('fiscal.*') || $cliente->infoFiscal ? 'true' : 'false' }},
            showDeleteModal: {{ $errors->has('password_confirm') ? 'true' : 'false' }},
            emailLocked: true,
            showEmailModal: false,
            adminPassword: '',
            passwordError: '',
            
            // Función para verificar contraseña via AJAX
            async unlockEmail() {
                this.passwordError = '';
                try {
                    // Enviamos la contraseña al controlador
                    let response = await fetch('{{ route('password.verify') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ password: this.adminPassword })
                    });

                    if (response.ok) {
                        // SI ES CORRECTA: Desbloqueamos y cerramos modal
                        this.emailLocked = false;
                        this.showEmailModal = false;
                        this.adminPassword = ''; // Limpiamos por seguridad
                    } else {
                        // SI ES INCORRECTA
                        this.passwordError = 'Contraseña incorrecta.';
                    }
                } catch (error) {
                    this.passwordError = 'Error de conexión.';
                }
            }
        }">


        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 relative">
                
                {{-- FORMULARIO PRINCIPAL (UPDATE) --}}
                <form action="{{ route('clientes.update', $cliente->id_cliente) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- ================= DATOS DEL CLIENTE (IGUAL QUE ANTES) ================= --}}
                    <div class="grid grid-cols-1 gap-y-4">
                        {{-- Nombre --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input type="text" name="nombre" value="{{ old('nombre', $cliente->nombre) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            @error('nombre') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Apellidos --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Apellido paterno</label>
                                <input type="text" name="apellido_paterno" value="{{ old('apellido_paterno', $cliente->apellido_paterno) }}"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                @error('apellido_paterno') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Apellido materno</label>
                                <input type="text" name="apellido_materno" value="{{ old('apellido_materno', $cliente->apellido_materno) }}"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @error('apellido_materno') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- ================= CORREO BLOQUEADO ================= --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">
                                Correo electrónico (login)
                            </label>
                            
                            <div class="relative rounded-md shadow-sm">
                                {{-- INPUT: El atributo :readonly depende de la variable 'emailLocked' --}}
                                {{-- La clase dinámica cambia el fondo: gris si está bloqueado, blanco si no --}}
                                <input type="email" name="correo" 
                                       value="{{ old('correo', $cliente->user->email ?? '') }}"
                                       :readonly="emailLocked"
                                       :class="emailLocked ? 'bg-gray-100 text-gray-500 cursor-not-allowed' : 'bg-white text-gray-900'"
                                       class="block w-full border-gray-300 rounded-md pr-10 focus:ring-indigo-500 focus:border-indigo-500"
                                       required>
                                
                                {{-- ÍCONO DE CANDADO: Al hacer click abre el modal --}}
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
                                <span class="font-bold">Protegido.</span> Haz clic en el candado para editar.
                            </p>
                            <p x-show="!emailLocked" class="text-xs text-green-600 mt-1 font-bold">
                                ¡Edición habilitada! Recuerda guardar los cambios abajo.
                            </p>
                            @error('correo') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Teléfono --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                            <input type="text" name="telefono" value="{{ old('telefono', $cliente->telefono) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            @error('telefono') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Empresa y Giro --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Empresa</label>
                                <input type="text" name="empresa" value="{{ old('empresa', $cliente->empresa) }}"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                @error('empresa') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Giro / Sector</label>
                                <input type="text" name="giro_sector" value="{{ old('giro_sector', $cliente->giro_sector) }}"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                @error('giro_sector') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- ================= SECCIÓN FISCAL ================= --}}
                    <div class="my-8 border-t border-gray-200 pt-4 flex justify-between items-center">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" x-model="showFiscal" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                            <span class="ml-2 text-gray-800 font-semibold">
                                {{ $cliente->infoFiscal ? 'Editar Información Fiscal' : 'Agregar Información Fiscal' }}
                            </span>
                        </label>

                        {{-- BOTÓN ROJO DE ELIMINAR (Solo aparece si hay info fiscal guardada) --}}
                        @if($cliente->infoFiscal)
                            <button type="button" 
                                    @click="showDeleteModal = true"
                                    class="text-red-600 hover:text-red-800 text-sm font-medium underline">
                                Eliminar Datos Fiscales
                            </button>
                        @endif
                    </div>

                    <div x-show="showFiscal" x-transition class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Datos de Facturación</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- RFC --}}
                            {{-- NOTA EN VALUE: Usamos $cliente->infoFiscal->rfc ?? '' para evitar error si no existe --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">RFC</label>
                                <input type="text" name="fiscal[rfc]" 
                                       value="{{ old('fiscal.rfc', $cliente->infoFiscal->rfc ?? '') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm uppercase">
                                @error('fiscal.rfc') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Razón Social --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Razón Social</label>
                                <input type="text" name="fiscal[razon_social]" 
                                       value="{{ old('fiscal.razon_social', $cliente->infoFiscal->razon_social ?? '') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm uppercase">
                                @error('fiscal.razon_social') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Régimen --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Régimen Fiscal</label>
                                <input type="text" name="fiscal[regimen]" 
                                       value="{{ old('fiscal.regimen', $cliente->infoFiscal->regimen ?? '') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @error('fiscal.regimen') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                             {{-- Correo Fiscal --}}
                             <div>
                                <label class="block text-sm font-medium text-gray-700">Correo Facturación</label>
                                <input type="email" name="fiscal[correo_fiscal]" 
                                       value="{{ old('fiscal.correo_fiscal', $cliente->infoFiscal->correo_fiscal ?? '') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @error('fiscal.correo_fiscal') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Teléfono Fiscal --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Teléfono Fiscal</label>
                                <input type="text" name="fiscal[telefono_fiscal]" 
                                       value="{{ old('fiscal.telefono_fiscal', $cliente->infoFiscal->telefono_fiscal ?? '') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @error('fiscal.telefono_fiscal') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Dirección Fiscal --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Dirección Fiscal Completa</label>
                                <input type="text" name="fiscal[direccion_fiscal]" 
                                       value="{{ old('fiscal.direccion_fiscal', $cliente->infoFiscal->direccion_fiscal ?? '') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @error('fiscal.direccion_fiscal') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- ================= BOTONES ================= --}}
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('clientes.index') }}" 
                           class="px-4 py-2 border rounded-md text-sm text-gray-700 hover:bg-gray-50">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ================= MODAL PARA DESBLOQUEAR CORREO ================= --}}
        <div x-show="showEmailModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showEmailModal" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75" @click="showEmailModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <div x-show="showEmailModal" x-transition.scale class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Desbloquear edición de correo
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 mb-4">
                                Esta es una acción sensible. Ingresa tu contraseña de administrador para continuar.
                            </p>
                            <input type="password" x-model="adminPassword" 
                                   @keydown.enter.prevent="unlockEmail()"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" 
                                   placeholder="Contraseña de Admin">
                            
                            {{-- Mensaje de error dinámico (JS) --}}
                            <p x-show="passwordError" x-text="passwordError" class="text-sm text-red-600 mt-2 font-bold"></p>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" @click="unlockEmail()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm">
                            Confirmar
                        </button>
                        <button type="button" @click="showEmailModal = false; passwordError = ''" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>


        {{-- ================= MODAL DE CONFIRMACIÓN (FUERA DEL FORM PRINCIPAL) ================= --}}
        {{-- Usamos x-show para mostrar/ocultar. Fixed para que cubra la pantalla --}}
        <div x-show="showDeleteModal" 
             style="display: none;" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             aria-labelledby="modal-title" role="dialog" aria-modal="true">
            
            {{-- Fondo oscuro --}}
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showDeleteModal" 
                     x-transition.opacity
                     class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                     @click="showDeleteModal = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                {{-- Contenido del Modal --}}
                <div x-show="showDeleteModal" 
                     x-transition.scale
                     class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    
                    {{-- FORMULARIO DE BORRADO --}}
                    <form action="{{ route('clientes.destroyFiscal', $cliente->id_cliente) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                    {{-- Icono de alerta --}}
                                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                        Eliminar Información Fiscal
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500 mb-4">
                                            ¿Estás seguro? Esta acción eliminará el RFC y datos de facturación de este cliente.
                                        </p>
                                        
                                        <label class="block text-sm font-medium text-gray-700 text-left">
                                            Ingresa tu contraseña de Administrador para confirmar:
                                        </label>
                                        <input type="password" 
                                               name="password_confirm" 
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500" 
                                               placeholder="Tu contraseña"
                                               required>
                                        @error('password_confirm') 
                                            <p class="text-sm text-red-600 mt-1 text-left">{{ $message }}</p> 
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                Confirmar y Eliminar
                            </button>
                            <button type="button" 
                                    @click="showDeleteModal = false"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>