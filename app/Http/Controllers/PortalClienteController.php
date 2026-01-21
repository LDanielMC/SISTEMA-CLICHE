<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Brief;
use App\Services\GoogleFormsService;
use Illuminate\Support\Facades\Log;

class PortalClienteController extends Controller
{
    protected $googleFormsService;

    public function __construct(GoogleFormsService $googleFormsService)
    {
        $this->googleFormsService = $googleFormsService;
    }

    public function dashboard()
    {
        $user = Auth::user();

        if ($user->rol !== 'cliente') {
            abort(403, 'No autorizado.');
        }

        $cliente = $user->cliente; // Relación definida en User model

        $briefsPendientes = [];

        if ($cliente) {
            // Obtenemos los pendientes iniciales
            $briefsCandidates = Brief::where('id_cliente', $cliente->id_cliente)
                                     ->where('estado', 'pendiente')
                                     ->orderBy('created_at', 'desc')
                                     ->get();
            
            // Verificamos en tiempo real si ya fueron respondidos
            foreach ($briefsCandidates as $brief) {
                try {
                    $responsesList = $this->googleFormsService->getFormResponses($brief->google_form_id);
                    $responses = $responsesList ? $responsesList->getResponses() : [];

                    $hasNewResponse = false;
                    
                    if (!empty($responses)) {
                        foreach ($responses as $response) {
                            // Verificamos si la respuesta es posterior a la fecha de asignación actual
                            // Google devuelve timestamp ISO 8601 / RFC3339
                            $submissionTime = \Carbon\Carbon::parse($response->getCreateTime());
                            
                            // Si no hay fecha de envío, asumimos que todas cuentan (fallback), 
                            // pero si hay, filtramos las viejas.
                            if (!$brief->fecha_envio || $submissionTime->greaterThan($brief->fecha_envio)) {
                                $hasNewResponse = true;
                                break;
                            }
                        }
                    }

                    if ($hasNewResponse) {
                        // Si hay respuestas VÁLIDAS (nuevas), actualizamos estado y NO lo agregamos a pendientes
                        $brief->update(['estado' => 'recibido']);
                    } else {
                        // Si no hay respuestas o son todas anteriores a la reasignación, lo mostramos como pendiente
                        $briefsPendientes[] = $brief;
                    }
                } catch (\Exception $e) {
                    // Si falla la API (ej. error de red), asumimos que sigue pendiente para no ocultarlo por error
                    Log::error("Error verificando brief {$brief->id} en dashboard: " . $e->getMessage());
                    $briefsPendientes[] = $brief;
                }
            }
        }

        // Convertir a colección para asegurar compatibilidad con métodos de vista (ej. ->count())
        $briefsPendientes = collect($briefsPendientes);

        // Obtener historial de encuestas completadas (incluyendo las que se acaban de actualizar)
        $briefsCompletados = [];
        if ($cliente) {
            $briefsCompletados = Brief::where('id_cliente', $cliente->id_cliente)
                                      ->where('estado', 'recibido')
                                      ->orderBy('updated_at', 'desc')
                                      ->take(10)
                                      ->get();
        }

        return view('portal_cliente.dashboard', compact('briefsPendientes', 'briefsCompletados'));
    }
}
