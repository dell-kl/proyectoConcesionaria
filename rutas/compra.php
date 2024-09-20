<?php
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;

    require '../vendor/autoload.php';

    $app->get('/compras', function(Request $request, Response $response, $args) { 
        global $db;

        try {
            //code...
            $sql = "";
        } catch (\Throwable $th) {
            //throw $th;
        }
    });

    $app->post('/compras', function(Request $request, Response $response, $args) { 
        global $db;
        $data = json_decode($request->getBody(), true);

        $resp = $response->getBody();
       
        try {
            //code...
            $sql =  "INSERT INTO Compra(compra_codigoCliente, compra_vehiculoCodigo, compra_matricula, compra_compraDetalleId)";
            $sql .= " ";

            $values = [];
            $params = [];

            $i = 0;
            foreach ( $data as $index => $value )
            {
                if ($i === 0)
                {
                    $values[] = "VALUES(?, ?, ?, ?)";
                    $i += 1;
                }
                else 
                {
                    $values[] = " (?, ?, ?, ?)";
                }

                $params[] = $value["compra_codigoCliente"];
                $params[] = $value["compra_vehiculoCodigo"];
                $params[] = $value["compra_matricula"];
                $params[] = $value["compra_compraDetalleId"];
            }
            $sql .= implode(",", $values);

            $consulta = $db->prepare($sql);
            $consulta->execute($params);

            $resp->write(true);
        } catch (\PDOException $th) {
            //throw $th;
            $resp->write(false);
            
        }
    });

    $app->delete('/compras/eliminar/{idCompra}', function(Request $request, Response $response, $args) { 
        global $db;
        $idCompra = $request->getAttribute("idCompra");

        try {
            //code...
            $sql = "DELETE FROM Compra WHERE compra_id = ?";

            $consulta = $db->prepare($sql);
            $consulta->bindParam(1, $idCompra);
            $consulta->execute();

            echo "eliminacion de compra correctamente";
        } catch (\Throwable $th) {
            echo "eliminacion de compra no se pudo realizar";
            //throw $th;
            echo $th;
        }
    });

    $app->put('/compras/actualizar/{idCompra}', function(Request $request, Response $response, $args) { 
        global $db;
        $idCompra = $request->getAttribute("idCompra");
        $data = json_decode($request->getBody(), true);

        try {
            //code...
            $sql =  "UPDATE Compra SET compra_codigoCliente = ?, compra_vehiculoCodigo = ?, compra_matricula = ?, compra_compraDetalleId = ? WHERE compra_id = ?";
            $consulta = $db->prepare($sql);
            $consulta->bindParam(1, $data["compra_codigoCliente"]);
            $consulta->bindParam(2, $data["compra_vehiculoCodigo"]);
            $consulta->bindParam(3, $data["compra_matricula"]);
            $consulta->bindParam(3, $data["compra_compraDetalleId"]);
            $consulta->bindParam(5, $idCompra);
            $consulta->execute();
            
            echo "actualizaciones realizadas con exito";
        } catch (\PDOException $th) {
            //throw $th;
            echo "actualizacoines canceladas por error";
            echo $th;
        }
    }); 

?>