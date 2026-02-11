<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Brief;
use App\Models\Publicacion;
use App\Models\Cliente;
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

        $cliente = $user->cliente; 

        $eventosFormateados = [];

        if ($cliente) {
             $eventos = Publicacion::where('cliente_id', $cliente->id_cliente)
                ->with(['plataforma', 'formato'])
                ->get();

            $eventosFormateados = $eventos->map(function ($evento) {
                return [
                    'id' => $evento->idPublicacion,
                    'title' => ($evento->formato?->nombre ?? 'Publicación') . ($evento->plataforma ? ' (' . $evento->plataforma->nombre . ')' : ''),
                    'start' => $evento->fecha->format('Y-m-d'),
                    'color' => $this->getColorPorEstatus($evento->estatus),
                    'extendedProps' => [
                        'estatus' => $evento->estatus,
                        'formato' => $evento->formato?->nombre ?? '',
                        'plataforma' => $evento->plataforma?->nombre ?? '',
                        'cliente_nombre' => ($evento->cliente?->empresa ?: $evento->cliente?->nombre) ?? 'Cliente',
                        'copy' => $evento->copy,
                        'arte' => $evento->arte,
                    ]
                ];
            });
        }

        return view('portal_cliente.dashboard', compact('eventosFormateados'));
    }

    public function briefs()
    {
        $user = Auth::user();

        if ($user->rol !== 'cliente') {
            abort(403, 'No autorizado.');
        }

        $cliente = $user->cliente;

        $briefsPendientes = [];
        $briefsCompletados = [];

        if ($cliente) {
            // Obtenemos los pendientes iniciales desde la relación pivot
            $briefsCandidates = $cliente->briefs()
                                        ->wherePivot('estado', 'pendiente')
                                        ->orderBy('brief_cliente.created_at', 'desc')
                                        ->get();
            
            // Verificamos en tiempo real si ya fueron respondidos
            foreach ($briefsCandidates as $brief) {
                try {
                    $responsesList = $this->googleFormsService->getFormResponses($brief->google_form_id);
                    $responses = $responsesList ? $responsesList->getResponses() : [];
                    
                    // Obtener emails de OTROS clientes asignados a este brief para evitar falsos positivos
                    // (Si Cliente A responde, que no se le marque como completado a Cliente B solo por fecha)
                    $otherClientsEmails = $brief->clientes()
                        ->where('clientes.id_cliente', '!=', $cliente->id_cliente)
                        ->with('user')
                        ->get()
                        ->pluck('user.email')
                        ->filter()
                        ->map(fn($e) => strtolower(trim($e)))
                        ->toArray();

                    $hasNewResponse = false;
                    
                    if (!empty($responses)) {
                        foreach ($responses as $response) {
                            $responseId = $response->getResponseId();
                            $submissionTime = \Carbon\Carbon::parse($response->getCreateTime());
                            $fechaEnvio = $brief->pivot->fecha_envio ? \Carbon\Carbon::parse($brief->pivot->fecha_envio) : null;
                            $linkedResponseId = $brief->pivot->google_response_id;

                            // 1. Si está vinculado manualmente a MI usuario, es match
                            if ($linkedResponseId && $responseId === $linkedResponseId) {
                                $hasNewResponse = true;
                                break;
                            }
                            
                            // Si está vinculado a OTRO response ID (que no es este), obviamente no es match de este response specific, 
                            // pero el loop sigue. La lógica correcta es: si este response está vinculado a OTRO pivoting, ignore file.
                            // Pero aquí 'linkedResponseId' es "el ID que mi pivot espera". Si tengo uno, solo ese vale.
                            if ($linkedResponseId && $responseId !== $linkedResponseId) {
                                continue;
                            }

                            // 2. Si NO tengo vinculación manual, buscamos coincidencia inteligente
                            if (!$linkedResponseId) {
                                $respondentEmail = strtolower(trim($response->getRespondentEmail()));
                                
                                // FILTRO POR FECHA: Solo contar respuestas POSTERIORES a fecha_envio
                                // Esto permite que al solicitar "Nueva Respuesta", solo cuenten las nuevas
                                if ($fechaEnvio && $submissionTime->lessThanOrEqualTo($fechaEnvio)) {
                                    continue; // Respuesta anterior al envío actual, no cuenta
                                }
                                
                                // EXCLUSIÓN DE SEGURIDAD: 
                                // Si el email de esta respuesta pertenece explícitamente a otro cliente asignado, IGNORARLA.
                                if ($respondentEmail && in_array($respondentEmail, $otherClientsEmails)) {
                                    continue;
                                }

                                // Busqueda en Body (con Exclusion también)
                                $foundEmailInBody = false;
                                $userEmail = $user->email;

                                $answers = $response->getAnswers();
                                if ($answers) {
                                    foreach ($answers as $answer) {
                                        $textAnswers = $answer->getTextAnswers();
                                        if ($textAnswers && $textAnswers->getAnswers()) {
                                            foreach ($textAnswers->getAnswers() as $textAnswer) {
                                                $val = strtolower(trim($textAnswer->getValue()));
                                                
                                                // Check si es mio
                                                if ($userEmail && $val === strtolower(trim($userEmail))) {
                                                    $foundEmailInBody = true;
                                                }
                                                // Check si es de otro (Exclusión)
                                                if (in_array($val, $otherClientsEmails)) {
                                                    continue 3; // Salir de loops internos y pasar al siguiente RESPONSE
                                                }
                                            }
                                        }
                                    }
                                }

                                // Coincidencia estricta por Email (Metadata o Cuerpo)
                                // Ya NO hay fallback por fecha - solo email
                                if (($userEmail && $respondentEmail === strtolower(trim($userEmail))) || $foundEmailInBody) {
                                    $hasNewResponse = true;
                                    break;
                                }
                            }
                        }
                    }

                    if ($hasNewResponse) {
                        // Actualizamos el estado en la tabla pivot
                        $cliente->briefs()->updateExistingPivot($brief->id, ['estado' => 'recibido']);
                    } else {
                        $briefsPendientes[] = $brief;
                    }
                } catch (\Exception $e) {
                    Log::error("Error verificando brief {$brief->id} en dashboard: " . $e->getMessage());
                    $briefsPendientes[] = $brief;
                }
            }

            // Historial desde la relación pivot
            $briefsCompletados = $cliente->briefs()
                                         ->wherePivot('estado', 'recibido')
                                         ->orderBy('brief_cliente.updated_at', 'desc')
                                         ->get();
        }

        $briefsPendientes = collect($briefsPendientes);

        return view('portal_cliente.briefs', compact('briefsPendientes', 'briefsCompletados'));
    }

    private function getColorPorEstatus($estatus)
    {
        return match ($estatus) {
            'Publicado' => '#28a745',
            'Pendiente' => '#ffc107',
            'Reprogramar' => '#dc3545',
            default => '#6c757d',
        };
    }
}
