<?php

namespace App\Services;

use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\EventDateTime;
use App\Models\Evento;
use Carbon\Carbon;

class GoogleCalendarService
{
    private $client;
    private $calendar;
    private $calendarId;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setApplicationName(config('app.name'));
        $this->client->setScopes(config('google-calendar.scopes'));
        $this->client->setAuthConfig([
            'client_id' => config('google-calendar.client_id'),
            'client_secret' => config('google-calendar.client_secret'),
            'redirect_uris' => [config('google-calendar.redirect_uri')],
        ]);
        $this->client->setAccessType('offline');
        $this->client->setPrompt('consent');

        $this->calendarId = config('google-calendar.calendar_id');

        // Cargar token si existe
        $this->loadToken();
    }

    private function loadToken()
    {
        $tokenPath = config('google-calendar.token_path');
        
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $this->client->setAccessToken($accessToken);

            // Refrescar token si está expirado
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

    public function getAuthUrl()
    {
        return $this->client->createAuthUrl();
    }

    public function authenticate($code)
    {
        $accessToken = $this->client->fetchAccessTokenWithAuthCode($code);
        
        if (isset($accessToken['error'])) {
            throw new \Exception('Error al autenticar: ' . $accessToken['error']);
        }

        $tokenPath = config('google-calendar.token_path');
        if (!file_exists(dirname($tokenPath))) {
            mkdir(dirname($tokenPath), 0700, true);
        }
        file_put_contents($tokenPath, json_encode($accessToken));

        return $accessToken;
    }

    private function getCalendarService()
    {
        if (!$this->calendar) {
            $this->calendar = new Calendar($this->client);
        }
        return $this->calendar;
    }

    public function createEvent(Evento $evento)
    {
        if (!config('google-calendar.sync_enabled') || !$this->isAuthenticated()) {
            return null;
        }

        try {
            $service = $this->getCalendarService();
            
            // Extraer solo la parte de hora si es timestamp completo
            $horaInicio = strlen($evento->hora_inicio) > 8 
                ? date('H:i:s', strtotime($evento->hora_inicio))
                : $evento->hora_inicio;
            $horaFin = strlen($evento->hora_fin) > 8 
                ? date('H:i:s', strtotime($evento->hora_fin))
                : $evento->hora_fin;
            
            $googleEvent = new Event([
                'summary' => $evento->titulo,
                'location' => $evento->lugar,
                'description' => $this->buildDescription($evento),
                'start' => [
                    'dateTime' => Carbon::parse($evento->fecha->format('Y-m-d') . ' ' . $horaInicio)->toRfc3339String(),
                    'timeZone' => config('app.timezone'),
                ],
                'end' => [
                    'dateTime' => Carbon::parse($evento->fecha->format('Y-m-d') . ' ' . $horaFin)->toRfc3339String(),
                    'timeZone' => config('app.timezone'),
                ],
                'colorId' => $this->convertColorToGoogleColorId($evento->color),
            ]);

            // Agregar participantes
            if ($evento->participantes->count() > 0) {
                $attendees = [];
                foreach ($evento->participantes as $participante) {
                    $attendees[] = ['email' => $participante->correo];
                }
                $googleEvent->setAttendees($attendees);
            }

            // Agregar recordatorios
            if ($evento->recordatorios->count() > 0) {
                $overrides = [];
                foreach ($evento->recordatorios as $recordatorio) {
                    $override = new \Google\Service\Calendar\EventReminder();
                    $override->setMethod('email');
                    $override->setMinutes($recordatorio->minutos_antes);
                    $overrides[] = $override;
                }
                
                $reminders = new \Google\Service\Calendar\EventReminders();
                $reminders->setUseDefault(false);
                $reminders->setOverrides($overrides);
                $googleEvent->setReminders($reminders);
            }

            // Agregar recurrencia si aplica
            if ($evento->recurrencia !== 'ninguna' && $evento->recurrencia_hasta) {
                $googleEvent->setRecurrence($this->buildRecurrenceRule($evento));
            }

            // Insertar evento y enviar notificaciones a participantes
            $createdEvent = $service->events->insert(
                $this->calendarId, 
                $googleEvent,
                ['sendNotifications' => true] // Enviar invitaciones por correo
            );
            
            // Actualizar evento con el ID de Google
            $evento->update([
                'google_event_id' => $createdEvent->getId(),
                'sincronizado_google' => true,
                'ultima_sincronizacion' => now(),
            ]);

            return $createdEvent;
        } catch (\Exception $e) {
            \Log::error('Error al crear evento en Google Calendar: ' . $e->getMessage());
            throw $e;
        }
    }

    public function updateEvent(Evento $evento)
    {
        if (!config('google-calendar.sync_enabled') || !$this->isAuthenticated() || !$evento->google_event_id) {
            return null;
        }

        try {
            $service = $this->getCalendarService();
            
            // Extraer solo la parte de hora si es timestamp completo
            $horaInicio = strlen($evento->hora_inicio) > 8 
                ? date('H:i:s', strtotime($evento->hora_inicio))
                : $evento->hora_inicio;
            $horaFin = strlen($evento->hora_fin) > 8 
                ? date('H:i:s', strtotime($evento->hora_fin))
                : $evento->hora_fin;
            
            $googleEvent = $service->events->get($this->calendarId, $evento->google_event_id);
            
            $googleEvent->setSummary($evento->titulo);
            $googleEvent->setLocation($evento->lugar);
            $googleEvent->setDescription($this->buildDescription($evento));
            
            // Crear objetos EventDateTime correctamente
            $start = new EventDateTime();
            $start->setDateTime(Carbon::parse($evento->fecha->format('Y-m-d') . ' ' . $horaInicio)->toRfc3339String());
            $start->setTimeZone(config('app.timezone'));
            $googleEvent->setStart($start);
            
            $end = new EventDateTime();
            $end->setDateTime(Carbon::parse($evento->fecha->format('Y-m-d') . ' ' . $horaFin)->toRfc3339String());
            $end->setTimeZone(config('app.timezone'));
            $googleEvent->setEnd($end);
            
            // Actualizar participantes
            if ($evento->participantes->count() > 0) {
                $attendees = [];
                foreach ($evento->participantes as $participante) {
                    $attendees[] = ['email' => $participante->correo];
                }
                $googleEvent->setAttendees($attendees);
            } else {
                // Limpiar participantes si ya no hay
                $googleEvent->setAttendees([]);
            }
            
            // Actualizar recurrencia
            if ($evento->recurrencia !== 'ninguna' && $evento->recurrencia_hasta) {
                $googleEvent->setRecurrence($this->buildRecurrenceRule($evento));
            } else {
                // Limpiar recurrencia si se cambió a "ninguna"
                $googleEvent->setRecurrence(null);
            }

            // Actualizar evento y enviar notificaciones
            $updatedEvent = $service->events->update(
                $this->calendarId, 
                $evento->google_event_id, 
                $googleEvent,
                ['sendNotifications' => true] // Enviar notificaciones de cambios
            );
            
            $evento->update([
                'ultima_sincronizacion' => now(),
            ]);

            return $updatedEvent;
        } catch (\Exception $e) {
            \Log::error('Error al actualizar evento en Google Calendar: ' . $e->getMessage());
            throw $e;
        }
    }

    public function deleteEvent(Evento $evento)
    {
        if (!config('google-calendar.sync_enabled') || !$this->isAuthenticated() || !$evento->google_event_id) {
            return null;
        }

        try {
            $service = $this->getCalendarService();
            $service->events->delete($this->calendarId, $evento->google_event_id);
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Error al eliminar evento de Google Calendar: ' . $e->getMessage());
            throw $e;
        }
    }

    private function buildDescription(Evento $evento)
    {
        $description = $evento->notas ?? '';
        
        if ($evento->cliente) {
            $description .= "\n\nCliente: " . $evento->cliente->nombre;
        }
        
        if ($evento->participantes->count() > 0) {
            $description .= "\n\nParticipantes:\n";
            foreach ($evento->participantes as $participante) {
                $description .= "- " . $participante->nombre . " (" . $participante->correo . ")\n";
            }
        }

        return $description;
    }

    private function convertColorToGoogleColorId($hexColor)
    {
        // Google Calendar tiene 11 colores predefinidos
        // Mapear colores hex a IDs de Google Calendar
        $colorMap = [
            '#3B82F6' => '1', // Azul
            '#EF4444' => '11', // Rojo
            '#10B981' => '10', // Verde
            '#F59E0B' => '5', // Naranja
            '#8B5CF6' => '3', // Morado
        ];

        return $colorMap[$hexColor] ?? '1'; // Default azul
    }

    private function buildRecurrenceRule(Evento $evento)
    {
        // Mapear recurrencia español -> inglés para Google Calendar
        $freqMap = [
            'diaria' => 'DAILY',
            'semanal' => 'WEEKLY',
            'mensual' => 'MONTHLY',
            'anual' => 'YEARLY',
        ];
        
        $freq = $freqMap[$evento->recurrencia] ?? null;
        
        if (!$freq || $evento->recurrencia === 'ninguna') {
            return null;
        }

        // Formato: 20260203T235959Z (UTC)
        $until = Carbon::parse($evento->recurrencia_hasta)->endOfDay()->utc()->format('Ymd\THis\Z');
        
        $rule = "RRULE:FREQ={$freq};UNTIL={$until}";
        
        return [$rule];
    }

    public function syncFromGoogle()
    {
        if (!$this->isAuthenticated()) {
            return [];
        }

        try {
            $service = $this->getCalendarService();
            
            $optParams = [
                'maxResults' => 100,
                'orderBy' => 'startTime',
                'singleEvents' => true,
                'timeMin' => Carbon::now()->subMonths(1)->toRfc3339String(),
                'timeMax' => Carbon::now()->addMonths(3)->toRfc3339String(),
            ];

            $results = $service->events->listEvents($this->calendarId, $optParams);
            $events = $results->getItems();

            return $events;
        } catch (\Exception $e) {
            \Log::error('Error al sincronizar desde Google Calendar: ' . $e->getMessage());
            return [];
        }
    }
}
