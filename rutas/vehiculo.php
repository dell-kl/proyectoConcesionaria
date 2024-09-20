<?php
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;

    require '../vendor/autoload.php';

    $app->Get('/vehiculos', function(Request $request, Response $response, $args) { 
        global $db;
        
        try {
            //code...
            $sql = "SELECT * FROM Vehiculo";
            $consulta = $db->prepare($sql);
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

    $app->Get('/vehiculos/{codigo}', function(Request $request, Response $response, $args) { 
        global $db;
        $codigo = $request->getAttribute("codigo");

        try {
            //code...
            $sql = "SELECT * FROM Vehiculo WHERE vehiculo_codigo = ?";
            $consulta = $db->prepare($sql);
            $consulta->bindParam(1, $codigo);
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


    $app->post('/vehiculos/registrar', function(Request $request, Response $response, $args) { 
        global $db;
        $data = json_decode($request->getBody(), true);

        try {
            //code...
            $sql =  "INSERT INTO Vehiculo(";
            $sql .= "vehiculo_codigo,";
            $sql .= "vehiculo_modelo,";
            $sql .= "vehiculo_marca,";
            $sql .= "vehiculo_color,";
            $sql .= "vehiculo_ano,";
            $sql .= "vehiculo_precio,";
            $sql .= "vehiculo_rutaImagen,";
            $sql .= "vehiculo_puertas";
            $sql .= ") ";
            $sql .= "VALUES(?, ?, ?, ?, ?, ?, ?, ?)";

            $consulta = $db->prepare($sql);
            $consulta->bindParam(1, $data["vehiculo_codigo"]);
            $consulta->bindParam(2, $data["vehiculo_modelo"] );
            $consulta->bindParam(3, $data["vehiculo_marca"] );
            $consulta->bindParam(4, $data["vehiculo_color"] );
            $consulta->bindParam(5, $data["vehiculo_ano"] );
            $consulta->bindParam(6, $data["vehiculo_precio"]);
            $consulta->bindParam(7, $data["vehiculo_rutaImagen"] );
            $consulta->bindParam(8, $data["vehiculo_puertas"] );
            $consulta->execute();
            
            echo "correcto";
        } catch (\Throwable $th) {
            //throw $th;
            echo "error";
        }
    });

    $app->put('/vehiculos/actualizar/{codigo}', function(Request $request, Response $response, $args) { 
        global $db;
        $codigo = $request->getAttribute("codigo");
        $data = json_decode($request->getBody(), true);

        try {
            //code...
            $sql =  "UPDATE Vehiculo";
            $sql .= " ";
            $sql .= "SET vehiculo_modelo = ?,";
            $sql .= "vehiculo_marca = ?,";
            $sql .= "vehiculo_color = ?,";
            $sql .= "vehiculo_placa = ?,";
            $sql .= "vehiculo_ano = ?,";
            $sql .= "vehiculo_precio = ?,";
            $sql .= "vehiculo_rutaImagen = ?,";
            $sql .= "vehiculo_puertas = ?";
            $sql .= " ";
            $sql .= "WHERE vehiculo_codigo = ?";

            $consulta = $db->prepare($sql);
            $consulta->bindParam(1,  $data["vehiculo_modelo"] );
            $consulta->bindParam(2,  $data["vehiculo_marca"] );
            $consulta->bindParam(3,  $data["vehiculo_color"] );
            $consulta->bindParam(4,  $data["vehiculo_placa"] );
            $consulta->bindParam(5,  $data["vehiculo_ano"] );
            $consulta->bindParam(6,  $data["vehiculo_precio"]);
            $consulta->bindParam(7,  $data["vehiculo_rutaImagen"] );
            $consulta->bindParam(8,  $data["vehiculo_puertas"] );
            $consulta->bindParam(9, $codigo);
            $consulta->execute();
            
            echo "vehiculo actualizado exitosamente";
        } catch (\Throwable $th) {
            //throw $th;
            echo "error al actualizar vehiculo";
        }
    });

    $app->delete('/vehiculos/eliminar/{codigo}', function(Request $request, Response $response, $args) { 
        global $db;
        $codigo = $request->getAttribute("codigo");

        try {
            //code...
            $sql = "DELETE FROM Vehiculo WHERE vehiculo_codigo = ?";
            $consulta = $db->prepare($sql);
            $consulta->bindParam(1, $codigo);
            $consulta->execute();
        
            echo "vehiculo eliminado exitosamente";
        } catch (\Throwable $th) {
            //throw $th;
            echo "error al eliminar vehiculo";
        }
    });
?>