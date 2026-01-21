<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📊 {{ __('Detalles del Brief') }}: {{ $brief->titulo }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="{{ route('briefs.index') }}" class="text-blue-500 hover:text-blue-700">
                    ← Volver al listado
                </a>
            </div>

            @if($error)
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4" role="alert">
                    <p class="font-bold">Aviso de Sincronización</p>
                    <p>{{ $error }}</p>
                    <p class="text-sm mt-2">Mostrando información local guardada.</p>
                </div>
            @endif

            <!-- Información General -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-2xl font-bold mb-2">{{ $brief->titulo }}</h3>
                            <p class="text-gray-600 mb-4">{{ $brief->descripcion }}</p>
                            @if($brief->form_url)
                                <a href="{{ $brief->form_url }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Ir al Formulario en Google ↗
                                </a>
                            @endif
                        </div>
                        <div class="text-right text-sm text-gray-500">
                            <p>ID: {{ $brief->google_form_id }}</p>
                            <p>Registrado: {{ $brief->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Respuestas -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4 border-b pb-2">Respuestas Recibidas ({{ is_countable($responses) ? count($responses) : 0 }})</h3>

                    @if(empty($responses))
                        <p class="text-gray-500 italic">No hay respuestas registradas aún en Google Forms.</p>
                    @else
                        <div class="space-y-6">
                            @foreach($responses as $index => $response)
                                <div class="border rounded-lg p-4 bg-gray-50">
                                    <div class="flex justify-between items-center mb-3">
                                        <h4 class="font-semibold text-gray-700">Respuesta #{{ $index + 1 }}</h4>
                                        <span class="text-xs text-gray-500">
                                            Fecha: {{ \Carbon\Carbon::parse($response->getCreateTime())->format('d/m/Y H:i') }}
                                        </span>
                                    </div>
                                    
                                    <div class="space-y-3">
                                        @php
                                            $answers = $response->getAnswers();
                                        @endphp
                                        
                                        @if($answers)
                                            @foreach($answers as $questionId => $answer)
                                                @php
                                                    // Intentar encontrar el título de la pregunta usando el formDetails si está disponible
                                                    $questionTitle = 'Pregunta ' . $questionId;
                                                    if ($formDetails) {
                                                        foreach ($formDetails->getItems() as $item) {
                                                            $questionItem = $item->getQuestionItem();
                                                            if ($questionItem && $questionItem->getQuestion()->getQuestionId() == $questionId) {
                                                                $questionTitle = $item->getTitle();
                                                                break;
                                                            }
                                                        }
                                                    }
                                                    
                                                    // Obtener valor de la respuesta (texto)
                                                    $textAnswers = $answer->getTextAnswers();
                                                    $answerValue = 'Sin texto';
                                                    
                                                    if ($textAnswers && $textAnswers->getAnswers()) {
                                                        $values = [];
                                                        foreach ($textAnswers->getAnswers() as $textAnswer) {
                                                            $values[] = $textAnswer->getValue();
                                                        }
                                                        $answerValue = implode(', ', $values);
                                                    }
                                                @endphp
                                                <div class="ml-4">
                                                    <p class="text-sm font-medium text-gray-800">{{ $questionTitle }}</p>
                                                    <p class="text-sm text-gray-600 bg-white p-2 rounded border mt-1">{{ $answerValue }}</p>
                                                </div>
                                            @endforeach
                                        @else
                                            <p class="text-sm text-gray-500 italic ml-4">Respuestas vacías o formato no soportado.</p>
                                        @endif
                                    </div>
                                    
                                    <div class="mt-3 text-right">
                                        <span class="text-xs text-gray-400">ID Respuesta: {{ $response->getResponseId() }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
