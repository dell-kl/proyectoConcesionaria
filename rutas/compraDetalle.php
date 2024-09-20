<?php
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;

    require '../vendor/autoload.php';

    $app->Get('/comprasDetalle/cedulaCliente/{cedula}', function(Request $request, Response $response, $args) { 
        global $db;
        $cedula = $request->getAttribute("cedula");

        try {
            //code...
            $sql = "EXECUTE PCR_ReporteHistorial @dato = ?";

            $consulta = $db->prepare($sql);
            $consulta->bindParam(1, $cedula);
            $consulta->execute();
            $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);

            $resultado = json_encode($resultado);
            $resp = $response->getBody();
            $resp->write($resultado);
            
        } catch (\Throwable $th) {
            //throw $th;
            echo $th;
        }
    });

    $app->Get('/comprasDetalle/placa/{placa}', function(Request $request, Response $response, $args) { 
        global $db;
        $placa = $request->getAttribute("placa");

        try {
            //code...
            $sql = "EXECUTE PCR_ReporteHistorial @dato = ?, @tipo = ?";

            $consulta = $db->prepare($sql);
            $consulta->bindValue(1, $placa);
            $consulta->bindValue(2, "matricula");
            $consulta->execute();
            $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);

            $resultado = json_encode($resultado);
            $resp = $response->getBody();
            $resp->write($resultado);
            
        } catch (\Throwable $th) {
            //throw $th;
            echo $th;
        }
    });

    $app->Get('/comprasDetalle', function(Request $request, Response $response, $args) { 
        global $db;

        try {
            //code...
            $sql = "SELECT TOP 1 compraDetalle_id, compraDetalle_precio, compraDetalle_valorTotal, compraDetalle_cantidad, compraDetalle_comprobante FROM CompraDetalle ORDER BY compraDetalle_id DESC";

            $consulta = $db->prepare($sql);
            $consulta->execute();
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

            $resultado = json_encode($resultado);
       
            $resp = $response->getBody();
            $resp->write($resultado);

        } catch (\Throwable $th) {
            //throw $th;
            echo $th->getMessage();
        }
    });

    $app->Get('/comprasDetalle/all', function(Request $request, Response $response, $args) { 
        global $db;

        try {
            //code...
            $sql = "SELECT compraDetalle_id, compraDetalle_precio, compraDetalle_valorTotal, compraDetalle_cantidad, compraDetalle_comprobante FROM CompraDetalle ORDER BY compraDetalle_id DESC";

            $consulta = $db->prepare($sql);
            $consulta->execute();
            $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);

            $resultado = json_encode($resultado);
       
            $resp = $response->getBody();
            $resp->write($resultado);

        } catch (\Throwable $th) {
            //throw $th;
            echo $th->getMessage();
        }
    });


    $app->post('/comprasDetalle/registrar', function(Request $request, Response $response, $args) { 
        global $db;
        $solicitud = json_decode( $request->getBody() , true);
        $resp = $response->getBody();
        try {
            //code...
            $sql = "INSERT INTO CompraDetalle(compraDetalle_precio, compraDetalle_valorTotal, compraDetalle_cantidad, compraDetalle_comprobante)";
            $sql .= " ";
            $sql .= "VALUES(?, ?, ?, ?)";

            $consulta = $db->prepare($sql);
            $consulta->bindParam(1, $solicitud["compraDetalle_precio"]);
            $consulta->bindParam(2, $solicitud["compraDetalle_valorTotal"]);
            $consulta->bindParam(3, $solicitud["compraDetalle_cantidad"]);
            $consulta->bindParam(4, $solicitud["compraDetalle_comprobante"]);
            $consulta->execute();
            
            $resp->write(true);

        } catch (\Throwable $th) {
            //throw $th;
            $resp->write(false);
        }
    });

?>