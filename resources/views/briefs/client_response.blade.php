<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📄 {{ __('Respuestas del Cliente') }}: {{ $cliente->empresa ?: $cliente->nombre . ' ' . $cliente->apellido_paterno }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6 flex justify-between items-center">
                <a href="{{ route('briefs.assign', $brief->id) }}" class="text-blue-500 hover:text-blue-700">
                    ← Volver a Asignaciones
                </a>
                <span class="text-gray-500 text-sm">Brief: {{ $brief->titulo }}</span>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4 border-b pb-2">Respuestas Recibidas ({{ count($clientResponses) }})</h3>

                    @if(empty($clientResponses) && empty($potentialMatches))
                        <div class="text-center py-12 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                            <p class="text-gray-500 italic mb-2">No se encontraron respuestas para este cliente.</p>
                            <p class="text-sm text-gray-400">Verificamos el correo: <strong>{{ $cliente->user ? $cliente->user->email : 'Sin correo registrado' }}</strong></p>
                            <p class="text-xs text-gray-400 mt-2">Tampoco hay respuestas anónimas recientes que coincidan con la fecha de asignación.</p>
                        </div>
                    @else
                        
                        <!-- Respuestas Verificadas / Vinculadas -->
                        @if(!empty($clientResponses))
                            <div class="space-y-6 mb-8">
                                <h4 class="text-md font-bold {{ isset($isLinked) && $isLinked ? 'text-blue-700 bg-blue-50 border-blue-200' : 'text-green-700 bg-green-50 border-green-200' }} p-2 rounded border inline-block mb-2">
                                    {{ isset($isLinked) && $isLinked ? '🔗 Respuesta Vinculada Manualmente' : '✅ Respuestas Verificadas (Email coincidente)' }}
                                </h4>
                                @foreach($clientResponses as $index => $response)
                                    @include('briefs.partials.response_card', ['response' => $response, 'index' => $index + 1, 'verified' => true, 'isLinked' => $isLinked ?? false])
                                    
                                    @if(isset($isLinked) && $isLinked)
                                        <div class="mt-2 text-right">
                                            <form action="{{ route('briefs.unlink_response', [$brief->id, $cliente->id_cliente]) }}" method="POST" onsubmit="return confirm('¿Desvincular esta respuesta? Volverás a ver las coincidencias automáticas.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm text-red-500 hover:text-red-700 underline">Desvincular respuesta</button>
                                            </form>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif

                        <!-- Respuestas Potenciales (Solo si no hay verificadas o si se quiere mostrar todo) -->
                        @if(!empty($potentialMatches))
                            <div class="space-y-6">
                                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-yellow-700">
                                                <strong>Atención:</strong> Las siguientes respuestas coinciden con la fecha pero no con el email. 
                                                Si el cliente respondió desde otra cuenta, puedes <strong>Vincularla</strong> manualmente.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                @foreach($potentialMatches as $index => $response)
                                    <div>
                                        @include('briefs.partials.response_card', ['response' => $response, 'index' => $index + 1, 'verified' => false])
                                        <div class="mt-2 text-right">
                                            <form action="{{ route('briefs.link_response', [$brief->id, $cliente->id_cliente]) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="response_id" value="{{ $response->getResponseId() }}">
                                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold py-1 px-3 rounded shadow-sm">
                                                    🔗 Vincular esta respuesta a este cliente
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
