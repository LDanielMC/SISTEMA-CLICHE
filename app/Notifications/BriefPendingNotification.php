<?php

namespace App\Notifications;

use App\Models\Brief;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BriefPendingNotification extends Notification
{
    use Queueable;

    public $brief;

    /**
     * Create a new notification instance.
     */
    public function __construct(Brief $brief)
    {
        $this->brief = $brief;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'titulo' => 'Recordatorio de Brief Pendiente',
            'mensaje' => 'No olvides completar el formulario: ' . $this->brief->titulo,
            'url' => $this->brief->form_url,
            'brief_id' => $this->brief->id,
            'fecha' => now(),
            'tipo' => 'alerta'
        ];
    }
}
