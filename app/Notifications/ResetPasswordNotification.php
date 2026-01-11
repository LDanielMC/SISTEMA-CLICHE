<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    /**
     * El token para restablecer la contraseña.
     *
     * @var string
     */
    public $token;

    /**
     * Create a new notification instance.
     * Recibimos el token aquí para poder usarlo en el enlace.
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // Generamos la URL segura para el reset
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('🔐 Recuperación de acceso | SGI Cliché') // Asunto personalizado
            ->greeting('¡Hola!') // Saludo amigable
            ->line('Hemos recibido una solicitud para actualizar las credenciales de acceso a tu cuenta en el Sistema de Gestión Interna de Cliché Marketing Digital.')
            ->line('Entendemos lo importante que es mantener tu flujo de trabajo, por lo que puedes restablecer tu contraseña haciendo clic en el siguiente botón:')
            ->action('Crear Nueva Contraseña', $url) // El botón
            ->line('⚠️ Por seguridad, este enlace tiene una validez de 60 minutos.')
            ->line('Si no solicitaste este cambio, puedes ignorar este mensaje de forma segura. Tu cuenta permanece protegida.')
            ->salutation("Saludos cordiales,\nEl equipo de Tecnología de Cliché");
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}