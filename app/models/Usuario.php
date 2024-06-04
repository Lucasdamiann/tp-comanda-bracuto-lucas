<?php
class Usuario
{
    protected $id;
    protected $usuario;
    protected $clave;
    protected $nombre;
    protected $sector;
    protected $puesto;
    protected $fechaLogIn;
    protected $estado;
    protected $fechaBaja;

    public function crearUsuario()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuarios (usuario, clave, nombre, sector, puesto, fechaLogIn, estado) VALUES (:usuario, :clave, :nombre, :sector, :puesto, :fechaLogIn, :estado)");
        $consulta->bindValue(':usuario', $this->nombre, PDO::PARAM_STR);
        $clave = password_hash($this->clave, PASSWORD_DEFAULT);
        $consulta->bindValue(':clave', $clave);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
        $consulta->bindValue(':puesto', $this->puesto, PDO::PARAM_STR);
        $consulta->bindValue(':fechaLogIn', date("Y-m-d H:i:s"), PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR); //estado se instancia?
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, usuario, clave, nombre, sector, puesto, fechaLogIn, estado FROM usuarios");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }

    public static function obtenerUsuario($usuario)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, usuario, clave, nombre, sector, puesto, fechaLogIn, estado FROM usuarios WHERE usuario = :usuario");
        $consulta->bindValue(':usuario', $usuario, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Usuario');
    }

    public static function modificarUsuario($usuario)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET usuario = :usuario, clave = :clave, nombre = :nombre, sector = :sector, puesto = :puesto, estado = :estado WHERE id = :id");
        $consulta->bindValue(':id', $usuario->id, PDO::PARAM_INT);
        $consulta->bindValue(':usuario', $usuario->usuario, PDO::PARAM_STR);
        $clave = password_hash($usuario->clave, PASSWORD_DEFAULT);
        $consulta->bindValue(':clave', $clave);
        $consulta->bindValue(':nombre', $usuario->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':sector', $usuario->sector, PDO::PARAM_STR);
        $consulta->bindValue(':puesto', $usuario->puesto, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $usuario->estado, PDO::PARAM_STR); //estado se modifica aca o aparte?
        $consulta->execute();
    }

    public static function borrarUsuario($usuarioId)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET fechaBaja = :fechaBaja WHERE id = :id");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':id', $usuarioId, PDO::PARAM_INT);
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }
}
