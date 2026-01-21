<?php

namespace App\Http\Controllers;

use App\Models\Brief;
use App\Models\Notificacion;
use App\Services\GoogleFormsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        $briefs = Brief::latest()->get();
        return view('briefs.index', compact('briefs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clientes = \App\Models\Cliente::where('estatus', 'activo')->orderBy('nombre')->get();
        return view('briefs.create', compact('clientes'));
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
                'id_cliente' => 'nullable|exists:clientes,id_cliente',
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

                $brief = Brief::create([
                    'google_form_id' => $formId,
                    'titulo' => $request->titulo,
                    'descripcion' => $request->descripcion,
                    'form_url' => $formUrl,
                    'id_cliente' => $request->id_cliente,
                    'estado' => $request->id_cliente ? 'pendiente' : 'pendiente', // Si hay cliente, inicia pendiente
                    'fecha_envio' => $request->id_cliente ? now() : null,
                ]);

                // Enviar correo si hay cliente asignado
                if ($brief->id_cliente && $brief->cliente && $brief->cliente->user) {
                     \Illuminate\Support\Facades\Mail::to($brief->cliente->user->email)
                        ->send(new \App\Mail\BriefAssigned($brief));
                     
                     // Crear notificación en sistema
                     Notificacion::create([
                        'user_id' => $brief->cliente->user->id,
                        'tipo' => 'alerta',
                        'titulo' => 'Nuevo Formulario Asignado',
                        'mensaje' => "Se te ha asignado: {$brief->titulo}. Por favor respóndelo pronto.",
                        'url' => $brief->form_url,
                        'leida' => false,
                     ]);
                }

                return redirect()->route('briefs.index')->with('success', 'Nuevo formulario creado en Google, registrado y asignado exitosamente.');

            } catch (\Exception $e) {
                Log::error('Error al crear brief: ' . $e->getMessage());
                return back()->with('error', 'Error al crear el formulario en Google: ' . $e->getMessage())->withInput();
            }

        } else {
            // --- LÓGICA ORIGINAL: VINCULAR EXISTENTE ---
            $request->validate([
                'google_form_url' => 'required|string',
                'id_cliente' => 'nullable|exists:clientes,id_cliente',
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

                $brief = Brief::create([
                    'google_form_id' => $formId,
                    'titulo' => $titulo,
                    'descripcion' => $descripcion,
                    'form_url' => $formUrl,
                    'id_cliente' => $request->id_cliente,
                    'estado' => $request->id_cliente ? 'pendiente' : 'pendiente',
                    'fecha_envio' => $request->id_cliente ? now() : null,
                ]);

                // Enviar correo si hay cliente asignado
                if ($brief->id_cliente && $brief->cliente && $brief->cliente->user) {
                     \Illuminate\Support\Facades\Mail::to($brief->cliente->user->email)
                        ->send(new \App\Mail\BriefAssigned($brief));
                     
                     // Crear notificación en sistema
                     Notificacion::create([
                        'user_id' => $brief->cliente->user->id,
                        'tipo' => 'alerta',
                        'titulo' => 'Nuevo Formulario Asignado',
                        'mensaje' => "Se te ha asignado: {$brief->titulo}. Por favor respóndelo pronto.",
                        'url' => $brief->form_url,
                        'leida' => false,
                     ]);
                }

                return redirect()->route('briefs.index')->with('success', 'Formulario registrado y asignado exitosamente.');

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
        $clientes = \App\Models\Cliente::where('estatus', 'activo')->orderBy('nombre')->get();
        return view('briefs.edit', compact('brief', 'clientes'));
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
            'id_cliente' => 'nullable|exists:clientes,id_cliente',
        ]);

        $previousClienteId = $brief->id_cliente;
        $data = $request->only(['titulo', 'descripcion', 'form_url', 'id_cliente']);
        
        // Si se asigna un cliente por primera vez O se cambia de cliente
        // Reiniciamos el estado y la fecha de envío para que cuente como una nueva asignación
        if ($request->id_cliente && ($request->id_cliente != $previousClienteId || !$previousClienteId)) {
            $data['estado'] = 'pendiente';
            $data['fecha_envio'] = now();
        }

        $brief->update($data);

        // Notificar si se asignó a un nuevo cliente (o cambió)
        if ($request->id_cliente && $request->id_cliente != $previousClienteId) {
             // Recargar relación para asegurar que tenemos el cliente nuevo
             $brief->load('cliente.user');
             
             if ($brief->cliente && $brief->cliente->user) {
                 // 1. Correo
                 try {
                    \Illuminate\Support\Facades\Mail::to($brief->cliente->user->email)
                        ->send(new \App\Mail\BriefAssigned($brief));
                 } catch (\Exception $e) {
                     Log::error('Error enviando correo de asignación en update: ' . $e->getMessage());
                 }

                 // 2. Notificación Sistema
                 Notificacion::create([
                    'user_id' => $brief->cliente->user->id,
                    'tipo' => 'alerta',
                    'titulo' => 'Nuevo Formulario Asignado',
                    'mensaje' => "Se te ha asignado: {$brief->titulo}. Por favor respóndelo pronto.",
                    'url' => $brief->form_url,
                    'leida' => false,
                 ]);
             }
        }

        // Opcional: Si cambió el cliente, podríamos enviar notificación, 
        // pero por ahora solo actualizamos la referencia.

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
}
