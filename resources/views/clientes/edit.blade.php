<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Cliente: {{ $cliente->nombre }} {{ $cliente->apellido_paterno }}
        </h2>
    </x-slot>

    @php
        $fiscalesData = old('fiscales')
            ? array_values(old('fiscales'))
            : $cliente->infosFiscales->map(fn($f) => [
                'id' => $f->id_fiscal,
                'rfc' => $f->rfc ?? '',
                'razon_social' => $f->razon_social ?? '',
                'regimen' => $f->regimen ?? '',
                'correo_fiscal' => $f->correo_fiscal ?? '',
                'telefono_fiscal' => $f->telefono_fiscal ?? '',
                'direccion_fiscal' => $f->direccion_fiscal ?? '',
            ])->values()->toArray();
    @endphp

    <script>
        window.__clienteFiscales = @json($fiscalesData);
        window.__fiscalErrors = @json($errors->get('fiscales.*'));
    </script>

    {{-- AUMENTAMOS EL ESTADO DE ALPINE: Controlamos info fiscales múltiples --}}
    <div class="py-8" x-data="{ 
            showFiscal: {{ $errors->has('fiscales.*') || old('fiscales') || $cliente->infosFiscales->count() > 0 ? 'true' : 'false' }},
            showDeleteModal: false,
            fiscalToDelete: null,
            emailLocked: true,
            showEmailModal: false,
            adminPassword: '',
            passwordError: '',
            
            // Array de info fiscales
            fiscales: window.__clienteFiscales,
            
            fiscalErrors: window.__fiscalErrors,
            
            getError(index, field) {
                let key = 'fiscales.' + index + '.' + field;
                return this.fiscalErrors[key] ? this.fiscalErrors[key][0] : '';
            },
            
            validateFiscales() {
                this.fiscalErrors = {};
                let valid = true;
                
                this.fiscales.forEach((fiscal, i) => {
                    if (fiscal.rfc && fiscal.rfc.trim() !== '') {
                        let duplicado = this.fiscales.findIndex((f, j) => j !== i && f.rfc && f.rfc.trim().toUpperCase() === fiscal.rfc.trim().toUpperCase());
                        if (duplicado !== -1) {
                            this.fiscalErrors['fiscales.' + i + '.rfc'] = ['El RFC debe ser único en el formulario.'];
                            valid = false;
                        }
                    }
                    if (fiscal.correo_fiscal && fiscal.correo_fiscal.trim() !== '') {
                        let duplicado = this.fiscales.findIndex((f, j) => j !== i && f.correo_fiscal && f.correo_fiscal.trim().toLowerCase() === fiscal.correo_fiscal.trim().toLowerCase());
                        if (duplicado !== -1) {
                            this.fiscalErrors['fiscales.' + i + '.correo_fiscal'] = ['El correo fiscal debe ser único en el formulario.'];
                            valid = false;
                        }
                    }
                    if (fiscal.telefono_fiscal && fiscal.telefono_fiscal.trim() !== '') {
                        if (!/^[0-9]{10}$/.test(fiscal.telefono_fiscal.trim())) {
                            this.fiscalErrors['fiscales.' + i + '.telefono_fiscal'] = ['El teléfono fiscal debe contener exactamente 10 dígitos numéricos.'];
                            valid = false;
                        }
                    }
                });
                
                return valid;
            },
            
            validateAndSubmit(event) {
                if (this.fiscales.length > 0 && !this.validateFiscales()) {
                    this.showFiscal = true;
                    this.$nextTick(() => {
                        let firstError = document.querySelector('[data-fiscal-error]');
                        if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    });
                    return;
                }
                event.target.submit();
            },
            
            // Agregar nueva info fiscal vacía
            agregarFiscal() {
                this.fiscales.push({
                    id: null,
                    rfc: '',
                    razon_social: '',
                    regimen: '',
                    correo_fiscal: '',
                    telefono_fiscal: '',
                    direccion_fiscal: ''
                });
            },
            
            // Eliminar info fiscal del array
            eliminarFiscal(index) {
                this.fiscales.splice(index, 1);
                this.validateFiscales();
            },
            
            // Función para verificar contraseña via AJAX
            async unlockEmail() {
                this.passwordError = '';
                try {
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
            <div class="bg-white shadow-sm sm:rounded-lg p-6 relative">
                
                {{-- FORMULARIO PRINCIPAL (UPDATE) --}}
                <form action="{{ route('clientes.update', $cliente->id_cliente) }}" method="POST" @submit.prevent="validateAndSubmit($event)">
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
                                       value="{{ $errors->has('correo') ? ($cliente->user->email ?? '') : old('correo', $cliente->user->email ?? '') }}"
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
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                   inputmode="numeric" maxlength="10" pattern="[0-9]{10}"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                   placeholder="10 dígitos"
                                   required>
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

                    {{-- ================= SECCIÓN FISCAL (MÚLTIPLES) ================= --}}
                    <div class="my-8 border-t border-gray-200 pt-4 flex justify-between items-center">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" x-model="showFiscal" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                            <span class="ml-2 text-gray-800 font-semibold">
                                Información Fiscal (<span x-text="fiscales.length"></span>)
                            </span>
                        </label>

                        <button type="button" 
                                @click="showFiscal = true; agregarFiscal()"
                                class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                            + Agregar Datos Fiscales
                        </button>
                    </div>

                    <div x-show="showFiscal" x-transition class="space-y-4 mb-6">
                        {{-- Mensaje si no hay info fiscal --}}
                        <template x-if="fiscales.length === 0">
                            <div class="bg-gray-50 p-4 rounded-lg border border-dashed border-gray-300 text-center">
                                <p class="text-gray-500">No hay información fiscal registrada.</p>
                                <button type="button" @click="agregarFiscal()" 
                                        class="mt-2 text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                    + Agregar primera información fiscal
                                </button>
                            </div>
                        </template>

                        {{-- Errores de validación fiscal --}}
                        @if($errors->has('fiscales.*'))
                            <div class="bg-red-50 border border-red-300 rounded-lg p-4">
                                <h4 class="text-red-800 font-semibold text-sm mb-2">⚠️ Errores en la información fiscal:</h4>
                                <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                                    @foreach(collect($errors->get('fiscales.*'))->flatten()->unique()->values() as $message)
                                        <li>{{ $message }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Iteración sobre cada info fiscal --}}
                        <template x-for="(fiscal, index) in fiscales" :key="index">
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 relative">
                                {{-- Header con número y botón eliminar --}}
                                <div class="flex justify-between items-center mb-4 border-b pb-2">
                                    <h3 class="text-lg font-medium text-gray-900">
                                        Datos Fiscales #<span x-text="index + 1"></span>
                                        <span x-show="fiscal.rfc" class="text-sm font-normal text-gray-500">
                                            (<span x-text="fiscal.rfc"></span>)
                                        </span>
                                    </h3>
                                    <button type="button" @click="eliminarFiscal(index)" 
                                            class="text-red-600 hover:text-red-800 text-sm font-medium">
                                        🗑️ Eliminar
                                    </button>
                                </div>
                                
                                {{-- Hidden field para el ID (si existe) --}}
                                <input type="hidden" :name="'fiscales[' + index + '][id]'" :value="fiscal.id">
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    {{-- RFC --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">RFC *</label>
                                        <input type="text" :name="'fiscales[' + index + '][rfc]'" 
                                               x-model="fiscal.rfc"
                                               :class="getError(index, 'rfc') ? 'border-red-500' : 'border-gray-300'"
                                               class="mt-1 block w-full rounded-md shadow-sm uppercase"
                                               required>
                                        <p x-show="getError(index, 'rfc')" x-text="getError(index, 'rfc')" class="text-sm text-red-600 mt-1" data-fiscal-error></p>
                                    </div>

                                    {{-- Razón Social --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Razón Social *</label>
                                        <input type="text" :name="'fiscales[' + index + '][razon_social]'" 
                                               x-model="fiscal.razon_social"
                                               :class="getError(index, 'razon_social') ? 'border-red-500' : 'border-gray-300'"
                                               class="mt-1 block w-full rounded-md shadow-sm uppercase"
                                               required>
                                        <p x-show="getError(index, 'razon_social')" x-text="getError(index, 'razon_social')" class="text-sm text-red-600 mt-1"></p>
                                    </div>

                                    {{-- Régimen --}}
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">Régimen Fiscal</label>
                                        <input type="text" :name="'fiscales[' + index + '][regimen]'" 
                                               x-model="fiscal.regimen"
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    </div>

                                    {{-- Correo Fiscal --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Correo Facturación</label>
                                        <input type="email" :name="'fiscales[' + index + '][correo_fiscal]'" 
                                               x-model="fiscal.correo_fiscal"
                                               :class="getError(index, 'correo_fiscal') ? 'border-red-500' : 'border-gray-300'"
                                               class="mt-1 block w-full rounded-md shadow-sm">
                                        <p x-show="getError(index, 'correo_fiscal')" x-text="getError(index, 'correo_fiscal')" class="text-sm text-red-600 mt-1" data-fiscal-error></p>
                                    </div>

                                    {{-- Teléfono Fiscal --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Teléfono Fiscal</label>
                                        <input type="text" :name="'fiscales[' + index + '][telefono_fiscal]'" 
                                               x-model="fiscal.telefono_fiscal"
                                               inputmode="numeric" maxlength="10" pattern="[0-9]{10}"
                                               oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                               placeholder="10 dígitos"
                                               :class="getError(index, 'telefono_fiscal') ? 'border-red-500' : 'border-gray-300'"
                                               class="mt-1 block w-full rounded-md shadow-sm">
                                        <p x-show="getError(index, 'telefono_fiscal')" x-text="getError(index, 'telefono_fiscal')" class="text-sm text-red-600 mt-1" data-fiscal-error></p>
                                    </div>

                                    {{-- Dirección Fiscal --}}
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">Dirección Fiscal Completa</label>
                                        <input type="text" :name="'fiscales[' + index + '][direccion_fiscal]'" 
                                               x-model="fiscal.direccion_fiscal"
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    </div>
                                </div>
                            </div>
                        </template>

                        {{-- Botón para agregar más (abajo de la lista) --}}
                        <div x-show="fiscales.length > 0" class="text-center">
                            <button type="button" @click="agregarFiscal()" 
                                    class="text-indigo-600 hover:text-indigo-800 text-sm font-medium border border-indigo-300 px-4 py-2 rounded-md hover:bg-indigo-50">
                                + Agregar otra información fiscal
                            </button>
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


    </div>
</x-app-layout>