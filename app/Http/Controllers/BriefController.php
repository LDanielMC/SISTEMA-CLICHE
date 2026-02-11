<?php

namespace App\Http\Controllers;

use App\Models\Brief;
use App\Models\Notificacion;
use App\Models\Cliente;
use App\Services\GoogleFormsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class BriefController extends Controller
{
    protected $googleFormsService;

    public function __construct(GoogleFormsService $googleFormsService)
    {
        $this->googleFormsService = $googleFormsService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $briefs = Brief::latest()->withCount('clientes')->get();
        return view('briefs.index', compact('briefs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('briefs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $actionType = $request->input('action_type', 'link');

        if ($actionType === 'create') {
            // --- LÓGICA PARA CREAR NUEVO FORMULARIO EN GOOGLE ---
            $request->validate([
                'titulo' => 'required|string|max:250',
                'descripcion' => 'nullable|string',
                'questions' => 'nullable|array',
            ]);

            try {
                // Crear form en Google con preguntas si las hay
                $createdForm = $this->googleFormsService->createForm(
                    $request->titulo, 
                    $request->descripcion,
                    $request->questions ?? []
                );
                
                $formId = $createdForm->getFormId();
                $formUrl = $createdForm->getResponderUri() ?? "https://docs.google.com/forms/d/{$formId}/viewform";

                Brief::create([
                    'google_form_id' => $formId,
                    'titulo' => $request->titulo,
                    'descripcion' => $request->descripcion,
                    'form_url' => $formUrl,
                ]);

                return redirect()->route('briefs.index')->with('success', 'Nuevo formulario creado en Google y registrado exitosamente.');

            } catch (\Exception $e) {
                Log::error('Error al crear brief: ' . $e->getMessage());
                return back()->with('error', 'Error al crear el formulario en Google: ' . $e->getMessage())->withInput();
            }

        } else {
            // --- LÓGICA ORIGINAL: VINCULAR EXISTENTE ---
            $request->validate([
                'google_form_url' => 'required|string',
            ]);

            $formId = $this->googleFormsService->extractFormIdFromUrl($request->google_form_url);

            if ($formId === false) {
                return back()->with('error', '⚠️ Error: Has ingresado un enlace "Público" (comienza con /d/e/). El sistema necesita el enlace de EDICIÓN para poder leer las respuestas. Abre tu formulario en modo edición y copia esa URL.')->withInput();
            }

            if (!$formId) {
                return back()->with('error', 'No se pudo identificar un ID válido en el enlace proporcionado. Asegúrate de usar la URL completa de edición.')->withInput();
            }

            // Verificar si ya existe
            if (Brief::where('google_form_id', $formId)->exists()) {
                return back()->with('error', 'Este formulario ya está registrado.')->withInput();
            }

            try {
                // Intentar obtener detalles de Google Forms
                $formDetails = $this->googleFormsService->getFormDetails($formId);

                if (!$formDetails) {
                     return back()->with('error', 'No se pudo conectar con Google Forms. Verifica que la aplicación esté autenticada y tenga permisos.')->withInput();
                }

                $titulo = $formDetails->getInfo()->getTitle() ?? 'Sin título';
                $descripcion = $formDetails->getInfo()->getDescription();
                // Construir URL de respuesta si no se proporcionó una explícita
                $formUrl = $formDetails->getResponderUri() ?? "https://docs.google.com/forms/d/{$formId}/viewform";

                Brief::create([
                    'google_form_id' => $formId,
                    'titulo' => $titulo,
                    'descripcion' => $descripcion,
                    'form_url' => $formUrl,
                ]);

                return redirect()->route('briefs.index')->with('success', 'Formulario registrado exitosamente.');

            } catch (\Exception $e) {
                Log::error('Error al registrar brief: ' . $e->getMessage());
                return back()->with('error', 'Ocurrió un error al registrar el formulario: ' . $e->getMessage())->withInput();
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Brief $brief)
    {
        $formDetails = null;
        $responses = null;
        $error = null;

        try {
            $formDetails = $this->googleFormsService->getFormDetails($brief->google_form_id);
            $responsesList = $this->googleFormsService->getFormResponses($brief->google_form_id);
            $responses = $responsesList ? $responsesList->getResponses() : [];
        } catch (\Exception $e) {
            $error = 'No se pudieron cargar los detalles actualizados desde Google: ' . $e->getMessage();
        }

        return view('briefs.show', compact('brief', 'formDetails', 'responses', 'error'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brief $brief)
    {
        return view('briefs.edit', compact('brief'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brief $brief)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'form_url' => 'nullable|url',
        ]);

        $brief->update($request->only(['titulo', 'descripcion', 'form_url']));

        return redirect()->route('briefs.index')->with('success', 'Formulario actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brief $brief)
    {
        $brief->delete();
        return redirect()->route('briefs.index')->with('success', 'Formulario eliminado exitosamente.');
    }

    /**
     * Muestra la vista para asignar clientes al brief.
     */
    public function assign(Brief $brief)
    {
        $clientes = Cliente::where('estatus', 'activo')
                           ->whereDoesntHave('briefs', function($q) use ($brief) {
                               $q->where('brief_id', $brief->id);
                           })
                           ->orderBy('nombre')
                           ->get();

        $brief->load('clientes.user');

        return view('briefs.assign', compact('brief', 'clientes'));
    }

    /**
     * Almacena la asignación de un cliente al brief.
     */
    public function storeAssignment(Request $request, Brief $brief)
    {
        $request->validate([
            'id_cliente' => 'required|exists:clientes,id_cliente',
        ]);

        $cliente = Cliente::with('user')->findOrFail($request->id_cliente);

        // Verificar si ya está asignado
        if ($brief->clientes()->where('cliente_id', $cliente->id_cliente)->exists()) {
            return back()->with('error', 'El cliente ya tiene asignado este brief.');
        }

        // Verificar si ya existe una respuesta en Google Forms para este cliente
        $googleResponseId = null;
        $estadoInicial = 'pendiente';
        
        if ($cliente->user && $cliente->user->email) {
            try {
                $clientEmail = strtolower(trim($cliente->user->email));
                $responsesList = $this->googleFormsService->getFormResponses($brief->google_form_id);
                $allResponses = $responsesList ? $responsesList->getResponses() : [];
                
                foreach ($allResponses as $response) {
                    $respondentEmail = strtolower(trim($response->getRespondentEmail()));
                    
                    // Verificar coincidencia por email en metadata
                    if ($respondentEmail === $clientEmail) {
                        $googleResponseId = $response->getResponseId();
                        $estadoInicial = 'recibido';
                        break;
                    }
                    
                    // Verificar coincidencia por email en el cuerpo de las respuestas
                    $answers = $response->getAnswers();
                    if ($answers) {
                        foreach ($answers as $answer) {
                            $textAnswers = $answer->getTextAnswers();
                            if ($textAnswers && $textAnswers->getAnswers()) {
                                foreach ($textAnswers->getAnswers() as $textAnswer) {
                                    $val = strtolower(trim($textAnswer->getValue()));
                                    if ($val === $clientEmail) {
                                        $googleResponseId = $response->getResponseId();
                                        $estadoInicial = 'recibido';
                                        break 3;
                                    }
                                }
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::warning('Error verificando respuestas existentes al asignar: ' . $e->getMessage());
            }
        }

        // Asignar con el estado y google_response_id detectados
        $brief->clientes()->attach($cliente->id_cliente, [
            'estado' => $estadoInicial,
            'fecha_envio' => now(),
            'google_response_id' => $googleResponseId,
        ]);

        // Enviar notificación y correo solo si está pendiente
        if ($cliente->user && $estadoInicial === 'pendiente') {
            try {
                \Illuminate\Support\Facades\Mail::to($cliente->user->email)
                    ->send(new \App\Mail\BriefAssigned($brief, $cliente));
            } catch (\Exception $e) {
                Log::error('Error enviando correo de asignación: ' . $e->getMessage());
            }

            Notificacion::create([
                'user_id' => $cliente->user->id,
                'tipo' => 'alerta',
                'titulo' => 'Nuevo Formulario Asignado',
                'mensaje' => "Se te ha asignado: {$brief->titulo}. Por favor respóndelo pronto.",
                'url' => $brief->form_url,
                'leida' => false,
            ]);
        }

        $mensaje = $estadoInicial === 'recibido' 
            ? 'Brief asignado. Se detectó una respuesta existente y fue vinculada automáticamente.'
            : 'Brief asignado correctamente al cliente.';
            
        return back()->with('success', $mensaje);
    }

    /**
     * Elimina la asignación de un cliente.
     */
    public function unassign(Brief $brief, $clienteId)
    {
        $brief->clientes()->detach($clienteId);

        // Eliminar notificación asociada a este cliente y brief
        $cliente = Cliente::find($clienteId);
        if ($cliente && $cliente->user) {
            Notificacion::where('user_id', $cliente->user->id)
                        ->where('url', $brief->form_url)
                        ->where('tipo', 'alerta')
                        ->delete();
        }

        return back()->with('success', 'Asignación eliminada correctamente.');
    }

    /**
     * Solicita una nueva respuesta del cliente (reinicia el estado).
     */
    public function requestNewResponse(Brief $brief, $clienteId)
    {
        $cliente = Cliente::with('user')->findOrFail($clienteId);
        
        // Reiniciar el estado de la asignación
        $brief->clientes()->updateExistingPivot($clienteId, [
            'estado' => 'pendiente',
            'google_response_id' => null,
            'fecha_envio' => now(),
            'fecha_ultimo_recordatorio' => null,
        ]);

        // Enviar notificación y correo
        if ($cliente->user) {
            try {
                \Illuminate\Support\Facades\Mail::to($cliente->user->email)
                    ->send(new \App\Mail\BriefAssigned($brief, $cliente));
            } catch (\Exception $e) {
                Log::error('Error enviando correo de nueva solicitud: ' . $e->getMessage());
            }

            Notificacion::create([
                'user_id' => $cliente->user->id,
                'tipo' => 'alerta',
                'titulo' => 'Nueva Respuesta Solicitada',
                'mensaje' => "Se te ha solicitado una nueva respuesta para: {$brief->titulo}.",
                'url' => $brief->form_url,
                'leida' => false,
            ]);
        }

        return back()->with('success', 'Se ha solicitado una nueva respuesta al cliente. Se reinició el estado y se envió notificación.');
    }

    /**
     * Muestra las respuestas de un cliente específico.
     */
    public function showClientResponse(Brief $brief, $clienteId)
    {
        $cliente = Cliente::with('user')->findOrFail($clienteId);

        // Obtener datos del pivot 
        $pivot = $brief->clientes()->where('cliente_id', $clienteId)->first()->pivot;
        $fechaEnvio = $pivot->fecha_envio ? \Carbon\Carbon::parse($pivot->fecha_envio) : null;
        $linkedResponseId = $pivot->google_response_id;

        // Obtener emails de otros clientes asignados para excluir sus respuestas explícitas de "Posibles Coincidencias"
        $otherEmails = $brief->clientes()
            ->where('clientes.id_cliente', '!=', $clienteId)
            ->with('user')
            ->get()
            ->pluck('user.email')
            ->filter()
            ->map(fn($e) => strtolower(trim($e)))
            ->toArray();

        $clientResponses = [];
        $potentialMatches = [];
        $formDetails = null;
        $isLinked = false;

        try {
            // Obtener todas las respuestas
            $responsesList = $this->googleFormsService->getFormResponses($brief->google_form_id);
            $allResponses = $responsesList ? $responsesList->getResponses() : [];
            
            // Obtener detalles para títulos de preguntas
            $formDetails = $this->googleFormsService->getFormDetails($brief->google_form_id);

            // Filtrar por email del cliente
            $clientEmail = $cliente->user ? $cliente->user->email : null;
            
            if ($allResponses) {
                 foreach ($allResponses as $response) {
                     $responseId = $response->getResponseId();
                     
                     // 0. Si YA está vinculado manualmente, mostramos SOLO ese
                     if ($linkedResponseId && $responseId === $linkedResponseId) {
                         $clientResponses[] = $response;
                         $isLinked = true;
                         // Limpiamos potentialMatches porque ya encontramos el definitivo
                         $potentialMatches = [];
                         break; 
                     }

                     // Si NO hay uno vinculado, buscamos candidatos
                     if (!$linkedResponseId) {
                         $respondentEmail = $response->getRespondentEmail();
                         $submissionTime = \Carbon\Carbon::parse($response->getCreateTime());
                         
                         // FILTRO POR FECHA: Solo mostrar respuestas POSTERIORES a la fecha de envío
                         // Esto permite que al solicitar "Nueva Respuesta", solo se muestren las nuevas
                         if ($fechaEnvio && $submissionTime->lessThanOrEqualTo($fechaEnvio)) {
                             continue; // Respuesta anterior al envío actual, no la mostramos
                         }
                         
                         // Si el email pertenece a OTRO cliente asignado, lo ignoramos completamente aquí
                         if ($respondentEmail && in_array(strtolower(trim($respondentEmail)), $otherEmails)) {
                             continue;
                         }

                         // Búsqueda profunda de email en las respuestas del cuerpo (para auto-verificación y EXCLUSIÓN)
                         $foundEmailInBody = false;
                         $bodyEmail = null;

                         $answers = $response->getAnswers();
                         if ($answers) {
                             foreach ($answers as $answer) {
                                 $textAnswers = $answer->getTextAnswers();
                                 if ($textAnswers && $textAnswers->getAnswers()) {
                                     foreach ($textAnswers->getAnswers() as $textAnswer) {
                                         $val = strtolower(trim($textAnswer->getValue()));
                                         
                                         // 1. Si encontramos el email del cliente ACTUAL, marcamos found
                                         if ($clientEmail && $val === strtolower(trim($clientEmail))) {
                                             $foundEmailInBody = true;
                                         }

                                         // 2. Si encontramos el email de OTRO cliente, excluimos esta respuesta (es de otro)
                                         if (in_array($val, $otherEmails)) {
                                             continue 3; // Salir de loops internos y pasar al siguiente RESPONSE
                                         }
                                     }
                                 }
                             }
                         }

                         // 1. Coincidencia Exacta por Email (Metadata o Cuerpo)
                         if (($clientEmail && strtolower(trim($respondentEmail)) === strtolower(trim($clientEmail))) || $foundEmailInBody) {
                             $clientResponses[] = $response;
                             
                             // AUTO-VINCULAR: Si hay coincidencia exacta por email, guardar automáticamente
                             if (!$linkedResponseId) {
                                 $brief->clientes()->updateExistingPivot($clienteId, [
                                     'google_response_id' => $responseId,
                                     'estado' => 'recibido'
                                 ]);
                                 $linkedResponseId = $responseId; // Marcar como vinculado para no seguir buscando
                                 $isLinked = true;
                             }
                         }
                         // 2. Coincidencia por Fecha (Si no hay email o no coincide, pero la fecha es válida para este cliente)
                         elseif ($fechaEnvio && $submissionTime->greaterThan($fechaEnvio)) {
                             $potentialMatches[] = $response;
                         }
                     }
                 }
            }

        } catch (\Exception $e) {
            Log::error('Error cargando respuestas de cliente: ' . $e->getMessage());
            return back()->with('error', 'Error al conectar con Google Forms: ' . $e->getMessage());
        }

        return view('briefs.client_response', compact('brief', 'cliente', 'clientResponses', 'potentialMatches', 'formDetails', 'isLinked'));
    }

    /**
     * Vincula manualmente una respuesta a un cliente.
     */
    public function linkResponse(Request $request, Brief $brief, $clienteId)
    {
        $request->validate([
            'response_id' => 'required|string'
        ]);

        $brief->clientes()->updateExistingPivot($clienteId, [
            'google_response_id' => $request->response_id,
            'estado' => 'recibido' // Forzamos a recibido si lo vinculan
        ]);

        return back()->with('success', 'Respuesta vinculada correctamente al cliente.');
    }

    /**
     * Desvincula una respuesta de un cliente.
     */
    public function unlinkResponse(Brief $brief, $clienteId)
    {
        $brief->clientes()->updateExistingPivot($clienteId, [
            'google_response_id' => null
        ]);

        return back()->with('success', 'Vinculación eliminada. Ahora verás todas las posibles coincidencias.');
    }
}
