 <?php

    use Psr\Http\Message\ServerRequestInterface as Request;
    use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
    use Slim\Psr7\Response;

    class AuthMiddleware
    {

        private $perfil;
        public function __construct($perfil)
        {
            $this->perfil = $perfil;
        }
        public function __invoke(Request $request, RequestHandler $requestHandler)
        {
            $response = new Response();

            $params = $request->getQueryParams();

            if (isset($params["credenciales"])) {
                $credenciales = $params["credenciales"];
                if ($credenciales === $this->perfil) {
                    $response = $requestHandler->handle($request);
                } else {
                    $response->getBody()->write(json_encode((array("ERROR => No sos" . $this->perfil))));
                }
            } else {
                $response->getBody()->write(json_encode((array("ERROR => No se ingresaron credenciales"))));
            }
        return $response;
        }
    }
