<?php
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;

    require '../vendor/autoload.php';

    $app->Get('/vehiculos', function(Request $request, Response $response, $args) { 
        global $db;
        $data = $response->getBody();
        try {
            //code...
            $sql = "SELECT vehiculo_codigo, vehiculo_modelo, vehiculo_marca, vehiculo_color, vehiculo_ano, vehiculo_precio, vehiculo_puertas, vehiculo_cantidad FROM Vehiculo";
            $consulta = $db->prepare($sql);
            $consulta->execute();
            $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);

            if ( $resultado )
            {
                $resultado = json_encode($resultado);   
                $resp = $response->getBody();
                $resp->write($resultado);
                return $response->withStatus(200);
            }
            
            $data->write(json_encode(['respuesta' => 'vehiculos no encontrados']));
            return $response->withStatus(404);
        } catch (\Throwable $th) {
            //throw $th;
            $data->write(json_encode(['respuesta' => 'error servidor']));
            return $response->withStatus(500);
        }
    });

    $app->Get('/vehiculos/{codigo}', function(Request $request, Response $response, $args) { 
        global $db;
        $codigo = $request->getAttribute("codigo");
        $data = $response->getBody();
        try {
            //code...
            $sql = "SELECT vehiculo_codigo, vehiculo_modelo, vehiculo_marca, vehiculo_color, vehiculo_ano, vehiculo_precio, vehiculo_puertas, vehiculo_cantidad FROM Vehiculo WHERE vehiculo_codigo = ?";
            $consulta = $db->prepare($sql);
            $consulta->bindParam(1, $codigo);
            $consulta->execute();
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

            if ( $resultado )
            {
                $resultado = json_encode($resultado);      
                $resp = $response->getBody();
                $resp->write($resultado);
                return $response->withStatus(200);
            }

            $data->write(json_encode(['respuesta' => 'vehiculo no encontrado']));
            return $response->withStatus(404);
        } catch (\Throwable $th) {
            //throw $th;
            $data->write(json_encode(['respuesta' => 'error servidor']));
            return $response->withStatus(500);
        }
    });


    $app->post('/vehiculos/registrar', function(Request $request, Response $response, $args) { 
        global $db;
        
        $cuerpo = $response->getBody();
        try {
            $datos = $request->getParsedBody();
            $imagenVehiculo = $request->getUploadedFiles();

            if ( isset($datos['datos']) && isset($imagenVehiculo['imagenVehiculo']))
            {
                if (!empty($imagenVehiculo['imagenVehiculo']) && !empty($datos['datos']))
                {
                    $totalImagenesEncontradas = array_reduce($imagenVehiculo['imagenVehiculo'], function($carry, $item){
                        $nombreImagen = $item->getClientFilename();
                        $tipoImagen = $item->getClientMediaType();
                        $tamanoImagen = $item->getSize();
                        $bytesMaximo = 1073741824;
                      
                        if ( !empty($nombreImagen)  && ($tipoImagen === "image/png" || $tipoImagen === "image/jpg" || $tipoImagen === "image/jpeg") && $tamanoImagen <= $bytesMaximo)
                        {
                            $carry += 1;
                        }
                        return $carry;
                    });
                    
                    if ( $totalImagenesEncontradas === count($imagenVehiculo['imagenVehiculo']) )
                    {
                        //generaremos un codigo para el vehiculo
                        $codigo = md5(rand(0,10000));
                        $codigo = password_hash($codigo, PASSWORD_BCRYPT);
    
                        $data = json_decode($datos["datos"], true);

                        //code...
                        $sql =  "INSERT INTO Vehiculo(";
                        $sql .= "vehiculo_codigo,";
                        $sql .= "vehiculo_modelo,";
                        $sql .= "vehiculo_marca,";
                        $sql .= "vehiculo_color,";
                        $sql .= "vehiculo_ano,";
                        $sql .= "vehiculo_precio,";
                        $sql .= "vehiculo_puertas,";
                        $sql .= "vehiculo_cantidad";
                        $sql .= ") ";
                        $sql .= "VALUES(?, ?, ?, ?, ?, ?, ?, ?)";

                        try {
                            //code...
                            $consulta = $db->prepare($sql);
                            $consulta->bindParam(1, $codigo);
                            $consulta->bindParam(2, $data["vehiculo_modelo"] );
                            $consulta->bindParam(3, $data["vehiculo_marca"] );
                            $consulta->bindParam(4, $data["vehiculo_color"] );
                            $consulta->bindParam(5, $data["vehiculo_ano"] );
                            $consulta->bindParam(6, $data["vehiculo_precio"]);
                            $consulta->bindParam(6, $data["vehiculo_puertas"]);
                            $consulta->bindParam(8, $data["vehiculo_cantidad"] );
                            $consulta->execute();
    
                            // return $response->withStatus(200);
                        } catch (\Throwable $th) {
                            $data->write(json_encode(['respuesta' => $th]));
                            return $response->withStatus(500);
                        }
                                    
                        
                        //en este caso vamos a registrar una imagen...
                        $sql = "INSERT INTO ImagenVehiculo(imgVH_ruta, imgVH_fechaSubido) VALUES(:ruta, :fecha)";
                        $consulta = $db->prepare($sql);
                        $consulta->bindParam(1, "");
                        $consulta->bindParam(2, "");
                    }

                    $cuerpo->write(json_encode(['respuesta' => 'Tipo imagenes erroneo enviado']));
                    return $response->withStatus(409);
                }

                $cuerpo->write(json_encode(['respuesta' => 'parametros vacios enviados']));
                return $response->withStatus(409);
            }

            $cuerpo->write(json_encode(['respuesta' => 'parametros no definidos']));
            return $response->withStatus(409);

            
        } catch (\Throwable $th) {
            //throw $th;
            $data->write(json_encode(['respuesta' => $th]));
            return $response->withStatus(500);
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