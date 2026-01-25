<div class="border rounded-lg p-4 bg-gray-50 {{ isset($verified) && $verified ? 'border-green-200 bg-green-50' : 'border-yellow-200 bg-yellow-50' }}">
    <div class="flex justify-between items-center mb-3">
        <h4 class="font-semibold text-gray-700">Respuesta #{{ $index }} {{ isset($verified) && $verified ? '✅' : '⚠️' }}</h4>
        <div class="text-right">
            <span class="block text-xs text-gray-500">
                Fecha: {{ \Carbon\Carbon::parse($response->getCreateTime())->format('d/m/Y H:i') }}
            </span>
            @if($response->getRespondentEmail())
                <span class="block text-xs text-gray-400">Email: {{ $response->getRespondentEmail() }}</span>
            @endif
        </div>
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
                    if (isset($formDetails) && $formDetails) {
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
