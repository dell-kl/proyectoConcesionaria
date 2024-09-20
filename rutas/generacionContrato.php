<?php
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;

    require '../vendor/autoload.php';

    $app->Get('/generacionContrato', function(Request $request, Response $response, $args) { 
        global $db;

        try {
            //code...
            $sql = "SELECT TOP 1 * FROM Compra as c  INNER JOIN Vehiculo as v ON c.compra_vehiculoCodigo = v.vehiculo_codigo INNER JOIN Cliente as cl ON c.compra_codigoCliente = cl.cliente_id ORDER BY c.compra_id DESC";

            $consulta = $db->prepare($sql);
            $consulta->execute();
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

            $resultado = json_encode($resultado);
            $resp = $response->getBody();
            $resp->write($resultado);
            
        } catch (\Throwable $th) {
            //throw $th;
            echo $th;
        }
    });

?>