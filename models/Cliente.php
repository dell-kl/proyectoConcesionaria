<?php


class Cliente {

    private $cliente_id;
    private $cliente_usuario;
    private $cliente_correo;
    private $cliente_celular;
    private $cliente_genero;
    private $cliente_provincia;
    private $cliente_archivoRuta;
    private $cliente_contrasena;
    private $cliente_rol;
    private $cliente_cedula;


    public function __construct(
        $id,
        $usuario,
        $correo,
        $celular,
        $genero,
        $provincia,
        $archivoRuta,
        $contrasena,
        $rol,
        $cedula
    )
    {
        $this->cliente_id = $id;
        $this->cliente_usuario = $usuario;
        $this->cliente_correo = $correo;
        $this->cliente_celular = $celular;
        $this->cliente_genero = $genero;
        $this->cliente_provincia = $provincia;
        $this->cliente_archivoRuta = $archivoRuta;
        $this->cliente_contrasena = $contrasena;
        $this->cliente_rol = $rol;
        $this->cliente_cedula = $cedula;
    }

    public function getId() {
        return $this->cliente_id;
    }

    public function getUsuario()
    {
        return $this->cliente_usuario;
    }

    public function getCorreo()
    {
        return $this->cliente_correo;
    }

    public function getCelular()
    {
        return $this->cliente_celular;
    }

    public function getGenero()
    {
        return $this->cliente_genero;
    }

    public function getProvincia()
    {
        return $this->cliente_provincia;
    }

    public function getArchivoRuta()
    {
        return $this->cliente_archivoRuta;
    }

    public function getContrasena()
    {
        return $this->cliente_contrasena;
    }

    public function getRol()
    {
        return $this->cliente_rol;
    }

    public function getCedula()
    {
        return $this->cliente_cedula;
    }
}
?>