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
require_once './controllers/ArchivosController.php';
require_once './controllers/EncuestaController.php';
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
$app->addBodyParsingMiddleware();

$app->group("/Mesa", function (RouteCollectorProxy $group) {
    $group->get("[/]", \MesaController::class . ':TraerTodos')->add(\AuthMiddleware::class . ':verificarPerfilMozo');
    $group->get("/{id}", \MesaController::class . ':TraerUno')->add(\AuthMiddleware::class . ':verificarPerfilMozo');
    $group->post("[/]", \MesaController::class . ':CargarUno')->add(\AuthMiddleware::class . ':verificarPerfilSocio');
    $group->put("/{id}", \MesaController::class . ':ModificarUno')->add(\AuthMiddleware::class . ':verificarPerfilSocio');
    $group->put("/cerrada/", \MesaController::class . ':ModificarEstadoCerrada')->add(\AuthMiddleware::class . ':verificarPerfilSocio');
    $group->put("/esperando/", \MesaController::class . ':ModificarEstadoClienteEsperando')->add(\AuthMiddleware::class . ':verificarPerfilMozo');
    $group->put("/comiendo/", \MesaController::class . ':ModificarEstadoClienteComiendo')->add(\AuthMiddleware::class . ':verificarPerfilMozo');
    $group->put("/pagando/", \MesaController::class . ':ModificarEstadoClientePagando')->add(\AuthMiddleware::class . ':verificarPerfilMozo');
    $group->delete("/{id}", \MesaController::class . ':BorrarUno')->add(\AuthMiddleware::class . ':verificarPerfilSocio');
})->add(\AuthMiddleware::class . ':verificarToken');

$app->group("/Pedido", function (RouteCollectorProxy $group) {
    $group->get("[/]", \PedidoController::class . ':TraerTodos')->add(\AuthMiddleware::class . ':verificarPerfilMozo');
    $group->get("/listos", \PedidoController::class . ':TraerTodosListosParaServir')->add(\AuthMiddleware::class . ':verificarPerfilMozo');
    $group->get("/pagar/{id}", \PedidoController::class . ':TraerMonto')->add(\AuthMiddleware::class . ':verificarPerfilMozo');
    $group->get("/todo/", \PedidoController::class . ':TraerTodosLosPedidosConTiempoRestante')->add(\AuthMiddleware::class . ':verificarPerfilSocio');
    $group->get("/{id}", \PedidoController::class . ':TraerUno')->add(\AuthMiddleware::class . ':verificarPerfilSocio');
    $group->get("/numero/", \PedidoController::class . ':TraerNumeroPedido')->add(\AuthMiddleware::class . ':verificarPerfilMozo');
    $group->post("[/]", \PedidoController::class . ':CargarUno')->add(\AuthMiddleware::class . ':verificarPerfilMozo');
    $group->post("/Foto/{id}", \PedidoController::class . ':SacarFoto')->add(\AuthMiddleware::class . ':verificarPerfilMozo');
    $group->put("/{id}", \PedidoController::class . ':ModificarUno')->add(\AuthMiddleware::class . ':verificarPerfilSocio');
    $group->delete("/{id}", \PedidoController::class . ':BorrarUno')->add(\AuthMiddleware::class . ':verificarPerfilSocio');
})->add(\AuthMiddleware::class . ':verificarToken');

$app->group("/Producto", function (RouteCollectorProxy $group) {
    $group->get("[/]", \ProductoController::class . ':TraerTodos');
    $group->get("/{id}", \ProductoController::class . ':TraerUno');
    $group->post("[/]", \ProductoController::class . ':CargarUno');
    $group->put("/{id}", \ProductoController::class . ':ModificarUno');
    $group->delete("/{id}", \ProductoController::class . ':BorrarUno');
})->add(\AuthMiddleware::class . ':verificarToken')
    ->add(\AuthMiddleware::class . ':verificarPerfilSocio');

$app->group("/ProductosPedidos", function (RouteCollectorProxy $group) {
    $group->get("[/]", \ProductosPedidosController::class . ':TraerTodos')->add(\AuthMiddleware::class . ':verificarPerfilSocio');
    $group->get("/{id}", \ProductosPedidosController::class . ':TraerUno')->add(\AuthMiddleware::class . ':verificarPerfilSocio');
    $group->get("/pendientes/cocina", \ProductosPedidosController::class . ':TraerUnoCocina')->add(\AuthMiddleware::class . ':verificarPerfilCocinero');
    $group->get("/pendientes/barra", \ProductosPedidosController::class . ':TraerUnoBarra')->add(\AuthMiddleware::class . ':verificarPerfilBartender');
    $group->get("/pendientes/cerveceria", \ProductosPedidosController::class . ':TraerUnoCerveceria')->add(\AuthMiddleware::class . ':verificarPerfilCervecero');
    $group->post("[/]", \ProductosPedidosController::class . ':CargarUno')->add(\AuthMiddleware::class . ':verificarPerfilMozo');
    $group->put("/{id}", \ProductosPedidosController::class . ':ModificarUno')->add(\AuthMiddleware::class . ':verificarPerfilSocio');
    $group->put("/tomar/cocina/{id}", \ProductosPedidosController::class . ':ModificarEstadoYTiempoEstimado')->add(\AuthMiddleware::class . ':verificarPerfilCocinero');
    $group->put("/tomar/barra/{id}", \ProductosPedidosController::class . ':ModificarEstadoYTiempoEstimado')->add(\AuthMiddleware::class . ':verificarPerfilBartender');
    $group->put("/tomar/cerveceria/{id}", \ProductosPedidosController::class . ':ModificarEstadoYTiempoEstimado')->add(\AuthMiddleware::class . ':verificarPerfilCervecero');
    $group->delete("/{id}", \ProductosPedidosController::class . ':BorrarUno')->add(\AuthMiddleware::class . ':verificarPerfilSocio');
})->add(\AuthMiddleware::class . ':verificarToken');

