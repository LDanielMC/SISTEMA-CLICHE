<?php

namespace App\Services;

use Google\Client;
use Google\Service\Forms;
use Google\Service\Forms\Form;
use Illuminate\Support\Facades\Log;

class GoogleFormsService
{
    private $client;
    private $service;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setApplicationName(config('app.name'));
        $this->client->setScopes(config('google-calendar.scopes')); // Usamos los mismos scopes configurados
        $this->client->setAuthConfig([
            'client_id' => config('google-calendar.client_id'),
            'client_secret' => config('google-calendar.client_secret'),
            'redirect_uris' => [config('google-calendar.redirect_uri')],
        ]);
        $this->client->setAccessType('offline');
        
        $this->loadToken();
    }

    private function loadToken()
    {
        $tokenPath = config('google-calendar.token_path');
        
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $this->client->setAccessToken($accessToken);

            if ($this->client->isAccessTokenExpired()) {
                if ($this->client->getRefreshToken()) {
                    $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
                    file_put_contents($tokenPath, json_encode($this->client->getAccessToken()));
                }
            }
        }
    }

    public function isAuthenticated()
    {
        return $this->client->getAccessToken() && !$this->client->isAccessTokenExpired();
    }

    private function getService()
    {
        if (!$this->service) {
            $this->service = new Forms($this->client);
        }
        return $this->service;
    }

    public function getFormDetails($formId)
    {
        if (!$this->isAuthenticated()) {
            throw new \Exception('La aplicación no está autenticada con Google.');
        }

        try {
            $service = $this->getService();
            return $service->forms->get($formId);
        } catch (\Exception $e) {
            Log::error('Error al obtener formulario de Google Forms: ' . $e->getMessage());
            throw $e; // Re-lanzar para manejar en el controlador
        }
    }

    public function getFormResponses($formId)
    {
        if (!$this->isAuthenticated()) {
            return null;
        }

        try {
            $service = $this->getService();
            return $service->forms_responses->listFormsResponses($formId);
        } catch (\Exception $e) {
            Log::error('Error al obtener respuestas de Google Forms: ' . $e->getMessage());
            return null;
        }
    }

    public function createForm($titulo, $descripcion = '', $questions = [])
    {
        if (!$this->isAuthenticated()) {
            throw new \Exception('Aplicación no autenticada con Google.');
        }

        try {
            $service = $this->getService();
            
            // 1. Crear el formulario vacío
            $form = new Form();
            $info = new \Google\Service\Forms\Info();
            $info->setTitle($titulo);
            $form->setInfo($info);
            
            $createdForm = $service->forms->create($form);
            $formId = $createdForm->getFormId();

            // Preparar batchUpdate para descripción y preguntas
            $requests = [];

            // 2. Actualizar descripción si existe
            if (!empty($descripcion)) {
                $updateItem = new \Google\Service\Forms\Request();
                $updateInfo = new \Google\Service\Forms\UpdateFormInfoRequest();
                $infoToUpdate = new \Google\Service\Forms\Info();
                $infoToUpdate->setDescription($descripcion);
                
                $updateInfo->setInfo($infoToUpdate);
                $updateInfo->setUpdateMask('description');
                
                $updateItem->setUpdateFormInfo($updateInfo);
                $requests[] = $updateItem;
            }

            // 3. Agregar preguntas si existen
            if (!empty($questions)) {
                foreach ($questions as $index => $q) {
                    $createItemRequest = new \Google\Service\Forms\CreateItemRequest();
                    $item = new \Google\Service\Forms\Item();
                    $item->setTitle($q['title']);
                    // $item->setDescription($q['description'] ?? ''); // Opcional si quisieras

                    $questionItem = new \Google\Service\Forms\QuestionItem();
                    $question = new \Google\Service\Forms\Question();
                    $question->setRequired(isset($q['required']) ? (bool)$q['required'] : false);

                    // Configurar tipo de pregunta
                    $type = $q['type'] ?? 'text'; // text, paragraph, choice
                    
                    if ($type === 'text') {
                        $textQuestion = new \Google\Service\Forms\TextQuestion();
                        $textQuestion->setParagraph(false); // False = Short answer
                        $question->setTextQuestion($textQuestion);
                    } elseif ($type === 'paragraph') {
                        $textQuestion = new \Google\Service\Forms\TextQuestion();
                        $textQuestion->setParagraph(true); // True = Paragraph
                        $question->setTextQuestion($textQuestion);
                    } elseif ($type === 'choice') {
                        $choiceQuestion = new \Google\Service\Forms\ChoiceQuestion();
                        $choiceQuestion->setType('RADIO'); // Radio buttons
                        
                        $options = [];
                        if (isset($q['options']) && is_array($q['options'])) {
                            foreach ($q['options'] as $optText) {
                                if (trim($optText) !== '') {
                                    $option = new \Google\Service\Forms\Option();
                                    $option->setValue($optText);
                                    $options[] = $option;
                                }
                            }
                        }
                        // Si no hay opciones válidas, agregamos una por defecto para evitar error
                        if (empty($options)) {
                            $option = new \Google\Service\Forms\Option();
                            $option->setValue('Opción 1');
                            $options[] = $option;
                        }
                        
                        $choiceQuestion->setOptions($options);
                        $question->setChoiceQuestion($choiceQuestion);
                    }

                    $questionItem->setQuestion($question);
                    $item->setQuestionItem($questionItem);

                    $createItemRequest->setItem($item);

                    $location = new \Google\Service\Forms\Location();
                    $location->setIndex($index);
                    $createItemRequest->setLocation($location);

                    $req = new \Google\Service\Forms\Request();
                    $req->setCreateItem($createItemRequest);
                    $requests[] = $req;
                }
            }

            // Ejecutar batchUpdate si hay requests
            if (!empty($requests)) {
                $batchRequest = new \Google\Service\Forms\BatchUpdateFormRequest();
                $batchRequest->setRequests($requests);
                
                $service->forms->batchUpdate($formId, $batchRequest);
                
                // Recargar el formulario actualizado
                $createdForm = $service->forms->get($formId);
            }

            return $createdForm;

        } catch (\Exception $e) {
            Log::error('Error al crear Google Form: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Extrae el ID del formulario de una URL de Google Forms.
     * Soporta urls como:
     * https://docs.google.com/forms/d/e/1FAIpQLS.../viewform
     * https://docs.google.com/forms/d/1FAIpQLS.../edit
     */
    public function extractFormIdFromUrl($url)
    {
        // 1. Detectar si es una URL pública (viewform) que suele tener /d/e/
        // Estas URLs usan un ID público que NO SIRVE para la API.
        if (strpos($url, '/d/e/') !== false) {
            return false;
        }

        // 2. Patrón para IDs de formularios (generalmente entre /d/ y /)
        // Forzamos que tenga al menos 20 caracteres para evitar capturar segmentos cortos como "e"
        if (preg_match('/\/d\/([a-zA-Z0-9-_]{20,})/', $url, $matches)) {
            return $matches[1];
        }
        
        // 3. Si ya es un ID (cadena larga alfanumérica sin slashes)
        if (preg_match('/^[a-zA-Z0-9-_]{20,}$/', $url)) {
            return $url;
        }

        return null;
    }
}
