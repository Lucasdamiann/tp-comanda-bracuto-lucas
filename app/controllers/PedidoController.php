<?php
require_once './models/Pedido.php';
require_once './interfaces/IApiUsable.php';

class PedidoController extends Pedido implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $cliente = $parametros['cliente'];
        $estado = $parametros['estado'];
        $numeroPedido = $parametros['numeroPedido'];
        $codigoMesa = $parametros['codigoMesa'];

        $ped = new Pedido();
        $ped->cliente = $cliente;
        $ped->estado = $estado;
        $ped->numeroPedido = $numeroPedido;
        $ped->codigoMesa = $codigoMesa;
        $ped->id = $ped->crearPedido();

        $payload = json_encode(array("mensaje" => "Pedido creado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
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
        
        $cliente = $parametros['cliente'];
        $estado = $parametros['estado'];
        $numeroPedido = $parametros['numeroPedido'];
        $codigoMesa = $parametros['codigoMesa'];
        $id = $parametros['id'];
        Pedido::modificarPedido($estado, $cliente, $numeroPedido, $codigoMesa, $id);

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