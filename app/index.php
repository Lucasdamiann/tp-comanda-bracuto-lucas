<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

require_once './db/AccesoDatos.php';
require_once './controllers/UsuarioController.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';
// require_once './middlewares/Logger.php';


// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();
$app = AppFactory::create();

// $app->get('/hello', function ($request, $response, array $args) {
// 		$response->getBody()->write("Funciona!");
// return $response;
// });

// $app->get("/test", function(Request $request, Response $response, array $args){
//     $params = $request->getQueryParams();
//     $response->getBody()->write(json_encode($params));
//     return $response;
// });

$app->group("/Mesa", function (RouteCollectorProxy $group) {
    $group->get("[/]", \MesaController::class . ':TraerTodos');
    $group->get("/Mesa/{id}", \MesaController::class . ':TraerUno');
    $group->post("[/]", \MesaController::class . ':CargarUno');
    $group->put("/Mesa/{id}", \MesaController::class . ':ModificarUno');
    $group->delete("/Mesa/{id}", \MesaController::class . ':BorrarUno');
});
$app->group("/Pedido", function (RouteCollectorProxy $group) {
    $group->get("[/]", \PedidoController::class . ':TraerTodos');
    $group->get("/Pedido/{id}", \PedidoController::class . ':TraerUno');
    $group->post("[/]", \PedidoController::class . ':CargarUno');
    $group->put("/Pedido/{id}", \PedidoController::class . ':ModificarUno');
    $group->delete("/Pedido/{id}", \PedidoController::class . ':BorrarUno');
});
$app->group("/Producto", function (RouteCollectorProxy $group) {
    $group->get("[/]", \ProductoController::class . ':TraerTodos');
    $group->get("/Producto/{id}", \ProductoController::class . ':TraerUno');
    $group->post("[/]", \ProductoController::class . ':CargarUno');
    $group->put("/Producto/{id}", \ProductoController::class . ':ModificarUno');
    $group->delete("/Producto/{id}", \ProductoController::class . ':BorrarUno');
});
$app->group("/Usuario", function (RouteCollectorProxy $group) {
    $group->get("[/]", \UsuarioController::class . ':TraerTodos');
    $group->get("/Usuario/{id}", \UsuarioController::class . ':TraerUno');
    $group->post("[/]", \UsuarioController::class . ':CargarUno');
    $group->put("/Usuario/{id}", \UsuarioController::class . ':ModificarUno');
    $group->delete("/Usuario/{id}", \UsuarioController::class . ':BorrarUno');
});
// $app->get("/usuarios", function ($request, $response, array $args)
// {
//     //obtener usuarios
// });
// $app->get("/usuarios/{id}", function ($request, $response, array $args)
// {
//     //obtener usuario por id
// });
// $app->post("/usuarios", function ($request, $response, array $args)
// {
//     //crear usuario
// });
// $app->put("/usuarios/{id}", function ($request, $response, array $args)
// {
//     //actualizar un usuario por id
// });
// $app->delete("/usuarios/{id}", function ($request, $response, array $args)
// {
//     //borrar usuario por id
// });

$app->run();
