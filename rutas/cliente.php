<?php


use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Http\UploadedFile;

    require '../vendor/autoload.php';
    require '../models/Cliente.php';
    require './../tools/MoverImagenesTools.php';

    $app->Get('/clientes', function(Request $request, Response $response, $args) { 
        global $db;

        try {
            //code...
            $sql = "SELECT * FROM Cliente";

            $consulta = $db->prepare($sql);
            $consulta->execute();
            $clientes = $consulta->fetchAll(PDO::FETCH_ASSOC);

            $clientes = json_encode($clientes);
            
            $resp = $response->getBody();
            $resp->write($clientes);

        } catch (\Throwable $th) {
            //throw $th;
            echo $th;
        }
    });

    $app->Get('/clientes/{identificador}', function(Request $request, Response $response, $args) { 
        global $db;
        $id = $request->getAttribute("identificador");

        try {
            //code...
            $sql = "SELECT * FROM Cliente WHERE cliente_id = ?";

            $consulta = $db->prepare($sql);
            $consulta->bindValue(1, $id);
            $consulta->execute();
            $clientes = $consulta->fetchAll(PDO::FETCH_ASSOC);

            $clientes = json_encode($clientes);
            
            $resp = $response->getBody();
            $resp->write($clientes);

        } catch (\Throwable $th) {
            //throw $th;
            echo $th;
        }
    });

    $app->post('/clientes/registrar', function(Request $request, Response $response, $args) {
        global $db;
        //$data = json_decode($request->getBody(), true);
        $cuerpo = $response->getBody();
        try {

            $solicitudCliente = $request->getParsedBody();
            $imagenCliente = $request->getUploadedFiles();

            if ($solicitudCliente !== null && $imagenCliente !== null)
            {
                $data = json_decode($solicitudCliente["datos"], true);
                
                #informacion de la imagen el cual vamos a mandar a guardar.
                $nombreImagen = $imagenCliente["imagenCliente"]->getClientFilename();
                $tipoImagen = $imagenCliente["imagenCliente"]->getClientMediaType();
                $tamanoImagen = $imagenCliente["imagenCliente"]->getSize();
                
                $bytesMaximo = 1073741824; // aproximado un 1GB

                //vamos primero a realizar una verificacion de la existencia de los datos, para no tener duplicados.
                $sql = "EXECUTE VerificarDatosCliente @cedula = :cedula, @email = :email";

                $consulta = $db->prepare($sql);
                $consulta->bindParam(1, $data["cliente_cedula"]);
                $consulta->bindParam(2, $data["cliente_correo"]);
                $consulta->execute();

                $respuestaConsulta = $consulta->fetch(PDO::FETCH_ASSOC);


                if ($respuestaConsulta["respuesta"] === "inexistente")
                {
                    if ( $tamanoImagen <= $bytesMaximo && $tipoImagen === "image/png" )
                    {
                        $carpetaDestino = "./../wwwroot/imagenes/picturesClientes";
                        $resultadoMoverArchivo = moveUploadedFile($carpetaDestino, $imagenCliente["imagenCliente"]);
    
                        $carpetaDestino = "wwwroot/imagenes/picturesClientes/$resultadoMoverArchivo";
                        #vamos a guardar a continuacion los datos en nuestra base de datos.
    
                        $sql =  "INSERT INTO Cliente(cliente_usuario, cliente_correo, cliente_celular, cliente_genero, cliente_provincia, cliente_archivoRuta, cliente_contrasena, cliente_rol, cliente_cedula)";
                        $sql .= " ";
                        $sql .= "VALUES(:usuario, :correo, :celular, :genero, :provincia, :archivoRuta, :contrasena, :rol, :cedula)";
                        
                        $consulta = $db->prepare($sql);
                        $consulta->bindParam(1, $data['cliente_usuario']);
                        $consulta->bindParam(2, $data['cliente_correo']);
                        $consulta->bindParam(3, $data['cliente_celular']);
                        $consulta->bindParam(4, $data['cliente_genero']);
                        $consulta->bindParam(5, $data['cliente_provincia']);
                        $consulta->bindParam(6, $carpetaDestino);
                        $consulta->bindParam(7, $data['cliente_contrasena']);
                        $consulta->bindParam(8, $data['cliente_rol']);
                        $consulta->bindParam(9, $data['cliente_cedula']);
                        $consulta->execute();
            
                        $cuerpo->write(json_encode(['respuesta' => "registrado exitosamente"]));
                        $response->withStatus(200);
    
                    }
                }
                else 
                {
                    $cuerpo->write(json_encode(['respuesta' => "duplicacion datos"]));
                    return $response->withStatus(409);
                }
            }
        } catch (PDOException $th) {
            $response->withStatus(500);
            $cuerpo->write($th);
        }
    });

    $app->post('/clientes/registrar/app', function(Request $request, Response $response, $args) {
        global $db;
        $data = json_decode($request->getBody(), true);
        $respuesta = $response->getBody();
        try {
            // el apartado para decodificar mi imagen....
            $imagenProceso = $data["cliente_archivoRuta"];
            $imagenCodificada = $imagenProceso[0];
            $imagenNombre = $imagenProceso[1];

            $decodificarImagen = base64_decode($imagenCodificada);
            $urlGuardar = "/source/picturesClientes/" . $imagenNombre;

            //vamos a mover la imagen dentro de la carpeta de la api
            if ( file_put_contents($urlGuardar, $decodificarImagen) )
            {
                //el apartado para guardar la informacion en la base ded datos
                $sql =  "INSERT INTO Cliente(cliente_usuario, cliente_correo, cliente_celular, cliente_genero, cliente_provincia, cliente_archivoRuta, cliente_contrasena, cliente_rol, cliente_cedula)";
                $sql .= " ";
                $sql .= "VALUES(:usuario, :correo, :celular, :genero, :provincia, :archivoRuta, :contrasena, :rol, :cedula)";
                
                $consulta = $db->prepare($sql);
                $consulta->bindParam(1, $data['cliente_usuario']);
                $consulta->bindParam(2, $data['cliente_correo']);
                $consulta->bindParam(3, $data['cliente_celular']);
                $consulta->bindParam(4, $data['cliente_genero']);
                $consulta->bindParam(5, $data['cliente_provincia']);
                $consulta->bindParam(6, $urlGuardar);
                $consulta->bindParam(7, $data['cliente_contrasena']);
                $consulta->bindParam(8, $data['cliente_rol']);
                $consulta->bindParam(9, $data['cliente_cedula']);
                $consulta->execute();
    
                $respuesta->write("correcto");
            }
            else 
            {
                
                $respuesta->write("error");
            }
        } catch (PDOException $th) {
            $respuesta->write("error");
            echo "<pre>";
            var_dump($th);
            echo "</pre>";
           echo "error";
        }
    });

    $app->post('/clientes/actualizar/{id}', function(Request $request, Response $response, $args) {
        global $db;
        $id = $request->getAttribute("id");
        $cuerpo = $response->getBody();
        try {
            $sql = "SELECT cliente_id, cliente_usuario, cliente_correo, cliente_celular, cliente_genero, cliente_provincia, cliente_archivoRuta, cliente_contrasena, cliente_rol, cliente_cedula FROM Cliente WHERE cliente_id = :id";
            $consulta = $db->prepare($sql);
            $consulta->bindParam(1, $id);
            $consulta->execute();
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

            if ( $resultado )
            {
                $cliente = new Cliente(
                    $resultado["cliente_id"],
                    $resultado["cliente_usuario"],
                    $resultado["cliente_correo"],
                    $resultado["cliente_celular"],
                    $resultado["cliente_genero"],
                    $resultado["cliente_provincia"],
                    $resultado["cliente_archivoRuta"],
                    $resultado["cliente_contrasena"],
                    $resultado["cliente_rol"],
                    $resultado["cliente_cedula"],
                );

                $solicitudCliente = $request->getParsedBody();
                $imagenCliente = $request->getUploadedFiles();

                if ( $solicitudCliente !== null && !empty($imagenCliente["imagenCliente"]->getClientFilename()) )
                {
                    $data = json_decode($solicitudCliente["datos"], true);
    
                    $PuedeActualizar = false;

                    if ( $data["cliente_cedula"] === $cliente->getCedula() && $data["cliente_correo"] === $cliente->getCorreo() )
                    {
                        $PuedeActualizar = true;
                    }

                    if ( $data["cliente_cedula"] !== $cliente->getCedula() && $data["cliente_correo"] !== $cliente->getCorreo() )
                    {
                        //vamos a verificar de que no exista cedula o correos electronicos repetidos.
                        $respuestaConsulta = VerificarExistenciaDeDatos($db, $data["cliente_cedula"], $data["cliente_correo"]);

                        if ( $respuestaConsulta === "inexistente" )
                        {
                            $PuedeActualizar = true;
                        }
                    }

                    if ( $data["cliente_cedula"] === $cliente->getCedula() && $data["cliente_correo"] !== $cliente->getCorreo() )
                    {

                        $respuestaConsulta = VerificarExistenciaDeDatos($db, "", $data["cliente_correo"]);

                        if ( $respuestaConsulta === "inexistente" )
                        {
                            $PuedeActualizar = true;
                        }
                    }

                    if ( $data["cliente_cedula"] !== $cliente->getCedula() && $data["cliente_correo"] === $cliente->getCorreo() )
                    {
                        $respuestaConsulta = VerificarExistenciaDeDatos($db, $data["cliente_correo"], "");

                        if ( $respuestaConsulta === "inexistente" )
                        {
                            $PuedeActualizar = true;
                        }
                    }

                    if ( $PuedeActualizar )
                    {
                        //vamos a verificar la parte de la actualizacion de las imagenes que tenemos que realizar. 
                        $tipoImagen = $imagenCliente["imagenCliente"]->getClientMediaType();
                        $tamanoImagen = $imagenCliente["imagenCliente"]->getSize();
                        $bytesMaximo = 1073741824; // aproximado un 1GB

                        if ( $tamanoImagen <= $bytesMaximo && $tipoImagen === "image/png" )
                        {
                            $carpetaDestino = "./../wwwroot/imagenes/picturesClientes";
                            $resultadoMoverArchivo = moveUploadedFile($carpetaDestino, $imagenCliente["imagenCliente"]);
        
                            $carpetaDestino = "wwwroot/imagenes/picturesClientes/$resultadoMoverArchivo";

                            $sql =  "UPDATE Cliente SET cliente_usuario = ?, cliente_correo = ?, cliente_celular = ?, cliente_genero = ?, cliente_provincia = ?, cliente_archivoRuta = ?, cliente_contrasena = ?, cliente_rol = ?, cliente_cedula = ? WHERE cliente_id = ?";
                
                            $consulta = $db->prepare($sql);
                            $consulta->bindParam(1, $data['cliente_usuario']);
                            $consulta->bindParam(2, $data['cliente_correo']);
                            $consulta->bindParam(3, $data['cliente_celular']);
                            $consulta->bindParam(4, $data['cliente_genero']);
                            $consulta->bindParam(5, $data['cliente_provincia']);
                            $consulta->bindParam(6, $carpetaDestino);
                            $consulta->bindParam(7, $data['cliente_contrasena']);
                            $consulta->bindParam(8, $data['cliente_rol']);
                            $consulta->bindParam(9, $data['cliente_cedula']);
                            $consulta->bindParam(10, $id);
                            $consulta->execute();
    
                            $cuerpo->write(json_encode(['respuesta' => "actualizado correctamente"]));
                            return $response->withStatus(200);
                        }

                    }

                    $cuerpo->write(json_encode(['respuesta' => "duplicacion datos"]));
                    return $response->withStatus(409);
                }
                
                $cuerpo->write(json_encode(['respuesta' => "datos no procesables"]));
                return $response->withStatus(500);
            
            }
            
            $cuerpo->write(json_encode(['respuesta' => "usuario inexistente"]));
            return $response->withStatus(404);
        
        } catch (PDOException $th) {
           echo "Posiblemente no exista el registro a querer actualizar.";
        }
    });

    $app->delete('/clientes/eliminar/{id}', function(Request $request, Response $response, $args) {
        global $db;
        
        $id = $request->getAttribute("id");
        $cuerpo = $response->getBody();

        try {
            $registroCliente = TraerDatosCliente($db,$id);

            if ( $registroCliente )
            {
                //code...
                $sql =  "DELETE FROM Cliente WHERE cliente_id = ?";
    
                $consulta = $db->prepare($sql);
                $consulta->bindParam(1, $id);
                $consulta->execute();
    
                $cuerpo->write(json_encode(['respuesta' => "usuario eliminado"]));
                return $response->withStatus(200);
            }

            $cuerpo->write(json_encode(['respuesta' => "usuario inexistente"]));
            return $response->withStatus(404);
        } catch (PDOException $th) {
            $cuerpo->write(json_encode(['respuesta' => "error del servidor"]));
            return $response->withStatus(500);
        }
    });


    $app->get("/clientes/imagenes/{id}", function(Request $request, Response $response, $args) {
        global $db;
        $id = $request->getAttribute("id");
        $data = $response->getBody();
        try {
            $sql = "SELECT cliente_archivoRuta FROM Cliente WHERE cliente_id = ?";

            $consulta = $db->prepare($sql);
            $consulta->bindParam(1, $id);
            $consulta->execute();
            $respuesta = $consulta->fetch(PDO::FETCH_ASSOC);

            if ( $respuesta )
            {
                $clienteArchivoRuta = $respuesta["cliente_archivoRuta"];
                $rutaArchivo = "./../$clienteArchivoRuta";

                if ( file_exists($rutaArchivo) )
                {
                    //vamos a tener que enviar la imagen como respuesta... 
                    $imagen = file_get_contents($rutaArchivo);
                    
                    if ( $imagen )
                    {
                        $data->write($imagen);
                        return $response->withHeader('Content-Type', mime_content_type($rutaArchivo));
                    }
                    
                    $data->write(json_encode(['respuesta' => 'error procesar imagen']));
                    return $response->withStatus(500);
                    
                }
                else {
                    $data->write(json_encode(['respuesta' => 'imagen no encontrada']));
                    return $response->withStatus(404);
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    });


    function VerificarExistenciaDeDatos($db, $cedula, $correo)
    {
        $sql = "EXECUTE VerificarDatosCliente @cedula = :cedula, @email = :email";

        $consulta = $db->prepare($sql);
        $consulta->bindParam(1, $cedula);
        $consulta->bindParam(2, $correo);
        $consulta->execute();

        $respuestaConsulta = $consulta->fetch(PDO::FETCH_ASSOC);

        return $respuestaConsulta;
    }

    function TraerDatosCliente($db, $id)
    {
        $sql = "SELECT cliente_id, cliente_usuario, cliente_correo, cliente_celular, cliente_genero, cliente_provincia, cliente_archivoRuta, cliente_contrasena, cliente_rol, cliente_cedula FROM Cliente WHERE cliente_id = :id";
        $consulta = $db->prepare($sql);
        $consulta->bindParam(1, $id);
        $consulta->execute();
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        return $resultado;
    }
?>