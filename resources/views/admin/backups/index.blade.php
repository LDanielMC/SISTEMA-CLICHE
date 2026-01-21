<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            💾 {{ __('Administración de Respaldos') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ 
        showCreateModal: false, 
        showRestoreModal: false, 
        showDeleteModal: false,
        selectedBackup: '',
        deleteUrl: ''
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Mensajes -->
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <!-- Tarjeta de Acciones Principales -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Copias de Seguridad de la Base de Datos</h3>
                            <p class="text-sm text-gray-600 mt-1">Genera respaldos completos para prevenir pérdida de información.</p>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <button @click="showCreateModal = true" 
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                Generar Nuevo Respaldo
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Listado de Respaldos -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h4 class="font-bold text-gray-700 mb-4">Historial de Respaldos</h4>
                    
                    @if(count($backups) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full leading-normal">
                                <thead>
                                    <tr>
                                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Archivo
                                        </th>
                                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Fecha y Hora
                                        </th>
                                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Tamaño
                                        </th>
                                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($backups as $backup)
                                        <tr>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                <div class="flex items-center">
                                                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                                                    <span class="font-mono text-gray-700">{{ $backup['filename'] }}</span>
                                                </div>
                                            </td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                {{ $backup['date'] }}
                                            </td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                {{ $backup['size'] }}
                                            </td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                                                <div class="flex justify-center space-x-3">
                                                    <!-- Descargar -->
                                                    <a href="{{ route('backups.download', $backup['filename']) }}" class="text-blue-600 hover:text-blue-900" title="Descargar">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                    </a>

                                                    <!-- Botón Restaurar (Abre Modal) -->
                                                    <button @click="showRestoreModal = true; selectedBackup = '{{ $backup['filename'] }}'" 
                                                            class="text-yellow-600 hover:text-yellow-900" 
                                                            title="Restaurar Base de Datos">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                                    </button>

                                                    <!-- Botón Eliminar (Abre Modal) -->
                                                    <button @click="showDeleteModal = true; deleteUrl = '{{ route('backups.delete', $backup['filename']) }}'"
                                                            class="text-red-600 hover:text-red-900" 
                                                            title="Eliminar Archivo">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay respaldos</h3>
                            <p class="mt-1 text-sm text-gray-500">Genera tu primer respaldo para comenzar.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- MODAL 1: Generar Respaldo -->
            <div x-show="showCreateModal" style="display: none;" 
                 class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-md" @click.away="showCreateModal = false">
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 mb-4">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">Generar Nuevo Respaldo</h3>
                        <p class="mt-2 text-sm text-gray-500">Se creará un archivo SQL con el estado actual de toda la base de datos. Este proceso podría tomar unos segundos.</p>
                    </div>
                    <div class="mt-6 flex justify-center gap-3">
                        <button @click="showCreateModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">Cancelar</button>
                        <form action="{{ route('backups.create') }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">Confirmar</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- MODAL 2: Restaurar (PELIGROSO) -->
            <div x-show="showRestoreModal" style="display: none;"
                 class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm"
                 x-transition>
                <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-lg border-t-4 border-yellow-500" @click.away="showRestoreModal = false">
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 mb-4">
                            <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">⚠️ Advertencia de Restauración</h3>
                        <div class="mt-2 text-left bg-yellow-50 p-4 rounded-md border border-yellow-200">
                            <p class="text-sm text-yellow-800 font-semibold mb-2">Estás a punto de restaurar el archivo:</p>
                            <code class="block bg-white p-2 rounded border border-gray-200 text-xs text-gray-700 mb-3" x-text="selectedBackup"></code>
                            <ul class="list-disc list-inside text-sm text-yellow-800 space-y-1">
                                <li>Toda la información actual <strong>será eliminada y sobrescrita</strong>.</li>
                                <li>Los cambios realizados después de este respaldo se perderán para siempre.</li>
                                <li>Esta acción <strong>NO SE PUEDE DESHACER</strong>.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button @click="showRestoreModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">Cancelar</button>
                        <form action="{{ route('backups.restore') }}" method="POST">
                            @csrf
                            <input type="hidden" name="backup_file" :value="selectedBackup">
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-yellow-600 rounded-md hover:bg-yellow-700 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2">
                                Sí, Restaurar Base de Datos
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- MODAL 3: Eliminar -->
            <div x-show="showDeleteModal" style="display: none;"
                 class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm"
                 x-transition>
                <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-md" @click.away="showDeleteModal = false">
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">¿Eliminar Respaldo?</h3>
                        <p class="mt-2 text-sm text-gray-500">El archivo será eliminado permanentemente del servidor.</p>
                    </div>
                    <div class="mt-6 flex justify-center gap-3">
                        <button @click="showDeleteModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">Cancelar</button>
                        <form :action="deleteUrl" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">Eliminar</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
