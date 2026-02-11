<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Registrar cliente
        </h2>
    </x-slot>

    <script>
        window.__clienteFiscales = @json(array_values(old('fiscales', [])));
        window.__fiscalErrors = @json($errors->get('fiscales.*'));
    </script>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                
                {{-- INICIO DEL FORMULARIO --}}
                {{-- Usamos x-data de Alpine.js para controlar múltiples info fiscales --}}
                <form action="{{ route('clientes.store') }}" method="POST" 
                      @submit.prevent="validateAndSubmit($event)"
                      x-data="{ 
                          showFiscal: {{ $errors->has('fiscales.*') || old('fiscales') ? 'true' : 'false' }},
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
                                  // RFC duplicado
                                  if (fiscal.rfc && fiscal.rfc.trim() !== '') {
                                      let duplicado = this.fiscales.findIndex((f, j) => j !== i && f.rfc && f.rfc.trim().toUpperCase() === fiscal.rfc.trim().toUpperCase());
                                      if (duplicado !== -1) {
                                          this.fiscalErrors['fiscales.' + i + '.rfc'] = ['El RFC debe ser único en el formulario.'];
                                          valid = false;
                                      }
                                  }
                                  
                                  // Correo fiscal duplicado
                                  if (fiscal.correo_fiscal && fiscal.correo_fiscal.trim() !== '') {
                                      let duplicado = this.fiscales.findIndex((f, j) => j !== i && f.correo_fiscal && f.correo_fiscal.trim().toLowerCase() === fiscal.correo_fiscal.trim().toLowerCase());
                                      if (duplicado !== -1) {
                                          this.fiscalErrors['fiscales.' + i + '.correo_fiscal'] = ['El correo fiscal debe ser único en el formulario.'];
                                          valid = false;
                                      }
                                  }
                                  
                                  // Teléfono fiscal: exactamente 10 dígitos
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
                          
                          agregarFiscal() {
                              this.fiscales.push({
                                  rfc: '',
                                  razon_social: '',
                                  regimen: '',
                                  correo_fiscal: '',
                                  telefono_fiscal: '',
                                  direccion_fiscal: ''
                              });
                          },
                          
                          eliminarFiscal(index) {
                              this.fiscales.splice(index, 1);
                              this.validateFiscales();
                          }
                      }">
                    @csrf

                    {{-- ================= SECCIÓN 1: DATOS GENERALES ================= --}}
                    <div class="grid grid-cols-1 gap-y-4">
                        
                        {{-- Nombre --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input type="text" name="nombre" value="{{ old('nombre') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            @error('nombre') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Apellidos (Grid de 2 columnas para ahorrar espacio visual) --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Apellido paterno</label>
                                <input type="text" name="apellido_paterno" value="{{ old('apellido_paterno') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                @error('apellido_paterno') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Apellido materno</label>
                                <input type="text" name="apellido_materno" value="{{ old('apellido_materno') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @error('apellido_materno') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Correo y Teléfono --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Correo electrónico (login)</label>
                                <input type="email" name="correo" value="{{ old('correo') }}"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                @error('correo') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                                <input type="text" name="telefono" value="{{ old('telefono') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                       inputmode="numeric" maxlength="10" pattern="[0-9]{10}"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                       placeholder="10 dígitos"
                                       required>
                                @error('telefono') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Empresa y Giro --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Empresa</label>
                                <input type="text" name="empresa" value="{{ old('empresa') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                @error('empresa') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Giro / Sector</label>
                                <input type="text" name="giro_sector" value="{{ old('giro_sector') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                @error('giro_sector') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Fecha ingreso --}}
                        <div>
                            <label for="fecha_registro" class="block text-sm font-medium text-gray-700">Fecha de ingreso</label>
                            <input type="date" id="fecha_registro" name="fecha_registro" value="{{ old('fecha_registro', now()->format('Y-m-d')) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100" readonly required>
                            @error('fecha_registro') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
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

                        {{-- Iteración sobre cada info fiscal --}}
                        <template x-for="(fiscal, index) in fiscales" :key="index">
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 relative">
                                {{-- Header con número y botón eliminar --}}
                                <div class="flex justify-between items-center mb-4 border-b pb-2">
                                    <h3 class="text-lg font-medium text-gray-900">
                                        Datos Fiscales #<span x-text="index + 1"></span>
                                    </h3>
                                    <button type="button" @click="eliminarFiscal(index)" 
                                            class="text-red-600 hover:text-red-800 text-sm font-medium">
                                        🗑️ Eliminar
                                    </button>
                                </div>
                                
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
                                               placeholder="Ej: 601 - General de Ley Personas Morales"
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
                                               placeholder="Calle, Número, Colonia, CP, Municipio, Estado"
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

                    {{-- BOTONES DE ACCIÓN --}}
                    <div class="flex justify-end gap-3 mt-6">
                        <a href="{{ route('clientes.index') }}" 
                           class="px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Guardar cliente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>