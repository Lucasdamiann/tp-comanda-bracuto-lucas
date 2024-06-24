<?php
class Encuesta{

    public $id;
    public $mesa;
    public $codigoMesa;
    public $restaurante;
    public $mozo;
    public $cocinero;
    public $numeroPedido;
    public $resenia;
    public $fechaBaja;
    
    public function crearEncuesta()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO encuesta (mesa, codigoMesa, restaurante, mozo, cocinero, numeroPedido, resenia) VALUES (:mesa, :codigoMesa, :restaurante, :mozo, :cocinero, :numeroPedido, :resenia)");
        $consulta->bindValue(':mesa', $this->mesa, PDO::PARAM_INT);
        $consulta->bindValue(':codigoMesa', $this->codigoMesa, PDO::PARAM_STR);
        $consulta->bindValue(':restaurante', $this->restaurante, PDO::PARAM_INT);
        $consulta->bindValue(':mozo', $this->mozo, PDO::PARAM_INT);
        $consulta->bindValue(':cocinero', $this->cocinero, PDO::PARAM_INT);
        $consulta->bindValue(':numeroPedido', $this->numeroPedido, PDO::PARAM_STR);
        $consulta->bindValue(':resenia', $this->resenia, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, mesa, codigoMesa, restaurante, mozo, cocinero, numeroPedido, resenia FROM encuesta");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Encuesta');
    }

    public static function obtenerEncuesta($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, mesa, codigoMesa, restaurante, mozo, cocinero, numeroPedido, resenia FROM encuesta WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Encuesta');
    }

    public static function modificarEncuesta($encuesta)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE encuesta SET mesa = :mesa, codigoMesa = :codigoMesa, restaurante = :restaurante, mozo = :mozo, cocinero = :cocinero, numeroPedido = :numeroPedido, resenia = :resenia WHERE id = :id");
        $consulta->bindValue(':mesa', $encuesta->mesa, PDO::PARAM_INT);
        $consulta->bindValue(':codigoMesa', $encuesta->codigoMesa, PDO::PARAM_STR);
        $consulta->bindValue(':restaurante', $encuesta->restaurante, PDO::PARAM_INT);
        $consulta->bindValue(':mozo', $encuesta->mozo, PDO::PARAM_INT);
        $consulta->bindValue(':cocinero', $encuesta->cocinero, PDO::PARAM_INT);
        $consulta->bindValue(':numeroPedido', $encuesta->numeroPedido, PDO::PARAM_STR);
        $consulta->bindValue(':resenia', $encuesta->resenia, PDO::PARAM_INT);
        $consulta->bindValue(':id', $encuesta->id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrarEncuesta($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE encuesta SET fechaBaja = :fechaBaja WHERE id = :id");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }

    public static function obtenerBuenosComentarios(){
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("SELECT mesa, restaurante, mozo, cocinero, resenia FROM encuesta WHERE (mesa+restaurante+mozo+cocinero)/4 > 5");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS);

    }

    public static function obtenerMalosComentarios(){
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("SELECT mesa, restaurante, mozo, cocinero, resenia FROM encuesta WHERE (mesa+restaurante+mozo+cocinero)/4 <= 5");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS);

    }
}