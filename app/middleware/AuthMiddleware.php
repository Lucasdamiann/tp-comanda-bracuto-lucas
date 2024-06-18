<?php

require_once './utils/AutentificadorJWT.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class AuthMiddleware
{
    /*
     * Example middleware invokable class
     *
     * @param  ServerRequest  $request PSR-7 request
     * @param  RequestHandler $handler PSR-15 request handler
     *
     * @return Response
     */
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $header = $request->getHeaderLine('Authorization');
        if ($header) {
            $token = trim(explode("Bearer", $header)[1]);
        } else {
            $token = "";
        }

        try {
            AutentificadorJWT::VerificarToken($token);
            $response = $handler->handle($request);
        } catch (Exception $e) {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'ERROR: Hubo un error con el TO
            KEN'));
            $response->getBody()->write($payload);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function verificarToken(Request $request, RequestHandler $handler): Response
    {
        $header = $request->getHeaderLine('Authorization');
        if ($header) {
            $token = trim(explode("Bearer", $header)[1]);
        } else {
            $token = "";
        }

        try {
            AutentificadorJWT::VerificarToken($token);
            $response = $handler->handle($request);
        } catch (Exception $e) {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'ERROR: Hubo un error con el TOKEN'));
            $response->getBody()->write($payload);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function verificarPerfilMozo(Request $request, RequestHandler $handler): Response
    {
        $cargo = "mozo";
        $header = $request->getHeaderLine('Authorization');
        if ($header) {
            $token = trim(explode("Bearer", $header)[1]);
        } else {
            $token = "";
        }

        try {
            $data = AutentificadorJWT::ObtenerData($token);
            if ($data->cargo === "socio" || $data->cargo === $cargo) {
                $response = $handler->handle($request);
            } else {
                $response = new Response();
                $payload = json_encode(array('mensaje' => 'ERROR: No sos ' . $cargo));
                $response->getBody()->write($payload);
            }
        } catch (Exception $e) {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'ERROR: Hubo un error con el TOKEN'));
            $response->getBody()->write($payload);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function verificarPerfilCocinero(Request $request, RequestHandler $handler): Response
    {
        $cargo = "cocinero";
        $header = $request->getHeaderLine('Authorization');
        if ($header) {
            $token = trim(explode("Bearer", $header)[1]);
        } else {
            $token = "";
        }

        try {
            $data = AutentificadorJWT::ObtenerData($token);
            if ($data->cargo === "socio" || $data->cargo === $cargo) {
                $response = $handler->handle($request);
            } else {
                $response = new Response();
                $payload = json_encode(array('mensaje' => 'ERROR: No sos ' . $cargo));
                $response->getBody()->write($payload);
            }
        } catch (Exception $e) {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'ERROR: Hubo un error con el TOKEN'));
            $response->getBody()->write($payload);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }
    
    public static function verificarPerfilBartender(Request $request, RequestHandler $handler): Response
    {
        $cargo = "bartender";
        $header = $request->getHeaderLine('Authorization');
        if ($header) {
            $token = trim(explode("Bearer", $header)[1]);
        } else {
            $token = "";
        }

        try {
            $data = AutentificadorJWT::ObtenerData($token);
            if ($data->cargo === "socio" || $data->cargo === $cargo) {
                $response = $handler->handle($request);
            } else {
                $response = new Response();
                $payload = json_encode(array('mensaje' => 'ERROR: No sos ' . $cargo));
                $response->getBody()->write($payload);
            }
        } catch (Exception $e) {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'ERROR: Hubo un error con el TOKEN'));
            $response->getBody()->write($payload);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function verificarPerfilCervecero(Request $request, RequestHandler $handler): Response
    {
        $cargo = "cervecero";
        $header = $request->getHeaderLine('Authorization');
        if ($header) {
            $token = trim(explode("Bearer", $header)[1]);
        } else {
            $token = "";
        }

        try {
            $data = AutentificadorJWT::ObtenerData($token);
            if ($data->cargo === "socio" || $data->cargo === $cargo) {
                $response = $handler->handle($request);
            } else {
                $response = new Response();
                $payload = json_encode(array('mensaje' => 'ERROR: No sos ' . $cargo));
                $response->getBody()->write($payload);
            }
        } catch (Exception $e) {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'ERROR: Hubo un error con el TOKEN'));
            $response->getBody()->write($payload);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }
}
