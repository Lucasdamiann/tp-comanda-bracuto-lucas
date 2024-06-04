<?php
enum EstadoPedido {
    case PENDIENTE;
    case EN_PROCESO;
    case COMPLETADO;
    case CANCELADO;

    public function descripcion(): string {
        return match($this) {
            self::PENDIENTE => 'El pedido está pendiente.',
            self::EN_PROCESO => 'El pedido está en proceso.',
            self::COMPLETADO => 'El pedido se ha completado.',
            self::CANCELADO => 'El pedido ha sido cancelado.',
        };
    }
}

$estadoActual = EstadoPedido::EN_PROCESO;
echo $estadoActual->descripcion(); // Salida: El pedido está en proceso.
?>
