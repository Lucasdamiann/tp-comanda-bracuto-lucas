<?php
require_once './models/Mesa.php';
require_once './interfaces/IApiUsable.php';

class MesaController extends Mesa implements IApiUsable
{
  public function CargarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $estado = $parametros['estado'];
    $codigoMesa = $parametros['codigoMesa'];

    // Creamos el Mesa
    $mesa = new Mesa();
    $mesa->estado = $estado;
    $mesa->codigoMesa = $codigoMesa;
    $mesa->id = $mesa->crearMesa();

    $payload = json_encode(array("mensaje" => "Mesa creado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerUno($request, $response, $args)
  {
    // Buscamos Mesa por nombre
    $mesa = $args['estado'];
    $mesa = Mesa::obtenerMesa($mesa);
    $payload = json_encode($mesa);

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerTodos($request, $response, $args)
  {
    $lista = Mesa::obtenerTodos();
    $payload = json_encode(array("listaMesa" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function ModificarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $estado = $parametros['estado'];
    $codigoMesa = $parametros['codigoMesa'];
    $id = $parametros['mesaId'];
    Mesa::modificarMesa($estado, $codigoMesa, $id);

    $payload = json_encode(array("mensaje" => "Mesa modificado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function BorrarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $id = $parametros['mesaId'];
    Mesa::borrarMesa($id);

    $payload = json_encode(array("mensaje" => "Mesa borrado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
}