$app->group("/Usuario", function (RouteCollectorProxy $group) {
    $group->get("[/]", \UsuarioController::class . ':TraerTodos');
    $group->get("/{id}", \UsuarioController::class . ':TraerUno');
    $group->post("[/]", \UsuarioController::class . ':CargarUno');
    $group->put("/{id}", \UsuarioController::class . ':ModificarUno');
    $group->put("/suspender/{id}", \UsuarioController::class . ':SuspenderUno');
    $group->delete("/{id}", \UsuarioController::class . ':BorrarUno');
})->add(\AuthMiddleware::class . ':verificarToken')
    ->add(\AuthMiddleware::class . ':verificarPerfilSocio');

$app->group("/Auth", function (RouteCollectorProxy $group) {
    $group->post("/login", \UsuarioController::class . ':LogIn');
});

$app->group("/Consulta", function (RouteCollectorProxy $group) {
    $group->get("/tiemporestante/", \PedidoController::class . ':TraerTiempoRestante');    
});

$app->group("/Administrador", function (RouteCollectorProxy $group) {
    $group->get("/historial/", \UsuarioController::class . ':ConsultarIngresos');
    $group->get("/operaciones/", \ProductosPedidosController::class . ':TraerCantidadOperaciones');
    $group->get("/operaciones/sector/", \ProductosPedidosController::class . ':TraerCantidadOperacionesPorSector');
    $group->get("/operaciones/empleado/", \ProductosPedidosController::class . ':TraerCantidadOperacionesPorEmpleado');
    $group->get("/productos/masvendido/", \ProductosPedidosController::class . ':TraerProductoMasVendido');
    $group->get("/productos/menosvendido/", \ProductosPedidosController::class . ':TraerProductoMenosVendido');
    $group->get("/productos/fueratiempo/", \ProductosPedidosController::class . ':TraerProductosFueraDeTiempo');
    $group->get("/productos/cancelados/", \ProductosPedidosController::class . ':TraerProductosCancelados');
    $group->get("/mesas/masusada/", \MesaController::class . ':TraerMesaMasUsada');
    $group->get("/mesas/menosusada/", \MesaController::class . ':TraerMesaMenosUsada');
    $group->get("/mesas/masfacturo/", \MesaController::class . ':TraerMesaQueMasFacturo');
    $group->get("/mesas/menosfacturo/", \MesaController::class . ':TraerMesaQueMenosFacturo');
    $group->get("/mesas/mayorfactura/", \MesaController::class . ':TraerMesaConFacturaDeMayorImporte');
    $group->get("/mesas/menorfactura/", \MesaController::class . ':TraerMesaConFacturaDeMenorImporte');
    $group->get("/mesas/fechas/", \MesaController::class . ':TraerMesaLoQueFacturoEntreFechas');
    $group->get("/mejorcomentario/", \EncuestaController::class . ':TraerBuenosComentarios');
    $group->get("/peorcomentario/", \EncuestaController::class . ':TraerMalosComentarios');
})->add(\AuthMiddleware::class . ':verificarToken')
    ->add(\AuthMiddleware::class . ':verificarPerfilAdmin');

$app->group("/Encuesta", function (RouteCollectorProxy $group) {
    $group->post("[/]", \EncuestaController::class . ':CargarUno');
    $group->get("[/]", \EncuestaController::class . ':TraerTodos')->add(\AuthMiddleware::class . ':verificarToken')->add(\AuthMiddleware::class . ':verificarPerfilSocio');
    $group->get("/{id}", \EncuestaController::class . ':TraerUno')->add(\AuthMiddleware::class . ':verificarToken')->add(\AuthMiddleware::class . ':verificarPerfilSocio');
    $group->put("/{id}", \EncuestaController::class . ':ModificarUno')->add(\AuthMiddleware::class . ':verificarToken')->add(\AuthMiddleware::class . ':verificarPerfilSocio');
    $group->delete("/{id}", \EncuestaController::class . ':BorrarUno')->add(\AuthMiddleware::class . ':verificarToken')->add(\AuthMiddleware::class . ':verificarPerfilSocio');
});

$app->group("/Archivo", function (RouteCollectorProxy $group) {
    $group->post("/exportar", \ArchivosController::class . ':ExportarCSV');
    $group->post("/importar", \ArchivosController::class . ':ImportarCSV');
    $group->get("/crearPDF", \ArchivosController::class . ':CrearPDF');
})->add(\AuthMiddleware::class . ':verificarToken')
    ->add(\AuthMiddleware::class . ':verificarPerfilSocio');

$app->run();
