<?php

class Historial{

    public static function crearRegistroHistorial($usuario)
    {
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO historial (idEmpleado, usuario, fechaLogIn) VALUES (:idEmpleado, :usuario, :fechaLogIn)");
        $consulta->bindValue(':idEmpleado', $usuario->id, PDO::PARAM_STR);
        $consulta->bindValue(':usuario', $usuario->usuario, PDO::PARAM_STR);
        $consulta->bindValue(':fechaLogIn', date("Y-m-d H:i:s"), PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, idEmpleado, usuario, fechaLogIn FROM historial");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Historial');
    }

    public static function obtenerTodosEntreFechas($fechaInicial, $fechaFinal)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, idEmpleado, usuario, fechaLogIn FROM historial WHERE fechaLogIn BETWEEN :fechaInicial AND :fechaFinal");
        $consulta->bindValue(':fechaInicial', $fechaInicial, PDO::PARAM_STR);
        $consulta->bindValue(':fechaFinal', $fechaFinal, PDO::PARAM_STR);

        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS);
    }

    public static function obtenerUno($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, idEmpleado, usuario, fechaLogIn FROM historial WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Encuesta');
    }

    public static function modificarHistorial($usuario, $id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE historial SET idEmpleado = :idEmpleado, usuario = :usuario, fechaLogIn = :fechaLogIn WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':idEmpleado', $usuario->idEmpleado, PDO::PARAM_INT);
        $consulta->bindValue(':usuario', $usuario->usuario, PDO::PARAM_STR);
        $consulta->bindValue(':fechaLogIn', $usuario->fechaLogIn, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function borrarUsuario($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE historial SET fechaBaja = :fechaBaja WHERE id = :id");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }
}