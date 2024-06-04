<?php
require_once './models/Pedido.php';
require_once './interfaces/IApiUsable.php';

class PedidoController extends Pedido implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $estado = $parametros['estado'];
        $pedido = $parametros['pedido'];
        $numeroPedido = $parametros['numeroPedido'];
        $tiempoEstimado = $parametros['tiempoEstimado'];
        $codigoMesa = $parametros['codigoMesa'];
        $ventas = $parametros['ventas'];

        // Creamos el Pedido
        $ped = new Pedido();
        $ped->estado = $estado;
        $ped->pedido = $pedido;
        $ped->numeroPedido = $numeroPedido;
        $ped->tiempoEstimado = $tiempoEstimado;
        $ped->codigoMesa = $codigoMesa;
        $ped->ventas = $ventas;
        $ped->id = $ped->crearPedido();

        $payload = json_encode(array("mensaje" => "Pedido creado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos Pedido por estado
        $ped = $args['estado'];
        $pedido = Pedido::obtenerPedido($ped);
        $payload = json_encode($pedido);

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
        $parametros = $request->getParsedBody();

        $estado = $parametros['estado'];
        $pedido = $parametros['pedido'];
        $numeroPedido = $parametros['numeroPedido'];
        $tiempoEstimado = $parametros['tiempoEstimado'];
        $codigoMesa = $parametros['codigoMesa'];
        $ventas = $parametros['ventas'];
        $id = $parametros['id'];
        Pedido::modificarPedido($estado, $pedido, $numeroPedido, $tiempoEstimado, $codigoMesa, $ventas, $id);

        $payload = json_encode(array("mensaje" => "Pedido modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id = $parametros['id'];
        Pedido::borrarPedido($id);

        $payload = json_encode(array("mensaje" => "Pedido borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}