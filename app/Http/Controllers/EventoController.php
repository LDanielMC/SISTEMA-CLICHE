<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\EventoParticipante;
use App\Models\EventoRecordatorio;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Services\GoogleCalendarService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class EventoController extends Controller
{
    public function index(Request $request)
    {
        $eventos = Evento::with(['cliente', 'creador', 'participantes', 'recordatorios'])
            ->where('creado_por', auth()->id())
            ->proximos()
            ->get();

        $clientes = Cliente::where('estatus', 'activo')->orderBy('nombre')->get();
        $empleados = Empleado::where('estatus', 'activo')->orderBy('nombre')->get();

        return view('eventos.index', compact('eventos', 'clientes', 'empleados'));
    }

    public function calendario(Request $request)
    {
        $inicio = Carbon::parse($request->get('start', now()->startOfMonth()->format('Y-m-d')));
        $fin = Carbon::parse($request->get('end', now()->endOfMonth()->format('Y-m-d')));

        // Obtener eventos normales en el rango
        $eventos = Evento::with(['cliente', 'participantes'])
            ->where('creado_por', auth()->id())
            ->where(function($query) use ($inicio, $fin) {
                // Eventos normales en el rango
                $query->whereBetween('fecha', [$inicio->format('Y-m-d'), $fin->format('Y-m-d')])
                    // O eventos recurrentes que empiezan antes pero se extienden al rango
                    ->orWhere(function($q) use ($inicio) {
                        $q->where('recurrencia', '!=', 'ninguna')
                          ->where('fecha', '<=', $inicio->format('Y-m-d'));
                    });
            })
            ->orderBy('fecha', 'asc')
            ->orderBy('hora_inicio', 'asc')
            ->get();

        $eventosExpandidos = [];

        foreach ($eventos as $evento) {
            // Extraer solo la parte de hora si es timestamp completo
            $horaInicio = strlen($evento->hora_inicio) > 8 
                ? date('H:i:s', strtotime($evento->hora_inicio))
                : $evento->hora_inicio;
            $horaFin = strlen($evento->hora_fin) > 8 
                ? date('H:i:s', strtotime($evento->hora_fin))
                : $evento->hora_fin;

            if ($evento->recurrencia === 'ninguna') {
                // Evento sin recurrencia - agregar directamente
                $eventosExpandidos[] = [
                    'id' => $evento->id,
                    'title' => $evento->titulo,
                    'start' => $evento->fecha->format('Y-m-d') . 'T' . $horaInicio,
                    'end' => $evento->fecha->format('Y-m-d') . 'T' . $horaFin,
                    'backgroundColor' => $evento->color,
                    'borderColor' => $evento->color,
                    'extendedProps' => [
                        'cliente' => $evento->cliente?->nombre,
                        'lugar' => $evento->lugar,
                        'notas' => $evento->notas,
                        'recurrencia' => false,
                    ],
                ];
            } else {
                // Evento recurrente - expandir ocurrencias
                $ocurrencias = $this->expandirRecurrencia($evento, $inicio, $fin);
                foreach ($ocurrencias as $fechaOcurrencia) {
                    $eventosExpandidos[] = [
                        'id' => $evento->id . '_' . $fechaOcurrencia->format('Ymd'),
                        'title' => $evento->titulo,
                        'start' => $fechaOcurrencia->format('Y-m-d') . 'T' . $horaInicio,
                        'end' => $fechaOcurrencia->format('Y-m-d') . 'T' . $horaFin,
                        'backgroundColor' => $evento->color,
                        'borderColor' => $evento->color,
                        'extendedProps' => [
                            'cliente' => $evento->cliente?->nombre,
                            'lugar' => $evento->lugar,
                            'notas' => $evento->notas,
                            'recurrencia' => true,
                            'eventoOriginalId' => $evento->id,
                        ],
                    ];
                }
            }
        }

        return response()->json($eventosExpandidos);
    }

    private function expandirRecurrencia(Evento $evento, Carbon $rangoInicio, Carbon $rangoFin)
    {
        $ocurrencias = [];
        $fechaActual = Carbon::parse($evento->fecha);
        $fechaLimite = $evento->recurrencia_hasta 
            ? Carbon::parse($evento->recurrencia_hasta) 
            : $rangoFin;

        // No generar más de 100 ocurrencias para evitar problemas de memoria
        $maxOcurrencias = 100;
        $contador = 0;

        while ($fechaActual->lte($fechaLimite) && $fechaActual->lte($rangoFin) && $contador < $maxOcurrencias) {
            if ($fechaActual->gte($rangoInicio)) {
                $ocurrencias[] = $fechaActual->copy();
            }

            // Avanzar según el tipo de recurrencia
            switch ($evento->recurrencia) {
                case 'diaria':
                    $fechaActual->addDay();
                    break;
                case 'semanal':
                    $fechaActual->addWeek();
                    break;
                case 'mensual':
                    $fechaActual->addMonth();
                    break;
                case 'anual':
                    $fechaActual->addYear();
                    break;
            }

            $contador++;
        }

        return $ocurrencias;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'cliente_id' => 'nullable|exists:clientes,id_cliente',
            'fecha' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'lugar' => 'nullable|string|max:255',
            'notas' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'recurrencia' => 'required|in:ninguna,diaria,semanal,mensual,anual',
            'recurrencia_hasta' => 'nullable|date|after:fecha',
            'participantes' => 'nullable|array',
            'participantes.*.tipo' => 'required|in:cliente,empleado,externo',
            'participantes.*.referencia_id' => 'nullable|integer',
            'participantes.*.nombre' => 'required|string',
            'participantes.*.correo' => 'required|email',
            'recordatorios' => 'nullable|array',
            'recordatorios.*.tipo_notificacion' => 'required|in:correo,sistema,ambos',
            'recordatorios.*.minutos_antes' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $evento = Evento::create([
                'titulo' => $validated['titulo'],
                'cliente_id' => $validated['cliente_id'] ?? null,
                'creado_por' => auth()->id(),
                'fecha' => $validated['fecha'],
                'hora_inicio' => $validated['hora_inicio'],
                'hora_fin' => $validated['hora_fin'],
                'lugar' => $validated['lugar'] ?? null,
                'notas' => $validated['notas'] ?? null,
                'color' => $validated['color'] ?? '#3B82F6',
                'recurrencia' => $validated['recurrencia'],
                'recurrencia_hasta' => $validated['recurrencia_hasta'] ?? null,
            ]);

            if (isset($validated['participantes'])) {
                foreach ($validated['participantes'] as $participante) {
                    EventoParticipante::create([
                        'evento_id' => $evento->id,
                        'tipo' => $participante['tipo'],
                        'referencia_id' => $participante['referencia_id'] ?? null,
                        'nombre' => $participante['nombre'],
                        'correo' => $participante['correo'],
                    ]);
                }
            }

            if (isset($validated['recordatorios'])) {
                foreach ($validated['recordatorios'] as $recordatorio) {
                    EventoRecordatorio::create([
                        'evento_id' => $evento->id,
                        'tipo_notificacion' => $recordatorio['tipo_notificacion'],
                        'minutos_antes' => $recordatorio['minutos_antes'],
                    ]);
                }
            }

            DB::commit();

            // Sincronizar con Google Calendar
            try {
                $googleService = new GoogleCalendarService();
                if ($googleService->isAuthenticated()) {
                    $googleService->createEvent($evento);
                }
            } catch (\Exception $e) {
                Log::error('Error al sincronizar con Google Calendar: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'evento' => $evento->load(['cliente', 'participantes', 'recordatorios'])
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al crear el evento: ' . $e->getMessage()], 500);
        }
    }

    public function show(Evento $evento)
    {
        $evento->load(['cliente', 'creador', 'participantes', 'recordatorios']);
        return response()->json($evento);
    }

    public function update(Request $request, Evento $evento)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'cliente_id' => 'nullable|exists:clientes,id_cliente',
            'fecha' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'lugar' => 'nullable|string|max:255',
            'notas' => 'nullable|string',
            'color' => 'nullable|string|max:7',
        ]);

        $evento->update($validated);

        // Sincronizar con Google Calendar
        try {
            $googleService = new GoogleCalendarService();
            if ($googleService->isAuthenticated() && $evento->google_event_id) {
                $googleService->updateEvent($evento);
            }
        } catch (\Exception $e) {
            Log::error('Error al actualizar en Google Calendar: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'evento' => $evento->load(['cliente', 'participantes', 'recordatorios'])
        ]);
    }

    public function destroy(Evento $evento)
    {
        // Eliminar de Google Calendar primero
        try {
            $googleService = new GoogleCalendarService();
            if ($googleService->isAuthenticated() && $evento->google_event_id) {
                $googleService->deleteEvent($evento);
            }
        } catch (\Exception $e) {
            Log::error('Error al eliminar de Google Calendar: ' . $e->getMessage());
        }

        $evento->delete();
        return response()->json(['success' => true]);
    }

    public function googleAuth()
    {
        $googleService = new GoogleCalendarService();
        return redirect($googleService->getAuthUrl());
    }

    public function googleCallback(Request $request)
    {
        Log::info('=== INICIO Google Callback ===');
        Log::info('Request completo: ', $request->all());
        
        $code = $request->get('code');
        $error = $request->get('error');
        
        if ($error) {
            Log::error('Google OAuth error: ' . $error);
            return redirect()->route('eventos.index')->with('error', 'Google rechazó la autorización: ' . $error);
        }
        
        if (!$code) {
            Log::error('No code parameter received');
            return redirect()->route('eventos.index')->with('error', 'No se recibió código de autorización de Google');
        }

        Log::info('Code recibido, longitud: ' . strlen($code));

        try {
            Log::info('Creando GoogleCalendarService...');
            $googleService = new GoogleCalendarService();
            
            Log::info('Intentando autenticar con código...');
            $result = $googleService->authenticate($code);
            
            Log::info('Autenticación exitosa. Token guardado.');
            Log::info('Verificando archivo de token...');
            
            $tokenPath = config('google-calendar.token_path');
            if (file_exists($tokenPath)) {
                Log::info('✅ Token guardado correctamente en: ' . $tokenPath);
            } else {
                Log::error('❌ Token NO se guardó en: ' . $tokenPath);
            }
            
            // Verificar si hay sesión activa
            if (!auth()->check()) {
                Log::warning('Usuario no autenticado después del callback');
                session(['google_auth_success' => true]);
                return redirect()->route('login')->with('success', '✅ Google Calendar conectado. Inicia sesión para continuar.');
            }
            
            Log::info('=== FIN Google Callback EXITOSO ===');
            return redirect()->route('eventos.index')->with('success', '✅ Conectado con Google Calendar exitosamente');
            
        } catch (\Exception $e) {
            Log::error('=== ERROR en Google OAuth callback ===');
            Log::error('Mensaje: ' . $e->getMessage());
            Log::error('Archivo: ' . $e->getFile() . ':' . $e->getLine());
            Log::error('Trace: ' . $e->getTraceAsString());
            
            if (!auth()->check()) {
                return redirect()->route('login')->with('error', 'Error al conectar: ' . $e->getMessage());
            }
            
            return redirect()->route('eventos.index')->with('error', 'Error al conectar con Google Calendar: ' . $e->getMessage());
        }
    }

    public function googleStatus()
    {
        $googleService = new GoogleCalendarService();
        return response()->json([
            'authenticated' => $googleService->isAuthenticated(),
            'auth_url' => !$googleService->isAuthenticated() ? $googleService->getAuthUrl() : null,
        ]);
    }
}
