<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Models\Cliente;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/db.php';

$app = new \Slim\App();
$cn = new DbConexion();
$db = $cn->conexion_baseDatos();


require_once __DIR__ . '/../rutas/compra.php';
require_once __DIR__ . '/../rutas/cliente.php';
require_once __DIR__ . '/../rutas/compraDetalle.php';
require_once __DIR__ . '/../rutas/vehiculo.php';
require_once __DIR__ . '/../rutas/generacionContrato.php';

$app->run();

?>