<?php

class Pedido
{
    protected $id;
    protected $pedido;
    protected $numeroPedido;
    protected $tiempoEstimado;
    protected $codigoMesa;
    protected $ventas;
    protected $estado;
    protected $fechaBaja;

    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (estado, pedido, numeroPedido, tiempoEstimado, codigoMesa, ventas) VALUES (:estado, :pedido, :numeroPedido, :tiempoEstimado, :codigoMesa, :ventas)");
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':pedido', $this->pedido, PDO::PARAM_INT);
        $consulta->bindValue(':numeroPedido', $this->numeroPedido, PDO::PARAM_INT);
        $consulta->bindValue(':tiempoEstimado', $this->tiempoEstimado, PDO::PARAM_INT);
        $consulta->bindValue(':codigoMesa', $this->codigoMesa, PDO::PARAM_INT);
        $consulta->bindValue(':ventas', $this->ventas, PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, estado, pedido, numeroPedido, tiempoEstimado, codigoMesa, ventas FROM pedidos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerPedido($numeroPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, estado, pedido, numeroPedido, tiempoEstimado, codigoMesa, ventas FROM Pedidos WHERE numeroPedido = :numeroPedido");
        $consulta->bindValue(':numeroPedido', $numeroPedido, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }

    public static function modificarPedido($estado, $pedido, $numeroPedido, $tiempoEstimado, $codigoMesa, $ventas, $id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET estado = :estado, pedido = :pedido, numeroPedido = :numeroPedido, tiempoEstimado = :tiempoEstimado, codigoMesa = :codigoMesa, ventas = :ventas WHERE id = :id");
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
        $consulta->bindValue(':pedido', $pedido, PDO::PARAM_STR);
        $consulta->bindValue(':numeroPedido', $numeroPedido, PDO::PARAM_INT);
        $consulta->bindValue(':tiempoEstimado', $tiempoEstimado, PDO::PARAM_INT);
        $consulta->bindValue(':codigoMesa', $codigoMesa, PDO::PARAM_INT);
        $consulta->bindValue(':ventas', $ventas, PDO::PARAM_INT);
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrarPedido($pedidoId)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET fechaBaja = :fechaBaja WHERE id = :id");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':id', $pedidoId, PDO::PARAM_INT);
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }
}
