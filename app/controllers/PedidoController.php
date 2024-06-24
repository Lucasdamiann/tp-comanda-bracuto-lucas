<?php

require_once './models/Pedido.php';
require_once './interfaces/IApiUsable.php';

class PedidoController extends Pedido implements IApiUsable
{
  public function CargarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();
    $cliente = $parametros['cliente'];
    $idMesa = $parametros['idMesa'];
    $ped = new Pedido();
    $ped->cliente = $cliente;
    $ped->idMesa = $idMesa;
    $ped->id = $ped->crearPedido();
    $payload = json_encode(array("mensaje" => "Pedido creado con exito"));
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerUno($request, $response, $args)
  {
    $ped = $args['id'];
    $pedido = Pedido::obtenerPedido($ped);
    $payload = json_encode($pedido);
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerNumeroPedido($request, $response, $args)
  {
    $parametros = $request->getQueryParams();
    $cliente = $parametros['cliente'];
    $idMesa = $parametros['idMesa'];
    $pedido = Pedido::obtenerNumeroPedidoPorClienteYMesa($cliente, $idMesa);
    $payload = json_encode(array("mensaje" => "El numero de Pedido es: " . $pedido));
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerTodos($request, $response, $args)
  {
    $lista = Pedido::obtenerTodos();
    $payload = json_encode(array("listaPedido" => $lista));
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function ModificarUno($request, $response, $args)
  {
    $id = $args['id'];
    $parametros = $request->getParsedBody();
    $cliente = $parametros['cliente'];
    $numeroPedido = $parametros['numeroPedido'];
    $idMesa = $parametros['idMesa'];
    $estado = $parametros['estado'];
    $pedidoMod = Pedido::obtenerPedido($id);
    if ($pedidoMod !== NULL && $cliente !== NULL && $estado !== NULL && $numeroPedido !== NULL && $idMesa !== NULL && $id !== NULL) {
      $pedidoMod->cliente = $cliente;
      $pedidoMod->estado = $estado;
      $pedidoMod->numeroPedido = $numeroPedido;
      $pedidoMod->idMesa = $idMesa;
      Pedido::modificarPedido($pedidoMod);
      $payload = json_encode(array("mensaje" => "Pedido modificado con exito"));
    } else {
      $payload = json_encode(array("mensaje" => "Pedido no se pudo modificar"));
    }
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function BorrarUno($request, $response, $args)
  {
    $id = $args['id'];
    Pedido::borrarPedido($id);
    $payload = json_encode(array("mensaje" => "Pedido borrado con exito"));
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public static function SacarFoto($request, $response, $args)
  {
    $id = $args['id'];
    $foto = $request->getUploadedFiles();
    $pedido = Pedido::ObtenerPedido($id);
    $mesa = Mesa::ObtenerMesa($pedido->idMesa);
    if ($pedido !== NULL && $mesa !== NULL && $foto !== NULL) {
      if (FilesManager::CargarFotoPedido($foto, $pedido, $mesa)) {
        $payload = json_encode(array("mensaje" => "Foto subida con exito"));
        $response->getBody()->write($payload);
      } else {
        $payload = json_encode(array("mensaje" => "ERROR: No se pudo subir la Foto"));
        $response->getBody()->write($payload);
      }
    } else {
      $payload = json_encode(array("mensaje" => "ERROR: Falla al obtener Pedido o Mesa"));
      $response->getBody()->write($payload);
    }
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public static function TraerTiempoRestante($request, $response, $args)
  {
    $parametros = $request->getQueryParams();
    $codigoMesa = $parametros['codigoMesa'];
    $numeroPedido = $parametros['numeroPedido'];
    $pedido = Pedido::obtenerTiempoRestante($codigoMesa, $numeroPedido);
    $tiemposPorEmpleado = [];
    foreach ($pedido as $tarea) {
      $idEmpleado = $tarea["idEmpleado"];
      $tiempoEstimado = $tarea["tiempoEstimado"];
      if (!isset($tiemposPorEmpleado[$idEmpleado])) {
        $tiemposPorEmpleado[$idEmpleado] = 0;
      }
      $tiemposPorEmpleado[$idEmpleado] += $tiempoEstimado;
    }
    $tiempo = max($tiemposPorEmpleado);
    $payload = json_encode(array("Tiempo estimado" => $tiempo . " minutos"));
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public static function TraerTodosLosPedidosConTiempoRestante($request, $response, $args)
  {
    $lista = Pedido::obtenerTodos();
    foreach($lista as $pedido){
      $numeroPedido = $pedido->numeroPedido;
      $mesa = Mesa::obtenerMesa($pedido->idMesa);
      $codigoMesa = $mesa->codigoMesa;
      $restante = Pedido::obtenerTiempoRestante($codigoMesa, $numeroPedido);
      $tiemposPorEmpleado = [];
      foreach ($restante as $tarea) {
        $idEmpleado = $tarea["idEmpleado"];
        $tiempoEstimado = $tarea["tiempoEstimado"];
        if (!isset($tiemposPorEmpleado[$idEmpleado])) {
          $tiemposPorEmpleado[$idEmpleado] = 0;
        }
        $tiemposPorEmpleado[$idEmpleado] += $tiempoEstimado;
      }
      $tiempo = max($tiemposPorEmpleado);
      $payload = json_encode(array("Pedido" => $pedido ,"Tiempo estimado" => $tiempo . " minutos"));
      $response->getBody()->write($payload);
    }
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerTodosListosParaServir($request, $response, $args)
  {
    $lista = Pedido::obtenerTodosListosParaServir();
    $payload = json_encode(array("Pedidos listos" => $lista));
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerMonto($request, $response, $args)
  {
    $id = $args['id'];
    $monto = Pedido::obtenerMontoPedido($id);
    $payload = json_encode(array("Cliente" => $monto->cliente,"Monto a pagar" =>$monto->PRECIO));
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
}
