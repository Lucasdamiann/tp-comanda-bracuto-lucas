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
    $mesa = $args['id'];
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

    $id = $args['id'];
    $estado = $parametros['estado'];
    $codigoMesa = $parametros['codigoMesa'];
    $mesaMod = Mesa::obtenerMesa($id);
    if($mesaMod !== NULL && $id !== NULL && $estado !== NULL && $codigoMesa !== NULL){
      $mesaMod->estado = $estado;
      $mesaMod->codigoMesa = $codigoMesa;
      Mesa::modificarMesa($mesaMod);
      $payload = json_encode(array("mensaje" => "Mesa modificado con exito"));
    }else{
      $payload = json_encode(array("mensaje" => "ERROR: No se pudo modificar la Mesa"));
    }
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function ModificarEstadoClienteEsperando($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $id = $parametros['idMesa'];
    $mesaMod = Mesa::obtenerMesa($id);
    if($mesaMod !== NULL && $id !== NULL){
      $mesaMod->estado = "con cliente esperando pedido";
      Mesa::modificarMesa($mesaMod);
      $payload = json_encode(array("mensaje" => "Mesa modificada con exito"));
    }else{
      $payload = json_encode(array("mensaje" => "ERROR: No se pudo modificar la Mesa"));
    }
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function ModificarEstadoClienteComiendo($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $id = $parametros['idMesa'];
    $mesaMod = Mesa::obtenerMesa($id);
    if($mesaMod !== NULL && $id !== NULL){
      $mesaMod->estado = "con cliente comiendo";
      Mesa::modificarMesa($mesaMod);
      $payload = json_encode(array("mensaje" => "Mesa modificada con exito"));
    }else{
      $payload = json_encode(array("mensaje" => "ERROR: No se pudo modificar la Mesa"));
    }
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function ModificarEstadoClientePagando($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $id = $parametros['idMesa'];
    $mesaMod = Mesa::obtenerMesa($id);
    if($mesaMod !== NULL && $id !== NULL){
      $mesaMod->estado = "con cliente pagando";
      Mesa::modificarMesa($mesaMod);
      $payload = json_encode(array("mensaje" => "Mesa modificada con exito"));
    }else{
      $payload = json_encode(array("mensaje" => "ERROR: No se pudo modificar la Mesa"));
    }
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function ModificarEstadoCerrada($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $id = $parametros['idMesa'];
    $mesaMod = Mesa::obtenerMesa($id);
    if($mesaMod !== NULL && $id !== NULL){
      $mesaMod->estado = "cerrada";
      Mesa::modificarMesa($mesaMod);
      $payload = json_encode(array("mensaje" => "Mesa modificada con exito"));
    }else{
      $payload = json_encode(array("mensaje" => "ERROR: No se pudo modificar la Mesa"));
    }
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function BorrarUno($request, $response, $args)
  {
    $id = $args['id'];
    Mesa::borrarMesa($id);

    $payload = json_encode(array("mensaje" => "Mesa borrada con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerMesaMasUsada($request, $response, $args){
    $parametros = $request->getQueryParams();
    $fechaInicial = $parametros['fechaInicial'];
    $fechaFinal = $parametros['fechaFinal'];
    $mesa = Mesa::obtenerMesaMasUsada($fechaInicial, $fechaFinal);
    $payload = json_encode(array("Mesa más usada" => $mesa));
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerMesaMenosUsada($request, $response, $args){
    $parametros = $request->getQueryParams();
    $fechaInicial = $parametros['fechaInicial'];
    $fechaFinal = $parametros['fechaFinal'];
    $mesa = Mesa::obtenerMesaMenosUsada($fechaInicial, $fechaFinal);
    $payload = json_encode(array("Mesa menos usada" => $mesa));
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerMesaQueMasFacturo($request, $response, $args){
    $parametros = $request->getQueryParams();
    $fechaInicial = $parametros['fechaInicial'];
    $fechaFinal = $parametros['fechaFinal'];
    $mesa = Mesa::obtenerMesaQueMasFacturo($fechaInicial, $fechaFinal);
    $payload = json_encode(array("Mesa que mas facturo" => $mesa));
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
  
  public function TraerMesaQueMenosFacturo($request, $response, $args){
    $parametros = $request->getQueryParams();
    $fechaInicial = $parametros['fechaInicial'];
    $fechaFinal = $parametros['fechaFinal'];
    $mesa = Mesa::obtenerMesaQueMenosFacturo($fechaInicial, $fechaFinal);
    $payload = json_encode(array("Mesa que menos facturo" => $mesa));
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerMesaConFacturaDeMayorImporte($request, $response, $args){
    $parametros = $request->getQueryParams();
    $fechaInicial = $parametros['fechaInicial'];
    $fechaFinal = $parametros['fechaFinal'];
    $mesa = Mesa::obtenerMesaConFacturaDeMayorImporte($fechaInicial, $fechaFinal);
    $payload = json_encode(array("Mesa con la factura de mayor importe" => $mesa));
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerMesaConFacturaDeMenorImporte($request, $response, $args){
    $parametros = $request->getQueryParams();
    $fechaInicial = $parametros['fechaInicial'];
    $fechaFinal = $parametros['fechaFinal'];
    $mesa = Mesa::obtenerMesaConFacturaDeMenorImporte($fechaInicial, $fechaFinal);
    $payload = json_encode(array("Mesa con la factura de menor importe" => $mesa));
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerMesaLoQueFacturoEntreFechas($request, $response, $args){
    $parametros = $request->getQueryParams();
    $id = $parametros['id'];
    $fechaInicial = $parametros['fechaInicial'];
    $fechaFinal = $parametros['fechaFinal'];
    $mesa = Mesa::obtenerMesaLoQueFacturoEntreFechas($fechaInicial, $fechaFinal, $id);
    $payload = json_encode(array("Facturacion entre $fechaInicial y $fechaFinal" => $mesa));
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
}
