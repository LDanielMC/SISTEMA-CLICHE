<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ✏️ {{ __('Editar Formulario (Brief)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
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
                    <div class="mb-6">
                        <a href="{{ route('briefs.index') }}" class="text-blue-500 hover:text-blue-700">
                            ← Volver al listado
                        </a>
                    </div>

                    <form action="{{ route('briefs.update', $brief) }}" method="POST" class="max-w-2xl mx-auto">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="titulo" class="block text-gray-700 text-sm font-bold mb-2">
                                Título
                            </label>
                            <input type="text" name="titulo" id="titulo" 
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   value="{{ old('titulo', $brief->titulo) }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="descripcion" class="block text-gray-700 text-sm font-bold mb-2">
                                Descripción
                            </label>
                            <textarea name="descripcion" id="descripcion" rows="4"
                                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('descripcion', $brief->descripcion) }}</textarea>
                        </div>

                        <div class="mb-6 bg-gray-50 p-4 rounded text-sm text-gray-600">
                            <strong>Nota:</strong> Editar estos datos solo cambia la información en tu sistema local, no modifica el formulario en Google Forms.
                        </div>

                        <div class="flex items-center justify-end">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                                Actualizar Información
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
