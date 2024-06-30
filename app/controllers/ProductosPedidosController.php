<?php
require_once './models/ProductosPedidos.php';
require_once './interfaces/IApiUsable.php';
require_once './middleware/AuthMiddleware.php';
date_default_timezone_set('America/Argentina/Buenos_Aires');

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
    $id = $args['id'];
    $productoPedido = ProductosPedidos::obtenerProductosPedidos($id);
    $payload = json_encode($productoPedido);
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerUnoCocina($request, $response, $args)
  {
    $sector = "cocina";
    $productoPedido = ProductosPedidos::obtenerProductosPedidosPorSector($sector);
    $payload = json_encode($productoPedido);
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerUnoCerveceria($request, $response, $args)
  {
    $sector = "cerveceria";
    $productoPedido = ProductosPedidos::obtenerProductosPedidosPorSector($sector);
    $payload = json_encode($productoPedido);
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerUnoBarra($request, $response, $args)
  {
    $sector = "barra";
    $productoPedido = ProductosPedidos::obtenerProductosPedidosPorSector($sector);
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
    $idEmpleado = $parametros['idEmpleado'];
    $pedidoMod = ProductosPedidos::obtenerProductosPedidos($id);
    if ($pedidoMod !== NULL && $idPedido !== NULL && $idProducto !== NULL && $id !== NULL) {
      $pedidoMod->idPedido = $idPedido;
      $pedidoMod->idProducto = $idProducto;
      $pedidoMod->idEmpleado = $idEmpleado;
      ProductosPedidos::modificarProductosPedidos($pedidoMod);
      $payload = json_encode(array("mensaje" => "ProductosPedidos modificados con exito"));
    } else {
      $payload = json_encode(array("mensaje" => "ERROR: No se pudo modificar ProductosPedidos"));
    }
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function ModificarEstadoYTiempoEstimado($request, $response, $args)
  {
    $parametros = $request->getParsedBody();
    $id = $args['id'];
    $estado = $parametros['estado'];
    $tiempoEstimado = $parametros['tiempoEstimado'];
    $usuario = $parametros['usuario'];
    $clave = $parametros['clave'];
    $usuarioObt = Usuario::obtenerUsuarioPorUsuarioYClave($usuario, $clave);
    $prodPedido = ProductosPedidos::obtenerProductosPedidosConSectorPorId($id, $usuarioObt);
    if ($prodPedido) {
      if ($estado === 'en preparacion') {
        $prodPedido->estado = $estado;
        $prodPedido->tiempoEstimado = $tiempoEstimado;
        $prodPedido->fechaTomado = date('Y-m-d H:i:s');
      } elseif ($estado === 'listo para servir') {
        $prodPedido->estado = $estado;
        $prodPedido->fechaEntregado = date('Y-m-d H:i:s');
      }elseif ($estado === 'cancelado'){
        $prodPedido->estado = $estado;
      }
      if ($usuarioObt !== NULL) {
        $prodPedido->idEmpleado = $usuarioObt->id;
        ProductosPedidos::modificarProductosPedidos($prodPedido);
        $pedido = Pedido::obtenerPedido($prodPedido->idPedido);
        $pedido->estado = $estado;
        Pedido::modificarPedido($pedido);
        $payload = json_encode(array("mensaje" => "Estado de ProductosPedidos modificado con exito"));
      } else {
        $payload = json_encode(array("mensaje" => "ERROR: Datos del usuario incorrectos"));
      }
    } else {
      $payload = json_encode(array("mensaje" => "ERROR: No puede modificar ProductosPedidos de otro sector"));
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

  public function TraerCantidadOperaciones($request, $response, $args)
  {
    $parametros = $request->getQueryParams();
    $fechaInicial = $parametros['fechaInicial'];
    $fechaFinal = $parametros['fechaFinal'];
    $operaciones = ProductosPedidos::obtenerTodosLasOperaciones($fechaInicial, $fechaFinal);
    $payload = json_encode(array("Operaciones" => $operaciones));
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerCantidadOperacionesPorSector($request, $response, $args)
  {
    $parametros = $request->getQueryParams();
    $sector = $parametros['sector'];
    $fechaInicial = $parametros['fechaInicial'];
    $fechaFinal = $parametros['fechaFinal'];
    $operaciones = ProductosPedidos::obtenerTodosLasOperacionesPorSector($sector, $fechaInicial, $fechaFinal);
    $payload = json_encode(array("Operaciones por sector" => $operaciones));
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerCantidadOperacionesPorEmpleado($request, $response, $args)
  {
    $parametros = $request->getQueryParams();
    $fechaInicial = $parametros['fechaInicial'];
    $fechaFinal = $parametros['fechaFinal'];
    $operaciones = ProductosPedidos::obtenerTodasLasOperacionesPorEmpleado($fechaInicial, $fechaFinal);
    $payload = json_encode(array("Operaciones por empleado" => $operaciones));
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerProductoMasVendido($request, $response, $args)
  {
    $parametros = $request->getQueryParams();
    $fechaInicial = $parametros['fechaInicial'];
    $fechaFinal = $parametros['fechaFinal'];
    $vendido = ProductosPedidos::obtenerElProductoMasVendido($fechaInicial, $fechaFinal);
    $payload = json_encode(array("Producto mas vendido" => $vendido));
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerProductoMenosVendido($request, $response, $args)
  {
    $parametros = $request->getQueryParams();
    $fechaInicial = $parametros['fechaInicial'];
    $fechaFinal = $parametros['fechaFinal'];
    $vendido = ProductosPedidos::obtenerElProductoMenosVendido($fechaInicial, $fechaFinal);
    $payload = json_encode(array("Producto menos vendido" => $vendido));
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerProductosFueraDeTiempo($request, $response, $args)
  {
    $parametros = $request->getQueryParams();
    $fechaInicial = $parametros['fechaInicial'];
    $fechaFinal = $parametros['fechaFinal'];
    $fueraTiempo = ProductosPedidos::obtenerProductosFueraDeTiempo($fechaInicial, $fechaFinal);
    $payload = json_encode(array("Productos fuera de tiempo" => $fueraTiempo));
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerProductosCancelados($request, $response, $args)
  {
    $parametros = $request->getQueryParams();
    $fechaInicial = $parametros['fechaInicial'];
    $fechaFinal = $parametros['fechaFinal'];
    $fueraTiempo = ProductosPedidos::obtenerProductosCancelados($fechaInicial, $fechaFinal);
    $payload = json_encode(array("Productos cancelados" => $fueraTiempo));
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
}
