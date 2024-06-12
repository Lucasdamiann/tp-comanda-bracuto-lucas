<?php
class Producto
{
    public $id;
    public $tipo;
    public $sector;
    public $precio;
    public $tiempoEstimado;
    public $estado;
    public $fechaBaja;

    public function crearProducto()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO productos (tipo, sector, precio) VALUES (:tipo, :sector, :precio)");
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, tipo, sector, precio, tiempoEstimado, estado FROM productos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }

    public static function obtenerProducto($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, tipo, sector, precio, tiempoEstimado, estado FROM productos WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Producto');
    }

    public static function modificarProducto($producto)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE productos SET tipo = :tipo, sector = :sector, precio = :precio, tiempoEstimado = :tiempoEstimado, estado = :estado WHERE id = :id");
        $consulta->bindValue(':tipo', $producto->tipo, PDO::PARAM_STR);
        $consulta->bindValue(':sector', $producto->sector, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $producto->precio, PDO::PARAM_INT);
        $consulta->bindValue(':tiempoEstimado', $producto->tiempoEstimado, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $producto->estado, PDO::PARAM_STR);
        $consulta->bindValue(':id', $producto->id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrarProducto($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE productos SET fechaBaja = :fechaBaja WHERE id = :id");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }
}
