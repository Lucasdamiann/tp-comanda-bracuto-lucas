<?php
class Mesa
{
    public $id;
    public $estado;
    public $codigoMesa;
    public $fechaBaja;

    public function crearMesa()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO mesas (estado, codigoMesa) VALUES (:estado, :codigoMesa)");

        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':codigoMesa', $this->codigoMesa, PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, estado, codigoMesa FROM mesas");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }

    public static function obtenerMesa($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, estado, codigoMesa FROM mesas WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Mesa');
    }

    public static function modificarMesa($mesa)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas SET estado = :estado, codigoMesa = :codigoMesa WHERE id = :id");
        $consulta->bindValue(':estado', $mesa->estado, PDO::PARAM_STR);
        $consulta->bindValue(':codigoMesa', $mesa->codigoMesa, PDO::PARAM_STR);
        $consulta->bindValue(':id', $mesa->id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrarMesa($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas SET fechaBaja = :fechaBaja WHERE id = :id");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }

    public static function obtenerMesaMasUsada($fechaInicial, $fechaFinal)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT mesas.id AS ID, mesas.codigoMesa AS CodigoMesa, COUNT(pedidos.idMesa) AS Cantidad FROM mesas INNER JOIN pedidos ON pedidos.idMesa = mesas.id WHERE pedidos.fechaCreacion BETWEEN :fechaInicial AND :fechaFinal GROUP BY mesas.id, mesas.codigoMesa ORDER BY Cantidad DESC LIMIT 1");
        $consulta->bindValue(':fechaInicial', $fechaInicial, PDO::PARAM_STR);
        $consulta->bindValue(':fechaFinal', $fechaFinal, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject();
    }

    public static function obtenerMesaMenosUsada($fechaInicial, $fechaFinal)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT mesas.id AS ID, mesas.codigoMesa AS CodigoMesa, COUNT(pedidos.idMesa) AS Cantidad FROM mesas INNER JOIN pedidos ON pedidos.idMesa = mesas.id WHERE pedidos.fechaCreacion BETWEEN :fechaInicial AND :fechaFinal GROUP BY mesas.id, mesas.codigoMesa ORDER BY Cantidad ASC LIMIT 1");
        $consulta->bindValue(':fechaInicial', $fechaInicial, PDO::PARAM_STR);
        $consulta->bindValue(':fechaFinal', $fechaFinal, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject();
    }

    public static function obtenerMesaQueMasFacturo($fechaInicial, $fechaFinal)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT mesas.id AS ID, mesas.codigoMesa AS CodigoMesa, SUM(productos.precio) AS TotalGasto FROM mesas INNER JOIN pedidos ON pedidos.idMesa = mesas.id INNER JOIN productos_pedidos ON productos_pedidos.idPedido = pedidos.id INNER JOIN productos ON productos_pedidos.idProducto = productos.id WHERE productos_pedidos.estado = 'listo para servir' AND pedidos.fechaCreacion BETWEEN :fechaInicial AND :fechaFinal GROUP BY mesas.id, mesas.codigoMesa ORDER BY TotalGasto DESC LIMIT 1");
        $consulta->bindValue(':fechaInicial', $fechaInicial, PDO::PARAM_STR);
        $consulta->bindValue(':fechaFinal', $fechaFinal, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject();
    }

    public static function obtenerMesaQueMenosFacturo($fechaInicial, $fechaFinal)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT mesas.id AS ID, mesas.codigoMesa AS CodigoMesa, SUM(productos.precio) AS TotalGasto FROM mesas INNER JOIN pedidos ON pedidos.idMesa = mesas.id INNER JOIN productos_pedidos ON productos_pedidos.idPedido = pedidos.id INNER JOIN productos ON productos_pedidos.idProducto = productos.id WHERE productos_pedidos.estado = 'listo para servir' AND pedidos.fechaCreacion BETWEEN :fechaInicial AND :fechaFinal GROUP BY mesas.id, mesas.codigoMesa ORDER BY TotalGasto ASC LIMIT 1");
        $consulta->bindValue(':fechaInicial', $fechaInicial, PDO::PARAM_STR);
        $consulta->bindValue(':fechaFinal', $fechaFinal, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject();
    }

    public static function obtenerMesaConFacturaDeMayorImporte($fechaInicial, $fechaFinal)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT mesas.id AS ID, mesas.codigoMesa AS CodigoMesa, pedidos.id AS PedidoID, SUM(productos.precio) AS TotalGasto FROM mesas INNER JOIN pedidos ON pedidos.idMesa = mesas.id INNER JOIN productos_pedidos ON productos_pedidos.idPedido = pedidos.id INNER JOIN productos ON productos_pedidos.idProducto = productos.id WHERE productos_pedidos.estado = 'listo para servir' AND pedidos.fechaCreacion BETWEEN :fechaInicial AND :fechaFinal GROUP BY mesas.id, mesas.codigoMesa, pedidos.id ORDER BY TotalGasto DESC LIMIT 1");
        $consulta->bindValue(':fechaInicial', $fechaInicial, PDO::PARAM_STR);
        $consulta->bindValue(':fechaFinal', $fechaFinal, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject();
    }

    public static function obtenerMesaConFacturaDeMenorImporte($fechaInicial, $fechaFinal)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT mesas.id AS ID, mesas.codigoMesa AS CodigoMesa, pedidos.id AS PedidoID, SUM(productos.precio) AS TotalGasto FROM mesas INNER JOIN pedidos ON pedidos.idMesa = mesas.id INNER JOIN productos_pedidos ON productos_pedidos.idPedido = pedidos.id INNER JOIN productos ON productos_pedidos.idProducto = productos.id WHERE productos_pedidos.estado = 'listo para servir' AND pedidos.fechaCreacion BETWEEN :fechaInicial AND :fechaFinal GROUP BY mesas.id, mesas.codigoMesa, pedidos.id ORDER BY TotalGasto ASC LIMIT 1");
        $consulta->bindValue(':fechaInicial', $fechaInicial, PDO::PARAM_STR);
        $consulta->bindValue(':fechaFinal', $fechaFinal, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject();
    }

    public static function obtenerMesaLoQueFacturoEntreFechas($fechaInicial, $fechaFinal, $id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT mesas.id AS ID, mesas.codigoMesa AS CodigoMesa, SUM(productos.precio) AS TotalFacturado FROM mesas INNER JOIN pedidos ON pedidos.idMesa = mesas.id INNER JOIN productos_pedidos ON productos_pedidos.idPedido = pedidos.id INNER JOIN productos ON productos_pedidos.idProducto = productos.id WHERE pedidos.fechaCreacion BETWEEN :fechaInicial AND :fechaFinal AND mesas.id = :id AND productos_pedidos.estado = 'listo para servir' GROUP BY mesas.id, mesas.codigoMesa");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':fechaInicial', $fechaInicial, PDO::PARAM_STR);
        $consulta->bindValue(':fechaFinal', $fechaFinal, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject();
    }
}
