<?php
class Sistema{

    public static function obtenerClaveSecreta()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT claveSecreta FROM sistema");
        $consulta->execute();

        return $consulta->fetchObject()->claveSecreta;
    }
}