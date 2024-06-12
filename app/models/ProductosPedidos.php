<?php
class ProductosPedidos {

    public $id;
    public $idProducto;
    public $idPedido;
    public $fechaBaja;

    public function crearProductosPedidos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO productos_pedidos (idProducto, idPedido) VALUES (:idProducto, :idPedido)");
        $consulta->bindValue(':idProducto', $this->idProducto, PDO::PARAM_INT);
        $consulta->bindValue(':idPedido', $this->idPedido, PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, idPedido, idProducto FROM productos_pedidos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'ProductosPedidos');
    }

    public static function obtenerProductosPedidos($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, idPedido, idProducto, fechaBaja FROM productos_pedidos WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('ProductosPedidos');
    }

    public static function modificarProductosPedidos($pedido)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE productos_pedidos SET idPedido = :idPedido, idProducto = :idProducto WHERE id = :id");
        $consulta->bindValue(':idPedido', $pedido->idPedido, PDO::PARAM_INT);
        $consulta->bindValue(':idProducto', $pedido->idProducto, PDO::PARAM_INT);
        $consulta->bindValue(':id', $pedido->id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrarProductosPedidos($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE productos_pedidos SET fechaBaja = :fechaBaja WHERE id = :id");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }

}