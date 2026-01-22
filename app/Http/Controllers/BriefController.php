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

        // Asignar
        $brief->clientes()->attach($cliente->id_cliente, [
            'estado' => 'pendiente',
            'fecha_envio' => now(),
        ]);

        // Enviar notificación y correo
        if ($cliente->user) {
            try {
                \Illuminate\Support\Facades\Mail::to($cliente->user->email)
                    ->send(new \App\Mail\BriefAssigned($brief));
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

        return back()->with('success', 'Brief asignado correctamente al cliente.');
    }

    /**
     * Elimina la asignación de un cliente.
     */
    public function unassign(Brief $brief, $clienteId)
    {
        $brief->clientes()->detach($clienteId);
        return back()->with('success', 'Asignación eliminada correctamente.');
    }
}
