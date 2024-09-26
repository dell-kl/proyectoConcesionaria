<?php

class Vehiculo 
{
    private string $vehiculo_codigo;
    private string $vehiculo_modelo;
    private string $vehiculo_marca;
    private string $vehiculo_color;
    private string $vehiculo_ano;
    private float $vehiculo_precio;
    private int $vehiculo_puertas;
    private int $vehiculo_cantidad;

    public function __construct(
        $codigo = "",
        $modelo = "",
        $marca = "",
        $color = "",
        $ano = "",
        $precio = 0,
        $puertas = 0,
        $cantidad  = 0  
    )
    {
        $this->vehiculo_codigo = $codigo;
        $this->vehiculo_modelo = $modelo;
        $this->vehiculo_marca = $marca;
        $this->vehiculo_color = $color;
        $this->vehiculo_ano = $ano;
        $this->vehiculo_precio = $precio;
        $this->vehiculo_puertas = $puertas;
        $this->vehiculo_cantidad = $cantidad;
    }

    public function rellenarDatos($vehiculo){
        $this->setCodigo($vehiculo['vehiculo_codigo']);
        $this->setModelo($vehiculo['vehiculo_modelo']);
        $this->setMarca($vehiculo['vehiculo_marca']);
        $this->setColor($vehiculo['vehiculo_color']);
        $this->setAno($vehiculo['vehiculo_ano']);
        $this->setPrecio($vehiculo['vehiculo_precio']);
        $this->setPuertas($vehiculo['vehiculo_puertas']);
        $this->setCantidad($vehiculo['vehiculo_cantidad']);
    }

    public function verificarDuplicadosDatos($datos, $db)
    {
        $sql = "EXECUTE VerificarDatosVehiculo @modelo = :modelo, @marca = :marca";
        $consulta = $db->prepare($sql);

        if ( $datos["vehiculo_modelo"] === $this->getModelo() && $datos["vehiculo_marca"] === $this->getMarca() )
        {
            return true;
        }
        else if ( $datos["vehiculo_modelo"] === $this->getModelo() || $datos["vehiculo_marca"] !== $this->getMarca() )
        {
            //consulta en la db ... 
            $consulta->bindParam(1, "");
            $consulta->bindParam(2, $datos["vehiculo_marca"]);
        }
        else if ( $datos["vehiculo_modelo"] !== $this->getModelo() || $datos["vehiculo_marca"] === $this->getMarca() )
        {
            //consulta en la db ... 
            $consulta->bindParam(1, $datos["vehiculo_modelo"]);
            $consulta->bindParam(2, "");
        }

        $consulta->execute();
        $respuesta = $consulta->fetch(PDO::FETCH_ASSOC);

        if ( $respuesta["resultado"] === "existente" )
        {
            return false;
        }

        return true;
    }

    public function setCodigo($codigo) { $this->vehiculo_codigo = $codigo; }
    public function setModelo($modelo) { $this->vehiculo_modelo = $modelo; }
    public function setMarca($marca) { $this->vehiculo_marca = $marca; }
    public function setColor($color) { $this->vehiculo_color = $color; }
    public function setAno($ano) { $this->vehiculo_ano = $ano; }
    public function setPrecio($precio) { $this->vehiculo_precio = $precio; }
    public function setPuertas($puertas) { $this->vehiculo_puertas = $puertas; }
    public function setCantidad($cantidad) { $this->vehiculo_cantidad = $cantidad; }

    public function getCodigo() { return $this->vehiculo_codigo; }
    public function getModelo() { return $this->vehiculo_modelo; }
    public function getMarca() { return $this->vehiculo_marca; }
    public function getColor() { return $this->vehiculo_color; }
    public function getAno() { return $this->vehiculo_ano; }
    public function getPrecio() { return $this->vehiculo_precio; }
    public function getPuertas() { return $this->vehiculo_puertas; }
    public function getCantidad() { return $this->vehiculo_cantidad; }
}
?>