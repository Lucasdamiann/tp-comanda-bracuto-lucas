<?php

require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';

class UsuarioController extends Usuario implements IApiUsable
{
  public function CargarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();
    $usuario = $parametros['usuario'];
    $clave = $parametros['clave'];
    $sector = $parametros['sector'];
    $cargo = $parametros['cargo'];
    $estado = $parametros['estado'];
    
    // Creamos el usuario
    $usr = new Usuario();
    $usr->usuario = $usuario;
    $usr->clave = $clave;
    $usr->sector = $sector;
    $usr->cargo = $cargo;
    $usr->estado = $estado;
    $usr->id = $usr->crearUsuario();

    $payload = json_encode(array("mensaje" => "Usuario creado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerUno($request, $response, $args)
  {
    $usr = $args['id'];
    $usuarioGet = Usuario::obtenerUsuario($usr);
    $payload = json_encode(array("usuario: " => $usuarioGet));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerTodos($request, $response, $args)
  {
    $lista = Usuario::obtenerTodos();
    $payload = json_encode(array("listaUsuario" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function ModificarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();
    $id = $args['id'];
    $usuario = $parametros["usuario"];
    $clave = $parametros["clave"];
    $sector = $parametros["sector"];
    $cargo = $parametros["cargo"];
    $estado = $parametros["estado"];
    $usrMod = Usuario::obtenerUsuario($id);
    if ($usrMod !== NULL && $id !== NULL && $usuario !== NULL && $clave !== NULL && $sector !== NULL && $estado !== NULL) {
      $usrMod->usuario = $usuario;
      $usrMod->clave = $clave;
      $usrMod->sector = $sector;
      $usrMod->cargo = $cargo;
      $usrMod->estado = $estado;
      Usuario::modificarUsuario($usrMod);
      $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));
    } else {
      $payload = json_encode(array("mensaje" => "ERROR: No se pudo modificar el Usuario"));
    }
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function BorrarUno($request, $response, $args)
  {
    $id = $args['id'];
    Usuario::borrarUsuario($id);

    $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function LogIn($request, $response){
    $parametros = $request->getParsedBody();
    $usuario = $parametros["usuario"];
    $clave = $parametros["clave"];
    $usr = Usuario::obtenerUsuarioPorUsuarioYClave($usuario, $clave);
    if($usr){
      $data = array('usuario' => $usr->usuario, 'cargo' => $usr->cargo);
      $token = AutentificadorJWT::CrearToken($data);
      $payload = json_encode(array("jwt" => $token));
    }else{
      $payload = json_encode(array("mensaje" => "ERROR: Usuario y/o Contraseña incorrecta"));
    }
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
}
