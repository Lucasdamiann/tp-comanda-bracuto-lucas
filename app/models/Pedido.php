<?php
class Pedido
{
    public $id;
    public $cliente;
    public $numeroPedido;
    public $idMesa;
    public $estado;
    public $fechaBaja;

    public function crearPedido()
    {
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (cliente, numeroPedido, idMesa, fechaCreacion) VALUES (:cliente, :numeroPedido, :idMesa, :fechaCreacion)");
        $numero = self::GenerarCodigoAlfanumericoAleatorio();
        $consulta->bindValue(':cliente', $this->cliente, PDO::PARAM_STR);
        $consulta->bindValue(':numeroPedido', $numero, PDO::PARAM_STR);
        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':fechaCreacion', date("Y-m-d H:i:s"), PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function GenerarCodigoAlfanumericoAleatorio($cantidad = 5)
    {
        $caracteres = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $codigo = '';
        for ($i = 0; $i < $cantidad; $i++) {
            $codigo .= $caracteres[rand(0, strlen($caracteres) - 1)];
        }
        return $codigo;
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, cliente, numeroPedido, idMesa, estado FROM pedidos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerPedido($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, cliente, numeroPedido, idMesa, estado, fechaBaja FROM Pedidos WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }

    public static function modificarPedido($pedido)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET cliente = :cliente, numeroPedido = :numeroPedido, idMesa = :idMesa, estado = :estado WHERE id = :id");
        $consulta->bindValue(':cliente', $pedido->cliente, PDO::PARAM_STR);
        $consulta->bindValue(':numeroPedido', $pedido->numeroPedido, PDO::PARAM_INT);
        $consulta->bindValue(':idMesa', $pedido->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $pedido->estado, PDO::PARAM_STR);
        $consulta->bindValue(':id', $pedido->id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrarPedido($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET fechaBaja = :fechaBaja WHERE id = :id");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }

    public static function ModificarFoto($id, $foto)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta('UPDATE pedidos SET foto = :foto WHERE id = :id');
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':foto', $foto, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function obtenerNumeroPedidoPorClienteYMesa($cliente, $idMesa)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("SELECT numeroPedido FROM pedidos WHERE cliente = :cliente AND idMesa = :idMesa");
        $consulta->bindValue(':cliente', $cliente, PDO::PARAM_STR);
        $consulta->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
        $consulta->execute();
        $resultado = $consulta->fetchObject('Pedido');

        return $resultado->numeroPedido;
    }

    public static function obtenerTiempoRestante($codigoMesa, $numeroPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idEmpleado, tiempoEstimado FROM productos_pedidos INNER JOIN pedidos ON productos_pedidos.idPedido = pedidos.id AND pedidos.numeroPedido = :numeroPedido INNER JOIN mesas ON pedidos.idMesa = mesas.id AND mesas.codigoMesa = :codigoMesa");
        $consulta->bindValue(':numeroPedido', $numeroPedido, PDO::PARAM_STR);
        $consulta->bindValue(':codigoMesa', $codigoMesa, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function obtenerTodosListosParaServir()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, cliente, numeroPedido, idMesa, estado FROM pedidos WHERE estado = 'listo para servir'");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerMontoPedido($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT pedidos.cliente, SUM(productos.precio) AS PRECIO FROM productos INNER JOIN productos_pedidos ON productos.id = productos_pedidos.idProducto INNER JOIN pedidos ON pedidos.id = productos_pedidos.idPedido WHERE pedidos.id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject();
    }
}
