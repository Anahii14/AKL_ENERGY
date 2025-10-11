<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CotizacionEstadoActualizado extends Notification
{
    use Queueable;

    public function __construct(
        public string $tipo,         // 'grua' | 'electrico'
        public int $cotizacionId,
        public string $estado,       // 'aceptada' | 'rechazada'
        public string $motivo
    ) {}

    public function via($notifiable): array
    {
        return ['database']; // agrega 'mail' si luego configuras SMTP
    }

    public function toDatabase($notifiable): array
    {
        return [
            'tipo'          => $this->tipo,
            'cotizacion_id' => $this->cotizacionId,
            'estado'        => $this->estado,
            'motivo'        => $this->motivo,
            'mensaje'       => "Tu cotización de {$this->tipo} fue {$this->estado}.",
        ];
    }
}
