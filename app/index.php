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

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();
$app = AppFactory::create();

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
    $group->get("/{id}", \UsuarioController::class . ':TraerUno');
    $group->post("[/]", \UsuarioController::class . ':CargarUno');
    $group->put("/{id}", \UsuarioController::class . ':ModificarUno');
    $group->delete("/{id}", \UsuarioController::class . ':BorrarUno');
});

$app->run();
