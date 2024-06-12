<?php
require_once './models/Producto.php';
require_once './interfaces/IApiUsable.php';

class ProductoController extends Producto implements IApiUsable
{
  public function CargarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $tipo = $parametros['tipo'];
    $sector = $parametros['sector'];
    $precio = $parametros['precio'];
    // Creamos el Producto
    $prd = new Producto();
    $prd->tipo = $tipo;
    $prd->sector = $sector;
    $prd->precio = $precio;
    $prd->id = $prd->crearProducto();

    $payload = json_encode(array("mensaje" => "Producto creado con exito"));
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerUno($request, $response, $args)
  {
    $prd = $args['id'];
    $producto = Producto::obtenerProducto($prd);
    $payload = json_encode($producto);

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerTodos($request, $response, $args)
  {
    $lista = Producto::obtenerTodos();
    $payload = json_encode(array("listaProducto" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function ModificarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $id = $args['id'];
    $tipo = $parametros['tipo'];
    $sector = $parametros['sector'];
    $precio = $parametros['precio'];
    $tiempoEstimado = $parametros['tiempoEstimado'];
    $estado = $parametros['estado'];
    $prodMod = Producto::obtenerProducto($id);
    if ($prodMod !== NULL && $id !== NULL && $tipo !== NULL && $sector !== NULL && $precio !== NULL && $tiempoEstimado !== NULL && $estado !== NULL) {
      $prodMod->tipo = $tipo;
      $prodMod->tipo = $sector;
      $prodMod->tipo = $precio;
      $prodMod->tipo = $tiempoEstimado;
      $prodMod->tipo = $estado;
      Producto::modificarProducto($id);
      $payload = json_encode(array("mensaje" => "Producto modificado con exito"));
    }else{
      
      $payload = json_encode(array("mensaje" => "ERROR: No se pudo modificar el Producto"));
    }
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function BorrarUno($request, $response, $args)
  {
    $id = $args['id'];
    Producto::borrarProducto($id);

    $payload = json_encode(array("mensaje" => "Producto borrado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
}
