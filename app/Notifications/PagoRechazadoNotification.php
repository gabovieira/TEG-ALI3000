<?php

namespace App\Notifications;

use App\Models\Pago;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PagoRechazadoNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $pago;

    /**
     * Create a new notification instance.
     */
    public function __construct(Pago $pago)
    {
        $this->pago = $pago;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = route('admin.pagos.show', $this->pago->id);
        $consultor = $this->pago->consultor;
        
        return (new MailMessage)
            ->subject('¡ALERTA! Pago Rechazado por Consultor - ' . $this->pago->nombre_quincena)
            ->greeting('Hola ' . $notifiable->primer_nombre . ',')
            ->line('Te informamos que el consultor ' . $consultor->primer_nombre . ' ' . $consultor->primer_apellido . ' ha reportado un problema con el pago correspondiente a la ' . $this->pago->nombre_quincena . '.')
            ->line('Monto: $' . number_format($this->pago->total_menos_islr_divisas, 2) . ' USD')
            ->line('Motivo del rechazo: ' . $this->pago->comentario_confirmacion)
            ->line('Por favor, contacta al consultor para resolver este problema lo antes posible.')
            ->action('Ver Detalles del Pago', $url)
            ->line('Este asunto requiere atención inmediata.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $consultor = $this->pago->consultor;
        
        return [
            'pago_id' => $this->pago->id,
            'quincena' => $this->pago->quincena,
            'nombre_quincena' => $this->pago->nombre_quincena,
            'monto_divisas' => $this->pago->total_menos_islr_divisas,
            'consultor_id' => $consultor->id,
            'consultor_nombre' => $consultor->primer_nombre . ' ' . $consultor->primer_apellido,
            'fecha_rechazo' => $this->pago->fecha_confirmacion->format('Y-m-d H:i:s'),
            'motivo' => $this->pago->comentario_confirmacion,
            'tipo' => 'pago_rechazado'
        ];
    }
}
