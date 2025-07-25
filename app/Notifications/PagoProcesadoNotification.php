<?php

namespace App\Notifications;

use App\Models\Pago;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PagoProcesadoNotification extends Notification implements ShouldQueue
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
        $url = route('consultor.pagos.show', $this->pago->id);
        
        return (new MailMessage)
            ->subject('Pago Procesado - ' . $this->pago->nombre_quincena)
            ->greeting('Hola ' . $notifiable->primer_nombre . ',')
            ->line('Te informamos que se ha procesado un pago correspondiente a la ' . $this->pago->nombre_quincena . '.')
            ->line('Monto: $' . number_format($this->pago->total_menos_islr_divisas, 2) . ' USD')
            ->line('Equivalente a: Bs. ' . number_format($this->pago->total_menos_islr_bs, 2))
            ->action('Ver Detalles del Pago', $url)
            ->line('Por favor, verifica que hayas recibido este pago en tu cuenta bancaria y confírmalo en el sistema.')
            ->line('Gracias por tu colaboración.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'pago_id' => $this->pago->id,
            'quincena' => $this->pago->quincena,
            'nombre_quincena' => $this->pago->nombre_quincena,
            'monto_divisas' => $this->pago->total_menos_islr_divisas,
            'monto_bs' => $this->pago->total_menos_islr_bs,
            'empresa' => $this->pago->empresa->nombre,
            'fecha_procesado' => $this->pago->fecha_procesado->format('Y-m-d H:i:s'),
            'tipo' => 'pago_procesado'
        ];
    }
}
