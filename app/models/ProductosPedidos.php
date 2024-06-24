<?php
class ProductosPedidos
{

    public $id;
    public $idProducto;
    public $idPedido;
    public $idEmpleado;
    public $estado;
    public $tiempoEstimado;
    public $fechaTomado;
    public $fechaEntregado;
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
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, idPedido, idProducto, idEmpleado FROM productos_pedidos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'ProductosPedidos');
    }

    public static function obtenerProductosPedidos($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, idPedido, idProducto, idEmpleado, estado, tiempoEstimado, fechaTomado, fechaBaja FROM productos_pedidos WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('ProductosPedidos');
    }

    public static function obtenerProductosPedidosPorSector($sector)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT productos_pedidos.id, idPedido, productos.tipo, idEmpleado, productos_pedidos.estado, tiempoEstimado, sector, productos_pedidos.fechaBaja FROM productos_pedidos JOIN productos ON productos_pedidos.idProducto = productos.id WHERE sector = :sector AND productos_pedidos.estado = 'pendiente'");
        $consulta->bindValue(':sector', $sector, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS);
    }

    public static function modificarProductosPedidos($pedido)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE productos_pedidos SET idPedido = :idPedido, idProducto = :idProducto, idEmpleado= :idEmpleado, estado = :estado, tiempoEstimado = :tiempoEstimado, fechaTomado = :fechaTomado, fechaEntregado = :fechaEntregado WHERE id = :id");
        $consulta->bindValue(':idPedido', $pedido->idPedido, PDO::PARAM_INT);
        $consulta->bindValue(':idProducto', $pedido->idProducto, PDO::PARAM_INT);
        $consulta->bindValue(':idEmpleado', $pedido->idEmpleado, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $pedido->estado, PDO::PARAM_STR);
        $consulta->bindValue(':tiempoEstimado', $pedido->tiempoEstimado, PDO::PARAM_INT);
        $consulta->bindValue(':fechaTomado', $pedido->fechaTomado, PDO::PARAM_STR);
        $consulta->bindValue(':fechaEntregado', $pedido->fechaEntregado, PDO::PARAM_STR);
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

    public static function obtenerTodosLasOperaciones($fechaInicial, $fechaFinal)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT usuarios.sector AS Sector, COUNT(productos_pedidos.id) AS Operaciones FROM productos_pedidos JOIN usuarios ON productos_pedidos.idEmpleado = usuarios.id WHERE productos_pedidos.fechaTomado BETWEEN :fechaInicial AND :fechaFinal GROUP BY usuarios.sector");
        $consulta->bindValue(':fechaInicial', $fechaInicial, PDO::PARAM_STR);
        $consulta->bindValue(':fechaFinal', $fechaFinal, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS);
    }

    public static function obtenerTodosLasOperacionesPorSector($sector, $fechaInicial, $fechaFinal)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT usuarios.usuario, usuarios.sector, COUNT(productos_pedidos.id) AS Operaciones FROM productos_pedidos JOIN usuarios ON productos_pedidos.idEmpleado = usuarios.id WHERE usuarios.sector = :sector AND productos_pedidos.fechaTomado BETWEEN :fechaInicial AND :fechaFinal GROUP BY usuarios.sector, usuarios.usuario ORDER BY usuarios.sector, usuarios.usuario");
        $consulta->bindValue(':sector', $sector, PDO::PARAM_INT);
        $consulta->bindValue(':fechaInicial', $fechaInicial, PDO::PARAM_STR);
        $consulta->bindValue(':fechaFinal', $fechaFinal, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS);
    }

    public static function obtenerTodasLasOperacionesPorEmpleado($fechaInicial, $fechaFinal)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT usuarios.usuario AS Empleado, COUNT(productos_pedidos.id) AS Operaciones FROM productos_pedidos JOIN usuarios ON productos_pedidos.idEmpleado = usuarios.id WHERE productos_pedidos.fechaTomado BETWEEN :fechaInicial AND :fechaFinal GROUP BY usuarios.usuario ORDER BY usuarios.usuario");
        $consulta->bindValue(':fechaInicial', $fechaInicial, PDO::PARAM_STR);
        $consulta->bindValue(':fechaFinal', $fechaFinal, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS);
    }
    
    public static function obtenerElProductoMasVendido($fechaInicial, $fechaFinal)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT productos.tipo AS Producto, COUNT(productos_pedidos.idProducto) AS Cantidad FROM productos_pedidos JOIN productos ON productos_pedidos.idProducto = productos.id WHERE productos_pedidos.fechaTomado BETWEEN :fechaInicial AND :fechaFinal GROUP BY productos_pedidos.idProducto, productos.tipo ORDER BY Cantidad DESC LIMIT 1");
        $consulta->bindValue(':fechaInicial', $fechaInicial, PDO::PARAM_STR);
        $consulta->bindValue(':fechaFinal', $fechaFinal, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS);
    }

    public static function obtenerElProductoMenosVendido($fechaInicial, $fechaFinal)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT productos.tipo AS Producto, COUNT(productos_pedidos.idProducto) AS Cantidad FROM productos_pedidos JOIN productos ON productos_pedidos.idProducto = productos.id WHERE productos_pedidos.fechaTomado BETWEEN :fechaInicial AND :fechaFinal GROUP BY productos_pedidos.idProducto, productos.tipo ORDER BY Cantidad ASC LIMIT 3");
        $consulta->bindValue(':fechaInicial', $fechaInicial, PDO::PARAM_STR);
        $consulta->bindValue(':fechaFinal', $fechaFinal, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS);
    }

    public static function obtenerProductosFueraDeTiempo($fechaInicial, $fechaFinal)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT productos_pedidos.idPedido, productos_pedidos.idProducto, productos.tipo AS Producto FROM productos_pedidos JOIN productos ON productos_pedidos.idProducto = productos.id WHERE DATE_ADD(productos_pedidos.fechaTomado, INTERVAL productos_pedidos.tiempoEstimado MINUTE) < productos_pedidos.fechaEntregado AND productos_pedidos.fechaTomado BETWEEN :fechaInicial AND :fechaFinal  GROUP BY productos_pedidos.idProducto, productos.tipo");
        $consulta->bindValue(':fechaInicial', $fechaInicial, PDO::PARAM_STR);
        $consulta->bindValue(':fechaFinal', $fechaFinal, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS);
    }

    public static function obtenerProductosCancelados($fechaInicial, $fechaFinal)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT productos_pedidos.idPedido, productos_pedidos.idProducto, productos.tipo AS Producto FROM productos_pedidos JOIN productos ON productos_pedidos.idProducto = productos.id WHERE productos_pedidos.estado = 'cancelado' AND productos_pedidos.fechaTomado BETWEEN :fechaInicial AND :fechaFinal  GROUP BY productos_pedidos.idProducto, productos.tipo");
        $consulta->bindValue(':fechaInicial', $fechaInicial, PDO::PARAM_STR);
        $consulta->bindValue(':fechaFinal', $fechaFinal, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS);
    }
}
