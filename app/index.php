<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

require_once './db/AccesoDatos.php';
require_once './controllers/UsuarioController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/PedidoController.php';
require_once './controllers/ProductosPedidosController.php';
require_once './controllers/MesaController.php';
require_once './middleware/AuthMiddleware.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();
$app = AppFactory::create();

$app->group("/Mesa", function (RouteCollectorProxy $group) {
    $group->get("[/]", \MesaController::class . ':TraerTodos');
    $group->get("/{id}", \MesaController::class . ':TraerUno');
    $group->post("[/]", \MesaController::class . ':CargarUno');
    $group->put("/{id}", \MesaController::class . ':ModificarUno');
    $group->delete("/{id}", \MesaController::class . ':BorrarUno');
})->add(new AuthMiddleware("socio"));
$app->group("/Pedido", function (RouteCollectorProxy $group) {
    $group->get("[/]", \PedidoController::class . ':TraerTodos');
    $group->get("/{id}", \PedidoController::class . ':TraerUno');
    $group->post("[/]", \PedidoController::class . ':CargarUno');
    $group->post("/Foto/{id}", \PedidoController::class . ':SacarFoto');
    $group->put("/{id}", \PedidoController::class . ':ModificarUno');
    $group->delete("/{id}", \PedidoController::class . ':BorrarUno');
})->add(new AuthMiddleware("socio"));
$app->group("/Producto", function (RouteCollectorProxy $group) {
    $group->get("[/]", \ProductoController::class . ':TraerTodos');
    $group->get("/{id}", \ProductoController::class . ':TraerUno');
    $group->post("[/]", \ProductoController::class . ':CargarUno');
    $group->put("/{id}", \ProductoController::class . ':ModificarUno');
    $group->delete("/{id}", \ProductoController::class . ':BorrarUno');
})->add(new AuthMiddleware("socio"));
$app->group("/ProductosPedidos", function (RouteCollectorProxy $group) {
    $group->get("[/]", \ProductosPedidosController::class . ':TraerTodos');
    $group->get("/{id}", \ProductosPedidosController::class . ':TraerUno');
    $group->post("[/]", \ProductosPedidosController::class . ':CargarUno');
    $group->put("/{id}", \ProductosPedidosController::class . ':ModificarUno');
    $group->delete("/{id}", \ProductosPedidosController::class . ':BorrarUno');
})->add(new AuthMiddleware("socio"))
  ->add(new AuthMiddleware("bartender"));
$app->group("/Usuario", function (RouteCollectorProxy $group) {
    $group->get("[/]", \UsuarioController::class . ':TraerTodos');
    $group->get("/{id}", \UsuarioController::class . ':TraerUno');
    $group->post("[/]", \UsuarioController::class . ':CargarUno');
    $group->put("/{id}", \UsuarioController::class . ':ModificarUno');
    $group->delete("/{id}", \UsuarioController::class . ':BorrarUno');
})->add(new AuthMiddleware("socio"));

$app->run();
