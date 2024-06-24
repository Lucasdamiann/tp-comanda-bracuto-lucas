<?php
require_once './models/Encuesta.php';
require_once './interfaces/IApiUsable.php';

class EncuestaController extends Encuesta implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $mesa = $parametros['mesa'];
        $codigoMesa = $parametros['codigoMesa'];
        $restaurante = $parametros['restaurante'];
        $mozo = $parametros['mozo'];
        $cocinero = $parametros['cocinero'];
        $numeroPedido = $parametros['numeroPedido'];
        $resenia = $parametros['resenia'];
         
        $Encuesta = new Encuesta();
        $Encuesta->mesa = $mesa;
        $Encuesta->codigoMesa = $codigoMesa;
        $Encuesta->restaurante = $restaurante;
        $Encuesta->mozo = $mozo;
        $Encuesta->cocinero = $cocinero;
        $Encuesta->numeroPedido = $numeroPedido;
        $Encuesta->resenia = $resenia;
        $Encuesta->id = $Encuesta->crearEncuesta();

        $payload = json_encode(array("mensaje" => "Encuesta creada con exito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        $encuesta = $args['id'];
        $encuesta = Encuesta::obtenerEncuesta($encuesta);
        $payload = json_encode($encuesta);

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Encuesta::obtenerTodos();
        $payload = json_encode(array("listaEncuesta" => $lista));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id = $args['id'];
        $mesa = $parametros['mesa'];
        $codigoMesa = $parametros['codigoMesa'];
        $restaurante = $parametros['restaurante'];
        $mozo = $parametros['mozo'];
        $cocinero = $parametros['cocinero'];
        $numeroPedido = $parametros['numeroPedido'];
        $resenia = $parametros['resenia'];
        $encuestaMod = Encuesta::obtenerEncuesta($id);
        if ($encuestaMod !== NULL && $id !== NULL && $mesa !== NULL && $codigoMesa !== NULL && $restaurante !== NULL && $mozo !== NULL && $cocinero !== NULL && $numeroPedido !== NULL && $resenia !== NULL) {
            $encuestaMod->mesa = $mesa;
            $encuestaMod->codigoMesa = $codigoMesa;
            $encuestaMod->restaurante = $restaurante;
            $encuestaMod->mozo = $mozo;
            $encuestaMod->cocinero = $cocinero;
            $encuestaMod->numeroPedido = $numeroPedido;
            $encuestaMod->resenia = $resenia;
            Encuesta::modificarEncuesta($encuestaMod);
            $payload = json_encode(array("mensaje" => "Encuesta modificada con exito"));
        } else {
            $payload = json_encode(array("mensaje" => "ERROR: No se pudo modificar la Encuesta"));
        }
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $id = $args['id'];
        Encuesta::borrarEncuesta($id);
        $payload = json_encode(array("mensaje" => "Encuesta borrada con exito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerBuenosComentarios($request, $response, $args) {
        $comentarios = Encuesta::obtenerBuenosComentarios();
        $payload = json_encode(array("Mejores Encuestas" => $comentarios));
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerMalosComentarios($request, $response, $args) {
        $comentarios = Encuesta::obtenerMalosComentarios();
        $payload = json_encode(array("Peores Encuestas" => $comentarios));
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}
