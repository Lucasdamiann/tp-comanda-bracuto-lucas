<?php
require_once './models/ProductosPedidos.php';
require_once './interfaces/IApiUsable.php';

class ProductosPedidosController extends ProductosPedidos implements IApiUsable
{
  public function CargarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();
    $idProducto = $parametros['idProducto'];
    $idPedido = $parametros['idPedido'];
    $ped = new ProductosPedidos();
    $ped->idProducto = $idProducto;
    $ped->idPedido = $idPedido;
    $ped->id = $ped->crearProductosPedidos();
    $payload = json_encode(array("mensaje" => "ProductosPedidos creados con exito"));
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerUno($request, $response, $args)
  {
    $ped = $args['id'];
    $productoPedido = ProductosPedidos::obtenerProductosPedidos($ped);
    $payload = json_encode($productoPedido);
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerTodos($request, $response, $args)
  {
    $lista = ProductosPedidos::obtenerTodos();
    $payload = json_encode(array("listaPedido" => $lista));
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function ModificarUno($request, $response, $args)
  {
    $id = $args['id'];
    $parametros = $request->getParsedBody();
    $idPedido = $parametros['idPedido'];
    $idProducto = $parametros['idProducto'];
    $pedidoMod = ProductosPedidos::obtenerProductosPedidos($id);
    if($pedidoMod !== NULL && $idPedido !== NULL && $idProducto !==NULL && $id !== NULL){
      $pedidoMod->idPedido = $idPedido;
      $pedidoMod->idProducto = $idProducto;
      ProductosPedidos::modificarProductosPedidos($pedidoMod);
      $payload = json_encode(array("mensaje" => "ProductosPedidos modificados con exito"));
    }else{
      $payload = json_encode(array("mensaje" => "ERROR: No se pudo modificar ProductosPedidos"));
    }
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function BorrarUno($request, $response, $args)
  {
    $id = $args['id'];
    ProductosPedidos::borrarProductosPedidos($id);
    $payload = json_encode(array("mensaje" => "ProductosPedidos borrado con exito"));
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
}
